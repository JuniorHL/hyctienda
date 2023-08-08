<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
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


<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/opinion.jpg" style="width: 550px; height: 400px;">
      </div>

      <div class="content">
         <h3>DEJANOS TU COMENTARIO</h3>
         <p> 
            Nos interesa saber cómo fue tu experiencia con la compra de tus productos, tus comentarios son realmente importante para nosotros y así seguir mejorando nuestro servicio.
            ¡Agradecemos muchísimo tus comentarios!
         </p>
         <?php
            if($user_id !== '') {
               echo '<a href="tuopinion.php" class="btn">Deja tu comentario</a>';
            } else {
               echo '<a href="usuariologin.php" class="btn">Deja tu comentario</a>';
            }
         ?>
      </div>

   </div>

</section>

<section class="reviews">
   
   <h1 class="heading">Reseña de nuestros clientes</h1>

   <div class="swiper reviews-slider">

   <div class="swiper-wrapper">

      <?php
         $select_sms = $conn->prepare("SELECT m.id,m.mensaje,u.usuario FROM mensajes m INNER JOIN usuariocliente u WHERE m.Idusuario = u.id"); 
         $select_sms->execute();
         if($select_sms->rowCount() > 0){
            while($fetch_sms = $select_sms->fetch(PDO::FETCH_ASSOC)){
      ?>

      <form action="" method="post" class="swiper-slide slide">
         <input type="hidden" name="pid" value="<?= $fetch_sms['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_sms['usuario']; ?>">
         <input type="hidden" name="message" value="<?= $fetch_sms['mensaje']; ?>">
         
         <img src="images/usuario.png" alt="">
         <div class="name"><p><?= $fetch_sms['usuario']; ?></p></div>
         <div class="message"><h3><?= $fetch_sms['mensaje']; ?></h3></div>
         
      </form>

   <?php
      }
   }else{
      echo '<p class="empty">No hay mensajes disponibles</p>';
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

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>