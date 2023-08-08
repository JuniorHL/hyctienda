<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(isset($_POST['delete'])){
   $delete_id = $_POST['idad'];
   $delete_admins = $conn->prepare("DELETE FROM `usuarioadmin` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:empleados.php');
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

if(isset($_POST['add_empleado'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $apellido = $_POST['apellido'];
   $apellido = filter_var($apellido, FILTER_SANITIZE_STRING);
   $documento = $_POST['dni'];
   $documento = filter_var($documento, FILTER_SANITIZE_STRING);
   $telefono = $_POST['telefono'];
   $telefono = filter_var($telefono, FILTER_SANITIZE_STRING);
   $rol = $_POST['rol'];
   $rol = filter_var($rol, FILTER_SANITIZE_STRING);
   $user = $_POST['usuario'];
   $user = filter_var($user, FILTER_SANITIZE_STRING);
   $pass = $_POST['contraseña'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
  
   $select_admin = $conn->prepare("SELECT * FROM `usuarioadmin` WHERE usuario = ?");
   $select_admin->execute([$user]);

   if($select_admin->rowCount() > 0){
      $message[] = 'Trabajador ya registrado';
   }else{
      if(strlen($pass) <= 10) {
         $message[] = 'La contraseña debe tener más de 10 caracteres';
      } elseif (strlen($documento) !== 8) {
         $message[] = 'El número de documento debe tener exactamente 8 caracteres';
      } else{
		 $password = password_hash($pass, PASSWORD_BCRYPT);
         $insert_admin = $conn->prepare("INSERT INTO `usuarioadmin`(nombre, apellido, DNI, telefono, idRol, usuario, contraseña) VALUES(?,?,?,?,?,?,?)");
         $insert_admin->execute([$name, $apellido, $documento, $telefono, $rol, $user, $password]);
         $message[] = 'Nuevo trabajador agregado exitosamente';
      }
   }

}

   if (isset($_POST['edit_empleado'])) {

	$name = $_POST['name'];
   	$name = filter_var($name, FILTER_SANITIZE_STRING);
   	$apellido = $_POST['apellido'];
   	$apellido = filter_var($apellido, FILTER_SANITIZE_STRING);
   	$documento = $_POST['dni'];
   	$documento = filter_var($documento, FILTER_SANITIZE_STRING);
   	$telefono = $_POST['telefono'];
   	$telefono = filter_var($telefono, FILTER_SANITIZE_STRING);
   	$rol = $_POST['rol'];
   	$rol = filter_var($rol, FILTER_SANITIZE_STRING);
   	$user = $_POST['usuario'];
   	$user = filter_var($user, FILTER_SANITIZE_STRING);
   	$pass = $_POST['contraseña'];
   	$pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $password = password_hash($pass, PASSWORD_BCRYPT);
	$idusuario = $_POST['idusuario'];

	$update_admin = $conn->prepare("UPDATE `usuarioadmin` SET nombre=?, apellido=?, DNI=?, telefono=?, idRol=?, usuario=?, contraseña=? WHERE id = ?");
	$update_admin->execute([$name, $apellido, $documento, $telefono, $rol, $user, $password, $idusuario]);

 }

	$select_admin = $conn->prepare("SELECT a.id, a.nombre, a.apellido, a.telefono, a.dni, a.usuario, a.contraseña, r.descripcion  FROM usuarioadmin a INNER JOIN rol r ON a.idrol = r.id");
	$select_admin->execute();

	

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
                				<h2 class="ml-lg-2">EMPLEADOS</h2>
              				</div>
              				<?php if ($rol == '1') : ?>
							<div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
								<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
								<i class="material-icons">&#xE147;</i> <span>Añadir</span></a>
								<a href="../admin/empleadosexcel.php" class="btn btn-success">
								<i class="material-icons">table_rows</i> <span>EXCEL</span></a>
								<a href="../fpdf/empleadospdf.php" target="_blank" class="btn btn-danger">
								<i class="material-icons">picture_as_pdf</i> <span>PDF</span></a>
								
              				</div>
							<?php endif; ?>
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
								<th>Rol</th>
								<?php if ($rol == '1') : ?>
								<th>Acciones</th>
								<?php endif; ?>
								<?php if ($rol != '1') : ?>
								<th></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php
								if($select_admin->rowCount() > 0){
									while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){ 	
									// Truncar el nombre si excede los 40 caracteres
									//$product_name = strlen($fetch_products['nombre']) > 30 ? substr($fetch_products['nombre'], 0, 30) . '...' : $fetch_products['nombre'];
							?>
								<tr>
								<td><?= $fetch_admin['id']; ?></td>
								<td><?= $fetch_admin['nombre']; ?></td>
								<td><?= $fetch_admin['apellido']; ?></td>
								<td><?= $fetch_admin['dni']; ?></td>
								<td><?= $fetch_admin['telefono']; ?></td>
								<td><?= $fetch_admin['descripcion']; ?></td>
								
								<td>
									<a href="#editEmployeeModal-<?php echo $fetch_admin['id']; ?>" class="edit" data-toggle="modal">
									<i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>

									<a href="#deleteEmployeeModal-<?php echo $fetch_admin['id']; ?>" class="delete" data-toggle="modal">
									<i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i></a>
								</td>
								
								
								<td></td>
							</tr>

							<!-- Edit Modal HTML -->
							<div id="editEmployeeModal-<?php echo $fetch_admin['id']; ?>" class="modal fade">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post" enctype="multipart/form-data">
											<div class="modal-header">
												<h4 class="modal-title">Editar Empleado</h4>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											</div>
											<div class="modal-body">
												<div class="form-group">
													<label>Nombre</label>
													<input name="name" type="text" class="form-control" value="<?php echo $fetch_admin['nombre']; ?>" required>
													<input type="hidden" name="idusuario" value="<?php echo $fetch_admin['id'] ?>">
												</div>
                                    			<div class="form-group">
													<label>Apellido</label>
													<input name="apellido" type="text" class="form-control" value="<?php echo $fetch_admin['apellido']; ?>" required>
												</div>
                                   				<div class="form-group">
													<label>DNI</label>
													<input name="dni" type="text" class="form-control" value="<?php echo $fetch_admin['dni']; ?>" required>
												</div>
                                    			<div class="form-group">
													<label>Telefono</label>
													<input name="telefono" type="text" class="form-control" value="<?php echo $fetch_admin['telefono']; ?>" required>
												</div>
												<div class="form-group">
													<label>Rol</label>
													<br>
													<select name="rol" class="form-control" required>
															<?php
															$select_rol = $conn->prepare("SELECT id,descripcion FROM rol");
															$select_rol->execute();
															$roles = $select_rol->fetchAll();

															foreach ($roles as $rol) {
																$id = $rol['id'];
																$descripcion = $rol['descripcion'];
																if($nombre == $fetch_admin['rol']){
																	echo "<option value=\"$id\" selected>$descripcion</option>";
																} else {
																	echo "<option value=\"$id\">$descripcion</option>";
																}
															}
															?>
														</select>
												</div>
                                    			<div class="form-group">
													<label>Usuario</label>
													<input name="usuario" type="text" class="form-control" value="<?php echo $fetch_admin['usuario']; ?>" required>
												</div>
                                    			<div class="form-group">
													<label>Contraseña</label>
													<input name="contraseña" type="text" class="form-control" required>
												</div>
											</div>
											<div class="modal-footer">
												<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
												<input type="submit" class="btn btn-success" name="edit_empleado" value="Actualizar">
											</div>
										</form>
									</div>
								</div>
							</div>

							<!-- Delete Modal HTML -->
							<div id="deleteEmployeeModal-<?php echo $fetch_admin['id']; ?>" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
								<form>
									<div class="modal-header">
									<h4 class="modal-title">Eliminar empleado</h4>
									<button type="button" class="close" data-dismiss="modal" 
									aria-hidden="true">&times;</button>
									</div>
									<div class="modal-body">
									<p>¿Estas seguro que deseas eliminar a este empleado?</p>
									<p class="text-warning"><small>Esta accion no se podra revertir</small></p>
									<input type="hidden" name="idad" value="<?php echo $fetch_admin['id'] ?>">
									</div>
									<div class="modal-footer">
									<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
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
									echo '<p class="empty">Empleado no encontrado</p>';
								} else {
									echo '<p class="empty">No hay empleado</p>';
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
								<h4 class="modal-title">Añadir empleado</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label>Nombre</label>
									<input name="name" type="text" class="form-control" required>
								</div>
                        <div class="form-group">
									<label>Apellido</label>
									<input name="apellido" type="text" class="form-control" required>
								</div>
                        <div class="form-group">
									<label>DNI</label>
									<input name="dni" type="text" class="form-control" required>
								</div>
                        <div class="form-group">
									<label>Telefono</label>
									<input name="telefono" type="text" class="form-control" required>
								</div>
								<div class="form-group">
									<label>Rol</label>
									<br>
									<select name="rol" class="form-control" required>
                              <?php
											$select_rol = $conn->prepare("SELECT id,descripcion FROM rol");
											$select_rol->execute();
											$roles = $select_rol->fetchAll();

                                 foreach ($roles as $rol) {      
                                    $id = $rol['id'];
												$descripcion = $rol['descripcion'];
												if($nombre == $fetch_admin['rol']){
													echo "<option value=\"$id\" selected>$descripcion</option>";
												} else {
													echo "<option value=\"$id\">$descripcion</option>";
										      }
										   }
										?>
									</select>
								</div>
								<div class="form-group">
									<label>Usuario</label>
									<input name="usuario" type="text" class="form-control" required>
								</div>
                        <div class="form-group">
									<label>Contraseña</label>
									<input name="contraseña" type="text" class="form-control" required>
								</div>
							</div>
							<div class="modal-footer">
								<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
								<input type="submit" class="btn btn-success" name="add_empleado" value="Añadir">
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
      if($select_admin->rowCount() > 0){
         while($fetch_accounts = $select_admin->fetch(PDO::FETCH_ASSOC)){  
            $empleado_name = strlen($fetch_accounts['nombre']) > 20 ? substr($fetch_products['nombre'], 0, 20) . '...' : $fetch_accounts['nombre']; 
   ?>
   <div class="box">
      <p>Nombre: <span><?= $fetch_accounts['nombre']; ?></span></p>
      <p>D.N.I: <span><?= $fetch_accounts['dni']; ?></span></p>
      <p>Rol: <span><?= $fetch_accounts['descripcion']; ?></span></p>
      <div class="flex-btn">
         <a href="editar_empleado.php?update=<?= $fetch_accounts['id']; ?>" class="option-btn">Editar</a>
         <a href="empleados.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('¿Desea eliminar a este usuario?')" class="delete-btn">Eliminar</a>
      </div>
   </div>
   <?php
         }
      } else {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<p class="empty">Empleado no encontrado</p>';
         } else {
            echo '<p class="empty">No hay empleados</p>';
         }
      }
   ?>

   </div>

</section>
   -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>
