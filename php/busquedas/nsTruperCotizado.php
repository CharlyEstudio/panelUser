<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    //Sacamos el % de Nivel de Servicio de Truper Tipo C
    $queryNsTruperC = "SELECT SUM(des.descantidad * des.desventa) AS importeSolicitadoC
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

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $nsTruCotizado = '$ '.number_format($importeSolicitadoTruperC, 2, ".", ",");
    echo $nsTruCotizado;
?>