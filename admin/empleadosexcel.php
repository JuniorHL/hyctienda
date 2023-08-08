<?php
    include("../components/connect.php");

    $select_admin = $conn->prepare("SELECT a.id, a.nombre, a.apellido, a.telefono, a.dni, a.usuario, a.contraseña, r.descripcion  FROM usuarioadmin a INNER JOIN rol r ON a.idrol = r.id");
	$select_admin->execute();

    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=empleados.xls")
?>
<table>
    <caption>EMPLEADOS</caption>
    <tr>
        <th>Id</th>
		<th>Nombre</th>
		<th>Apellidos</th>
		<th>DNI</th>
		<th>Telefono</th>
		<th>Rol</th>
    </tr>
    <?php
		 while($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)){ 	
		?>
			<tr>
                <td><?= $fetch_admin['id']; ?></td>
                <td><?= $fetch_admin['nombre']; ?></td>
				<td><?= $fetch_admin['apellido']; ?></td>
				<td><?= $fetch_admin['dni']; ?></td>
				<td><?= $fetch_admin['telefono']; ?></td>
				<td><?= $fetch_admin['descripcion']; ?></td>
            </tr>
        <?php 
        } 
        ?>

</table>