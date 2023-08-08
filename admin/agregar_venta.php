<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
$_SESSION['lista'] = (isset( $_SESSION['lista'])) ?  $_SESSION['lista'] : [];
if(!isset($admin_id)){
   header('location:login.php');
};

$select_cliente = $conn->prepare("SELECT * FROM cliente");
$select_cliente->execute();
$clientes = $select_cliente->fetchAll();

if(isset($_POST['agregar'])){
    
    if(isset($_POST['codigo'])) {
        $codigo = $_POST['codigo'];
        $select_prod = $conn->prepare("SELECT * FROM producto WHERE id = ?");
        $select_prod->execute([$codigo]);
        $producto = $select_prod->fetchAll();
        if(!$producto) {
            echo "
            <script type='text/javascript'>
                window.location.href='vender.php'
                alert('No se ha encontrado el producto')
            </script>";
            return;
        }    
       
        if (!isset($_SESSION['lista'])) {
            $_SESSION['lista'] = array(); // Crea el arreglo $_SESSION['lista'] si no existe
        }
        foreach ($producto as $p) {
            $subtotal = $p['precio'] * 1; // Cálculo del subtotal (precio * cantidad)
            $item = array(
                'id' => $p['id'],
                'nombre' => $p['nombre'],
                'precio' => $p['precio'],
                'cantidad' => 1,
                'subtotal' => $subtotal
            );
    
            $_SESSION['lista'][] = $item; // Agrega el producto al arreglo $_SESSION['lista']
        } // Agrega el producto al arreglo $_SESSION['lista']

        unset($_POST['codigo']);
        header("location: agregar_venta.php");
    }
}

if (isset($_POST['edit_id'])) {
    $editId = $_POST['edit_id'];
    $editCantidad = $_POST['cantidad'];

    foreach ($_SESSION['lista'] as &$item) {
        if ($item['id'] == $editId) {
            $item['cantidad'] = $editCantidad;
            $item['subtotal'] = $item['precio'] * $editCantidad;
            break;
        }
    }
}

if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    foreach ($_SESSION['lista'] as $key => $item) {
        if ($item['id'] == $deleteId) {
            unset($_SESSION['lista'][$key]);
            break;
        }
    }
}

$total = 0; // Variable para almacenar el total
$cantotal=0;
foreach ($_SESSION['lista'] as $item) {
    $subtotal = $item['subtotal'];
    $cantidad = $item['cantidad']; // Obtener el valor del subtotal del item actual
    $total += $subtotal;
    $cantotal+=$cantidad; // Acumular el subtotal en la variable $total
}

$clienteSeleccionado = "";

if (isset($_GET['quitar'])){
    $clienteSeleccionado = "";
}

if (isset($_POST['select_cliente'])){
    $idCliente = $_POST['idCliente'];
    $select_clientid = $conn->prepare("SELECT * FROM cliente WHERE id = ?");
    $select_clientid->execute([$idCliente]);
    $clienteSeleccionado = $select_clientid->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['cancelar'])){
    session_start();
    $_SESSION['lista'] = [];
    header("location: agregar_venta.php");
}

if(isset($_GET['registrar'])){
    $cuenta = 0;
    if (isset($_GET['id'])) {
        foreach ($_SESSION['lista'] as $item){
            $idProducto = $item['id'];
            $cantidadProducto = $item['cantidad'];
            $select_cantidad = $conn->prepare("SELECT stock FROM producto WHERE id=?");
            $select_cantidad->execute([$idProducto]);
            $cantidad = $select_cantidad->fetchColumn();
            $cantidad = (int) $cantidad;
            if ((int)$cantidadProducto>$cantidad){
                $cuenta =$cuenta + 1;
            }
        }

        if($cuenta > 0){
            $message[] = 'La cantidad excede a al stock, cambiela';
            header("location: agregar_venta.php");
        }elseif($cuenta == 0){
            $clienteId = $_GET['id'];
        $insertar_venta = $conn->prepare("INSERT INTO `ventas`(idCliente,fecha,cantProd,importeTotal) VALUES(?,?,?,?)");
        date_default_timezone_set('America/Lima');
        $fecha = date('Y-m-d H:i:s');
            if ($insertar_venta->execute([$clienteId, $fecha, $cantotal, $total])) {
                $contador = 0;
                $idventa = $conn->lastInsertId();
                foreach ($_SESSION['lista'] as $item) {
                    $idProducto = $item['id'];
                    $precioProducto = $item['precio'];
                    $cantidadProducto = $item['cantidad'];
                    $subtotalProducto = $item['subtotal'];
                    $insertDetalleVenta = $conn->prepare("INSERT INTO detalleventa (idVenta, idProducto, cantProd, precioProd, importeTotal) VALUES (?, ?, ?, ?, ?)");
                    if($insertDetalleVenta->execute([$idventa, $idProducto, $cantidadProducto,$precioProducto, $subtotalProducto])){
                        $actualizar_stock = $conn->prepare("UPDATE producto SET stock = stock - ? WHERE id = ?");
                        $actualizar_stock->execute([$cantidadProducto, $idProducto]);
                        $movsalida = $conn->prepare("INSERT into movimientos (idProducto,cantidad, tipo) values (?,?,?)");
                        $movsalida->execute([$idProducto, $cantidadProducto, 'salida']);
                        $contador=$contador+1;
                    }      
                }if ($contador>0){
                    
                        session_start();
                        $_SESSION['lista'] = [];
                        $message[] = 'Venta registrada correctamente';
                        header("location: agregar_venta.php");
                }
            }else{
                $message[] = 'Error al procesar la venta';
            };
        }
        
        
        // Resto del código para ingresar el valor en la base de datos
    }else{
        $message[] ='Cliente no seleccionado';
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

  	<link rel="stylesheet" href="../css/custom.css">
   	<link rel="stylesheet" href="../css/bootstrap.min.css">
   
	<!--google fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

	<!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons"rel="stylesheet">
    <script>
        function actualizarSubtotal(input, precio, index) {
            var cantidad = input.value;
            var subtotal = parseFloat(cantidad * precio).toFixed(2);
            document.getElementById('subtotal-' + index).textContent = '$' + subtotal;
        }
    </script>

</head>
<body>

<?php include '../components/sliderbar.php'; ?>
<div class="container mt-3">
    <form method="post" class="row">
        <div class="col-7">
            <input class="form-control form-control-lg" name="codigo" autofocus id="codigo" type="text" placeholder="Id del producto" aria-label="codigoBarras">
        </div>
        <div class="col-2">
            <input type="submit" value="Agregar" name="agregar" class="btn btn-success mt">
        </div>
    </form>
    <br>
    <?php if($_SESSION['lista']) {?>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            
            <?php foreach($_SESSION['lista'] as $lista) {?>
                <tr>
                    <td><?php echo $lista['id'];?></td>
                    <td><?php echo $lista['nombre'];?></td>
                    <td>s./<?php echo $lista['precio'];?></td>
                    <form action="post"></form>

                    <form id="editForm-<?php echo $lista['id']; ?>" method="post">
                    <td>
                        <input type="number" name="cantidad" value="<?php echo $lista['cantidad'];?>">
                    </td>
                    <td id="subtotal">s/.<?php echo floatval($lista['cantidad'] * $lista['precio']);?></td>
                    <td>
                        <input type="hidden" name="edit_id" value="<?php echo $lista['id']; ?>">
                        <a href="#" class="edit" data-toggle="modal" onclick="submitForm('editForm-<?php echo $lista['id']; ?>')">
                            <i class="material-icons" data-toggle="tooltip" title="Editar">&#xE254;</i>
                        </a>
                    </td>
                    </form>

                    <form id="deleteForm-<?php echo $lista['id']; ?>" method="post">
                        <input type="hidden" name="delete_id" value="<?php echo $lista['id']; ?>">
                        <td>
                        <a href="#" class="delete" data-toggle="modal" onclick="submitForm('deleteForm-<?php echo $lista['id']; ?>')">
                            <i class="material-icons" data-toggle="tooltip" title="Eliminar">&#xE872;</i>
                        </a>
                        </td>  
                    </form>

<script>
    function submitForm(formId) {
        document.getElementById(formId).submit();
    }
</script>
										
                    
                    
                    </td>
                </tr>
            <?php }?>
          
           
            </tbody>
        </table>

        <form class="row" method="post">
            <div class="col-10">
            <select class="form-select" aria-label="Default select example" name="idCliente">
                <option selected value="">Selecciona el cliente</option>
                <?php foreach($clientes as $cliente) {?>
                    <option value="<?php echo $cliente['id']?>"><?php echo $cliente['nombre'].' '.$cliente['apellido']?></option>
                <?php }?>
            </select>
            </div>
            <div class="col-auto">
                <input class="btn btn-info" name="select_cliente" type="submit" value="Seleccionar cliente">
                </input>
            </div>
        </form>

        <?php if ($clienteSeleccionado): ?>
            <div class="alert alert-primary mt-3" role="alert">
                <b>Cliente seleccionado:</b><br>
                <b>Nombre completo:</b> <?php echo $clienteSeleccionado['nombre'].' '.$clienteSeleccionado['apellido']?><br>
                <b>Teléfono:</b> <?php echo $clienteSeleccionado['DNI']?><br>
                <b>Dirección:</b> <?php echo $clienteSeleccionado['telefono']?><br>
                <a href="?quitar" class="btn btn-warning">Quitar</a>
            </div>
        <?php else: if(empty($clienteSeleccionado))?>
            <div class="alert alert-info mt-3" role="alert">
                No se ha seleccionado ningún cliente.
            </div>
        <?php endif; ?>

        
        <div class="text-center mt-3">
            <h1>Total: $<?php echo $total;?></h1>
            <a class="btn btn-primary btn-lg" href="?registrar&id=<?php echo $clienteSeleccionado['id']; ?>"><i class="fa fa-check"></i> 
            Terminar venta</a>
                
                
            <a class="btn btn-danger btn-lg" href="?cancelar">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </div>
    <?php }?>
</div>

</body>
</html>