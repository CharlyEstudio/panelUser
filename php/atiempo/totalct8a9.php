<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct8a9 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '08:00' and doc.hora <= '09:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct8a9 = mysqli_query($getConnection, $totalColct8a9);
    $rowTotalColct8a9 = mysqli_fetch_row($totalEncontradoColct8a9);
    $nTotalct8a9 = $rowTotalColct8a9[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct8a9 = $nTotalct8a9;
    echo $tct8a9;
?>