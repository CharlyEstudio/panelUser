<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 9 a 10
    $totalCol14a15 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '14:00' and doc.hora <= '15:00')
                        and doc.tipo = 'F'";
    $totalEncontradoCol14a15 = mysqli_query($getConnection, $totalCol14a15);
    $rowTotalCol14a15 = mysqli_fetch_row($totalEncontradoCol14a15);
    $nTotal14a15 = $rowTotalCol14a15[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $t14a15 = $nTotal14a15;
    echo $t14a15;
?>