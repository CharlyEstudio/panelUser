<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 9 a 10
    $totalCol9a10 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '09:00' and doc.hora <= '10:00')
                        and doc.tipo = 'F'";
    $totalEncontradoCol9a10 = mysqli_query($getConnection, $totalCol9a10);
    $rowTotalCol9a10 = mysqli_fetch_row($totalEncontradoCol9a10);
    $nTotal9a10 = $rowTotalCol9a10[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $t9a10 = $nTotal9a10;
    echo $t9a10;
?>