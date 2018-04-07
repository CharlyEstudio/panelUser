<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de FMO Tipo N Entregado
    $queryNsFMOEntregadoN = "SELECT sum(des.desentregado * des.desventa) as importeEntregadoFMON
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'N'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMOF = $getConnection->query($queryNsFMOEntregadoN);
    $qNsFMOEntregadoN = mysqli_fetch_array($resultQueryDiaFMOF);
    if($qNsFMOEntregadoN === NULL){
        $importeEntregadoFMON = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeEntregadoFMON = $qNsFMOEntregadoN["importeEntregadoFMON"];
    }

    //Sacamos el % de Nivel de Servicio de FMO Tipo F Entregado
    $queryNsFMOEntregadoF = "SELECT sum(des.desentregado * des.desventa) as importeEntregadoFMOF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'F'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMOF = $getConnection->query($queryNsFMOEntregadoF);
    $qNsFMOEntregadoF = mysqli_fetch_array($resultQueryDiaFMOF);
    if($qNsFMOEntregadoF === NULL){
        $importeEntregadoFMOF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeEntregadoFMOF = $qNsFMOEntregadoF["importeEntregadoFMOF"];
    }
    // Porcentaje Real FMO
    if($importeEntregadoFMON === NULL){
      $RealFMO = '0.00%';
    }else{
      $RealT = $importeEntregadoFMOF * 100;
      $RealFMO = bcdiv($RealT,$importeEntregadoFMON,2).'%';
    }

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsFMOReal = $RealFMO;
    echo $nsFMOReal;
?>