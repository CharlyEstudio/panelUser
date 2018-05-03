<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct18a19 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '18:00' and doc.hora <= '19:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct18a19 = mysqli_query($getConnection, $totalColct18a19);
    $rowTotalColct18a19 = mysqli_fetch_row($totalEncontradoColct18a19);
    $nTotalct18a19 = $rowTotalColct18a19[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct18a19 = $nTotalct18a19;
    echo $tct18a19;
?>