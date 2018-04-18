<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $pedidosTotal = 0;

    //Se hace la busqueda de pedidos y ventas totales del Dia
    $queryPedDia = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1 + impuesto) FROM DUAL)) AS TotalPed
                            FROM doc
                          WHERE (
                                    fecha >= '".$fecInicio."'
                                    AND fecha <= '".$fecFinal."'
                                )
                            AND tipo = 'C'";
    $resultQueryDia = $getConnection->query($queryPedDia);
    $qPedDia = mysqli_fetch_array($resultQueryDia);
    // var_dump($qPedDia);
    if($qPedDia === NULL){
      $totalP = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $totalP = "$ ".number_format($qPedDia["TotalPed"], 2, '.',',');
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidosTotal = $totalP;
    echo $pedidosTotal;
?>