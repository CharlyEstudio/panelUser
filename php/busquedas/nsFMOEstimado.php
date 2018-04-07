<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de FMO Tipo N Solicitado
    $queryNsFMOSolicitadoN = "SELECT sum(des.descantidad * des.desventa) as importeSolicitadoFMON
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'N'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMON = $getConnection->query($queryNsFMOSolicitadoN);
    $qNsFMOSolicitadoN = mysqli_fetch_array($resultQueryDiaFMON);
    if($qNsFMOSolicitadoN === NULL){
        $importeSolicitadoFMON = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoFMON = $qNsFMOSolicitadoN["importeSolicitadoN"];
      $importeSolicitadoFMON = $qNsFMOSolicitadoN["importeSolicitadoFMON"];
    }

    //Sacamos el % de Nivel de Servicio de FMO Tipo F Solicitado
    $queryNsFMOSOlicitadoF = "SELECT sum(des.descantidad * des.desventa) as importeSolicitadoFMOF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'F'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMON = $getConnection->query($queryNsFMOSOlicitadoF);
    $qNsFMOSOlicitadoF = mysqli_fetch_array($resultQueryDiaFMON);
    if($qNsFMOSOlicitadoF === NULL){
        $importeSolicitadoFMOF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoFMOF = $qNsFMOSOlicitadoF["importeSOlicitadoF"];
      $importeSolicitadoFMOF = $qNsFMOSOlicitadoF["importeSolicitadoFMOF"];
    }
    // Porcentaje Estimado FMO
    if($importeSolicitadoFMON === NULL){
      $EstimadoFMO = '0.00%';
    }else{
      $EstimadoT = $importeSolicitadoFMOF * 100;
      $EstimadoFMO = bcdiv($EstimadoT,$importeSolicitadoFMON,2).'%';
    }

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsFMOEstimado = $EstimadoFMO;
    echo $nsFMOEstimado;
?>