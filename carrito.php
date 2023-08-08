<?php

include 'components/connect.php';

session_start();
if (isset($_SESSION['message'])) {
   $message = $_SESSION['message'];
   unset($_SESSION['message']); // Eliminar el mensaje de la sesión
} else {
   $message = array(); // Establecer un array vacío si no hay mensaje
}

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:usuariologin.php');
};

if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $carrid = $_POST['idcarr'];
   $select_carrito_id = $conn->prepare("SELECT id FROM `detallecarrito` WHERE idCarrito = ?");
   $select_carrito_id->execute([$carrid]);
   if ($select_carrito_id->rowCount() == 1) {
      $delete_cart_item = $conn->prepare("DELETE FROM `carrito` WHERE idUsuario = ?");
      $delete_cart_item->execute([$user_id]);
      header('location:carrito.php');
   }else{
      $delete_cart_item = $conn->prepare("DELETE FROM `detallecarrito` WHERE id = ?");
      $delete_cart_item->execute([$cart_id]);
      $carrito_id = $select_carrito_id->fetchColumn();
      $update_cart = $conn->prepare("UPDATE `carrito` c
                                    SET c.cantProd = (SELECT SUM(d.cantProd) FROM `detallecarrito` d WHERE d.idCarrito = ?),
                                          c.importeTotal = (SELECT SUM(d.importeTotal) FROM `detallecarrito` d WHERE d.idCarrito = ?)
                                    WHERE c.id = ?");      
      $update_cart->execute([$carrito_id, $carrito_id, $carrito_id]);
   }
   
}


if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `carrito` WHERE idUsuario = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:carrito.php');
}

if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `detallecarrito` SET cantProd = ?, importeTotal = precioProd * ? WHERE id = ?");
   $update_qty->execute([$qty, $qty, $cart_id]);
   $select_carrito_id = $conn->prepare("SELECT idCarrito FROM `detallecarrito` WHERE id = ?");
   $select_carrito_id->execute([$cart_id]);
   $carrito_id = $select_carrito_id->fetchColumn();
   $update_cart = $conn->prepare("UPDATE `carrito` c
                                   SET c.cantProd = (SELECT SUM(d.cantProd) FROM `detallecarrito` d WHERE d.idCarrito = ?),
                                       c.importeTotal = (SELECT SUM(d.importeTotal) FROM `detallecarrito` d WHERE d.idCarrito = ?)
                                   WHERE c.id = ?");
         
   $update_cart->execute([$carrito_id, $carrito_id, $carrito_id]);
   $message[] = "carrito actualizado correctamente";
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

<section class="products shopping-cart">

   <h3 class="heading">Carritos de compras</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT c.id AS idcarr ,c.importeTotal AS subtotal, d.id,d.idProducto, d.cantProd, d.precioProd, d.importeTotal, p.nombre, p.imagen1 FROM carrito c INNER JOIN detallecarrito d ON c.id = d.idCarrito INNER JOIN producto p ON d.idProducto = p.id WHERE c.idUsuario = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <input type="hidden" name="idcarr" value="<?= $fetch_cart['idcarr']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_cart['idProducto']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['imagen1']; ?>" alt="">
      <div class="name"><?= $fetch_cart['nombre']; ?></div>
      <div class="flex">
         <div class="price">$<?= $fetch_cart['precioProd']; ?>/-</div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['cantProd']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <div class="sub-total"> sub total:  <span>$<?= $sub_total = ($fetch_cart['importeTotal']); ?>/-</span> </div>
      <input type="submit" value="Eliminar producto" onclick="return confirm('¿Seguro que desea eliminar este producto de su lista?');" class="delete-btn" name="delete">
   </form>
   <?php
   $grand_total =$fetch_cart['subtotal'];
      }
   }else{
      echo '<p class="empty">No hay productos en tu carrito</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p> total : <span>$<?= $grand_total; ?>/-</span></p>
      <a href="tienda.php" class="option-btn">continuar comprando</a>
      <a href="carrito.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('¿Desea eliminar todos los productos?');">Eliminar todos los productos</a>
      <a href="compra.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Procesar pedido</a>
   </div>

</section>

<?php if (!empty($message)){
   $message="";
}
include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>