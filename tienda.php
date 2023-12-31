<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

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

<section class="products">

   <h1 class="heading">Productos</h1>

   <div class="box-container">

   <?php
     $select_products = $conn->prepare("SELECT p.id,p.nombre, p.precio, p.stock - COALESCE((
      SELECT SUM(dp.cantProd)
      FROM detallepedido dp
      INNER JOIN pedido pe ON dp.idPedido = pe.id
      WHERE dp.idProducto = p.id
         AND pe.estado = 'Pendiente'
   ), 0) AS stock_act, p.imagen1 FROM producto p"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         if($fetch_product['stock_act']>0){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['nombre']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['precio']; ?>">
      <input type="hidden" name="stock" value="<?= $fetch_product['stock_act']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['imagen1']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="ver_producto.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['imagen1']; ?>" alt="">
      <div class="name"><?= $fetch_product['nombre']; ?></div>
      <div  class="stock"><span>Stock: </span><?= $fetch_product['stock_act']; ?></div>
      <div class="flex">
         <div class="price"><span>S/.</span><?= $fetch_product['precio']; ?></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Comprar" class="btn" name="add_to_cart">
   </form>
   <?php
      }}
   }else{
      echo '<p class="empty">no products found!</p>';
   }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>