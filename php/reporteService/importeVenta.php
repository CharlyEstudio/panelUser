<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $importeVenta = 0;

    //Se hace la busqueda de ventas totales del mes
    $queryVtaMes = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS Total
                        FROM doc
                        WHERE (
                                fecha <= '".$fecFinal."'
                                AND fecha >= '".$fecInicio."' 
                                )
                            AND tipo = 'F'
                            AND serie NOT LIKE 'CH'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryMes = mysqli_query($getConnection, $queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $totalVentaMes = $qVtaMes['Total'];
    $formatTotalVentaMes = "$ ".number_format($totalVentaMes, 2, '.',',')."*";
    $importeVenta = $formatTotalVentaMes;
    echo $importeVenta
?>