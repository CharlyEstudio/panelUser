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
                                AND tipo = 'F'
                                AND serie NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
    $ajaxPedidosFacturar = mysqli_query($getConnection, $buscarPedidosFacturarAjax);
    $pedidosFacturarAjax = mysqli_num_rows($ajaxPedidosFacturar);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $numFact = $pedidosFacturarAjax;
    echo $numFact;
?>