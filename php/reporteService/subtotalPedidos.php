<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $subtotalPedidos = 0;

    //Se hace la busqueda de ventas totales del Dia
    $buscarPedidosAjax = "SELECT  SUM((SELECT (d.SUBTOTAL2 + d.SUBTOTAL1) FROM DUAL)) AS TotalPed
                            FROM doc d
                            WHERE (
                                        d.fecha >= '".$fecInicio."'
                                        AND d.fecha <= '".$fecFinal."'
                                    )
                                AND d.tipo = 'C'";
    $ajaxPedidos = mysqli_query($getConnection, $buscarPedidosAjax);
    $pedidosAjax =mysqli_fetch_array($ajaxPedidos);
    $sumBaja = "$ ".number_format($pedidosAjax["TotalPed"], 2, '.',',');
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $subtotalPedidos = $sumBaja;
    echo $subtotalPedidos;
?>