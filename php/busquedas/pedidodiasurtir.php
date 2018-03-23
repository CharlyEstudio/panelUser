<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosSurtirAjax = "SELECT COUNT(d.docid) AS Pedidos
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND tipo = 'N'
                                AND tipo NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidosSurtir = mysqli_query($getConnection, $buscarPedidosSurtirAjax);
    $pedidosSurtirAjax = mysqli_fetch_array($ajaxPedidosSurtir);
    $pedidos = $pedidosSurtirAjax["Pedidos"];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidosurtir = $pedidos;
    echo $pedidosurtir;
?>