<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:usuariologin.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $lastname = $_POST['lastname'];
   $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);
   $dni = $_POST['dni'];
   $dni = filter_var($dni, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $total_price = $_POST['total_price'];
   $total_products = $_POST['total_prod'];
   $telef_vacio= $_POST['telefono_vacio'];
   $check_cart = $conn->prepare("SELECT * FROM `carrito` WHERE idUsuario = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      $idcliente=0;
      if ($telef_vacio=='true'){
         $insert_cliente = $conn->prepare("INSERT INTO `cliente`(nombre,apellido,DNI,telefono,idUsuario) VALUES(?,?,?,?,?)");
         $insert_cliente->execute([$name,$lastname,$dni,$number,$user_id]);
         $idcliente = $conn->lastInsertId();
      }else{
         $update_cliente = $conn->prepare("UPDATE `cliente` SET nombre=?, apellido=?, DNI=?, telefono=? WHERE idUsuario=?");
         $update_cliente->execute([$name, $lastname, $dni, $number, $user_id]);
         $selectidcliente = $conn->prepare("SELECT id FROM cliente WHERE idUsuario = ?");
         $selectidcliente->execute([$user_id]);
         $result = $selectidcliente->fetch();
         $idcliente = $result['id'];
      }

      

      $select_cart = $conn->prepare("SELECT c.id,d.idProducto, d.cantProd, d.precioProd, d.importeTotal FROM carrito c INNER JOIN detallecarrito d ON d.idCarrito = c.id  WHERE c.idUsuario = ?");
      $select_cart->execute([$user_id]);
      $contar = 0;
      while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
             $select_stock = $conn->prepare("
               SELECT p.stock - COALESCE((
                  SELECT SUM(dp.cantProd)
                  FROM detallepedido dp
                  INNER JOIN pedido pe ON dp.idPedido = pe.id
                  WHERE dp.idProducto = p.id
                     AND pe.estado = 'Pendiente'
               ), 0) AS stock_actualizado
               FROM producto p
               WHERE p.id = ?
         ");
            $idProducto = $fetch_cart['idProducto']; // Valor del parámetro id
            $select_stock->execute([$idProducto]);
            $stock = $select_stock->fetchColumn();
            $stock = (int) $stock;
            if ((int) $fetch_cart['cantProd']>$stock){
               $contar=$contar+1;
            }
            
     }

     if($contar>0){
      $contar=0;
      $_SESSION['message'] = array('Las cantidades exceden al stock, corríjalas');
      header('Location: carrito.php');
      exit;
     }else{
      date_default_timezone_set('America/Lima');
      $fecha = date('Y-m-d H:i:s');
      $fecharcojo = date('Y-m-d H:i:s', strtotime($fecha . ' + 24 hours'));
      $insert_pedido = $conn->prepare("INSERT INTO `pedido` (idCliente, fecha,fechalimit, cantProd, importeTotal, metpago, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $insert_pedido->execute([$idcliente, $fecha,$fecharcojo, $total_products, $total_price, $method, "Pendiente"]);
      $idpedido = $conn->lastInsertId();

      $select_cart = $conn->prepare("SELECT c.id,d.idProducto, d.cantProd, d.precioProd, d.importeTotal FROM carrito c INNER JOIN detallecarrito d ON d.idCarrito = c.id  WHERE c.idUsuario = ?");
      $select_cart->execute([$user_id]);
      $contar = 0;
      while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               $insert_detallepedido = $conn->prepare("INSERT INTO `detallepedido` (idPedido, idProducto, cantProd, precioProd, importeTotal) VALUES (?, ?, ?, ?, ?)");
               $insert_detallepedido->execute([$idpedido, $fetch_cart['idProducto'], $fetch_cart['cantProd'], $fetch_cart['precioProd'], $fetch_cart['importeTotal']]);                   
     }

     $delete_cart = $conn->prepare("DELETE FROM carrito WHERE idUsuario = ?");
     $delete_cart->execute([$user_id]);


      $message[] = 'Pedido realizado exitosamente';
     }
      
   }else{
      $message[] = 'Sin productos en tu carrito';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>H&C TIENDA</title>
   <link rel="icon" type="image/jpg" href="../images/logohyc.jpg">
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Tus productos</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT c.importeTotal,c.cantProd AS totalp, p.nombre, d.cantProd,d.precioProd  FROM carrito c INNER JOIN 
         detallecarrito d ON d.idCarrito = c.id INNER JOIN producto p ON d.idProducto = p.id WHERE c.idUsuario = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['nombre'].' ('.$fetch_cart['precioProd'].' x '. $fetch_cart['cantProd'].')';
               $total_products = ($fetch_cart['totalp']);
               $grand_total = ($fetch_cart['importeTotal']);
      ?>
         <p> <?= $fetch_cart['nombre']; ?> <span>(<?= 'S/.'.$fetch_cart['precioProd'].' x '. $fetch_cart['cantProd']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">No hay productos</p>';
         }
      ?>
         <input type="hidden" name="total_prod" value="<?= $total_products; ?>" value="">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">Total a pagar: <span>S/.<?= $grand_total; ?></span></div>
      </div>

      <h3>Realizar pedido</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Nombre:</span>
            <input type="text" name="name"  class="box" value="<?= isset($fetch_profile["nombre"]) ? $fetch_profile["nombre"] : '' ?>" required>
         </div>
         <div class="inputBox">
            <span>Apellido:</span>
            <input type="text" name="lastname"  class="box" value="<?= isset($fetch_profile["apellido"]) ? $fetch_profile["apellido"] : '' ?>" required>
         </div>
         <div class="inputBox">
            <span>DNI:</span>
            <input type="text" name="dni"  class="box" value="<?= isset($fetch_profile["DNI"]) ? $fetch_profile["DNI"] : '' ?>" required>
         </div>
         <div class="inputBox">
            <span>Numero :</span>
            <input type="number" name="number"  class="box" value="<?= isset($fetch_profile["telefono"]) ? $fetch_profile["telefono"] : '' ?>" required>
            <?php if (empty($fetch_profile["telefono"])): ?>
               <input type="hidden" name="telefono_vacio" value="true">
            <?php else: ?>
               <input type="hidden" name="telefono_vacio" value="false">
            <?php endif; ?>
         </div>
         <div class="inputBox">
            <span>Metodo de Pago :</span>
            <select name="method" class="box" required>
               <option value="Efectivo">Efectivo</option>
               <option value="Yape/Plin">Yape/Plin</option>
               <option value="Tarjeta">Tarjeta</option>
            </select>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Pedir">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>