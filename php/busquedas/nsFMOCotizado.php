<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de FMO Tipo C
    $queryNsFMOC = "SELECT SUM(des.descantidad * des.desventa) AS importeSolicitadoFMOC
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

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsFMOCotizado = '$ '.number_format($importeSolicitadoFMOC, 2, ".", ",");
    echo $nsFMOCotizado;
?>