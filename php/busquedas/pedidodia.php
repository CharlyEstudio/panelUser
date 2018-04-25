<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosAjax = "SELECT d.docid
                            FROM doc d
                            WHERE d.fecha = '".$dia."'
                                AND d.tipo = 'C'";
    $ajaxPedidos = mysqli_query($getConnection, $buscarPedidosAjax);
    $pedidosAjax = mysqli_num_rows($ajaxPedidos);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedido = $pedidosAjax;
    echo $pedido;
?>