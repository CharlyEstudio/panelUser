<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

$codigo = $_POST["data"][0];
$ide = $_POST["data"][1];
$tx = $_POST["data"][2];
$qro = $_POST["data"][3];
$tipo = $_POST["data"][4];

$modificar      = "UPDATE controlinv
                        SET codigo = '$codigo', iden = '$ide', tequis = '$tx',
                            qro = '$qro', tipo = $tipo
                        WHERE codigo = $codigo";

if($mysqliCon->query($modificar) === TRUE){
    $confirmacion = "El producto se ha modificado correctamente";
} else {
    $confirmacion = "No se pudo modificar por el siguiente error: " . $mysqliCon->error."";
}

echo $confirmacion;
$mysqliCon->close();
?>