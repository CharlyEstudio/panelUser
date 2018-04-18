<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $codigo = $_POST['codigo'];
    $dia  = date("Y-m-d");

    // Morosidad TOTAL.
    $Existencia8 = "SELECT p.precio
                        from precios p
                            LEFT OUTER JOIN inv i ON i.articuloid = p.articuloid
                        where i.clvprov LIKE '8%'
                            and i.clvprov = $codigo
                            and p.nprecio = 3";
    $resultExistencia8 = mysqli_query($getConnection,$Existencia8);
    $rowExistencia = mysqli_fetch_array($resultExistencia8);
    if($rowExistencia === NULL){
      $nombre = 'Sin Existencia';
    } else {
      $nombre = $rowExistencia["precio"];
    }
    $existencias = '$ ' . number_format($nombre, 2, ".", ",");
    
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    echo $existencias;
?>