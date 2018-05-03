<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 9 a 10
    $totalCol16a17 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '16:00' and doc.hora <= '17:00')
                        and doc.tipo = 'F'";
    $totalEncontradoCol16a17 = mysqli_query($getConnection, $totalCol16a17);
    $rowTotalCol16a17 = mysqli_fetch_row($totalEncontradoCol16a17);
    $nTotal16a17 = $rowTotalCol16a17[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $t16a17 = $nTotal16a17;
    echo $t16a17;
?>