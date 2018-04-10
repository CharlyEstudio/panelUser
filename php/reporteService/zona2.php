<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $importeVenta = 0;
    
    //Se hace la busqueda de ventas totales del Mes Zona 2
    $queryVtaMesZona2 = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Zona2
                          FROM doc d
                            JOIN per p ON p.perid = d.vendedorid
                          WHERE d.tipo = 'F'
                            AND d.serie NOT LIKE 'CH'
                            AND p.sermov = 2
                            AND (
                                  d.fecha <= '$fecFinal'
                                  AND d.fecha >= '$fecInicio' 
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
    $zona2 = $formatTotalVentaMesZona2;
    echo $zona2;
?>