<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

   $select_rol = $conn->prepare("SELECT idRol FROM usuarioadmin");
   $select_rol->execute();
   $row = $select_rol->fetch(PDO::FETCH_ASSOC);
   $rol = $row['idRol'];

   $fechaActual = date('Y-m-d');

   // TABLA RECLAMOS
   $select_reclamos = $conn->prepare("SELECT r.id, r.reclamo, u.usuario, c.nombre, c.telefono FROM reclamos r INNER JOIN usuariocliente u ON
                                          r.idUsuario=u.id INNER JOIN cliente c ON c.idUsuario=u.id");
   $select_reclamos->execute();

   // TABLA EMPLEADOS
   $select_admin = $conn->prepare("SELECT a.id, a.nombre, a.apellido, a.telefono, a.dni, a.usuario, a.contraseña, r.descripcion  FROM usuarioadmin a INNER  JOIN rol r ON a.idrol = r.id");
	$select_admin->execute();

   //TABLA VENTAS
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
	<!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<!----css3---->
   <link rel="stylesheet" href="css/custom.css">
		
	<!--google fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
		
	<!--google material icon-->
   <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">

</head>

<body>

   <?php include '../components/sliderbar.php'; ?>

   <!-- CAJERO  -->
   <?php if ($rol == '4') : ?>
   <div class="main-content">
      
      <div class="row">
         <!--  PARTE SUPERIOR -->
        
         <div class="col-lg-3 col-md-6 col-sm-6">	
            
            <!-- Pedidos (CAJERO) -->

            <div class="card card-stats">
               <div class="card-header">
                  <div class="icon icon-warning">
                        <span class="material-icons">format_list_numbered</span>
                     </div>
                  </div>
                  <div class="card-content">
                  <?php
                     // Consulta SQL para obtener la cantidad de pedidos cancelados con fecha límite igual a hoy
                     $query = "SELECT COUNT(*) AS cantidad_pedidos FROM pedido WHERE DATE(fechalimit) = '$fechaActual'";
                     $stmt = $conn->prepare($query);
                     $stmt->execute();
                     $result = $stmt->fetch(PDO::FETCH_ASSOC);

                     // Obtener el valor de la cantidad de pedidos
                     $cantidadPedidos = $result['cantidad_pedidos'];
                  ?>
                     <p class="category"><strong>Total de Pedidos</strong></p>
                     <h3 class="card-title"><?= $cantidadPedidos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons text-info">double_arrow</i>
                        <a href="">De Hoy</a>
                     </div>
                  </div>
               </div>
            </div>	 
            
            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-info">
                        <span class="material-icons">reorder</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        // Consulta SQL para obtener la cantidad de pedidos cancelados con fecha límite igual a hoy
                        $query = "SELECT COUNT(*) AS cantidad_pedidos FROM pedido WHERE estado = 'Pendiente' AND DATE(fechalimit) = '$fechaActual'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Obtener el valor de la cantidad de pedidos
                        $cantidadPedidos = $result['cantidad_pedidos'];
                     ?>
                     <p class="category"><strong>Pedidos Pendientes</strong></p>
                     <h3 class="card-title"><?= $cantidadPedidos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">De Hoy</a>
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">playlist_add_check</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        // Consulta SQL para obtener la cantidad de pedidos cancelados con fecha límite igual a hoy
                        $query = "SELECT COUNT(*) AS cantidad_pedidos FROM pedido WHERE estado = 'Completado' AND DATE(fechalimit) = '$fechaActual'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Obtener el valor de la cantidad de pedidos
                        $cantidadPedidos = $result['cantidad_pedidos'];
                     ?>
                     <p class="category"><strong>Pedidos Entregados</strong></p>
                     <h3 class="card-title"><?= $cantidadPedidos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">De Hoy</a>
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-rose">
                        <span class="material-icons">playlist_remove</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        // Consulta SQL para obtener la cantidad de pedidos cancelados con fecha límite igual a hoy
                        $query = "SELECT COUNT(*) AS cantidad_pedidos FROM pedido WHERE estado = 'Cancelado' AND DATE(fechalimit) = '$fechaActual'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Obtener el valor de la cantidad de pedidos
                        $cantidadPedidos = $result['cantidad_pedidos'];
                     ?>
                     <p class="category"><strong>Pedidos Cancelados</strong></p>
                     <h3 class="card-title"><?= $cantidadPedidos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">De Hoy</a>
                     </div>
                  </div>
               </div>
            </div>

            
            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-rose">
                        <span class="material-icons">shopping_cart</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                     $query = "SELECT COUNT(*) AS cantidad_ventas FROM ventas WHERE DATE(fecha) = '$fechaActual'";
                     $stmt = $conn->prepare($query);
                     $stmt->execute();
                     $result = $stmt->fetch(PDO::FETCH_ASSOC);
                     
                     // Obtener el valor de la cantidad de ventas
                     $cantidadVentas = $result['cantidad_ventas'];
                     ?>
                     <p class="category"><strong>Total de Ventas</strong></p>
                     <h3 class="card-title"><?= $cantidadVentas; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>De Hoy
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">attach_money</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                     $select_ganancias = $conn->prepare("SELECT SUM(importeTotal) AS importeTotal FROM ventas WHERE DATE(fecha) = ?");
                     $select_ganancias->execute([$fechaActual]);
                     $total_ganancias = $select_ganancias->fetch(PDO::FETCH_ASSOC)['importeTotal'];
                     ?>
                     <p class="category"><strong>Ventas Hoy</strong></p>
                     <h3 class="card-title"><?= $total_ganancias; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>De Hoy
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-info">
                        <span class="material-icons">sell</span>
                     </div>
                  </div>
                  <div class="card-content">
                  <?php
                     $select_productos = $conn->prepare("SELECT SUM(cantProd) AS cantProd FROM ventas WHERE DATE(fecha) = ?");
                     $select_productos->execute([$fechaActual]);
                     $total_productos = $select_productos->fetch(PDO::FETCH_ASSOC)['cantProd'];
                     ?>
                     <p class="category"><strong>Productos Vendidos</strong></p>
                     <h3 class="card-title"><?= $total_productos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>De Hoy
                     </div>
                  </div>
               </div>
            </div>	
         </div>
      </div>

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
                                        <?php if ($rol == '3') : ?>
										<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
										<i class="material-icons">picture_as_pdf</i> <span>EXCEL</span></a>
										<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
										<i class="material-icons">picture_as_pdf</i> <span>PDF</span></a>
                                        <?php endif; ?>
									</div>
								</div>
							</div>
			
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Id</th>
										<th>Cliente</th>
										<th>Factura</th>
                              <th>Fecha - Hora</th>
                              <th>Cantidad Productos</th>
										<th>Total</th>
										<th></th>
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
											<td><?= $fetch_venta['codigo']; ?></td>
											<td><?= $fetch_venta['fecha']; ?></td>
											<td><?= $fetch_venta['cantProd']; ?></td>
											<td><?= $fetch_venta['importeTotal']; ?></td>
											<td></td>
										</tr>
										
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

				</div>
			</div>
		</div>


   </div>
   <?php endif; ?>
   
   <!-- INVENTARIO -->
   <?php if ($rol == '3') : ?>
   <div class="main-content">
      <?php
      $select_products = $conn->prepare("SELECT p.id, p.nombre, p.detalles, p.precio, p.stock, c.nombre AS categoria FROM producto p INNER JOIN categoria c ON p.idcat = c.id ORDER BY p.stock DESC");
      $select_products->execute();
      ?>

      <div class="row">
         <!--  PARTE SUPERIOR -->
      
         <div class="col-lg-3 col-md-6 col-sm-6">	
            
            <!-- productos -->

            <div class="card card-stats">
               <div class="card-header">
                  <div class="icon icon-warning">
                        <span class="material-icons">token</span>
                     </div>
                  </div>
                  <div class="card-content">
                  <?php
                     $select_productos = $conn->prepare("SELECT SUM(stock) AS total_stock FROM producto");
                     $select_productos->execute();
                     $total_stock = $select_productos->fetch(PDO::FETCH_ASSOC)['total_stock'];
                  ?>
                     <p class="category"><strong>Productos</strong></p>
                     <h3 class="card-title"><?= $total_stock; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons text-info">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>	 
            
            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-info">
                        <span class="material-icons">sync_alt</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $query = "SELECT SUM(cantidad) AS cantidad FROM movimientos WHERE DATE(fecha) = '$fechaActual'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        // Obtener el valor de la cantidad de movimientos
                        $cantidadMovimientos = $result['cantidad'];
                     ?>
                     <p class="category"><strong>Movientos</strong></p>
                     <h3 class="card-title"><?= $cantidadMovimientos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">login</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $query = "SELECT SUM(cantidad) AS cantidad FROM movimientos WHERE tipo = 'Entrada' AND DATE(fecha) = '$fechaActual'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        // Obtener el valor de la cantidad de movimientos
                        $cantidadMovimientos = $result['cantidad'];
                     ?>
                     <p class="category"><strong>Productos Entrantes</strong></p>
                     <h3 class="card-title"><?= $cantidadMovimientos ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">De Hoy</a>
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-rose">
                        <span class="material-icons">logout</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $query = "SELECT SUM(cantidad) AS cantidad FROM movimientos WHERE tipo = 'Salida' AND DATE(fecha) = '$fechaActual'";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        // Obtener el valor de la cantidad de movimientos
                        $cantidadMovimientos = $result['cantidad'];
                     ?>
                     <p class="category"><strong>Poductos Salientes</strong></p>
                     <h3 class="card-title"><?= $cantidadMovimientos; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">De Hoy</a>
                     </div>
                  </div>
               </div>
            </div>
            
         </div>

      </div>

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
                           </div>
                        </div>
                     </div>
    
                     <table class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th></th>
                              <th>Nombre</th>
                              <th>Detalles</th>
                              <th>Precio</th>
                              <th>Stock</th>
                              <th>Categoria</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>                       
                           <?php
                              if($select_products->rowCount() > 0){
                                 while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 	
                              ?>
                              <tr>
                                 <td></td>
                                 <td><?= $fetch_products['nombre']; ?></td>
                                 <td><?= $fetch_products['detalles']; ?></td>
                                 <td><?= $fetch_products['precio']; ?></td>
                                 <td><?= $fetch_products['stock']; ?></td>
                                 <td><?= $fetch_products['categoria']; ?></td>
                                 <td></td>
                              </tr>

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
				</div>
			</div>
		</div>
	
	</div>
   <?php endif; ?>

   <!-- ADMIN -->
   <?php if ($rol == '2') : ?>
   <div class="main-content">
      
      <div class="row">
         <!--  PARTE SUPERIOR -->
      
         <div class="col-lg-3 col-md-6 col-sm-6">	
            
            <div class="card card-stats">
               <div class="card-header">
                  <div class="icon icon-warning">
                        <span class="material-icons">group</span>
                     </div>
                  </div>
                  <div class="card-content">
                  <?php
                     $select_user = $conn->prepare("SELECT COUNT(*) AS total_user FROM usuarioadmin WHERE idrol IN (?, ?, ?)");
                     $select_user->execute(['3', '4', '2']);
                     $total_user = $select_user->fetch(PDO::FETCH_ASSOC)['total_user'];
                  ?>
                     <p class="category"><strong>Total</strong></p>
                     <h3 class="card-title"><?= $total_user; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons text-info">double_arrow</i>
                        <a href="">Empleados </a>
                     </div>
                  </div>
               </div>
            </div>	 
            
            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-info">
                        <span class="material-icons">person</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_user = $conn->prepare("SELECT idRol FROM `usuarioadmin` WHERE idrol = ?");
                        $select_user->execute(['3']);
                        $number_of_user = $select_user->rowCount()
                     ?>
                     <p class="category"><strong>Encargado de Almacen</strong></p>
                     <h3 class="card-title"><?= $number_of_user ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">person</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_user = $conn->prepare("SELECT idRol FROM `usuarioadmin` WHERE idrol = ?");
                        $select_user->execute(['4']);
                        $number_of_user = $select_user->rowCount()
                     ?>
                     <p class="category"><strong>Vendedor</strong></p>
                     <h3 class="card-title"><?= $number_of_user ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-rose">
                        <span class="material-icons">person_2</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_clientes = $conn->prepare("SELECT COUNT(*) AS total_clientes FROM cliente");
                        $select_clientes->execute();
                        $total_clientes = $select_clientes->fetch(PDO::FETCH_ASSOC)['total_clientes']
                     ?>
                     <p class="category"><strong>Clientes</strong></p>
                     <h3 class="card-title"><?= $total_clientes; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">attach_money</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_ganancias = $conn->prepare("SELECT SUM(importeTotal) AS importeTotal FROM ventas WHERE DATE(fecha) = ?");
                        $select_ganancias->execute([$fechaActual]);
                        $total_ganancias = $select_ganancias->fetch(PDO::FETCH_ASSOC)['importeTotal'];
                     ?>
                     <p class="category"><strong>Ventas Hoy</strong></p>
                     <h3 class="card-title"><?= $total_ganancias ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">attach_money</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $startOfWeek = date("Y-m-d", strtotime('monday this week'));
                        $endOfWeek = date("Y-m-d", strtotime('sunday this week'));
                        
                        $select_ganancias_semana = $conn->prepare("SELECT SUM(importeTotal) AS importeTotal FROM ventas WHERE fecha >= ? AND fecha <= ?");
                        $select_ganancias_semana->execute([$startOfWeek, $endOfWeek]);
         
                        $total_ganancias_semana = $select_ganancias_semana->fetch(PDO::FETCH_ASSOC)['importeTotal'];
                     ?>
                     <p class="category"><strong>Ventas Semana</strong></p>
                     <h3 class="card-title"><?= $total_ganancias_semana ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">attach_money</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $startOfMonth = date("Y-m-01");
                        $endOfMonth = date("Y-m-t");
                        
                        $select_ganancias_mes = $conn->prepare("SELECT SUM(importeTotal) AS importeTotal FROM ventas WHERE fecha >= ? AND fecha <= ?");
                        $select_ganancias_mes->execute([$startOfMonth, $endOfMonth]);
                        
                        $total_ganancias_mes = $select_ganancias_mes->fetch(PDO::FETCH_ASSOC)['importeTotal'];
                     ?>
                     <p class="category"><strong>Ventas Mes</strong></p>
                     <h3 class="card-title"><?= $total_ganancias_mes ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">attach_money</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>
            
         </div>

      </div>

      <div class="wrapper">
         <div class="main-content">
            <div class="row">
               <div class="col-md-12">
                  <div class="table-wrapper">
                        <div class="table-title">
                           <div class="row">
                              <div class="col-sm-6 p-0 d-flex justify-content-lg-start justify-content-center">
                              <h2 class="ml-lg-2">RECLAMOS</h2>
                              </div>
                              <div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
                              </div>
                           </div>
                        </div>
         
                     <table class="table table-striped table-hover">
                        <thead>
                           <tr>
                              <th>Id</th>
                              <th>Nombre</th>
                              <th>Usuario</th>
                              <th>Telefono</th>
                              <th>Reclamo</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if($select_reclamos->rowCount() > 0){
                                 while($fetch_reclamos = $select_reclamos->fetch(PDO::FETCH_ASSOC)){
                           ?>
                              <tr>
                                 <td><?= $fetch_reclamos['id']; ?></td>
                                 <td><?= $fetch_reclamos['nombre']; ?></td>
                                 <td><?= $fetch_reclamos['usuario']; ?></td>
                                 <td><?= $fetch_reclamos['telefono']; ?></td>
                                 <td><?= $fetch_reclamos['reclamo']; ?></td>
                                 <td>
                                 </td>
                              </tr>

                           <?php
                           }
                           }else{
                              if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                 echo '<p class="empty"></p>';
                              } else {
                                 echo '<p class="empty">No hay Reclamos</p>';
                              }
                           }
                        ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
	
	</div>
   <?php endif; ?>

   <!-- GERENTE -->
   <?php if ($rol == '1') : ?>
   <div class="main-content">
      <?php
         $select_cliente = $conn->prepare("SELECT * FROM cliente");
         $select_cliente->execute();
      ?>

      <div class="row">
         <!--  PARTE SUPERIOR -->
      
         <div class="col-lg-3 col-md-6 col-sm-6">	
            
            <div class="card card-stats">
               <div class="card-header">
                  <div class="icon icon-warning">
                        <span class="material-icons">group</span>
                     </div>
                  </div>
                  <div class="card-content">
                  <?php
                     $select_user = $conn->prepare("SELECT COUNT(*) AS total_user FROM usuarioadmin WHERE idrol IN (?, ?, ?, ?)");
                     $select_user->execute(['1','3', '4', '2']);
                     $total_user = $select_user->fetch(PDO::FETCH_ASSOC)['total_user'];
                  ?>
                     <p class="category"><strong>Total</strong></p>
                     <h3 class="card-title"><?= $total_user; ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons text-info">double_arrow</i>
                        <a href="">Empleados </a>
                     </div>
                  </div>
               </div>
            </div>	 

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-rose">
                        <span class="material-icons">person_2</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_user = $conn->prepare("SELECT idRol FROM `usuarioadmin` WHERE idrol = ?");
                        $select_user->execute(['2']);
                        $number_of_user = $select_user->rowCount()
                     ?>
                     <p class="category"><strong>Administrador</strong></p>
                     <h3 class="card-title"><?= $number_of_user ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-info">
                        <span class="material-icons">person</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_user = $conn->prepare("SELECT idRol FROM `usuarioadmin` WHERE idrol = ?");
                        $select_user->execute(['3']);
                        $number_of_user = $select_user->rowCount()
                     ?>
                     <p class="category"><strong>Encargado de Almacen</strong></p>
                     <h3 class="card-title"><?= $number_of_user ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>	

            <div class="col-lg-3 col-md-6 col-sm-6">
               <div class="card card-stats">
                  <div class="card-header">
                     <div class="icon icon-success">
                        <span class="material-icons">person</span>
                     </div>
                  </div>
                  <div class="card-content">
                     <?php
                        $select_user = $conn->prepare("SELECT idRol FROM `usuarioadmin` WHERE idrol = ?");
                        $select_user->execute(['4']);
                        $number_of_user = $select_user->rowCount()
                     ?>
                     <p class="category"><strong>Vendedor</strong></p>
                     <h3 class="card-title"><?= $number_of_user ?></h3>
                  </div>
                  <div class="card-footer">
                     <div class="stats">
                        <i class="material-icons">double_arrow</i>
                        <a href="">Total</a>
                     </div>
                  </div>
               </div>
            </div>	

            
         </div>

      </div>

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
              				<?php if ($rol == '4') : ?>
                        <div class="col-sm-6 p-0 d-flex justify-content-lg-end justify-content-center">
                           <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                           <i class="material-icons">&#xE147;</i> <span>Añadir</span></a>
                           <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                           <i class="material-icons">picture_as_pdf</i> <span>EXCEL</span></a>
                           <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                           <i class="material-icons">picture_as_pdf</i> <span>PDF</span></a>
                           <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                           <i class="material-icons">print</i> <span>IMPRIMIR</span></a>
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
                              <?php if ($rol == '4') : ?>
                              <th>Acciones</th>
                              <?php endif; ?>
                              <?php if ($rol != '4') : ?>
                              <th></th>
                              <?php endif; ?>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if($select_admin->rowCount() > 0){
                                 while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){ 	
                                 
                           ?>
                              <tr>
                              <td><?= $fetch_admin['id']; ?></td>
                              <td><?= $fetch_admin['nombre']; ?></td>
                              <td><?= $fetch_admin['apellido']; ?></td>
                              <td><?= $fetch_admin['dni']; ?></td>
                              <td><?= $fetch_admin['telefono']; ?></td>
                              <td><?= $fetch_admin['descripcion']; ?></td>
                              <?php if ($rol == '4') : ?>
                              <td>
                                 <a href="#editEmployeeModal-<?php echo $fetch_admin['id']; ?>" class="edit" data-toggle="modal">
                                 <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i></a>

                                 <a href="#deleteEmployeeModal-<?php echo $fetch_admin['id']; ?>" class="delete" data-toggle="modal">
                                 <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i></a>
                              </td>
                              <?php endif; ?>
                              <?php if ($rol != '4') : ?>
                                 <td></td>
                              <?php endif; ?>
                           </tr>

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

            </div>
         </div>
      </div>
	
	</div>
   <?php endif; ?>

   <script src="../js/admin_script.js"></script>

</body>
</html>