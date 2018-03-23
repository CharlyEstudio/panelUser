<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosFacturarAjax = "SELECT d.docid
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND tipo = 'F'
                                AND serie NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidosFacturar = mysqli_query($getConnection, $buscarPedidosFacturarAjax);
    $pedidosFacturarAjax = mysqli_num_rows($ajaxPedidosFacturar);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoFactura = $pedidosFacturarAjax;
    echo $pedidoFactura;
?>