<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

$dir_subida     = 'docs/';

// var_dump($_FILES, $_POST);

// Doc's obligatorios para Contado
$fichero_Solicitud = $dir_subida . basename($_FILES['imgSolicitud']['name']);
move_uploaded_file($_FILES['imgSolicitud']['tmp_name'], $fichero_Solicitud);

$vendedorid     = $_POST["inputVendedor"];
$nombre         = utf8_decode($_POST["inputRazonSocial"]);
$comercial      = utf8_decode($_POST["inputNombreComercial"]);

if($_POST["inputAltaHaciendaSi"] === ""){
    $rfc        = utf8_decode($_POST["inputFRCNo"]);
    $fecalta    = date("Y-01-01");
} else {
    $rfc        = utf8_decode($_POST["inputFRCSi"]);
    $fecalta    = $_POST["inputAltaHaciendaSi"];
}

if($_POST["inputEmailSi"] === ""){
    $email      = utf8_decode($_POST["inputEmailNo"]);
} else {
    $email      = utf8_decode($_POST["inputEmailSi"]);
}

$direccion      = utf8_decode($_POST["inputDireccion"]);
$cp             = $_POST["inputCP"];
$colonia        = utf8_decode($_POST["inputColonia"]);
$ciudad         = utf8_decode($_POST["inputCiudad"]);
$tel            = $_POST["inputTelFijo"];
$cel            = $_POST["inputCel"];
$credito        = $_POST["inputCantidadCredito"];
$diascred       = $_POST["selectDiasCredito"];
$metpag         = utf8_decode($_POST["inputMetPago"]);
$hacerped       = utf8_decode($_POST["inputResponsableHacerPedidos"]);
$recibirped     = utf8_decode($_POST["inputResponsableRecibirPedidos"]);
$mlocal         = $_POST["inputM2"];
$tlocal         = utf8_decode($_POST["selectPropiedad"]);
$ladode         = utf8_decode($_POST["textareaALado"]);
$frentede       = utf8_decode($_POST["textareaFrente"]);

$activo         = 'N';

$insertar       = "INSERT INTO nuevoscli(NOMBRE, COMERCIAL, RFC, FECALTA, DIRECCION, CP, COLONIA, CIUDAD, TEL, CEL, EMAIL,
                    CREDITO, DIASCRED, METPAG, HACERPED, RECIBIRPED, MLOCAL, TLOCAL, LADODE, FRENTEDE, VENDEDORID, ACTIVO,
                    IMGSOLICITUD)
                        VALUES ('$nombre','$comercial','$rfc',$fecalta,'$direccion',$cp,'$colonia',
                        '$ciudad','$tel','$cel','$email',$credito,$diascred,'$metpag','$hacerped',
                        '$recibirped',$mlocal,'$tlocal','$ladode','$frentede',$vendedorid, '$activo','$fichero_Solicitud')";

if($mysqliCon->query($insertar) === TRUE){
    header("location: ../../intranet/index.php");
} else {
    echo "Error: " . $insertar . "<br>" . $mysqliCon->error;
    var_dump($_POST);
}

$_FILES = [];
$mysqliCon->close();
?>