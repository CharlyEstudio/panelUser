<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del mes
    $month = date('m');
    $year = date('Y');
    $dayVtaTotMes = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $dayVtaTotMes, $year));

    $queryVtaMes = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS Total
                        FROM doc
                        WHERE (
                                fecha <= '".$ultimoDiaMes."'
                                AND fecha >= '".$primerDiaMes."' 
                                )
                            AND tipo = 'F'
                            AND serie NOT LIKE 'CH'";
    $resultQueryMes = mysqli_query($getConnection, $queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $totalVentaMes = (float) $qVtaMes['Total'];
    $mensaje = $totalVentaMes;
    echo $mensaje
?>