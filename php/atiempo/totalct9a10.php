<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct9a10 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '09:00' and doc.hora <= '10:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct9a10 = mysqli_query($getConnection, $totalColct9a10);
    $rowTotalColct9a10 = mysqli_fetch_row($totalEncontradoColct9a10);
    $nTotalct9a10 = $rowTotalColct9a10[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct9a10 = $nTotalct9a10;
    echo $tct9a10;
?>