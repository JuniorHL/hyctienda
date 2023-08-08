<?php
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
?> 

<header class="header">

   <section class="flex">

      <a href="../admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <ul>
            <li>
               <a href="../admin/dashboard.php">INICIO</a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a></a>
            </li>
            
            <li>
               <a>PRODUCTOS</a>
               <ul class="submenu">
                  <li><a href="../admin/productos.php">Ver Productos</a></li>
                  <li><a href="../admin/agregar_producto.php">Agregar Producto</a></li>
               </ul>
            </li>
            
            <li>
               <a></a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a href="../admin/pedidos.php">PEDIDOS</a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a href="#">EMPLEADOS</a>
               <ul class="submenu">
                  <li><a href="../admin/empleados.php">Ver Empleados</a></li>
                  <li><a href="../admin/registrar_admin.php">Registrar Empleado</a></li>
               </ul>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a href="reclamos.php">MENSAJES</a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a></a>
            </li>
            <li>
               <a href="#">VENTA</a>
            </li>
         </ul>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `usuarioadmin` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['usuario']; ?></p>
         <a href="../components/cerrarsesion.php" class="delete-btn" onclick="return confirm('¿Seguro de Cerrar Sesion?');">Cerrar Sesion</a> 
      </div>

   </section>

</header>