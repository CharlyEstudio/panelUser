<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    // Cartera TOTAL
    $getCarteraTo = "SELECT
                  SUM(d.total - d.totalpagado) as TotalCartera
                  FROM doc d
                  WHERE d.total > d.totalpagado
                    AND d.tipo = 'F'
                    AND (
                          (SELECT DATEDIFF(d.vence, '".$dia."')) < 0
                          OR (SELECT DATEDIFF(d.vence, '".$dia."')) > 0
                        )";
    $resultGetCarteraTo = mysqli_query($getConnection,$getCarteraTo);
    $rowCarteraTo = mysqli_fetch_array($resultGetCarteraTo);
    if($rowCarteraTo === NULL){
      $CarteraToF = 0;
    } else {
      $CarteraToF = $rowCarteraTo["TotalCartera"];
    }
    $CarteraTo = "$ ".number_format($CarteraToF, 2, ".", ",");
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $carterato = $CarteraTo;
    echo $carterato;
?>