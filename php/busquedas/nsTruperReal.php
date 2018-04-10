<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de Truper Tipo N Entregado
    $queryNsTruperEntregadoN = "SELECT sum((des.desentregado * des.desventa) - ((des.desentregado * des.desventa) * (des.desdescuento / 100))) as importeEntregadoN
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'N'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperEntregadoN = $getConnection->query($queryNsTruperEntregadoN);
    $qNsTruperEntregadoN = mysqli_fetch_array($resultQueryDiaTruperEntregadoN);
    if($qNsTruperEntregadoN === NULL){
        $importeTruperEntregadoN = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeTruperEntregadoN = $qNsTruperEntregadoN["importeEntregadoN"];
    }

    //Sacamos el % de Nivel de Servicio de Truper Tipo F Entregado
    $queryNsTruperEntregadoF = "SELECT sum((des.desentregado * des.desventa) - ((des.desentregado * des.desventa) * (des.desdescuento / 100))) as importeEntregadoF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'F'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperEntregadoF = $getConnection->query($queryNsTruperEntregadoF);
    $qNsTruperEntregadoF = mysqli_fetch_array($resultQueryDiaTruperEntregadoF);
    if($qNsTruperEntregadoF === NULL){
        $importeTruperEntregadoF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeTruperEntregadoF = $qNsTruperEntregadoF["importeEntregadoF"];
    }
    // Porcentaje Real Truper
    if($importeTruperEntregadoN === NULL){
      $RealTruper = '0.00%';
    }else{
      $RealT = $importeTruperEntregadoF * 100;
      $RealTruper = bcdiv($RealT,$importeTruperEntregadoN,2).'%';
    }

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsTruReal = $RealTruper;
    echo $nsTruReal;
?>