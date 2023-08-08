<?php
    include("../components/connect.php");

    $select_venta = $conn->prepare("SELECT v.id, v.idCliente, v.fecha, v.cantProd,v.importeTotal,
										(SELECT GROUP_CONCAT(CONCAT(pr.nombre, ' - cantidad: ', d.cantProd) SEPARATOR '\n')
									FROM detalleventa d
									JOIN producto pr 
									ON d.idProducto = pr.id
									WHERE d.idVenta = v.id) AS productos, c.nombre, c.apellido, c.telefono, f.codigo
									FROM ventas v
									JOIN cliente c 
									ON v.idCliente = c.id LEFT JOIN factura f ON f.idVenta = v.id ORDER BY v.fecha DESC");
	$select_venta->execute();

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=ventas.xls")
?>	
<table>
    <caption>Ventas</caption>
    <tr>
		<th>Id</th>
		<th>Cliente</th>
		<th>Factura</th>
        <th>Fecha Hora</th>
        <th>Cantidad Productos</th>
		<th>Total</th>
    </tr>
    <?php
		 while($fetch_venta = $select_venta->fetch(PDO::FETCH_ASSOC)){ 	
		?>
			<tr>
				<td><?= $fetch_venta['id']; ?></td>
				<td><?= $fetch_venta['nombre'].' '. $fetch_venta['apellido']; ?></td>
				<td><?= $fetch_venta['codigo']; ?></td>
				<td><?= $fetch_venta['fecha']; ?></td>
				<td><?= $fetch_venta['cantProd']; ?></td>
				<td><?= $fetch_venta['importeTotal']; ?></td>
            </tr>
        <?php 
        } 
        ?>

</table>