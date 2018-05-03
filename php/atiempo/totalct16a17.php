<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct16a17 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '16:00' and doc.hora <= '17:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct16a17 = mysqli_query($getConnection, $totalColct16a17);
    $rowTotalColct16a17 = mysqli_fetch_row($totalEncontradoColct16a17);
    $nTotalct16a17 = $rowTotalColct16a17[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct16a17 = $nTotalct16a17;
    echo $tct16a17;
?>