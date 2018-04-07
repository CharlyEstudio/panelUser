<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de Truper Tipo N Solicitado
    $queryNsTruperSolicitadoN = "SELECT sum(des.descantidad * des.desventa) as importeSolicitadoN
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'N'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperSolicitadoN = $getConnection->query($queryNsTruperSolicitadoN);
    $qNsTruperSolicitadoN = mysqli_fetch_array($resultQueryDiaTruperSolicitadoN);
    if($qNsTruperSolicitadoN === NULL){
        $importeSolicitadoTruperN = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoTruperN = $qNsTruperSolicitadoN["importeSolicitadoN"];
      $importeSolicitadoTruperN = $qNsTruperSolicitadoN["importeSolicitadoN"];
    }

    //Sacamos el % de Nivel de Servicio de Truper Tipo F Solicitado
    $queryNsTruperSolicitadoF = "SELECT sum(des.descantidad * des.desventa) as importeSolicitadoF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'F'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperSolicitadoF = $getConnection->query($queryNsTruperSolicitadoF);
    $qNsTruperSolicitadoF = mysqli_fetch_array($resultQueryDiaTruperSolicitadoF);
    if($qNsTruperSolicitadoF === NULL){
        $importeSolicitadoTruperF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoTruperSolicitadoF = $qNsTruperSolicitadoF["importeSolicitadoN"];
      $importeSolicitadoTruperF = $qNsTruperSolicitadoF["importeSolicitadoF"];
    }

    // Porcentaje Estimado Truper
    if($importeSolicitadoTruperN === NULL){
      $EstimadoTruper = '0.00%';
    }
    $EstimadoT = $importeSolicitadoTruperF * 100;
    $EstimadoTruper = bcdiv($EstimadoT,$importeSolicitadoTruperN,2).'%';

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsTruEstimado = $EstimadoTruper;
    echo $nsTruEstimado;
?>