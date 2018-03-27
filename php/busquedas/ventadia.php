<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");

    $queryVtaDia = "SELECT SUM(SUBTOTAL2) AS total
                          FROM doc
                          WHERE fecha = '".$dia."'
                            AND tipo = 'F'
                            AND serie NOT LIKE 'CH'
                            AND FECCAN = 0";
    $resultQueryDia = mysqli_query($getConnection, $queryVtaDia);
    $qVtaDia = mysqli_fetch_array($resultQueryDia);
    $totalVentaDia = $qVtaDia["total"];
    $formatoTotalVentaDia = "$ ".number_format($totalVentaDia, 2, '.',',')." *";
    // var_dump($pedidosAjax);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $mensaje = $formatoTotalVentaDia;
    echo $mensaje;
?>