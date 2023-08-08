<?php
    include("../components/connect.php");

    $select_products = $conn->prepare("SELECT p.id, p.nombre, p.detalles, p.precio, p.stock, p.imagen1, p.imagen2, p.imagen3, c.nombre AS categoria FROM producto p INNER JOIN categoria c ON p.idcat = c.id");
    $select_products->execute();

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=productos.xls")
?>
<table>
    <caption>PRODUCTOS</caption>
    <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Detalles</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Categoria</th>
    </tr>
    <?php
		 while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 	
							?>
			<tr>
                <td><?= $fetch_products['id']; ?></td>
                <td><?= $fetch_products['nombre']; ?></td>
                <td><?= $fetch_products['detalles']; ?></td>
                <td><?= $fetch_products['precio']; ?></td>
                <td><?= $fetch_products['stock']; ?></td>
                <td><?= $fetch_products['categoria']; ?></td>
            </tr>
            <?php 
        }   ?>

</table>