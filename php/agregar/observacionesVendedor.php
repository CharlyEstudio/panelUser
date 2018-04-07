<?php
date_default_timezone_set('America/Mexico_City');
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

//Variable de búsqueda
$clienteid = $_POST['clienteid'];
$perid = utf8_decode($_POST['perid']);
$observacion = 'VEND. - '.utf8_decode($_POST['observacion']);

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";
// $mensaje = $clienteid . $observacion . $perid;

//Comprueba si $observacion está seteado
if (isset($observacion)) {
    $update = "UPDATE nuevoscli SET observacion = '".$observacion."', status = 3 WHERE clienteid = $clienteid";
    
    if ($mysqliCon->query($update) === TRUE) {
        $feccharla = date("Y-m-d H:i:s");
        $historial = "INSERT INTO converCliNue(vendedorid, clienteid, charla,feccharla) VALUES ($perid, $clienteid, '$observacion', '$feccharla')";
        $mysqliCon->query($historial);
        $mensaje = "Mensaje enviado a Cartera, al actualizar se verán los cambios realizados.";
    } else {
        $mensaje = "Error al enviar el mensaje, favor de contactar con el administrador del sitio.";
    }
};

//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
$clienteid = 0;
$observacion = 0;
$mysqliCon->close();
?>