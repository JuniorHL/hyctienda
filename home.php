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
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
   <title>H&C TIENDA</title>
   <link rel="icon" type="image/jpg" href="../images/logohyc.jpg">

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/oferta.png" alt="">
         </div>
         <div class="content">
            <span>¡NUEVO!</span>
            <h3>Almohadas de napa</h3>
            <a href="tienda.php" class="btn">Compre ahora</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/oferta2.jpg" alt="">
         </div>
         <div class="content">
            <span>¡OFERTA!</span>
            <h3>Edredones piel con carnero</h3>
            <a href="tienda.php" class="btn">Compre ahora</a>
         </div>
      </div>


      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">CATEGORIAS</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="categoria.php?idcat=1" class="swiper-slide slide">
      <img src="images/almohada.png" alt="">
      <h3>Almohadas</h3>
   </a>

   <a href="categoria.php?idcat=2" class="swiper-slide slide">
      <img src="images/edredon.jpg" alt="">
      <h3>Edredones</h3>
   </a>

   <a href="categoria.php?idcat=3" class="swiper-slide slide">
      <img src="images/frazada.png" alt="">
      <h3>Frazada</h3>
   </a>

   <a href="categoria.php?idcat=4" class="swiper-slide slide">
      <img src="images/funda.jpg" alt="">
      <h3>Fundas</h3>
   </a>

   <a href="categoria.php?idcat=5" class="swiper-slide slide">
      <img src="images/sabana.png" alt="">
      <h3>Sabanas</h3>
   </a>

   <a href="categoria.php?idcat=6" class="swiper-slide slide">
      <img src="images/otros.png" alt="">
      <h3>Otros</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Productos</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `producto` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['nombre']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['precio']; ?>">
      <input type="hidden" name="stock" value="<?= $fetch_product['stock']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['imagen1']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="ver_producto.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['imagen1']; ?>" alt="">
      <div class="name"><?= $fetch_product['nombre']; ?></div>
      <div  class="stock"><span>Stock: </span><?= $fetch_product['stock']; ?></div>
      <div class="flex">
         <div class="price"><span>S/.</span><?= $fetch_product['precio']; ?></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Comprar" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">No hay productos disponibles</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>