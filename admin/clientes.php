<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(isset($_POST['delete_cliente'])){
   $delete_id = $_POST['idcliente'];
   $delete_cliente = $conn->prepare("DELETE FROM `cliente` WHERE id = ?");
   if($delete_cliente->execute([$delete_id])){
    $message[] = 'Cliente eliminado exitosamente';
   };
   header('location:clientes.php');
}

/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $searchAdmin = $_POST['search-input'];

   if (!empty($searchAdmin)) {
      $select_admin = $conn->prepare("SELECT a.id, a.nombre, a.dni,r.descripcion  FROM usuarioadmin a INNER JOIN rol r ON a.idrol = r.id WHERE name LIKE :search");
      $select_admin->bindValue(':search', '%' . $searchAdmin . '%');
      $select_admin->execute();
   } else {
      $select_admin = $conn->prepare("SELECT a.id,a.nombre, a.dni,r.descripcion  FROM usuarioadmin a INNER JOIN rol r ON a.idrol = r.id");
      $select_admin->execute();
   }
} else {
   $select_admin = $conn->prepare("SELECT a.id, a.nombre, a.dni,r.descripcion  FROM usuarioadmin a INNER JOIN rol r ON a.idrol = r.id");
   $select_admin->execute();
}
*/

if(isset($_POST['add_cliente'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $apellido = $_POST['apellido'];
   $apellido = filter_var($apellido, FILTER_SANITIZE_STRING);
   $documento = $_POST['dni'];
   $documento = filter_var($documento, FILTER_SANITIZE_STRING);
   $telefono = $_POST['telefono'];
   $telefono = filter_var($telefono, FILTER_SANITIZE_STRING);

   $select_cliente = $conn->prepare("SELECT * FROM `cliente` WHERE DNI = ? OR telefono = ?");
   $select_cliente->execute([$documento ,$telefono]);

   if($select_cliente->rowCount() > 0){
      $message[] = 'Cliente ya registrado';
   }else{
      if(strlen($documento) !== 8) {
         $message[] = 'El número de documento debe tener exactamente 8 caracteres';
      } else{
         $insert_cliente = $conn->prepare("INSERT INTO `cliente`(nombre, apellido, DNI, telefono) VALUES(?,?,?,?)");
         $insert_cliente->execute([$name, $apellido, $documento, $telefono]);
         $message[] = 'Nuevo cliente agregado exitosamente';
      }
   }

}

   if (isset($_POST['edit_cliente'])) {

	$name = $_POST['name'];
   	$name = filter_var($name, FILTER_SANITIZE_STRING);
   	$apellido = $_POST['apellido'];
   	$apellido = filter_var($apellido, FILTER_SANITIZE_STRING);
   	$documento = $_POST['dni'];
   	$documento = filter_var($documento, FILTER_SANITIZE_STRING);
   	$telefono = $_POST['telefono'];
   	$telefono = filter_var($telefono, FILTER_SANITIZE_STRING);
	$idcliente = $_POST['idcliente'];

	$update_cliente = $conn->prepare("UPDATE `cliente` SET nombre=?, apellido=?, DNI=?, telefono=? WHERE id = ?");
	if($update_cliente->execute([$name, $apellido, $documento, $telefono, $idcliente])){
        $message[] = 'Cliente modificado exitosamente';
    };

 }

	$select_cliente = $conn->prepare("SELECT * FROM cliente");
	$select_cliente->execute();

	$select_rol = $conn->prepare("SELECT idRol FROM usuarioadmin");
    $select_rol->execute();
    $row = $select_rol->fetch(PDO::FETCH_ASSOC);
    $rol = $row['idRol'];

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
                				<h2 class="ml-lg-2">CLIENTES</h2>
              				</div>
              				<div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
							  	<?php if ($rol == '2') : ?>
								<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
								<i class="material-icons">&#xE147;</i> <span>Añadir</span></a>
								<a href="../admin/clientesexcel.php" class="btn btn-success">
								<i class="material-icons">table_rows</i> <span>EXCEL</span></a>
								<a href="../fpdf/clientespdf.php" target="_blank" class="btn btn-danger">
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
								<th>Apellidos</th>
								<th>DNI</th>
								<th>Telefono</th>
								<?php if ($rol == '1') : ?>
								<th></th>
								<?php endif; ?>
								<?php if ($rol == '2') : ?>
								<th>Acciones</th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php
								if($select_cliente->rowCount() > 0){
									while($fetch_cliente = $select_cliente->fetch(PDO::FETCH_ASSOC)){ 	
									// Truncar el nombre si excede los 40 caracteres
									//$product_name = strlen($fetch_products['nombre']) > 30 ? substr($fetch_products['nombre'], 0, 30) . '...' : $fetch_products['nombre'];
							?>
								<tr>
								<td><?= $fetch_cliente['id']; ?></td>
								<td><?= $fetch_cliente['nombre']; ?></td>
								<td><?= $fetch_cliente['apellido']; ?></td>
								<td><?= $fetch_cliente['DNI']; ?></td>
								<td><?= $fetch_cliente['telefono']; ?></td>
								<?php if ($rol == '1') : ?>
								<th></th>
								<?php endif; ?>
								<?php if ($rol == '2') : ?>
								<td>
									<a href="#editEmployeeModal-<?php echo $fetch_cliente['id']; ?>" class="edit" data-toggle="modal">
									<i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>

									<a href="#deleteEmployeeModal-<?php echo $fetch_cliente['id']; ?>" class="delete" data-toggle="modal">
									<i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i></a>
								</td>
								<?php endif; ?>
							</tr>

							<!-- Edit Modal HTML -->
							<div id="editEmployeeModal-<?php echo $fetch_cliente['id']; ?>" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post" enctype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title">Editar cliente</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label>Nombre</label>
													<input name="name" type="text" class="form-control" value="<?php echo $fetch_cliente['nombre']; ?>" required>
													<input type="hidden" name="idcliente" value="<?php echo $fetch_cliente['id'] ?>">
												</div>
                                    			<div class="form-group">
													<label>Apellido</label>
													<input name="apellido" type="text" class="form-control" value="<?php echo $fetch_cliente['apellido']; ?>" required>
												</div>
                                   				<div class="form-group">
													<label>DNI</label>
													<input name="dni" type="text" class="form-control" value="<?php echo $fetch_cliente['DNI']; ?>" required>
												</div>
                                    			<div class="form-group">
													<label>Telefono</label>
													<input name="telefono" type="text" class="form-control" value="<?php echo $fetch_cliente['telefono']; ?>" required>
												</div>
                                            
											</div>
											<div class="modal-footer">
												<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
												<input type="submit" class="btn btn-success" name="edit_cliente" value="Actualizar">
											</div>
										</form>
									</div>
								</div>
							</div>

							<!-- Delete Modal HTML -->
							<div id="deleteEmployeeModal-<?php echo $fetch_cliente['id']; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
								<form action="" method="post" enctype="multipart/form-data">
									<div class="modal-header">
									<h4 class="modal-title">Eliminar cliente</h4>
									<button type="button" class="close" data-dismiss="modal" 
									aria-hidden="true">&times;</button>
									</div>
									<div class="modal-body">
									<p>¿Estas seguro que deseas eliminar a este cliente?</p>
									<p class="text-warning"><small>Esta accion no se podra revertir</small></p>
									<input type="hidden" name="idcliente" value="<?php echo $fetch_cliente['id'] ?>">
									</div>
									<div class="modal-footer">
									<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
									<input type="submit" class="btn btn-danger" name="delete_cliente" value="Eliminar">
									</div>
								</form>
								</div>
								</div>
							</div>
							<?php
							}
							}else{
								if ($_SERVER['REQUEST_METHOD'] === 'POST') {
									echo '<p class="empty">Cliente no encontrado</p>';
								} else {
									echo '<p class="empty">No hay clientes</p>';
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
								<h4 class="modal-title">Añadir cliente</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label>Nombre</label>
									<input name="name" type="text" class="form-control" required>
								</div>
                        <div class="form-group">
									<label>Apellido</label>
									<input name="apellido" type="text" class="form-control">
								</div>
                        <div class="form-group">
									<label>DNI</label>
									<input name="dni" type="text" class="form-control">
								</div>
                        <div class="form-group">
									<label>Telefono</label>
									<input name="telefono" type="text" class="form-control" required>
								</div>
								
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
								<input type="submit" class="btn btn-success" name="add_cliente" value="Añadir">
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
<section class="accounts">

   <h1 class="heading">Empleados</h1>

   <section class="flex">
      <nav class="product-search">
         <form method="POST">
            <input type="text" class="search-input" name="search-input" placeholder="Buscar empleado...">
            <button type="submit">Buscar</button>
         </form>
         <select class="category-select" onchange="location = this.value">
            <option value="" selected disabled>Seleccionar Rol</option>
            <option value="categoria_empleado.php?category=Gerente">Gerente</option>
            <option value="categoria_empleado.php?category=Administrador">Administrador</option>
            <option value="categoria_empleado.php?category=Enc de Inventario">Enc. de Inventario</option>
            <option value="categoria_empleado.php?category=Vendedor">Vendedor</option>
         </select>
      </nav>
   </section>

   <div class="box-container">

   <?php
      if($select_cliente->rowCount() > 0){
         while($fetch_accounts = $select_cliente->fetch(PDO::FETCH_ASSOC)){  
            $cliente_name = strlen($fetch_accounts['nombre']) > 20 ? substr($fetch_products['nombre'], 0, 20) . '...' : $fetch_accounts['nombre']; 
   ?>
   <div class="box">
      <p>Nombre: <span><?= $fetch_accounts['nombre']; ?></span></p>
      <p>D.N.I: <span><?= $fetch_accounts['dni']; ?></span></p>
      <p>Rol: <span></span></p>
      <div class="flex-btn">
         <a href="editar_empleado.php?update=<?= $fetch_accounts['id']; ?>" class="option-btn">Editar</a>
         <a href="empleados.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('¿Desea eliminar a este usuario?')" class="delete-btn">Eliminar</a>
      </div>
   </div>
   <?php
         }
      } else {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<p class="empty">Cliente no encontrado</p>';
         } else {
            echo '<p class="empty">No hay clientes</p>';
         }
      }
   ?>

   </div>

</section>
   -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>