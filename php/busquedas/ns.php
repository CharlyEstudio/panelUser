<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");

    $queryPedDia = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPed
                            FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'C'";
    $resultQueryDia = $getConnection->query($queryPedDia);
    $qPedDia = mysqli_fetch_array($resultQueryDia);
    if($qPedDia === NULL){
      $sumP = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $sumP = $qPedDia["TotalPed"];
    }

    // Ventas por Surtir del día
    $queryPedDiaSurtir = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedSurtir
                            FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'N'
                            AND tipo NOT LIKE 'CH'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDiaSurtir = $getConnection->query($queryPedDiaSurtir);
    $qPedDiaSurtir = mysqli_fetch_array($resultQueryDiaSurtir);
    if($qPedDiaSurtir === NULL){
      $SumSurNs = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $SumSurNs = $qPedDiaSurtir["TotalPedSurtir"];
    }

    // Ventas por Bajar del día
    $queryPedDiaBajar = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedBajar
                            FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'C'
                            AND tipo NOT LIKE 'CH'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDiaBajar = $getConnection->query($queryPedDiaBajar);
    $qPedDiaBajar = mysqli_fetch_array($resultQueryDiaBajar);
    if($qPedDiaBajar === NULL){
      $SumaBajNs = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $SumaBajNs = $qPedDiaBajar["TotalPedBajar"];
    }

    // Ventas por Factura del día
    $queryPedDiaFactura = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedFactura
                            FROM doc d
                            WHERE d.fecha = '$dia'
                                AND tipo = 'F'
                                AND tipo NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $resultQueryDiaFactura = $getConnection->query($queryPedDiaFactura);
    $qPedDiaFactura = mysqli_fetch_array($resultQueryDiaFactura);
    if($qPedDiaFactura === NULL){
      $sumFacNS = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $sumFacNS = $qPedDiaFactura["TotalPedFactura"];
    }

    $VentasDiaNs = $sumFacNS + $SumaBajNs + $SumSurNs;
    $divisionVDN = $VentasDiaNs * 100;
    $ns = "NS ".bcdiv($divisionVDN,$sumP,2)."%";
    // $ns = "NS ".$divisionVDN/$sumP."%";

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    echo $ns;
?>