<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de Truper Tipo C
    $queryNsTruperC = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) AS importeSolicitadoC
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                            AND des.destipo = 'C'
                            AND i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperC = $getConnection->query($queryNsTruperC);
    $qNsTruperC = mysqli_fetch_array($resultQueryDiaTruperC);
    if($qNsTruperC === NULL){
        $importeSolicitadoTruperC = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeSolicitadoTruperC = $qNsTruperC["importeSolicitadoC"];
    }

    //Sacamos el % de Nivel de Servicio de Truper Tipo N Solicitado
    $queryNsTruperSolicitadoF = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) as importeSolicitadoF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'N'
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
    if($importeSolicitadoTruperC === NULL){
      $EstimadoTruper = '0.00%';
    }
    $EstimadoT = $importeSolicitadoTruperF * 100;
    $EstimadoTruper = bcdiv($EstimadoT,$importeSolicitadoTruperC,2).'%';

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsTruEstimado = $EstimadoTruper;
    echo $nsTruEstimado;
?>