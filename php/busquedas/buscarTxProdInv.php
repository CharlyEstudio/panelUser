<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

$codigo = $_POST["codigo"];
$buscarProd = "SELECT * FROM controlinv WHERE codigo = $codigo";

$mysqliCon->close();
?>