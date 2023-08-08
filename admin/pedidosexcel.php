<?php
    include("../components/connect.php");

    $select_pedido = $conn->prepare("SELECT pe.id, pe.idCliente, pe.fecha, pe.metpago, pe.estado, pe.cantProd,pe.importeTotal,
										(SELECT GROUP_CONCAT(CONCAT(pr.nombre, ' - cantidad: ', d.cantProd) SEPARATOR '\n')
									FROM detallepedido d
									JOIN producto pr 
									ON d.idProducto = pr.id
									WHERE d.idPedido = pe.id) AS productos, c.nombre, c.apellido, c.telefono
									FROM pedido pe
									JOIN cliente c 
									ON pe.idCliente = c.id");
	$select_pedido->execute();

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=pedidos.xls")
?>
<table>
    <caption>PEDIDOS</caption>
    <tr>
        <th>Id</th>
		<th>Cliente</th>
		<th>Telefono</th>
		<th>Metodo de Pago</th>
		<th>Total</th>
		<th>Estado</th>
    </tr>
    <?php
		 while($fetch_pedido = $select_pedido->fetch(PDO::FETCH_ASSOC)){ 	
		?>
			<tr>
                <td><?= $fetch_pedido['id']; ?></td>
				<td><?= $fetch_pedido['nombre'].' '. $fetch_pedido['apellido']; ?></td>
				<td><?= $fetch_pedido['telefono']; ?></td>
			    <td><?= $fetch_pedido['metpago']; ?></td>
				<td><?= $fetch_pedido['importeTotal']; ?></td>
				<td><?= $fetch_pedido['estado']; ?></td>
            </tr>
        <?php 
        } 
        ?>

</table>