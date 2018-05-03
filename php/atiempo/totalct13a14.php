<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct13a14 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '13:00' and doc.hora <= '14:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct13a14 = mysqli_query($getConnection, $totalColct13a14);
    $rowTotalColct13a14 = mysqli_fetch_row($totalEncontradoColct13a14);
    $nTotalct13a14 = $rowTotalColct13a14[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct13a14 = $nTotalct13a14;
    echo $tct13a14;
?>