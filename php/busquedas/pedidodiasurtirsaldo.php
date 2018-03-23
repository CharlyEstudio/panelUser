<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosSurtirAjax = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPed
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND tipo = 'N'
                                AND tipo NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidosSurtir = mysqli_query($getConnection, $buscarPedidosSurtirAjax);
    $pedidosSurtirAjax = mysqli_fetch_array($ajaxPedidosSurtir);
    $sumSurt = "$ ".number_format($pedidosSurtirAjax["TotalPed"], 2, '.',',');
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidosurtirSaldo = $sumSurt;
    echo $pedidosurtirSaldo;
?>