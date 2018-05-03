<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct11a12 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '11:00' and doc.hora <= '12:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct11a12 = mysqli_query($getConnection, $totalColct11a12);
    $rowTotalColct11a12 = mysqli_fetch_row($totalEncontradoColct11a12);
    $nTotalct11a12 = $rowTotalColct11a12[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct11a12 = $nTotalct11a12;
    echo $tct11a12;
?>