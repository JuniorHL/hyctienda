<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


if(isset($_POST['send'])){

   $celular = $_POST['celular'];
   $celular = filter_var($celular, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `reclamos` WHERE idUsuario=? AND reclamo = ?");
   $select_message->execute([$user_id, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Reclamo y/o sugerencia ya enviado';
   }else{
      include 'components/user_header.php';
      $insert_message = $conn->prepare("INSERT INTO `reclamos`(idUsuario,reclamo) VALUES(?,?)");
      $insert_message->execute([$user_id,$msg]);
      if (empty($fetch_profile['telefono'])){
         $insert_celular = $conn->prepare("INSERT INTO `cliente`(telefono,correo,idUsuario) VALUES(?,(SELECT correo FROM usuariocliente WHERE id=?),?)");
         $insert_celular->execute([$celular,$user_id,$user_id]);
      }else{
         $update_cel = $conn->prepare("UPDATE `cliente` SET telefono=? WHERE idUsuario=?");
         $update_cel->execute([$celular,$user_id]);
      }
      $message[] = 'Reclamo y/o sugerencia enviado exitosamente';
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

<section class="contact">

   <form action="" method="post">
      <h3>Tu Reclamo y/o Sugerencia</h3>
      <input type="text" name="name" class="box" value="<?= $fetch_profile["usuario"]; ?>">
      <input type="number" name="celular" class="box" placeholder="Escriba su numero de celular" value="<?= isset($fetch_profile["telefono"]) ? $fetch_profile["telefono"] : '' ?>">
      <textarea name="msg" class="box" placeholder="Escribe tu reclamo y/o sugerencia" cols="30" rows="10"></textarea>
      <input type="submit" value="Enviar" name="send" class="btn">
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>