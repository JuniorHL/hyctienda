<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:usuariologin.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `listadeseos` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `carrito` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'Ya añadiste este producto a tus favoritos';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'Ya esta agregado al carrito';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `listadeseos`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         $message[] = 'Añadido a tus favoritos';
      }

   }

}

if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:usuariologin.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);

      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `carrito` c INNER JOIN `detallecarrito` d ON c.id = d.idCarrito WHERE d.idProducto = ? AND c.idUsuario = ?");
      $check_cart_numbers->execute([$pid, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'El producto ya esta añadido';
      }else{

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `listadeseos` WHERE idProducto = ? AND idUsuario = ?");
         $check_wishlist_numbers->execute([$pid, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `listadeseos` WHERE idProducto = ? AND idUsuario = ?");
            $delete_wishlist->execute([$pid, $user_id]);
         }

         $checkcart = $conn->prepare("SELECT id FROM carrito  WHERE idUsuario = ?");
         $checkcart->execute([$user_id]);

         $price=floatval($price);
         $qty = intval($qty);
         $imptotal=$price*$qty;
         $imptotal=number_format($imptotal,2,'.','');

         if($checkcart->rowCount()>0){
         $resultado = $checkcart->fetch();
         $carrito_id = $resultado['id'];
         $insert_detalle = $conn->prepare("INSERT INTO `detallecarrito` (idCarrito, idProducto, cantProd, precioProd, importeTotal) VALUES (?, ?, ?, ?, ?)");
         $insert_detalle->execute([$carrito_id, $pid, $qty, $price, $imptotal]);
         $update_carrito=$conn->prepare("UPDATE `carrito` SET cantProd=cantProd+?, importeTotal=importeTotal+? WHERE idUsuario=?");
         $update_carrito->execute([$qty, $imptotal, $user_id]);
         }else{
         $insert_cart = $conn->prepare("INSERT INTO `carrito` (idUsuario, cantProd, ImporteTotal) VALUES (?, ?, ?)");
         $insert_cart->execute([$user_id, $qty, $imptotal]);
         $carrito_id = $conn->lastInsertId();
         $insert_detalle = $conn->prepare("INSERT INTO `detallecarrito` (idCarrito, idProducto, cantProd, precioProd, importeTotal) VALUES (?, ?, ?, ?, ?)");
         $insert_detalle->execute([$carrito_id, $pid, $qty, $price, $imptotal]);
         }
         // Obtiene el último ID insertado en la tabla "carrito"

         $message[] = 'Producto añadido al carrito';

         
      }

   }

}

?>