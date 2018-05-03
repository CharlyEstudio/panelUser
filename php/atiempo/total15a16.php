<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $dia  = date("Y-m-d");

    //Se hace la busqueda de pedidos totales de 9 a 10
    $totalCol15a16 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '15:00' and doc.hora <= '16:00')
                        and doc.tipo = 'F'";
    $totalEncontradoCol15a16 = mysqli_query($getConnection, $totalCol15a16);
    $rowTotalCol15a16 = mysqli_fetch_row($totalEncontradoCol15a16);
    $nTotal15a16 = $rowTotalCol15a16[0];
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $t15a16 = $nTotal15a16;
    echo $t15a16;
?>