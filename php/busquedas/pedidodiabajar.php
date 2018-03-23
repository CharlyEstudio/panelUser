<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosBajarAjax = "SELECT d.docid
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND tipo = 'C'
                                AND tipo NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidosBajar = mysqli_query($getConnection, $buscarPedidosBajarAjax);
    $pedidosBajarAjax = mysqli_num_rows($ajaxPedidosBajar);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoBajar = $pedidosBajarAjax;
    echo $pedidoBajar;
?>