<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $fecInicio = $_POST['fecInicio'];
    $fecFinal = $_POST['fecFinal'];

    $importeVenta = 0;

    // Pedidos y ventas por Cancelados del día
    $queryPedDiaCancelacion = "SELECT count(docid)
                            FROM doc d
                            WHERE (
                                        d.fecha >= '".$fecInicio."'
                                        AND d.fecha <= '".$fecFinal."'
                                    )
                              AND estado = 'C'";
    $resultQueryDiaCancelacion = $getConnection->query($queryPedDiaCancelacion);
    $numPedDiaCancelacion = mysqli_num_rows($resultQueryDiaCancelacion);
    if($numPedDiaCancelacion === NULL){
      $PedCancelacion = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $PedCancelacion = $numPedDiaCancelacion;
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $numCancelados = $PedCancelacion;
    echo $numCancelados;
?>