<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   $usuario = $_POST['usuario'];
   $usuario = filter_var($usuario, FILTER_SANITIZE_STRING);
   $pass = $_POST['contraseña'];
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = $_POST['rcontraseña'];
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT COUNT(*) AS count FROM `usuariocliente` WHERE usuario = ?");
   $select_user->execute([$usuario]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($row['count'] > 0) {
      $message[] = 'El usuario ya existe';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Las contraseñas no coinciden';
      } elseif (strlen($pass) <= 7) {
         $message[] = 'La contraseña debe tener más de 8 caracteres';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `usuariocliente` (usuario, contraseña) VALUES (?, ?)");
         $password = password_hash($cpass, PASSWORD_BCRYPT);
         $insert_user->execute([$usuario, $password]);
         $message[] = 'Registro exitoso, ahora inicie sesión.';
      }
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

<section class="form-container">

   <form action="" method="post">
      <h3>Registrarse</h3>
      <input type="text" name="usuario" required placeholder="Ingrese su nombre de usuario" maxlength="20"  class="box">
      <input type="password" name="contraseña" required placeholder="Ingrese su contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="rcontraseña" required placeholder="Repita la contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Registrarse" class="btn" name="submit">
      <p>¿Ya tienes una cuenta?</p>
      <a href="usuariologin.php" class="option-btn">Iniciar Sesion</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>