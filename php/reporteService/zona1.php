<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $importeVenta = 0;
    
    //Se hace la busqueda de ventas totales del Mes Zona 1
    $queryVtaMesZona1 = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Zona1
                          FROM doc d
                            JOIN per p ON p.perid = d.vendedorid
                          WHERE d.tipo = 'F'
                            AND d.serie NOT LIKE 'CH'
                            AND p.sermov = 1
                            AND (
                                  d.fecha <= '$fecFinal'
                                  AND d.fecha >= '$fecInicio' 
                                )";
    $resultQueryMesZona1 = $getConnection->query($queryVtaMesZona1);
    $qVtaMesZona1 = mysqli_fetch_array($resultQueryMesZona1);
    if($qVtaMesZona1 === NULL){
      $totalVentaMesZona1 = 0;
    } else {
      $totalVentaMesZona1 = $qVtaMesZona1['Zona1'];
    }
    $formatTotalVentaMesZona1 = "$ ".number_format($totalVentaMesZona1, 2, '.',',')."*";
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $zona1 = $formatTotalVentaMesZona1;
    echo $zona1;
?>