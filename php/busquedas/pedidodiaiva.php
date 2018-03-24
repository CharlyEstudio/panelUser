<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos y ventas totales del Dia
    $queryPedDia = "SELECT SUM((SELECT (impuesto) FROM DUAL)) AS ImpuestoPed
                            FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'C'";
    $resultQueryDia = $getConnection->query($queryPedDia);
    $qPedDia = mysqli_fetch_array($resultQueryDia);
    if($qPedDia === NULL){
      $ivaP = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $ivaP = "$ ".number_format($qPedDia["ImpuestoPed"], 2, '.',',');
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoIva = $ivaP;
    echo $pedidoIva;
?>