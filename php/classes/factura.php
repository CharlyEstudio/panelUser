<?php
require('fpdf/fpdf.php');
require_once("../class.database.php");

$paramDb = new Database();
$getConnection = $paramDb->GetLink();

$id= $_GET['f'];
$clienteid = $_GET['n'];
$referencia = $_GET['r'];

/*$host = "192.168.1.250";
$username = "web";
$password = "webfmolvera17";
$database = "datosa";

$mysqli = new mysqli($host, $username, $password, $database);*/

/*$getDatFac= "SELECT *  FROM cfd where docid = $id";
$getFact= mysqli_query($getConnection, $getDatFac);
$fact = mysqli_fetch_row($getFact);
$getUserNum = "SELECT nombre, direccion, numero, interior, colonia, ciudad, municipio, estado, cp, rfc FROM dom WHERE clienteid = $clienteid";
$userNum = mysqli_query($getConnection,$getUserNum);
$n = mysqli_fetch_row($userNum);

$getVendedor = "SELECT p.nombre FROM des d JOIN per p ON p.perid = d.desemisorid WHERE d.desdocid = $id";
$vendNum = mysqli_query($getConnection,$getVendedor);
$v = mysqli_fetch_row($vendNum);*/

$getFactura = "SELECT cfd.*, d.nombre, d.direccion, d.numero, d.interior, d.colonia, d.ciudad, d.municipio, d.estado, d.cp, d.rfc, p.nombre
					FROM cfd
						JOIN des de ON de.desdocid = cfd.docid
						JOIN dom d ON d.clienteid = $clienteid
						JOIN per p ON p.perid = de.desemisorid
					WHERE cfd.docid = $id
						AND d.clienteid = $clienteid";
$getFact= mysqli_query($getConnection, $getFactura);
$fact = mysqli_fetch_row($getFact);

$cfdid 		= $fact[0];
$docid 		= $id;
$tipdoc 	= $fact[2];
$rfc 		= $fact[3];
$tipcfd 	= $fact[4];
$serie 		= $fact[5];
$folio 		= $fact[6];
$nceremi 	= $fact[7];
$aprobacion = $fact[8];
$fecha 		= $fact[9];
$hora 		= $fact[10];
$total 		= $fact[11];
$impuesto 	= $fact[12];
$estado 	= $fact[13];
$efecto 	= $fact[14];
$pedimento 	= $fact[15];
$fpedimento = $fact[16];
$aduana 	= $fact[17];
$xmltam 	= $fact[18];
$xml 		= $fact[19];
$cbb 		= $fact[20];
$cadena 	= $fact[21];
$digesto 	= $fact[22];
$digesto2 	= $fact[23];
$uuid 		= $fact[24];
$id2 		= $fact[25];
$fechatimb 	= $fact[26];
$cambiado 	= $fact[27];
$certisat 	= $fact[28];

$nombre 	= $fact[29];
$direccion 	= $fact[30];
$numero		= $fact[31];
$interior	= $fact[32];
$colonia	= $fact[33];
$ciudad 	= $fact[34];
$municipio 	= $fact[35];
$estado		= $fact[36];
$codigo 	= $fact[37];
$rfc 		= $fact[38];

$vendedor 	= $fact[39];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('../../../assets/img/logo-min.png' , 8, 25, 50, 20,'PNG', 'http://www.ferremayoristas.com.mx/');

// Encabezado de la factura
$pdf->SetFont('Arial','B',10);
$top_datos=30;
$pdf->SetXY(80, $top_datos);
$pdf->Cell(40,20, $folio, 0, 2, "J");
$pdf->Ln(0);

// Datos de la tienda
$pdf->SetFont('Arial','B',7);
$top_datosA=70;
$pdf->SetXY(5, $top_datosA);
$pdf->Cell(190, 10, "", 0, 2, "J");
$pdf->SetFont('Arial','B',6.5);
$pdf->MultiCell(125, //posición X
7.5, //posición Y
"CLIENTE: ".$clienteid."\n".
$nombre."\n".
$direccion." No. ".$numero." ".$interior."\n".
$colonia." C.P. ".$codigo."\n".
$ciudad." ".$estado."\n".
"R.F.C. ".$rfc." VENDEDOR: ".$vendedor, 
1, // bordes 0 = no | 1 = si
"J", // texto justificado
 false);

// Datos del pago
$pdf->SetFont('Arial','B',7);
$top_datosA=70;
$pdf->SetXY(130, $top_datosA);
$pdf->Cell(190, 10, "", 0, 2, "J");
$pdf->SetFont('Arial','B',6.5);
$pdf->MultiCell(74, //posición X
7.5, //posición Y
"PAGO EN UNA SOLA EXHIBICION \n".
"METODO DE PAGO: /ESTE DOCUMENTO ES\n".
"UNA REPRESENTACION IMPRESA DE UN CFDI\n".
"REGIMEN FISCAL EMISOR: REGIMEN GENERAL DE\n".
"LEY DE LAS PERSONAS MORALES\n".
"EFECTOS FISCALES AL PAGO", 
1, // bordes 0 = no | 1 = si
"J", // texto justificado
 false);

// Datos del cliente
$top_datosB=0;
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(135, $top_datosB);
$pdf->Cell(190, 10, "", 0, 2, "J");
$pdf->SetFont('Arial','B',6.5);
$pdf->MultiCell(
190, //posición X
5, //posicion Y
"FERREMAYORISTAS OLVERA S.A. DE C.V.\n".
"PROLONGACION CONSTITUYENTES No. 1095 2 Col.\n".
"EL POCITO\n".
"C.P.76190 QUERETARO,QUERE\n".
"RFC FOL160621K23\n".
"FECHA Y HORA: ".$fecha." ".$hora."\n".
"LUGAR DE EXPEDICION: QUERETARO,QRO.\n".
"FOLIO DEL SAT:\n".
strtoupper($uuid)."\n".
"FECHA DE CERTIFICACION: ".$fechatimb."\n".
"CERTIFICADO EMISOR: \n".
"CERTIFICADO SAT: \n".
"FECHA DE RE-IMPRESION: ".date("Y-m-d"),
 0, // bordes 0 = no | 1 = si
 "J", // texto justificado 
false);

//Salto de línea
$pdf->Ln(0);

//Creación de la tabla de los detalles de los productos productos
$top_productos = 127;
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(5, $top_productos);
$pdf->Cell(20, 10, 'Producto', 1, 1, 'C');
$pdf->SetXY(25, $top_productos);
$pdf->Cell(20, 10, 'Codigo', 1, 1, 'C');
$pdf->SetXY(45, $top_productos);
$pdf->Cell(59, 10, 'Descripcion', 1, 1, 'C');
$pdf->SetXY(104, $top_productos);
$pdf->Cell(20, 10, 'Cantidad', 1, 1, 'C');
$pdf->SetXY(124, $top_productos);
$pdf->Cell(20, 10, 'Unidad', 1, 1, 'C');
$pdf->SetXY(144, $top_productos);
$pdf->Cell(20, 10, 'Precio U.', 1, 1, 'C');
$pdf->SetXY(164, $top_productos);
$pdf->Cell(20, 10, 'Desc.', 1, 1, 'C');
$pdf->SetXY(184, $top_productos);
$pdf->Cell(20, 10, 'Importe', 1, 1, 'C');

$buscar=("SELECT desartid FROM des WHERE desdocid = $id");
$query_execute = $getConnection->query($buscar);

$precio_subtotal = 0; // variable para almacenar el subtotal
$y = 137; // variable para la posición top desde la cual se empezarán a agregar los datos
$x=0;

//se despliega el resultado
while($row = $query_execute->fetch_array()){
	$articuloid = $row['desartid'];

	$getArtPed = "SELECT d.desartid, i.clave, i.clvprov, i.descripcio, d.descantidad, d.desventa, d.desdescuento
						FROM des d
							JOIN inv i ON i.articuloid = d.desartid
						WHERE d.desdocid = $id
							AND d.desartid = $articuloid";

	$artPed = mysqli_query($getConnection,$getArtPed);
	$articulos = mysqli_fetch_row($artPed);
	
	$clave = $articulos[1];
	$codigo = $articulos[2];
	$descripcion = $articulos[3];
	$cantidad = number_format($articulos[4],2,'.',',');
	$precio = $articulos[5];
	$desc = $articulos[6];
	$unidad = "PZ";
	$importe = $precio * $cantidad;

	$pdf->SetFont('Arial','',6.5);
	$pdf->SetXY(5, $y);
	$pdf->Cell(20, 7, $clave, 1, 1, 'C');
	$pdf->SetXY(25, $y);
	$pdf->Cell(20, 7, $codigo, 1, 1, 'C');
	$pdf->SetFont('Arial','',5.5);
	$pdf->SetXY(45, $y);
	$pdf->Cell(59, 7, $descripcion, 1, 1, 'L');
	$pdf->SetFont('Arial','',6.5);
	$pdf->SetXY(104, $y);
	$pdf->Cell(20, 7, $cantidad, 1, 1, 'C');
	$pdf->SetXY(124, $y);
	$pdf->Cell(20, 7, $unidad, 1, 1, 'C');
	$pdf->SetXY(144, $y);
	$pdf->Cell(20, 7, $precio, 1, 1, 'C');
	$pdf->SetXY(164, $y);
	$pdf->Cell(20, 7, $desc, 1, 1, 'C');
	$pdf->SetXY(184, $y);
	$pdf->Cell(20, 7,"$ ".$importe, 1, 1, 'C');

	//Cálculo del subtotal  
	$precio_subtotal += $precio * $cantidad;
	$x++;

	// aumento del top 5 cm
	$y = $y + 7;
}

/*$top_productos = 200;
$pdf->SetFont('Arial','B',10);
$pdf->SetXY(5, $top_productos);
$pdf->Cell(20, 10, $x, 1, 1, 'C');*/

//Cálculo del Impuesto
$add_iva = $precio_subtotal * (16 / 100);

//Cálculo del precio total
//$total_mas_iva = round($precio_subtotal + $add_iva + $gastos_de_envio, 2); //Con envio
$total_mas_iva = $precio_subtotal + $add_iva; //Sin envio

$pdf->Ln(0);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(164, $y);
$pdf->MultiCell(20, //posición X
5, //posición Y
"SubTotal\n".
"IVA 16%\n".
"Toatl",
 1, // bordes 0 = no | 1 = si
 "R", // texto justificado 
 false);

$pdf->SetFont('Arial','B',8);
$pdf->SetXY(184, $y);
$pdf->MultiCell(
20, //posición X
5, //posicion Y
"$ ".number_format($precio_subtotal,2,'.',',')."\n".
"$ ".number_format($add_iva,2,'.',',')."\n".
"$ ".number_format($total_mas_iva,2,'.',','), 
1, // bordes 0 = no | 1 = si
"R", // texto justificado
false);

$miMoneda = "PESOS MEXICANOS";

$pdf->Ln(0);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5, $y);
$pdf->MultiCell(159, //posición X
7.5, //posición Y
"Importe con Letra\n".
valorEnLetras($total_mas_iva)."\n",
 1, // bordes 0 = no | 1 = si
 "L", // texto justificado 
 false);

$fichero= $referencia.'-'.$folio.'.pdf';
$pdfdoc = $pdf->Output($fichero, "D");
//Eliminación del archivo en el servidor
//unlink($archivo);

function valorEnLetras($x) 
{ 
if ($x<0) { $signo = "menos ";} 
else      { $signo = "";} 
$x = abs ($x); 
$C1 = $x; 

$G6 = floor($x/(1000000));  // 7 y mas 

$E7 = floor($x/(100000)); 
$G7 = $E7-$G6*10;   // 6 

$E8 = floor($x/1000); 
$G8 = $E8-$E7*100;   // 5 y 4 

$E9 = floor($x/100); 
$G9 = $E9-$E8*10;  //  3 

$E10 = floor($x); 
$G10 = $E10-$E9*100;  // 2 y 1 


$G11 = round(($x-$E10)*100,0);  // Decimales 
////////////////////// 

$H6 = unidades($G6); 

if($G7==1 AND $G8==0) { $H7 = "Cien "; } 
else {    $H7 = decenas($G7); } 

$H8 = unidades($G8); 

if($G9==1 AND $G10==0) { $H9 = "Cien "; } 
else {    $H9 = decenas($G9); } 

$H10 = unidades($G10); 

if($G11 < 10) { $H11 = "0".$G11; } 
else { $H11 = $G11; } 

///////////////////////////// 
    if($G6==0) { $I6=" "; } 
elseif($G6==1) { $I6="Millón "; } 
         else { $I6="Millones "; } 
          
if ($G8==0 AND $G7==0) { $I8=" "; } 
         else { $I8="Mil "; } 
          
$I10 = "Pesos "; 
$I11 = "/100 M.N. "; 

$C3 = $signo.$H6.$I6.$H7.$H8.$I8.$H9.$H10.$I10.$H11.$I11; 

return $C3; //Retornar el resultado 

} 

function unidades($u) 
{ 
    if ($u==0)  {$ru = " ";} 
elseif ($u==1)  {$ru = "Un ";} 
elseif ($u==2)  {$ru = "Dos ";} 
elseif ($u==3)  {$ru = "Tres ";} 
elseif ($u==4)  {$ru = "Cuatro ";} 
elseif ($u==5)  {$ru = "Cinco ";} 
elseif ($u==6)  {$ru = "Seis ";} 
elseif ($u==7)  {$ru = "Siete ";} 
elseif ($u==8)  {$ru = "Ocho ";} 
elseif ($u==9)  {$ru = "Nueve ";} 
elseif ($u==10) {$ru = "Diez ";} 

elseif ($u==11) {$ru = "Once ";} 
elseif ($u==12) {$ru = "Doce ";} 
elseif ($u==13) {$ru = "Trece ";} 
elseif ($u==14) {$ru = "Catorce ";} 
elseif ($u==15) {$ru = "Quince ";} 
elseif ($u==16) {$ru = "Dieciseis ";} 
elseif ($u==17) {$ru = "Decisiete ";} 
elseif ($u==18) {$ru = "Dieciocho ";} 
elseif ($u==19) {$ru = "Diecinueve ";} 
elseif ($u==20) {$ru = "Veinte ";} 

elseif ($u==21) {$ru = "Veintiun ";} 
elseif ($u==22) {$ru = "Veintidos ";} 
elseif ($u==23) {$ru = "Veintitres ";} 
elseif ($u==24) {$ru = "Veinticuatro ";} 
elseif ($u==25) {$ru = "Veinticinco ";} 
elseif ($u==26) {$ru = "Veintiseis ";} 
elseif ($u==27) {$ru = "Veintisiente ";} 
elseif ($u==28) {$ru = "Veintiocho ";} 
elseif ($u==29) {$ru = "Veintinueve ";} 
elseif ($u==30) {$ru = "Treinta ";} 

elseif ($u==31) {$ru = "Treintayun ";} 
elseif ($u==32) {$ru = "Treintaydos ";} 
elseif ($u==33) {$ru = "Treintaytres ";} 
elseif ($u==34) {$ru = "Treintaycuatro ";} 
elseif ($u==35) {$ru = "Treintaycinco ";} 
elseif ($u==36) {$ru = "Treintayseis ";} 
elseif ($u==37) {$ru = "Treintaysiete ";} 
elseif ($u==38) {$ru = "Treintayocho ";} 
elseif ($u==39) {$ru = "Treintaynueve ";} 
elseif ($u==40) {$ru = "Cuarenta ";} 

elseif ($u==41) {$ru = "Cuarentayun ";} 
elseif ($u==42) {$ru = "Cuarentaydos ";} 
elseif ($u==43) {$ru = "Cuarentaytres ";} 
elseif ($u==44) {$ru = "Cuarentaycuatro ";} 
elseif ($u==45) {$ru = "Cuarentaycinco ";} 
elseif ($u==46) {$ru = "Cuarentayseis ";} 
elseif ($u==47) {$ru = "Cuarentaysiete ";} 
elseif ($u==48) {$ru = "Cuarentayocho ";} 
elseif ($u==49) {$ru = "Cuarentaynueve ";} 
elseif ($u==50) {$ru = "Cincuenta ";} 

elseif ($u==51) {$ru = "Cincuentayun ";} 
elseif ($u==52) {$ru = "Cincuentaydos ";} 
elseif ($u==53) {$ru = "Cincuentaytres ";} 
elseif ($u==54) {$ru = "Cincuentaycuatro ";} 
elseif ($u==55) {$ru = "Cincuentaycinco ";} 
elseif ($u==56) {$ru = "Cincuentayseis ";} 
elseif ($u==57) {$ru = "Cincuentaysiete ";} 
elseif ($u==58) {$ru = "Cincuentayocho ";} 
elseif ($u==59) {$ru = "Cincuentaynueve ";} 
elseif ($u==60) {$ru = "Sesenta ";} 

elseif ($u==61) {$ru = "Sesentayun ";} 
elseif ($u==62) {$ru = "Sesentaydos ";} 
elseif ($u==63) {$ru = "Sesentaytres ";} 
elseif ($u==64) {$ru = "Sesentaycuatro ";} 
elseif ($u==65) {$ru = "Sesentaycinco ";} 
elseif ($u==66) {$ru = "Sesentayseis ";} 
elseif ($u==67) {$ru = "Sesentaysiete ";} 
elseif ($u==68) {$ru = "Sesentayocho ";} 
elseif ($u==69) {$ru = "Sesentaynueve ";} 
elseif ($u==70) {$ru = "Setenta ";} 

elseif ($u==71) {$ru = "Setentayun ";} 
elseif ($u==72) {$ru = "Setentaydos ";} 
elseif ($u==73) {$ru = "Setentaytres ";} 
elseif ($u==74) {$ru = "Setentaycuatro ";} 
elseif ($u==75) {$ru = "Setentaycinco ";} 
elseif ($u==76) {$ru = "Setentayseis ";} 
elseif ($u==77) {$ru = "Setentaysiete ";} 
elseif ($u==78) {$ru = "Setentayocho ";} 
elseif ($u==79) {$ru = "Setentaynueve ";} 
elseif ($u==80) {$ru = "Ochenta ";} 

elseif ($u==81) {$ru = "Ochentayun ";} 
elseif ($u==82) {$ru = "Ochentaydos ";} 
elseif ($u==83) {$ru = "Ochentaytres ";} 
elseif ($u==84) {$ru = "Ochentaycuatro ";} 
elseif ($u==85) {$ru = "Ochentaycinco ";} 
elseif ($u==86) {$ru = "Ochentayseis ";} 
elseif ($u==87) {$ru = "Ochentaysiete ";} 
elseif ($u==88) {$ru = "Ochentayocho ";} 
elseif ($u==89) {$ru = "Ochentaynueve ";} 
elseif ($u==90) {$ru = "Noventa ";} 

elseif ($u==91) {$ru = "Noventayun ";} 
elseif ($u==92) {$ru = "Noventaydos ";} 
elseif ($u==93) {$ru = "Noventaytres ";} 
elseif ($u==94) {$ru = "Noventaycuatro ";} 
elseif ($u==95) {$ru = "Noventaycinco ";} 
elseif ($u==96) {$ru = "Noventayseis ";} 
elseif ($u==97) {$ru = "Noventaysiete ";} 
elseif ($u==98) {$ru = "Noventayocho ";} 
else            {$ru = "Noventaynueve ";} 
return $ru; //Retornar el resultado 
} 

function decenas($d) 
{ 
    if ($d==0)  {$rd = "";} 
elseif ($d==1)  {$rd = "Ciento ";} 
elseif ($d==2)  {$rd = "Doscientos ";} 
elseif ($d==3)  {$rd = "Trescientos ";} 
elseif ($d==4)  {$rd = "Cuatrocientos ";} 
elseif ($d==5)  {$rd = "Quinientos ";} 
elseif ($d==6)  {$rd = "Seiscientos ";} 
elseif ($d==7)  {$rd = "Setecientos ";} 
elseif ($d==8)  {$rd = "Ochocientos ";} 
else            {$rd = "Novecientos ";} 
return $rd; //Retornar el resultado 
} 

$getConnection->close();
?>