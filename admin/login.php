<?php

include '../components/connect.php';

session_start();

if(isset($_SESSION['admin_id'])){
   $admin_id = $_SESSION['admin_id'];
}else{
   $admin_id = '';
};

if(isset($_POST['submit'])){

   $usuario = $_POST['name'];
   $usuario = filter_var($usuario, FILTER_SANITIZE_STRING);
   $contraseña = $_POST['pass'];
   $contraseña = filter_var($contraseña, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT id,usuario,contraseña FROM `usuarioadmin` WHERE usuario = ?");
   $select_admin->execute([$usuario]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if(($select_admin->rowCount() > 0)&& password_verify($contraseña, $row['contraseña'])){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
   }else{
      $message[] = 'Usuario y/o Contraseña incorrecta';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
   <title>H&C TIENDA</title>
   <link rel="icon" type="image/jpg" href="../images/logohyc.jpg">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

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

<section class="form-container">

   <form action="" method="post">
      <h3>Iniciar Sesion</h3>
      <h4>usuario: 'rol' - contraseña: 'rol'123</h4>
      <input type="text" name="name" required placeholder="Ingrese su usuario" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Ingrese su contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Ingresar" class="btn" name="submit">
   </form>


</section>
</body>
</html>