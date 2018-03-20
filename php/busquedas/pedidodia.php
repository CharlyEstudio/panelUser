<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");
    $buscarPedidosAjax = "SELECT docid
                            FROM doc
                            WHERE fecha = '".$dia."'
                                AND tipo = 'F'
                                AND subtotal2 > 0
                                AND FECCAN = 0";
    $ajaxPedidos = mysqli_query($getConnection, $buscarPedidosAjax);
    $pedidosAjax = mysqli_num_rows($ajaxPedidos);
    // var_dump($pedidosAjax);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $mensaje = $pedidosAjax;
    echo $mensaje
?>