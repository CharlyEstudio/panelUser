<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $importeVenta = 0;

    //Se hace la busqueda de ventas totales del Dia
    $buscarPedidosFacturaAjax = "SELECT  SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPed
                            FROM doc d
                            WHERE (
                                        d.fecha >= '".$fecInicio."'
                                        AND d.fecha <= '".$fecFinal."'
                                    )
                                AND tipo = 'F'
                                AND serie NOT LIKE 'CH'";
    $ajaxPedidosFactura = mysqli_query($getConnection, $buscarPedidosFacturaAjax);
    $pedidosFacturaAjax =mysqli_fetch_array($ajaxPedidosFactura);
    $sumFactura = "$ ".number_format($pedidosFacturaAjax["TotalPed"], 2, '.',',')."*";
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $impoFact = $sumFactura;
    echo $impoFact;
?>