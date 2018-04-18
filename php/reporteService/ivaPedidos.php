<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $ivaPedidos = 0;

    //Se hace la busqueda de pedidos y ventas totales del Dia
    $queryPedDia = "SELECT SUM(impuesto) AS ImpuestoPed
                            FROM doc
                          WHERE (
                                    fecha >= '".$fecInicio."'
                                    AND fecha <= '".$fecFinal."'
                                )
                            AND tipo = 'C'";
    $resultQueryDia = $getConnection->query($queryPedDia);
    $qPedDia = mysqli_fetch_array($resultQueryDia);
    if($qPedDia === NULL){
      $ivaP = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $ivaP = "$ ".number_format($qPedDia["ImpuestoPed"], 2, '.',',');
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $ivaPedidos = $ivaP;
    echo $ivaPedidos;
?>