<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    //Se hace la busqueda de ventas totales del Semana
    $year=date('Y');
    $month=date('m');
    $day=date('d');
    # Obtenemos el numero de la semana
    $semana=date("W",mktime(0,0,0,$month,$day,$year));

    # Obtenemos el día de la semana de la fecha dada
    $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));

    # el 0 equivale al domingo...
    if($diaSemana==0){
    $diaSemana=7;
    }

    # A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
    $primerDia=date("Y-m-d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));

    # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
    $ultimoDia=date("Y-m-d",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));

    $queryVtaSemana = "SELECT SUM(SUBTOTAL2) AS total
                        FROM doc
                        WHERE (
                                fecha <= '$ultimoDia'
                                AND fecha >= '$primerDia' 
                                )
                            AND tipo = 'F'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQuerySemana = $getConnection->query($queryVtaSemana);
    $qVtaSemana = mysqli_fetch_array($resultQuerySemana);
    $totalVentaSemana = $qVtaSemana['total'];
    $formatTotalVentaSemana = "$ ".number_format($totalVentaSemana, 2, '.',',');
    // var_dump($pedidosAjax);
    //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    $mensaje = $formatTotalVentaSemana;
    echo $mensaje
?>