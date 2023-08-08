<?php

# Incluyendo librerias necesarias #
include '../fpdf/code128.php';

$pdf = new PDF_Code128('P','mm',array(80,258));
$pdf->SetMargins(4,10,4);
$pdf->AddPage();

# Número inicial y cantidad de tickets a generar #
$numeroInicial = 1;
$cantidadTickets = 100;

for ($i = 0; $i < $cantidadTickets; $i++) {
    $pdf->AddPage();

    # Encabezado y datos de la empresa #
    $pdf->SetFont('Arial','B',10);
    $pdf->SetTextColor(0,0,0);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper("Nombre de empresa")),0,'C',false);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0,5,utf8_decode("Mercado Ciudad de Dios, SJM"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: 982535347"),0,'C',false);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    $pdf->MultiCell(0,5,utf8_decode("Fecha: ".date("d/m/Y", strtotime("13-09-2022"))." ".date("h:s A")),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Caja Nro: 1"),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Cajero: Carlos Alfaro"),0,'C',false);
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(0,5,utf8_decode(strtoupper("Ticket Nro: " . ($numeroInicial + $i))),0,'C',false);
    $pdf->SetFont('Arial','',9);

    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(5);

    // Resto del código para generar el contenido del ticket...

    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),"COD000001V0001",70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,utf8_decode("COD000001V0001"),0,'C',false);

    # Separador de tickets #
    $pdf->Ln(10);
    $pdf->Cell(0,0,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(10);
}

include '../components/connect.php';

// Consulta SQL para obtener los datos de los productos
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

while ($row = $select_venta->fetch(PDO::FETCH_ASSOC)) {
    $cliente = $row['nombre'] . ' ' . $row['apellido'];
    $telefono = $row['telefono'];
    $codigo_factura = $row['codigo'];
    $productos = $row['productos'];
    $cantidad_productos = $row['cantProd'];
    $total = $row['importeTotal'];
    
    $pdf->MultiCell(0,5,utf8_decode("Cliente: ".$cliente),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Teléfono: ".$telefono),0,'C',false);
    $pdf->MultiCell(0,5,utf8_decode("Factura: ".$codigo_factura),0,'C',false);
    
    $pdf->Ln(1);
    $pdf->Cell(0,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);
    
    # Tabla de productos #
    $pdf->Cell(10,5,utf8_decode("Cant."),0,0,'C');
    $pdf->Cell(45,5,utf8_decode("Producto"),0,0,'C');
    $pdf->Cell(19,5,utf8_decode("Precio"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("Total"),0,0,'C');
    
    $pdf->Ln(3);
    $pdf->Cell(102,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    $pdf->Ln(3);
    
    $pdf->MultiCell(0,4,utf8_decode($productos),0,'C',false);
    $pdf->Cell(10,4,utf8_decode($cantidad_productos),0,0,'C');
    $pdf->Cell(19,4,utf8_decode("$0.00 USD"),0,0,'C');
    $pdf->Cell(28,4,utf8_decode("$".$total." USD"),0,0,'C');
    $pdf->Ln(4);
    
    $pdf->Ln(7);
    
    $pdf->Cell(102,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    
    $pdf->Ln(5);
    
    # Impuestos & totales #
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(29,5,utf8_decode("SUBTOTAL"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("+ $".$total." USD"),0,0,'C');
    
    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(29,5,utf8_decode("IVA (13%)"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("+ $0.00 USD"),0,0,'C');
    
    $pdf->Ln(5);
    
    $pdf->Cell(102,5,utf8_decode("-------------------------------------------------------------------"),0,0,'C');
    
    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(29,5,utf8_decode("TOTAL A PAGAR"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("$".$total." USD"),0,0,'C');
    
    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(29,5,utf8_decode("TOTAL PAGADO"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("$100.00 USD"),0,0,'C');
    
    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(29,5,utf8_decode("CAMBIO"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("$30.00 USD"),0,0,'C');
    
    $pdf->Ln(5);
    
    $pdf->Cell(18,5,utf8_decode(""),0,0,'C');
    $pdf->Cell(29,5,utf8_decode("USTED AHORRA"),0,0,'C');
    $pdf->Cell(28,5,utf8_decode("$0.00 USD"),0,0,'C');
    
    $pdf->Ln(10);
    
    $pdf->MultiCell(0,5,utf8_decode("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar este ticket ***"),0,'C',false);
    
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,7,utf8_decode("Gracias por su compra"),'',0,'C');
    
    $pdf->Ln(9);
    
    # Codigo de barras #
    $pdf->Code128(5,$pdf->GetY(),"COD000001V0001",70,20);
    $pdf->SetXY(0,$pdf->GetY()+21);
    $pdf->SetFont('Arial','',14);
    $pdf->MultiCell(0,5,utf8_decode("COD000001V0001"),0,'C',false);
    
    # Separador de tickets #
    $pdf->Ln(10);
    $pdf->Cell(0,0,utf8_decode("------------------------------------------------------"),0,0,'C');
    $pdf->Ln(10);
}

# Nombre del archivo PDF #
$pdf->Output("I","Ticket_Nro_1.pdf",true);


?>