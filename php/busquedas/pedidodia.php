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
                                JOIN cli c ON c.clienteid = d. clienteid
                            WHERE d.fecha = '".$dia."'
                                AND d.tipo = 'F'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidos = mysqli_query($getConnection, $buscarPedidosAjax);
    $pedidosAjax = mysqli_num_rows($ajaxPedidos);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedido = $pedidosAjax;
    echo $pedido;
?>