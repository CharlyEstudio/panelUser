<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    // Pedidos y ventas por Cancelados del día
    $queryPedDiaCancelacion = "SELECT COUNT(docid) AS PedidosCancelacion
                            FROM doc d
                            WHERE d.fecha = '$dia'
                              AND estado = 'C'";
    $resultQueryDiaCancelacion = $getConnection->query($queryPedDiaCancelacion);
    $qPedDiaCancelacion = mysqli_fetch_array($resultQueryDiaCancelacion);
    if($qPedDiaCancelacion === NULL){
      $PedCancelacion = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $PedCancelacion = $qPedDiaCancelacion["PedidosCancelacion"];
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoCancelacion = $PedCancelacion;
    echo $pedidoCancelacion;
?>