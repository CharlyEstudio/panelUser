<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 8 a 9
    $totalColct10a11 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '10:00' and doc.hora <= '11:00')
                        and doc.tipo = 'C'";
    $totalEncontradoColct10a11 = mysqli_query($getConnection, $totalColct10a11);
    $rowTotalColct10a11 = mysqli_fetch_row($totalEncontradoColct10a11);
    $nTotalct10a11 = $rowTotalColct10a11[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $tct10a11 = $nTotalct10a11;
    echo $tct10a11;
?>