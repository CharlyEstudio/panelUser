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

    $queryVtaMes = "SELECT SUM(SUBTOTAL2) AS total
                        FROM doc
                        WHERE (
                            fecha <= '".$ultimoDiaMes."'
                            AND fecha >= '".$primerDiaMes."' 
                            )
                        AND tipo = 'F'
                        AND subtotal2 > 0
                        AND FECCAN = 0";
    $resultQueryMes = mysqli_query($getConnection, $queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $totalVentaMes = $qVtaMes['total'];
    $formatTotalVentaMes = "$ ".number_format($totalVentaMes, 2, '.',',');
    $mensaje = $formatTotalVentaMes;
    echo $mensaje
?>