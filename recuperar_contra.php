<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['cambiarcontra'])){
    // Obtener los datos enviados desde el formulario
    $user = $_POST['user'];
    $nuevaContraseña = $_POST['pass'];
    $confirmarContraseña = $_POST['cpass'];
  
    // Realizar una consulta a la base de datos para verificar los datos ingresados
    $select_user = $conn->query("SELECT * FROM usuariocliente WHERE usuario = '$user'");
    // Verificar si se encontró una coincidencia en la base de datos
    if ($select_user->rowCount() > 0) {
      // Validar la nueva contraseña y confirmar que coincidan
      if ($nuevaContraseña === $confirmarContraseña && strlen($nuevaContraseña) >= 8) {

        $password = password_hash($nuevaContraseña, PASSWORD_BCRYPT);
        // Actualizar la contraseña en la base de datos
        $updateQuery = "UPDATE usuariocliente SET contraseña = '$password' WHERE usuario = '$user'";
        $conn->query($updateQuery);
        // Redireccionar al usuario a una página de éxito o mostrar un mensaje de éxito
        $message[] = 'Usuario actualizado correctamente'; 
      } else {
        // Las contraseñas no coinciden o no cumplen con los requisitos
        // Mostrar un mensaje de error o redireccionar a una página de error
        $message[] = 'Las contraseñas no coinciden'; 
      }
    } else {
      // No se encontró una coincidencia en la base de datos
      // Mostrar un mensaje de error o redireccionar a una página de error
      $message[] = 'Usuario no encontrado'; 
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
      <h3>Cambiar Contraseña</h3>
      <input type="text" name="user" required placeholder="Ingrese su nombre de usuario" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Ingrese su nueva contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Repita la nueva contraseña" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" name = "cambiarcontra"value="Actualizar" class="btn" name="submit">>
      <a href="usuariologin.php" class="option-btn">Volver</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>