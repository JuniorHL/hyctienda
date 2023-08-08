<?php
	include '../components/connect.php';

	session_start();

	$admin_id = $_SESSION['admin_id'];  

	if(!isset($admin_id)){
		header('location:login.php');
	}

	$select_venta = $conn->prepare("SELECT v.id, v.idCliente, v.fecha, v.cantProd,v.importeTotal,
										(SELECT GROUP_CONCAT(CONCAT(pr.nombre, ' - cantidad: ', d.cantProd) SEPARATOR '\n')
									FROM detalleventa d
									JOIN producto pr 
									ON d.idProducto = pr.id
									WHERE d.idVenta = v.id) AS productos, c.nombre, c.apellido, c.telefono, f.codigo
									FROM ventas v
									JOIN cliente c 
									ON v.idCliente = c.id LEFT JOIN factura f ON f.idVenta = v.id ORDER BY v.fecha DESC");
	$select_venta->execute();
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
										<h2 class="ml-lg-2">LISTA DE VENTAS</h2>
									</div>
									<div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
										<a href="../admin/ventasexcel.php" class="btn btn-success">
										<i class="material-icons">table_rows</i> <span>EXCEL</span></a>
										<a href="../fpdf/ventaspdf.php" target="_blank"class="btn btn-danger">
										<i class="material-icons">picture_as_pdf</i> <span>PDF</span></a>
										
									</div>
								</div>
							</div>
			
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Id</th>
										<th>Cliente</th>
                                        <th>Fecha y Hora</th>
                                        <th>Cantidad Productos</th>
										<th>Total</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if($select_venta->rowCount() > 0){
											while($fetch_venta = $select_venta->fetch(PDO::FETCH_ASSOC)){ 	
											// Truncar el nombre si excede los 40 caracteres
											//$product_name = strlen($fetch_products['nombre']) > 30 ? substr($fetch_products['nombre'], 0, 30) . '...' : $fetch_products['nombre'];
									?>
										<tr>
											<td><?= $fetch_venta['id']; ?></td>
											<td><?= $fetch_venta['nombre'].' '. $fetch_venta['apellido']; ?></td>
											<td><?= $fetch_venta['fecha']; ?></td>
											<td><?= $fetch_venta['cantProd']; ?></td>
											<td><?= $fetch_venta['importeTotal']; ?></td>
											<td>

                                                <a href="#seeEmployeeModal-<?php echo $fetch_venta['id']; ?>" class="see" data-toggle="modal">
												<i class="material-icons" data-toggle="tooltip" title="Ver">visibility</i></a>	
												
											</td>
										</tr>

										<!-- Edit Modal HTML -->
										<div id="editEmployeeModal-<?php echo $fetch_pedido['id']; ?>" class="modal fade">
											<div class="modal-dialog">
												<div class="modal-content">
													<form action="" method="post" enctype="multipart/form-data">
														<div class="modal-header">
															<h4 class="modal-title">Editar pedido</h4>
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
																<input name="contraseña" type="text" class="form-control" value="<?php echo $fetch_admin['contraseña']; ?>" required>
															</div>
														</div>
														<div class="modal-footer">
															<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
															<input type="submit" class="btn btn-success" name="edit_pedido" value="Actualizar">
														</div>
													</form>
												</div>
											</div>
										</div>
                                        
                                         <!-- See Modal HTML -->
									<div id="seeEmployeeModal-<?php echo $fetch_venta['id']; ?>" class="modal fade">
									<div class="modal-dialog">
										<div class="modal-content">
											<form action="" method="post" enctype="multipart/form-data">
												<div class="modal-header">
													<h4 class="modal-title">Ver venta</h4>
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<label>Cliente</label>
														<div name="name" class="form-control"><?php echo $fetch_venta['nombre'].' '.$fetch_venta['apellido']; ?></div>

													</div>
													<div class="form-group">
														<label>Telefono cliente</label>
														<div name="name" class="form-control"><?php echo $fetch_venta['telefono']; ?></div>
													</div>
													<div class="form-group">
														<label>Cantidad productos</label>
														<div name="price" class="form-control"><?php echo $fetch_venta['cantProd']; ?></div>
													</div>
													<div class="form-group">
														<label>Importe Total</label>
														<div name='stock' class="form-control"><?php echo $fetch_venta['importeTotal']; ?></div>
													</div>

													<div class="form-group">
														<label>Fecha venta</label>
														<div name="name" class="form-control"><?php echo $fetch_venta['fecha']; ?></div>
													</div>
													<div class="form-group">
														<label>Detalle venta</label>
														<textarea name="details" class="form-control" readonly><?php echo $fetch_venta['productos']; ?></textarea>
													</div>
													
												</div>
												<div class="modal-footer">
													<input type="button" class="btn btn-default" data-dismiss="modal" value="Salir">
												</div>
											</form>
										</div>
									</div>
								</div>
										<!-- Delete Modal HTML -->
										<div id="deleteEmployeeModal-<?php echo $fetch_pedido['id']; ?>" class="modal fade">
											<div class="modal-dialog">
												<div class="modal-content">
													<form action="" method="post" enctype="multipart/form-data">
														<div class="modal-header">
															<h4 class="modal-title">Eliminar pedido</h4>
															<button type="button" class="close" data-dismiss="modal" Waria-hidden="true">&times;</button>
														</div>
														<div class="modal-body">
															<p>¿Estas seguro que deseas eliminar este pedido?</p>
															<p class="text-warning"><small>Esta accion no se podra revertir</small></p>
															<input type="hidden" name="idped" value="<?php echo $fetch_pedido['id'] ?>">
														</div>
														<div class="modal-footer">
															<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
															<input type="submit" class="btn btn-danger" name="delete" value="Eliminar">
														</div>
													</form>
												</div>
											</div>
										</div>
										
										<!--Añadir pedido a venta  --> 
										<div id="AddventaEmployeeModal-<?php echo $fetch_pedido['id']; ?>" class="modal fade">
											<div class="modal-dialog">
												<div class="modal-content">
													<form action="" method="post" enctype="multipart/form-data">
														<div class="modal-header">
															<h4 class="modal-title">Añadir venta</h4>
															<button type="button" class="close" data-dismiss="modal" Waria-hidden="true">&times;</button>
														</div>
														<div class="modal-body">
															<p>¿Estas seguro que deseas añadir este pedido a venta?</p>
															<p class="text-warning"><small>Esta accion no se podra revertir</small></p>
															<input type="hidden" name="idped" value="<?php echo $fetch_pedido['id'] ?>">
															<input type="hidden" name="idcliente" value="<?php echo $fetch_pedido['idCliente'] ?>">
															<input type="hidden" name="cantidad" value="<?php echo $fetch_pedido['cantProd'] ?>">
															<input type="hidden" name="importe" value="<?php echo $fetch_pedido['importeTotal'] ?>">
														</div>
														<div class="modal-footer">
															<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
															<input type="submit" class="btn btn-danger" name="addventa" value="Añadir">
														</div>
													</form>
												</div>
											</div>
										</div>
								<?php
											}
									}else{
										if ($_SERVER['REQUEST_METHOD'] === 'POST') {
											echo '<p class="empty">Pedido no encontrado</p>';
										} else {
											echo '<p class="empty">No hay pedidos registrados</p>';
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
															if($descripcion == $fetch_admin['rol']){
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
		<script src="../js/admin_script.js"></script>
	</body>
</html>

<!--
	<section class="orders">

	<h1 class="heading">Pedidos</h1>

	<section class="flex">
		<nav class="product-search">
			<select class="category-select" onchange="location = this.value">
				<option value="" selected disabled>Seleccionar Estado</option>
				<option value="pedidos.php">Todos</option>
				<option value="estado_pedido.php?category=pendiente">Pendiente</option>
				<option value="estado_pedido.php?category=completado">Completado</option>
				<option value="estado_pedido.php?category=cancelado">Cancelado</option>
			</select>
		</nav>
	</section>

	<div class="box-container">

	<?php
		$select_orders = $conn->prepare("SELECT pe.id, pe.fecha, pe.metpago,pe.estado, pe.importeTotal,
		(SELECT GROUP_CONCAT(CONCAT(pr.nombre, ' - cantidad: ', d.cantProd) SEPARATOR '\n')
		FROM detallepedido d
		JOIN producto pr ON d.idProducto = pr.id
		WHERE d.idPedido = pe.id) AS productos,
		c.nombre, c.apellido, c.telefono
		FROM pedido pe
		JOIN cliente c ON pe.idCliente = c.id WHERE pe.estado='pendiente'");
		$select_orders->execute();
		if($select_orders->rowCount() > 0){
			while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
	?>
	<div class="box">
		<p> Fecha : <span><?= $fetch_orders['fecha']; ?></span> </p>
		<p> Nombre : <span><?= $fetch_orders['nombre'].' '. $fetch_orders['apellido']; ?></span> </p>
		<p> Numero: <span><?= $fetch_orders['telefono']; ?></span> </p>
		<p> Productos : <span><?= $fetch_orders['productos']; ?></span> </p>
		<p> Total a pagar : <span>S/. <?= $fetch_orders['importeTotal']; ?> </span> </p>
		<p> Metodo de Pago : <span><?= $fetch_orders['metpago']; ?></span> </p>
		<form action="" method="post">
			<input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
			<select name="payment_status" class="select">
				<option selected disabled><?= $fetch_orders['estado']; ?></option>
				<option value="pendiente">Pendiente</option>
				<option value="completado">Completado</option>
				<option value="cancelado">Cancelado</option>
			</select>
			<div class="flex-btn">
			<input type="submit" value="Actualizar" class="option-btn" name="update_payment">
			</div>
		</form>
	</div>
	<?php
			}
		}else{
			echo '<p class="empty">No hay pedidos</p>';
		}
	?>

	</div>

	</section>
-->