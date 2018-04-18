<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $codigo = $_POST['codigo'];
    $dia  = date("Y-m-d");

    // Morosidad TOTAL.
    $Existencia8 = "SELECT alm.existencia, i.clvprov
                        from alm
                            LEFT OUTER JOIN inv i ON i.articuloid = alm.articuloid
                        where i.clvprov LIKE '8%'
                            and i.clvprov = $codigo
                            and alm.existencia > 0";
    $resultExistencia8 = mysqli_query($getConnection,$Existencia8);
    $rowExistencia = mysqli_fetch_array($resultExistencia8);
    if($rowExistencia === NULL){
      $existencia = 0;
    } else {
      $existencia = number_format($rowExistencia["existencia"], 0, ".", ",");
    }
    $existencias = $existencia;
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    echo $existencias;
?>