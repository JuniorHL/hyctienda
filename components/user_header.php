<?php
   if(isset($message)){
      foreach($message as $msg){
         echo '
         <div class="message">
            <span>'.$msg.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <a><img src="images/logohyc.jpg" style="width: 100px; height: 50px;"></a>

      <nav class="navbar">
         <a href="home.php">Inicio</a>
         <a href="tienda.php">Tienda</a>
         <a href="tuspedidos.php">Tus Pedidos</a>
         <a href="comentario.php">Reseñas</a>
      </nav>

      <div class="icons">
         <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `listadeseos` WHERE idUsuario = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();
            $count_cart_items = $conn->prepare("SELECT d.id FROM detallecarrito d INNER JOIN carrito c ON d.idCarrito = c.id  WHERE c.idUsuario = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href=""><i class="fas fa-search"></i></a>
         <a href="favorito.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="carrito.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php 
            $verificarcliente = $conn->prepare("SELECT id FROM cliente WHERE idUsuario = ?");
            $verificarcliente->execute([$user_id]);
            if($verificarcliente->rowCount() > 0){
               $select_profile = $conn->prepare("SELECT u.usuario, c.nombre, c.apellido, c.DNI, c.telefono  FROM usuariocliente u INNER JOIN cliente c ON ? = c.idUsuario WHERE u.id = ?");
               $select_profile->execute([$user_id,$user_id]);
            }else{
               $select_profile = $conn->prepare("SELECT * FROM usuariocliente WHERE id = ?"); 
               $select_profile->execute([$user_id]);
            }
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile["usuario"]; ?></p>
         <a href="mensaje.php" class="option-btn">Reclamo - Sugerencia</a>
         <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('¿Desea salir?');">Cerrar Sesion</a> 
         <?php
            }else{
         ?>
         <div class="flex-btn">
            <a href="usuarioRegistro.php" class="option-btn">Registrarse</a>
            <a href="usuariologin.php" class="option-btn">Iniciar Sesion</a>
         </div>
         <?php
            }
         ?>      
         
         
      </div>

   </section>

</header>