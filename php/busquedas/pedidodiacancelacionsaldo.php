<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    // Pedidos y ventas por Cancelados del día
    $queryPedDiaCancelacion = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedCancelacion
                                FROM doc d
                                WHERE d.fecha = '$dia'
                                AND estado = 'C'";
    $resultQueryDiaCancelacion = $getConnection->query($queryPedDiaCancelacion);
    $qPedDiaCancelacion = mysqli_fetch_array($resultQueryDiaCancelacion);
    if($qPedDiaCancelacion === NULL){
      $sumCancelacion = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $sumCancelacion = "$ ".number_format($qPedDiaCancelacion["TotalPedCancelacion"], 2, '.',',')." *";
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoCancelacionSaldo = $sumCancelacion;
    echo $pedidoCancelacionSaldo;
?>