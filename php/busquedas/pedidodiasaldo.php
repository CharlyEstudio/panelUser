<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosAjax = "SELECT  SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPed
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND tipo = 'C'";
    $ajaxPedidos = mysqli_query($getConnection, $buscarPedidosAjax);
    $pedidosAjax =mysqli_fetch_array($ajaxPedidos);
    $sumBaja = "$ ".number_format($pedidosAjax["TotalPed"], 2, '.',',');
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoSaldo = $sumBaja;
    echo $pedidoSaldo;
?>