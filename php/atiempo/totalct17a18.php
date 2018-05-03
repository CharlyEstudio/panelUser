<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct17a18 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '17:00' and doc.hora <= '18:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct17a18 = mysqli_query($getConnection, $totalColct17a18);
    $rowTotalColct17a18 = mysqli_fetch_row($totalEncontradoColct17a18);
    $nTotalct17a18 = $rowTotalColct17a18[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct17a18 = $nTotalct17a18;
    echo $tct17a18;
?>