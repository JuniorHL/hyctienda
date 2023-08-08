<?php


include '../components/connect.php';



    if(isset($message)){
        foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
        }
    }



    $select_rol = $conn->prepare("SELECT idRol FROM usuarioadmin WHERE id = ?");
    $select_rol->execute([$admin_id]);
    $row = $select_rol->fetch(PDO::FETCH_ASSOC);
    $rol = $row['idRol'];

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>H&C TIENDA</title>
	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
	<!----css3---->
    <link rel="stylesheet" href="../css/custom.css">
	<!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
	
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <div class="body-overlay"></div>
            <!-------------------------sidebar------------>
            <!-- Sidebar  -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <a href="../admin/dashboard.php"><h3><img src="../images/logohyc.jpg" class="img-fluid"/><span>H&C TIENDA</span></h3></a>
                </div>
                
                <ul class="list-unstyled components">
                    
                    <?php if ($rol == '1' || $rol == '2' || $rol == '3' || $rol == '4') : ?>
                    <li  class="active">
                        <a href="../admin/dashboard.php" class="dashboard"><i class="material-icons">dashboard</i><span>Dashboard</span></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($rol == '1' || $rol == '2') : ?>
                    <li  class="">
                        <a href="../admin/empleados.php"><i class="material-icons">person</i><span>Usuarios</span></a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ($rol == '1' || $rol == '3' || $rol == '4') : ?>
                    <li class="dropdown">
                        <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">store</i><span>Productos</span></a>
                        <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                            <li>
                                <a href="../admin/productos.php"><i class="material-icons">inventory</i><span>Productos</span></a>
                            </li>
                            <?php if ($rol == '1' || $rol == '3') : ?>
                            <li>
                                <a href="../admin/movimiento.php"><i class="material-icons">production_quantity_limits</i><span>Movimientos</span></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if ($rol == '1' || $rol == '4') : ?>
                    <li class="dropdown">
                        <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">add_business</i><span>Ventas</span></a>
                        <ul class="collapse list-unstyled menu" id="pageSubmenu3">
                            <li>
                                <a href="../admin/ventas.php"><i class="material-icons">receipt_long</i><span>Lista de ventas</span></a>
                            </li>
                            <?php if ($rol == '4') : ?>
                            <li>
                                <a href="../admin/agregar_venta.php"><i class="material-icons">point_of_sale</i><span>Agregar venta</span></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if ($rol == '4') : ?>
                    <li class="">
                        <a href="../admin/pedidos.php"><i class="material-icons">list_alt</i><span>Pedidos</span></a>
                    </li>
                    <?php endif; ?>

                    <?php if ($rol == '1' || $rol == '2') : ?>
                    <li class="dropdown">
                        <a href="#pageSubmenu4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">person_2</i><span>Clientes</span></a>
                        <ul class="collapse list-unstyled menu" id="pageSubmenu4">
                            <li>
                                <a href="../admin/clientes.php"><i class="material-icons">account_circle</i><span>Clientes</span></a>
                            </li>
                            <li>
                                <a href="../admin/reclamos.php"><i class="material-icons">forum</i><span>Reclamos</span></a>
                            </li>

                        </ul>
                    </li>
                    <?php endif; ?>   
                     

				<!--
				<li class="dropdown">
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
					<i class="material-icons">store</i><span>Productos</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                        <li>
                            <a href="#">Page 1</a>
                        </li>
                        <li>
                            <a href="#">Page 2</a>
                        </li>
                        <li>
                            <a href="#">Page 3</a>
                        </li>
                    </ul>
                </li>
                -->
               
            </ul> 

            </nav>
        <!--------page-content---------------->
        <div id="content">
            <!--top--navbar----design--------->
            <div class="top-navbar">
                <div class="xp-topbar">
                    <!-- Start XP Row -->
                    <div class="row"> 
                        <!-- Start XP Col -->
                        <div class="col-2 col-md-1 col-lg-1 order-2 order-md-1 align-self-center">
                            <div class="xp-menubar">
                                <span class="material-icons text-white">signal_cellular_alt</span>
                            </div>
                        </div> 
                        <!-- End XP Col -->
  
                        <div class="col-md-5 col-lg-3 order-3 order-md-2">
                          <div class="xp-searchbar">
                                <form>
                                </form>
                            </div>
                        </div>
                    
                        <!-- Start XP Col -->
                        <div class="col-10 col-md-6 col-lg-8 order-1 order-md-3">
                            <div class="xp-profilebar text-right">
                                <nav class="navbar p-0">
                            <ul class="nav navbar-nav flex-row ml-auto">   
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="#" data-toggle="dropdown">
                                        <img src="../images/usuario.png" style="width:40px; border-radius:50%;"/>
                                        <span class="xp-user-live"></span>
                                    </a>
                                    <ul class="dropdown-menu small-menu">
                                            
                                        <li>
                                            <a href="../home.php" target="_blank"><span class="material-icons">store</span>Tienda</a>
                                        </li>
                                        <li>
                                            <a href="../components/cerrarsesion.php"><span class="material-icons">logout</span>Salir</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>     
                        </div>
                    </div>
                </div> 
            </div>
        </div>


    <!----------html code compleate----------->
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery-3.3.1.min.js"></script>
    
    <script type="text/javascript">
          
          $(document).ready(function(){
            $(".xp-menubar").on('click',function(){
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
            
            $(".xp-menubar,.body-overlay").on('click',function(){
                $('#sidebar,.body-overlay').toggleClass('show-nav');
            });
            
        }); 
    </script>

</body>
</html>