<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_stock'])){
	$idp = $_POST['producto'];
	$cantidad = $_POST['entrada'];
	$cantidad = (int) $cantidad;
	date_default_timezone_set('America/Lima');
	$fecha = date('Y-m-d H:i:s');
	$insertmov = $conn->prepare("INSERT into movimientos (idProducto,fecha, cantidad, tipo) VALUES (?,?,?,?)");
	if($insertmov->execute([$idp, $fecha, $cantidad, 'entrada'])){
		$updateprod = $conn->prepare("UPDATE producto SET stock = stock + ? WHERE id = ?");
		if ($updateprod->execute([$cantidad, $idp])){
			$message[]='Ingreso registrado correctamente';
		}
	};
}


	$select_movimiento = $conn->prepare("SELECT m.id, m.fecha, p.nombre, m.cantidad, m.tipo FROM movimientos m INNER JOIN producto p ON m.idProducto = p.id ORDER BY m.fecha DESC");
	$select_movimiento->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<title>H&C TIENDA</title>
	<link rel="icon" type="image/jpg" href="../images/logohyc.jpg">

   	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

  	<link rel="stylesheet" href="../css/custom.css">
   	<link rel="stylesheet" href="../css/bootstrap.min.css">
   
	<!--google fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

	<!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">

</head>
<body>

<?php include '../components/sliderbar.php'; ?>


<div class="wrapper">
  	<div class="main-content">
		<div class="row">
			<div class="col-md-12">
				<div class="table-wrapper">
          			<div class="table-title">
            			<div class="row">
              				<div class="col-sm-6 p-0 d-flex justify-content-lg-start justify-content-center">
								<h2 class="ml-lg-2">Entradas y salidas de productos</h2>
              				</div>
							<div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
								<?php if ($rol == '3') : ?>
								<a href="#moreEmployeeModal" class="btn btn-success" data-toggle="modal">
								<i class="material-icons">&#xE147;</i> <span>Añadir</span></a>
								<a href="../admin/movimientosexcel.php" class="btn btn-success" >
								<i class="material-icons">table_rows</i> <span>EXCEL</span></a>
								<a href="../fpdf/movimientospdf.php" target="_blank" class="btn btn-danger" >
								<i class="material-icons">picture_as_pdf</i> <span>PDF</span></a>
								
								<?php endif; ?>
              				</div>
            			</div>
          			</div>
    
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Id</th>
								<th>Fecha</th>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Tipo</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($select_movimiento->rowCount() > 0){
									while($fetch_movimiento = $select_movimiento->fetch(PDO::FETCH_ASSOC)){ 	
									
							?>
								<tr>
									<td><?= $fetch_movimiento['id']; ?></td>
									<td><?= $fetch_movimiento['fecha']; ?></td>
									<td><?= $fetch_movimiento['nombre']; ?></td>
									<td><?= $fetch_movimiento['cantidad']; ?></td>	
									<td><?= $fetch_movimiento['tipo']; ?></td>
									<th></th>
								</tr>

							<?php
							}
							}else{
								if ($_SERVER['REQUEST_METHOD'] === 'POST') {
									echo '<p class="empty">Producto no encontrado</p>';
								} else {
									echo '<p class="empty">No hay movimientos de productos</p>';
								}
							}
						?>
						</tbody>
					</table>
  				</div>
			</div>	

			<!-- More Modal HTML -->
			<div id="moreEmployeeModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form action="" method="post" enctype="multipart/form-data">
							<div class="modal-header">
								<h4 class="modal-title">Agregar ingreso</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label>Nombre</label>
									<select name="producto" class="form-control" required>
										<?php
											$select_producto = $conn->prepare("SELECT id,nombre FROM producto");
											$select_producto->execute();
											$productos = $select_producto->fetchAll();

											foreach ($productos as $producto) {
												$id = $producto['id'];
												$nombre = $producto['nombre'];
												if($nombre == $fetch_producto['producto']){
													echo "<option value=\"$id\" selected>$nombre</option>";
												} else {
													echo "<option value=\"$id\">$nombre</option>";
												}
											}
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Stock</label>
									<input name="entrada" type="text" class="form-control" required>
									
								</div>								
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
								<input type="submit" class="btn btn-danger" name="add_stock" value="Añadir">
							</div>
						</form>
					</div>
				</div>
			</div>	

		</div>
	</div>
</div>

<!--
<section class="show-products">

   <h1 class="heading">Productos</h1>

   <section class="flex">
      <nav class="product-search">
         <form method="POST">
            <input type="text" class="search-input" name="search-input" placeholder="Buscar producto...">
            <button type="submit">Buscar</button>
         </form>
         <select class="category-select" onchange="location = this.value">
            <option value="" selected disabled>Seleccionar categoría</option>
            <option value="categoria_producto.php?category=Almohada">Almohadas</option>
            <option value="categoria_producto.php?category=Edredon">Edredones</option>
            <option value="categoria_producto.php?category=Frazada">Frazadas</option>
            <option value="categoria_producto.php?category=Fundas">Fundas</option>
            <option value="categoria_producto.php?category=Sabanas">Sábanas</option>
            <option value="categoria_producto.php?category=Otros">Otros</option>
         </select>
      </nav>
   </section>

   <div class="box-container">

   <?php
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            // Truncar el nombre si excede los 40 caracteres
            $product_name = strlen($fetch_products['nombre']) > 30 ? substr($fetch_products['nombre'], 0, 30) . '...' : $fetch_products['nombre'];
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['imagen1']; ?>" alt="">
      <div class="name"><?= $fetch_products['nombre']; ?></div>
      <div class="price">$<span><?= $fetch_products['precio']; ?></span>/-</div>
      <div class="details"><span><?= $fetch_products['detalles']; ?></span></div>
      <div class="flex-btn">
         <a href="Editar_producto.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Editar</a>
         <a href="productos.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('¿Seguro que desea eliminar este producto?');">Eliminar</a>
      </div>
   </div>
   <?php
         }
      }else{
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<p class="empty">Producto no encontrado</p>';
         } else {
            echo '<p class="empty">No hay productos</p>';
         }
      }
   ?>
   
   </div>

</section> -->

<script src="../js/admin_script.js"></script>

   
</body>
</html>