<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de FMO Tipo C
    $queryNsFMOC = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) AS importeSolicitadoFMOC
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                            AND des.destipo = 'C'
                            and i.clvprov LIKE '8%'";
    $resultQueryDiaFMOC = $getConnection->query($queryNsFMOC);
    $qNsFMOC = mysqli_fetch_array($resultQueryDiaFMOC);
    if($qNsFMOC === NULL){
        $importeSolicitadoFMOC = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeSolicitadoFMOC = $qNsFMOC["importeSolicitadoFMOC"];
    }

    //Sacamos el % de Nivel de Servicio de FMO Tipo N Solicitado
    $queryNsFMOSOlicitadoF = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) as importeSolicitadoFMOF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'N'
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
    if($importeSolicitadoFMOC === NULL){
      $EstimadoFMO = '0.00%';
    }else{
      $EstimadoT = $importeSolicitadoFMOF * 100;
      $EstimadoFMO = bcdiv($EstimadoT,$importeSolicitadoFMOC,2).'%';
    }

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsFMOEstimado = $EstimadoFMO;
    echo $nsFMOEstimado;
?>