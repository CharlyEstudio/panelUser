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

    //Se hace la busqueda de ventas totales del Mes Especiales
    $queryVtaMesEspeciales = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Especiales
                                FROM doc d
                                  JOIN per p ON p.perid = d.vendedorid
                                WHERE d.tipo = 'F'
                                  AND d.serie NOT LIKE 'CH'
                                  AND (
                                        p.perid = 16
                                        OR p.perid = 20
                                        OR p.perid = 21
                                        OR p.perid = 145
                                        OR p.perid = 152
                                      )
                                  AND (
                                        d.fecha <= '$ultimoDiaMes'
                                        AND d.fecha >= '$primerDiaMes' 
                                      )";
    $resultQueryMesEspeciales = $getConnection->query($queryVtaMesEspeciales);
    $qVtaMesEspeciales = mysqli_fetch_array($resultQueryMesEspeciales);
    if($qVtaMesEspeciales === NULL){
      $totalVentaMesEspeciales = 0;
    } else {
      $totalVentaMesEspeciales = $qVtaMesEspeciales['Especiales'];
    }
    $formatTotalVentaMesEspeciales = "$ ".number_format($totalVentaMesEspeciales, 2, '.',',')."*";
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $vtaEspe = $formatTotalVentaMesEspeciales;
    echo $vtaEspe;
?>