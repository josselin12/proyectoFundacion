<?php
session_start();

include('config/bd.php');
require('fpdf.php');
include('config/bd.php');
class PDF extends FPDF
{
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('img/imagen01.png',10,8,33);
    // Arial bold 15
    $this->SetFont('Arial','B',20);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(40,10,'Ficha de pago',0,0,'C');
    // Salto de línea
    $this->Ln(30);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
}
}


$sqlSentencia = $con ->prepare("SELECT CONCAT(nombreAlumno, ' ', apellidoPAlumno, ' ',apellidoMAlumno)  as nombre,
                                CONCAT(nombrePeriodo, ' ', fechaInicia, ' - ' ,fechaTermina) as periodo, isBecario, monto, 
                                cuenta, matricula, fechaPago
                                FROM pagos pa
                                INNER JOIN periodos p ON pa.id_Periodo = p.id_Periodo
                                INNER JOIN alumnos a ON pa.id_Alumno = a.id_Alumno
                                WHERE pa.id_Alumno= :id 
                                ORDER BY pa.id_Pago DESC LIMIT 1;");
    $sqlSentencia -> bindParam(':id',$_SESSION['alumno']);
    $sqlSentencia -> execute();


    $pago = $sqlSentencia -> fetch(PDO::FETCH_LAZY);


        

        $pdf = new PDF();
        $pdf -> AliasNbPages();
        $pdf->AddPage();
        //INICA SECCION DATOS
        $pdf->SetFont('Arial','B',12);
        $pdf->SetDrawColor(231,231,231);
        $pdf->SetFillColor(231,231,231);
        $pdf->Cell(190,10,'Datos personales',1,1,'C', 1);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln(4);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(40,10,'Nombre:',0,0,'C', 0);
        $pdf->Cell(150,10,utf8_decode($pago["nombre"]),0,1,'C', 0);
        $pdf->Ln(4);
        
        //TERMINA SECCION DATOS
        //INICIA PAGO
        $pdf->SetFont('Arial','B',12);
        $pdf->SetDrawColor(231,231,231);
        $pdf->SetFillColor(231,231,231);
        $pdf->Cell(190,10,'Pago',1,1,'C', 1);
        $pdf->Ln(4);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(80,10,'Periodo',0,0,'C', 0);
        $pdf->Cell(40,10,'Becado',0,0,'C', 0);
        $pdf->Cell(70,10,'Importe a pagar',0,1,'C', 0);
        $pdf->Cell(80,10,utf8_decode($pago["periodo"]),0,0,'C', 0);
        $pdf->Cell(40,10,utf8_decode(($pago["isBecario"] == 1)? "Sí":"No"),0,0,'C', 0);
        $pdf->Cell(70,10,utf8_decode($pago["monto"]),0,1,'C', 0);
        
        $pdf->Ln(4);
        // TERMINA PAGO
        //INICIA BANCO
        $pdf->SetFont('Arial','B',12);
        $pdf->SetDrawColor(231,231,231);
        $pdf->SetFillColor(231,231,231);
        $pdf->Cell(190,10,'Datos Bancarios',1,1,'C', 1);
        $pdf->Ln(4);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(45);
        $pdf->Cell(40,10,'Banco:',0,0,'L', 0);
        $pdf->Cell(110,10,'BBVA BANCOMER',0,1,'L', 0);
        $pdf->Cell(45);
        $pdf->Cell(40,10,'Titular:',0,0,'L', 0);
        $pdf->Cell(110,10,'Casa Hogar San Luis Gonzaga S.A de C.V',0,1,'L', 0);
        $pdf->Cell(45);
        $pdf->Cell(40,10,utf8_decode('Importe a pagar'),0,0,'L', 0);
        $pdf->Cell(110,10,utf8_decode($pago["monto"]),0,1,'L', 0);
        $pdf->Cell(45);
        $pdf->Cell(40,10,utf8_decode('cuenta'),0,0,'L', 0);
        $pdf->Cell(110,10,utf8_decode($pago["cuenta"]),0,1,'L', 0);
        
        $pdf->Ln(4);
        $pdf->Cell(45);
        $pdf->Cell(140,10,utf8_decode('Fecha en que se generó orden de pago: ').$pago["fechaPago"],0,1,'C', 0);
        $pdf->Ln(4);
        //TERMINA BANCO

        $pdf->Ln(10);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(190,4,utf8_decode('EL CAJERO TE ENTREGARÁ TU COMPROBANTE DE PAGO DE ACUERO CON LA REFERENCIA, TE '),0,1,'C', 0);
        $pdf->Cell(190,4,utf8_decode('RECOMENDAMOS VERIFICAR QUE LOS DATOS SEAN CORRECTOS Y GUARDARLO PARA CUALQUIER DUDA O '),0,1,'C', 0);
        $pdf->Cell(190,4,utf8_decode('ACLARACIÓN'),0,1,'C', 0);
        $pdf->Cell(190,10,utf8_decode('Dudas o Aclaraciones: pagosFundacion@gmail.com'),0,1,'C', 0);
        $pdf->Cell(190,10,utf8_decode('La información contenida es manejada de acuerdo al Aviso de Privacidad'),0,1,'C', 0);
        $pdf->Ln(4);
        
        $pdf->Output();


?>