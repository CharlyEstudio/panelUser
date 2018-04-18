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
    $buscarPedidosFacturarAjax = "SELECT count(d.docid)
                            FROM doc d
                            WHERE (
                                        d.fecha <= '".$fecFinal."'
                                        AND d.fecha >= '".$fecInicio."'
                                    )
                                AND d.tipo = 'F'
                                AND d.serie NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidosFacturar = mysqli_query($getConnection, $buscarPedidosFacturarAjax);
    $pedidosFacturarAjax = mysqli_fetch_row($ajaxPedidosFacturar);
    $PedFacturados = $pedidosFacturarAjax[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $numFact = $PedFacturados;
    echo $numFact;
?>