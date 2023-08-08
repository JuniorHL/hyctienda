<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `reclamos` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:reclamos.php');
}

   $select_reclamos = $conn->prepare("SELECT r.id, r.reclamo, u.usuario, c.nombre, c.telefono FROM reclamos r INNER JOIN usuariocliente u ON
                                    r.idUsuario=u.id INNER JOIN cliente c ON c.idUsuario=u.id");
   $select_reclamos->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
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




<!--
<section class="contacts">

<h1 class="heading">Reclamos y/o sugerencias</h1>

<div class="box-container">

   <?php
      $select_reclamos = $conn->prepare("SELECT r.id, r.reclamo, u.usuario, c.nombre, c.telefono FROM reclamos r INNER JOIN usuariocliente u ON
                                          r.idUsuario=u.id INNER JOIN cliente c ON c.idUsuario=u.id");
      $select_reclamos->execute();
      if($select_reclamos->rowCount() > 0){
         while($fetch_reclamos = $select_reclamos->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
   <p> Nombre : <span><?= $fetch_reclamos['nombre']; ?></span></p>
   <p> Usuario : <span><?= $fetch_reclamos['usuario']; ?></span></p>
   <p> Celular : <span><?= $fetch_reclamos['telefono']; ?></span></p>
   <p> Mensaje : <span><?= $fetch_reclamos['reclamo']; ?></span></p>
   <a href="reclamos.php?delete=<?= $fetch_reclamos['id']; ?>" onclick="return confirm('¿Seguro que desea eliminar este reclamo?');" class="delete-btn">Eliminar</a>

   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No tiene reclamos</p>';
      }
   ?>

</div>

</section>
   -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>