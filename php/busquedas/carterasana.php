<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    // Cartera Sana TOTAL.
    $getCarteraSana = "SELECT
                  SUM(d.total - d.totalpagado) as TotalCarteraSana
                  FROM doc d
                  WHERE d.total > d.totalpagado
                    AND d.tipo = 'F'
                    AND (SELECT DATEDIFF(d.vence, '".$dia."')) > 0";
    $resultGetCarteraSana = mysqli_query($getConnection,$getCarteraSana);
    $rowCarteraSana = mysqli_fetch_array($resultGetCarteraSana);
    if($rowCarteraSana === NULL){
      $CarteraSanaF = 0;
    } else {
      $CarteraSanaF = $rowCarteraSana["TotalCarteraSana"];
    }
    $CarteraSana = "$ ".number_format($CarteraSanaF, 2, ".", ",");
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $carteraS = $CarteraSana;
    echo $carteraS;
?>