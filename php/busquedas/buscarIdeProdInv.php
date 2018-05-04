<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

$codigo = $_POST["codigo"];
var_dump($codigo);
// $buscarProd = "SELECT iden, tequis, qro FROM controlinv WHERE codigo = $codigo";

// if($prodEnc = mysqli_query($mysqliCon,$buscarProd)){
//     $row = mysqli_fetch_row($prodEnc);
//     $ide = $row[0];
// }else{
//     $ide = "No se encontro";
// }

$mysqliCon->close();
?>