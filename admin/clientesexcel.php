<?php
    include("../components/connect.php");

    $select_cliente = $conn->prepare("SELECT * FROM cliente");
	$select_cliente->execute();

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=clientes.xls")
?>
<table>
    <caption>CLIENTES</caption>
    <tr>
        <th>Id</th>
		<th>Nombre</th>
		<th>Apellidos</th>
		<th>DNI</th>
		<th>Telefono</th>
    </tr>
    <?php
		 while($fetch_cliente = $select_cliente->fetch(PDO::FETCH_ASSOC)){ 	
		?>
			<tr>
                <td><?= $fetch_cliente['id']; ?></td>
				<td><?= $fetch_cliente['nombre']; ?></td>
				<td><?= $fetch_cliente['apellido']; ?></td>
				<td><?= $fetch_cliente['DNI']; ?></td>
				<td><?= $fetch_cliente['telefono']; ?></td>
            </tr>
        <?php 
        } 
        ?>

</table>