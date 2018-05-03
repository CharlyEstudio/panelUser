<?php
require('../classes/PHPMailer/class.phpmailer.php');
require('../classes/PHPMailer/class.smtp.php');
require('tcpdf/tcpdf.php');
require_once("../class.database.php");
require_once("../functions/util.php");
// require_once("../functions/dml.php");

$paramDb = new Database();
$getConnection = $paramDb->GetLink();
date_default_timezone_set('America/Mexico_City');

// $fecha = date('l jS \of F Y h:i:s A');
$d = date('D');
$m = date('m');

switch($d){
    case 'Mon':
      $nomDia = 'Lunes';
      break;
    case 'Tue':
      $nomDia = 'Martes';
      break;
    case 'Wed':
      $nomDia = 'Miércoles';
      break;
    case 'Thu':
      $nomDia = 'Jueves';
      break;
    case 'Fri':
      $nomDia = 'Viernes';
      break;
    case 'Sat':
      $nomDia = 'Sábado';
      break;
}

switch ($m) {
    case 1:
      $mes='Enero';
    break;
    case 2:
      $mes='Febrero';
    break;
    case 3:
      $mes='Marzo';
    break;
    case 4:
      $mes='Abril';
    break;
    case 5:
      $mes='Mayo';
    break;
    case 6:
      $mes='Junio';
    break;
    case 7:
      $mes='Julio';
    break;
    case 8:
      $mes='Agosto';
    break;
    case 9:
      $mes='Septiembre';
    break;
    case 10:
      $mes='Octubre';
    break;
    case 11:
      $mes='Noviembre';
    break;
    case 12:
      $mes='Diciembre';
    break;
  }

$numDia = date('d');
$fecha = $nomDia." ".$numDia." de ".$mes." del ".date('Y')." ".date('h:i:s A');

$chofer = $_POST["chofer"];
$abiertas = $_POST["abiertas"];
$azules = $_POST["azules"];
$narGrande = $_POST["narGrande"];
$narPeque = $_POST["narPeque"];
$verifico = $_POST["verifico"];

$paramsSend = $_POST["data"];
$params = json_encode($_POST["data"]);
$totalSum = $paramsSend["totalSum"];
$length = count($paramsSend);
$numPed = $length - 1;
// var_dump($length);
// var_dump($params);

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ferremayoristas Olvera');
$pdf->SetTitle('RUTA DEL DIA');

$pdf->setPrintHeader(false); 
$pdf->setPrintFooter(false);
$pdf->SetMargins(1, 0, 0, false);
$pdf->SetAutoPageBreak(true, 20); 
$pdf->SetFont('Helvetica', '', 8);
$pdf->SetFillColor(218, 218, 218);
$pdf->addPage();

// Encabezado texto centrado en una celda con cuadro 20*10 mm y salto de línea
$pdf->Image('http://www.ferremayoristas.com.mx/images/logo-min.png',0,0,30,0,'PNG');
$pdf->Cell(35,10,'',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(40,10,'RUTA DEL DIA',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(13,10,'CHOFER: ',0,0,'L');
$pdf->Cell(20,10,$chofer,0,0,'L');
$pdf->Cell(13,10,'Verifico: ',0,0,'R');
$pdf->Cell(20,10,$verifico,0,0,'L');
$pdf->Cell(5,10,$numPed,0,0,'R');
$pdf->Cell(28,10,' Pedido(s)',0,0,'L');
$pdf->Cell(60,10,$fecha,0,0,'C');
$pdf->Cell(60,10,'',0,1,'C');


$pdf->Cell(13,10,'CAJAS: ',0,0,'L');

if($abiertas > 0){
  $pdf->Cell(20,10,'ABIERTAS: '.$abiertas,0,0,'L');
}
if($azules >0){
  $pdf->Cell(20,10,'AZULES: '.$azules,0,0,'L');
}
if($narGrande > 0){
  $pdf->Cell(20,10,'NAR. GDE.: '.$narGrande,0,0,'L');
}
if($narPeque > 0){
  $pdf->Cell(20,10,'NAR. PEQ.: '.$narPeque,0,0,'L');
}
$pdf->SetFont('Helvetica', 'B', 8);

// Salto de línea
$pdf->Ln();

// Titulos texto centrado en una celda con cuadro 20*10 mm y salto de línea
$pdf->Cell(12,5,'Factura',1,0,'C');
$pdf->Cell(12,5,'Cliente',1,0,'C');
$pdf->Cell(70,5,'Nombre',1,0,'L');
$pdf->Cell(70,5,'Domicilio',1,0,'L');
$pdf->Cell(40,5,'Población',1,0,'L');
$pdf->Cell(35,5,'Vendedor',1,0,'L');
$pdf->Cell(15,5,'Importe',1,0,'R');
$pdf->Cell(40,5,'Observación',1,1,'C');

for ($i=0; $i < $length; $i++) {
    if(isset($paramsSend[$i])) {
        $folio 		= $paramsSend[$i]["folio"];
        $numero 	= $paramsSend[$i]["numero"];
        $nombre 	= $paramsSend[$i]["nombre"];
        $direccion	= $paramsSend[$i]["direccion"];
        $colonia 	= $paramsSend[$i]["colonia"];
        $ciudad 	= $paramsSend[$i]["ciudad"];
        $estado 	= $paramsSend[$i]["estado"];
        $vendedor 	= $paramsSend[$i]["vendedor"];
        $total 		= $paramsSend[$i]["total"];

        $formatoTotal 		= number_format($total, 2, ".", ",");

        $pdf->SetFont('Helvetica', '', 6);

        // Titulos texto centrado en una celda con cuadro 20*10 mm y salto de línea
        $pdf->Cell(12,5,$folio,1,0,'C');
        $pdf->Cell(12,5,$numero,1,0,'C');
        $pdf->Cell(70,5,$nombre,1,0,'L');
        $pdf->Cell(70,5,$direccion.', '.$colonia,1,0,'L');
        $pdf->Cell(40,5,$ciudad.', '.$estado,1,0,'L');
        $pdf->Cell(35,5,$vendedor,1,0,'L');
        $pdf->Cell(15,5,$formatoTotal,1,0,'R');
        $pdf->Cell(40,5,'',1,1,'C');
    }
}

$formatoTotalSum 	= number_format($totalSum, 2, ".", ",");
$pdf->Ln();

// Totales texto centrado en una celda con cuadro 20*10 mm y salto de línea
$pdf->Cell(25,5,'FIRMA DEL CHOFER',0,0,'L');
$pdf->Cell(30,5,'',1,0,'C');
$pdf->Cell(80,5,'',0,0,'C');
$pdf->Cell(30,5,'IMPORTE DE COBRANZA',0,0,'C');
$pdf->Cell(69,5,'',0,0,'C');
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(20,5,'Total C.O.D.',0,1,'L');
$pdf->SetFont('Helvetica', '', 6);

$pdf->Cell(25,5,'FIRMA DE C X C',0,0,'L');
$pdf->Cell(30,5,'',1,0,'C');
$pdf->Cell(80,5,'',0,0,'C');
$pdf->Cell(30,5,'',1,0,'C');
$pdf->Cell(69,5,'',0,0,'C');
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(20,5,'Total Crédito',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(40,5,$formatoTotalSum,1,1,'R');
$pdf->SetFont('Helvetica', '', 6);

$pdf->Cell(25,5,'',0,0,'C');
$pdf->Cell(30,5,'',0,0,'C');
$pdf->Cell(80,5,'',0,0,'C');
$pdf->Cell(30,5,'',0,0,'C');
$pdf->Cell(69,5,'',0,0,'C');
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(20,5,'Total de Guía: $',0,0,'L');
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(40,5,$formatoTotalSum,1,1,'R');
$pdf->SetFont('Helvetica', '', 6);

// Códigos Especiales
if(isset($_POST["codigos"])){
    $paramsCodigo = $_POST["codigos"];
    $lengthCodigo = count($paramsCodigo);
    $pdf->Cell(24,5,'Se encontraron los siguientes Tubos y/o Tinacos:',0,1,'L');
    foreach($paramsCodigo as $clave => $cantidad){
        $buscarClave = "SELECT descripcio FROM inv WHERE clvprov = $clave";
        $claveEnc = mysqli_query($getConnection,$buscarClave);
        $rowClave = mysqli_fetch_row($claveEnc);
        $titulo = $rowClave[0];

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80,5,$clave.' - '.$titulo,1,0,'L');
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(8,5,$cantidad,1,1,'C');
    }
}else{
    $pdf->Cell(24,5,'Sin códigos de TUBOS Y TINACOS',0,0,'L');
}

$pdf->lastPage();
// $pdf->output('pdf/'.$chofer."-".date('Y-m-d').'-'.date('H:i:s').".pdf", "F", true);
$pdf->output("pdf/rutadia.pdf", "F", true);

// $mensaje = '<div class="text-center">
//                 <p>PDF Creado con éxito!</p>
//                 <a href="../php/classes/pdf/'.$chofer.'-'.date('Y-m-d').'-'.date('H:i:s').'.pdf" style="font-size: 2em;" target="_blanck"><i class="far fa-file-pdf"></i></a>
//                 <p>Descargar</p>
//             </div>';
$mensaje = '<div class="text-center">
                <p>PDF Creado con éxito!</p>
                <a href="../php/classes/pdf/rutadia.pdf" style="font-size: 2em;" target="_blanck"><i class="far fa-file-pdf"></i></a>
                <p>Descargar</p>
            </div>';
echo $mensaje;
?>