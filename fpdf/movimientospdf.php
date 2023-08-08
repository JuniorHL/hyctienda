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
      $this->Image('logohyc.jpg', 175, 10, 28); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
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
      $this->Cell(100, 10, utf8_decode("REPORTE DE MOVIMIENTOS"), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(59,131,189); //colorFondo
      $this->SetTextColor(255, 255, 255); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(10, 10, utf8_decode('ID'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('FECHA'), 1, 0, 'C', 1);
      $this->Cell(70, 10, utf8_decode('PRODUCTO'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('CANTIDAD'), 1, 0, 'C', 1);
      $this->Cell(35, 10, utf8_decode('TIPO'), 1, 1, 'C', 1);
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
//require '../../funciones/CortarCadena.php';
/* CONSULTA INFORMACION DEL HOSPEDAJE */
//$consulta_info = $conexion->query(" select *from hotel ");
//$dato_info = $consulta_info->fetch_object();

$select_movimiento = $conn->prepare("SELECT m.id, m.fecha, p.nombre, m.cantidad, m.tipo FROM movimientos m INNER JOIN producto p ON m.idProducto = p.id");
$select_movimiento->execute();


$pdf = new PDF();
$pdf->AddPage(); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

while ($fetch_movimiento = $select_movimiento->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(10, 10, utf8_decode($fetch_movimiento['id']), 1, 0, 'C');
    $pdf->Cell(50, 10, utf8_decode($fetch_movimiento['fecha']), 1, 0, 'C');
    $pdf->Cell(70, 10, utf8_decode($fetch_movimiento['nombre']), 1, 0, 'C');
    $pdf->Cell(25, 10, utf8_decode($fetch_movimiento['cantidad']), 1, 0, 'C');
    $pdf->Cell(35, 10, utf8_decode($fetch_movimiento['tipo']), 1, 1, 'C');
}


$pdf->Output('Prueba.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)