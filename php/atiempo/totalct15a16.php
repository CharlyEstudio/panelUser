<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct15a16 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '15:00' and doc.hora <= '16:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct15a16 = mysqli_query($getConnection, $totalColct15a16);
    $rowTotalColct15a16 = mysqli_fetch_row($totalEncontradoColct15a16);
    $nTotalct15a16 = $rowTotalColct15a16[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct15a16 = $nTotalct15a16;
    echo $tct15a16;
?>