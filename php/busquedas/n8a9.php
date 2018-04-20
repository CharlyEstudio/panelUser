<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    // $perid = $_POST['perid'];
    $perid = $_POST["perid"];
    $dia = date('Y-m-d');

    $buscar8a9 = "SELECT count(docid)
                      FROM doc
                      where fecha = '".$dia."'
                        and (hora >= '08:00' and hora <= '09:00')
                        and vendedorid = ".$perid."
                        and tipo = 'C'";
    $encontrado8a9 = mysqli_query($getConnection, $buscar8a9);

    while($row8a9 = mysqli_fetch_row($encontrado8a9)){
        $n8a9 = $row8a9[0];
        // $mensaje .= "<a class='dropdown-item' href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy(".$recibirParametro.")'>".$categoria."</a>";
    }

    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $n8a9Men = $n8a9;
    echo $n8a9Men;
?>