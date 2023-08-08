<?php

include '../fpdf/fpdf.php';

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      //include '../../recursos/Recurso_conexion_bd.php';//llamamos a la conexion BD

      //$consulta_info = $conexion->query(" select *from hotel ");//traemos datos de la empresa desde BD
      //$dato_info = $consulta_info->fetch_object();
      $this->Image('logohyc.jpg', 180, 9, 32); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(45); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      $this->Cell(110, 15, utf8_decode('H&C TIENDA'), 1, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Ln(3); // Salto de línea
      $this->SetTextColor(103); //color

      /* UBICACION */
      $this->Cell(110);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode(""), 0, 0, '', 0);
      $this->Ln(5);

      
      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(10,10,10);
      $this->Cell(50); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE PRODUCTOS"), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(59,131,189); //colorFondo
      $this->SetTextColor(255, 255, 255); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(10, 10, utf8_decode('ID'), 1, 0, 'C', 1);
      $this->Cell(70, 10, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
      $this->Cell(135, 10, utf8_decode('DETALLES'), 1, 0, 'C', 1);
      $this->Cell(20, 10, utf8_decode('PRECIO'), 1, 0, 'C', 1);
      $this->Cell(20, 10, utf8_decode('STOCK'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('CATEGORIA'), 1, 1, 'C', 1);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

include '../components/connect.php';

$pdf = new PDF();
$pdf->AddPage("landscape"); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$select_products = $conn->prepare("SELECT p.id, p.nombre, p.detalles, p.precio, p.stock, c.nombre AS categoria FROM producto p INNER JOIN categoria c ON p.idcat = c.id");
$select_products->execute();

while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(10, 10, utf8_decode($fetch_products['id']), 1, 0, 'C');
    $pdf->Cell(70, 10, utf8_decode($fetch_products['nombre']), 1, 0, 'C');
    $pdf->Cell(135, 10, utf8_decode($fetch_products['detalles']), 1, 0, 'C');
    $pdf->Cell(20, 10, utf8_decode($fetch_products['precio']), 1, 0, 'C');
    $pdf->Cell(20, 10, utf8_decode($fetch_products['stock']), 1, 0, 'C');
    $pdf->Cell(25, 10, utf8_decode($fetch_products['categoria']), 1, 1, 'C');
}

$pdf->Output('Reporte_Productos.pdf', 'I'); //nombreDescarga, Visor(I->visualizar - D->descargar)