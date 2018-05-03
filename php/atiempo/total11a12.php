<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 9 a 10
    $totalCol11a12 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '11:00' and doc.hora <= '12:00')
                        and doc.tipo = 'F'";
    $totalEncontradoCol11a12 = mysqli_query($getConnection, $totalCol11a12);
    $rowTotalCol11a12 = mysqli_fetch_row($totalEncontradoCol11a12);
    $nTotal11a12 = $rowTotalCol11a12[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $t11a12 = $nTotal11a12;
    echo $t11a12;
?>