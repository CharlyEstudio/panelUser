<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosBajarAjax = "SELECT  SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPed
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND tipo = 'C'
                                AND serie NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0
                                AND estado NOT LIKE 'C'";
    $ajaxPedidosBajar = mysqli_query($getConnection, $buscarPedidosBajarAjax);
    $pedidosBajarAjax =mysqli_fetch_array($ajaxPedidosBajar);
    $sumBaja = "$ ".number_format($pedidosBajarAjax["TotalPed"], 2, '.',',')." *";
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoBajarSaldo = $sumBaja;
    echo $pedidoBajarSaldo;
?>