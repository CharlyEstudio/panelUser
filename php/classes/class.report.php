<?php
require_once("../class.database.php");
require_once("../functions/util.php");

class Report {
  public function getterDashBoardAdmin($params) {
    $this->getDashBoardAdmin($params);
  }

  public function getterDashBoardDirIndex($params) {
    $this->getDashBoardDirIndex($params);
  }

  public function getterDashBoardDireccion($params) {
    $this->getDashBoardDireccion($params);
  }

  public function getterEnlaceZona1($params) {
    $this->getEnlaceZona1($params);
  }

  public function getterEnlaceZona2($params) {
    $this->getEnlaceZona2($params);
  }

  public function getterDashBoardSz($params) {
    $this->getDashBoardSz($params);
  }

  public function getterGetReport($params) {
    $this->getReport($params);
  }

  public function getterGetReporteVendedor($params) {
    $this->getReporteVendedor($params);
  }

  public function getterGetShowDetailMor($params){
    $this->ShowDetailMor($params);
  }

  private function getReport($params) {
    $paramFunctions = new Util();
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $rol = $_SESSION["data"]["rol"];

    $query = "SELECT inv.descripcio, pre.nprecio, pre.precio
                FROM inv
                  JOIN precios pre ON pre.unidadid = inv.unibasid
                WHERE   (
                        nprecio = 2
                        OR nprecio = 1
                    )
                ORDER BY inv.clvprov ASC
                LIMIT 0,10";

    $resultado = mysqli_query($getConnection,$query);
    $getConnection->close();

    $result = [];
    while($row = mysqli_fetch_array($resultado)) {
      $titulo = addslashes($row["descripcio"]); // addslashes
      $params = array("titulo"=>$titulo);
      array_push($result, json_encode($params));
    }
    $paramsSend = json_encode($result);

    // NOTE pass param from php to javascript
    echo "<script>
              var obj = $paramsSend;
              </script>";

    $print = "<div class='row'>";
    $print .=   "<div class='col-md-12'";
    $print .=     "<div id='myfirstchart' style='height: 250px; margin: 40px 0 10px 250px; padding-right:280px;'>";
    $print .=     "</div>";
    $print .=   "</div>";
    $print .= "</div>"; // row

    echo $print;
  }

  private function getDashBoardDirIndex($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");

    $queryVtaDia = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'F'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDia = $getConnection->query($queryVtaDia);
    $qVtaDia = mysqli_fetch_array($resultQueryDia);
    if($qVtaDia === NULL){
      $totalVentaDia = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $totalVentaDia = $qVtaDia['total'];
    }
    $formatTotalVentaDia = number_format($totalVentaDia, 2, '.',',');

    //Se hace la busqueda de ventas totales del Mes
    $month = date('m');
    $year = date('Y');
    $dayVtaTotMes = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $dayVtaTotMes, $year));
    $queryVtaMes = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE (
                                fecha <= '$ultimoDiaMes'
                                AND fecha >= '$primerDiaMes' 
                                )
                            AND tipo = 'F'
                            and totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryMes = $getConnection->query($queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    if($qVtaMes === NULL){
      $totalVentaMes = 0;
    } else {
      $totalVentaMes = $qVtaMes['total'];
    }
    $formatTotalVentaMes = number_format($totalVentaMes, 2, '.',',');

    //Sacamos el mes en que estamos
    switch ($month) {
      case 1:
        $mes='Enero';
        $diasTotalMes = 31;
      break;
      case 2:
        $mes='Febrero';
        $diasTotalMes = 28;
      break;
      case 3:
        $mes='Marzo';
        $diasTotalMes = 31;
      break;
      case 4:
        $mes='Abril';
        $diasTotalMes = 30;
      break;
      case 5:
        $mes='Mayo';
        $diasTotalMes = 31;
      break;
      case 6:
        $mes='Junio';
        $diasTotalMes = 30;
      break;
      case 7:
        $mes='Julio';
        $diasTotalMes = 31;
      break;
      case 8:
        $mes='Agosto';
        $diasTotalMes = 31;
      break;
      case 9:
        $mes='Septiembre';
        $diasTotalMes = 30;
      break;
      case 10:
        $mes='Octubre';
        $diasTotalMes = 31;
      break;
      case 11:
        $mes='Noviembre';
        $diasTotalMes = 30;
      break;
      case 12:
        $mes='Diciembre';
        $diasTotalMes = 31;
      break;
    }

    // Morosidad TOTAL.
    $getMorosidad = "SELECT
                  SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                  FROM doc d
                  WHERE d.total > d.totalpagado
                      AND d.tipo = 'F'
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
    $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
    if($rowMorosidad === NULL){
      $MorosidadF = 0;
    } else {
      $MorosidadF = $rowMorosidad[0]*(-1);
    }
    $Morosidad = number_format($MorosidadF, 2, ".", ",");

    $print =  '<div class="row" style="margin: 50px 0 0 0;display: flex;align-items: center;justify-content: center;flex-direction: column;">
                <div class="content-wrapper">
                  <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                    <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                    <img src="../img/img_pro/barrafmo2.gif" width="200"/>
                  </div>
                  <!-- Main content -->
                  <section class="content">
                    <!-- Info boxes -->
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-red"><i class="fas fa-stopwatch" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">No. PEDIDOS AL DIA</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">#</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-yellow"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS AL MES</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaMes.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-orange"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">FACTURAS VENCIDAS AL MES</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">#</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-fuchsia"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">MOROSIDA TOTAL</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$Morosidad.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-blue"><i class="fas fa-user-plus" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">NUEVOS CLIENTES AL MES</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">#</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-green"><i class="fas fa-tasks" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">PEDIDOS DE CLIENTES NUEVOS</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">#</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </section>
                  <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
              </div>'; // End div row
    echo $print;
    $getConnection->close();
  }

  private function getDashBoardDireccion($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    //Establenciendo los dias, mes y años
    $year=date('Y');
    $month=date('m');
    $day=date('d');

    //Establecemos el trimestre
    if($month > 0 && $month < 4){
      $trim = 1;
      $primerDiaTrimestre = date('Y-01-01');
      $ultimoDiaTrimestre = date('Y-03-31');
    } else if($month > 3 && $month < 7){
      $trim = 2;
      $primerDiaTrimestre = date('Y-04-01');
      $ultimoDiaTrimestre = date('Y-06-30');
    } else if($month > 6 && $month < 10){
      $trim = 3;
      $primerDiaTrimestre = date('Y-07-01');
      $ultimoDiaTrimestre = date('Y-09-30');
    } else if($month > 9){
      $trim = 4;
      $primerDiaTrimestre = date('Y-10-01');
      $ultimoDiaTrimestre = date('Y-12-31');
    }

    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");

    $queryVtaDia = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDia = $getConnection->query($queryVtaDia);
    $qVtaDia = mysqli_fetch_array($resultQueryDia);
    if($qVtaDia === NULL){
      $totalVentaDia = 0;
    } else {
      $totalVentaDia = $qVtaDia['total'];
    }
    $formatTotalVentaDia = number_format($totalVentaDia, 2, '.',',');

    //Pedidos al día
    $queryPedDia = "SELECT docid
                      FROM doc
                      WHERE fecha = '$dia'
                        AND tipo = 'F'
                        AND totalpagado = total
                        AND subtotal2 > 0
                        AND FECCAN = 0";
    $resultQueryPedDia = $getConnection->query($queryPedDia);
    if($resultQueryPedDia === ''){
      $qPediDia = 0;
    } else{
      $qPediDia = mysqli_num_rows($resultQueryPedDia);
    }

    //Se hace la busqueda de ventas totales del Semana
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

    $queryVtaSemana = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE (
                                fecha <= '$ultimoDia'
                                AND fecha >= '$primerDia' 
                                )
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQuerySemana = $getConnection->query($queryVtaSemana);
    $qVtaSemana = mysqli_fetch_array($resultQuerySemana);
    if($qVtaSemana === NULL){
      $totalVentaSemana = 0;
    } else {
      $totalVentaSemana = $qVtaSemana['total'];
    }
    $formatTotalVentaSemana = number_format($totalVentaSemana, 2, '.',',');

    //Se hace la busqueda de ventas totales del Mes
    $diaUltimo = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $diaUltimo, $year));
    $queryVtaMes = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE (
                                fecha <= '$ultimoDiaMes'
                                AND fecha >= '$primerDiaMes' 
                                )
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryMes = $getConnection->query($queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    if($qVtaMes === NULL){
      $totalVentaMes = 0;
    } else {
      $totalVentaMes = $qVtaMes['total'];
    }
    $formatTotalVentaMes = number_format($totalVentaMes, 2, '.',',');

    //Se hace la busqueda de ventas totales del Trimestre
    $queryVtaTrimestre = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE (
                                fecha <= '$ultimoDiaTrimestre'
                                AND fecha >= '$primerDiaTrimestre' 
                                )
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryTrimestre = $getConnection->query($queryVtaTrimestre);
    $qVtaTrimestre = mysqli_fetch_array($resultQueryTrimestre);
    if($qVtaTrimestre === NULL){
      $totalVentaTrimestre = 0;
    } else {
      $totalVentaTrimestre = $qVtaTrimestre['total'];
    }
    $formatTotalVentaTrimestre = number_format($totalVentaTrimestre, 2, '.',',');

    //Sacamos el mes en que estamos
    switch ($month) {
      case 1:
        $mes='Enero';
        $diasTotalMes = 31;
      break;
      case 2:
        $mes='Febrero';
        $diasTotalMes = 28;
      break;
      case 3:
        $mes='Marzo';
        $diasTotalMes = 31;
      break;
      case 4:
        $mes='Abril';
        $diasTotalMes = 30;
      break;
      case 5:
        $mes='Mayo';
        $diasTotalMes = 31;
      break;
      case 6:
        $mes='Junio';
        $diasTotalMes = 30;
      break;
      case 7:
        $mes='Julio';
        $diasTotalMes = 31;
      break;
      case 8:
        $mes='Agosto';
        $diasTotalMes = 31;
      break;
      case 9:
        $mes='Septiembre';
        $diasTotalMes = 30;
      break;
      case 10:
        $mes='Octubre';
        $diasTotalMes = 31;
      break;
      case 11:
        $mes='Noviembre';
        $diasTotalMes = 30;
      break;
      case 12:
        $mes='Diciembre';
        $diasTotalMes = 31;
      break;
    }

    //Reporte Mes Anterior
    $mtAnt = date('m')-1;
    if($mtAnt == 0){
      $yrAnt = date('Y')-1;
      $mtAnt = 12;
    } else {
      $yrAnt = date('Y');
    }

    if($diasTotalMes < 30){
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-28');
    } else if($diasTotalMes > 30){
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-31');
    } else {
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-30');
    }
    $queryVtaTotalDiaAnt = "SELECT (
                                      SUM((SELECT (SUBTOTAL2) FROM DUAL))
                                    ) AS total
                                  FROM doc
                                  WHERE fecha >= '".$fecAnteIni."'
                                    AND fecha <= '".$fecAnteFin."'
                                    AND tipo = 'F'
                                    AND totalpagado = total
                                    AND subtotal2 > 0
                                    AND FECCAN = 0";
    $resultQueryvtdAnt = $getConnection->query($queryVtaTotalDiaAnt);
    $qVtDAnt = mysqli_fetch_array($resultQueryvtdAnt);
    if($qVtDAnt === NULL){
      $vAnt = $qVtDAnt['total'];
    } else {
      $vAnt = $qVtDAnt['total'];
    }
    
    //Reporte Anual Actual
    $totalYearActual = 0;
    for($y = 1; $y <= 12; $y++){
      if($diasTotalMes < 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-31');
      } else {
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$y}."'
                                  AND fecha >= '".${'fecIniQ'.$y}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$y."
                              FROM doc
                              WHERE (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                              AND tipo = 'F'
                              AND totalpagado = total
                              AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mes'.$y} = 0;
      } else {
        ${'mes'.$y} = $mesEnc[0];
      }
      $totalYearActual += $mesEnc[0];
      echo            '<p id="anual'.$y.'" style="display:none;">'.${'mes'.$y}.'</p>';
    }

    //Reporte Anual Anterior
    $yearAnt = date('Y')-1;
    $totalYearAnterior = 0;
    for($y = 1; $y <= 12; $y++){
      if($diasTotalMes < 30){
        ${'fecIniQ'.$y} = date(''.$yearAnt.'-'.$y.'-01');
        ${'fecFinQ'.$y} = date(''.$yearAnt.'-'.$y.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$y} = date(''.$yearAnt.'-'.$y.'-01');
        ${'fecFinQ'.$y} = date(''.$yearAnt.'-'.$y.'-31');
      } else {
        ${'fecIniQ'.$y} = date(''.$yearAnt.'-'.$y.'-01');
        ${'fecFinQ'.$y} = date(''.$yearAnt.'-'.$y.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$y}."'
                                  AND fecha >= '".${'fecIniQ'.$y}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$y."
                              FROM doc
                              WHERE (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                              AND tipo = 'F'
                              AND totalpagado = total
                              AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mesAnte'.$y} = 0;
      } else {
        ${'mesAnte'.$y} = $mesEnc[0];
      }
      $totalYearAnterior += $mesEnc[0];
      echo            '<p id="anualAnte'.$y.'" style="display:none;">'.${'mesAnte'.$y}.'</p>';
    }

    //Reporte Anual Actual Empresa
    $totalYearEmpresa = 0;
    for($y = 1; $y <= 12; $y++){
      if($diasTotalMes < 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-31');
      } else {
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$y}."'
                                  AND fecha >= '".${'fecIniQ'.$y}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$y."
                              FROM doc
                              WHERE tipo = 'F'
                                AND totalpagado = total
                                AND (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                                AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mesEmp'.$y} = 0;
      } else {
        ${'mesEmp'.$y} = $mesEnc[0];
      }
      $totalYearEmpresa += $mesEnc[0];
      echo            '<p id="anualEmp'.$y.'" style="display:none;">'.${'mesEmp'.$y}.'</p>';
    }

    //Reporte Mejor Mes
    $vMejor = 0;
    $anioAnterior = date('Y')-1;
    for($mAnt = 1; $mAnt <= 12; $mAnt++){

      if($diasTotalMes < 30){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-31');
      } else {
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$mAnt}."'
                                  AND fecha >= '".${'fecIniQ'.$mAnt}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$mAnt."
                              FROM doc
                              WHERE (
                                      fecha <= '".${'fecFinQ'.$mAnt}."'
                                      AND fecha >= '".${'fecIniQ'.$mAnt}."'
                                    )
                              AND tipo = 'F'
                              AND totalpagado = total
                              AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mes'.$mAnt} = 0;
      } else {
        ${'mes'.$mAnt} = $mesEnc[0];
        if(${'mes'.$mAnt} > $vMejor) {
          $vMejor = ${'mes'.$mAnt};
          $mMes = $mAnt;
        }
      }
    }
    switch ($mMes) {
      case 1:
        $mesMej='Enero';
      break;
      case 2:
        $mesMej='Febrero';
      break;
      case 3:
        $mesMej='Marzo';
      break;
      case 4:
        $mesMej='Abril';
      break;
      case 5:
        $mesMej='Mayo';
      break;
      case 6:
        $mesMej='Junio';
      break;
      case 7:
        $mesMej='Julio';
      break;
      case 8:
        $mesMej='Agosto';
      break;
      case 9:
        $mesMej='Septiembre';
      break;
      case 10:
        $mesMej='Octubre';
      break;
      case 11:
        $mesMej='Noviembre';
      break;
      case 12:
        $mesMej='Diciembre';
      break;
    }

    $print =  '<div class="row" style="margin: 50px 0 0 0;display: flex;align-items: center;justify-content: center;flex-direction: column;">
                <div class="content-wrapper">
                  <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                    <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                    <img src="../img/img_pro/barrafmo2.gif" width="200"/>
                  </div>
                  <!-- Main content -->
                  <section class="content">
                    <!-- Info boxes -->
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-red"><i class="fas fa-stopwatch" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-6">
                                  <span class="info-box-text">VENTAS AL DIA</span>
                                  <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaDia.'</span>
                                </div>
                                <div class="col-md-6">
                                  <span class="info-box-text">PEDIDOS AL DIA</span>
                                  <span class="info-box-number" style="font-size: 1.7em !important;">'.$qPediDia.'</span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-aqua"><i class="fa fa-table" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS A LA SEMANA</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaSemana.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-yellow"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS AL MES</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaMes.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-fuchsia"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS AL TRIMESTRE</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaTrimestre.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="box">
                          <div class="box-header with-border">';
        $print .=               '<h3 class="box-title">Venta Mensual</h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <p class="text-center">';
        $print .=               '</p>
                                <div clas="row">
                                  <div class="col-md-12 col-sm-12">';
        $print .=                   '<h4 class="text-center">Venta Mensual de <b>'.$mes.'</b> del <b>'.date("Y").'</b></h4>
                                    <p id="diasTotalMes" style="display: none;">'.$diasTotalMes.'</p>
                                    <p id="mesActual" style="display: none;">'.$totalVentaMes.'</p>
                                    <p id="mesAnterior" style="display: none;">'.$vAnt.'</p>
                                    <p id="mejorMes" style="display: none;">'.$vMejor.'</p>
                                    <p id="mesMej" style="display: none;">'.$mesMej.'</p>
                                    <div class="chart">
                                      <canvas id="areaChart" style="height:250px;"></canvas>
                                    </div>
                                  </div>
                                  <script src="../intranet/bower_components/jquery/dist/jquery.min.js"></script>
                                  <script src="../intranet/dist/js/Chart.js"></script>
                                  <script src="../intranet/dist/js/graficas.js"></script>
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                          </div>
                          <!-- ./box-body -->
                          <div class="box-footer">
                            <div class="row">
                              <div class="col-sm-12 col-xs-12">';
          $print .=             '<div class="description-block">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <span class="description-percentage text-red" style="font-weight:bold;">
                                        Ventas del Mes pasado<br /><b style="font-size: 2em;">$ '.number_format($vAnt, 2, ".", ",").'</b>
                                      </span>
                                    </div>
                                    <div class="col-md-4">
                                      <span class="description-percentage text-blue" style="font-weight:bold;">
                                        Venta Actual<br /><b style="font-size: 2em;">$ '.number_format($totalVentaMes, 2, ".", ",").'</b>
                                      </span>
                                    </div>
                                    <div class="col-md-4">
                                      <span class="description-percentage text-green" style="font-weight:bold;">
                                        Ventas del Mejor Mes<br /><b style="font-size: 2em;">$ '.number_format($vMejor, 2, ".", ",").'</b>
                                      </span>
                                      <p class="text-green" style="font-weight:bold">'.$mesMej.' '.$anioAnterior.'</p>
                                    </div>
                                  </div>
                                  <div class="col-md-12 text-center">
                                    <h1 class="display-4">Proyección de Cierre</h1>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <p class="lead"><b>Ingrese los días del mes actual a calcular</b></p>
                                          </div>
                                          <div class="col-md-12">
                                            <input id="diasActual" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 25" required>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <p class="lead"><b>Ingrese los días del mes anterior a calcular</b></p>
                                          </div>
                                          <div class="col-md-12">
                                            <input id="diasAnterior" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 20" required>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <p class="lead"><b>Ingrese los días que han pasado (días hábiles)</b></p>
                                          </div>
                                          <div class="col-md-12">
                                            <input id="diasConteo" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 11" required>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div id="botonCalcular" class="col-md-12" style="margin: 10px 0 10px 0;">
                                      <input class="btn btn-success" type="submit" name="" value="Calcular" onClick="calcular();">
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6" id="ventaPorDiaActual"></div>
                                      <div class="col-md-6" id="ventaPorDiaAnterior"></div>
                                      <div class="col-md-6" id="pronosticoMensual"></div>
                                      <div class="col-md-6" id="proyeccionCierre"></div>
                                      <div class="col-md-12" id="ventaPorDiaIgualar"></div>
                                    </div>
                                    <script src="../js/calculos.js"></script>
                                  </div>';
        $print .=                 '<div class="col-md-12">
                                    <h5 class="text-center lead">Se presenta el reporte de ventas al mes, en comparación con el anterior y con el mejor mes.</h5>
                                  </div>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                          </div>
                          <!-- /.box-footer -->
                        </div>
                        <!-- /.box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->';
        $print .=   '<div class="row">
                      <div class="col-md-12">
                        <div class="box">
                          <div class="box-header with-border">
                              <h3 class="box-title">Reporte de Venta</h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <p class="text-center">';
        $print .=                   '</p>
                                <div clas="row">
                                  <div class="col-md-12 col-sm-12">';
        $print .=                   '<h4 class="text-center"><b>'.date("Y").' <em style="font-size: 0.5em;">Vs</em> '.$anioAnterior.' <em style="font-size: 0.5em;">Vs</em> Empresa</b></h4>
                                    <div class="chart">
                                      <canvas id="areaChartAnual" style="height:250px"></canvas>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                          </div>
                          <!-- ./box-body -->
                          <div class="box-footer">
                            <div class="row">
                              <div class="col-sm-12 col-xs-12">';
        $vtaAnualActual = date("Y");
        $print .=               '<div class="description-block">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <p class="lead" style="color:#3adcf4"><b style="font-size: 1em;">'.$anioAnterior.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearAnterior, 2, ".", ",").'</b></p>
                                    </div>
                                    <div class="col-md-6">
                                      <p class="lead" style="color:#7f0b0b"><b style="font-size: 1em;">'.$vtaAnualActual.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearEmpresa, 2, ".", ",").'</b></p>
                                    </div>
                                  </div>
                                  <h5 class="description-header">Se presenta el reporte de ventas anuales.</h5>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                          </div>
                          <!-- /.box-footer -->
                        </div>
                        <!-- /.box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->';
      // TODO hacer consultas por vendedor, por tipo de cliente y por tiempo de morosidad.
      $hoy = date('Y-m-d');

      $getMorosidad = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) < 0";
      $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
      $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
      $MorosidadF = $rowMorosidad[0]*(-1);
      $Morosidad = number_format($MorosidadF, 2, ".", ",");

      $get030DiasDist = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) >= -30
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) < 0
                    ORDER BY d.vence ASC";
      $resultGet030Dist = mysqli_query($getConnection,$get030DiasDist);
      $row030Dist = mysqli_fetch_array($resultGet030Dist);
      $dias030DistF = $row030Dist[0]*(-1);
      $dias030Dist = number_format($dias030DistF, 2, ".", ",");

      $get3060DiasDist = "SELECT
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) >= -60
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) < -30
                    ORDER BY d.vence ASC";
      $resultGet3060Dist = mysqli_query($getConnection,$get3060DiasDist);
      $row3060Dist = mysqli_fetch_array($resultGet3060Dist);
      $dias3060DistF = $row3060Dist[0]*(-1);
      $dias3060Dist = number_format($dias3060DistF, 2, ".", ",");

      $get6090DiasDist = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) >= -90
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) < -60
                    ORDER BY d.vence ASC";
      $resultGet6090Dist = mysqli_query($getConnection,$get6090DiasDist);
      $row6090Dist = mysqli_fetch_array($resultGet6090Dist);
      $dias6090DistF = $row6090Dist[0]*(-1);
      $dias6090Dist = number_format($dias6090DistF, 2, ".", ",");

      $get90DiasDist = "SELECT
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$hoy."')) < -90
                    ORDER BY d.vence ASC";
      $resultGet90Dist = mysqli_query($getConnection,$get90DiasDist);
      $row90Dist = mysqli_fetch_array($resultGet90Dist);
      $dias90DistF = $row90Dist[0]*(-1);
      $dias90Dist = number_format($dias90DistF, 2, ".", ",");

      $print .=     '<div class="row">
                      <div class="col-md-12">
                        <div class="box">
                          <div class="box-header with-border">
                              <h3 class="box-title">Cuentas por Cobrar</h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <div clas="row">
                                  <div class="col-md-12 col-sm-12">
                                    <h4 class="text-center">Cartera Vencida Total</h4>
                                    <p class="text-center" style="color:red; font-weight:bold;font-size: 2em;">$ '.$Morosidad.'</p>
                                    <div>
                                      <table class="table table-striped">
                                        <thead class="thead-inverse">
                                          <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">0 - 30 Días</th>
                                            <th class="text-center">31 - 60 Días</th>
                                            <th class="text-center">61 - 90 Días</th>
                                            <th class="text-center">+ 90 Días</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <th scope="row" class="text-center">Importe</th>';

      if($dias030Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias030Dist.' <a href="#" style="color: red;" onClick="showMorosidad(0, 1);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias030Dist.'</td>';
      }

      if($dias3060Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias3060Dist.' <a href="#" style="color: red;" onClick="showMorosidad(0, 2);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias3060Dist.'</td>';
      }

      if($dias6090Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias6090Dist.' <a href="#" style="color: red;" onClick="showMorosidad(0, 3);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias6090Dist.'</td>';
      }

      if($dias90Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias90Dist.' <a href="#" style="color: red;" onClick="showMorosidad(0, 4);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias90Dist.'</td>';
      }

      $print .=                           '</tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="box-footer">
                            <div class="row">
                              <div class="col-sm-12 col-xs-12">';
        $print .=               '<div class="description-block">
                                  <h5 class="description-header">Se presenta el reporte de cuentas por cobrar.</h5>
                                </div>
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                          </div>
                          <!-- /.box-footer -->
                        </div>
                      </div>
                    </div>';
      $print .=   '</section>
                  <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
              </div>'; // End div row
    echo $print;
    $getConnection->close();
  }

  private function getReporteVendedor($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $perid            = $paramDb->SecureInput($params["perID"]);
    $getConnection    = $paramDb->GetLink();
    /*$perid = $_SESSION["personal"];*/

    //Se define las fechas y trimestres
    $dia  = date("Y-m-d");
    $year=date('Y');
    $month=date('m');
    $day=date('d');

    if($month < 4){
      $primerDiaTrimestre = date('Y-01-01');
      $ultimoDiaTrimestre = date('Y-03-31');
    } elseif($month < 7){
      $primerDiaTrimestre = date('Y-04-01');
      $ultimoDiaTrimestre = date('Y-06-30');
    } elseif($month < 10){
      $primerDiaTrimestre = date('Y-07-01');
      $ultimoDiaTrimestre = date('Y-09-30');
    } elseif($month >9){
      $primerDiaTrimestre = date('Y-10-01');
      $ultimoDiaTrimestre = date('Y-12-31');
    }

    //Se obtiene los datos del vendedor
    $queryPer = "SELECT p.nombre, v.foto, v.tel, p.ingreso, p.sermov
                    FROM per p
                      JOIN vendedores v ON v.vendedorid = p.perid
                    WHERE perid = $perid";

    $resultadoPer = mysqli_query($getConnection,$queryPer);
    $filaPer = mysqli_fetch_array($resultadoPer);
    $nombre = $filaPer[0];
    $foto = $filaPer[1];
    $tel = $filaPer[2];
    $ingreso = $filaPer[3];
    $sermov = $filaPer[4];

    if($sermov == 1){
      $zona = 1;
    } elseif($sermov == 2) {
      $zona = 2;
    } else {
      $zona = 0;
    }

    //Se hace la busqueda de ventas totales del Dia
    $queryVtaDia = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND fecha = '$dia'
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDia = $getConnection->query($queryVtaDia);
    $qVtaDia = mysqli_fetch_array($resultQueryDia);
    if($qVtaDia === NULL){
      $totalVentaDia = 0;
    } else {
      $totalVentaDia = $qVtaDia['total'];
    }
    $formatTotalVentaDia = number_format($totalVentaDia, 2, '.',',');

    //Se hace la busqueda de ventas totales de la Semana
    # Obtenemos el día de la semana de la fecha dada
    $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
    # el 0 equivale al domingo...
    if($diaSemana==0)
      $diaSemana=7;
    # A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
    $primerDia=date("Y-m-d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
    # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
    $ultimoDia=date("Y-m-d",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
    $queryVtaSemana = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDia'
                                AND fecha >= '$primerDia' 
                                )
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQuerySemana = $getConnection->query($queryVtaSemana);
    $qVtaSemana = mysqli_fetch_array($resultQuerySemana);
    if($qVtaSemana === NULL){
      $totalVentaSemana = 0;
    } else {
      $totalVentaSemana = $qVtaSemana['total'];
    }
    $formatTotalVentaSemana = number_format($totalVentaSemana, 2, '.',',');

    //Se hace la busqueda de ventas totales del Mes
    $diaFinal = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $diaFinal, $year));
    $queryVtaMes = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDiaMes'
                                AND fecha >= '$primerDiaMes' 
                                )
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryMes = $getConnection->query($queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    if($qVtaMes === NULL){
      $totalVentaMes = 0;
    } else {
      $totalVentaMes = $qVtaMes['total'];
    }
    $formatTotalVentaMes = number_format($totalVentaMes, 2, '.',',');


    //Se hace la busqueda de ventas totales del Trimestre
    $queryVtaTrimestre = "SELECT (
                            SELECT CASE
                            WHEN TOTAL > 0
                            THEN SUM((SELECT (SUBTOTAL2) FROM DUAL))
                            END
                            ) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDiaTrimestre'
                                AND fecha >= '$primerDiaTrimestre' 
                                )
                            AND tipo = 'F'
                            AND totalpagado = total
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryTrimestre = $getConnection->query($queryVtaTrimestre);
    $qVtaTrimestre = mysqli_fetch_array($resultQueryTrimestre);
    if($qVtaTrimestre === NULL){
      $totalVentaTrimestre = 0;
    } else {
      $totalVentaTrimestre = $qVtaTrimestre['total'];
    }
    $formatTotalVentaTrimestre = number_format($totalVentaTrimestre, 2, '.',',');

    //Sacamos el mes en que estamos y los dias por mes
    switch ($month) {
      case 1:
        $mes='Enero';
        $diasTotalMes = 31;
      break;
      case 2:
        $mes='Febrero';
        $diasTotalMes = 28;
      break;
      case 3:
        $mes='Marzo';
        $diasTotalMes = 31;
      break;
      case 4:
        $mes='Abril';
        $diasTotalMes = 30;
      break;
      case 5:
        $mes='Mayo';
        $diasTotalMes = 31;
      break;
      case 6:
        $mes='Junio';
        $diasTotalMes = 30;
      break;
      case 7:
        $mes='Julio';
        $diasTotalMes = 31;
      break;
      case 8:
        $mes='Agosto';
        $diasTotalMes = 31;
      break;
      case 9:
        $mes='Septiembre';
        $diasTotalMes = 30;
      break;
      case 10:
        $mes='Octubre';
        $diasTotalMes = 31;
      break;
      case 11:
        $mes='Noviembre';
        $diasTotalMes = 30;
      break;
      case 12:
        $mes='Diciembre';
        $diasTotalMes = 31;
      break;
    }

    /*echo      '<div class="row" style="margin: 0 0 0 0;">
                <div class="content-wrapper">
                  <!-- Content Header (Page header) -->
                  <section class="content-header">
                    <div class="row">
                      <div class="col-md-12">';*/
      /*echo            "<p>Ventas Actuales</p>";*/
    
    $mtAnt = date('m')-1;
    if($mtAnt == 0){
      $yrAnt = date('Y')-1;
      $mtAnt = 12;
    } else {
      $yrAnt = date('Y');
    }
    if($diasTotalMes < 30){
      $fecActIni = date(''.$year.'-'.$month.'-01');
      $fecActFin = date(''.$year.'-'.$month.'-28');
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-28');
    } else if($diasTotalMes > 30){
      $fecActIni = date(''.$year.'-'.$month.'-01');
      $fecActFin = date(''.$year.'-'.$month.'-31');
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-31');
    } else {
      $fecActIni = date(''.$year.'-'.$month.'-01');
      $fecActFin = date(''.$year.'-'.$month.'-30');
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-30');
    }

    // Reporte Actual
    $queryVtaTotalDia = "SELECT (
                                  SUM((SELECT (SUBTOTAL2) FROM DUAL))
                                ) AS total
                                FROM doc
                                WHERE vendedorid = $perid
                                  AND fecha >= '".$fecActIni."'
                                  AND fecha <= '".$fecActFin."'
                                  AND tipo = 'F'
                                  AND totalpagado = total
                                  AND subtotal2 > 0
                                  AND FECCAN = 0";
    $resultQueryvtd = $getConnection->query($queryVtaTotalDia);
    $qVtD = mysqli_fetch_array($resultQueryvtd);
    if($qVtD === NULL){
      $vAct = 0;
    } else {
      $vAct = $qVtD['total'];
    }

    //Reporte Anterior
    /*echo            "<p>Ventas Anteriores</p>";*/
    $queryVtaTotalDiaAnt = "SELECT (
                                      SUM((SELECT (SUBTOTAL2) FROM DUAL))
                                    ) AS total
                                  FROM doc
                                  WHERE vendedorid = $perid
                                    AND fecha >= '".$fecAnteIni."'
                                    AND fecha <= '".$fecAnteFin."'
                                    AND tipo = 'F'
                                    AND totalpagado = total
                                    AND subtotal2 > 0
                                    AND FECCAN = 0";
    $resultQueryvtdAnt = $getConnection->query($queryVtaTotalDiaAnt);
    $qVtDAnt = mysqli_fetch_array($resultQueryvtdAnt);
    if($qVtDAnt === NULL){
      $vAnt = 0;
    } else {
      $vAnt = $qVtDAnt['total'];
    }

    //Reporte Anual
    /*echo            "<p>Ventas Anuales</p>";*/
    $totalYearActual = 0;
    for($y = 1; $y <= 12; $y++){
      if($diasTotalMes < 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-31');
      } else {
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$y}."'
                                  AND fecha >= '".${'fecIniQ'.$y}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$y."
                              FROM doc
                              WHERE vendedorid = $perid
                                AND (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                              AND tipo = 'F'
                              AND totalpagado = total
                              AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mes'.$y} = 0;
      } else {
        ${'mes'.$y} = $mesEnc[0];
      }
      $totalYearActual += $mesEnc[0];
      echo            '<p id="anual'.$y.'" style="display:none;">'.${'mes'.$y}.'</p>';
    }

    //Reporte Anual Anterior
    /*echo            "<p>Ventas Anuales Anterior</p>";*/
    $yearAnt = date('Y')-1;
    $totalYearAnterior = 0;
    for($y = 1; $y <= 12; $y++){
      if($diasTotalMes < 30){
        ${'fecIniQ'.$y} = date(''.$yearAnt.'-'.$y.'-01');
        ${'fecFinQ'.$y} = date(''.$yearAnt.'-'.$y.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$y} = date(''.$yearAnt.'-'.$y.'-01');
        ${'fecFinQ'.$y} = date(''.$yearAnt.'-'.$y.'-31');
      } else {
        ${'fecIniQ'.$y} = date(''.$yearAnt.'-'.$y.'-01');
        ${'fecFinQ'.$y} = date(''.$yearAnt.'-'.$y.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$y}."'
                                  AND fecha >= '".${'fecIniQ'.$y}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$y."
                              FROM doc
                              WHERE vendedorid = $perid
                                AND (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                              AND tipo = 'F'
                              AND totalpagado = total
                              AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mesAnte'.$y} = 0;
      } else {
        ${'mesAnte'.$y} = $mesEnc[0];
      }
      $totalYearAnterior += $mesEnc[0];
      echo            '<p id="anualAnte'.$y.'" style="display:none;">'.${'mesAnte'.$y}.'</p>';
    }

    //Reporte Anual Empresa
    /*echo            "<p>Ventas Anuales</p>";*/
    $totalYearEmpresa = 0;
    for($y = 1; $y <= 12; $y++){
      if($diasTotalMes < 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-31');
      } else {
        ${'fecIniQ'.$y} = date('Y-'.$y.'-01');
        ${'fecFinQ'.$y} = date('Y-'.$y.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$y}."'
                                  AND fecha >= '".${'fecIniQ'.$y}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$y."
                              FROM doc
                              WHERE tipo = 'F'
                                AND totalpagado = total
                                AND (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                                AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mesEmp'.$y} = 0;
      } else {
        ${'mesEmp'.$y} = $mesEnc[0];
      }
      $totalYearEmpresa += $mesEnc[0];
      echo            '<p id="anualEmp'.$y.'" style="display:none;">'.${'mesEmp'.$y}.'</p>';
    }

    //Reporte Mejor Mes
    /*echo            "<p>Ventas Anuales</p>";*/
    $vMejor = 0;
    $anioAnterior = date('Y')-1;
    for($mAnt = 1; $mAnt <= 12; $mAnt++){

      if($diasTotalMes < 30){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-31');
      } else {
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-30');
      }

      $buscandoAnual= "SELECT (
                              IF(
                                  fecha <= '".${'fecFinQ'.$mAnt}."'
                                  AND fecha >= '".${'fecIniQ'.$mAnt}."',
                                    SUM((SELECT (SUBTOTAL2) FROM DUAL)),
                                  0
                                )
                              ) AS dato".$mAnt."
                              FROM doc
                              WHERE vendedorid = $perid
                                AND (
                                      fecha <= '".${'fecFinQ'.$mAnt}."'
                                      AND fecha >= '".${'fecIniQ'.$mAnt}."'
                                    )
                              AND tipo = 'F'
                              AND totalpagado = total
                              AND FECCAN = 0";
      $buscandoMes = $getConnection->query($buscandoAnual);
      $mesEnc = mysqli_fetch_row($buscandoMes);

      if($mesEnc == 0){
        ${'mes'.$mAnt} = 0;
      } else {
        ${'mes'.$mAnt} = $mesEnc[0];
        if(${'mes'.$mAnt} > $vMejor) {
          $vMejor = ${'mes'.$mAnt};
          $mMes = $mAnt;
        }
      }
    }
    switch ($mMes) {
      case 1:
        $mesMej='Enero';
      break;
      case 2:
        $mesMej='Febrero';
      break;
      case 3:
        $mesMej='Marzo';
      break;
      case 4:
        $mesMej='Abril';
      break;
      case 5:
        $mesMej='Mayo';
      break;
      case 6:
        $mesMej='Junio';
      break;
      case 7:
        $mesMej='Julio';
      break;
      case 8:
        $mesMej='Agosto';
      break;
      case 9:
        $mesMej='Septiembre';
      break;
      case 10:
        $mesMej='Octubre';
      break;
      case 11:
        $mesMej='Noviembre';
      break;
      case 12:
        $mesMej='Diciembre';
      break;
    }

        /*echo          "</div>
                    </div>
                  </section>
                </div>
              </div>";*/

    $print =  '<div class="row" style="margin: 55px 0 0 0;">
                <div class="content-wrapper">
                  <!-- Content Header (Page header) -->
                  <section class="content-header">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                        <div class="col-sm-6 col-md-2 text-center">
                          <img src="../img/img_pro/vendedores/'.$foto.'" class="img-circle img-fluid" alt="'.$foto.'" width="130">
                        </div>
                        <div class="col-sm-6 col-md-8">
                          <h1 class="display-4">
                            <span>'.$nombre.'</span> 
                          </h1>
                          <h1>
                            <small>Tel.: '.$tel.'</small>
                          </h1>
                        </div>
                        <div class="col-sm-6 col-md-2 text-center">
                          <h3>
                            <small style="font-weight:bold;">Fecha de Ingreso<br />'.$ingreso.'</small>
                          </h3>
                          <h3>
                            <small style="font-weight:bold;">Zona: '.$zona.'</small>
                          </h3>
                        </div>
                      </div>
                    </div>
                  </section>
                  <!-- Main content -->
                  <section class="content">
                    <!-- Info boxes -->
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-red"><i class="fas fa-stopwatch" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-6">
                                  <span class="info-box-text">VENTAS AL DIA</span>
                                  <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaDia.'</span>
                                </div>
                                <div class="col-md-6">
                                  <span class="info-box-text">PEDIDOS AL DIA</span>
                                  <span class="info-box-number" style="font-size: 1.7em !important;">#</span>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-aqua"><i class="fa fa-table" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS A LA SEMANA</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaSemana.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-yellow"><i class="far fa-calendar-alt" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS AL MES</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaMes.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="info-box">
                          <span class="info-box-icon bg-fuchsia"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                          <div class="info-box-content">
                            <span class="info-box-text">VENTAS AL TRIMESTRE</span>
                            <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatTotalVentaTrimestre.'</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="box">
                          <div class="box-header with-border">';
        $print .=               '<h3 class="box-title">Venta Mensual</h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <p class="text-center">';
        $print .=               '</p>
                                <div clas="row">
                                  <div class="col-md-12 col-sm-12">';
        $print .=                   '<h4 class="text-center">Venta Mensual de <b>'.$mes.'</b> del <b>'.date("Y").'</b></h4>
                                    <p id="diasTotalMes" style="display: none;">'.$diasTotalMes.'</p>
                                    <p id="mesActual" style="display: none;">'.$totalVentaMes.'</p>
                                    <p id="mesAnterior" style="display: none;">'.$vAnt.'</p>
                                    <p id="mejorMes" style="display: none;">'.$vMejor.'</p>
                                    <p id="mesMej" style="display: none;">'.$mesMej.'</p>
                                    <div class="chart">
                                      <canvas id="areaChart" style="height:250px;"></canvas>
                                    </div>
                                  </div>
                                  <script src="../intranet/bower_components/jquery/dist/jquery.min.js"></script>
                                  <script src="../intranet/dist/js/Chart.js"></script>
                                  <script src="../intranet/dist/js/graficas.js"></script>
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                          </div>
                          <!-- ./box-body -->
                          <div class="box-footer">
                            <div class="row">
                              <div class="col-sm-12 col-xs-12">';
          $print .=             '<div class="description-block">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <span class="description-percentage text-red" style="font-weight:bold;">
                                        Ventas del Mes pasado<br /><b style="font-size: 2em;">$ '.number_format($vAnt, 2, ".", ",").'</b>
                                      </span>
                                    </div>
                                    <div class="col-md-4">
                                      <span class="description-percentage text-blue" style="font-weight:bold;">
                                        Venta Actual<br /><b style="font-size: 2em;">$ '.number_format($totalVentaMes, 2, ".", ",").'</b>
                                      </span>
                                    </div>
                                    <div class="col-md-4">
                                      <span class="description-percentage text-green" style="font-weight:bold;">
                                        Ventas del Mejor Mes<br /><b style="font-size: 2em;">$ '.number_format($vMejor, 2, ".", ",").'</b>
                                      </span>
                                      <p class="text-green" style="font-weight:bold">'.$mesMej.' '.$anioAnterior.'</p>
                                    </div>
                                  </div>
                                  <div class="col-md-12 text-center">
                                    <h1 class="display-4">Proyección de Cierre</h1>
                                    <div class="row">
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <p class="lead"><b>Ingrese los días del mes actual a calcular</b></p>
                                          </div>
                                          <div class="col-md-12">
                                            <input id="diasActual" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 25" required>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <p class="lead"><b>Ingrese los días del mes anterior a calcular</b></p>
                                          </div>
                                          <div class="col-md-12">
                                            <input id="diasAnterior" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 20" required>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="row">
                                          <div class="col-md-12">
                                            <p class="lead"><b>Ingrese los días que han pasado (días hábiles)</b></p>
                                          </div>
                                          <div class="col-md-12">
                                            <input id="diasConteo" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 11" required>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div id="botonCalcular" class="col-md-12" style="margin: 10px 0 10px 0;">
                                      <input class="btn btn-success" type="submit" name="" value="Calcular" onClick="calcular();">
                                    </div>
                                    <div class="row">
                                      <div class="col-md-6" id="ventaPorDiaActual"></div>
                                      <div class="col-md-6" id="ventaPorDiaAnterior"></div>
                                      <div class="col-md-6" id="pronosticoMensual"></div>
                                      <div class="col-md-6" id="proyeccionCierre"></div>
                                      <div class="col-md-12" id="ventaPorDiaIgualar"></div>
                                    </div>
                                    <script src="../js/calculos.js"></script>
                                  </div>';
        $print .=                 '<div class="col-md-12">
                                    <h5 class="text-center lead">Se presenta el reporte de ventas al mes, en comparación con el anterior y con el mejor mes.</h5>
                                  </div>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                          </div>
                          <!-- /.box-footer -->
                        </div>
                        <!-- /.box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->';
        $print .=   '<div class="row">
                      <div class="col-md-12">
                        <div class="box">
                          <div class="box-header with-border">
                              <h3 class="box-title">Reporte de Venta</h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <p class="text-center">';
        $print .=                   '</p>
                                <div clas="row">
                                  <div class="col-md-12 col-sm-12">';
        $print .=                   '<h4 class="text-center"><b>'.date("Y").' <em style="font-size: 0.5em;">Vs</em> '.$anioAnterior.' <em style="font-size: 0.5em;">Vs</em> Empresa</b></h4>
                                    <div class="chart">
                                      <canvas id="areaChartAnual" style="height:250px"></canvas>
                                    </div>
                                  </div>
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                          </div>
                          <!-- ./box-body -->
                          <div class="box-footer">
                            <div class="row">
                              <div class="col-sm-12 col-xs-12">';
        $vtaAnualActual = date("Y");
        $print .=               '<div class="description-block">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <p class="lead" style="color:#3adcf4"><b style="font-size: 1em;">'.$anioAnterior.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearAnterior, 2, ".", ",").'</b></p>
                                    </div>
                                    <div class="col-md-4">
                                      <p class="lead" style="color:#b109ab"><b style="font-size: 1em;">'.$vtaAnualActual.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearActual, 2, ".", ",").'</b></p>
                                    </div>
                                    <div class="col-md-4">
                                      <p class="lead" style="color:#7f0b0b"><b style="font-size: 1em;">Empresa '.$vtaAnualActual.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearEmpresa, 2, ".", ",").'</b></p>
                                    </div>
                                  </div>
                                  <h5 class="description-header">Se presenta el reporte de ventas anuales.</h5>
                                </div>
                                <!-- /.description-block -->
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                          </div>
                          <!-- /.box-footer -->
                        </div>
                        <!-- /.box -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->';
      // TODO hacer consultas por vendedor, por tipo de cliente y por tiempo de morosidad.
      $getMorosidad = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.vendedorid = $perid
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
      $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
      $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
      if($rowMorosidad === NULL){
        $MorosidadF = 0;
      } else {
        $MorosidadF = $rowMorosidad[0]*(-1);
      }
      $Morosidad = number_format($MorosidadF, 2, ".", ",");

      $get030DiasDist = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.vendedorid = $perid
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -30
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0
                    ORDER BY d.vence ASC";
      $resultGet030Dist = mysqli_query($getConnection,$get030DiasDist);
      $row030Dist = mysqli_fetch_array($resultGet030Dist);
      if($row030Dist === NULL){
        $dias030DistF = 0;
      } else {
        $dias030DistF = $row030Dist[0]*(-1);
      }
      $dias030Dist = number_format($dias030DistF, 2, ".", ",");

      $get3060DiasDist = "SELECT
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.vendedorid = $perid
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -60
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -30
                    ORDER BY d.vence ASC";
      $resultGet3060Dist = mysqli_query($getConnection,$get3060DiasDist);
      $row3060Dist = mysqli_fetch_array($resultGet3060Dist);
      if($row3060Dist){
        $dias3060DistF = 0;
      } else {
        $dias3060DistF = $row3060Dist[0]*(-1);
      }
      $dias3060Dist = number_format($dias3060DistF, 2, ".", ",");

      $get6090DiasDist = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.vendedorid = $perid
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -90
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -60
                    ORDER BY d.vence ASC";
      $resultGet6090Dist = mysqli_query($getConnection,$get6090DiasDist);
      $row6090Dist = mysqli_fetch_array($resultGet6090Dist);
      if($row6090Dist === NULL){
        $dias6090DistF = 0;
      } else {
        $dias6090DistF = $row6090Dist[0]*(-1);
      }
      $dias6090Dist = number_format($dias6090DistF, 2, ".", ",");

      $get90DiasDist = "SELECT 
                    SUM((SELECT (SELECT (d.totalpagado - d.total)) FROM DUAL)) as TotalDeuda
                    FROM doc d
                    WHERE d.total > d.totalpagado
                        AND d.vendedorid = $perid
                        AND d.tipo = 'F'
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -90
                    ORDER BY d.vence ASC";
      $resultGet90Dist = mysqli_query($getConnection,$get90DiasDist);
      $row90Dist = mysqli_fetch_array($resultGet90Dist);
      if($row90Dist){
        $dias90DistF = 0;
      } else {
        $dias90DistF = $row90Dist[1]*(-1);
      }
      $dias90Dist = number_format($dias90DistF, 2, ".", ",");

      $print .=     '<div class="row">
                      <div class="col-md-12">
                        <div class="box">
                          <div class="box-header with-border">
                              <h3 class="box-title">Cuentas por Cobrar</h3>
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="row">
                              <div class="col-md-12 col-sm-12">
                                <div clas="row">
                                  <div class="col-md-12 col-sm-12">
                                    <h4 class="text-center">Cartera Vencida Total</h4>
                                    <p class="text-center" style="color:red; font-weight:bold;font-size: 2em;">$ '.$Morosidad.'</p>
                                    <div>
                                      <table class="table table-striped">
                                        <thead class="thead-inverse">
                                          <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">0 - 30 Días</th>
                                            <th class="text-center">31 - 60 Días</th>
                                            <th class="text-center">61 - 90 Días</th>
                                            <th class="text-center">+ 90 Días</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <th scope="row" class="text-center">Importe</th>';

      if($dias030Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias030Dist.' <a href="#" style="color: red;" onClick="showMorosidad('.$perid.', 1);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias030Dist.'</td>';
      }

      if($dias3060Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias3060Dist.' <a href="#" style="color: red;" onClick="showMorosidad('.$perid.', 2);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias3060Dist.'</td>';
      }

      if($dias6090Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias6090Dist.' <a href="#" style="color: red;" onClick="showMorosidad('.$perid.', 3);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias6090Dist.'</td>';
      }

      if($dias90Dist > 0){
        $print .=                           '<td class="text-center" style="font-weight:bold;color:red;">$ '.$dias90Dist.' <a href="#" style="color: red;" onClick="showMorosidad('.$perid.', 4);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=                           '<td class="text-center">$ '.$dias90Dist.'</td>';
      }

      $print .=                           '</tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="box-footer">
                            <div class="row">
                              <div class="col-sm-12 col-xs-12">';
        $print .=               '<div class="description-block">
                                  <h5 class="description-header">Se presenta el reporte de cuentas por cobrar.</h5>
                                </div>
                              </div>
                              <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                          </div>
                          <!-- /.box-footer -->
                        </div>
                      </div>
                    </div>';
      $print .=   '</section>
                  <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->
              </div>'; // End div row
    echo $print;
    $getConnection->close();
  }

  private function ShowDetailMor($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $perid = $paramDb->SecureInput($params["perid"]);
    $tiempoMor = $paramDb->SecureInput($params["tiempoMor"]);
    $hoyMor = date("Y-m-d");
    // TODO buscar morosidad por el tipo de tiempo, vendedor y lista de cliente
    $getMor = "SELECT c.clienteid, c.numero, c.nombre, cfd.folio, (d.totalpagado - d.total) as total, (SELECT DATEDIFF(d.vence, '".$hoyMor."')) as dias
                    FROM doc d
                      JOIN cfd ON cfd.docid = d.docid
                      JOIN cli c ON c.clienteid = d.clienteid
                    WHERE d.total > d.totalpagado";
    if($perid > 0){
      $getMor .=      " AND d.vendedorid = $perid
                        AND cfd.tipdoc = 'F'";
    } else {
      $getMor .=      " AND cfd.tipdoc = 'F'";
    }
    // dependiendo del tiempo de morosidad (0-30 dias, etc) será el tipo de busqueda
    if($tiempoMor == 1){
      $periodo = "0-30 días";
      $getMor .= " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) >= -30
                        AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < 0
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } elseif($tiempoMor == 2){
      $periodo = "31-60 días";
      $getMor .= " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) >= -60
                        AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < -30
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } elseif($tiempoMor == 3){
      $periodo = "61-90 días";
      $getMor .= " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) >= -90
                              AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < -60
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } elseif($tiempoMor == 4){
      $periodo = "mayor de 90 días";
      $getMor .= " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < -90
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } else {
      $periodo = "Total";
      $getMor .= "
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    }
    /*$result = mysqli_query($getConnection, $getMor);*/
    $getMorosidadEx = $paramDb->Query($getMor);
    try {
      /*$numeroR = mysqli_num_rows($result);
      $filasM = mysqli_fetch_row($result);*/
      /*$morososN = $result->num_rows;*/
      /*$morososF = ;*/
      $numRow = $paramDb->NumRows();
      $rows = $paramDb->Rows();

      if($numRow > 0) {
        $total = 0;
        $position = 0;

        // TODO make validation for user: registrado, publico and add column for get price
        $headers = ["#", "NUMERO", "CLIENTE", "FACTURA", "DIAS VENCIDOS", "IMPORTE"];
        $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

        echo "<div style='margin: 55px 0 0 0;'>";
        echo  '<div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                <img src="../img/img_pro/barrafmo2.gif" width="200"/>
              </div>';
        echo  "<div class='panel panel-default' style='margin:40px 15px 10px 250px;'>";
        echo    "<div class='panel-heading'>";

        // TODO hacer boton de regresar para el vendedor
        if($perid > 0){
          $linkFunctionPersonal = "showPersonal(".$perid.")";
          echo      "<h4>
                      <a href='#' onClick='".$linkFunctionPersonal."'>
                        <i class='fas fa-arrow-alt-circle-left fa-lg' aria-hidden='true'></i>
                      </a> Clientes Morosos en <b>".$periodo."</b> a la fecha de <b>".$hoyMor."</b></h4>";
        } else {
          $linkFunctionPersonal = 'showInformation("dashBoardDireccion")';
          echo      "<h4><a href='#' onClick='".$linkFunctionPersonal."'><i class='fas fa-arrow-alt-circle-left fa-lg' aria-hidden='true'></i></a> Clientes Morosos en <b>".$periodo."</b> a la fecha de <b>".$hoyMor."</b></h4>";
        }
        echo    "</div>";
        echo    "<div class='panel-body'>";
        $print = $paramFunctions->drawTableHeader($headers, $classPerColumn);
        $i = 0;
        $suma = 0;

        foreach ($rows as $row) {
          $clienteid = $row["clienteid"];
          $numero = $row["numero"];
          $cliente = $row["nombre"];
          $folio = $row["folio"];
          $importe = $row["total"];
          $diasVencidos = $row["dias"];
          $i++;
          $suma += $importe;
          // set format
          $formatoImporte = number_format($importe, 2, '.', ',');
          $print .= "<tr>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$i</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$numero</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$cliente</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$folio</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$diasVencidos</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;color:red;'>MX$ $formatoImporte</td>";
          $position++;
        }
        $print .= "</tr>
                  <tr>
                    <th colspan='5' scope='row' style='font-size:2em;text-align:right;'>TOTAL</th>
                    <td colspan='6' class='text-center' style='font-size:2em;font-weight:bold;color:red;'>MX$ ".number_format($suma, 2, '.', ',')."</td>
                  </tr>";
        echo $print;
      } else {
        echo '<div class="content-wrapper">';
        echo  "<h4>Sin morosidad.</h4>";
        echo '</div>';
      }
    } catch (Exception $e){
      echo '<div class="content-wrapper">';
        echo  "<h4>Hay un problema</h4>";
        echo '</div>';
    }
    $getConnection->close();
  }

  private function getDashBoardAdmin($params) {
  $paramDb = new Database();
  $paramFunctions = new Util();
  $getConnection = $paramDb->GetLink();

  if(isset($_SESSION["data"])) {
    $session = $_SESSION["data"];
  }

  $rol = $paramDb->SecureInput($session["rol"]);
  $clienteID = $paramDb->SecureInput($session["username"]);
  $id = $_SESSION["data"]["id"];
  //$id = 4;
  $username = $_SESSION["data"]["name"];
  $rfc = $_SESSION["data"]["rfc"];
  $saldo = $_SESSION["data"]["saldo"];
  $formatoSaldo = number_format($saldo, 2, '.',',');
  $limite = $_SESSION["data"]["limite"];
  $formatoLimite = number_format($limite, 2, '.',',');
  $dispo = $limite - $saldo;
  $dispo = number_format($dispo, 2, '.',',');
  $disponible = $limite - $saldo;
  $vendedor = $_SESSION["data"]["vendedor"];
  $vendedorID = $_SESSION["data"]["vendedorid"];
  $diascredito = $_SESSION["data"]["diacredito"];
  $diasvisita = $_SESSION["data"]["diavis"];
  $ultimacompra = $_SESSION["data"]["ucompra"];
  $compraReciente = $_SESSION["data"]["compraReciente"];
  $pas2 = $_SESSION["data"]["pas2"];
  $pasAnt = $_SESSION["data"]["pasAnt"];
  $correo = $_SESSION["data"]["correo"];
  $arrayBooleans = array("bManagementOrder" => false);

  switch ($diasvisita) {
    case 'D':
      $dia = 'Domingo';
      break;
    case 'L':
      $dia = 'Lunes';
      break;
    case 'M':
      $dia = 'Martes';
      break;
    case 'I':
      $dia = 'Miércoles';
      break;
    case 'J':
      $dia = 'Jueves';
      break;
    case 'V':
      $dia = 'Viernes';
      break;
    case 'S':
      $dia = 'Sábado';
      break;
    default:
      $dia = 'Sin Visita';
      break;
  }

  $date=new DateTime();
  $fechaActualDia = $date->format('d');
  $fechaActualMes = $date->format('m');

  $UltimaComp = strtotime($ultimacompra);
  $diasUltimaCompra = idate('d' ,$UltimaComp);
  $mesUltimaCOmpra = idate('m',$UltimaComp);

  switch ($mesUltimaCOmpra) {
    case '1':
      $diasTotalMesUltimaCompra = 31;
      break;

    case '2':
      $diasTotalMesUltimaCompra = 28;
      break;

    case '3':
      $diasTotalMesUltimaCompra = 31;
      break;

    case '4':
      $diasTotalMesUltimaCompra = 30;
      break;

    case '5':
      $diasTotalMesUltimaCompra = 31;
      break;

    case '6':
      $diasTotalMesUltimaCompra = 30;
      break;

    case '7':
      $diasTotalMesUltimaCompra = 31;
      break;

    case '8':
      $diasTotalMesUltimaCompra = 31;
      break;

    case '9':
      $diasTotalMesUltimaCompra = 30;
      break;

    case '10':
      $diasTotalMesUltimaCompra = 31;
      break;

    case '11':
      $diasTotalMesUltimaCompra = 30;
      break;

    case '12':
      $diasTotalMesUltimaCompra = 31;
      break;
  }

  $sumaDeDias = $diasUltimaCompra + $diascredito;

  if($ultimacompra > 0){
    if($fechaActualMes == $mesUltimaCOmpra){
      if($sumaDeDias >= $diasTotalMesUltimaCompra){
        $diasRestantesNewMes = $sumaDeDias - $diasTotalMesUltimaCompra;
        $nextMes = $mesUltimaCOmpra + 1;
        $fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");
      } else {
        $fechaLimite = date("Y-$mesUltimaCOmpra-$sumaDeDias");//39
        $diasRestantesNewMes = $sumaDeDias;
      }
    } elseif($sumaDeDias >= $diasTotalMesUltimaCompra){
      $diasRestantesNewMes = $diasTotalMesUltimaCompra - $sumaDeDias;
      if($diasRestantesNewMes > 0){
        $nextMes = $mesUltimaCOmpra + 1;
        $fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");//39
      } else {
        $fechaLimite = 'Factura(s) Vencida';
      }
    } else {
      $diasRestantesNewMes = ($diasTotalMesUltimaCompra - $sumaDeDias) - $fechaActualDia;
      $fechaLimite = 'Factura(s) Vencida';
      $nextMes = $mesUltimaCOmpra;
    }
  } else {
    $diasRestantesNewMes = 0;
    $fechaLimite = 'Sin Vencimiento';
    $nextMes = 0;
  }

  if($pas2==''){
    echo  '<div id="myLargeModalLabel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header text-center">
                <p class="lead">Bienvenido a su Escritorio Virtual FMO, este es su primer inicio de sesión y le invitamos a realizar el cambio de su contraseña; si no lo realiza, no podrá accesar nuevamente a su escritorio hasta que termine el mes, <b>ya que su contraseña se resetea cada inicio de mes.</b></p>
              </div>
              <div class="modal-body" style="display:flex; align-items: center; justify-content: center;">
                <form>
                  <div class="form-group" style="text-align: center;">
                    <input style="width:300px;" class="form-control text-center" id="usuario" name="usuario" value="'.$clienteID.'" type="text" readonly="readonly">
                  </div>
                  <div class="form-group" style="text-align: center;display:;">
                    <input style="width:300px;" class="form-control text-center" id="email" name="email" value="'.$correo.'" type="email" readonly="readonly">
                  </div>
                  <div class="form-group">
                    <input autofocus style="width:300px;" class="form-control text-center" id="passwordNew" name="password" onChange="verificarPassword()" placeholder="Nuevo Password" type="text" autocomplete="off" required>
                    <p id="pasAnt" style="display:none;">'.$pasAnt.'</p>
                  </div>
                  <script>
                    function verificarPassword(){
                      var pasAnt  = document.getElementById("pasAnt").innerHTML;
                      var pasNew  = document.getElementById("passwordNew").value;
                      var usuario = document.getElementById("usuario").value;
                      var email   = document.getElementById("email").value;
                      console.log(pasAnt, pasNew, usuario, email);
                      if(pasAnt === pasNew){
                        alert("El nuevo password debe ser diferente.");
                      } else {
                        $.post("../php/classes/cambiarpass.php", {usuario: usuario, password: pasNew, emial: email});
                        alert("Su contraseña se cambio correctamente, debe de inciar sesión con los nuevos datos. Gracias!.");
                        Session.Clear();
                        Session.Abandon();
                      }
                    }
                  </script>
                  <div id="mensajePas"></div>
                  <div class="form-group text-center" id="botonEnviar" style="display: none;">
                    <button class="btn btn-danger pull-center" type="submit">Enviar</button>
                  </div>
                </form>
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
              </div>
            </div>
          </div>
        </div>
        <script>$(document).ready(function(){$("#myLargeModalLabel").modal("show");});</script>';
  }

  $print =  '<div class="row" style="margin: 55px 0 0 0;">
          <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
            <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
            <img src="../img/img_pro/barrafmo2.gif" width="200"/>
          </div>
          <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                <span style="font-weight:bold;">#'.$clienteID.'</span> - '.$username.'
                <small>'.$rol.'</small>
              </h1>
            </section>
            <!-- Main content -->
            <section class="content">
              <!-- Info boxes -->
              <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-usd fa-lg" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Saldo en la Cuenta</span>
                      <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatoSaldo.'</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Saldo Disponible</span>
                      <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$dispo.'</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Limite de Crédito</span>
                      <span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatoLimite.'</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
              </div>
              <!-- /.row -->

              <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Días de Crédito</span>
                      <span class="info-box-number" style="font-size: 1.7em !important;">'.$diascredito.'</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-fuchsia"><i class="fa fa-linode" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Compra Reciente</span>
                      <span class="info-box-number" style="font-size: 1.7em !important;">'.$compraReciente.'</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-maroon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Próximo Vencimiento</span>
                      <span class="info-box-number" style="font-size: 1.7em !important;">'.$fechaLimite.'</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <div class="row">
                <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">';

  $trim1 = "<strong>Compras: 01 Enero, 2017 - 31 Marzo, ".date("Y")."</strong>";
  $trim2 = "<strong>Compras: 01 Abril, 2017 - 30 Junio, ".date("Y")."</strong>";
  $trim3 = "<strong>Compras: 01 Julio, 2017 - 30 Septiembre, ".date("Y")."</strong>";
  $trim4 = "<strong>Compras: 01 Octubre, 2017 - 31 Diciembre, ".date("Y")."</strong>";

  $newMesActual = new DateTime();
  $MesActual = $newMesActual->format('m');

  if($MesActual < 4){
    $periodo    = '1er. Periodo';
  } elseif($MesActual < 7){
    $periodo    = '2do. Periodo';
  } elseif($MesActual < 10){
    $periodo    = '3er. Periodo';
  } elseif($MesActual > 9){
    $periodo    = '4to. Periodo';
  }

  $print .=               '<h3 class="box-title">'.$periodo.' Trimestral del '.date("Y").'</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <p class="text-center">';
  if($MesActual < 4){
    $print .=                   $trim1;
    $fechaInicio  = date("Y-01-01");
    $fechaFinal   = date("Y-03-31");

  } elseif($MesActual < 7){
    $print .=                   $trim2;
    $fechaInicio  = date("Y-04-01");
    $fechaFinal   = date("Y-06-30");
  } elseif($MesActual < 10){
    $print .=                   $trim3;
    $fechaInicio  = date("Y-07-01");
    $fechaFinal   = date("Y-09-30");
    //$periodo    = '3er. Periodo';
  } elseif($MesActual > 9){
    $print .=                   $trim4;
    $fechaInicio  = date("Y-10-01");
    $fechaFinal   = date("Y-12-31");
  }

  $print .=                   '</p>
                          <!-- DONUT CHART -->
                          <div clas="row">
                            <div class="col-md-4 col-sm-12">';

  $getGraphCompras = "SELECT d.docid, de.desartid, i.clvprov, de.desventa, de.descantidad, d.feccap
      FROM doc d
        JOIN des de ON de.desdocid = d.docid
        JOIN inv i ON i.articuloid = de.desartid
      where d.clienteid = $id
        AND de.desartid > 14
                AND d.subtotal2 > 0
        AND de.descantidad > 0
        AND d.feccap >= '$fechaInicio'
        AND d.feccap <= '$fechaFinal'
                AND i.clvprov NOT LIKE '8%'
        AND i.clvprov NOT LIKE '6%'
      ORDER BY d.feccap ASC";

    $query_execute = $getConnection->query($getGraphCompras);
    $numeroArt = $query_execute->num_rows;
    $total = 0;

  while($row = $query_execute->fetch_array()){
    $importe = $row["desventa"];
    $cantidad = $row["descantidad"];

    $total += $importe * $cantidad;
  }

  $fecInicio = date("Y-01-01");
  $fecActual = date("Y-m-d");

  $getGraphVencido = "SELECT docid, feccap, feccan, vence, total, totalpagado
              FROM doc
              WHERE clienteid = $id
                AND totalpagado < total
                AND feccan = 0
                AND tipo NOT LIKE 'C'
                AND vence < '$fecActual'
              ORDER BY feccap ASC";

  $numVec = mysqli_query($getConnection, $getGraphVencido);
  $numeroVeces = $numVec->num_rows;

  switch ($MesActual) {
    case '1':
      $diasTotalMes = 31;
      break;

    case '2':
      $diasTotalMes = 28;
      break;

    case '3':
      $diasTotalMes = 31;
      break;

    case '4':
      $diasTotalMes = 30;
      break;

    case '5':
      $diasTotalMes = 31;
      break;

    case '6':
      $diasTotalMes = 30;
      break;

    case '7':
      $diasTotalMes = 31;
      break;

    case '8':
      $diasTotalMes = 31;
      break;

    case '9':
      $diasTotalMes = 30;
      break;

    case '10':
      $diasTotalMes = 31;
      break;

    case '11':
      $diasTotalMes = 30;
      break;

    case '12':
      $diasTotalMes = 31;
      break;
  }

  $weekNum = date("W") - date("W", strtotime(date("Y-m-01"))) + 1;

  $year = date("Y");
  $month = $MesActual;

  # Obtenemos el ultimo dia del mes
    $ultimoDiaMes=date("t",mktime(0,0,0,$month,1,$year));
 
    # Obtenemos la semana del primer dia del mes
    $primeraSemana=date("W",mktime(0,0,0,$month,1,$year));
 
    # Obtenemos la semana del ultimo dia del mes
    $ultimaSemana=date("W",mktime(0,0,0,$month,$ultimoDiaMes,$year));

  if($MesActual < 4){
    if($ultimaSemana < 6){
      $semanas = $ultimaSemana - $primeraSemana;
    }
  } elseif($MesActual < 7){
    $semanas = $ultimaSemana - $primeraSemana;
  } elseif($MesActual < 10){
    $semanas = $ultimaSemana - $primeraSemana;
  } elseif($MesActual > 9){
    $semanas = $ultimaSemana - $primeraSemana;

    //Semanas del Mes
    if($MesActual < 4){
      $semana1 = "feccap >= '$year-01-01' AND feccap <= '$year-01-07' ";
      $semana2 = "feccap >= '$year-01-08' AND feccap <= '$year-01-14' ";
      $semana3 = "feccap >= '$year-01-15' AND feccap <= '$year-01-21' ";
      $semana4 = "feccap >= '$year-01-21' AND feccap <= '$year-01-28' ";
      $semana5 = "feccap >= '$year-01-29' AND feccap <= '$year-01-31' ";
      $semana6 = "feccap >= '$year-02-01' AND feccap <= '$year-02-07' ";
      $semana7 = "feccap >= '$year-02-08' AND feccap <= '$year-02-14' ";
      $semana8 = "feccap >= '$year-02-15' AND feccap <= '$year-02-21' ";
      $semana9 = "feccap >= '$year-02-21' AND feccap <= '$year-02-28' ";
      $semana10 = "feccap >= '$year-02-29' AND feccap <= '$year-02-30' ";
      $semana11 = "feccap >= '$year-03-01' AND feccap <= '$year-03-07' ";
      $semana12 = "feccap >= '$year-03-08' AND feccap <= '$year-03-14' ";
      $semana13 = "feccap >= '$year-03-15' AND feccap <= '$year-03-21' ";
      $semana14 = "feccap >= '$year-03-21' AND feccap <= '$year-03-28' ";
      $semana15 = "feccap >= '$year-03-29' AND feccap <= '$year-03-31' ";

      //Semana 1 Mes 1
      $getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana1
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasA = $getComprasSemanalA->num_rows;

      if($numComprasA > 0){
        $numComprasMes1Sem1 = $numComprasA;
      } else {
        $numComprasMes1Sem1 = 0;
      }

      //Semana 2 Mes 1
      $getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana2
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasB = $getComprasSemanalB->num_rows;

      if($numComprasB > 0){
        $numComprasMes1Sem2 = $numComprasB;
      } else {
        $numComprasMes1Sem2 = 0;
      }

      //Semana 3 Mes 1
      $getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana3
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasC = $getComprasSemanalC->num_rows;

      if($numComprasC > 0){
        $numComprasMes1Sem3 = $numComprasC;
      } else {
        $numComprasMes1Sem3 = 0;
      }

      //Semana 4 Mes 1
      $getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana4
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasD = $getComprasSemanalD->num_rows;

      if($numComprasD > 0){
        $numComprasMes1Sem4 = $numComprasD;
      } else {
        $numComprasMes1Sem4 = 0;
      }

      //Semana 1 Mes 2
      $getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana6
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasE = $getComprasSemanalE->num_rows;

      if($numComprasE > 0){
        $numComprasMes2Sem1 = $numComprasE;
      } else {
        $numComprasMes2Sem1 = 0;
      }

      //Semana 2 Mes 2
      $getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana7
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasF = $getComprasSemanalF->num_rows;

      if($numComprasF > 0){
        $numComprasMes2Sem2 = $numComprasF;
      } else {
        $numComprasMes2Sem2 = 0;
      }

      //Semana 3 Mes 2
      $getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana8
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasG = $getComprasSemanalG->num_rows;

      if($numComprasG > 0){
        $numComprasMes2Sem3 = $numComprasG;
      } else {
        $numComprasMes2Sem3 = 0;
      }

      //Semana 4 Mes 2
      $getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana9
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasH = $getComprasSemanalH->num_rows;

      if($numComprasH > 0){
        $numComprasMes2Sem4 = $numComprasH;
      } else {
        $numComprasMes2Sem4 = 0;
      }

      //Semana 1 Mes 3
      $getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana11
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasI = $getComprasSemanalI->num_rows;

      if($numComprasI > 0){
        $numComprasMes3Sem1 = $numComprasI;
      } else {
        $numComprasMes3Sem1 = 0;
      }

      //Semana 2 Mes 3
      $getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana12
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasJ = $getComprasSemanalJ->num_rows;

      if($numComprasJ > 0){
        $numComprasMes3Sem2 = $numComprasJ;
      } else {
        $numComprasMes3Sem2 = 0;
      }

      //Semana 3 Mes 3
      $getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana13
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasK = $getComprasSemanalK->num_rows;

      if($numComprasK > 0){
        $numComprasMes3Sem3 = $numComprasK;
      } else {
        $numComprasMes3Sem3 = 0;
      }

      //Semana 4 Mes 3
      $getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana14
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasL = $getComprasSemanalL->num_rows;

      if($numComprasL > 0){
        $numComprasMes3Sem4 = $numComprasL;
      } else {
        $numComprasMes3Sem4 = 0;
      }
    } elseif($MesActual < 7){
      $semana1 = "feccap >= '$year-04-01' AND feccap <= '$year-04-07' ";
      $semana2 = "feccap >= '$year-04-08' AND feccap <= '$year-04-14' ";
      $semana3 = "feccap >= '$year-04-15' AND feccap <= '$year-04-21' ";
      $semana4 = "feccap >= '$year-04-21' AND feccap <= '$year-04-28' ";
      $semana5 = "feccap >= '$year-04-29' AND feccap <= '$year-04-30' ";
      $semana6 = "feccap >= '$year-05-01' AND feccap <= '$year-05-07' ";
      $semana7 = "feccap >= '$year-05-08' AND feccap <= '$year-05-14' ";
      $semana8 = "feccap >= '$year-05-15' AND feccap <= '$year-05-21' ";
      $semana9 = "feccap >= '$year-05-21' AND feccap <= '$year-05-28' ";
      $semana10 = "feccap >= '$year-05-29' AND feccap <= '$year-05-31' ";
      $semana11 = "feccap >= '$year-06-01' AND feccap <= '$year-06-07' ";
      $semana12 = "feccap >= '$year-06-08' AND feccap <= '$year-06-14' ";
      $semana13 = "feccap >= '$year-06-15' AND feccap <= '$year-06-21' ";
      $semana14 = "feccap >= '$year-06-21' AND feccap <= '$year-06-28' ";
      $semana15 = "feccap >= '$year-06-29' AND feccap <= '$year-06-30' ";

      //Semana 1 Mes 1
      $getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana1
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasA = $getComprasSemanalA->num_rows;

      if($numComprasA > 0){
        $numComprasMes1Sem1 = $numComprasA;
      } else {
        $numComprasMes1Sem1 = 0;
      }

      //Semana 2 Mes 1
      $getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana2
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasB = $getComprasSemanalB->num_rows;

      if($numComprasB > 0){
        $numComprasMes1Sem2 = $numComprasB;
      } else {
        $numComprasMes1Sem2 = 0;
      }

      //Semana 3 Mes 1
      $getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana3
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasC = $getComprasSemanalC->num_rows;

      if($numComprasC > 0){
        $numComprasMes1Sem3 = $numComprasC;
      } else {
        $numComprasMes1Sem3 = 0;
      }

      //Semana 4 Mes 1
      $getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana4
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasD = $getComprasSemanalD->num_rows;

      if($numComprasD > 0){
        $numComprasMes1Sem4 = $numComprasD;
      } else {
        $numComprasMes1Sem4 = 0;
      }

      //Semana 1 Mes 2
      $getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana6
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasE = $getComprasSemanalE->num_rows;

      if($numComprasE > 0){
        $numComprasMes2Sem1 = $numComprasE;
      } else {
        $numComprasMes2Sem1 = 0;
      }

      //Semana 2 Mes 2
      $getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana7
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasF = $getComprasSemanalF->num_rows;

      if($numComprasF > 0){
        $numComprasMes2Sem2 = $numComprasF;
      } else {
        $numComprasMes2Sem2 = 0;
      }

      //Semana 3 Mes 2
      $getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana8
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasG = $getComprasSemanalG->num_rows;

      if($numComprasG > 0){
        $numComprasMes2Sem3 = $numComprasG;
      } else {
        $numComprasMes2Sem3 = 0;
      }

      //Semana 4 Mes 2
      $getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana9
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasH = $getComprasSemanalH->num_rows;

      if($numComprasH > 0){
        $numComprasMes2Sem4 = $numComprasH;
      } else {
        $numComprasMes2Sem4 = 0;
      }

      //Semana 1 Mes 3
      $getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana11
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasI = $getComprasSemanalI->num_rows;

      if($numComprasI > 0){
        $numComprasMes3Sem1 = $numComprasI;
      } else {
        $numComprasMes3Sem1 = 0;
      }

      //Semana 2 Mes 3
      $getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana12
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasJ = $getComprasSemanalJ->num_rows;

      if($numComprasJ > 0){
        $numComprasMes3Sem2 = $numComprasJ;
      } else {
        $numComprasMes3Sem2 = 0;
      }

      //Semana 3 Mes 3
      $getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana13
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasK = $getComprasSemanalK->num_rows;

      if($numComprasK > 0){
        $numComprasMes3Sem3 = $numComprasK;
      } else {
        $numComprasMes3Sem3 = 0;
      }

      //Semana 4 Mes 3
      $getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana14
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasL = $getComprasSemanalL->num_rows;

      if($numComprasL > 0){
        $numComprasMes3Sem4 = $numComprasL;
      } else {
        $numComprasMes3Sem4 = 0;
      }
    } elseif($MesActual < 10){
      $semana1 = "feccap >= '$year-07-01' AND feccap <= '$year-07-07' ";
      $semana2 = "feccap >= '$year-07-08' AND feccap <= '$year-07-14' ";
      $semana3 = "feccap >= '$year-07-15' AND feccap <= '$year-07-21' ";
      $semana4 = "feccap >= '$year-07-21' AND feccap <= '$year-07-28' ";
      $semana5 = "feccap >= '$year-07-29' AND feccap <= '$year-07-31' ";
      $semana6 = "feccap >= '$year-08-01' AND feccap <= '$year-08-07' ";
      $semana7 = "feccap >= '$year-08-08' AND feccap <= '$year-08-14' ";
      $semana8 = "feccap >= '$year-08-15' AND feccap <= '$year-08-21' ";
      $semana9 = "feccap >= '$year-08-21' AND feccap <= '$year-08-28' ";
      $semana10 = "feccap >= '$year-08-29' AND feccap <= '$year-08-31' ";
      $semana11 = "feccap >= '$year-09-01' AND feccap <= '$year-09-07' ";
      $semana12 = "feccap >= '$year-09-08' AND feccap <= '$year-09-14' ";
      $semana13 = "feccap >= '$year-09-15' AND feccap <= '$year-09-21' ";
      $semana14 = "feccap >= '$year-09-21' AND feccap <= '$year-09-28' ";
      $semana15 = "feccap >= '$year-09-29' AND feccap <= '$year-09-30' ";

      //Semana 1 Mes 1
      $getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana1
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasA = $getComprasSemanalA->num_rows;

      if($numComprasA > 0){
        $numComprasMes1Sem1 = $numComprasA;
      } else {
        $numComprasMes1Sem1 = 0;
      }

      //Semana 2 Mes 1
      $getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana2
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasB = $getComprasSemanalB->num_rows;

      if($numComprasB > 0){
        $numComprasMes1Sem2 = $numComprasB;
      } else {
        $numComprasMes1Sem2 = 0;
      }

      //Semana 3 Mes 1
      $getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana3
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasC = $getComprasSemanalC->num_rows;

      if($numComprasC > 0){
        $numComprasMes1Sem3 = $numComprasC;
      } else {
        $numComprasMes1Sem3 = 0;
      }

      //Semana 4 Mes 1
      $getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana4
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasD = $getComprasSemanalD->num_rows;

      if($numComprasD > 0){
        $numComprasMes1Sem4 = $numComprasD;
      } else {
        $numComprasMes1Sem4 = 0;
      }

      //Semana 1 Mes 2
      $getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana6
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasE = $getComprasSemanalE->num_rows;

      if($numComprasE > 0){
        $numComprasMes2Sem1 = $numComprasE;
      } else {
        $numComprasMes2Sem1 = 0;
      }

      //Semana 2 Mes 2
      $getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana7
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasF = $getComprasSemanalF->num_rows;

      if($numComprasF > 0){
        $numComprasMes2Sem2 = $numComprasF;
      } else {
        $numComprasMes2Sem2 = 0;
      }

      //Semana 3 Mes 2
      $getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana8
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasG = $getComprasSemanalG->num_rows;

      if($numComprasG > 0){
        $numComprasMes2Sem3 = $numComprasG;
      } else {
        $numComprasMes2Sem3 = 0;
      }

      //Semana 4 Mes 2
      $getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana9
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasH = $getComprasSemanalH->num_rows;

      if($numComprasH > 0){
        $numComprasMes2Sem4 = $numComprasH;
      } else {
        $numComprasMes2Sem4 = 0;
      }

      //Semana 1 Mes 3
      $getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana11
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasI = $getComprasSemanalI->num_rows;

      if($numComprasI > 0){
        $numComprasMes3Sem1 = $numComprasI;
      } else {
        $numComprasMes3Sem1 = 0;
      }

      //Semana 2 Mes 3
      $getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana12
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasJ = $getComprasSemanalJ->num_rows;

      if($numComprasJ > 0){
        $numComprasMes3Sem2 = $numComprasJ;
      } else {
        $numComprasMes3Sem2 = 0;
      }

      //Semana 3 Mes 3
      $getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana13
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasK = $getComprasSemanalK->num_rows;

      if($numComprasK > 0){
        $numComprasMes3Sem3 = $numComprasK;
      } else {
        $numComprasMes3Sem3 = 0;
      }

      //Semana 4 Mes 3
      $getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana14
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasL = $getComprasSemanalL->num_rows;

      if($numComprasL > 0){
        $numComprasMes3Sem4 = $numComprasL;
      } else {
        $numComprasMes3Sem4 = 0;
      }
    } elseif($MesActual > 9){
      $semana1 = "feccap >= '$year-10-01' AND feccap <= '$year-10-07' ";
      $semana2 = "feccap >= '$year-10-08' AND feccap <= '$year-10-14' ";
      $semana3 = "feccap >= '$year-10-15' AND feccap <= '$year-10-21' ";
      $semana4 = "feccap >= '$year-10-21' AND feccap <= '$year-10-28' ";
      $semana5 = "feccap >= '$year-10-29' AND feccap <= '$year-10-31' ";
      $semana6 = "feccap >= '$year-11-01' AND feccap <= '$year-11-07' ";
      $semana7 = "feccap >= '$year-11-08' AND feccap <= '$year-11-14' ";
      $semana8 = "feccap >= '$year-11-15' AND feccap <= '$year-11-21' ";
      $semana9 = "feccap >= '$year-11-21' AND feccap <= '$year-11-28' ";
      $semana10 = "feccap >= '$year-11-29' AND feccap <= '$year-11-30' ";
      $semana11 = "feccap >= '$year-12-01' AND feccap <= '$year-12-07' ";
      $semana12 = "feccap >= '$year-12-08' AND feccap <= '$year-12-14' ";
      $semana13 = "feccap >= '$year-12-15' AND feccap <= '$year-12-21' ";
      $semana14 = "feccap >= '$year-12-21' AND feccap <= '$year-12-28' ";
      $semana15 = "feccap >= '$year-12-29' AND feccap <= '$year-12-31' ";

      //Semana 1 Mes 1
      $getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana1
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasA = $getComprasSemanalA->num_rows;

      if($numComprasA > 0){
        $numComprasMes1Sem1 = $numComprasA;
      } else {
        $numComprasMes1Sem1 = 0;
      }

      //Semana 2 Mes 1
      $getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana2
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasB = $getComprasSemanalB->num_rows;

      if($numComprasB > 0){
        $numComprasMes1Sem2 = $numComprasB;
      } else {
        $numComprasMes1Sem2 = 0;
      }

      //Semana 3 Mes 1
      $getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana3
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasC = $getComprasSemanalC->num_rows;

      if($numComprasC > 0){
        $numComprasMes1Sem3 = $numComprasC;
      } else {
        $numComprasMes1Sem3 = 0;
      }

      //Semana 4 Mes 1
      $getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana4
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasD = $getComprasSemanalD->num_rows;

      if($numComprasD > 0){
        $numComprasMes1Sem4 = $numComprasD;
      } else {
        $numComprasMes1Sem4 = 0;
      }

      //Semana 1 Mes 2
      $getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana6
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasE = $getComprasSemanalE->num_rows;

      if($numComprasE > 0){
        $numComprasMes2Sem1 = $numComprasE;
      } else {
        $numComprasMes2Sem1 = 0;
      }

      //Semana 2 Mes 2
      $getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana7
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasF = $getComprasSemanalF->num_rows;

      if($numComprasF > 0){
        $numComprasMes2Sem2 = $numComprasF;
      } else {
        $numComprasMes2Sem2 = 0;
      }

      //Semana 3 Mes 2
      $getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana8
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasG = $getComprasSemanalG->num_rows;

      if($numComprasG > 0){
        $numComprasMes2Sem3 = $numComprasG;
      } else {
        $numComprasMes2Sem3 = 0;
      }

      //Semana 4 Mes 2
      $getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana9
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasH = $getComprasSemanalH->num_rows;

      if($numComprasH > 0){
        $numComprasMes2Sem4 = $numComprasH;
      } else {
        $numComprasMes2Sem4 = 0;
      }

      //Semana 1 Mes 3
      $getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana11
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasI = $getComprasSemanalI->num_rows;

      if($numComprasI > 0){
        $numComprasMes3Sem1 = $numComprasI;
      } else {
        $numComprasMes3Sem1 = 0;
      }

      //Semana 2 Mes 3
      $getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana12
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasJ = $getComprasSemanalJ->num_rows;

      if($numComprasJ > 0){
        $numComprasMes3Sem2 = $numComprasJ;
      } else {
        $numComprasMes3Sem2 = 0;
      }

      //Semana 3 Mes 3
      $getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana13
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasK = $getComprasSemanalK->num_rows;

      if($numComprasK > 0){
        $numComprasMes3Sem3 = $numComprasK;
      } else {
        $numComprasMes3Sem3 = 0;
      }

      //Semana 4 Mes 3
      $getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
                                FROM doc
                                where $semana14
                                AND feccan = 0
                                AND (
                                    tipo = 'F'
                                    OR tipo = 'R'
                                  )
                                AND clienteid = $id");
      $numComprasL = $getComprasSemanalL->num_rows;

      if($numComprasL > 0){
        $numComprasMes3Sem4 = $numComprasL;
      } else {
        $numComprasMes3Sem4 = 0;
      }
    }
  }
    
  // Mes y A単o
  $buscarMes=new DateTime();
  $mesNum = $buscarMes->format('m');
  $buscarAnio=new DateTime();
  $anio = $buscarAnio->format('Y');
  switch ($mesNum) {
    case 1:
      $mes='Enero';
      break;
    case 2:
      $mes='Febrero';
      break;
    case 3:
      $mes='Marzo';
      break;
    case 4:
      $mes='Abril';
      break;
    case 5:
      $mes='Mayo';
      break;
    case 6:
      $mes='Junio';
      break;
    case 7:
      $mes='Julio';
      break;
    case 8:
      $mes='Agosto';
      break;
    case 9:
      $mes='Septiembre';
      break;
    case 10:
      $mes='Octubre';
      break;
    case 11:
      $mes='Noviembre';
      break;
    case 12:
      $mes='Diciembre';
      break;
  }

  $print .=                       '<h4 class="text-center">Compras Totales</h4>
                              <p class="text-center">$ 20,000.00 MXN Minimo</p>
                              <p id="total" style="display: none;">'.$total.'</p>
                              <canvas id="comprasTri"></canvas>
                            </div>
                            <div class="col-md-4 col-sm-12" id="asignar">
                              <h4 class="text-center">Facturas Vencidas</h4>
                              <p class="text-center">No debe tener ninguna vencida</p>
                              <p style="display:none;" id="faltaVenTota">$faltaVenTota</p>
                              <div class="row">
                                <div class="col-sm-12">
                                  <p style="text-align:center;font-size:10em;font-weight:bold;color: #F02C2C !important; margin-top: 30px;">'.$numeroVeces.'</p>
                                </div>
                                <div style="display:none;">
                                  <canvas id="facturasTri"></canvas>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4 col-sm-12" style="width:30%">
                              <h4 class="text-center">Compras Semanales</h4>
                              <p id="mesNum" style="display: none;">'.$mesNum.'</p>
                              <p id="numComprasMes1Sem1" style="display:none;">'.$numComprasMes1Sem1.'</p>
                              <p id="numComprasMes1Sem2" style="display:none;">'.$numComprasMes1Sem2.'</p>
                              <p id="numComprasMes1Sem3" style="display:none;">'.$numComprasMes1Sem3.'</p>
                              <p id="numComprasMes1Sem4" style="display:none;">'.$numComprasMes1Sem4.'</p>
                              <p id="numComprasMes2Sem1" style="display:none;">'.$numComprasMes2Sem1.'</p>
                              <p id="numComprasMes2Sem2" style="display:none;">'.$numComprasMes2Sem2.'</p>
                              <p id="numComprasMes2Sem3" style="display:none;">'.$numComprasMes2Sem3.'</p>
                              <p id="numComprasMes2Sem4" style="display:none;">'.$numComprasMes2Sem4.'</p>
                              <p id="numComprasMes3Sem1" style="display:none;">'.$numComprasMes3Sem1.'</p>
                              <p id="numComprasMes3Sem2" style="display:none;">'.$numComprasMes3Sem2.'</p>
                              <p id="numComprasMes3Sem3" style="display:none;">'.$numComprasMes3Sem3.'</p>
                              <p id="numComprasMes3Sem4" style="display:none;">'.$numComprasMes3Sem4.'</p>
                              <canvas id="barChart"></canvas>
                            </div>
                            <script src="../intranet/dist/js/pages/dashboard2.js"></script>
                          </div>
                          <!-- /.box-body -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                      <div class="row">
                        <div class="col-sm-4 col-xs-6">';
  if($total >= 20000){
    $print .=                   '<div class="description-block border-right">
                            <span class="description-percentage text-green"><i class="fa fa-check"></i> Aprobando</span>';
  } else {
    $print .=                   '<div class="description-block border-right">
                            <span class="description-percentage text-yellow"><i class="fa fa-times"></i> Reprobando</span>';
  }

  $print .=                     '<h5 class="description-header">Sus compras trimestrales debe ser mayor o igual a $20,000.00 pesos.</h5>
                            <span class="description-text">No entran códigos que inicien con 8/5/6, pero si entran de la marca Klintek</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-xs-6">
                            <div class="description-block border-right">';
  if($numeroVeces == 0){
    $print .=                     '<span class="description-percentage text-green"><i class="fa fa-check"></i> Aprobando</span>';
  } else {
    $print .=                     '<span class="description-percentage text-yellow"><i class="fa fa-times"></i> Reprobando</span>';
  }
  $print .=                     '<h5 class="description-header">No tener ninguna factura vencidad antes de que termine el trimestre.</h5>
                            <span class="description-text">Excepto si el último día de pago cae en domingo, se pasa al día siguiente.</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 col-xs-6">
                          <div class="description-block border-right">';
  $print .=                     '<h5 class="description-header">Debe de tener registrado por lo menos 2 compras por semana distinita.</h5>
                            <span class="description-text">No se puede registrar 8 compras al principio o al final del mes.</span>
                            </div>
                          <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                      <p class="text-center lead">La información mostrada es de solo carácter informativo y está sujeto a evaluación.</p>
                    </div>
                    <!-- /.box-footer -->
                  </div>
                  <!-- /.box -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <div class="row">
                <div class="col-md-12">
                  <div class="box box-warning direct-chat direct-chat-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title">PROMOTRUPER <b>'.$mes.' '.$anio.'</b></h3>
                      <div class="box-tools pull-right">
                        <div id="actualizarCarrito"></div>
                        </div>
                    </div>
                    <div class="box-body no-padding">
                      <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="pad" style="overflow-x:auto; height:360px;">';
  if($rol == "DISTRIBUIDOR"){
    $numPrecio = 1;
  } elseif ($rol == "SUBDISTRIBUIDOR"){
    $numPrecio = 2;
  } elseif ($rol == "MAYOREO"){
    $numPrecio = 3;
  }
  $getNumPromo = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento
            FROM inv i
              JOIN precios pre ON pre.unidadid = i.unibasid
            WHERE i.invdescuento > 0
              AND pre.nprecio = 1
            ORDER BY i.clvprov";
  $allNumPromo = mysqli_query($getConnection,$getNumPromo);
  $totalNumberPromo = mysqli_num_rows($allNumPromo);

  if($totalNumberPromo > 0) {
    $getPromo = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento
            FROM inv i
              JOIN precios pre ON pre.unidadid = i.unibasid
            WHERE i.invdescuento > 0
              AND pre.nprecio = 1
            ORDER BY i.invdescuento DESC";
    $executeQuery = $paramDb->Query($getPromo);
    $numRowPromo = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    if($numRowPromo > 0) {
      $headersPromo = ["Clave", "Imagen", "Descripción", "Precio", "Descuento", "Precio Promoción", "Agregar"];
      $classPerColumnPromo = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

      /*$headersPromo = ["Clave", "Código", "Imagen", "Descripción", "Precio", "Descuento", "Precio Promoción", "Agregar"];
      $classPerColumnPromo = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];*/

      $print .= $paramFunctions->drawTableHeader($headersPromo, $classPerColumnPromo);
      foreach($rows as $row) {
        $clave = $row["clave"];
        $codigo = $row["clvprov"];
        $titulo = $row["descripcio"];
        $precio = $row["precio"];
        $impuesto = $row["pimpuesto"];
        $impuesto = $impuesto / 100;
        $precioConIva = $precio + ($precio * $impuesto);
        $precioFormato = number_format($precioConIva, 2);
        $descPromo = $row["invdescuento"];
        $descPromDec = $descPromo / 100;
        $numDesc = number_format($descPromo);
        $preDesc = $precioConIva - ($precioConIva * $descPromDec);
        $preDescFomrato = number_format($preDesc, 2);

        $paramsPromo = array("productoID"=>$codigo,
                "location"=>"addProduct-to-shoppingcart-partner",
                "url"=>"../php/shopping/shopping.php",
                "booleanResponse"=>true,
                "divResultID"=>"content-shoppingCar-partner",
                "msgSuccess"=>"Producto agregado correctamente",
                "msgError"=>"Error al agregar producto al carrito");
        $paramsSendPromo = json_encode($paramsPromo);

        /*$getImgPromo = "SELECT imagen FROM imagenes WHERE codigo = $codigo";
        $imgPromo = mysqli_query($getConnection,$getImgPromo);
        $rowImg = mysqli_fetch_row($imgPromo);
        $imagen = $rowImg[0];*/
        

        if($precio > 0){
          $print .=                 "<tr>";
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  $clave
                                </td>";
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  $codigo
                                </td>";
          /*$print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  <img src='../img/img_pro/img/".$imagen."' width='100'/>
                                </td>";*/
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  $titulo
                                </td>";
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  MX$ $precioFormato
                                </td>";
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  <img src='../img/iconos/".$numDesc."porciento2000x763.png' width='100'/>
                                </td>";
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
                                  <p style='font-weight:bold; color: red;'>MX$ $preDescFomrato</p>
                                </td>";
          $print .=                   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>";
          $print .=                       "<button type='button' class='btn btn-success btnOk' onclick='generalFunctionToRequest($paramsSendPromo)'><i class='fa fa-plus' aria-hidden='true'></i></button>";
          $print .=                   "</td>
                              </tr>";
        }
      }
      $print .=                   '</table>';
    } else {
      $print .=         "<h4>No hay PROMOTRUPER este mes.</h4>";
    }
  }
    //

  $print .=                   '</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- MAP & BOX PANE -->
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Historial</h3>
                  <div class="box-tools pull-right">
                    <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="row">
                    <div class="col-md-12 col-sm-12">
                      <div class="pad" style="overflow-x:auto; height:360px;">';
  $getAllOrders = "SELECT clienteid FROM doc WHERE clienteid = $id";
  $allOrders = mysqli_query($getConnection,$getAllOrders);
  if (!$allOrders) {
    die(mysqli_error($getConnection));
  }
  $totalNumberRows = mysqli_num_rows($allOrders);

  $getUserNum = "SELECT numero FROM cli WHERE clienteid = $id";
  $userNum = mysqli_query($getConnection,$getUserNum);
  $num = mysqli_fetch_row($userNum);
  $numero = $num[0];

  if($totalNumberRows > 0) {
    $getorders = "SELECT docid, numero, tipo, fecha, total, totalpagado, impuesto, nota, clienteid
    FROM doc
    WHERE (
        tipo = 'F'
        OR tipo = 'R'
      )
      AND clienteid = $id
    ORDER BY fecha DESC";

    $executeQuery = $paramDb->Query($getorders);
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    if($numRow > 0) {
      /*$headers = ["Fecha", "Documento", "Saldo", "Pagado", "Status", "IVA", "", ""];
      $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];*/
      $headers = ["No. Pedido", "Fecha", "Documento", "Monto", "Pagado", "Saldo", "", "", "Detalles"];
      $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

      $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
      foreach($rows as $row) {
        $pedidoID = $row["docid"];
        $fecha = $row["fecha"];
        $referencia = $row["tipo"];
        $saldo = $row["total"];
        $formatoSaldo = number_format($saldo, 2);
        $pagado = $row["totalpagado"];
        $formatoPagado = number_format($pagado, 2);
        $falta = $pagado - $saldo;

        if($falta >= 0){
          $restante = 'Liquidada';
        } elseif($falta < 0){
          $restante = "MX$ ".number_format($falta, 2, '.', ',');
        }

        if($referencia == "F"){
          $referencia = "FACTURA";
        } elseif($referencia == "N") {
          $referencia = "REMISION";
        } elseif ($referencia == "R"){
          $referencia = "NOTA DE CREDITO";
        }

        if($saldo > 0){
          $print .=         "<tr>";
          $print .=           "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$pedidoID</td>";
          $print .=           "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$fecha</td>";
          $print .=           "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$referencia</td>";
          $print .=           "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>MX$ $formatoSaldo</td>";
          $print .=           "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>MX$ $formatoPagado</td>";
          $docID = $row["docid"];
          $pedidoIDPrueba = 1;
          if($restante == 'Liquidada'){
            $print .=         "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$restante</td>";
            $print .=         "<td class='text-center' style='vertical-align:middle; font-weight:bold;'><a href='../php/classes/factura.php?f=$docID&n=$id&u=$clienteID&r=$referencia'><i class='fa fa-file-pdf-o fa-2x' aria-hidden='true'></i></a></td>";
            $print .=         "<td class='text-center' style='vertical-align:middle; font-weight:bold;'><a href='../php/classes/xml.php?f=$docID'><i class='fa fa-file-code-o fa-2x' aria-hidden='true'></i></a></td>";
          } else {
            $print .=         "<td class='text-center' style='vertical-align:middle; font-weight:bold; color: white; background-color: #F88686;'>$restante</td>";
            $print .=         "<td class='text-center' style='vertical-align:middle; font-weight:bold;'></td>";
            $print .=         "<td class='text-center' style='vertical-align:middle; font-weight:bold;'></td>";
          }
          $print .=           "<td class='text-center'>
                          <a href='#' onclick='showDetail($docID)'>
                            <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
                          </a>
                        </td>";
          $print .=         "</tr>";
        }
      }
      $print .=         '</table>';
    } else {
      $print .=         "<h4>No tienes ningún pedido</h4>";
    }
  } // end validation num row > 0, do something if doesn't exist order
  $print .=         '</div>
                <!-- Map will be created here
                <div id="world-map-markers" style="height: 325px;"></div> -->
              </div>
            </div>
            <!-- /.row -->
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">En Ruta</h3>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Pedidos Realizados</h3>
          </div>
          <div class="box-body no-padding">
            <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="pad" style="overflow-x:auto; height:200px;">';

  $buscarPedidosRealizados = ("SELECT id, fechaPedido, status, folio FROM pedidos WHERE clienteid = $id");
  $queryPedidosRealizados = $getConnection->query($buscarPedidosRealizados);
  $rowsEncontrados = $queryPedidosRealizados->num_rows;
  $montototal = 0;
  if($rowsEncontrados > 0){
    $headers = ["ID", "FOLIO", "FECHA DEL PEDIDO", "ESTATUS", "DETALLES"];
    $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center"];
    $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
    while($filaPedidosRealizados = $queryPedidosRealizados->fetch_array()){
      $idPedido = $filaPedidosRealizados[0];
      $fechaDelPedido = $filaPedidosRealizados[1];
      $statusPedido = $filaPedidosRealizados[2];
      $folioPedido = $filaPedidosRealizados[3];
      $print .=           "<tr>
                        <td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$idPedido."</td>
                        <td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$folioPedido."</td>
                        <td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$fechaDelPedido."</td>
                        <td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$statusPedido."</td>
                        <td class='text-center'>
                          <a href='#' onclick='showDetailOrder($idPedido, $numPrecio)'>
                          <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
                          </a>
                        </td>
                      </tr>";
    }
  } else {
      $print .=           "<tr><p><b>Sin pedidos encontrados</b></p></tr>";
  }

  $print .=           '</table>
                </div>
              </div>
            </div>
          </div>
        </div>';

  switch ($mesNum) {
    case '1':
      $diasMes = 31;
      break;

    case '2':
      $diasMes = 28;
      break;

    case '3':
      $diasMes = 31;
      break;

    case '4':
      $diasMes = 30;
      break;

    case '5':
      $diasMes = 31;
      break;

    case '6':
      $diasMes = 30;
      break;

    case '7':
      $diasMes = 31;
      break;

    case '8':
      $diasMes = 31;
      break;

    case '9':
      $diasMes = 30;
      break;

    case '10':
      $diasMes = 31;
      break;

    case '11':
      $diasMes = 30;
      break;

    case '12':
      $diasMes = 31;
      break;
  }

  $inicioFechaCompMesActual = date("Y-m-01");
  $finFechaCompMesActual = date("Y-m-$diasMes");
  $buscarMonto=("SELECT docid, tipo, subtotal2 FROM doc
      where clienteid = $id
        AND (
            feccap >= '$inicioFechaCompMesActual'
                    AND feccap <= '$finFechaCompMesActual'
                  )
                AND tipo = 'F'
            ORDER BY docid");
  $queryMonto = $getConnection->query($buscarMonto);
  $montototal = 0;
  while($filaMonto = $queryMonto->fetch_array()){
    $monto = $filaMonto['subtotal2'];
    $montototal += $monto;
  }
  $montoTri = 20000;
  $montoMes = ($montoTri / 3) * 2;
  $porcentaje = ($montototal * 100) / $montoMes;

  $print .= '<!-- Info Boxes Style 2 -->
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Compras del Mes</span>
            <span class="info-box-number">$ '.number_format($montototal,2,".",",").'</span>
            <div class="progress">
              <div class="progress-bar" style="width: '.$porcentaje.'%"></div>
            </div>
            <span class="progress-description">
              Total de tus compras en este mes
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Facebook</h3>
          </div>
          <div class="box-body">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12">
              <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FFerremayoristasOlvera%2F&tabs=timeline&width=500&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=279354469090128" width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
            </div>
          </div>
        </div>
        <!-- /.box -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  </div>
  <!--<script>
    var facturasVencidas = document.getElementById("numeroVeces").innerHTML;
    var veces = 0;

    if(veces === 0){
      if(facturasVencidas > 0){
        alert("Tiene facturas vencidas, favor de realizar los pagos correspondientes para que no afecte su historial con nosotros. Gracias");
        veces = 1;
      }
    }
  </script>-->'; // End div row
   echo $print;
   $getConnection->close();
  }

  private function getEnlaceZona1($params){
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    if(isset($_SESSION["data"])) {
      $session = $_SESSION["data"];
    }

    $rol = "SZ-01";
    $zona = 1;
    $clienteID = $paramDb->SecureInput($session["username"]);
    $id = $_SESSION["data"]["id"];
    $username = $_SESSION["data"]["name"];
    $pas2 = $_SESSION["data"]["pas2"];
    $pasAnt = $_SESSION["data"]["pasAnt"];
    $correo = $_SESSION["data"]["correo"];
    $arrayBooleans = array("bManagementOrder" => false);
    if($pas2==''){
      echo  '<div id="myLargeModalLabel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header text-center">
                  <p class="lead">Bienvenido a su Escritorio Virtual FMO, este es su primer inicio de sesión y le invitamos a realizar el cambio de su contraseña; si no lo realiza, no podrá accesar nuevamente a su escritorio hasta que termine el mes, <b>ya que su contraseña se resetea cada inicio de mes.</b></p>
                </div>
                <div class="modal-body" style="display:flex; align-items: center; justify-content: center;">
                  <form>
                    <div class="form-group" style="text-align: center;">
                      <input style="width:300px;" class="form-control text-center" id="usuario" name="usuario" value="'.$clienteID.'" type="text" readonly="readonly">
                    </div>
                    <div class="form-group" style="text-align: center;display:;">
                      <input style="width:300px;" class="form-control text-center" id="email" name="email" value="'.$correo.'" type="email" readonly="readonly">
                    </div>
                    <div class="form-group">
                      <input autofocus style="width:300px;" class="form-control text-center" id="passwordNew" name="password" onChange="verificarPassword()" placeholder="Nuevo Password" type="text" autocomplete="off" required>
                      <p id="pasAnt" style="display:none;">'.$pasAnt.'</p>
                    </div>
                    <script>
                      function verificarPassword(){
                        var pasAnt  = document.getElementById("pasAnt").innerHTML;
                        var pasNew  = document.getElementById("passwordNew").value;
                        var usuario = document.getElementById("usuario").value;
                        var email   = document.getElementById("email").value;
                        console.log(pasAnt, pasNew, usuario, email);
                        if(pasAnt === pasNew){
                          alert("El nuevo password debe ser diferente.");
                        } else {
                          $.post("../php/classes/cambiarpass.php", {usuario: usuario, password: pasNew, emial: email});
                          alert("Su contraseña se cambio correctamente, debe de inciar sesión con los nuevos datos. Gracias!.");
                          Session.Clear();
                          Session.Abandon();
                        }
                      }
                    </script>
                    <div id="mensajePas"></div>
                    <div class="form-group text-center" id="botonEnviar" style="display: none;">
                      <button class="btn btn-danger pull-center" type="submit">Enviar</button>
                    </div>
                  </form>
                  <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
                </div>
              </div>
            </div>
          </div>
          <script>$(document).ready(function(){$("#myLargeModalLabel").modal("show");});</script>';
    }

    $getFotoVen = "SELECT p.nombre, v.tel, v.foto, p.perid
                FROM vendedores v
                  JOIN per p ON p.perid = v.vendedorid
                WHERE p.sermov = $zona";
    $FotoVenEnc = mysqli_query($getConnection,$getFotoVen);
    $filaTotal =$FotoVenEnc->num_rows;

    $exeQuGet = $paramDb->Query($getFotoVen);
    if($exeQuGet === false) {
      echo "error-query";
      return false;
    }

    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();

    $email = 0;
    $print =  '<div class="row" style="margin: 55px 0 0 0;">
            <div class="content-wrapper">
              <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                <img src="../img/img_pro/barrafmo2.gif" width="200"/>
              </div>
              <!--<h4>Rol: '.$rol.'</h4>-->
              <!--<h4>Total de Vendedores Encontrados: '.$filaTotal.'</h4>-->
              <!-- Content Header (Page header) -->
              
              <!-- Main content -->
                <!-- Info boxes -->
                <div class="col-md-12">
                  <div class="row">';
    foreach($rows as $row) {
      $foto = $row["foto"];
      $nombre = $row["nombre"];
      $perid = $row["perid"];
      $linkFunctionPersonal = "showPersonal(".$perid.")";

      $print .=     '<div class="col-md-3" style="margin: 0 0 10px 0 !important;display: flex;
        justify-content: center;align-items: center;">
                      <div class="card text-center" style="width: 20rem;">
                        <a href="#" onclick="'.$linkFunctionPersonal.'">
                          <img class="img-circle foto-vendedor" src="../img/img_pro/vendedores/'.$foto.'" alt="'.$foto.'" width="170">
                        </a>
                        <div class="card-block">
                          <h4 class="card-title">'.$nombre.'</h4>
                        </div>
                      </div>
                    </div>';
    }
    $print .=     '</div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.content-wrapper -->
          </div>
          <!--<script>
            var facturasVencidas = document.getElementById("numeroVeces").innerHTML;
            var veces = 0;

            if(veces === 0){
              if(facturasVencidas > 0){
                alert("Tiene facturas vencidas, favor de realizar los pagos correspondientes para que no afecte su historial con nosotros. Gracias");
                veces = 1;
              }
            }
          </script>-->'; // End div row
    echo $print;
    $getConnection->close();
  }

  private function getEnlaceZona2($params){
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    if(isset($_SESSION["data"])) {
      $session = $_SESSION["data"];
    }

    $rol = "SZ-02";
    $zona = 2;
    $clienteID = $paramDb->SecureInput($session["username"]);
    $id = $_SESSION["data"]["id"];
    $username = $_SESSION["data"]["name"];
    $pas2 = $_SESSION["data"]["pas2"];
    $pasAnt = $_SESSION["data"]["pasAnt"];
    $correo = $_SESSION["data"]["correo"];
    $arrayBooleans = array("bManagementOrder" => false);
    if($pas2==''){
      echo  '<div id="myLargeModalLabel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header text-center">
                  <p class="lead">Bienvenido a su Escritorio Virtual FMO, este es su primer inicio de sesión y le invitamos a realizar el cambio de su contraseña; si no lo realiza, no podrá accesar nuevamente a su escritorio hasta que termine el mes, <b>ya que su contraseña se resetea cada inicio de mes.</b></p>
                </div>
                <div class="modal-body" style="display:flex; align-items: center; justify-content: center;">
                  <form>
                    <div class="form-group" style="text-align: center;">
                      <input style="width:300px;" class="form-control text-center" id="usuario" name="usuario" value="'.$clienteID.'" type="text" readonly="readonly">
                    </div>
                    <div class="form-group" style="text-align: center;display:;">
                      <input style="width:300px;" class="form-control text-center" id="email" name="email" value="'.$correo.'" type="email" readonly="readonly">
                    </div>
                    <div class="form-group">
                      <input autofocus style="width:300px;" class="form-control text-center" id="passwordNew" name="password" onChange="verificarPassword()" placeholder="Nuevo Password" type="text" autocomplete="off" required>
                      <p id="pasAnt" style="display:none;">'.$pasAnt.'</p>
                    </div>
                    <script>
                      function verificarPassword(){
                        var pasAnt  = document.getElementById("pasAnt").innerHTML;
                        var pasNew  = document.getElementById("passwordNew").value;
                        var usuario = document.getElementById("usuario").value;
                        var email   = document.getElementById("email").value;
                        console.log(pasAnt, pasNew, usuario, email);
                        if(pasAnt === pasNew){
                          alert("El nuevo password debe ser diferente.");
                        } else {
                          $.post("../php/classes/cambiarpass.php", {usuario: usuario, password: pasNew, emial: email});
                          alert("Su contraseña se cambio correctamente, debe de inciar sesión con los nuevos datos. Gracias!.");
                          Session.Clear();
                          Session.Abandon();
                        }
                      }
                    </script>
                    <div id="mensajePas"></div>
                    <div class="form-group text-center" id="botonEnviar" style="display: none;">
                      <button class="btn btn-danger pull-center" type="submit">Enviar</button>
                    </div>
                  </form>
                  <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
                </div>
              </div>
            </div>
          </div>
          <script>$(document).ready(function(){$("#myLargeModalLabel").modal("show");});</script>';
    }

    $getFotoVen = "SELECT p.nombre, v.tel, v.foto, p.perid
                FROM vendedores v
                  JOIN per p ON p.perid = v.vendedorid
                WHERE p.sermov = $zona";
    $FotoVenEnc = mysqli_query($getConnection,$getFotoVen);
    $filaTotal =$FotoVenEnc->num_rows;

    $exeQuGet = $paramDb->Query($getFotoVen);
    if($exeQuGet === false) {
      echo "error-query";
      return false;
    }

    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();

    $email = 0;
    $print =  '<div class="row" style="margin: 55px 0 0 0;">
            <div class="content-wrapper">
              <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                <img src="../img/img_pro/barrafmo2.gif" width="200"/>
              </div>
              <!--<h4>Rol: '.$rol.'</h4>-->
              <!--<h4>Total de Vendedores Encontrados: '.$filaTotal.'</h4>-->
              <!-- Content Header (Page header) -->
              
                <!-- Info boxes -->
                <div class="col-md-12">
                  <div class="row">';
    foreach($rows as $row) {
      $foto = $row["foto"];
      $nombre = $row["nombre"];
      $perid = $row["perid"];
      $linkFunctionPersonal = "showPersonal(".$perid.")";

      $print .=     '<div class="col-md-3" style="margin: 0 0 10px 0 !important;display: flex;
        justify-content: center;align-items: center;">
                      <div class="card text-center" style="width: 20rem;">
                        <a href="#" onclick="'.$linkFunctionPersonal.'">
                          <img class="img-circle foto-vendedor" src="../img/img_pro/vendedores/'.$foto.'" alt="'.$foto.'" width="170">
                        </a>
                        <div class="card-block">
                          <h4 class="card-title">'.$nombre.'</h4>
                        </div>
                      </div>
                    </div>';
    }
    $print .=     '</div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.content-wrapper -->
          </div>
          <!--<script>
            var facturasVencidas = document.getElementById("numeroVeces").innerHTML;
            var veces = 0;

            if(veces === 0){
              if(facturasVencidas > 0){
                alert("Tiene facturas vencidas, favor de realizar los pagos correspondientes para que no afecte su historial con nosotros. Gracias");
                veces = 1;
              }
            }
          </script>-->'; // End div row
    echo $print;
    $getConnection->close();
  }

  private function getDashBoardSz($params) {
  $paramDb = new Database();
  $paramFunctions = new Util();
  $getConnection = $paramDb->GetLink();

  if(isset($_SESSION["data"])) {
    $session = $_SESSION["data"];
  }

  $rol = $paramDb->SecureInput($session["rol"]);
  $clienteID = $paramDb->SecureInput($session["username"]);
  $id = $_SESSION["data"]["id"];
  //$id = 4;
  $username = $_SESSION["data"]["name"];
  $pas2 = $_SESSION["data"]["pas2"];
  $pasAnt = $_SESSION["data"]["pasAnt"];
  $correo = $_SESSION["data"]["correo"];
  $arrayBooleans = array("bManagementOrder" => false);
  if($pas2==''){
    echo  '<div id="myLargeModalLabel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header text-center">
                <p class="lead">Bienvenido a su Escritorio Virtual FMO, este es su primer inicio de sesión y le invitamos a realizar el cambio de su contraseña; si no lo realiza, no podrá accesar nuevamente a su escritorio hasta que termine el mes, <b>ya que su contraseña se resetea cada inicio de mes.</b></p>
              </div>
              <div class="modal-body" style="display:flex; align-items: center; justify-content: center;">
                <form>
                  <div class="form-group" style="text-align: center;">
                    <input style="width:300px;" class="form-control text-center" id="usuario" name="usuario" value="'.$clienteID.'" type="text" readonly="readonly">
                  </div>
                  <div class="form-group" style="text-align: center;display:;">
                    <input style="width:300px;" class="form-control text-center" id="email" name="email" value="'.$correo.'" type="email" readonly="readonly">
                  </div>
                  <div class="form-group">
                    <input autofocus style="width:300px;" class="form-control text-center" id="passwordNew" name="password" onChange="verificarPassword()" placeholder="Nuevo Password" type="text" autocomplete="off" required>
                    <p id="pasAnt" style="display:none;">'.$pasAnt.'</p>
                  </div>
                  <script>
                    function verificarPassword(){
                      var pasAnt  = document.getElementById("pasAnt").innerHTML;
                      var pasNew  = document.getElementById("passwordNew").value;
                      var usuario = document.getElementById("usuario").value;
                      var email   = document.getElementById("email").value;
                      console.log(pasAnt, pasNew, usuario, email);
                      if(pasAnt === pasNew){
                        alert("El nuevo password debe ser diferente.");
                      } else {
                        $.post("../php/classes/cambiarpass.php", {usuario: usuario, password: pasNew, emial: email});
                        alert("Su contraseña se cambio correctamente, debe de inciar sesión con los nuevos datos. Gracias!.");
                        Session.Clear();
                        Session.Abandon();
                      }
                    }
                  </script>
                  <div id="mensajePas"></div>
                  <div class="form-group text-center" id="botonEnviar" style="display: none;">
                    <button class="btn btn-danger pull-center" type="submit">Enviar</button>
                  </div>
                </form>
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
              </div>
            </div>
          </div>
        </div>
        <script>$(document).ready(function(){$("#myLargeModalLabel").modal("show");});</script>';
  }

  if($rol == 'SZ-01'){
    $zona = 1;
  }elseif($rol == 'SZ-02'){
    $zona = 2;
  }else{
    session_unset();
    session_destroy();
    echo '<script language="javascript">alert("Su tiempo ha expirado, vuelva a iniciar sesión para continuar en su escritorio.");</script>'; 
    header("Location: ../login/index.php");
  }

  $getFotoVen = "SELECT p.nombre, v.tel, v.foto, p.perid
              FROM vendedores v
                JOIN per p ON p.perid = v.vendedorid
              WHERE p.sermov = $zona";
  $FotoVenEnc = mysqli_query($getConnection,$getFotoVen);
  $filaTotal =$FotoVenEnc->num_rows;

  $exeQuGet = $paramDb->Query($getFotoVen);
  if($exeQuGet === false) {
    echo "error-query";
    return false;
  }

  $numRow = $paramDb->NumRows();
  $rows = $paramDb->Rows();

  $email = 0;
  $print =  '<div class="row" style="margin: 55px 0 0 0;">
          <div class="content-wrapper">
            <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
              <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
              <img src="../img/img_pro/barrafmo2.gif" width="200"/>
            </div>
            <!--<h4>Rol: '.$rol.'</h4>-->
            <!--<h4>Total de Vendedores Encontrados: '.$filaTotal.'</h4>-->
            <!-- Content Header (Page header) -->
            
            <!-- Main content -->
            <section class="content">
              <!-- Info boxes -->
              <div class="col-md-12">
                <div class="row">';
  foreach($rows as $row) {
    $foto = $row["foto"];
    $nombre = $row["nombre"];
    $perid = $row["perid"];
    $linkFunctionPersonal = "showPersonal(".$perid.")";

    $print .=     '<div class="col-md-4" style="margin: 0 0 10px 0 !important; display: flex;">
                    <div class="card text-center" style="width: 20rem;">
                      <a href="#" onclick="'.$linkFunctionPersonal.'">
                        <img class="img-circle foto-vendedor" src="../img/img_pro/vendedores/'.$foto.'" alt="'.$foto.'" width="170">
                      </a>
                      <div class="card-block">
                        <h4 class="card-title">'.$nombre.'</h4>
                      </div>
                    </div>
                  </div>';
  }
  $print .=     '</div>
              </div>
              <!-- /.row -->
            </section>
            <!-- /.content -->
          </div>
          <!-- /.content-wrapper -->
        </div>
        <!--<script>
          var facturasVencidas = document.getElementById("numeroVeces").innerHTML;
          var veces = 0;

          if(veces === 0){
            if(facturasVencidas > 0){
              alert("Tiene facturas vencidas, favor de realizar los pagos correspondientes para que no afecte su historial con nosotros. Gracias");
              veces = 1;
            }
          }
        </script>-->'; // End div row
    echo $print;
    $getConnection->close();
  }
}

?>