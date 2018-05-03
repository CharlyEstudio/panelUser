<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct14a15 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '14:00' and doc.hora <= '15:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct14a15 = mysqli_query($getConnection, $totalColct14a15);
    $rowTotalColct14a15 = mysqli_fetch_row($totalEncontradoColct14a15);
    $nTotalct14a15 = $rowTotalColct14a15[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct14a15 = $nTotalct14a15;
    echo $tct14a15;
?>