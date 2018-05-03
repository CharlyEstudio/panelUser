<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct12a13 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '12:00' and doc.hora <= '13:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct12a13 = mysqli_query($getConnection, $totalColct12a13);
    $rowTotalColct12a13 = mysqli_fetch_row($totalEncontradoColct12a13);
    $nTotalct12a13 = $rowTotalColct12a13[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct12a13 = $nTotalct12a13;
    echo $tct12a13;
?>