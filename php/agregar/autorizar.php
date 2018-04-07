<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
$clienteid      = $_POST['clienteid'];
$activo         = 'S';
// $mensaje = "";
$mensaje = "";
$update = "UPDATE nuevoscli SET activo = '".$activo."' WHERE clienteid = $clienteid";

if ($mysqliCon->query($update) === TRUE) {
    $mensaje = "Se ha autorizado la alta de este cliente, ya no estará en lista del Asesor y/o Crédito y Cobranza.";
} else {
    $mensaje = "Error al enviar el mensaje, favor de contactar con el administrador del sitio.";
}
echo $mensaje;
$clienteid = 0;
$mysqliCon->close();
?>