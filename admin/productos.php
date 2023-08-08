<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

	$name = $_POST['name'];
	$name = filter_var($name, FILTER_SANITIZE_STRING);
	$price = $_POST['price'];
	$price = filter_var($price, FILTER_SANITIZE_STRING);
	$details = $_POST['details'];
	$details = filter_var($details, FILTER_SANITIZE_STRING);
	$category = $_POST['category'];
	$stock = $_POST['stock'];
	$stock = filter_var($stock, FILTER_SANITIZE_STRING);
 
	$image_01 = $_FILES['image_01']['name'];
	$image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
	$image_size_01 = $_FILES['image_01']['size'];
	$image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
	$image_folder_01 = '../uploaded_img/'.$image_01;
 
	$image_02 = $_FILES['image_02']['name'];
	$image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
	$image_size_02 = $_FILES['image_02']['size'];
	$image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
	$image_folder_02 = '../uploaded_img/'.$image_02;
 
	$image_03 = $_FILES['image_03']['name'];
	$image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
	$image_size_03 = $_FILES['image_03']['size'];
	$image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
	$image_folder_03 = '../uploaded_img/'.$image_03;
 
	$select_products = $conn->prepare("SELECT * FROM `producto` WHERE nombre = ?");
	$select_products->execute([$name]);
 
	if($select_products->rowCount() > 0){
	   $message[] = 'Ya existe un producto con el mismo nombre';
	}else{
	   $insert_products = $conn->prepare("INSERT INTO `producto`(nombre, detalles, precio, stock, idcat, imagen1, imagen2, imagen3) VALUES(?,?,?,?,?,?,?,?)");
	   $insert_products->execute([$name, $details, $price, $stock, $category, $image_01, $image_02, $image_03]);
	   if($insert_products){
		  if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
			 $message[] = 'Imagen demasiada pesada';
		  }else{
			 move_uploaded_file($image_tmp_name_01, $image_folder_01);
			 move_uploaded_file($image_tmp_name_02, $image_folder_02);
			 move_uploaded_file($image_tmp_name_03, $image_folder_03);
			 $message[] = 'Producto añadido';
		  }
 
	   }
 
	}  
 
 }
 
if (isset($_POST['actualizar_product'])){
	$name = $_POST['name'];
	$name = filter_var($name, FILTER_SANITIZE_STRING);
	$price = $_POST['price'];
	$price = filter_var($price, FILTER_SANITIZE_STRING);
	$details = $_POST['details'];
	$details = filter_var($details, FILTER_SANITIZE_STRING);
	$category = $_POST['category'];
	$stock = $_POST['stock'];
	$stock = filter_var($stock, FILTER_SANITIZE_STRING);
	$idprod = $_POST['idprod'];

	if (isset($_FILES['image_01']) && $_FILES['image_01']['error'] === UPLOAD_ERR_OK) {
		// Aquí puedes realizar las acciones para procesar la imagen
		$image_01 = $_FILES['image_01']['name'];
		$image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
		$image_size_01 = $_FILES['image_01']['size'];
		$image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
		$previos_image_01 = '../uploaded_img/'.$image_01;
		$image_folder_01 = $previos_image_01;
		
		if ($image_size_01 > 2000000) {
			$message[] = 'Imagen demasiado pesada';
			$image_01 = isset($_POST['or_image1']) ? $_POST['or_image1'] : '';
		}else {
			if (file_exists($previos_image_01)){
				unlink($previos_image_01);
			}
			if($image_size_01 <= 2000000){
				move_uploaded_file($image_tmp_name_01, $image_folder_01);
			}	
		}   
	} else {
		$image_01 = isset($_POST['or_image1']) ? $_POST['or_image1'] : '';
	}

	if (isset($_FILES['image_02']) && $_FILES['image_02']['error'] === UPLOAD_ERR_OK) {
		// Aquí puedes realizar las acciones para procesar la imagen
		$image_02 = $_FILES['image_02']['name'];
		$image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
		$image_size_02 = $_FILES['image_02']['size'];
		$image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
		$previos_image_02 = '../uploaded_img/'.$image_02;
		$image_folder_02=$previos_image_02;
		if($image_size_02 > 2000000){
			$message[] = 'Imagen demasiada pesada';
			$image_02 = isset($_POST['or_image2']) ? $_POST['or_image2'] : '';
		 }else {
			if (file_exists($previos_image_02)){
				unlink($previos_image_02);
			}
			if($image_size_02 <= 2000000){
				move_uploaded_file($image_tmp_name_02, $image_folder_02);
			}	
		}   
	} else {
		$image_02 = isset($_POST['or_image2']) ? $_POST['or_image2'] : '';
	}

	if (isset($_FILES['image_03']) && $_FILES['image_03']['error'] === UPLOAD_ERR_OK) {
		// Aquí puedes realizar las acciones para procesar la imagen
		$image_03 = $_FILES['image_03']['name'];
		$image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
		$image_size_03 = $_FILES['image_03']['size'];
		$image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
		$previos_image_03 = '../uploaded_img/'.$image_03;
		$image_folder_03=$previos_image_03;
		if($image_size_03 > 2000000){
			$message[] = 'Imagen demasiada pesada';
			$image_03 = isset($_POST['or_image3']) ? $_POST['or_image3'] : '';
		 }else {
			if (file_exists($previos_image_03)){
				unlink($previos_image_03);
			}
			if($image_size_03 <= 2000000){
				move_uploaded_file($image_tmp_name_03, $image_folder_03);
			}	
		}   
	} else {
		$image_03 = isset($_POST['or_image3']) ? $_POST['or_image3'] : '';
	}
	   $update_products = $conn->prepare("UPDATE `producto` SET nombre = ?, detalles = ?, precio = ?, stock = ?, idcat = ?, imagen1 = ?, imagen2 = ?, imagen3 = ? WHERE id = ?");
	   $update_products->execute([$name, $details, $price, $stock, $category, $image_01, $image_02, $image_03, $idprod]);
	   if($update_products){
		$message[] = 'El producto se actualizó correctamente';
	   }
};

if(isset($_POST['delete'])){

   $delete_id = $_POST['idprod'];
   $delete_product_image = $conn->prepare("SELECT * FROM `producto` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['imagen1']);
   unlink('../uploaded_img/'.$fetch_delete_image['imagen2']);
   unlink('../uploaded_img/'.$fetch_delete_image['imagen3']);
   $delete_product = $conn->prepare("DELETE FROM `producto` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   
   header('location:productos.php');
}


   $select_products = $conn->prepare("SELECT p.id, p.nombre, p.detalles, p.precio, p.stock, p.imagen1, p.imagen2, p.imagen3, c.nombre AS categoria FROM producto p INNER JOIN categoria c ON p.idcat = c.id");
   $select_products->execute();


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
								<h2 class="ml-lg-2">PRODUCTOS</h2>
              				</div>
              				<div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
							  	<?php if ($rol == '3') : ?>
								<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
								<i class="material-icons">&#xE147;</i> <span>Añadir</span></a>
								
								<a href="../admin/productosexcel.php" class="btn btn-success">
								<i class="material-icons">table_rows</i> <span>EXCEL</span></a>
								
								<a href="../fpdf/productospdf.php" target="_blank" class="btn btn-danger">
								<i class="material-icons">picture_as_pdf</i> <span>PDF</span></a>

								<?php endif; ?>
              				</div>
            			</div>
          			</div>
    
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Id</th>
								<th>Nombre</th>
								<th>Detalles</th>
								<th>Precio</th>
								<th>Stock</th>
								<th>Categoria</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if($select_products->rowCount() > 0){
									while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 	
									// Truncar el nombre si excede los 40 caracteres
									$product_name = strlen($fetch_products['nombre']) > 30 ? substr($fetch_products['nombre'], 0, 30) . '...' : $fetch_products['nombre'];
							?>
								<tr>
									<td><?= $fetch_products['id']; ?></td>
									<td><?= $fetch_products['nombre']; ?></td>
									<td><?= $fetch_products['detalles']; ?></td>
									<td><?= $fetch_products['precio']; ?></td>
									<td><?= $fetch_products['stock']; ?></td>
									<td><?= $fetch_products['categoria']; ?></td>
									<td>

										<a href="#seeEmployeeModal-<?php echo $fetch_products['id']; ?>" class="see" data-toggle="modal">
										<i class="material-icons" data-toggle="tooltip" title="Ver">visibility</i></a>
										<?php if ($rol == '3') : ?>
										<a href="#editEmployeeModal-<?php echo $fetch_products['id']; ?>" class="edit" data-toggle="modal">
										<i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>

										<a href="#deleteEmployeeModal-<?php echo $fetch_products['id']; ?>" class="delete" data-toggle="modal">
										<i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i></a>
										<?php endif; ?>
									</td>
								</tr>

								<!-- See Modal HTML -->
								<div id="seeEmployeeModal-<?php echo $fetch_products['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="" method="post" enctype="multipart/form-data">
												<div class="modal-header">
													<h4 class="modal-title">Ver producto</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label>Nombre</label>
														<div name="name" class="form-control"><?php echo $fetch_products['nombre']; ?></div>
														<input type="hidden" name="idprod" value="<?php echo $fetch_products['id'] ?>">
													</div>
													<div class="form-group">
														<label>Categoría</label>
														<br>
														<div name="category" class="form-control"><?php echo $fetch_products['categoria']; ?></div>
													</div>
													<div class="form-group">
														<label>Precio</label>
														<div name="price" class="form-control"><?php echo $fetch_products['precio']; ?></div>
													</div>
													<div class="form-group">
														<label>Stock</label>
														<div name='stock' class="form-control"><?php echo $fetch_products['stock']; ?></div>
													</div>
													<div class="form-group">
														<label>Imágenes</label>
														<div class="d-flex justify-content-center align-items-center">
															<div class="mr-3">
																<div class="image-thumbnail">
																	<img src="../uploaded_img/<?php echo !empty($fetch_products['imagen1']) ? $fetch_products['imagen1'] : 'agregar-producto.png'; ?>" class="img-fluid img-thumbnail" alt="Imagen">
																</div>
															</div>
															<div class="mr-3">
																<div class="image-thumbnail">
																	<img src="../uploaded_img/<?php echo !empty($fetch_products['imagen2']) ? $fetch_products['imagen2'] : 'agregar-producto.png'; ?>" class="img-fluid img-thumbnail" alt="Imagen">
																</div>
															</div>
															<div>
																<div class="image-thumbnail">
																	<img src="../uploaded_img/<?php echo !empty($fetch_products['imagen3']) ? $fetch_products['imagen3'] : 'agregar-producto.png'; ?>" class="img-fluid img-thumbnail" alt="Imagen">
																</div>
															</div>
														</div>
													</div>
													<input type="hidden" name="or_image1" value="<?php echo !empty($fetch_products['imagen1']) ? $fetch_products['imagen1'] : ''; ?>">
													<input type="hidden" name="or_image2" value="<?php echo !empty($fetch_products['imagen2']) ? $fetch_products['imagen2'] : ''; ?>">
													<input type="hidden" name="or_image3" value="<?php echo !empty($fetch_products['imagen3']) ? $fetch_products['imagen3'] : ''; ?>">
													<div class="form-group">
														<label>Detalles</label>
														<textarea name="details" class="form-control" readonly><?php echo $fetch_products['detalles']; ?></textarea>
													</div>
													
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Salir">
												</div>
											</form>
										</div>
									</div>
								</div>

								<!-- Edit Modal HTML -->
								<div id="editEmployeeModal-<?php echo $fetch_products['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="" method="post" enctype="multipart/form-data">
												<div class="modal-header">
													<h4 class="modal-title">Editar producto</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label>Nombre</label>
														<input name="name" type="text" class="form-control" value="<?php echo $fetch_products['nombre']; ?>" required>
														<input type="hidden" name="idprod" value="<?php echo $fetch_products['id'] ?>">
													</div>
													<div class="form-group">
														<label>Categoría</label>
														<br>
														<select name="category" class="form-control" required>
																<?php
																$select_categoria = $conn->prepare("SELECT id,nombre FROM categoria");
																$select_categoria->execute();
																$categorias = $select_categoria->fetchAll();

																foreach ($categorias as $categoria) {
																	$id = $categoria['id'];
																	$nombre = $categoria['nombre'];
																	if($nombre == $fetch_products['categoria']){
																		echo "<option value=\"$id\" selected>$nombre</option>";
																	} else {
																		echo "<option value=\"$id\">$nombre</option>";
																	}
																}
																?>
															</select>
													</div>
													<div class="form-group">
														<label>Precio</label>
														<input name="price" type="text" class="form-control" value="<?php echo $fetch_products['precio']; ?>" required>
													</div>
													<div class="form-group">
														<label>Stock</label>
														<input name='stock'type="text" class="form-control" value="<?php echo $fetch_products['stock']; ?>" required>
													</div>
													<div class="form-group">
														<label>Imágenes</label>
														<div class="d-flex justify-content-center align-items-center">
															<div class="mr-3">
																<div class="image-thumbnail">
																	<img src="../uploaded_img/<?php echo !empty($fetch_products['imagen1']) ? $fetch_products['imagen1'] : 'agregar-producto.png'; ?>" class="img-fluid img-thumbnail" alt="Imagen">
																</div>
															</div>
															<div class="mr-3">
																<div class="image-thumbnail">
																	<img src="../uploaded_img/<?php echo !empty($fetch_products['imagen2']) ? $fetch_products['imagen2'] : 'agregar-producto.png'; ?>" class="img-fluid img-thumbnail" alt="Imagen">
																</div>
															</div>
															<div>
																<div class="image-thumbnail">
																	<img src="../uploaded_img/<?php echo !empty($fetch_products['imagen3']) ? $fetch_products['imagen3'] : 'agregar-producto.png'; ?>" class="img-fluid img-thumbnail" alt="Imagen">
																</div>
															</div>
														</div>
													</div>
													<input type="hidden" name="or_image1" value="<?php echo !empty($fetch_products['imagen1']) ? $fetch_products['imagen1'] : ''; ?>">
													<input type="hidden" name="or_image2" value="<?php echo !empty($fetch_products['imagen2']) ? $fetch_products['imagen2'] : ''; ?>">
													<input type="hidden" name="or_image3" value="<?php echo !empty($fetch_products['imagen3']) ? $fetch_products['imagen3'] : ''; ?>">
													<div class="form-group">
														<h6>Seleccionar imágenes</h6>
														<input name="image_01" type="file" class="form-control-file mt-2">
														<input name="image_02" type="file" class="form-control-file mt-2">
														<input name="image_03" type="file" class="form-control-file mt-2">
													</div>
													<div class="form-group">
														<label>Detalles</label>
														<textarea name="details" class="form-control" required><?php echo $fetch_products['detalles']; ?></textarea>
													</div>
													
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
													<input type="submit" class="btn btn-success" name="actualizar_product" value="Actualizar">
												</div>
											</form>
										</div>
									</div>
								</div>
								
								<!-- Delete Modal HTML -->
								<div id="deleteEmployeeModal-<?php echo $fetch_products['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="" method="post" enctype="multipart/form-data">
												<div class="modal-header">
													<h4 class="modal-title">Agregar Stock</h4>
													
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">
													<p>¿Estas seguro que deseas eliminar este producto?</p>
													<p class="text-warning"><small>Esta accion no se podra revertir</small></p>
													<input type="hidden" name="idprod" value="<?php echo $fetch_products['id'] ?>">
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
													<input type="submit" class="btn btn-danger" name="delete" value="Eliminar">
												</div>
											</form>
										</div>
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
						</tbody>
					</table>
  				</div>
			</div>

			<!-- Add Modal HTML -->
			<form action="" method="post" enctype="multipart/form-data">
			<div id="addEmployeeModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<form>
							<div class="modal-header">
								<h4 class="modal-title">Añadir Producto</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label>Nombre</label>
									<input name="name" type="text" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Categoría</label>
									<br>
									<select name="category" class="form-control" required>
									
										<?php
										$select_categoria = $conn->prepare("SELECT id,nombre FROM categoria");
										$select_categoria->execute();
										$categorias = $select_categoria->fetchAll();

										foreach ($categorias as $categoria) {
											$id = $categoria['id'];
											$nombre = $categoria['nombre'];
											echo "<option value=\"$id\">$nombre</option>";
										}
										?>
									</select>

								</div>
								<div class="form-group">
									<label>Precio</label>
									<input name="price" type="text" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Stock</label>
									<input name='stock'type="text" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Imagen 01</label>
									<input name="image_01" type="file" class="form-control">
								</div>
								<div class="form-group">
									<label>Imagen 02</label>
									<input name="image_02" type="file" class="form-control">
								</div>
								<div class="form-group">
									<label>Imagen 03</label>
									<input name="image_03" type="file" class="form-control">
								</div>
								<div class="form-group">
									<label>Detalles</label>
									<textarea name="details" class="form-control" required></textarea>
								</div>
								
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
								<input type="submit" class="btn btn-success" name="add_product" value="Añadir">
							</div>
						</form>
					</div>
				</div>
			</div>
			</form>
			
				
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