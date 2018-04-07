<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Sacamos el mes en que estamos
    $month = date('m');
    $year = date('Y');
    $dayVtaTotMes = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $dayVtaTotMes, $year));

    //Se hace la busqueda de ventas totales del Mes Zona 2
    $queryVtaMesZona2 = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Zona2
                          FROM doc d
                            JOIN per p ON p.perid = d.vendedorid
                          WHERE d.tipo = 'F'
                            AND d.serie NOT LIKE 'CH'
                            AND p.sermov = 2
                            AND (
                                  d.fecha <= '$ultimoDiaMes'
                                  AND d.fecha >= '$primerDiaMes' 
                                )";
    $resultQueryMesZona2 = $getConnection->query($queryVtaMesZona2);
    $qVtaMesZona2 = mysqli_fetch_array($resultQueryMesZona2);
    if($qVtaMesZona2 === NULL){
      $totalVentaMesZona2 = 0;
    } else {
      $totalVentaMesZona2 = $qVtaMesZona2['Zona2'];
    }
    $formatTotalVentaMesZona2 = "$ ".number_format($totalVentaMesZona2, 2, '.',',')."*";
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $vtaZ2 = $formatTotalVentaMesZona2;
    echo $vtaZ2;
?>