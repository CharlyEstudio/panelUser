<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $dia  = date("Y-m-d");

    // Pedidos y ventas por Cancelados del día
    $queryPedDiaCancelacion = "SELECT docid
                            FROM doc
                            WHERE fecha = '$dia'
                              AND (tipo NOT LIKE 'E' and tipo NOT LIKE 'M')
                              AND estado = 'C'";
    $resultQueryDiaCancelacion = $getConnection->query($queryPedDiaCancelacion);
    // $qPedDiaCancelacion = mysqli_fetch_row($resultQueryDiaCancelacion);
    $numPedDiaCancelacion = mysqli_num_rows($resultQueryDiaCancelacion);
    if($numPedDiaCancelacion === NULL){
      $sumCancelacion = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $sumCan = 0;
      while($qPedDiaCancelacion = mysqli_fetch_array($resultQueryDiaCancelacion)){
      // foreach($qPedDiaCancelacion as $row){
        $docid = $qPedDiaCancelacion[0];
        $buscarPartidasCanceladas = "SELECT sum(desventa * descantidad) as SumPedCan
                                      FROM des
                                      where descantidad > 0
                                      and desfecha = '$dia'
                                      and desdocid = $docid";
        $resultPartidasCanceladas = $getConnection->query($buscarPartidasCanceladas);
        $PartidasCanceladas = mysqli_fetch_array($resultPartidasCanceladas);
        $sumCan = $sumCan + $PartidasCanceladas["SumPedCan"];
        // var_dump($qPedDiaCancelacion);
      }
      $sumCancelacion = "$ ".number_format($sumCan, 2, '.',',').'*';
    }
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $pedidoCancelacionSaldo = $sumCancelacion;
    echo $pedidoCancelacionSaldo;
?>