<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

// var_dump($_POST);

$vendedorid     = $_POST["inputVendedor"];
$nombre         = utf8_decode($_POST["inputRazonSocial"]);
$comercial      = utf8_decode($_POST["inputNombreComercial"]);
$rfc            = utf8_decode($_POST["inputFRCSi"]);
$email          = utf8_decode($_POST["inputEmailSi"]);
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
$notas          = utf8_decode($_POST["textareaNota"]);

$modificar      = "UPDATE nuevoscli SET NOMBRE = '$nombre', COMERCIAL = '$comercial', RFC = '$rfc',
                        DIRECCION = '$direccion', CP = $cp, COLONIA = '$colonia', CIUDAD = '$ciudad',
                        TEL = '$tel', CEL = '$cel', EMAIL = '$email', CREDITO = $credito, DIASCRED = $diascred,
                        METPAG = '$metpag', HACERPED = '$hacerped', RECIBIRPED = '$recibirped', MLOCAL = $mlocal,
                        TLOCAL = '$tlocal', LADODE = '$ladode', FRENTEDE = '$frentede', VENDEDORID = $vendedorid";
if($mysqliCon->query($modificar) === TRUE){
    header("location: ../../intranet/index.php");
} else {
    echo "Error: " . $modificar . "<br>" . $mysqliCon->error;
    var_dump($_POST);
}
$mysqliCon->close();
?>