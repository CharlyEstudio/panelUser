<?php

$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

$codigo = $_POST["data"][0];

$buscarProd = "SELECT * FROM controlinv WHERE codigo = $codigo";
if(mysqli_query($mysqliCon,$buscarProd)){
    $iden       = utf8_decode($_POST["data"][1]);
    $tequis     = $_POST["data"][2];
    $qro        = $_POST["data"][3];
    $tipo       = $_POST["data"][4];

    $insertar       = "INSERT INTO controlinv(codigo, iden, tequis, qro, tipo)
                            VALUES ($codigo,'$iden',$tequis,$qro,$tipo)";

    if($mysqliCon->query($insertar) === TRUE){
        $message = "Se ha registrado el producto a inventario interno.";
    } else {
        $message = "Error al intentar guardar el producto al inventario. Error: ".$mysqliCon->error."";
    }
}else{
    $message = "Este código ya está registrado.";
}

echo $message;

$mysqliCon->close();
?>