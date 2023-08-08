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
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Pedidos Realizados</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">Por favor inicie sesión para ver sus pedidos...</p>';
      }else{
         $select_orders = $conn->prepare("SELECT pe.fecha, pe.metpago, pe.estado, pe.cantProd, pe.importeTotal,
         (SELECT GROUP_CONCAT(CONCAT(pr.nombre, ' - cantidad: ', d.cantProd) SEPARATOR '\n')
          FROM detallepedido d
          JOIN producto pr ON d.idProducto = pr.id
          WHERE d.idPedido = pe.id) AS productos,
         c.nombre, c.apellido, c.telefono
         FROM pedido pe
         JOIN cliente c ON pe.idCliente = c.id WHERE c.idUsuario=?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Fecha : <span><?= $fetch_orders['fecha']; ?></span></p>
      <p>Nombre : <span><?= $fetch_orders['nombre'].' '.$fetch_orders['apellido']; ?></span></p>
      <p>Numero : <span><?= $fetch_orders['telefono']; ?></span></p>
      <p>Metodo de Pago : <span><?= $fetch_orders['metpago']; ?></span></p>
      <p>Tus productos : <span><?= $fetch_orders['productos']; ?></span></p>
      <p>Total a pagar : <span>$<?= $fetch_orders['importeTotal']; ?>/-</span></p>
      <p>Estado : <span style="color:<?php if($fetch_orders['estado'] == 'Pendiente'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['estado']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">No hay pedidos registrados</p>';
      }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>