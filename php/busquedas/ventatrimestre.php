<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Trimestre
    $month=date('m');
    if($month > 0 && $month < 4){
        $primerDiaTrimestre = date('Y-01-01');
        $ultimoDiaTrimestre = date('Y-03-31');
    } else if($month > 3 && $month < 7){
        $primerDiaTrimestre = date('Y-04-01');
        $ultimoDiaTrimestre = date('Y-06-30');
    } else if($month > 6 && $month < 10){
        $primerDiaTrimestre = date('Y-07-01');
        $ultimoDiaTrimestre = date('Y-09-30');
    } else if($month > 9){
        $primerDiaTrimestre = date('Y-10-01');
        $ultimoDiaTrimestre = date('Y-12-31');
    }
    $queryVtaTrimestre = "SELECT SUM(SUBTOTAL2) AS total
                            FROM doc
                            WHERE (
                                    fecha <= '".$ultimoDiaTrimestre."'
                                    AND fecha >= '".$primerDiaTrimestre."' 
                                    )
                                AND tipo = 'F'
                                AND subtotal2 > 0
                                AND FECCAN = 0";
    $resultQueryTrimestre = $getConnection->query($queryVtaTrimestre);
    $qVtaTrimestre = mysqli_fetch_array($resultQueryTrimestre);
    $totalVentaTrimestre = $qVtaTrimestre['total'];
    $formatTotalVentaTrimestre = "$ ".number_format($totalVentaTrimestre, 2, '.',',');
    // var_dump($pedidosAjax);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $mensaje = $formatTotalVentaTrimestre;
    echo $mensaje
?>