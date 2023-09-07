<?php
require('../fpdf/fpdf.php');

$pdf=new FPDF();
$pdf->AddPage('cotizacion_g_materiap_vista.php');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'¡Mi primera página pdf con FPDF!');
$pdf->Link(10,8,10,10,"http://www.acycia.com/intranet/cotizacion_g_materiap_vista.php");
$pdf->Output();
?>