<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    // Morosidad TOTAL.
    $getMorosidad = "SELECT
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                  WHERE d.total > d.totalpagado
                    AND d.tipo = 'F'
                    AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
    $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
    if($rowMorosidad === NULL){
      $MorosidadF = 0;
    } else {
      $MorosidadF = $rowMorosidad["TotalDeuda"]*(-1);
    }
    $Morosidad = "$ ".number_format($MorosidadF, 2, ".", ",");
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $moros = $Morosidad;
    echo $moros;
?>