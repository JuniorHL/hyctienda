<?php
    include("../components/connect.php");

    $select_movimiento = $conn->prepare("SELECT m.id, m.fecha, p.nombre, m.cantidad, m.tipo FROM movimientos m INNER JOIN producto p ON m.idProducto = p.id");
	$select_movimiento->execute();

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=movimientos.xls")
?>
<table>
    <caption>MOVIMIENTOS</caption>
    <tr>
        <th>Id</th>
		<th>Fecha</th>
		<th>Producto</th>
		<th>Cantidad</th>
		<th>Tipo</th>
    </tr>
    <?php
		 while($fetch_movimiento = $select_movimiento->fetch(PDO::FETCH_ASSOC)){ 	
		?>
			<tr>
                <td><?= $fetch_movimiento['id']; ?></td>
				<td><?= $fetch_movimiento['fecha']; ?></td>
				<td><?= $fetch_movimiento['nombre']; ?></td>
				<td><?= $fetch_movimiento['cantidad']; ?></td>	
				<td><?= $fetch_movimiento['tipo']; ?></td>
            </tr>
        <?php 
        } 
        ?>

</table>