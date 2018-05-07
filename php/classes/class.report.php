<?php
require_once("../class.database.php");
require_once("../functions/util.php");
date_default_timezone_set('America/Mexico_City');

class Report {
  public function getterDashBoardAdmin($params) {
    $this->getDashBoardAdmin($params);
  }

  public function getterDashBoardComp($params) {
    $this->getDashBoardComp($params);
  }

  public function getterResumenInvTub($params) {
    $this->getResumenInvTub($params);
  }

  public function getterDashBoardMesaQro($params) {
    $this->getDashBoardMesaQro($params);
  }

  public function getterDashBoardCartera($params) {
    $this->getDashBoardCartera($params);
  }

  public function getterDashBoardDirIndex($params) {
    $this->getDashBoardDirIndex($params);
  }

  public function getterDashBoardAlamcen($params) {
    $this->getDashBoardAlamcen($params);
  }

  public function getterDashBoardMesa($params) {
    $this->getDashBoardMesa($params);
  }

  public function getterOutPipes($params) {
    $this->getOutPipes($params);
  }

  public function getterDashBoardDireccion($params) {
    $this->getDashBoardDireccion($params);
  }

  public function getterGetPedidosPorHora($params) {
    $this->getPedidosPorHora($params);
  }

  public function getterShowBackOrderActual($params) {
    $this->getShowBackOrderActual($params);
  }

  public function getterShowBackOrderReng($params) {
    $this->getShowBackOrderReng($params);
  }

  public function getterBackOrder($params) {
    $this->getBackOrder($params);
  }

  public function getterReportService($params) {
    $this->getReportService($params);
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

  public function getterGetDashBoardAsesor($params) {
    $this->getDashBoardAsesor($params);
  }

  public function getterGetReporteVendedor($params) {
    $this->getReporteVendedor($params);
  }

  public function getterGetReportePedidosDia($params) {
    $this->getReportePedidosDia($params);
  }

  public function getterGetClientesNuevosMes($params) {
    $this->getClientesNuevosMes($params);
  }

  public function getterGetShowDetailMor($params){
    $this->ShowDetailMor($params);
  }

  public function getterNuevosClientes($params){
    $this->NuevosClientes($params);
  }

  public function getterGetAddInv($params){
    $this->getAddInv($params);
  }

  public function getterGetModInv($params){
    $this->getModInv($params);
  }

  public function getterGetNewCustomer($params){
    $this->getNewCustomer($params);
  }

  private function getPedidosPorHora($params) {
    $paramFunctions = new Util();
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $rol = $params["session"]["rol"];

    $dv = date('D');
    switch($dv){
      case 'Mon':
        $diaVis = 'L';
        break;
      case 'Tue':
        $diaVis = 'M';
        break;
      case 'Wed':
        $diaVis = 'I';
        break;
      case 'Thu':
        $diaVis = 'J';
        break;
      case 'Fri':
        $diaVis = 'V';
        break;
    }

    if($rol == "direccion"){
    // Se busca a los vendedores
    $buscarVendedoresZona1 ="SELECT perid, nombre
                          FROM per
                          WHERE grupo = 'MV'
                            AND caja > 0
                            AND sermov = 1";
    $venEncontradoZona1 = mysqli_query($getConnection, $buscarVendedoresZona1);

    $linkActualizar = "showInformation('pedidosPorHora')";

    $print = '<div class="col-12 paddingT paddinB">
              <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                <img src="../img/barrafmo2.gif" width="200"/>
              </div>
              <h4 class="display-4 text-center">LINEA DE TIEM<span class="text-tomato">PO DEL ASESOR</span></h4>
              <div class="row paddingT">
                <div class="col-12 text-center paddingB">
                  <button type="button" class="btn btn-outline-primary text-center" onClick="'.$linkActualizar.'">Actualizar</button>
                </div>
                <div class="col-12 text-center paddingB">
                  <h4 class="h4">ZONA 1</h4>
                  <ul class="list-inline">
                    <li class="list-inline-item">CD: Clientes del Día</li>
                    <li class="list-inline-item">C: Clientes</li>
                    <li class="list-inline-item">P: Pedidos</li>
                  </ul>
                </div>
                <div class="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>ASESOR</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>CLIENTES DEL DIA</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>CLIENTES DE OTRO DIA</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>8:00 - 9:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>9:00 - 10:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>10:00 - 11:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>11:00 - 12:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>12:00 - 13:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>13:00 - 14:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>14:00 - 15:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>15:00 - 16:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>16:00 - 17:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>17:00 - 18:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>18:00 - 19:00</span>
                </div>';
    $indice = 0;
    while($rowVenZona1 = mysqli_fetch_row($venEncontradoZona1)){
      // var_dump($rowVenZona1);
      $perid = $rowVenZona1[0];
      $nombre = $rowVenZona1[1];
      $dia = date('Y-m-d');

      $print .= '<span id="per'.$indice.'" style="display: none;">'.$perid.'</span>';

      $indice++;

      $linkFunctionPersonal = "showPersonal(".$perid.")";

      $buscarCDTotFil = "SELECT count(c.clienteid)
                        FROM cli c
                        where c.vendedorid = ".$perid."
                          and c.diavis LIKE '%".$diaVis."%'";
      $encontradoCDTotFil = mysqli_query($getConnection, $buscarCDTotFil);
      $rowCDTotFil = mysqli_fetch_row($encontradoCDTotFil);
      $nCDTotFil = $rowCDTotFil[0];

      $print .= '<div class="colTime centrar" style="border-left: 1px solid #d7d7d7;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <div style="width: 100%">
                    <div>
                      <a class="nav-link text-truncate" style="color: white!important" href="#" onclick="'.$linkFunctionPersonal.'" data-toggle="tooltip" data-placement="top" title="'.$nombre.'"><span class="text-truncate" style="font-size: .6em;">'.$nombre.'</span></a>
                    </div>
                    <div style="text-align:center;font-size:.8em;">
                      CD:<span class="text-green" id="totalDia'.$perid.'">'.$nCDTotFil.'</span>
                    </div>
                  </div>
                </div>';

      $print .= '<div class="colTime">
                  <div class="row">';

      $buscarCTotFil = "SELECT c.clienteid
                        from doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where c.vendedorid = ".$perid."
                          and d.fecha = '".$dia."'
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'
                          and c.diavis LIKE '%".$diaVis."%'
                          group by c.clienteid
                          order by c.clienteid";
      $encontradoCTTotFil = mysqli_query($getConnection, $buscarCTotFil);
      $rowCTTotFil = mysqli_num_rows($encontradoCTTotFil);

      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      C:<span class="text-green">'.$rowCTTotFil.'</span>
                    </div>';
      
      $buscarPDTotFil = "SELECT d.docid
                        from doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where c.vendedorid = ".$perid."
                          and d.fecha = '".$dia."'
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'
                          and c.diavis LIKE '%".$diaVis."%'";
      $encontradoPDTotFil = mysqli_query($getConnection, $buscarPDTotFil);
      $rowPDTotFil = mysqli_num_rows($encontradoPDTotFil);

      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      P:<span class="text-green">'.$rowPDTotFil.'</span>
                    </div>';
      
      $print .=   '</div>
                </div>';

      $print .= '<div class="colTime">
                  <div class="row">';

      $buscarCTotFil = "SELECT d.clienteid
                          from doc d
                            left outer join cli c on c.clienteid = d.clienteid
                          where c.vendedorid = ".$perid."
                            and d.fecha = '".$dia."'
                            and c.vendedor NOT LIKE 'OF'
                            and c.diavis NOT LIKE '%".$diaVis."%'
                          group by d.clienteid";
      $encontradoCTotFil = mysqli_query($getConnection, $buscarCTotFil);
      $rowCTotFil = mysqli_num_rows($encontradoCTotFil);

      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      C:<span class="text-green">'.$rowCTotFil.'</span>
                    </div>';

      $buscarSumTotFil = "SELECT count(d.total)
                            FROM doc d
                              left outer join cli c on c.clienteid = d.clienteid
                            where d.fecha = '".$dia."'
                              and c.vendedorid = ".$perid."
                              and c.vendedor NOT LIKE 'OF'
                              and d.tipo = 'C'
                              and c.diavis NOT LIKE '%".$diaVis."%'";
      $encontradoSumTotFil = mysqli_query($getConnection, $buscarSumTotFil);
      $rowSumTotFil = mysqli_fetch_row($encontradoSumTotFil);
      $sumaTotFil = $rowSumTotFil[0];
                
      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      P:<span class="text-green" id="total'.$perid.'">'.$sumaTotFil.'</span>
                    </div>';
      
      $print .=   '</div>
                </div>';
      
      $buscar8a9 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '08:00' and d.hora <= '09:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado8a9 = mysqli_query($getConnection, $buscar8a9);

      while($row8a9 = mysqli_fetch_row($encontrado8a9)){
        $n8a9 = $row8a9[0];
        if($n8a9 > 0){
    $print .=   '<div class="colTime text-center text-yellow centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n8a9'.$perid.'">'.$n8a9.'</span>
                </div>';
        }else{
    $print .=   '<div class=" colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n8a9'.$perid.'">'.$n8a9.'</span>
                </div>';
        }
      }

      $buscar9a10 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '09:00' and d.hora <= '10:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado9a10 = mysqli_query($getConnection, $buscar9a10);

      while($row9a10 = mysqli_fetch_row($encontrado9a10)){
        $n9a10 = $row9a10[0];
        if($n9a10 > 0){
    $print .=   '<div class="colTime text-center text-yellow centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n9a10'.$perid.'">'.$n9a10.'</span>
                </div>';
        }else{
    $print .=   '<div class=" colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n9a10'.$perid.'">'.$n9a10.'</span>
                </div>';
        }
      }

      $buscar10a11 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '10:00' and d.hora <= '11:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado10a11 = mysqli_query($getConnection, $buscar10a11);

      while($row10a11 = mysqli_fetch_row($encontrado10a11)){
        $n10a11 = $row10a11[0];
        if($n10a11 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n10a11'.$perid.'">'.$n10a11.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n10a11'.$perid.'">'.$n10a11.'</span>
                </div>';
        }
      }

      $buscar11a12 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '11:00' and d.hora <= '12:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado11a12 = mysqli_query($getConnection, $buscar11a12);

      while($row11a12 = mysqli_fetch_row($encontrado11a12)){
        $n11a12 = $row11a12[0];
        if($n11a12 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n11a12'.$perid.'">'.$n11a12.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n11a12'.$perid.'">'.$n11a12.'</span>
                </div>';
        }
      }

      $buscar12a13 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '12:00' and d.hora <= '13:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado12a13 = mysqli_query($getConnection, $buscar12a13);

      while($row12a13 = mysqli_fetch_row($encontrado12a13)){
        $n12a13 = $row12a13[0];
        if($n12a13 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n12a13'.$perid.'">'.$n12a13.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n12a13'.$perid.'">'.$n12a13.'</span>
                </div>';
        }
      }

      $buscar13a14 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '13:00' and d.hora <= '14:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado13a14 = mysqli_query($getConnection, $buscar13a14);

      while($row13a14 = mysqli_fetch_row($encontrado13a14)){
        $n13a14 = $row13a14[0];
        if($n13a14 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n13a14'.$perid.'">'.$n13a14.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n13a14'.$perid.'">'.$n13a14.'</span>
                </div>';
        }
      }

      $buscar14a15 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '14:00' and d.hora <= '15:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado14a15 = mysqli_query($getConnection, $buscar14a15);

      while($row14a15 = mysqli_fetch_row($encontrado14a15)){
        $n14a15 = $row14a15[0];
        if($n14a15 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n14a15'.$perid.'">'.$n14a15.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n14a15'.$perid.'">'.$n14a15.'</span>
                </div>';
        }
      }

      $buscar15a16 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '15:00' and d.hora <= '16:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado15a16 = mysqli_query($getConnection, $buscar15a16);

      while($row15a16 = mysqli_fetch_row($encontrado15a16)){
        $n15a16 = $row15a16[0];
        if($n15a16 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n15a16'.$perid.'">'.$n15a16.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n15a16'.$perid.'">'.$n15a16.'</span>
                </div>';
        }
      }

      $buscar16a17 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '16:00' and d.hora <= '17:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado16a17 = mysqli_query($getConnection, $buscar16a17);

      while($row16a17 = mysqli_fetch_row($encontrado16a17)){
        $n16a17 = $row16a17[0];
        if($n16a17 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n16a17'.$perid.'">'.$n16a17.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n16a17'.$perid.'">'.$n16a17.'</span>
                </div>';
        }
      }

      $buscar17a18 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '17:00' and d.hora <= '18:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado17a18 = mysqli_query($getConnection, $buscar17a18);

      while($row17a18 = mysqli_fetch_row($encontrado17a18)){
        $n17a18 = $row17a18[0];
        if($n17a18 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n17a18'.$perid.'">'.$n17a18.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n17a18'.$perid.'">'.$n17a18.'</span>
                </div>';
        }
      }

      $buscar18a19 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '18:00' and d.hora <= '19:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado18a19 = mysqli_query($getConnection, $buscar18a19);

      while($row18a19 = mysqli_fetch_row($encontrado18a19)){
        $n18a19 = $row18a19[0];
        if($n18a19 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n18a19'.$perid.'">'.$n18a19.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n18a19'.$perid.'">'.$n18a19.'</span>
                </div>';
        }
      }
    }

    $buscarTotalPedDia = "SELECT count(d.docid)
                            FROM doc d
                              left outer join per p on p.perid = d.vendedorid
                              left outer join cli c on c.clienteid = d.clienteid
                            where d.fecha = '".$dia."'
                              and d.tipo = 'C'
                              and c.vendedor NOT LIKE 'OF'
                              and c.diavis LIKE '%".$diaVis."%'
                              and p.sermov = 1";
    $enconTotalPedDia = mysqli_query($getConnection, $buscarTotalPedDia);
    $rowTotalPedDia = mysqli_fetch_row($enconTotalPedDia);
    $totalPedDia = $rowTotalPedDia[0];

    $buscarTotalPedDiaVis = "SELECT c.clienteid
                              from doc d
                                left outer join cli c on c.clienteid = d.clienteid
                                left OUTER JOIN per p ON p.perid = D.vendedorid
                              where d.fecha = '".$dia."'
                                and p.sermov = 1
                                and d.tipo = 'C'
                                and c.vendedor NOT LIKE 'OF'
                                and c.diavis NOT LIKE '%".$diaVis."%'";
    $enconTotalPedDiaVis = mysqli_query($getConnection, $buscarTotalPedDiaVis);
    $rowTotalPedDiaVis = mysqli_num_rows($enconTotalPedDiaVis);

    // Total por columna
    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border: 1px solid #d7d7d7;">
                  <span>Total</span>
                </div>';
    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$totalPedDia.'</span>
                </div>';
    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$rowTotalPedDiaVis.'</span>
                </div>';

    $totalCol8a9 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '08:00' and doc.hora <= '09:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol8a9 = mysqli_query($getConnection, $totalCol8a9);
    $rowTotalCol8a9 = mysqli_fetch_row($totalEncontradoCol8a9);
    $nTotal8a9 = $rowTotalCol8a9[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal8a9.'</span>
                </div>';

    $totalCol9a10 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '09:00' and doc.hora <= '10:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol9a10 = mysqli_query($getConnection, $totalCol9a10);
    $rowTotalCol9a10 = mysqli_fetch_row($totalEncontradoCol9a10);
    $nTotal9a10 = $rowTotalCol9a10[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal9a10.'</span>
                </div>';

    $totalCol10a11 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '10:00' and doc.hora <= '11:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol10a11 = mysqli_query($getConnection, $totalCol10a11);
    $rowTotalCol10a11 = mysqli_fetch_row($totalEncontradoCol10a11);
    $nTotal10a11 = $rowTotalCol10a11[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal10a11.'</span>
                </div>';

    $totalCol11a12 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '11:00' and doc.hora <= '12:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol11a12 = mysqli_query($getConnection, $totalCol11a12);
    $rowTotalCol11a12 = mysqli_fetch_row($totalEncontradoCol11a12);
    $nTotal11a12 = $rowTotalCol11a12[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal11a12.'</span>
                </div>';

    $totalCol12a13 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '12:00' and doc.hora <= '13:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol12a13 = mysqli_query($getConnection, $totalCol12a13);
    $rowTotalCol12a13 = mysqli_fetch_row($totalEncontradoCol12a13);
    $nTotal12a13 = $rowTotalCol12a13[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal12a13.'</span>
                </div>';

    $totalCol13a14 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '13:00' and doc.hora <= '14:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol13a14 = mysqli_query($getConnection, $totalCol13a14);
    $rowTotalCol13a14 = mysqli_fetch_row($totalEncontradoCol13a14);
    $nTotal13a14 = $rowTotalCol13a14[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal13a14.'</span>
                </div>';

    $totalCol14a15 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '14:00' and doc.hora <= '15:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol14a15 = mysqli_query($getConnection, $totalCol14a15);
    $rowTotalCol14a15 = mysqli_fetch_row($totalEncontradoCol14a15);
    $nTotal14a15 = $rowTotalCol14a15[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal14a15.'</span>
                </div>';

    $totalCol15a16 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '15:00' and doc.hora <= '16:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol15a16 = mysqli_query($getConnection, $totalCol15a16);
    $rowTotalCol15a16 = mysqli_fetch_row($totalEncontradoCol15a16);
    $nTotal15a16 = $rowTotalCol15a16[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal15a16.'</span>
                </div>';

    $totalCol16a17 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '16:00' and doc.hora <= '17:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol16a17 = mysqli_query($getConnection, $totalCol16a17);
    $rowTotalCol16a17 = mysqli_fetch_row($totalEncontradoCol16a17);
    $nTotal16a17 = $rowTotalCol16a17[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal16a17.'</span>
                </div>';

    $totalCol17a18 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '17:00' and doc.hora <= '18:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol17a18 = mysqli_query($getConnection, $totalCol17a18);
    $rowTotalCol17a18 = mysqli_fetch_row($totalEncontradoCol17a18);
    $nTotal17a18 = $rowTotalCol17a18[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal17a18.'</span>
                </div>';

    $totalCol18a19 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '18:00' and doc.hora <= '19:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 1";
    $totalEncontradoCol18a19 = mysqli_query($getConnection, $totalCol18a19);
    $rowTotalCol18a19 = mysqli_fetch_row($totalEncontradoCol18a19);
    $nTotal18a19 = $rowTotalCol18a19[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal18a19.'</span>
                </div>';

    $print .= '</div>
            </div>
            <div class="col-12 paddingT paddingB">
              <div class="row paddingT">
                <div class="col-12 text-center paddingB">
                    <h4 class="h4">ZONA 2</h4>
                </div>
                <div class="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>ASESOR</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>CLIENTES DEL DIA</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>CLIENTES DE OTRO DIA</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>8:00 - 9:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>9:00 - 10:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>10:00 - 11:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>11:00 - 12:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>12:00 - 13:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>13:00 - 14:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>14:00 - 15:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>15:00 - 16:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>16:00 - 17:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>17:00 - 18:00</span>
                </div>
                <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                  <span>18:00 - 19:00</span>
                </div>';
        
    // Se busca a los vendedores
    $buscarVendedoresZona2 ="SELECT perid, nombre
                          FROM per
                          WHERE grupo = 'MV'
                            AND caja > 0
                            AND sermov = 2";
    $venEncontradoZona2 = mysqli_query($getConnection, $buscarVendedoresZona2);

    $indice2 = 0;
    while($rowVenZona2 = mysqli_fetch_row($venEncontradoZona2)){
      // var_dump($rowVenZona2);
      $perid = $rowVenZona2[0];
      $nombre = $rowVenZona2[1];
      $dia = date('Y-m-d');
      $print .= '<span id="per'.$indice2.'" style="display: none;">'.$perid.'</span>';

      $indice2++;

      $linkFunctionPersonal = "showPersonal(".$perid.")";

      $buscarCDTotFil = "SELECT count(c.clienteid)
                          FROM cli c
                          where c.vendedorid = ".$perid."
                            and c.diavis LIKE '%".$diaVis."%'";
      $encontradoCDTotFil = mysqli_query($getConnection, $buscarCDTotFil);
      $rowCDTotFil = mysqli_fetch_row($encontradoCDTotFil);
      $nCDTotFil = $rowCDTotFil[0];

      $print .= '<div class="colTime centrar" style="border-left: 1px solid #d7d7d7;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <div style="width: 100%">
                    <div>
                      <a class="nav-link text-truncate" style="color: white!important" href="#" onclick="'.$linkFunctionPersonal.'" data-toggle="tooltip" data-placement="top" title="'.$nombre.'"><span class="text-truncate" style="font-size: .6em;">'.$nombre.'</span></a>
                    </div>
                    <div style="text-align:center;font-size:.8em;">
                      CD:<span class="text-green" id="totalDia'.$perid.'">'.$nCDTotFil.'</span>
                    </div>
                  </div>
                </div>';

      $print .= '<div class="colTime">
                  <div class="row">';

      $buscarCTotFil = "SELECT c.clienteid
                        from doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where c.vendedorid = ".$perid."
                          and d.fecha = '".$dia."'
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'
                          and c.diavis LIKE '%".$diaVis."%'
                          group by c.clienteid
                          order by c.clienteid";
      $encontradoCTTotFil = mysqli_query($getConnection, $buscarCTotFil);
      $rowCTTotFil = mysqli_num_rows($encontradoCTTotFil);

      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      C:<span class="text-green">'.$rowCTTotFil.'</span>
                    </div>';

      $buscarPDTotFil = "SELECT d.docid
                        from doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.vendedorid = ".$perid."
                          and d.fecha = '".$dia."'
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'
                          and c.diavis LIKE '%".$diaVis."%'";
      $encontradoPDTotFil = mysqli_query($getConnection, $buscarPDTotFil);
      $rowPDTotFil = mysqli_num_rows($encontradoPDTotFil);

      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      PD:<span class="text-green">'.$rowPDTotFil.'</span>
                    </div>';

      $print .=   '</div>
                </div>';

      $print .= '<div class="colTime">
                  <div class="row">';

      $buscarCTotFil = "SELECT c.clienteid
                          from doc d
                            left outer join cli c on c.clienteid = d.clienteid
                          where c.vendedorid = ".$perid."
                            and d.fecha = '".$dia."'
                            and c.vendedor NOT LIKE 'OF'
                            and c.diavis NOT LIKE '%".$diaVis."%'
                          group by c.clienteid";
      $encontradoCTotFil = mysqli_query($getConnection, $buscarCTotFil);
      $rowCTotFil = mysqli_num_rows($encontradoCTotFil);

      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      C:<span class="text-green">'.$rowCTotFil.'</span>
                    </div>';

      $buscarSumTotFil = "SELECT count(d.total)
                            FROM doc d
                              left outer join cli c on c.clienteid = d.clienteid
                            where d.fecha = '".$dia."'
                              and c.vendedorid = ".$perid."
                              and c.vendedor NOT LIKE 'OF'
                              and d.tipo = 'C'
                              and c.diavis NOT LIKE '%".$diaVis."%'";
      $encontradoSumTotFil = mysqli_query($getConnection, $buscarSumTotFil);
      $rowSumTotFil = mysqli_fetch_row($encontradoSumTotFil);
      $sumaTotFil = $rowSumTotFil[0];
                
      $print .=     '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                      P:<span class="text-green" id="total'.$perid.'">'.$sumaTotFil.'</span>
                    </div>';

      $print .=   '</div>
                </div>';

      $buscar8a9 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '08:00' and d.hora <= '09:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado8a9 = mysqli_query($getConnection, $buscar8a9);

      while($row8a9 = mysqli_fetch_row($encontrado8a9)){
        $n8a9 = $row8a9[0];
        if($n8a9 > 0){
      $print .=   '<div class="colTime text-center text-yellow centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n8a9'.$perid.'">'.$n8a9.'</span>
                </div>';
        }else{
      $print .=   '<div class=" colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n8a9'.$perid.'">'.$n8a9.'</span>
                </div>';
        }
      }

      $buscar9a10 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '09:00' and d.hora <= '10:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado9a10 = mysqli_query($getConnection, $buscar9a10);

      while($row9a10 = mysqli_fetch_row($encontrado9a10)){
        $n9a10 = $row9a10[0];
        if($n9a10 > 0){
      $print .=   '<div class="colTime text-center text-yellow centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n9a10'.$perid.'">'.$n9a10.'</span>
                </div>';
        }else{
      $print .=   '<div class=" colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n9a10'.$perid.'">'.$n9a10.'</span>
                </div>';
        }
      }

      $buscar10a11 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '10:00' and d.hora <= '11:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado10a11 = mysqli_query($getConnection, $buscar10a11);

      while($row10a11 = mysqli_fetch_row($encontrado10a11)){
        $n10a11 = $row10a11[0];
        if($n10a11 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n10a11'.$perid.'">'.$n10a11.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n10a11'.$perid.'">'.$n10a11.'</span>
                </div>';
        }
      }

      $buscar11a12 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '11:00' and d.hora <= '12:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado11a12 = mysqli_query($getConnection, $buscar11a12);

      while($row11a12 = mysqli_fetch_row($encontrado11a12)){
        $n11a12 = $row11a12[0];
        if($n11a12 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n11a12'.$perid.'">'.$n11a12.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n11a12'.$perid.'">'.$n11a12.'</span>
                </div>';
        }
      }

      $buscar12a13 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '12:00' and d.hora <= '13:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado12a13 = mysqli_query($getConnection, $buscar12a13);

      while($row12a13 = mysqli_fetch_row($encontrado12a13)){
        $n12a13 = $row12a13[0];
        if($n12a13 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n12a13'.$perid.'">'.$n12a13.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n12a13'.$perid.'">'.$n12a13.'</span>
                </div>';
        }
      }

      $buscar13a14 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '13:00' and d.hora <= '14:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado13a14 = mysqli_query($getConnection, $buscar13a14);

      while($row13a14 = mysqli_fetch_row($encontrado13a14)){
        $n13a14 = $row13a14[0];
        if($n13a14 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n13a14'.$perid.'">'.$n13a14.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n13a14'.$perid.'">'.$n13a14.'</span>
                </div>';
        }
      }

      $buscar14a15 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '14:00' and d.hora <= '15:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado14a15 = mysqli_query($getConnection, $buscar14a15);

      while($row14a15 = mysqli_fetch_row($encontrado14a15)){
        $n14a15 = $row14a15[0];
        if($n14a15 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n14a15'.$perid.'">'.$n14a15.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n14a15'.$perid.'">'.$n14a15.'</span>
                </div>';
        }
      }

      $buscar15a16 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '15:00' and d.hora <= '16:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado15a16 = mysqli_query($getConnection, $buscar15a16);

      while($row15a16 = mysqli_fetch_row($encontrado15a16)){
        $n15a16 = $row15a16[0];
        if($n15a16 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n15a16'.$perid.'">'.$n15a16.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n15a16'.$perid.'">'.$n15a16.'</span>
                </div>';
        }
      }

      $buscar16a17 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '16:00' and d.hora <= '17:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado16a17 = mysqli_query($getConnection, $buscar16a17);

      while($row16a17 = mysqli_fetch_row($encontrado16a17)){
        $n16a17 = $row16a17[0];
        if($n16a17 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n16a17'.$perid.'">'.$n16a17.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n16a17'.$perid.'">'.$n16a17.'</span>
                </div>';
        }
      }

      $buscar17a18 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '17:00' and d.hora <= '18:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado17a18 = mysqli_query($getConnection, $buscar17a18);

      while($row17a18 = mysqli_fetch_row($encontrado17a18)){
        $n17a18 = $row17a18[0];
        if($n17a18 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n17a18'.$perid.'">'.$n17a18.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n17a18'.$perid.'">'.$n17a18.'</span>
                </div>';
        }
      }

      $buscar18a19 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '18:00' and d.hora <= '19:00')
                        and c.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado18a19 = mysqli_query($getConnection, $buscar18a19);

      while($row18a19 = mysqli_fetch_row($encontrado18a19)){
        $n18a19 = $row18a19[0];
        if($n18a19 > 0){
      $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n18a19'.$perid.'">'.$n18a19.'</span>
                </div>';
        }else{
      $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span id="n18a19'.$perid.'">'.$n18a19.'</span>
                </div>';
        }
      }
    }

    $buscarTotalPedDia = "SELECT count(d.docid)
                            FROM doc d
                              left outer join per p on p.perid = d.vendedorid
                              left outer join cli c on c.clienteid = d.clienteid
                            where d.fecha = '".$dia."'
                              and d.tipo = 'C'
                              and c.vendedor NOT LIKE 'OF'
                              and c.diavis LIKE '%".$diaVis."%'
                              and p.sermov = 2";
    $enconTotalPedDia = mysqli_query($getConnection, $buscarTotalPedDia);
    $rowTotalPedDia = mysqli_fetch_row($enconTotalPedDia);
    $totalPedDia = $rowTotalPedDia[0];
    // REVISAR TOTAL DE CLIENTES QUE NO SON DEL DIA
    $buscarTotalPedDiaVis = "SELECT c.clienteid
                              from doc d
                                left outer join cli c on c.clienteid = d.clienteid
                                left OUTER JOIN per p ON p.perid = D.vendedorid
                              where d.fecha = '".$dia."'
                                and p.sermov = 2
                                and d.tipo = 'C'
                                and c.vendedor NOT LIKE 'OF'
                                and c.diavis NOT LIKE '%".$diaVis."%'";
    $enconTotalPedDiaVis = mysqli_query($getConnection, $buscarTotalPedDiaVis);
    $rowTotalPedDiaVis = mysqli_num_rows($enconTotalPedDiaVis);

    // Total por columna
    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border: 1px solid #d7d7d7;">
                  <span>Total</span>
                </div>';
    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$totalPedDia.'</span>
                </div>';
    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$rowTotalPedDiaVis.'</span>
                </div>';

    $totalCol8a9 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '08:00' and doc.hora <= '09:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol8a9 = mysqli_query($getConnection, $totalCol8a9);
    $rowTotalCol8a9 = mysqli_fetch_row($totalEncontradoCol8a9);
    $nTotal8a9 = $rowTotalCol8a9[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal8a9.'</span>
                </div>';

    $totalCol9a10 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '09:00' and doc.hora <= '10:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol9a10 = mysqli_query($getConnection, $totalCol9a10);
    $rowTotalCol9a10 = mysqli_fetch_row($totalEncontradoCol9a10);
    $nTotal9a10 = $rowTotalCol9a10[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal9a10.'</span>
                </div>';

    $totalCol10a11 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '10:00' and doc.hora <= '11:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol10a11 = mysqli_query($getConnection, $totalCol10a11);
    $rowTotalCol10a11 = mysqli_fetch_row($totalEncontradoCol10a11);
    $nTotal10a11 = $rowTotalCol10a11[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal10a11.'</span>
                </div>';

    $totalCol11a12 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '11:00' and doc.hora <= '12:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol11a12 = mysqli_query($getConnection, $totalCol11a12);
    $rowTotalCol11a12 = mysqli_fetch_row($totalEncontradoCol11a12);
    $nTotal11a12 = $rowTotalCol11a12[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal11a12.'</span>
                </div>';

    $totalCol12a13 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '12:00' and doc.hora <= '13:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol12a13 = mysqli_query($getConnection, $totalCol12a13);
    $rowTotalCol12a13 = mysqli_fetch_row($totalEncontradoCol12a13);
    $nTotal12a13 = $rowTotalCol12a13[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal12a13.'</span>
                </div>';

    $totalCol13a14 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '13:00' and doc.hora <= '14:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol13a14 = mysqli_query($getConnection, $totalCol13a14);
    $rowTotalCol13a14 = mysqli_fetch_row($totalEncontradoCol13a14);
    $nTotal13a14 = $rowTotalCol13a14[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal13a14.'</span>
                </div>';

    $totalCol14a15 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '14:00' and doc.hora <= '15:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol14a15 = mysqli_query($getConnection, $totalCol14a15);
    $rowTotalCol14a15 = mysqli_fetch_row($totalEncontradoCol14a15);
    $nTotal14a15 = $rowTotalCol14a15[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal14a15.'</span>
                </div>';

    $totalCol15a16 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '15:00' and doc.hora <= '16:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol15a16 = mysqli_query($getConnection, $totalCol15a16);
    $rowTotalCol15a16 = mysqli_fetch_row($totalEncontradoCol15a16);
    $nTotal15a16 = $rowTotalCol15a16[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal15a16.'</span>
                </div>';

    $totalCol16a17 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '16:00' and doc.hora <= '17:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol16a17 = mysqli_query($getConnection, $totalCol16a17);
    $rowTotalCol16a17 = mysqli_fetch_row($totalEncontradoCol16a17);
    $nTotal16a17 = $rowTotalCol16a17[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal16a17.'</span>
                </div>';

    $totalCol17a18 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '17:00' and doc.hora <= '18:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol17a18 = mysqli_query($getConnection, $totalCol17a18);
    $rowTotalCol17a18 = mysqli_fetch_row($totalEncontradoCol17a18);
    $nTotal17a18 = $rowTotalCol17a18[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal17a18.'</span>
                </div>';

    $totalCol18a19 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '18:00' and doc.hora <= '19:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = 2";
    $totalEncontradoCol18a19 = mysqli_query($getConnection, $totalCol18a19);
    $rowTotalCol18a19 = mysqli_fetch_row($totalEncontradoCol18a19);
    $nTotal18a19 = $rowTotalCol18a19[0];

    $print .=   '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                  <span>'.$nTotal18a19.'</span>
                </div>';

    $print .= '</div>
            </div>';
    }else{
      if($rol == 'SZ-01'){
        $sermov = 1;
      }else if($rol == 'SZ-02'){
        $sermov = 2;
      }
      // Se busca a los vendedores
    $buscarVendedoresZona1 ="SELECT perid, nombre
                              FROM per
                              WHERE grupo = 'MV'
                                AND caja > 0
                                AND sermov = $sermov";
    $venEncontradoZona1 = mysqli_query($getConnection, $buscarVendedoresZona1);

    $linkActualizar = "showInformation('pedidosPorHora')";

    $print =  '<div class="col-12 paddingT paddinB">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <h4 class="display-4 text-center">LINEA DE TIEM<span class="text-tomato">PO DEL ASESOR</span></h4>
                <div class="row paddingT paddingB">
                  <div class="col-12 text-center paddingB">
                    <button type="button" class="btn btn-outline-primary text-center" onClick="'.$linkActualizar.'">Actualizar</button>
                  </div>
                  <div class="col-12 text-center paddingB">
                    <h4 class="h4">ZONA '.$sermov.'</h4>
                    <ul class="list-inline">
                      <li class="list-inline-item">CD: Clientes del Día</li>
                      <li class="list-inline-item">PD: Pedidos del Día</li>
                      <li class="list-inline-item">C: Clientes</li>
                      <li class="list-inline-item">P: Pedidos</li>
                    </ul>
                  </div>
                  <div class="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>ASESOR</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>CLIENTES DEL DIA</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>CLIENTES DE OTRO DIA</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">|
                    <span>8:00 - 9:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>9:00 - 10:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>10:00 - 11:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>11:00 - 12:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>12:00 - 13:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>13:00 - 14:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>14:00 - 15:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>15:00 - 16:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>16:00 - 17:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>17:00 - 18:00</span>
                  </div>
                  <div class ="colTime text-center text-tomato centrar" style="border: 1px solid tomato;">
                    <span>18:00 - 19:00</span>
                  </div>';
    $indice = 0;
    while($rowVenZona1 = mysqli_fetch_row($venEncontradoZona1)){
      // var_dump($rowVenZona1);
      $perid = $rowVenZona1[0];
      $nombre = $rowVenZona1[1];
      $dia = date('Y-m-d');
      $print .= '<span id="per'.$indice.'" style="display: none;">'.$perid.'</span>';

      $indice++;

      $linkFunctionPersonal = "showPersonal(".$perid.")";

      $buscarCDTotFil = "SELECT count(c.clienteid)
                          FROM cli c
                          where c.vendedorid = ".$perid."
                            and c.diavis LIKE '%".$diaVis."%'";
      $encontradoCDTotFil = mysqli_query($getConnection, $buscarCDTotFil);
      $rowCDTotFil = mysqli_fetch_row($encontradoCDTotFil);
      $nCDTotFil = $rowCDTotFil[0];

      $print .=   '<div class="colTime centrar" style="border-left: 1px solid #d7d7d7;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <div style="width: 100%">
                      <div>
                        <a class="nav-link text-truncate" style="color: white!important" href="#" onclick="'.$linkFunctionPersonal.'" data-toggle="tooltip" data-placement="top" title="'.$nombre.'"><span class="text-truncate" style="font-size: .6em;">'.$nombre.'</span></a>
                      </div>
                      <div style="text-align:center;font-size:.8em;">
                        CD:<span class="text-green" id="totalDia'.$perid.'">'.$nCDTotFil.'</span>
                      </div>
                    </div>
                  </div>';

      $print .=   '<div class="colTime">
                    <div class="row">';

      $buscarCTotFil = "SELECT c.clienteid
                        from doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where c.vendedorid = ".$perid."
                          and d.fecha = '".$dia."'
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'
                          and c.diavis LIKE '%".$diaVis."%'
                          group by c.clienteid
                          order by c.clienteid";
      $encontradoCTTotFil = mysqli_query($getConnection, $buscarCTotFil);
      $rowCTTotFil = mysqli_num_rows($encontradoCTTotFil);

      $print .=       '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                        C:<span class="text-green">'.$rowCTTotFil.'</span>
                      </div>';

      $buscarPDTotFil = "SELECT c.clienteid
                          from doc d
                            left outer join cli c on c.clienteid = d.clienteid
                          where d.vendedorid = ".$perid."
                            and d.fecha = '".$dia."'
                            and c.diavis LIKE '%".$diaVis."%'
                            and c.vendedor NOT LIKE 'OF'";
      $encontradoPDTotFil = mysqli_query($getConnection, $buscarPDTotFil);
      $rowPDTotFil = mysqli_num_rows($encontradoPDTotFil);

      $print .=       '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                        PD:<span class="text-green">'.$rowPDTotFil.'</span>
                      </div>';

      $print .=     '</div>
                  </div>';

      $print .=   '<div class="colTime">
                    <div class="row">';

      $buscarCTotFil = "SELECT c.clienteid
                        from doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.vendedorid = ".$perid."
                          and d.fecha = '".$dia."'
                          and c.vendedor NOT LIKE 'OF'
                        group by c.clienteid";
      $encontradoCTotFil = mysqli_query($getConnection, $buscarCTotFil);
      $rowCTotFil = mysqli_num_rows($encontradoCTotFil);

      $print .=       '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                        C:<span class="text-green">'.$rowCTotFil.'</span>
                      </div>';

      $buscarSumTotFil = "SELECT count(d.total)
                            FROM doc d
                              left outer join cli c on c.clienteid = d.clienteid
                            where d.fecha = '".$dia."'
                              and d.vendedorid = ".$perid."
                              and c.vendedor NOT LIKE 'OF'
                              and d.tipo = 'C'";
      $encontradoSumTotFil = mysqli_query($getConnection, $buscarSumTotFil);
      $rowSumTotFil = mysqli_fetch_row($encontradoSumTotFil);
      $sumaTotFil = $rowSumTotFil[0];

      $print .=       '<div class ="text-center centrar" style="font-size:.8em;position:relative; width:100%; min-height:1px;padding: .5rem 1rem; -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%;border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                        P:<span class="text-green" id="total'.$perid.'">'.$sumaTotFil.'</span>
                      </div>';

      $print .=     '</div>
                  </div>';

      $buscar8a9 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '08:00' and d.hora <= '09:00')
                        and d.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado8a9 = mysqli_query($getConnection, $buscar8a9);

      while($row8a9 = mysqli_fetch_row($encontrado8a9)){
        $n8a9 = $row8a9[0];
        if($n8a9 > 0){
        $print .= '<div class="colTime text-center text-yellow centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n8a9'.$perid.'">'.$n8a9.'</span>
                  </div>';
        }else{
        $print .= '<div class=" colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n8a9'.$perid.'">'.$n8a9.'</span>
                  </div>';
        }
      }

      $buscar9a10 = "SELECT count(d.docid)
                      FROM doc d
                        left outer join cli c on c.clienteid = d.clienteid
                      where d.fecha = '".$dia."'
                        and (d.hora >= '09:00' and d.hora <= '10:00')
                        and d.vendedorid = ".$perid."
                        and c.vendedor NOT LIKE 'OF'
                        and d.tipo = 'C'";
      $encontrado9a10 = mysqli_query($getConnection, $buscar9a10);

      while($row9a10 = mysqli_fetch_row($encontrado9a10)){
        $n9a10 = $row9a10[0];
        if($n9a10 > 0){
        $print .= '<div class="colTime text-center text-yellow centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n9a10'.$perid.'">'.$n9a10.'</span>
                  </div>';
        }else{
        $print .= '<div class=" colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n9a10'.$perid.'">'.$n9a10.'</span>
                  </div>';
        }
      }

      $buscar10a11 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '10:00' and d.hora <= '11:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado10a11 = mysqli_query($getConnection, $buscar10a11);

      while($row10a11 = mysqli_fetch_row($encontrado10a11)){
        $n10a11 = $row10a11[0];
        if($n10a11 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n10a11'.$perid.'">'.$n10a11.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n10a11'.$perid.'">'.$n10a11.'</span>
                  </div>';
        }
      }

      $buscar11a12 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '11:00' and d.hora <= '12:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado11a12 = mysqli_query($getConnection, $buscar11a12);

      while($row11a12 = mysqli_fetch_row($encontrado11a12)){
        $n11a12 = $row11a12[0];
        if($n11a12 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n11a12'.$perid.'">'.$n11a12.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n11a12'.$perid.'">'.$n11a12.'</span>
                  </div>';
        }
      }

      $buscar12a13 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '12:00' and d.hora <= '13:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado12a13 = mysqli_query($getConnection, $buscar12a13);

      while($row12a13 = mysqli_fetch_row($encontrado12a13)){
        $n12a13 = $row12a13[0];
        if($n12a13 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n12a13'.$perid.'">'.$n12a13.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n12a13'.$perid.'">'.$n12a13.'</span>
                  </div>';
        }
      }

      $buscar13a14 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '13:00' and d.hora <= '14:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado13a14 = mysqli_query($getConnection, $buscar13a14);

      while($row13a14 = mysqli_fetch_row($encontrado13a14)){
        $n13a14 = $row13a14[0];
        if($n13a14 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n13a14'.$perid.'">'.$n13a14.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n13a14'.$perid.'">'.$n13a14.'</span>
                  </div>';
        }
      }

      $buscar14a15 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '14:00' and d.hora <= '15:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado14a15 = mysqli_query($getConnection, $buscar14a15);

      while($row14a15 = mysqli_fetch_row($encontrado14a15)){
        $n14a15 = $row14a15[0];
        if($n14a15 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n14a15'.$perid.'">'.$n14a15.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n14a15'.$perid.'">'.$n14a15.'</span>
                  </div>';
        }
      }

      $buscar15a16 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '15:00' and d.hora <= '16:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado15a16 = mysqli_query($getConnection, $buscar15a16);

      while($row15a16 = mysqli_fetch_row($encontrado15a16)){
        $n15a16 = $row15a16[0];
        if($n15a16 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n15a16'.$perid.'">'.$n15a16.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n15a16'.$perid.'">'.$n15a16.'</span>
                  </div>';
        }
      }

      $buscar16a17 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '16:00' and d.hora <= '17:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado16a17 = mysqli_query($getConnection, $buscar16a17);

      while($row16a17 = mysqli_fetch_row($encontrado16a17)){
        $n16a17 = $row16a17[0];
        if($n16a17 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n16a17'.$perid.'">'.$n16a17.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n16a17'.$perid.'">'.$n16a17.'</span>
                  </div>';
        }
      }

      $buscar17a18 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '17:00' and d.hora <= '18:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado17a18 = mysqli_query($getConnection, $buscar17a18);

      while($row17a18 = mysqli_fetch_row($encontrado17a18)){
        $n17a18 = $row17a18[0];
        if($n17a18 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n17a18'.$perid.'">'.$n17a18.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n17a18'.$perid.'">'.$n17a18.'</span>
                  </div>';
        }
      }

      $buscar18a19 = "SELECT count(d.docid)
                        FROM doc d
                          left outer join cli c on c.clienteid = d.clienteid
                        where d.fecha = '".$dia."'
                          and (d.hora >= '18:00' and d.hora <= '19:00')
                          and d.vendedorid = ".$perid."
                          and c.vendedor NOT LIKE 'OF'
                          and d.tipo = 'C'";
      $encontrado18a19 = mysqli_query($getConnection, $buscar18a19);

      while($row18a19 = mysqli_fetch_row($encontrado18a19)){
        $n18a19 = $row18a19[0];
        if($n18a19 > 0){
        $print .= '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n18a19'.$perid.'">'.$n18a19.'</span>
                  </div>';
        }else{
        $print .= '<div class ="colTime text-center centrar" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span id="n18a19'.$perid.'">'.$n18a19.'</span>
                  </div>';
        }
      }
    }

    $buscarTotalPedDia = "SELECT count(d.docid)
                            FROM doc d
                              left outer join per p on p.perid = d.vendedorid
                              left outer join cli c on c.clienteid = d.clienteid
                            where d.fecha = '".$dia."'
                              and d.tipo = 'C'
                              and c.vendedor NOT LIKE 'OF'
                              and p.sermov = $sermov";
    $enconTotalPedDia = mysqli_query($getConnection, $buscarTotalPedDia);
    $rowTotalPedDia = mysqli_fetch_row($enconTotalPedDia);
    $totalPedDia = $rowTotalPedDia[0];

    $buscarTotalPedDiaVis = "SELECT c.clienteid
                              from doc d
                                left outer join cli c on c.clienteid = d.clienteid
                                left OUTER JOIN per p ON p.perid = D.vendedorid
                              where d.fecha = '".$dia."'
                                and p.sermov = $sermov
                                and d.tipo = 'C'
                                and c.vendedor NOT LIKE 'OF'
                                and c.diavis NOT LIKE '%".$diaVis."%'";
    $enconTotalPedDiaVis = mysqli_query($getConnection, $buscarTotalPedDiaVis);
    $rowTotalPedDiaVis = mysqli_num_rows($enconTotalPedDiaVis);

    // Total por columna
    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border: 1px solid #d7d7d7;">
                    <span>Total</span>
                  </div>';
    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$rowTotalPedDiaVis.'</span>
                  </div>';
    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$totalPedDia.'</span>
                  </div>';

    $totalCol8a9 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '08:00' and doc.hora <= '09:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = $sermov";
    $totalEncontradoCol8a9 = mysqli_query($getConnection, $totalCol8a9);
    $rowTotalCol8a9 = mysqli_fetch_row($totalEncontradoCol8a9);
    $nTotal8a9 = $rowTotalCol8a9[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal8a9.'</span>
                  </div>';

    $totalCol9a10 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '09:00' and doc.hora <= '10:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = $sermov";
    $totalEncontradoCol9a10 = mysqli_query($getConnection, $totalCol9a10);
    $rowTotalCol9a10 = mysqli_fetch_row($totalEncontradoCol9a10);
    $nTotal9a10 = $rowTotalCol9a10[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal9a10.'</span>
                  </div>';

    $totalCol10a11 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '10:00' and doc.hora <= '11:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol10a11 = mysqli_query($getConnection, $totalCol10a11);
    $rowTotalCol10a11 = mysqli_fetch_row($totalEncontradoCol10a11);
    $nTotal10a11 = $rowTotalCol10a11[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal10a11.'</span>
                  </div>';

    $totalCol11a12 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '11:00' and doc.hora <= '12:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol11a12 = mysqli_query($getConnection, $totalCol11a12);
    $rowTotalCol11a12 = mysqli_fetch_row($totalEncontradoCol11a12);
    $nTotal11a12 = $rowTotalCol11a12[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal11a12.'</span>
                  </div>';

    $totalCol12a13 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '12:00' and doc.hora <= '13:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol12a13 = mysqli_query($getConnection, $totalCol12a13);
    $rowTotalCol12a13 = mysqli_fetch_row($totalEncontradoCol12a13);
    $nTotal12a13 = $rowTotalCol12a13[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal12a13.'</span>
                  </div>';

    $totalCol13a14 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '13:00' and doc.hora <= '14:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol13a14 = mysqli_query($getConnection, $totalCol13a14);
    $rowTotalCol13a14 = mysqli_fetch_row($totalEncontradoCol13a14);
    $nTotal13a14 = $rowTotalCol13a14[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal13a14.'</span>
                  </div>';

    $totalCol14a15 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '14:00' and doc.hora <= '15:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol14a15 = mysqli_query($getConnection, $totalCol14a15);
    $rowTotalCol14a15 = mysqli_fetch_row($totalEncontradoCol14a15);
    $nTotal14a15 = $rowTotalCol14a15[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal14a15.'</span>
                  </div>';

    $totalCol15a16 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '15:00' and doc.hora <= '16:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = $sermov";
    $totalEncontradoCol15a16 = mysqli_query($getConnection, $totalCol15a16);
    $rowTotalCol15a16 = mysqli_fetch_row($totalEncontradoCol15a16);
    $nTotal15a16 = $rowTotalCol15a16[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal15a16.'</span>
                  </div>';

    $totalCol16a17 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '16:00' and doc.hora <= '17:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol16a17 = mysqli_query($getConnection, $totalCol16a17);
    $rowTotalCol16a17 = mysqli_fetch_row($totalEncontradoCol16a17);
    $nTotal16a17 = $rowTotalCol16a17[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal16a17.'</span>
                  </div>';

    $totalCol17a18 = "SELECT count(doc.docid)
                        FROM doc
                          LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                          left outer join cli c on c.clienteid = doc.clienteid
                        where doc.fecha ='".$dia."'
                          and (doc.hora >= '17:00' and doc.hora <= '18:00')
                          and doc.tipo = 'C'
                          and c.vendedor NOT LIKE 'OF'
                          and p.sermov = $sermov";
    $totalEncontradoCol17a18 = mysqli_query($getConnection, $totalCol17a18);
    $rowTotalCol17a18 = mysqli_fetch_row($totalEncontradoCol17a18);
    $nTotal17a18 = $rowTotalCol17a18[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal17a18.'</span>
                  </div>';

    $totalCol18a19 = "SELECT count(doc.docid)
                      FROM doc
                        LEFT OUTER JOIN per p ON p.perid = doc.vendedorid
                        left outer join cli c on c.clienteid = doc.clienteid
                      where doc.fecha ='".$dia."'
                        and (doc.hora >= '18:00' and doc.hora <= '19:00')
                        and doc.tipo = 'C'
                        and c.vendedor NOT LIKE 'OF'
                        and p.sermov = $sermov";
    $totalEncontradoCol18a19 = mysqli_query($getConnection, $totalCol18a19);
    $rowTotalCol18a19 = mysqli_fetch_row($totalEncontradoCol18a19);
    $nTotal18a19 = $rowTotalCol18a19[0];

    $print .=     '<div class ="colTime text-center centrar text-yellow" style="border-right: 1px solid #d7d7d7;border-bottom: 1px solid #d7d7d7">
                    <span>'.$nTotal18a19.'</span>
                  </div>';

    $print .=   '</div>
              </div>';
    }

    echo $print;

    $getConnection->close();
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

  private function getDashBoardCartera($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $dia  = date("Y-m-d");
    $month = date('m');
    $year = date('Y');

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
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                    JOIN per p ON p.perid = d.vendedorid
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

    //Facturas Vencidas al mes
    $fechaInicioVenc = date('Y-m-01');
    $fechaFinalVenc = date('Y-m-'.$diasTotalMes.'');
    $numVenFac = "SELECT vence
                    FROM doc
                    WHERE totalpagado < total
                      AND feccan = 0
                      AND tipo = 'F'
                      AND vence < '$dia'
                      AND (
                            feccap < '$fechaFinalVenc'
                            AND feccap > '$fechaInicioVenc'
                          )";

    $venFac = mysqli_query($getConnection, $numVenFac);
    $numeroVecesFacVenc = mysqli_num_rows($venFac);

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT hg910 centrar text-center">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                      <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                      <img src="../img/barrafmo2.gif" width="200"/>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <h4 class="h4">FACTURAS VENCIDAS AL MES</h4>
                    <p class="lead text-tomato" style="font-size: 1.7em !important;">'.$numeroVecesFacVenc.'</p>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <h4 class="h4">CARTERA VENCIDA</h4>
                    <p class="lead text-tomato" style="font-size: 1.7em !important;">$ '.$Morosidad.'</p>
                  </div>
                </div>
              </div>';
    echo $print;
    $getConnection->close();
  }

  private function getOutPipes($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $codigo        = $paramDb->SecureInput($params["codigo"]);

    $print =  '<div class="container paddingT paddingB">
                <div class="row">
                  <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                    <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                    <img src="../img/barrafmo2.gif" width="200"/>
                  </div>
                  <div class="col-12 text-center">
                    <h1 class="display-4">CODIGOS <span class="text-tomato">ESPECIALES</span></h1>
                  </div>
                  <div class="col-12">
                    <div class="form-group">
                      <input style="text-align: center;" type="number" class="form-control" id="codigo" value="'.$codigo.'" readonly >
                    </div>
                    <div class="form-group">
                      <input style="text-align: center;" class="form-control" id="vendedor" type="text" placeholder="Ingrese el vendedor" required >
                    </div>
                    <div class="form-group">
                      <input style="text-align: center;" class="form-control" id="cliente" type="number" placeholder="Ingrese el cliente" required >
                    </div>
                    <div class="form-group">
                      <input style="text-align: center;" class="form-control" id="cantidad" type="text" placeholder="Ingrese la cantidad a solicitar" required >
                    </div>
                    <div class="form-group">
                      <select class="form-control" id="almacen" required>
                        <option value="">Seleccione un almacén de salida</option>
                        <option value="1">Querétaro</option>
                        <option value="2">Tequisquiapan</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <input type="button" class="btn btn-primary btn-lg btn-block" onClick="guardarPedInv();" value="APARTAR">
                    </div>
                  </div>
                </div>
              </div>';

    echo $print;

    $mysqliCon->close();
  }

  private function getDashBoardAlamcen($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $dia  = date("Y-m-d");
    $month = date('m');
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

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT centrar text-center">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB table-responsive-sm">
                        <p style="display:none;">Almacén</p>
                        <h4 class="display-1">PEDIDOS</h4>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col" style="font-size: 2em !important;">POR SURTIR</th>
                              <th scope="col" style="font-size: 2em !important;">POR BAJAR</th>
                              <th scope="col" style="font-size: 2em !important;">FACTURADO</th>
                              <th scope="col" style="font-size: 2em !important;">CANCELADOS</th>
                              <th scope="col" style="font-size: 2em !important;">TOTAL</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td style="font-size: 10em !important;"><span id="porsurtir">-</span></td>
                              <td style="font-size: 10em !important;"><span id="porbajar">-</span></td>
                              <td style="font-size: 10em !important;"><span id="porfactura">-</span></td>
                              <td style="font-size: 10em !important;"><span id="porCancelacion">-</span></td>
                              <td style="font-size: 10em !important;"><span id="totalpedidodia">-</span></td>
                            </tr>
                          </tbody>
                        </table>
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col"></th>
                              <th scope="col">8:00-9:00</th>
                              <th scope="col">9:00-10:00</th>
                              <th scope="col">10:00-11:00</th>
                              <th scope="col">11:00-12:00</th>
                              <th scope="col">12:00-13:00</th>
                              <th scope="col">13:00-14:00</th>
                              <th scope="col">14:00-15:00</th>
                              <th scope="col">15:00-16:00</th>
                              <th scope="col">16:00-17:00</th>
                              <th scope="col">17:00-18:00</th>
                              <th scope="col">18:00-19:00</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Cotizados</td>
                              <td><span id="totalct8a9">-</span></td>
                              <td><span id="totalct9a10">-</span></td>
                              <td><span id="totalct10a11">-</span></td>
                              <td><span id="totalct11a12">-</span></td>
                              <td><span id="totalct12a13">-</span></td>
                              <td><span id="totalct13a14">-</span></td>
                              <td><span id="totalct14a15">-</span></td>
                              <td><span id="totalct15a16">-</span></td>
                              <td><span id="totalct16a17">-</span></td>
                              <td><span id="totalct17a18">-</span></td>
                              <td><span id="totalct18a19">-</span></td>
                            </tr>
                            <tr>
                              <td>Facturados</td>
                              <td><span id="total8a9">-</span></td>
                              <td><span id="total9a10">-</span></td>
                              <td><span id="total10a11">-</span></td>
                              <td><span id="total11a12">-</span></td>
                              <td><span id="total12a13">-</span></td>
                              <td><span id="total13a14">-</span></td>
                              <td><span id="total14a15">-</span></td>
                              <td><span id="total15a16">-</span></td>
                              <td><span id="total16a17">-</span></td>
                              <td><span id="total17a18">-</span></td>
                              <td><span id="total18a19">-</span></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <a class="btn btn-secondary btn-enl btn-block" href="javascript:location.reload(true)">Borrar Historial</a>
                      </div>
                    </div>
                  </div>
                  <script type="text/javascript">
                    var f=new Date();
                    var hora=f.getHours();

                    if(hora < 21 && hora > 7){
                      $(document).ready(function() {
                        console.log("Sistema Abierto");

                        function cambiarColorTotal(numero){
                          var d = new Date();
                          var horaCambio = d.getHours();
                          var mediaCambio = d.getMinutes();
                          var tiempo = horaCambio+":"+mediaCambio;

                          var total = parseFloat(numero);
                          var res = (total * 100) / 350;

                          if(tiempo > "17:29" && tiempo < "18:30"){
                            if(res < 75){
                              $("#totalpedidodia").addClass("alertaRoja");
                            }

                            if(res < 81){
                              $("#totalpedidodia").addClass("alertaAmarillo");
                            }

                            if(res > 80){
                              $("#totalpedidodia").addClass("alertaVerde");
                            }
                          }

                          if(tiempo > "18:29" && tiempo < "20:01"){
                            if(res < 85){
                              $("#totalpedidodia").addClass("alertaRoja");
                            }

                            if(res < 91){
                              $("#totalpedidodia").addClass("alertaAmarillo");
                            }

                            if(res > 90){
                              $("#totalpedidodia").addClass("alertaVerde");
                            }
                          }
                        }

                        function pedidosDia(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodia.php",
                            success: function(pedido) {
                              $("#totalpedidodia").text(pedido);
                              var numero = parseInt(pedido);
                              cambiarColorTotal(numero);
                            }
                          });
                        }
                        setInterval(pedidosDia, 2000);

                        function cambiarColorSurtir(numero){
                          var surtir = parseFloat(numero);
                          var res = (surtir * 100) / 350;

                          var d = new Date();
                          var horaCambio = d.getHours();
                          var mediaCambio = d.getMinutes();
                          var tiempo = horaCambio+":"+mediaCambio;

                          if(res > 20){
                            var bandera = res;
                          }

                          if(typeof(bandera) === "undefined"){
                            // console.log("Por Surtir(","Hora:", tiempo, " Porcentaje: ", res, "% )");
                          }else{
                            // console.log("Por Surtir(","Hora:", tiempo, " Porcentaje: ", res, "%  Bandera: ", bandera, ")");
                          }
                          
                          if(tiempo > "17:29" && tiempo < "18:30"){
                            if(res < 24){
                              $("#porsurtir").addClass("alertaVerde");
                            }

                            if(res > 25){
                              $("#porsurtir").addClass("alertaAmarillo");
                            }

                            if(res > 30){
                              $("#porsurtir").addClass("alertaRoja");
                            }
                          }

                          if(tiempo > "18:29" && tiempo < "20:01"){
                            if(tiempo > "18:59" && bandera > 20){
                              $("#porsurtir").addClass("alertaRoja");
                            }else{
                              if(res < 16){
                                $("#porsurtir").addClass("alertaVerde");
                              }

                              if(res > 15){
                                $("#porsurtir").addClass("alertaAmarillo");
                              }

                              if(res > 20){
                                $("#porsurtir").addClass("alertaRoja");
                              }
                            }
                          }
                        }

                        function pedidosDiaSurtir(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasurtir.php",
                            success: function(pedidoSurtir) {
                              $("#porsurtir").text(pedidoSurtir);
                              var numero = parseInt(pedidoSurtir);
                              cambiarColorSurtir(numero);
                            }
                          });
                        }
                        setInterval(pedidosDiaSurtir, 2000);

                        function pedidosDiaBajar(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiabajar.php",
                            success: function(pedidoBajar) {
                              $("#porbajar").text(pedidoBajar);
                              // $("#porbajar").addClass("aviso");
                              // console.log("Bajar: ", pedidoBajar);
                            }
                          });
                        }
                        setInterval(pedidosDiaBajar, 2000);
                        
                        function pedidosDiaFactura(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiafacturar.php",
                            success: function(pedidoFactura) {
                              $("#porfactura").text(pedidoFactura);
                              // $("#porfactura").addClass("aviso");
                              // console.log("Facturadas: ", pedidoFactura);
                            }
                          });
                        }
                        setInterval(pedidosDiaFactura, 2000);

                        function pedidosDiaCancelacion(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiacancelacion.php",
                            success: function(pedidoCancelacion) {
                              $("#porCancelacion").text(pedidoCancelacion);
                              // $("#porCancelacion").addClass("aviso");
                              // console.log("Cancelaciondas: ", pedidoCancelacion);
                            }
                          });
                        }
                        setInterval(pedidosDiaCancelacion, 2000);

                        // Colocar funciones de pedidos cotizados totales por hora

                        function totalct8a9(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct8a9.php",
                            success: function(tct8a9) {
                              if(tct8a9 > 0){
                                $("#totalct8a9").text(tct8a9);
                                $("#totalct8a9").addClass("text-green");
                              }else{
                                $("#totalct8a9").text(tct8a9);
                              }
                            }
                          });
                        }
                        setInterval(totalct8a9, 2000);
                        
                        function totalct9a10(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct9a10.php",
                            success: function(tct9a10) {
                              if(tct9a10 > 0){
                                $("#totalct9a10").text(tct9a10);
                                $("#totalct9a10").addClass("text-green");
                              }else{
                                $("#totalct9a10").text(tct9a10);
                              }
                            }
                          });
                        }
                        setInterval(totalct9a10, 2000);

                        function totalct10a11(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct10a11.php",
                            success: function(tct10a11) {
                              if(tct10a11 > 0){
                                $("#totalct10a11").text(tct10a11);
                                $("#totalct10a11").addClass("text-green");
                              }else{
                                $("#totalct10a11").text(tct10a11);
                              }
                            }
                          });
                        }
                        setInterval(totalct10a11, 2000);

                        function totalct11a12(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct11a12.php",
                            success: function(tct11a12) {
                              if(tct11a12 > 0){
                                $("#totalct11a12").text(tct11a12);
                                $("#totalct11a12").addClass("text-green");
                              }else{
                                $("#totalct11a12").text(tct11a12);
                              }
                            }
                          });
                        }
                        setInterval(totalct11a12, 2000);

                        function totalct12a13(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct12a13.php",
                            success: function(tct12a13) {
                              if(tct12a13 > 0){
                                $("#totalct12a13").text(tct12a13);
                                $("#totalct12a13").addClass("text-green");
                              }else{
                                $("#totalct12a13").text(tct12a13);
                              }
                            }
                          });
                        }
                        setInterval(totalct12a13, 2000);

                        function totalct13a14(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct13a14.php",
                            success: function(tct13a14) {
                              if(tct13a14 > 0){
                                $("#totalct13a14").text(tct13a14);
                                $("#totalct13a14").addClass("text-green");
                              }else{
                                $("#totalct13a14").text(tct13a14);
                              }
                            }
                          });
                        }
                        setInterval(totalct13a14, 2000);

                        function totalct14a15(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct14a15.php",
                            success: function(tct14a15) {
                              if(tct14a15 > 0){
                                $("#totalct14a15").text(tct14a15);
                                $("#totalct14a15").addClass("text-green");
                              }else{
                                $("#totalct14a15").text(tct14a15);
                              }
                            }
                          });
                        }
                        setInterval(totalct14a15, 2000);

                        function totalct15a16(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct15a16.php",
                            success: function(tct15a16) {
                              if(tct15a16 > 0){
                                $("#totalct15a16").text(tct15a16);
                                $("#totalct15a16").addClass("text-green");
                              }else{
                                $("#totalct15a16").text(tct15a16);
                              }
                            }
                          });
                        }
                        setInterval(totalct15a16, 2000);

                        function totalct16a17(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct16a17.php",
                            success: function(tct16a17) {
                              if(tct16a17 > 0){
                                $("#totalct16a17").text(tct16a17);
                                $("#totalct16a17").addClass("text-green");
                              }else{
                                $("#totalct16a17").text(tct16a17);
                              }
                            }
                          });
                        }
                        setInterval(totalct16a17, 2000);

                        function totalct17a18(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct17a18.php",
                            success: function(tct17a18) {
                              if(tct17a18 > 0){
                                $("#totalct17a18").text(tct17a18);
                                $("#totalct17a18").addClass("text-green");
                              }else{
                                $("#totalct17a18").text(tct17a18);
                              }
                            }
                          });
                        }
                        setInterval(totalct17a18, 2000);

                        function totalct18a19(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct18a19.php",
                            success: function(tct18a19) {
                              if(tct18a19 > 0){
                                $("#totalct18a19").text(tct18a19);
                                $("#totalct18a19").addClass("text-green");
                              }else{
                                $("#totalct18a19").text(tct18a19);
                              }
                            }
                          });
                        }
                        setInterval(totalct18a19, 2000);

                        // Colocar funciones de pedidos totales por hora

                        function total8a9(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total8a9.php",
                            success: function(t8a9) {
                              if(t8a9 > 0){
                                $("#total8a9").text(t8a9);
                                $("#total8a9").addClass("text-green");
                              }else{
                                $("#total8a9").text(t8a9);
                              }
                            }
                          });
                        }
                        setInterval(total8a9, 2000);
                        
                        function total9a10(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total9a10.php",
                            success: function(t9a10) {
                              if(t9a10 > 0){
                                $("#total9a10").text(t9a10);
                                $("#total9a10").addClass("text-green");
                              }else{
                                $("#total9a10").text(t9a10);
                              }
                            }
                          });
                        }
                        setInterval(total9a10, 2000);

                        function total10a11(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total10a11.php",
                            success: function(t10a11) {
                              if(t10a11 > 0){
                                $("#total10a11").text(t10a11);
                                $("#total10a11").addClass("text-green");
                              }else{
                                $("#total10a11").text(t10a11);
                              }
                            }
                          });
                        }
                        setInterval(total10a11, 2000);

                        function total11a12(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total11a12.php",
                            success: function(t11a12) {
                              if(t11a12 > 0){
                                $("#total11a12").text(t11a12);
                                $("#total11a12").addClass("text-green");
                              }else{
                                $("#total11a12").text(t11a12);
                              }
                            }
                          });
                        }
                        setInterval(total11a12, 2000);

                        function total12a13(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total12a13.php",
                            success: function(t12a13) {
                              if(t12a13 > 0){
                                $("#total12a13").text(t12a13);
                                $("#total12a13").addClass("text-green");
                              }else{
                                $("#total12a13").text(t12a13);
                              }
                            }
                          });
                        }
                        setInterval(total12a13, 2000);

                        function total13a14(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total13a14.php",
                            success: function(t13a14) {
                              if(t13a14 > 0){
                                $("#total13a14").text(t13a14);
                                $("#total13a14").addClass("text-green");
                              }else{
                                $("#total13a14").text(t13a14);
                              }
                            }
                          });
                        }
                        setInterval(total13a14, 2000);

                        function total14a15(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total14a15.php",
                            success: function(t14a15) {
                              if(t14a15 > 0){
                                $("#total14a15").text(t14a15);
                                $("#total14a15").addClass("text-green");
                              }else{
                                $("#total14a15").text(t14a15);
                              }
                            }
                          });
                        }
                        setInterval(total14a15, 2000);

                        function total15a16(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total15a16.php",
                            success: function(t15a16) {
                              if(t15a16 > 0){
                                $("#total15a16").text(t15a16);
                                $("#total15a16").addClass("text-green");
                              }else{
                                $("#total15a16").text(t15a16);
                              }
                            }
                          });
                        }
                        setInterval(total15a16, 2000);

                        function total16a17(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total16a17.php",
                            success: function(t16a17) {
                              if(t16a17 > 0){
                                $("#total16a17").text(t16a17);
                                $("#total16a17").addClass("text-green");
                              }else{
                                $("#total16a17").text(t16a17);
                              }
                            }
                          });
                        }
                        setInterval(total16a17, 2000);

                        function total17a18(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total17a18.php",
                            success: function(t17a18) {
                              if(t17a18 > 0){
                                $("#total17a18").text(t17a18);
                                $("#total17a18").addClass("text-green");
                              }else{
                                $("#total17a18").text(t17a18);
                              }
                            }
                          });
                        }
                        setInterval(total17a18, 2000);

                        function total18a19(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total18a19.php",
                            success: function(t18a19) {
                              if(t18a19 > 0){
                                $("#total18a19").text(t18a19);
                                $("#total18a19").addClass("text-green");
                              }else{
                                $("#total18a19").text(t18a19);
                              }
                            }
                          });
                        }
                        setInterval(total18a19, 2000);
                      });
                    } else {
                      console.log("Fuera de Línea");
                      $(document).ready(function() {	
                        function pedidosDia(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodia.php",
                            success: function(pedido) {
                              $("#totalpedidodia").text(pedido);
                              // $("#totalpedidodia").addClass("aviso");
                              // console.log("No. Pedidos: ",pedido);
                            }
                          });
                        }
                        setTimeout(pedidosDia, 1000);

                        function pedidosDiaTotal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiatotal.php",
                            success: function(pedidoTotal) {
                              $("#totalPed").text(pedidoTotal);
                              // $("#totalPed").addClass("aviso");
                              // console.log("Total Pedidos Total: ", pedidoTotal);
                            }
                          });
                        }
                        setTimeout(pedidosDiaTotal, 1000);

                        function pedidosDiaSurtir(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasurtir.php",
                            success: function(pedidoSurtir) {
                              $("#porsurtir").text(pedidoSurtir);
                              // $("#porsurtir").addClass("aviso");
                              // console.log("Surti: ", pedidoSurtir);
                            }
                          });
                        }
                        setTimeout(pedidosDiaSurtir, 1000);

                        function pedidosDiaBajar(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiabajar.php",
                            success: function(pedidoBajar) {
                              $("#porbajar").text(pedidoBajar);
                              // $("#porbajar").addClass("aviso");
                              // console.log("Bajar: ", pedidoBajar);
                            }
                          });
                        }
                        setTimeout(pedidosDiaBajar, 1000);
                        
                        function pedidosDiaFactura(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiafacturar.php",
                            success: function(pedidoFactura) {
                              $("#porfactura").text(pedidoFactura);
                              // $("#porfactura").addClass("aviso");
                              // console.log("Facturadas: ", pedidoFactura);
                            }
                          });
                        }
                        setTimeout(pedidosDiaFactura, 1000);

                        function pedidosDiaCancelacion(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiacancelacion.php",
                            success: function(pedidoCancelacion) {
                              $("#porCancelacion").text(pedidoCancelacion);
                              // $("#porCancelacion").addClass("aviso");
                              // console.log("Cancelaciondas: ", pedidoCancelacion);
                            }
                          });
                        }
                        setTimeout(pedidosDiaCancelacion, 1000);

                        // Colocar funciones de pedidos cotizados totales por hora

                        function totalct8a9(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct8a9.php",
                            success: function(tct8a9) {
                              if(tct8a9 > 0){
                                $("#totalct8a9").text(tct8a9);
                                $("#totalct8a9").addClass("text-green");
                              }else{
                                $("#totalct8a9").text(tct8a9);
                              }
                            }
                          });
                        }
                        setTimeout(totalct8a9, 1000);
                        
                        function totalct9a10(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct9a10.php",
                            success: function(tct9a10) {
                              if(tct9a10 > 0){
                                $("#totalct9a10").text(tct9a10);
                                $("#totalct9a10").addClass("text-green");
                              }else{
                                $("#totalct9a10").text(tct9a10);
                              }
                            }
                          });
                        }
                        setTimeout(totalct9a10, 1000);

                        function totalct10a11(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct10a11.php",
                            success: function(tct10a11) {
                              if(tct10a11 > 0){
                                $("#totalct10a11").text(tct10a11);
                                $("#totalct10a11").addClass("text-green");
                              }else{
                                $("#totalct10a11").text(tct10a11);
                              }
                            }
                          });
                        }
                        setTimeout(totalct10a11, 1000);

                        function totalct11a12(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct11a12.php",
                            success: function(tct11a12) {
                              if(tct11a12 > 0){
                                $("#totalct11a12").text(tct11a12);
                                $("#totalct11a12").addClass("text-green");
                              }else{
                                $("#totalct11a12").text(tct11a12);
                              }
                            }
                          });
                        }
                        setTimeout(totalct11a12, 1000);

                        function totalct12a13(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct12a13.php",
                            success: function(tct12a13) {
                              if(tct12a13 > 0){
                                $("#totalct12a13").text(tct12a13);
                                $("#totalct12a13").addClass("text-green");
                              }else{
                                $("#totalct12a13").text(tct12a13);
                              }
                            }
                          });
                        }
                        setTimeout(totalct12a13, 1000);

                        function totalct13a14(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct13a14.php",
                            success: function(tct13a14) {
                              if(tct13a14 > 0){
                                $("#totalct13a14").text(tct13a14);
                                $("#totalct13a14").addClass("text-green");
                              }else{
                                $("#totalct13a14").text(tct13a14);
                              }
                            }
                          });
                        }
                        setTimeout(totalct13a14, 1000);

                        function totalct14a15(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct14a15.php",
                            success: function(tct14a15) {
                              if(tct14a15 > 0){
                                $("#totalct14a15").text(tct14a15);
                                $("#totalct14a15").addClass("text-green");
                              }else{
                                $("#totalct14a15").text(tct14a15);
                              }
                            }
                          });
                        }
                        setTimeout(totalct14a15, 1000);

                        function totalct15a16(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct15a16.php",
                            success: function(tct15a16) {
                              if(tct15a16 > 0){
                                $("#totalct15a16").text(tct15a16);
                                $("#totalct15a16").addClass("text-green");
                              }else{
                                $("#totalct15a16").text(tct15a16);
                              }
                            }
                          });
                        }
                        setTimeout(totalct15a16, 1000);

                        function totalct16a17(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct16a17.php",
                            success: function(tct16a17) {
                              if(tct16a17 > 0){
                                $("#totalct16a17").text(tct16a17);
                                $("#totalct16a17").addClass("text-green");
                              }else{
                                $("#totalct16a17").text(tct16a17);
                              }
                            }
                          });
                        }
                        setTimeout(totalct16a17, 1000);

                        function totalct17a18(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct17a18.php",
                            success: function(tct17a18) {
                              if(tct17a18 > 0){
                                $("#totalct17a18").text(tct17a18);
                                $("#totalct17a18").addClass("text-green");
                              }else{
                                $("#totalct17a18").text(tct17a18);
                              }
                            }
                          });
                        }
                        setTimeout(totalct17a18, 1000);

                        function totalct18a19(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/totalct18a19.php",
                            success: function(tct18a19) {
                              if(tct18a19 > 0){
                                $("#totalct18a19").text(tct18a19);
                                $("#totalct18a19").addClass("text-green");
                              }else{
                                $("#totalct18a19").text(tct18a19);
                              }
                            }
                          });
                        }
                        setTimeout(totalct18a19, 1000);

                        // Colocar funciones de pedidos totales por hora

                        function total8a9(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total8a9.php",
                            success: function(t8a9) {
                              if(t8a9 > 0){
                                $("#total8a9").text(t8a9);
                                $("#total8a9").addClass("text-green");
                              }else{
                                $("#total8a9").text(t8a9);
                              }
                            }
                          });
                        }
                        setTimeout(total8a9, 1000);
                        
                        function total9a10(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total9a10.php",
                            success: function(t9a10) {
                              if(t9a10 > 0){
                                $("#total9a10").text(t9a10);
                                $("#total9a10").addClass("text-green");
                              }else{
                                $("#total9a10").text(t9a10);
                              }
                            }
                          });
                        }
                        setTimeout(total9a10, 1000);

                        function total10a11(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total10a11.php",
                            success: function(t10a11) {
                              if(t10a11 > 0){
                                $("#total10a11").text(t10a11);
                                $("#total10a11").addClass("text-green");
                              }else{
                                $("#total10a11").text(t10a11);
                              }
                            }
                          });
                        }
                        setTimeout(total10a11, 1000);

                        function total11a12(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total11a12.php",
                            success: function(t11a12) {
                              if(t11a12 > 0){
                                $("#total11a12").text(t11a12);
                                $("#total11a12").addClass("text-green");
                              }else{
                                $("#total11a12").text(t11a12);
                              }
                            }
                          });
                        }
                        setTimeout(total11a12, 1000);

                        function total12a13(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total12a13.php",
                            success: function(t12a13) {
                              if(t12a13 > 0){
                                $("#total12a13").text(t12a13);
                                $("#total12a13").addClass("text-green");
                              }else{
                                $("#total12a13").text(t12a13);
                              }
                            }
                          });
                        }
                        setTimeout(total12a13, 1000);

                        function total13a14(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total13a14.php",
                            success: function(t13a14) {
                              if(t13a14 > 0){
                                $("#total13a14").text(t13a14);
                                $("#total13a14").addClass("text-green");
                              }else{
                                $("#total13a14").text(t13a14);
                              }
                            }
                          });
                        }
                        setTimeout(total13a14, 1000);

                        function total14a15(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total14a15.php",
                            success: function(t14a15) {
                              if(t14a15 > 0){
                                $("#total14a15").text(t14a15);
                                $("#total14a15").addClass("text-green");
                              }else{
                                $("#total14a15").text(t14a15);
                              }
                            }
                          });
                        }
                        setTimeout(total14a15, 1000);

                        function total15a16(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total15a16.php",
                            success: function(t15a16) {
                              if(t15a16 > 0){
                                $("#total15a16").text(t15a16);
                                $("#total15a16").addClass("text-green");
                              }else{
                                $("#total15a16").text(t15a16);
                              }
                            }
                          });
                        }
                        setTimeout(total15a16, 1000);

                        function total16a17(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total16a17.php",
                            success: function(t16a17) {
                              if(t16a17 > 0){
                                $("#total16a17").text(t16a17);
                                $("#total16a17").addClass("text-green");
                              }else{
                                $("#total16a17").text(t16a17);
                              }
                            }
                          });
                        }
                        setTimeout(total16a17, 1000);

                        function total17a18(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total17a18.php",
                            success: function(t17a18) {
                              if(t17a18 > 0){
                                $("#total17a18").text(t17a18);
                                $("#total17a18").addClass("text-green");
                              }else{
                                $("#total17a18").text(t17a18);
                              }
                            }
                          });
                        }
                        setTimeout(total17a18, 1000);

                        function total18a19(){
                          $.ajax({
                            type: "POST",
                            url: "../php/atiempo/total18a19.php",
                            success: function(t18a19) {
                              if(t18a19 > 0){
                                $("#total18a19").text(t18a19);
                                $("#total18a19").addClass("text-green");
                              }else{
                                $("#total18a19").text(t18a19);
                              }
                            }
                          });
                        }
                        setTimeout(total18a19, 1000);
                      });
                    }
                  </script>
                </div>
              </div>';

    echo $print;

    $getConnection->close();
  }

  private function getDashBoardDirIndex($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    //Se hace la busqueda de pedidos y ventas totales del Dia
    $dia  = date("Y-m-d");

    // $queryPedDia = "SELECT COUNT(docid) AS Pedidos, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS SubtotalPed, SUM((SELECT (impuesto) FROM DUAL)) AS ImpuestoPed, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1+impuesto) FROM DUAL)) AS TotalPed
    //                         FROM doc
    //                       WHERE fecha = '$dia'
    //                         AND tipo = 'C'";
    // $resultQueryDia = $getConnection->query($queryPedDia);
    // $qPedDia = mysqli_fetch_array($resultQueryDia);
    // if($qPedDia === NULL){
    //   $totalPed = 0;
    //   $sumPed = 0;
    //   $ivaP = 0;
    //   $totalP = 0;
    // } else {
    //   //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    //   $totalPed = $qPedDia["Pedidos"];
    //   $sumP = (float)$qPedDia["SubtotalPed"];
    //   $sumPed = "$ ".number_format($qPedDia["SubtotalPed"], 2, '.',',');
    //   $ivaP = "$ ".number_format($qPedDia["ImpuestoPed"], 2, '.',',');
    //   $totalP = "$ ".number_format($qPedDia["TotalPed"], 2, '.',',');
    // }

    // Pedidos y ventas por Surtir del día
    // $queryPedDiaSurtir = "SELECT COUNT(docid) AS PedidosSurtir, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedSurtir
    //                         FROM doc
    //                       WHERE fecha = '$dia'
    //                         AND tipo = 'N'
    //                         AND serie NOT LIKE 'CH'
    //                         AND subtotal2 > 0
    //                         AND FECCAN = 0";
    // $resultQueryDiaSurtir = $getConnection->query($queryPedDiaSurtir);
    // $qPedDiaSurtir = mysqli_fetch_array($resultQueryDiaSurtir);
    // if($qPedDiaSurtir === NULL){
    //   $PedSurt = 0;
    //   $sumSurt = 0;
    // } else {
    //   //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    //   $PedSurt = $qPedDiaSurtir["PedidosSurtir"];
    //   $SumSurNs = $qPedDiaSurtir["TotalPedSurtir"];
    //   $sumSurt = "$ ".number_format($qPedDiaSurtir["TotalPedSurtir"], 2, '.',',');
    // }

    // Pedidos y ventas por Bajar del día
    // $queryPedDiaBajar = "SELECT COUNT(docid) AS PedidosBajar, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedBajar
    //                         FROM doc
    //                       WHERE fecha = '$dia'
    //                         AND tipo = 'C'
    //                         AND serie NOT LIKE 'CH'
    //                         AND subtotal2 > 0
    //                         AND FECCAN = 0
    //                         AND estado NOT LIKE 'C'";
    // $resultQueryDiaBajar = $getConnection->query($queryPedDiaBajar);
    // $qPedDiaBajar = mysqli_fetch_array($resultQueryDiaBajar);
    // if($qPedDiaBajar === NULL){
    //   $PedBajar = 0;
    //   $sumBajar = 0;
    // } else {
    //   //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    //   $PedBajar = $qPedDiaBajar["PedidosBajar"];
    //   $SumaBajNs = $qPedDiaBajar["TotalPedBajar"];
    //   $sumBajar = "$ ".number_format($qPedDiaBajar["TotalPedBajar"], 2, '.',',');
    // }

    // Pedidos y ventas por Factura del día
    // $queryPedDiaFactura = "SELECT COUNT(docid) AS PedidosFactura, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedFactura
    //                         FROM doc d
    //                         WHERE d.fecha = '$dia'
    //                           AND tipo = 'F'
    //                           AND serie NOT LIKE 'CH'
    //                           AND d.subtotal2 > 0
    //                           AND d.FECCAN = 0";
    // $resultQueryDiaFactura = $getConnection->query($queryPedDiaFactura);
    // $qPedDiaFactura = mysqli_fetch_array($resultQueryDiaFactura);
    // if($qPedDiaFactura === NULL){
    //   $PedFactura = 0;
    //   $sumFactura = 0;
    // } else {
    //   //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    //   $PedFactura = $qPedDiaFactura["PedidosFactura"];
    //   $sumFacNS = $qPedDiaFactura["TotalPedFactura"];
    //   $sumFactura = "$ ".number_format($qPedDiaFactura["TotalPedFactura"], 2, '.',',');
    // }

    //Pedidos y ventas por Cancelados del día
    // $queryPedDiaCancelacion = "SELECT docid
    //                         FROM doc
    //                         WHERE fecha = '$dia'
    //                           AND estado = 'C'";
    // $resultQueryDiaCancelacion = $getConnection->query($queryPedDiaCancelacion);
    // $numPedDiaCancelacion = mysqli_num_rows($resultQueryDiaCancelacion);
    // if($numPedDiaCancelacion === NULL){
    //   $PedCancelacion = 0;
    //   $sumCancelacion = 0;
    // } else {
    //   $sumCan = 0;
    //   while($qPedDiaCancelacion = mysqli_fetch_array($resultQueryDiaCancelacion)){
    //     $docid = $qPedDiaCancelacion[0];
    //     $buscarPartidasCanceladas = "SELECT sum(desventa * descantidad) as SumPedCan
    //                                   FROM des
    //                                   where descantidad > 0
    //                                   and desfecha = '$dia'
    //                                   and desdocid = $docid";
    //     $resultPartidasCanceladas = $getConnection->query($buscarPartidasCanceladas);
    //     $PartidasCanceladas = mysqli_fetch_array($resultPartidasCanceladas);
    //     $sumCan = $sumCan + $PartidasCanceladas["SumPedCan"];
    //   }
    //   $sumCancelacion = '$ '.number_format($sumCan, 2, '.',',');
    //   $PedCancelacion = $numPedDiaCancelacion;
    // }

    //Sacamos el % de Nivel de Servicio General
    // $VentasDiaNs = $sumFacNS + $SumaBajNs + $SumSurNs + $sumCan;
    // $VentasDiaNs = $sumFacNS + $SumaBajNs + $SumSurNs;
    // $divisionVDN = $VentasDiaNs * 100;
    // $ns = bcdiv($divisionVDN,$sumP,2);

    //Se hace la busqueda de ventas totales del Mes
    $month = date('m');
    // $year = date('Y');
    // $dayVtaTotMes = date("d", mktime(0,0,0, $month+1, 0, $year));
    // $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    // $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $dayVtaTotMes, $year));
    //RESOLVER LAS VENTAS TOTALES MES
    // $queryVtaMes = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS Total
    //                         FROM doc
    //                       WHERE (
    //                               fecha <= '$ultimoDiaMes'
    //                               AND fecha >= '$primerDiaMes' 
    //                             )
    //                         AND tipo = 'F'
    //                         AND serie NOT LIKE 'CH'";
    // $resultQueryMes = $getConnection->query($queryVtaMes);
    // $qVtaMes = mysqli_fetch_array($resultQueryMes);
    // if($qVtaMes === NULL){
    //   $totalVentaMes = 0;
    // } else {
    //   $totalVentaMes = $qVtaMes['Total'];
    // }
    // $formatTotalVentaMes = number_format($totalVentaMes, 2, '.',',');

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
    $queryVtaTotalDiaAnt = "SELECT SUM(SUBTOTAL2) AS total
                                  FROM doc
                                  WHERE fecha >= '".$fecAnteIni."'
                                    AND fecha <= '".$fecAnteFin."'
                                    AND tipo = 'F'
                                    AND serie NOT LIKE 'CH'";
    $resultQueryvtdAnt = $getConnection->query($queryVtaTotalDiaAnt);
    $qVtDAnt = mysqli_fetch_array($resultQueryvtdAnt);
    if($qVtDAnt === NULL){
      $vAnt = $qVtDAnt['total'];
    } else {
      $vAnt = $qVtDAnt['total'];
    }
    $mesAnteriorTo = number_format($vAnt, 2, ".", ",");

    //Se hace la busqueda de ventas totales del Mes Zona 1
    // $queryVtaMesZona1 = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Zona1
    //                       FROM doc d
    //                         JOIN per p ON p.perid = d.vendedorid
    //                       WHERE d.tipo = 'F'
    //                         AND d.serie NOT LIKE 'CH'
    //                         AND p.sermov = 1
    //                         AND (
    //                               d.fecha <= '$ultimoDiaMes'
    //                               AND d.fecha >= '$primerDiaMes' 
    //                             )";
    // $resultQueryMesZona1 = $getConnection->query($queryVtaMesZona1);
    // $qVtaMesZona1 = mysqli_fetch_array($resultQueryMesZona1);
    // if($qVtaMesZona1 === NULL){
    //   $totalVentaMesZona1 = 0;
    // } else {
    //   $totalVentaMesZona1 = $qVtaMesZona1['Zona1'];
    // }
    // $formatTotalVentaMesZona1 = number_format($totalVentaMesZona1, 2, '.',',');

    //Se hace la busqueda de ventas totales del Mes Zona 2
    // $queryVtaMesZona2 = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Zona2
    //                       FROM doc d
    //                         JOIN per p ON p.perid = d.vendedorid
    //                       WHERE d.tipo = 'F'
    //                         AND d.serie NOT LIKE 'CH'
    //                         AND p.sermov = 2
    //                         AND (
    //                               d.fecha <= '$ultimoDiaMes'
    //                               AND d.fecha >= '$primerDiaMes' 
    //                             )";
    // $resultQueryMesZona2 = $getConnection->query($queryVtaMesZona2);
    // $qVtaMesZona2 = mysqli_fetch_array($resultQueryMesZona2);
    // if($qVtaMesZona2 === NULL){
    //   $totalVentaMesZona2 = 0;
    // } else {
    //   $totalVentaMesZona2 = $qVtaMesZona2['Zona2'];
    // }
    // $formatTotalVentaMesZona2 = number_format($totalVentaMesZona2, 2, '.',',');

    //Se hace la busqueda de ventas totales del Mes Especiales
    // $queryVtaMesEspeciales = "SELECT SUM(d.subtotal1 + d.subtotal2) AS Especiales
    //                             FROM doc d
    //                               JOIN per p ON p.perid = d.vendedorid
    //                             WHERE d.tipo = 'F'
    //                               AND d.serie NOT LIKE 'CH'
    //                               AND (
    //                                     p.perid = 16
    //                                     OR p.perid = 20
    //                                     OR p.perid = 21
    //                                     OR p.perid = 145
    //                                     OR p.perid = 152
    //                                   )
    //                               AND (
    //                                     d.fecha <= '$ultimoDiaMes'
    //                                     AND d.fecha >= '$primerDiaMes' 
    //                                   )";
    // $resultQueryMesEspeciales = $getConnection->query($queryVtaMesEspeciales);
    // $qVtaMesEspeciales = mysqli_fetch_array($resultQueryMesEspeciales);
    // if($qVtaMesEspeciales === NULL){
    //   $totalVentaMesEspeciales = 0;
    // } else {
    //   $totalVentaMesEspeciales = $qVtaMesEspeciales['Especiales'];
    // }
    // $formatTotalVentaMesEspeciales = number_format($totalVentaMesEspeciales, 2, '.',',');

    // Cartera TOTAL
    // $getCarteraTo = "SELECT
    //               SUM(d.total - d.totalpagado) as TotalCartera
    //               FROM doc d
    //               WHERE d.total > d.totalpagado
    //                 AND d.tipo = 'F'
    //                 AND (
    //                       (SELECT DATEDIFF(d.vence, '".$dia."')) < 0
    //                       OR (SELECT DATEDIFF(d.vence, '".$dia."')) > 0
    //                     )";
    // $resultGetCarteraTo = mysqli_query($getConnection,$getCarteraTo);
    // $rowCarteraTo = mysqli_fetch_array($resultGetCarteraTo);
    // if($rowCarteraTo === NULL){
    //   $CarteraToF = 0;
    // } else {
    //   $CarteraToF = $rowCarteraTo["TotalCartera"];
    // }
    // $CarteraTo = number_format($CarteraToF, 2, ".", ",");

    // Morosidad TOTAL.
    // $getMorosidad = "SELECT
    //               SUM(d.totalpagado - d.total) as TotalDeuda
    //               FROM doc d
    //               WHERE d.total > d.totalpagado
    //                 AND d.tipo = 'F'
    //                 AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    // $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
    // $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
    // if($rowMorosidad === NULL){
    //   $MorosidadF = 0;
    // } else {
    //   $MorosidadF = $rowMorosidad["TotalDeuda"]*(-1);
    // }
    // $Morosidad = number_format($MorosidadF, 2, ".", ",");

    // Cartera Sana TOTAL.
    // $getCarteraSana = "SELECT
    //               SUM(d.total - d.totalpagado) as TotalCarteraSana
    //               FROM doc d
    //               WHERE d.total > d.totalpagado
    //                 AND d.tipo = 'F'
    //                 AND (SELECT DATEDIFF(d.vence, '".$dia."')) > 0";
    // $resultGetCarteraSana = mysqli_query($getConnection,$getCarteraSana);
    // $rowCarteraSana = mysqli_fetch_array($resultGetCarteraSana);
    // if($rowCarteraSana === NULL){
    //   $CarteraSanaF = 0;
    // } else {
    //   $CarteraSanaF = $rowCarteraSana["TotalCarteraSana"];
    // }
    // $CarteraSana = number_format($CarteraSanaF, 2, ".", ",");

    //Facturas Vencidas al mes
    $fechaInicioVenc = date('Y-m-01');
    $fechaFinalVenc = date('Y-m-'.$diasTotalMes.'');
    $numVenFac = "SELECT vence
                            FROM doc
                            WHERE totalpagado < total
                              AND feccan = 0
                              AND (
                                    tipo = 'F'
                                    OR tipo = 'N'
                                  )
                              AND vence < '$dia'
                              AND (
                                    feccap < '$fechaFinalVenc'
                                    AND feccap > '$fechaInicioVenc'
                                  )";

    $venFac = mysqli_query($getConnection, $numVenFac);
    $numeroVecesFacVenc = mysqli_num_rows($venFac);
    
    //Nuevos clientes del mes
    $numCliMes = "SELECT clienteid
                            FROM cli
                            WHERE (
									                  fecaltcli < '$fechaFinalVenc'
									                  AND fecaltcli > '$fechaInicioVenc'
                                  )
                              AND catalogo NOT LIKE 'W'";

    $clieMes = mysqli_query($getConnection, $numCliMes);
    $numeroVecesCliNuevos = mysqli_num_rows($clieMes);

    //Se saca nivel de servico Truper

    //Sacamos el % de Nivel de Servicio de Truper Tipo C
    $queryNsTruperC = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) AS importeSolicitadoC
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                            AND des.destipo = 'C'
                            AND i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperC = $getConnection->query($queryNsTruperC);
    $qNsTruperC = mysqli_fetch_array($resultQueryDiaTruperC);
    if($qNsTruperC === NULL){
        $importeSolicitadoTruperC = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeSolicitadoTruperC = $qNsTruperC["importeSolicitadoC"];
    }

    // Sacamos el % de Nivel de Servicio de Truper Tipo F Solicitado
    $queryNsTruperSolicitadoF = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) as importeSolicitadoF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'F'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperSolicitadoF = $getConnection->query($queryNsTruperSolicitadoF);
    $qNsTruperSolicitadoF = mysqli_fetch_array($resultQueryDiaTruperSolicitadoF);
    if($qNsTruperSolicitadoF === NULL){
      $importeTruperSolicitadoF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeTruperSolicitadoF = $qNsTruperSolicitadoF["importeSolicitadoF"];
    }

    //Sacamos el % de Nivel de Servicio de Truper Tipo F Entregado
    $queryNsTruperEntregadoF = "SELECT sum((des.desentregado * des.desventa) - ((des.desentregado * des.desventa) * (des.desdescuento / 100))) as importeEntregadoF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                          join doc d on d.docid = des.desdocid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'F'
                          and d.serie NOT LIKE 'CH'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperEntregadoF = $getConnection->query($queryNsTruperEntregadoF);
    $qNsTruperEntregadoF = mysqli_fetch_array($resultQueryDiaTruperEntregadoF);
    if($qNsTruperEntregadoF === NULL){
        $importeTruperEntregadoF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeTruperEntregadoF = $qNsTruperEntregadoF["importeEntregadoF"];
    }

    //Sacamos el % de Nivel de Servicio de Truper Tipo N Solicitado
    $queryNsTruperSolicitadoN = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) as importeSolicitadoN
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'N'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperSolicitadoN = $getConnection->query($queryNsTruperSolicitadoN);
    $qNsTruperSolicitadoN = mysqli_fetch_array($resultQueryDiaTruperSolicitadoN);
    if($qNsTruperSolicitadoN === NULL){
        $importeSolicitadoTruperN = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoTruperN = $qNsTruperSolicitadoN["importeSolicitadoN"];
      $importeSolicitadoTruperN = $qNsTruperSolicitadoN["importeSolicitadoN"];
    }

    //Sacamos el % de Nivel de Servicio de Truper Tipo N Entregado
    $queryNsTruperEntregadoN = "SELECT sum((des.desentregado * des.desventa) - ((des.desentregado * des.desventa) * (des.desdescuento / 100))) as importeEntregadoN
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'N'
                          and i.clvprov NOT LIKE '8%'";
    $resultQueryDiaTruperEntregadoN = $getConnection->query($queryNsTruperEntregadoN);
    $qNsTruperEntregadoN = mysqli_fetch_array($resultQueryDiaTruperEntregadoN);
    if($qNsTruperEntregadoN === NULL){
        $importeEntregadoTruperN = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoTruperEntregadoN = $qNsTruperEntregadoN["importeSolicitadoN"];
      $importeEntregadoTruperN = $qNsTruperEntregadoN["importeEntregadoN"];
    }

    // Porcentaje Estimado Truper
    if($importeSolicitadoTruperC === NULL){
      $EstimadoTruper = '0.00';
    }else{
      $EstimadoT = $importeTruperSolicitadoF * 100;
      $EstimadoTruper = bcdiv($EstimadoT,$importeSolicitadoTruperC,2);
    }
    // Porcentaje Real Truper
    if($importeEntregadoTruperN === NULL){
      $RealTruper = '0.00';
    }else{
      $RealT = $importeTruperEntregadoF * 100;
      $RealTruper = bcdiv($RealT,$importeEntregadoTruperN,2);
    }
    
    //Se saca nivel de servico FMO

    //Sacamos el % de Nivel de Servicio de FMO Tipo C
    $queryNsFMOC = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) AS importeSolicitadoFMOC
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                            AND des.destipo = 'C'
                            and i.clvprov LIKE '8%'";
    $resultQueryDiaFMOC = $getConnection->query($queryNsFMOC);
    $qNsFMOC = mysqli_fetch_array($resultQueryDiaFMOC);
    if($qNsFMOC === NULL){
        $importeSolicitadoFMOC = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeSolicitadoFMOC = $qNsFMOC["importeSolicitadoFMOC"];
    }

    //Sacamos el % de Nivel de Servicio de FMO Tipo F Solicitado
    // $queryNsFMOSolicitadoF = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) as importeSolicitadoFMOF
    //                     FROM des
    //                       join inv i on i.articuloid = des.desartid
    //                     where des.desfecha = '$dia'
    //                       and des.descantidad > 0
    //                       and des.destipo = 'F'
    //                       and i.clvprov LIKE '8%'";
    // $resultQueryDiaFMOF = $getConnection->query($queryNsFMOSolicitadoF);
    // $qNsFMOSolicitadoF = mysqli_fetch_array($resultQueryDiaFMOF);
    // if($qNsFMOSolicitadoF === NULL){
    //     $importeSolicitadoFMOF = 0;
    // } else {
    //   //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
    //   $importeSolicitadoFMOF = $qNsFMOSolicitadoF["importeSolicitadoFMOF"];
    // }

    //Sacamos el % de Nivel de Servicio de FMO Tipo F Entregado
    $queryNsFMOEntregadoF = "SELECT sum((des.desentregado * des.desventa) - ((des.desentregado * des.desventa) * (des.desdescuento / 100))) as importeEntregadoFMOF
                        FROM des
                          join inv i on i.articuloid = des.desartid
                          join doc d on d.docid = des.desdocid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'F'
                          and d.serie NOT LIKE 'CH'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMOF = $getConnection->query($queryNsFMOEntregadoF);
    $qNsFMOEntregadoF = mysqli_fetch_array($resultQueryDiaFMOF);
    if($qNsFMOEntregadoF === NULL){
        $importeEntregadoFMOF = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      $importeEntregadoFMOF = $qNsFMOEntregadoF["importeEntregadoFMOF"];
    }

    //Sacamos el % de Nivel de Servicio de FMO Tipo N Solicitado
    $queryNsFMOSolicitadoN = "SELECT sum((des.descantidad * des.desventa) - ((des.descantidad * des.desventa) * (des.desdescuento / 100))) as importeSolicitadoFMON
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.descantidad > 0
                          and des.destipo = 'N'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMON = $getConnection->query($queryNsFMOSolicitadoN);
    $qNsFMOSolicitadoN = mysqli_fetch_array($resultQueryDiaFMON);
    if($qNsFMOSolicitadoN === NULL){
        $importeSolicitadoFMON = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeSolicitadoFMON = $qNsFMOSolicitadoN["importeSolicitadoN"];
      $importeSolicitadoFMON = $qNsFMOSolicitadoN["importeSolicitadoFMON"];
    }

    //Sacamos el % de Nivel de Servicio de FMO Tipo N Entregado
    $queryNsFMOEntregadoN = "SELECT sum((des.desentregado * des.desventa) - ((des.desentregado * des.desventa) * (des.desdescuento / 100))) as importeEntregadoFMON
                        FROM des
                          join inv i on i.articuloid = des.desartid
                        where des.desfecha = '$dia'
                          and des.desentregado > 0
                          and des.destipo = 'N'
                          and i.clvprov LIKE '8%'";
    $resultQueryDiaFMON = $getConnection->query($queryNsFMOEntregadoN);
    $qNsFMOEntregadoN = mysqli_fetch_array($resultQueryDiaFMON);
    if($qNsFMOEntregadoN === NULL){
        $importeEntregadoFMON = 0;
    } else {
      //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
      // $importeEntregadoFMON = $qNsFMOEntregadoN["importeEntregadoN"];
      $importeEntregadoFMON = $qNsFMOEntregadoN["importeEntregadoFMON"];
    }

    // Porcentaje Estimado FMO
    // if($importeSolicitadoFMOC === NULL){
    //   $EstimadoFMO = '0.00';
    // }else{
    //   $EstimadoT = $importeSolicitadoFMOF * 100;
    //   $EstimadoFMO = bcdiv($EstimadoT,$importeSolicitadoFMOC,2);
    // }
    // Porcentaje Real FMO
    // if($importeEntregadoFMON === NULL){
    //   $RealFMO = '0.00';
    // }else{
    //   $RealT = $importeEntregadoFMOF * 100;
    //   $RealFMO = bcdiv($RealT,$importeEntregadoFMON,2);
    // }

    // $linkFunctionPersonal = "showPersonal(".$perid.")";
    $linkFunctionPersonal = "showClientesNuevos()";

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT centrar text-center">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 paddingB">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB table-responsive-sm">
                        <h4 class="lead" style="font-size: 2.2em !important;">PEDIDOS</h4>
                        <table class="table table-dark">
                          <thead>
                            <tr>
                              <th scope="col">CATEGORIA</th>
                              <th scope="col">POR BAJAR</th>
                              <th scope="col">POR SURTIR</th>
                              <th scope="col">FACTURADO</th>
                              <th scope="col">CANCELADOS</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">PEDIDOS</th>
                              <td class="text-tomato" style="font-size: 1.7em !important;"><span class="enlaces" id="porbajar" style="cursor:pointer;" onClick="">0</span></td>
                              <td class="text-tomato" style="font-size: 1.7em !important;"><span class="enlaces" id="porsurtir" style="cursor:pointer;" onClick="showPedidosDia(3)">0</span></td>
                              <td class="text-tomato" style="font-size: 1.7em !important;"><span class="enlaces" id="porfactura" style="cursor:pointer;" onClick="showPedidosDia(4)">0</span></td>
                              <td class="text-tomato" style="font-size: 1.7em !important;"><span class="enlaces" id="porCancelacion" style="cursor:pointer;" onClick="showPedidosDia(5)">0</span></td>
                            </tr>
                            <tr>
                              <th scope="row">IMPORTE</th>
                              <td class="text-tomato" id="impoporbajar" style="font-size: 1.7em !important;">0*</td>
                              <td class="text-tomato" id="impoporsurtir" style="font-size: 1.7em !important;">0*</td>
                              <td class="text-tomato" id="porfacturaSaldo" style="font-size: 1.7em !important;">0*</td>
                              <td class="text-tomato" id="porCancelacionSaldo" style="font-size: 1.7em !important;">0*</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 centrar">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 centrar">
                                <h5 class="lead">PEDIDOS DEL DIA</h5>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 centrar">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <h5 class="lead">CANTIDAD</h5>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <p class="lead text-tomato" style="font-size: 3em !important;"><span class="enlaces" id="totalpedidodia" style="cursor:pointer;" onClick="showPedidosDia(1)">0</span></p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-left">
                                        <p class="lead">SUBTOTAL</p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-right">
                                        <span class="lead text-tomato" id="totalpedidodiaSaldo" style="font-size: 1.7em !important;">0</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-left">
                                        <p class="lead">I.V.A.</p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-right">
                                        <span class="lead text-tomato" id="ivaPed" style="font-size: 1.7em !important;">0</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-left">
                                        <p class="lead">TOTAL</p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-right">
                                        <span class="lead text-tomato" id="totalPed" style="font-size: 1.7em !important;">0</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 centrar">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h5 class="lead">NIVEL DE SERVICIO ESTIMADO</h5>
                                <p class="lead text-tomato" id="ns" style="font-size: 3em !important;">0.00%</p>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <h4 class="lead" style="font-size: 1em !important;">TRUPER</h4>
                                <p class="lead text-tomato" id="">PEDIDOS: '.number_format($importeSolicitadoTruperC, 2, ".", ",").'</p>
                                <p class="lead text-tomato" id="">SE BAJO: '.number_format($importeSolicitadoTruperN, 2, ".", ",").'</p>
                                <p class="lead text-tomato" id="">SE TRABAJO: '.number_format($importeEntregadoTruperN, 2, ".", ",").'</p>
                                <p class="lead text-tomato" id="">SE FACTURO: '.number_format($importeTruperEntregadoF, 2, ".", ",").'</p>
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <p class="lead" style="font-size: 1em !important;">Estimado</p>
                                    <p class="lead text-tomato" id="nsTruEstimado">0.00%</p>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <p class="lead" style="font-size: 1em !important;">Real</p>
                                    <p class="lead text-tomato" id="nsTruReal">0.00%</p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <h4 class="lead" style="font-size: 1em !important;">FMO</h4>
                                <p class="lead text-tomato" id="">PEDIDOS: '.number_format($importeSolicitadoFMOC, 2, ".", ",").'</p>
                                <p class="lead text-tomato" id="">SE BAJO: '.number_format($importeSolicitadoFMON, 2, ".", ",").'</p>
                                <p class="lead text-tomato" id="">SE TRABAJO: '.number_format($importeEntregadoFMON, 2, ".", ",").'</p>
                                <p class="lead text-tomato" id="">SE FACTURO: '.number_format($importeEntregadoFMOF, 2, ".", ",").'</p>
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <p class="lead" style="font-size: 1em !important;">Estimado</p>
                                    <p class="lead text-tomato" id="nsFerreMEstimado">0.00%</p>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <p class="lead" style="font-size: 1em !important;">Real</p>
                                    <p class="lead text-tomato" id="nsFMOReal">0.00%</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 paddingB">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                        <h4 class="lead" style="font-size: 2.2em !important;">VENTAS MENSUALES</h4>
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <h4 class="lead">MES ANTERIOR</h4>
                            <p class="lead text-tomato" style="font-size: 1.7em !important;">$ '.$mesAnteriorTo.'*</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <h4 class="lead">MES ACTUAL</h4>
                            <p class="lead text-tomato" id="totalventames" style="font-size: 1.7em !important;">$ 0*</p>
                          </div>
                        </div>
                        <h4 class="lead">DETALLE</h4>
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <h4 class="lead">ZONA 1</h4>
                            <p class="lead text-tomato" id="vtaZona1" style="font-size: 1.1em !important;">$ 0*</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <h4 class="lead">ZONA 2</h4>
                            <p class="lead text-tomato" id="vtaZona2" style="font-size: 1.1em !important;">$ 0*</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <h4 class="lead">ESPECIALES</h4>
                            <p class="lead text-tomato" id="vtaEspecial" style="font-size: 1.1em !important;">$ 0*</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                        <h4 class="lead" style="font-size: 2.2em !important;">CARTERA TOTAL</h4>
                        <p class="lead text-tomato" id="carteratotal" style="font-size: 1.7em !important;">$ 0</p>
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <h4 class="lead">CARTERA VENCIDA</h4>
                            <p class="lead text-tomato" id="morosidad" style="font-size: 1.7em !important;">$ 0</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <h4 class="lead">CARTERA AL DIA</h4>
                            <p class="lead text-tomato" id="carterasana" style="font-size: 1.7em !important;">$ 0</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <h4 class="lead">NUEVOS CLIENTES AL MES</h4>
                            <p class="lead text-tomato" style="font-size: 1.7em !important;">
                              <span class="enlaces" style="cursor:pointer;" onClick="'.$linkFunctionPersonal.'">'.$numeroVecesCliNuevos.'</span>
                            </p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <p class="lead">FACTURAS VENCIDAS AL MES</p>
                            <p class="lead text-tomato" style="font-size: 1.7em !important;">'.$numeroVecesFacVenc.'</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                        <a class="btn btn-secondary btn-enl btn-block" href="javascript:location.reload(true)">Borrar Historial</a>
                      </div>
                    </div>
                  </div>
                  <script type="text/javascript">
                    var f=new Date();
                    var hora=f.getHours();
                    if(hora < 21 && hora > 8){
                      $(document).ready(function() {
                        console.log("Sistema Abierto");
                        function pedidosDia(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodia.php",
                            success: function(pedido) {
                              $("#totalpedidodia").text(pedido);
                              // $("#totalpedidodia").addClass("aviso");
                              // console.log("No. Pedidos: ",pedido);
                            }
                          });
                        }
                        setInterval(pedidosDia, 3000);

                        function pedidosDiaSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasaldo.php",
                            success: function(pedidoSaldo) {
                              $("#totalpedidodiaSaldo").text(pedidoSaldo);
                              // $("#totalpedidodiaSaldo").addClass("aviso");
                              // console.log("Saldo Pedidos Total: ", pedidoSaldo);
                            }
                          });
                        }
                        setInterval(pedidosDiaSaldo, 3000);

                        function pedidosDiaIva(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiaiva.php",
                            success: function(pedidoIva) {
                              $("#ivaPed").text(pedidoIva);
                              // $("#ivaPed").addClass("aviso");
                              // console.log("Iva Pedidos Total: ", pedidoIva);
                            }
                          });
                        }
                        setInterval(pedidosDiaIva, 3000);

                        function pedidosDiaTotal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiatotal.php",
                            success: function(pedidoTotal) {
                              $("#totalPed").text(pedidoTotal);
                              // $("#totalPed").addClass("aviso");
                              // console.log("Total Pedidos Total: ", pedidoTotal);
                            }
                          });
                        }
                        setInterval(pedidosDiaTotal, 3000);

                        function pedidosDiaSurtir(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasurtir.php",
                            success: function(pedidoSurtir) {
                              $("#porsurtir").text(pedidoSurtir);
                              // $("#porsurtir").addClass("aviso");
                              // console.log("Surti: ", pedidoSurtir);
                            }
                          });
                        }
                        setInterval(pedidosDiaSurtir, 3000);

                        function pedidosDiaSurtirSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasurtirsaldo.php",
                            success: function(pedidoSurtirSaldo) {
                              $("#impoporsurtir").text(pedidoSurtirSaldo);
                              // $("#impoporsurtir").addClass("aviso");
                              // console.log("Saldo por Surtir: ", pedidoSurtirSaldo);
                            }
                          });
                        }
                        setInterval(pedidosDiaSurtirSaldo, 3000);

                        function pedidosDiaBajar(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiabajar.php",
                            success: function(pedidoBajar) {
                              $("#porbajar").text(pedidoBajar);
                              // $("#porbajar").addClass("aviso");
                              // console.log("Bajar: ", pedidoBajar);
                            }
                          });
                        }
                        setInterval(pedidosDiaBajar, 3000);
                        
                        function pedidosDiaBajarSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiabajarsaldo.php",
                            success: function(pedidoBajarSaldo) {
                              $("#impoporbajar").text(pedidoBajarSaldo);
                              // $("#impoporbajar").addClass("aviso");
                              // console.log("Saldo Bajar: ", pedidoBajarSaldo);
                            }
                          });
                        }
                        setInterval(pedidosDiaBajarSaldo, 3000);

                        function pedidosDiaFactura(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiafacturar.php",
                            success: function(pedidoFactura) {
                              $("#porfactura").text(pedidoFactura);
                              // $("#porfactura").addClass("aviso");
                              // console.log("Facturadas: ", pedidoFactura);
                            }
                          });
                        }
                        setInterval(pedidosDiaFactura, 3000);

                        function pedidosDiaFacturaSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiafacturarsaldo.php",
                            success: function(pedidoFacturaSaldo) {
                              $("#porfacturaSaldo").text(pedidoFacturaSaldo);
                              // $("#porfacturaSaldo").addClass("aviso");
                              // console.log("Saldo Facturadas: ", pedidoFacturaSaldo);
                            }
                          });
                        }
                        setInterval(pedidosDiaFacturaSaldo, 3000);

                        function pedidosDiaCancelacion(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiacancelacion.php",
                            success: function(pedidoCancelacion) {
                              $("#porCancelacion").text(pedidoCancelacion);
                              // $("#porCancelacion").addClass("aviso");
                              // console.log("Cancelaciondas: ", pedidoCancelacion);
                            }
                          });
                        }
                        setInterval(pedidosDiaCancelacion, 3000);

                        function pedidosDiaCancelacionSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiacancelacionsaldo.php",
                            success: function(pedidoCancelacionSaldo) {
                              $("#porCancelacionSaldo").text(pedidoCancelacionSaldo);
                              // $("#porCancelacionSaldo").addClass("aviso");
                              // console.log("Saldo Canceladas: ", pedidoCancelacionSaldo);
                            }
                          });
                        }
                        setInterval(pedidosDiaCancelacionSaldo, 3000);
                        
                        function ventasMes(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/ventames.php",
                            success: function(venta) {
                              $("#totalventames").text(venta);
                              // $("#totalventames").addClass("aviso");
                              // console.log(venta);
                            }
                          });
                        }
                        setInterval(ventasMes, 3000);

                        function nS(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/ns.php",
                            success: function(ns) {
                              $("#ns").text(ns);
                              // $("#ns").addClass("aviso");
                              // console.log("Nivel de Servicio: ", ns);
                            }
                          });
                        }
                        setInterval(nS, 3000);

                        function nsTruperEst(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsTruperEstimado.php",
                            success: function(nsTruEstimado) {
                              $("#nsTruEstimado").text(nsTruEstimado);
                              // $("#nsTruEstimado").addClass("aviso");
                              // console.log("Nivel de Servicio Truper Estimado: ", nsTruEstimado);
                            }
                          });
                        }
                        setInterval(nsTruperEst, 3000);

                        function nsTruperRe(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsTruperReal.php",
                            success: function(nsTruReal) {
                              $("#nsTruReal").text(nsTruReal);
                              // $("#nsTruReal").addClass("aviso");
                              // console.log("Nivel de Servicio Truper Estimado: ", nsTruReal);
                            }
                          });
                        }
                        setInterval(nsTruperRe, 3000);

                        function nsFerreEstimado(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsFMOEstimado.php",
                            success: function(nsFMOEstimado) {
                              $("#nsFerreMEstimado").text(nsFMOEstimado);
                              // $("#nsFerreMEstimado").addClass("aviso");
                              // console.log("Nivel de Servicio FMO: ", nsFMOEstimado);
                            }
                          });
                        }
                        setInterval(nsFerreEstimado, 3000);

                        function nsFerreReal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsFMOReal.php",
                            success: function(nsFMOReal) {
                              $("#nsFMOReal").text(nsFMOReal);
                              // $("#nsFMOReal").addClass("aviso");
                              // console.log("Nivel de Servicio FMO: ", nsFMOReal);
                            }
                          });
                        }
                        setInterval(nsFerreReal, 3000);
                        
                        function carteraTotal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/carteratotal.php",
                            success: function(carterato) {
                              $("#carteratotal").text(carterato);
                              // $("#carteratotal").addClass("aviso");
                              // console.log("Cartera Total: ", carterato);
                            }
                          });
                        }
                        setInterval(carteraTotal, 60000);
                        
                        function morosidad(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/morosidad.php",
                            success: function(moros) {
                              $("#morosidad").text(moros);
                              // $("#morosidad").addClass("aviso");
                              // console.log("Cartera Vencida: ", moros);
                            }
                          });
                        }
                        setInterval(morosidad, 60000);
                        
                        function carteraSana(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/carterasana.php",
                            success: function(carteraS) {
                              $("#carterasana").text(carteraS);
                              // $("#carterasana").addClass("aviso");
                              // console.log("Cartera al Día: ", carteraS);
                            }
                          });
                        }
                        setInterval(carteraSana, 60000);
                        
                        function vtaZN1(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/vtazona1.php",
                            success: function(vtaZ1) {
                              $("#vtaZona1 ").text(vtaZ1);
                              // $("#vtaZona1 ").addClass("aviso");
                              // console.log("Zona1: ", vtaZ1);
                            }
                          });
                        }
                        setInterval(vtaZN1, 3000);
                        
                        function vtaZN2(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/vtazona2.php",
                            success: function(vtaZ2) {
                              $("#vtaZona2 ").text(vtaZ2);
                              // $("#vtaZona2 ").addClass("aviso");
                              // console.log("Zona2: ", vtaZ2);
                            }
                          });
                        }
                        setInterval(vtaZN2, 3000);
                        
                        function vtaEspec(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/vtaespecial.php",
                            success: function(vtaEspe) {
                              $("#vtaEspecial  ").text(vtaEspe);
                              // $("#vtaEspecial  ").addClass("aviso");
                              // console.log("Especial: ", vtaEspe);
                            }
                          });
                        }
                        setInterval(vtaEspec, 3000);
                      });
                    } else {
                      console.log("Fuera de Línea");
                      $(document).ready(function() {	
                        function pedidosDia(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodia.php",
                            success: function(pedido) {
                              $("#totalpedidodia").text(pedido);
                              // $("#totalpedidodia").addClass("aviso");
                              // console.log("No. Pedidos: ",pedido);
                            }
                          });
                        }
                        setTimeout(pedidosDia, 1000);

                        function pedidosDiaSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasaldo.php",
                            success: function(pedidoSaldo) {
                              $("#totalpedidodiaSaldo").text(pedidoSaldo);
                              // $("#totalpedidodiaSaldo").addClass("aviso");
                              // console.log("Saldo Pedidos Total: ", pedidoSaldo);
                            }
                          });
                        }
                        setTimeout(pedidosDiaSaldo, 1000);

                        function pedidosDiaIva(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiaiva.php",
                            success: function(pedidoIva) {
                              $("#ivaPed").text(pedidoIva);
                              // $("#ivaPed").addClass("aviso");
                              // console.log("Iva Pedidos Total: ", pedidoIva);
                            }
                          });
                        }
                        setTimeout(pedidosDiaIva, 1000);

                        function pedidosDiaTotal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiatotal.php",
                            success: function(pedidoTotal) {
                              $("#totalPed").text(pedidoTotal);
                              // $("#totalPed").addClass("aviso");
                              // console.log("Total Pedidos Total: ", pedidoTotal);
                            }
                          });
                        }
                        setTimeout(pedidosDiaTotal, 1000);

                        function pedidosDiaSurtir(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasurtir.php",
                            success: function(pedidoSurtir) {
                              $("#porsurtir").text(pedidoSurtir);
                              // $("#porsurtir").addClass("aviso");
                              // console.log("Surti: ", pedidoSurtir);
                            }
                          });
                        }
                        setTimeout(pedidosDiaSurtir, 1000);

                        function pedidosDiaSurtirSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiasurtirsaldo.php",
                            success: function(pedidoSurtirSaldo) {
                              $("#impoporsurtir").text(pedidoSurtirSaldo);
                              // $("#impoporsurtir").addClass("aviso");
                              // console.log("Saldo por Surtir: ", pedidoSurtirSaldo);
                            }
                          });
                        }
                        setTimeout(pedidosDiaSurtirSaldo, 1000);

                        function pedidosDiaBajar(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiabajar.php",
                            success: function(pedidoBajar) {
                              $("#porbajar").text(pedidoBajar);
                              // $("#porbajar").addClass("aviso");
                              // console.log("Bajar: ", pedidoBajar);
                            }
                          });
                        }
                        setTimeout(pedidosDiaBajar, 1000);
                        
                        function pedidosDiaBajarSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiabajarsaldo.php",
                            success: function(pedidoBajarSaldo) {
                              $("#impoporbajar").text(pedidoBajarSaldo);
                              // $("#impoporbajar").addClass("aviso");
                              // console.log("Saldo Bajar: ", pedidoBajarSaldo);
                            }
                          });
                        }
                        setTimeout(pedidosDiaBajarSaldo, 1000);

                        function pedidosDiaFactura(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiafacturar.php",
                            success: function(pedidoFactura) {
                              $("#porfactura").text(pedidoFactura);
                              // $("#porfactura").addClass("aviso");
                              // console.log("Facturadas: ", pedidoFactura);
                            }
                          });
                        }
                        setTimeout(pedidosDiaFactura, 1000);

                        function pedidosDiaFacturaSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiafacturarsaldo.php",
                            success: function(pedidoFacturaSaldo) {
                              $("#porfacturaSaldo").text(pedidoFacturaSaldo);
                              // $("#porfacturaSaldo").addClass("aviso");
                              // console.log("Saldo Facturadas: ", pedidoFacturaSaldo);
                            }
                          });
                        }
                        setTimeout(pedidosDiaFacturaSaldo, 1000);

                        function pedidosDiaCancelacion(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiacancelacion.php",
                            success: function(pedidoCancelacion) {
                              $("#porCancelacion").text(pedidoCancelacion);
                              // $("#porCancelacion").addClass("aviso");
                              // console.log("Cancelaciondas: ", pedidoCancelacion);
                            }
                          });
                        }
                        setTimeout(pedidosDiaCancelacion, 1000);

                        function pedidosDiaCancelacionSaldo(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/pedidodiacancelacionsaldo.php",
                            success: function(pedidoCancelacionSaldo) {
                              $("#porCancelacionSaldo").text(pedidoCancelacionSaldo);
                              // $("#porCancelacionSaldo").addClass("aviso");
                              // console.log("Saldo Canceladas: ", pedidoCancelacionSaldo);
                            }
                          });
                        }
                        setTimeout(pedidosDiaCancelacionSaldo, 1000);
                        
                        function ventasMes(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/ventames.php",
                            success: function(venta) {
                              $("#totalventames").text(venta);
                              // $("#totalventames").addClass("aviso");
                              // console.log(venta);
                            }
                          });
                        }
                        setTimeout(ventasMes, 1000);

                        function nS(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/ns.php",
                            success: function(ns) {
                              $("#ns").text(ns);
                              // $("#ns").addClass("aviso");
                              // console.log("Nivel de Servicio: ", ns);
                            }
                          });
                        }
                        setTimeout(nS, 1000);

                        function nsTruperEst(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsTruperEstimado.php",
                            success: function(nsTruEstimado) {
                              $("#nsTruEstimado").text(nsTruEstimado);
                              // $("#nsTruEstimado").addClass("aviso");
                              // console.log("Nivel de Servicio Truper Estimado: ", nsTruEstimado);
                            }
                          });
                        }
                        setTimeout(nsTruperEst, 1000);

                        function nsTruperRe(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsTruperReal.php",
                            success: function(nsTruReal) {
                              $("#nsTruReal").text(nsTruReal);
                              // $("#nsTruReal").addClass("aviso");
                              // console.log("Nivel de Servicio Truper Estimado: ", nsTruReal);
                            }
                          });
                        }
                        setTimeout(nsTruperRe, 1000);

                        function nsFerreEstimado(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsFMOEstimado.php",
                            success: function(nsFMOEstimado) {
                              $("#nsFerreMEstimado").text(nsFMOEstimado);
                              // $("#nsFerreMEstimado").addClass("aviso");
                              // console.log("Nivel de Servicio FMO: ", nsFMOEstimado);
                            }
                          });
                        }
                        setTimeout(nsFerreEstimado, 1000);

                        function nsFerreReal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/nsFMOReal.php",
                            success: function(nsFMOReal) {
                              $("#nsFMOReal").text(nsFMOReal);
                              // $("#nsFMOReal").addClass("aviso");
                              // console.log("Nivel de Servicio FMO: ", nsFMOReal);
                            }
                          });
                        }
                        setTimeout(nsFerreReal, 1000);
                        
                        function carteraTotal(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/carteratotal.php",
                            success: function(carterato) {
                              $("#carteratotal").text(carterato);
                              // $("#carteratotal").addClass("aviso");
                              // console.log("Cartera Total: ", carterato);
                            }
                          });
                        }
                        setTimeout(carteraTotal, 61000);
                        
                        function morosidad(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/morosidad.php",
                            success: function(moros) {
                              $("#morosidad").text(moros);
                              // $("#morosidad").addClass("aviso");
                              // console.log("Cartera Vencida: ", moros);
                            }
                          });
                        }
                        setTimeout(morosidad, 61000);
                        
                        function carteraSana(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/carterasana.php",
                            success: function(carteraS) {
                              $("#carterasana").text(carteraS);
                              // $("#carterasana").addClass("aviso");
                              // console.log("Cartera al Día: ", carteraS);
                            }
                          });
                        }
                        setTimeout(carteraSana, 61000);
                        
                        function vtaZN1(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/vtazona1.php",
                            success: function(vtaZ1) {
                              $("#vtaZona1 ").text(vtaZ1);
                              // $("#vtaZona1 ").addClass("aviso");
                              // console.log("Zona1: ", vtaZ1);
                            }
                          });
                        }
                        setTimeout(vtaZN1, 1000);
                        
                        function vtaZN2(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/vtazona2.php",
                            success: function(vtaZ2) {
                              $("#vtaZona2 ").text(vtaZ2);
                              // $("#vtaZona2 ").addClass("aviso");
                              // console.log("Zona2: ", vtaZ2);
                            }
                          });
                        }
                        setTimeout(vtaZN2, 1000);
                        
                        function vtaEspec(){
                          $.ajax({
                            type: "POST",
                            url: "../php/busquedas/vtaespecial.php",
                            success: function(vtaEspe) {
                              $("#vtaEspecial  ").text(vtaEspe);
                              // $("#vtaEspecial  ").addClass("aviso");
                              // console.log("Especial: ", vtaEspe);
                            }
                          });
                        }
                        setTimeout(vtaEspec, 1000);
                      });
                    }
                  </script>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB text-center">
                    <p class="lead text-center" style="font-size:1.5em;">* Valores considerados sin I.V.A.</p>
                  </div>
                </div>
              </div>';
    echo $print;
    $getConnection->close();
  }

  private function getShowBackOrderActual($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $month = date('m');

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

    if($diasTotalMes < 30){
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-28');
    } else if($diasTotalMes > 30){
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-31');
    } else {
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-30');
    }

    $buscarProveedores = "SELECT clienteid, nombre FROM cli where (tipo REGEXP 'P' or tipo REGEXP 'D') and emisorid NOT LIKE '0'";
    $proveedoresEncontrados = mysqli_query($getConnection, $buscarProveedores);
    
    $print = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB paddingT">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <div class="container">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB paddingT text-center">
                    <h1>BACK ORDER <span class="text-tomato">POR PROVEEDOR</span></h1>
                    <p class="lead">'.$mes.' del '.date("Y").'</p>
                    <button type="button" class="btn btn-outline-primary" onClick="showBackOrderActual()">Actualizar</button>
                  </div>
                  <table class="table table-dark">
                    <thead>
                      <tr>
                        <th scope="col" class="text-center">ID Proveedor</th>
                        <th scope="col" class="text-center">Nombre</th>
                        <th scope="col" class="text-center">Solictado</th>
                        <th scope="col" class="text-center">Entregado</th>
                        <th scope="col" class="text-center">BackOrder</th>
                        <th scope="col" class="text-center">$ BackOrder</th>
                      </tr>
                    </thead>
                    <tbody>';
    $numsol = 0;
    $nument = 0;
    $numback = 0;
    $impoback = 0;
    while($rowProv = mysqli_fetch_array($proveedoresEncontrados)){
      $proveedorid = $rowProv["clienteid"];
      $nombre = $rowProv["nombre"];

      // Agregar fecha inicial y fecha final
      // $buscarBackOrderProv = "SELECT sum(desdoc.descantidad) as backordersolicitado,
      //                           sum(desdoc.desentregado) as backorderentregado,
      //                           sum(desdoc.descantidad - desdoc.desentregado) as backorderprov,
      //                           sum(((desdoc.descantidad - desdoc.desentregado) * desdoc.desventa) - (((desdoc.descantidad - desdoc.desentregado) * desdoc.desventa) * (desdoc.desdescuento / 100))) as importeBackOrderProv
      //                           FROM desdoc
      //                             JOIN ppa ON ppa.articuloid = desdoc.desartid
      //                           WHERE ppa.proveedorid = $proveedorid
      //                             and (
      //                                   desdoc.desfecha >= '".$fecActIni."'
      //                                   and desdoc.desfecha <= '".$fecActFin."'
      //                                 )
      //                             and (desdoc.descantidad - desdoc.desentregado) > 0";

      $backorderentregado     = 0;
      $backordersolicitado    = 0;
      $backorderprov          = 0;
      $importebackorderprov   = 0;

      // Buscando Renglonaje Mes Actual
      $buscandoRenglonaje = "SELECT d.desventa, d.descantidad, d.desentregado, d.desdescuento
                                  FROM desinvdocalm d
                                  WHERE d.proveedorid = $proveedorid
                                    AND (
                                          d.desfecha <= '".$fecActFin."'
                                          AND d.desfecha >= '".$fecActIni."'
                                        )
                                    AND (d.descantidad - d.desentregado) > 0
                                    AND d.destipo = 'C'";
      $reblonEncontrado = $getConnection->query($buscandoRenglonaje);
      $m = 0;
      $n = 0;
      $o = 0;
      $p = 0;
      $q = 0;
      while($mesActRen = mysqli_fetch_row($reblonEncontrado)){
        $desventaActRen = $mesActRen[0];
        $descantidadActRen = $mesActRen[1];
        $desentregadoActRen = $mesActRen[2];
        $desdescuento = $mesActRen[3];

        $resta = $descantidadActRen - $desentregadoActRen;
        // echo "(".$resta.")-";

        if($desdescuento > 0){
          $desc = $desdescuento / 100;
          // echo "(".$desc.")/";

          $impoDesc = $desventaActRen * $desc;
          // echo "(".$desventaActRen." * ".$desc.")/";

          $impoFinAntM = $desventaActRen - $impoDesc;
          // echo "(".$desventaActRen." - ".$impoDesc.")/";

          $impoReng = $resta * $impoFinAntM;
          // echo "(".$impoReng.")/";
        }else{
          $impoReng = $resta * $desventaActRen;
          // echo "(".$impoReng.")/";
        }

        $m+=$desventaActRen;
        $n+=$descantidadActRen;
        $o+=$desentregadoActRen;
        $p+=$resta;
        $q+=$impoReng;
      }
      $numsol += $n;
      $nument += $o;
      $numback += $p;
      $impoback += $q;

      $print .=       '<tr>';
      if($n > 0){
        $print .=       '<th scope="row" class="text-center"><a class="nav-linkImg" href="#" onClick="backOrderReng('.$proveedorid.');">'.$proveedorid.'</a></th>
                        <td class="text-center">'.$nombre.'</td>
                        <td class="text-center">'.number_format($n, 0, ".", ",").'</td>
                        <td class="text-center">'.number_format($o, 0, ".", ",").'</td>
                        <td class="text-center text-tomato">'.number_format($p, 0, ".", ",").'</td>
                        <td class="text-right text-tomato">$ '.number_format($q, 2, ".", ",").' </td>';
      }
      $print .=       '</tr>';
    }
    $print .=         '<tr>
                        <th colspan="2">Total</th>
                        <td class="text-center">'.number_format($numsol, 0, ".", ",").'</td>
                        <td class="text-center">'.number_format($nument, 0, ".", ",").'</td>
                        <td class="text-center text-tomato">'.number_format($numback, 0, ".", ",").'</td>
                        <td class="text-right text-tomato">$ '.number_format($impoback, 2, ".", ",").'</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>';

    echo $print;
    $getConnection->close();
  }

  private function getShowBackOrderReng($params){
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $proveedor        = $paramDb->SecureInput($params["proveedor"]);

    $month = date('m');

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

    if($diasTotalMes < 30){
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-28');
    } else if($diasTotalMes > 30){
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-31');
    } else {
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-30');
    }

    $buscarProveedor = "SELECT nombre FROM cli WHERE clienteid = $proveedor";
    $proveedorEncontrado = mysqli_query($getConnection, $buscarProveedor);
    $rowProve = mysqli_fetch_array($proveedorEncontrado);
    $nombreProv = $rowProve["nombre"];

    $buscarRenglonajes = "SELECT clave, clvprov, descripcio,
                            descantidad, desentregado,
                            desventa, desdescuento
                            FROM desinvdocalm
                            WHERE proveedorid = $proveedor
                              AND (descantidad - desentregado) > 0
                              AND (desfecha >= '$fecActIni' AND desfecha <= '$fecActFin')
                              AND destipo = 'C'";
    $renglonajeEncontrado = mysqli_query($getConnection, $buscarRenglonajes);

    $print = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB paddingT">
              <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                <img src="../img/barrafmo2.gif" width="200"/>
              </div>
              <div class="container">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB paddingT text-center">
                  <h1>BACK ORDER DEL PROVEEDOR<br/><span class="text-tomato">'.$nombreProv.'</span></h1>
                  <p class="lead">'.$mes.' del '.date("Y").'</p>
                  <button type="button" class="btn btn-outline-primary" onClick="showBackOrderActual()">Regresar</button>
                </div>
                <table class="table table-dark">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">Clave</th>
                      <th scope="col" class="text-center">Código</th>
                      <th scope="col" class="text-center">Articulo</th>
                      <th scope="col" class="text-center">Solicitado</th>
                      <th scope="col" class="text-center">Entregado</th>
                      <th scope="col" class="text-center">BackOrder</th>
                      <th scope="col" class="text-center">$ BackOrder</th>
                    </tr>
                  </thead>
                  <tbody>';
    $numsol = 0;
    $nument = 0;
    $numback = 0;
    $impoback = 0;
    while($rewReng = mysqli_fetch_array($renglonajeEncontrado)){
      $clave                  = $rewReng["CLAVE"];
      $codigo                 = $rewReng["CLVPROV"];
      $articulo               = $rewReng["DESCRIPCIO"];
      $solicitada             = $rewReng["DESCANTIDAD"];
      $entregada              = $rewReng["DESENTREGADO"];
      $precio                 = $rewReng["DESVENTA"];
      $descuento              = $rewReng["DESDESCUENTO"];
      
      $backorderReng          = ($solicitada - $entregada);
      $importeBackOrderReng1   = $backorderReng * $precio;
      $importeBackOrderReng2   = $descuento / 100;
      $importeBackOrderReng3   = $importeBackOrderReng1 * $importeBackOrderReng2;
      $importeBackOrderReng    = $importeBackOrderReng1 - $importeBackOrderReng3;
      
      $print .=     '<tr>';
      $print .=       '<th scope="row" class="text-center">'.$clave.'</th>
                      <td class="text-center">'.$codigo.'</td>
                      <td class="text-center">'.$articulo.'</td>
                      <td class="text-center">'.number_format($solicitada, 0, ".", ",").'</td>
                      <td class="text-center">'.number_format($entregada, 0, ".", ",").'</td>
                      <td class="text-center text-tomato">'.number_format($backorderReng, 0, ".", ",").'</td>
                      <td class="text-right text-tomato">$ '.number_format($importeBackOrderReng, 2, ".", ",").' </td>';
      $print .=     '</tr>';

      $numsol = $numsol + $solicitada;
      $nument = $nument + $entregada;
      $numback = $numback + $backorderReng;
      $impoback = $impoback + $importeBackOrderReng;
    }

    $print .=       '<tr>
                      <th colspan="3">Total</th>
                      <td class="text-center">'.number_format($numsol, 0, ".", ",").'</td>
                      <td class="text-center">'.number_format($nument, 0, ".", ",").'</td>
                      <td class="text-center text-tomato">'.number_format($numback, 0, ".", ",").'</td>
                      <td class="text-right text-tomato">$ '.number_format($impoback, 2, ".", ",").'</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>';

    echo $print;
    $getConnection->close();
  }

  private function getBackOrder($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $dia = date('Y-m-d');
    $month = date('m');

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

    $mtAnt = date('m')-1;
    $yearAnt = date('Y')-1;
    if($mtAnt == 0){
      $yrAnt = date('Y')-1;
      $mtAnt = 12;
    } else {
      $yrAnt = date('Y');
    }
    if($diasTotalMes < 30){
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-28');
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-28');
    } else if($diasTotalMes > 30){
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-31');
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-31');
    } else {
      $fecActIni = date('Y-'.$month.'-01');
      $fecActFin = date('Y-'.$month.'-30');
      $fecAnteIni = date(''.$yrAnt.'-'.$mtAnt.'-01');
      $fecAnteFin = date(''.$yrAnt.'-'.$mtAnt.'-30');
    }

    // Buscando Back Order Mes Ant
    $buscandoMesAnt = "SELECT 
                              if(
                                  d.desdescuento > 0, (d.desdescuento * d.desventa), 0
                                ) as ImpoDesc,
                              if(
                                  d.desentregado < d.descantidad, d.descantidad - d.desentregado, 0
                                ) as BackOrder,
                              d.desventa
                                              FROM desinvdocalm d
                                    
                                              WHERE (
                                                      d.desfecha <= '".$fecAnteFin."'
                                                      AND d.desfecha >= '".$fecAnteIni."'
                                                    )
                                                AND (d.descantidad - d.desentregado) > 0
                                                AND d.destipo REGEXP 'C'";
    $mesAntEncontrado = $getConnection->query($buscandoMesAnt);
    $m = 0;
    $n = 0;
    $o = 0;
    $p = 0;
    while($mesAntB = mysqli_fetch_row($mesAntEncontrado)){
      $ImpoDescAntM = $mesAntB[0];
      $BackOrderAntM = $mesAntB[1];
      $desventaAntM = $mesAntB[2];

      if($ImpoDescAntM > 0){
        $impoFinAntM = $desventaAntM - $ImpoDescAntM;
        $impoAntM = $BackOrderAntM * $impoFinAntM;
        $p += $impoAntM;
      }else{
        $impoAntM = $BackOrderAntM * $desventaAntM;
        $p += $impoAntM;
      }
      $m+=$ImpoDescAntM;
      $n+=$BackOrderAntM;
      $o+=$desventaAntM;
    }

    // Buscando Back Order Mes actual
    $buscandoMesActual = "SELECT 
                              if(
                                  d.desdescuento > 0, (d.desdescuento * d.desventa), 0
                                ) as ImpoDesc,
                              if(
                                  d.desentregado < d.descantidad, d.descantidad - d.desentregado, 0
                                ) as BackOrder,
                              d.desventa
                                              FROM desinvdocalm d
                                    
                                              WHERE (
                                                      d.desfecha <= '".$fecActFin."'
                                                      AND d.desfecha >= '".$fecActIni."'
                                                    )
                                                AND (d.descantidad - d.desentregado) > 0
                                                AND d.destipo REGEXP 'C'";
    $mesActualEncontrado = $getConnection->query($buscandoMesActual);
    $i = 0;
    $j = 0;
    $k = 0;
    $l = 0;
    while($mesActB = mysqli_fetch_row($mesActualEncontrado)){
      $ImpoDescActM = $mesActB[0];
      $BackOrderActM = $mesActB[1];
      $desventaActM = $mesActB[2];

      if($ImpoDescActM > 0){
        $impoFinActM = $desventaActM - $ImpoDescActM;
        $impoActM = $BackOrderActM * $impoFinActM;
        $l += $impoActM;
      }else{
        $impoActM = $BackOrderActM * $desventaActM;
        $l += $impoActM;
      }
      $i+=$ImpoDescActM;
      $j+=$BackOrderActM;
      $k+=$desventaActM;
    }

    // BACKO ORDER DE FORMA ANTERIOR,TARDABA MAS ESTA MANERA
    // $totalYearAnterior = 0;
    // $primerDiaAnioAnterior = date("".$yearAnt."-01-01");
    // $ultimoDiaAnioAnterior = date("".$yearAnt."-12-31");

    // $buscandoAnualAnt= "SELECT sum(des.descantidad), sum(des.desentregado), sum(des.desventa), sum(des.desdescuento)
    //                   FROM des
    //                     LEFT OUTER JOIN desdoc d ON d.desartid = des.desartid
    //                   WHERE (
    //                           des.desfecha <= '".$yearAnt."-12-31'
    //                           AND des.desfecha >= '".$yearAnt."-01-01'
    //                         )
    //                     AND (des.descantidad - des.desentregado) > 0
    //                     AND des.destipo REGEXP 'C'";

    // Buscando Back Order General del año anterior
    // $buscandoAnualAnt= "SELECT 
    //                           if(
    //                               d.desdescuento > 0, (d.desdescuento * d.desventa), 0
    //                             ) as ImpoDesc,
    //                           if(
    //                               d.desentregado < d.descantidad, d.descantidad - d.desentregado, 0
    //                             ) as BackOrder,
    //                           d.desventa
    //                                           FROM desinvdocalm d
                                    
    //                                           WHERE (
    //                                                   d.desfecha <= '".$yearAnt."-12-31'
    //                                                   AND d.desfecha >= '".$yearAnt."-01-01'
    //                                                 )
    //                                             AND (d.descantidad - d.desentregado) > 0
    //                                             AND d.destipo REGEXP 'C'";
    // $AnualAntEncontrado = $getConnection->query($buscandoAnualAnt);
    // $a = 0;
    // $b = 0;
    // $c = 0;
    // $d = 0;
    // while($anualAn = mysqli_fetch_row($AnualAntEncontrado)){
    //   $ImpoDesc = $anualAn[0];
    //   $BackOrder = $anualAn[1];
    //   $desventa = $anualAn[2];

    //   if($ImpoDesc > 0){
    //     $impoFin = $desventa - $ImpoDesc;
    //     $impo = $BackOrder * $impoFin;
    //     $d += $impo;
    //   }else{
    //     $impo = $BackOrder * $desventa;
    //     $d += $impo;
    //   }
    //   $a+=$ImpoDesc;
    //   $b+=$BackOrder;
    //   $c+=$desventa;
    // }

    $backOrderAntAnio = "SELECT sum(((des.descantidad - des.desentregado) * (des.desventa - ((des.desventa * des.desdescuento) / 100)))) as precioBackOrderDesc
                          FROM des
                            LEFT OUTER JOIN doc
                              ON doc.docid = des.desdocid
                          WHERE (
                            des.desfecha <= '".$yearAnt."-12-31'
                            AND des.desfecha >= '".$yearAnt."-01-01'
                          )
                          AND doc.tipo = 'C'
                          AND (des.descantidad - des.desentregado) > 0
                          AND doc.estado NOT LIKE 'C'";
    $backOrderAntAnioEnc = mysqli_query($getConnection,$backOrderAntAnio);
    $backOrderAA = mysqli_fetch_array($backOrderAntAnioEnc);
    $precioBackOrderDesc = (float)$backOrderAA["precioBackOrderDesc"];

    // Buscando Back Order General del año actual
    $buscandoAnualAct= "SELECT 
                              if(
                                  d.desdescuento > 0, (d.desdescuento * d.desventa), 0
                                ) as ImpoDesc,
                              if(
                                  d.desentregado < d.descantidad, d.descantidad - d.desentregado, 0
                                ) as BackOrder,
                              d.desventa
                                              FROM desinvdocalm d
                                    
                                              WHERE (
                                                      d.desfecha <= '".date('Y-12-31')."'
                                                      AND d.desfecha >= '".date('Y-01-01')."'
                                                    )
                                                AND (d.descantidad - d.desentregado) > 0
                                                AND d.destipo REGEXP 'C'";
    $AnualActEncontrado = $getConnection->query($buscandoAnualAct);
    $e = 0;
    $f = 0;
    $g = 0;
    $h = 0;
    while($anualAct = mysqli_fetch_row($AnualActEncontrado)){
      $ImpoDescAct = $anualAct[0];
      $BackOrderAct = $anualAct[1];
      $desventaAct = $anualAct[2];

      if($ImpoDescAct > 0){
        $impoFinAct = $desventaAct - $ImpoDescAct;
        $impoAct = $BackOrderAct * $impoFinAct;
        $h += $impoAct;
      }else{
        $impoAct = $BackOrderAct * $desventaAct;
        $h += $impoAct;
      }
      $e+=$ImpoDescAct;
      $f+=$BackOrderAct;
      $g+=$desventaAct;
    }

    $backOrderAct = "SELECT sum(((des.descantidad - des.desentregado) * (des.desventa - ((des.desventa * des.desdescuento) / 100)))) as precioBackOrderDesc
                          FROM des
                            LEFT OUTER JOIN doc
                              ON doc.docid = des.desdocid
                          WHERE (
                            des.desfecha <= '".date('Y-12-31')."'
                            AND des.desfecha >= '".date('Y-01-01')."'
                          )
                          AND doc.tipo = 'C'
                          AND (des.descantidad - des.desentregado) > 0
                          AND doc.estado NOT LIKE 'C'";
    $backOrderActEnc = mysqli_query($getConnection,$backOrderAct);
    $backOrderActAn = mysqli_fetch_array($backOrderActEnc);
    $precioBackOrderDescAct = (float)$backOrderActAn["precioBackOrderDesc"];

    // $buscandoAnualAct= "SELECT sum((des.descantidad - des.desentregado) * ((des.desventa) - ((des.desventa) * (des.desdescuento / 100)))) as BackOrderAnioActual
    //                   FROM des
    //                     JOIN desdoc d ON d.desdocid = des.desdocid
    //                   WHERE (
    //                           des.desfecha <= '".date('Y-12-31')."'
    //                           AND des.desfecha >= '".date('Y-01-01')."'
    //                         )
    //                     AND (des.descantidad - des.desentregado) > 0
    //                     AND des.destipo REGEXP 'C'";
    // $AnualActEncontrado = $getConnection->query($buscandoAnualAct);
    // $anualAct = mysqli_fetch_array($AnualActEncontrado);
    // $totalAnualActual = $anualAct["BackOrderAnioActual"];

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <div class="content-wrapper">';
                  
    $print .=     '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficasGeneral">
                    <div class="row paddingB">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <h3 class="display-4">Back order Mensual</h3>
                        <h4> <b>'.$mes.'</b> del <b>'.date("Y").'</b></h4>
                        <div class="row text-center">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficosBarra">
                            <p id="diasTotalMes" style="display: none;">'.$diasTotalMes.'</p>
                            <p id="mesActual" style="display: none;">'.$l.'</p>
                            <p id="mesAnterior" style="display: none;">'.$p.'</p>
                            <canvas id="areaChartBackOrder" style="max-height:350px;"></canvas>
                          </div>';
    $print .=           '</div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">';
    $print .=                 '<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <span class="text-redGraf">
                                  BackOrder del Mes pasado<br /><b style="font-size: 2em;">$ '.number_format($p, 2, ".", ",").'</b>
                                </span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <span class="text-blue">
                                  BackOrder Actual<br /><button type="button" class="btn btn-outline-primary btn-lg btn-block" onClick="showBackOrderActual()"><b style="font-size: 2em;">$ '.number_format($l, 2, ".", ",").'</b></button>
                                </span>
                              </div>
                            </div>';
    $print .=               '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <h5 class="text-center lead">Se presenta el reporte de Back-Order al mes en comparación con el anterior.</h5>
                              <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';
    $vtaAnualActual = date("Y");
    $print .=       '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                          <div class="row infoCard">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                              <h3 class="display-4">Reporte de BackOrder Global</h3>
                              <h4><b>'.$yearAnt.' <em style="font-size: 0.5em;">Vs</em> '.date("Y").'</b></h4>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <p id="totalYearAnterior" style="display: none;">'.$precioBackOrderDesc.'</p>
                              <p id="totalAnualActual" style="display: none;">'.$precioBackOrderDescAct.'</p>
                              <canvas id="areaChartAnualBackOrder" style="height:250px"></canvas>
                              <script src="../intranet/js/Chart.js"></script>
                              <script src="../intranet/js/backorder.js"></script>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                  <p class="lead text-redGraf"><b style="font-size: 1em;">BackOrder '.$yearAnt.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($precioBackOrderDesc,2,".",",").'</b></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                  <p class="lead" style="color:#3adcf4"><b style="font-size: 1em;">BackOrder '.date("Y").'</b><br /><b style="font-size: 1.5em;">$ '.number_format($precioBackOrderDescAct, 2,".", ",").'</b></p>
                                </div>
                              </div>
                              <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';

    $print .=     '</div>
                </div>
              </div>';
    echo $print;
    $getConnection->close();
  }

  private function getReportService($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();
    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h4 class="display-4 text-center">Reporte de Servicio</div>
                <form class="buscador">
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Fecha Inicio</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="date" name="fecInicio" id="fecInicio"/>
                    </div>
                    <label class="col-sm-2 col-form-label">Fecha Final</label>
                    <div class="col-sm-4">
                      <input class="form-control" type="date" name="fecFinal" id="fecFinal"/>
                    </div>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-warning btn-lg btn-block" onClick="buscarReporteServicio();">Buscar</button>
                  </div>
                </form>
                <div class="row paddingT">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <h3 class="text-center">PEDIDOS</h3>
                        <div class="row">
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead">CATEGORIAS</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead">PEDIDOS</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead">IMPORTES</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead">FACTURADO</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead" id="numFact">0</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead" id="impoFact">$ 0.00*</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead">CANCELADOS</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead" id="numCancelados">0</p>
                          </div>
                          <div class="col-12 col-md-12 col-lg-4 col-xl-4 text-center">
                            <p class="lead" id="impoCancelados">$ 0.00*</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <h3 class="text-center">PEDIDOS TOTALES</h3>
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 centrarSep">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 centrar">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <h5 class="lead">CANTIDAD</h5>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                                    <p class="lead" style="font-size: 3em !important;"><span id="numPed">0</span></p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-left">
                                        <p class="lead">SUBTOTAL</p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-right">
                                        <span class="lead" id="subtotalPedidos">$ 0.00*</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-left">
                                        <p class="lead">I.V.A.</p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-right">
                                        <span class="lead" id="ivaPedidos">$ 0.00*</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-left">
                                        <p class="lead">TOTAL</p>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8 text-right">
                                        <span class="lead" id="pedidosTotal">$ 0.00*</span>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <h3 class="text-center">VENTAS</h3>
                        <div class="row text-center">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <p class="lead" id="importeVenta">$ 0.00*</p>
                          </div>
                          
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <h4>DETALLE</h4>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <h4 class="lead">ZONA 1</h4>
                            <p class="lead" id="zona1">$ 0.00*</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <h4 class="lead">ZONA 2</h4>
                            <p class="lead" id="zona2">$ 0.00*</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <h4 class="lead">ESPECIALES</h4>
                            <p class="lead" id="especiales">$ 0.00*</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 text-center centrarSep">
                    <h3>NIVEL DE SERVICIO ESTIMADO</h3>
                    <p class="lead" id="nsEstimadoGen" style="font-size: 3em !important;">0.00%</p>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <h4 class="lead">TRUPER</h4>
                        <p class="lead" id="">PEDIDOS: $ 0.00*</p>
                        <p class="lead" id="">SE BAJO: $ 0.00*</p>
                        <p class="lead" id="">SE TRABAJO: $ 0.00*</p>
                        <p class="lead" id="">SE FACTURO: $ 0.00*</p>
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <p class="lead">Estimado</p>
                            <p class="lead" id="">0.00%</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <p class="lead">Real</p>
                            <p class="lead" id="">0.00%</p>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <h4 class="lead">FMO</h4>
                        <p class="lead" id="">PEDIDOS: $ 0.00*</p>
                        <p class="lead" id="">SE BAJO: $ 0.00*</p>
                        <p class="lead" id="">SE TRABAJO: $ 0.00*</p>
                        <p class="lead" id="">SE FACTURO: $ 0.00*</p>
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <p class="lead">Estimado</p>
                            <p class="lead" id="">0.00%</p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <p class="lead">Real</p>
                            <p class="lead" id="">0.00%</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
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

    $queryVtaDia = "SELECT SUM(SUBTOTAL2) AS total
                          FROM doc
                          WHERE fecha = '$dia'
                            AND tipo = 'F'
                            AND tipo NOT LIKE 'CH'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDia = mysqli_query($getConnection, $queryVtaDia);
    $qVtaDia = mysqli_fetch_array($resultQueryDia);
    if($qVtaDia == NULL){
      $totalVentaDia = 0;
    } else {
      $totalVentaDia = $qVtaDia["total"];
    }
    $formatTotalVentaDia = number_format($totalVentaDia, 2, '.',',');

    //Pedidos al día
    $queryPedDia = "SELECT d.docid
                      FROM doc d
                      WHERE d.fecha = '".$dia."'
                        AND tipo = 'C'";
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
                            AND serie NOT LIKE 'CH'
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
    $queryVtaMes = "SELECT SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS Total
                      FROM doc
                      WHERE (
                              fecha <= '$ultimoDiaMes'
                              AND fecha >= '$primerDiaMes' 
                              )
                        AND tipo = 'F'
                        AND serie NOT LIKE 'CH'
							          AND subtotal2 > 0";
    $resultQueryMes = $getConnection->query($queryVtaMes);
    $qVtaMes = mysqli_fetch_array($resultQueryMes);
    if($qVtaMes === NULL){
      $totalVentaMes = 0;
    } else {
      $totalVentaMes = $qVtaMes['Total'];
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
                            AND serie NOT LIKE 'CH'
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
                                    AND serie NOT LIKE 'CH'
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
                              AND serie NOT LIKE 'CH'
							                AND subtotal2 > 0
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
                              AND serie NOT LIKE 'CH'
							                AND subtotal2 > 0
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
                                AND (
                                      fecha <= '".${'fecFinQ'.$y}."'
                                      AND fecha >= '".${'fecIniQ'.$y}."'
                                    )
                                AND serie NOT LIKE 'CH'
							                  AND subtotal2 > 0
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
    $mesBusc = $month - 1;
    for($mAnt = 1; $mAnt <= $mesBusc; $mAnt++){

      if($diasTotalMes < 30){
        ${'fecIniQ'.$mAnt} = date('Y-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date('Y-'.$mAnt.'-28');
      } else if($diasTotalMes > 30){
        ${'fecIniQ'.$mAnt} = date('Y-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date('Y-'.$mAnt.'-31');
      } else {
        ${'fecIniQ'.$mAnt} = date('Y-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date('Y-'.$mAnt.'-30');
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
                              AND serie NOT LIKE 'CH'
							                AND subtotal2 > 0
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

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <div class="content-wrapper">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="row infoCard">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                <span>Ventas al día</span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                <p id="totalventadia" class="input-falso text-green text-bold">$ '.$formatTotalVentaDia.'</p>
										          </div>
									          </div>
								          </div>
								          <div class="form-group">
									          <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                <span>Pedidos al día</span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                <p id="totalpedidodia" class="input-falso text-green text-bold">'.$qPediDia.'</p>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                <span>Ventas al mes</span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                <p class="input-falso text-green text-bold" id="totalVentaMes">$ '.$formatTotalVentaMes.'</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                <span>Ventas a la semana</span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                <p id="totalventasemana" class="input-falso text-green text-bold">$ '.$formatTotalVentaSemana.'</p>
										          </div>
									          </div>
								          </div>
								          <div class="form-group">
									          <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                <span>Ventas al trimestre</span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                <p id="totalventatrimestre" class="input-falso text-green text-bold">$ '.$formatTotalVentaTrimestre.'</p>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                <span>Estatus</span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                <p class="input-falso text-green text-bold">Aqui va un estatus de como esta la compañia</p>
                              </div>
                            </div>
                          </div>
                          <script type="text/javascript">
                            var f=new Date();
                            var hora=f.getHours();
                            if(hora < 20 || hora > 8){
                              $(document).ready(function() {	
                                function ventasDia(){
                                  $.ajax({
                                      type: "POST",
                                      url: "../php/busquedas/ventadia.php",
                                      success: function(mensaje) {
                                          $("#totalventadia").text(mensaje);
                                          // console.log(mensaje)
                                      }
                                    });
                                }
                                setInterval(ventasDia, 3000);
                                function pedidosDia(){
                                  $.ajax({
                                      type: "POST",
                                      url: "../php/busquedas/pedidodia.php",
                                      success: function(mensaje) {
                                          $("#totalpedidodia").text(mensaje);
                                          // console.log(mensaje)
                                      }
                                  });
                                }
                                setInterval(pedidosDia, 3000);
                                function ventasMes(){
                                  $.ajax({
                                      type: "POST",
                                      url: "../php/busquedas/ventames.php",
                                      success: function(mensaje) {
                                          $("#totalVentaMes").text(mensaje);
                                          // console.log(mensaje)
                                      }
                                    });
                                }
                                setInterval(ventasMes, 3000);
                                function ventasSemana(){
                                    $.ajax({
                                        type: "POST",
                                        url: "../php/busquedas/ventasemana.php",
                                        success: function(mensaje) {
                                            $("#totalventasemana").text(mensaje);
                                            // console.log(mensaje)
                                        }
                                      });
                                }
                                setInterval(ventasSemana, 3000);
                                function ventasTrimestre(){
                                    $.ajax({
                                        type: "POST",
                                        url: "../php/busquedas/ventatrimestre.php",
                                        success: function(mensaje) {
                                            $("#totalventatrimestre").text(mensaje);
                                            // console.log(mensaje)
                                        }
                                      });
                                }
                                setInterval(ventasTrimestre, 3000);
                              });
                            } else {
                              console.log("Fuera de Línea");
                            }
                          </script>
                        </div>
                      </div>
                    </div>
                  </div>';
                  
    $print .=     '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficasGeneral">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <h3 class="display-4">Venta Mensual</h3>
                        <h4>Venta Mensual de <b>'.$mes.'</b> del <b>'.date("Y").'</b></h4>
                        <div class="row text-center">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficosBarra">
                            <p id="diasTotalMes" style="display: none;">'.$diasTotalMes.'</p>
                            <p id="mesActual" style="display: none;">'.$totalVentaMes.'</p>
                            <p id="mAnterior" style="display: none;">'.$vAnt.'</p>
                            <p id="mejorMes" style="display: none;">'.$vMejor.'</p>
                            <p id="mesMej" style="display: none;">'.$mesMej.'</p>
                            <canvas id="areaChart" style="max-height:350px;"></canvas>
                          </div>
                          <script src="../intranet/js/Chart.js"></script>
                          <script src="../intranet/js/graficas.js"></script>';
    $print .=           '</div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">';
    $print .=                 '<div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <span class="text-redGraf">
                                  Ventas del Mes pasado<br /><b style="font-size: 2em;">$ '.number_format($vAnt, 2, ".", ",").'</b>
                                </span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <span class="text-blue">
                                  Venta Actual<br /><b style="font-size: 2em;">$ '.number_format($totalVentaMes, 2, ".", ",").'</b>
                                </span>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                <span class="text-green">
                                  Ventas del Mejor Mes<br /><b style="font-size: 2em;">$ '.number_format($vMejor, 2, ".", ",").'</b>
                                </span>
                                <p class="text-green" style="font-weight:bold">'.$mesMej.' '.date("Y").'</p>
                              </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                              <h1 class="display-4">Proyección de Cierre</h1>
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                      <p class="lead"><b>Ingrese los días del mes actual a calcular</b></p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                      <input id="diasActual" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 25" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                      <p class="lead"><b>Ingrese los días del mes anterior a calcular</b></p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                      <input id="diasAnterior" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 20" required>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                      <p class="lead"><b>Ingrese los días que han pasado</b></p>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                      <input id="diasConteo" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 11" required>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div id="botonCalcular" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 10px;">
                                <input class="btn btn-success" type="submit" name="" value="Calcular" onClick="calcular();">
                              </div>
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="ventaPorDiaActual"></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="ventaPorDiaAnterior"></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="pronosticoMensual"></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="proyeccionCierre"></div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="ventaPorDiaIgualar"></div>
                              </div>
                              <script src="../intranet/js/calculos.js"></script>
                            </div>';
    $print .=               '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <h5 class="text-center lead">Se presenta el reporte de ventas al mes, en comparación con el anterior y con el mejor mes.</h5>
                              <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';
    $vtaAnualActual = date("Y");
    $print .=       '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                          <div class="row infoCard">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                              <h3 class="display-4">Reporte de Venta</h3>
                              <h4><b>'.date("Y").' <em style="font-size: 0.5em;">Vs</em> '.$anioAnterior.' <em style="font-size: 0.5em;">Vs</em> Empresa</b></h4>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <canvas id="areaChartAnual" style="height:250px"></canvas>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                  <p class="lead" style="color:#3adcf4"><b style="font-size: 1em;">'.$anioAnterior.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearAnterior, 2, ".", ",").'</b></p>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                  <p class="lead text-redGraf"><b style="font-size: 1em;">'.$vtaAnualActual.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearEmpresa, 2, ".", ",").'</b></p>
                                </div>
                              </div>
                              <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';

    $print .=     '</div>
                </div>
              </div>';

    // TODO hacer consultas por vendedor, por tipo de cliente y por tiempo de morosidad.
    $getMorosidad = "SELECT 
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                    RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                  WHERE d.total > d.totalpagado
                    AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )
                    AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
    $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
    $MorosidadF = $rowMorosidad[0]*(-1);
    $Morosidad = number_format($MorosidadF, 2, ".", ",");

    $get030DiasDist = "SELECT 
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                    RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                  WHERE d.total > d.totalpagado
                      AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -30
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGet030Dist = mysqli_query($getConnection,$get030DiasDist);
    $row030Dist = mysqli_fetch_array($resultGet030Dist);
    $dias030DistF = $row030Dist[0]*(-1);
    $dias030Dist = number_format($dias030DistF, 2, ".", ",");

    $get3060DiasDist = "SELECT
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                    RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                  WHERE d.total > d.totalpagado
                      AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -60
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -30";
    $resultGet3060Dist = mysqli_query($getConnection,$get3060DiasDist);
    $row3060Dist = mysqli_fetch_array($resultGet3060Dist);
    $dias3060DistF = $row3060Dist[0]*(-1);
    $dias3060Dist = number_format($dias3060DistF, 2, ".", ",");

    $get6090DiasDist = "SELECT 
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                    RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                  WHERE d.total > d.totalpagado
                      AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -90
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -60";
    $resultGet6090Dist = mysqli_query($getConnection,$get6090DiasDist);
    $row6090Dist = mysqli_fetch_array($resultGet6090Dist);
    $dias6090DistF = $row6090Dist[0]*(-1);
    $dias6090Dist = number_format($dias6090DistF, 2, ".", ",");

    $get90DiasDist = "SELECT
                  SUM(d.totalpagado - d.total) as TotalDeuda
                  FROM doc d
                    RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                  WHERE d.total > d.totalpagado
                      AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -90";
    $resultGet90Dist = mysqli_query($getConnection,$get90DiasDist);
    $row90Dist = mysqli_fetch_array($resultGet90Dist);
    $dias90DistF = $row90Dist[0]*(-1);
    $dias90Dist = number_format($dias90DistF, 2, ".", ",");

    $print .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                    <h3 class="display-4">Cuentas por Cobrar</h3>
                    <h4>Cartera Vencida Total</h4>
                    <p class="text-redGraf" style="font-weight:bold;font-size: 2em;">$ '.$Morosidad.'</p>
                    <table class="table table-striped table-dark">
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
        $print .=           '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias030Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad(0, 1);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=           '<td class="text-center">$ '.$dias030Dist.'</td>';
      }

      if($dias3060Dist > 0){
        $print .=           '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias3060Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad(0, 2);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=           '<td class="text-center">$ '.$dias3060Dist.'</td>';
      }

      if($dias6090Dist > 0){
        $print .=           '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias6090Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad(0, 3);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=           '<td class="text-center">$ '.$dias6090Dist.'</td>';
      }

      if($dias90Dist > 0){
        $print .=           '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias90Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad(0, 4);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
      } else {
        $print .=           '<td class="text-center">$ '.$dias90Dist.'</td>';
      }

      $print .=           '</th>
                        </tr>
                      </tbody>
                    </table>
                    <h5>Se presenta el reporte de cuentas por cobrar.</h5>
                    <p class="lead">La información mostrada es de solo carácter informativo.</p>
                  </div>
                </div>
              </div>';
    echo $print;
    $getConnection->close();
  }

  private function getReportePedidosDia($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $tipoPedido       = $paramDb->SecureInput($params["tipoPedido"]);
    $getConnection    = $paramDb->GetLink();

    // var_dump($tipoPedido);

    $dia  = date("Y-m-d");

    switch ($tipoPedido) {
      case 1:
        $titulo = "TOTALES";
        //Pedidos Totales
        $queryPedDia = "SELECT des.desdocid, des.destipo, des.desartid, i.clave, des.descantidad, des.desentregado, (des.descantidad * des.desventa) as importe
                          FROM des
                            LEFT OUTER JOIN inv i ON i.articuloid = des.desartid
                          WHERE des.desfecha = '$dia'
                            AND des.destipo = 'C'
                            AND des.descantidad > 0";
        $resultado = $getConnection->query($queryPedDia);
      break;
      case 2:
        $titulo = "POR BAJAR";
        //Pedidos por bajar
        
      break;
      case 3:
        $titulo = "POR SURTIR";
        //Pedidos por surtir
        $queryPedDiaSurtir = "SELECT des.desdocid, des.destipo, des.desartid, i.clave, des.descantidad, des.desentregado, (des.descantidad * des.desventa) as importe
                                FROM des
                                  LEFT OUTER JOIN inv i ON i.articuloid = des.desartid
                                WHERE des.desfecha = '$dia'
                                  AND des.destipo = 'N'
                                  AND des.desentregado > 0";
        $resultado = $getConnection->query($queryPedDiaSurtir);
      break;
      case 4:
        $titulo = "FACTURADOS";
        //Pedidos Facturados
        $queryPedDiaFactura = "SELECT des.desdocid, des.destipo, des.desartid, i.clave, des.descantidad, des.desentregado, (des.descantidad * des.desventa) as importe
                                FROM des
                                LEFT OUTER JOIN inv i ON i.articuloid = des.desartid
                                WHERE des.desfecha = '$dia'
                                  AND des.destipo = 'F'
                                  AND des.desentregado > 0";
        $resultado = $getConnection->query($queryPedDiaFactura);
      break;
      case 5:
        $titulo = "CANCELADOS";
        //Pedidos Cancelados
        $queryPedDiaCancelacion = "SELECT *
                                    FROM doc
                                    WHERE fecha = '$dia'
                                      AND (tipo NOT LIKE 'E' and tipo NOT LIKE 'M')
                                      AND estado = 'C'";
        $resultado = $getConnection->query($queryPedDiaCancelacion);
        // $numPedDiaCancelacion = mysqli_num_rows($resultQueryDiaCancelacion);
        // if($numPedDiaCancelacion === NULL){
        //   $sumCancelacion = 0;
        // } else {
        //   //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
        //   $sumCan = 0;
        //   while($qPedDiaCancelacion = mysqli_fetch_array($resultQueryDiaCancelacion)){
        //     $docid = $qPedDiaCancelacion[0];
        //     $buscarPartidasCanceladas = "SELECT des.desdocid, des.destipo, des.desartid, i.clave, des.descantidad, des.desentregado, (des.descantidad * des.desventa) as importe
        //                   FROM des
        //                     LEFT OUTER JOIN inv i ON i.articuloid = des.desartid
        //                   where descantidad > 0
        //                   and desfecha = '$dia'
        //                   and desdocid = $docid";
        //     $resultado = $getConnection->query($buscarPartidasCanceladas);
        //   }
        //   $sumCancelacion = "$ ".number_format($sumCan, 2, '.',',').'*';
        // }
      break;
    }
    $print = '<div class="container paddingT">
              <h4 class="h4 text-center">PEDIDOS <span class="text-tomato">'.$titulo.'</span></h4>
              <div class="table-responsive">
                <table class="table table-dark">
                  <thead>
                    <tr class="text-center">';
    if($tipoPedido == 5){
      $print .=       '<th scope="col">DOCUMENTO</th>
                      <th scope="col">TIPO</th>
                      <th scope="col">ESTADO</th>';
    }else{
      $print .=       '<th scope="col">DOCUMENTO</th>
                      <th scope="col">TIPO</th>
                      <th scope="col">CLAVE</th>
                      <th scope="col">ARTICULO</th>
                      <th scope="col">CANTIDAD</th>
                      <th scope="col">ENTREGADO</th>
                      <th scope="col">IMPORTE</th>';
    }
    $print .=       '</tr>
                  </thead>
                  <tbody>';
    while($row= mysqli_fetch_array($resultado)){
      if($tipoPedido == 5){
        $desdocid = $row["NUMERO"];
        $destipo = $row["TIPO"];
        $desestado = $row["ESTADO"];
        $descantidad = 1;
        $desentregado = 0;
      }else{
        $desdocid = $row["desdocid"];
        $destipo = $row["destipo"];
        $desartid = $row["desartid"];
        $clave = $row["clave"];
        $descantidad = $row["descantidad"];
        $desentregado = $row["desentregado"];
        $importe = $row["importe"];
      }

      if($desentregado > $descantidad){
        if($tipoPedido == 5){
          $print .=   '<tr class="text-center">
                      <td style="background: tomato!important;">'.$desdocid.'</td>
                      <td>'.$destipo.'</td>
                      <td>'.$desestado.'</td>
                    </tr>';
        }else{
          $print .= '<tr class="text-center">
                      <td style="background: #99ffcc!important;">'.$desdocid.'</td>
                      <td style="background: #99ffcc!important;">'.$destipo.'</td>
                      <td style="background: #99ffcc!important;">'.$clave.'</td>
                      <td style="background: #99ffcc!important;">'.$desartid.'</td>
                      <td style="background: #99ffcc!important;">'.$descantidad.'</td>
                      <td style="background: #99ffcc!important;">'.$desentregado.'</td>
                      <td style="background: #99ffcc!important;">'.number_format($importe, 2, ".", ",").'</td>
                    </tr>';
        }
      }else{
        if($tipoPedido == 5){
          $print .=   '<tr class="text-center">
                      <td style="background: tomato!important;">'.$desdocid.'</td>
                      <td>'.$destipo.'</td>
                      <td>'.$desestado.'</td>
                    </tr>';
        }else{
          $print .= '<tr class="text-center">
                      <td style="background: tomato!important;">'.$desdocid.'</td>
                      <td>'.$destipo.'</td>
                      <td>'.$clave.'</td>
                      <td>'.$desartid.'</td>
                      <td>'.$descantidad.'</td>
                      <td>'.$desentregado.'</td>
                      <td>'.number_format($importe, 2, ".", ",").'</td>
                    </tr>';
        }
      }
    }
    $print .=     '</tbody>
                </table>
              </div>
            </div>';
    echo $print;
    $getConnection->close();
  }

  private function getReporteVendedor($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();
    if($params["perID"]){
      $perid            = $paramDb->SecureInput($params["perID"]);
    }else{
      $perid = $_SESSION["data"]["id"];
    }
    /*$perid = $_SESSION["personal"];*/

    //Se define las fechas y trimestres
    $dia  = date("Y-m-d");
    $year=date('Y');
    $month=date('m');
    $day=date('d');
    $dv = date('D');
    switch($dv){
      case 'Mon':
        $diaVis = 'L';
        break;
      case 'Tue':
        $diaVis = 'I';
        break;
      case 'Wed':
        $diaVis = 'M';
        break;
      case 'Thu':
        $diaVis = 'J';
        break;
      case 'Fri':
        $diaVis = 'V';
        break;
    }

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
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $queryPerDat = "SELECT v.foto, v.tel, v.correo
                    FROM vendedores v
                    WHERE vendedorid = $perid";

    $resultadoPerDat = mysqli_query($mysqliCon,$queryPerDat);
    $filaPerDat = mysqli_fetch_array($resultadoPerDat);
    $foto = $filaPerDat[0];
    $tel = $filaPerDat[1];
    $correo = $filaPerDat[2];
    
    $queryPer = "SELECT p.nombre, p.ingreso, p.sermov
                    FROM per p
                    WHERE perid = $perid";

    $resultadoPer = mysqli_query($getConnection,$queryPer);
    $filaPer = mysqli_fetch_array($resultadoPer);
    $nombre = $filaPer[0];
    $ingreso = $filaPer[1];
    $sermov = $filaPer[2];

    if($sermov == 1){
      $zona = 1;
    } elseif($sermov == 2) {
      $zona = 2;
    } else {
      $zona = 0;
    }

    //Se hace la busqueda de ventas totales del Dia
    $queryVtaDia = "SELECT SUM(SUBTOTAL2) AS total, COUNT(SUBTOTAL2) AS contar
                            FROM doc
                          WHERE vendedorid = $perid
                            AND fecha = '$dia'
                            AND tipo = 'F'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDia = mysqli_query($getConnection,$queryVtaDia);
    $qVtaDia = mysqli_fetch_row($resultQueryDia);
    if($qVtaDia === NULL){
      $qPedDia = 0;
      $totalVentaDia = 0;
    } else {
      $qPedDia = $qVtaDia[1];
      $totalVentaDia = $qVtaDia[0];
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
    $queryVtaSemana = "SELECT SUM(SUBTOTAL2) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDia'
                                AND fecha >= '$primerDia' 
                                )
                            AND tipo = 'F'
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
    $queryVtaMes = "SELECT SUM(SUBTOTAL2) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDiaMes'
                                AND fecha >= '$primerDiaMes' 
                                )
                            AND tipo = 'F'
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
    $queryVtaTrimestre = "SELECT SUM(SUBTOTAL2) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDiaTrimestre'
                                AND fecha >= '$primerDiaTrimestre' 
                                )
                            AND tipo = 'F'
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
    $queryVtaTotalDiaAnt = "SELECT SUM(SUBTOTAL2) AS total
                                  FROM doc
                                  WHERE vendedorid = $perid
                                    AND fecha >= '".$fecAnteIni."'
                                    AND fecha <= '".$fecAnteFin."'
                                    AND tipo = 'F'
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
      if($mAnt == 1 || $mAnt == 3 || $mAnt == 5 || $mAnt == 7 || $mAnt == 8 || $mAnt == 10 || $mAnt == 12){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-31');
      } elseif($mAnt == 4 || $mAnt == 6 || $mAnt == 9 || $mAnt == 11){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-30');
      } elseif($mAnt == 2){
        ${'fecIniQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-01');
        ${'fecFinQ'.$mAnt} = date(''.$anioAnterior.'-'.$mAnt.'-28');
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
        $mMes = 0;
      }
    }
    switch ($mMes) {
      case 0:
        $mesMej='Sin Historial';
      break;
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

    // TODO hacer consultas por vendedor, por tipo de cliente y por tiempo de morosidad.
    $getMorosidad = "SELECT 
                      SUM(d.totalpagado - d.total) as TotalDeuda
                      FROM doc d
                        RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                        RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                      WHERE d.total > d.totalpagado
                          AND c.vendedorid = $perid
                          AND (
                                d.tipo = 'F'
                                OR d.tipo = 'N'
                              )
                          AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
    $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
    if($rowMorosidad === NULL){
    $MorosidadF = 0;
    } else {
    $MorosidadF = $rowMorosidad[0]*(-1);
    }
    $Morosidad = number_format($MorosidadF, 2, ".", ",");

    // Buscamos la cobranza del día
    $getMorosidadDia = "SELECT 
                      SUM(d.totalpagado - d.total) as CobranzaDia
                      FROM doc d
                        RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                        RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                      WHERE c.diavis = '$diaVis'
						            AND d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidadDia = mysqli_query($getConnection,$getMorosidadDia);
    $rowMorosidadDia = mysqli_fetch_array($resultGetMorosidadDia);
    if($rowMorosidadDia === NULL){
    $MorosidadFDia = 0;
    } else {
    $MorosidadFDia = $rowMorosidadDia[0]*(-1);
    }
    $MorosidadDia = number_format($MorosidadFDia, 2, ".", ",");

    // Buscamos los pagos de los clientes con morosidad del día
    $diaInicio = date('Y-m-d 00:00:00');
    $diaFin = date('Y-m-d 23:59:59');
    $getMorosidadPagoDia = "SELECT 
                              sum(pagado) as PagoCobDia
                              FROM doc d
                                LEFT OUTER JOIN cli c ON c.clienteid = d.clienteid
                                LEFT OUTER JOIN pagdoc pd ON pd.docid = d.docid
                              WHERE c.diavis = '$diaVis'
                                AND (fechapag >= '$diaInicio' AND fechapag <= '$diaFin')
                                AND d.total > d.totalpagado
                                AND c.vendedorid = $perid
                                AND (
                                      d.tipo = 'F'
                                      OR d.tipo = 'N'
                                    )
                                AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidadPagoDia = mysqli_query($getConnection,$getMorosidadPagoDia);
    $rowMorosidadPagoDia = mysqli_fetch_array($resultGetMorosidadPagoDia);
    if($rowMorosidadPagoDia === NULL){
      $MorosidadFPagoDia = 0;
    } else {
      $MorosidadFPagoDia = $rowMorosidadPagoDia[0];
    }
    $MorosidadPagoDia = number_format($MorosidadFPagoDia, 2, ".", ",");

        /*echo          "</div>
                    </div>
                  </section>
                </div>
              </div>";*/

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row infoCard">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5">
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                          <img src="../img/vendedores/'.$foto.'" class="rounded-circle img" alt="'.$foto.'" />
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                          <h5>
                            <span><b>#'.$perid.'</b></span> - '.$nombre.'
                          </h5>
                          <h5>
                            <small class="text-green">ZONA '.$zona.'</small>
                          </h5>
                          <h5>
                            <span>Tel.: '.$tel.'</span>
                          </h5>
                          <h5>
                            <span>Correo: '.$correo.'</span>
                          </h5>
                        </div>
                      </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                      <span>Ventas al día</span>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <input type="text" class="form-control" value="$ '.$formatTotalVentaDia.'" readonly />
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                      <span>Pedidos al día</span>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <input type="text" class="form-control" value="'.$qPedDia.'" readonly />
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                      <span>Ventas al mes</span>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <input type="text" class="form-control" value="$ '.$formatTotalVentaMes.'" readonly />
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                      <span>Cartera Vencida</span>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <input type="text" class="form-control text-red" value="$ '.$Morosidad.'" readonly />
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                      <span>Cobranza del día</span>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <input type="text" class="form-control text-red" value="$ '.$MorosidadDia.'" readonly />
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                      <span>Cobrado</span>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <input type="text" class="form-control" value="$ '.$MorosidadPagoDia.'" readonly />
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficasGeneral">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                    <h3>Venta Mensual</h3>
                    <h4Venta Mensual de <b>'.$mes.'</b> del <b>'.date("Y").'</b></h4>
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficos">
                          <p id="diasTotalMes" style="display: none;">'.$diasTotalMes.'</p>
                          <p id="mesActual" style="display: none;">'.$totalVentaMes.'</p>
                          <p id="mAnterior" style="display: none;">'.$vAnt.'</p>
                          <p id="mejorMes" style="display: none;">'.$vMejor.'</p>
                          <p id="mesMej" style="display: none;">'.$mesMej.'</p>
                          <canvas id="areaChart" style="height:450px;"></canvas>
                        </div>
                        <script src="../intranet/js/Chart.js"></script>
                        <script src="../intranet/js/graficas.js"></script>
                      </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <span class="text-redGraf">
                          Ventas del Mes pasado<br /><b style="font-size: 2em;">$ '.number_format($vAnt, 2, ".", ",").'</b>
                        </span>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <span class="text-blue">
                          Venta Actual<br /><b style="font-size: 2em;">$ '.number_format($totalVentaMes, 2, ".", ",").'</b>
                        </span>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <span class="text-green">
                          Ventas del Mejor Mes<br /><b style="font-size: 2em;">$ '.number_format($vMejor, 2, ".", ",").'</b>
                        </span>
                        <p class="text-green" style="font-weight:bold">'.$mesMej.' '.$anioAnterior.'</p>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                      <h1 class="display-4">Proyección de Cierre</h1>
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                          <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <p class="lead"><b>Ingrese los días del mes actual a calcular</b></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <input id="diasActual" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 25" required>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                          <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <p class="lead"><b>Ingrese los días del mes anterior a calcular</b></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <input id="diasAnterior" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 20" required>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                          <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <p class="lead"><b>Ingrese los días que han pasado</b></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                              <input id="diasConteo" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 11" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="botonCalcular" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 10px;">
                        <input class="btn btn-success" type="submit" name="" value="Calcular" onClick="calcular();">
                      </div>
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="ventaPorDiaActual"></div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="ventaPorDiaAnterior"></div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="pronosticoMensual"></div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="proyeccionCierre"></div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="ventaPorDiaIgualar"></div>
                      </div>
                      <script src="../intranet/js/calculos.js"></script>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <h5 class="text-center lead">Se presenta el reporte de ventas al mes, en comparación con el anterior y con el mejor mes.</h5>
                      <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row infoCard">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <h3>Reporte de Venta</h3>
                        <h4 class="text-center"><b>'.date("Y").' <em style="font-size: 0.5em;">Vs</em> '.$anioAnterior.' <em style="font-size: 0.5em;">Vs</em> Empresa</b></h4>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <canvas id="areaChartAnual" style="height:250px"></canvas>
                      </div>
                      <script src="../intranet/js/Chart.js"></script>
                      <script src="../intranet/js/graficas.js"></script>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <p class="lead" style="color:#3adcf4"><b style="font-size: 1em;">'.$anioAnterior.'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearAnterior, 2, ".", ",").'</b></p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                            <p class="lead text-redGraf" style="color:#b109ab"><b style="font-size: 1em;">'.date("Y").'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearActual , 2, ".", ",").'</b></p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                          <p class="lead text-redGraf"><b style="font-size: 1em;">Empresa '.date("Y").'</b><br /><b style="font-size: 1.5em;">$ '.number_format($totalYearEmpresa, 2, ".", ",").'</b></p>
                          </div>
                        </div>
                        <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';

      $get030DiasDist = "SELECT 
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -30
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
      $resultGet030Dist = mysqli_query($getConnection,$get030DiasDist);
      $row030Dist = mysqli_fetch_array($resultGet030Dist);
      if($row030Dist === NULL){
        $dias030DistF = 0;
      } else {
        $dias030DistF = $row030Dist[0]*(-1);
      }
      $dias030Dist = number_format($dias030DistF, 2, ".", ",");

      $get3060DiasDist = "SELECT
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -60
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -30";
      $resultGet3060Dist = mysqli_query($getConnection,$get3060DiasDist);
      $row3060Dist = mysqli_fetch_array($resultGet3060Dist);
      if($row3060Dist === NULL){
        $dias3060DistF = 0;
      } else {
        $dias3060DistF = $row3060Dist[0]*(-1);
      }
      $dias3060Dist = number_format($dias3060DistF, 2, ".", ",");

      $get6090DiasDist = "SELECT 
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                      AND c.vendedorid = $perid
                      AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -90
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -60";
      $resultGet6090Dist = mysqli_query($getConnection,$get6090DiasDist);
      $row6090Dist = mysqli_fetch_array($resultGet6090Dist);
      if($row6090Dist === NULL){
        $dias6090DistF = 0;
      } else {
        $dias6090DistF = $row6090Dist[0]*(-1);
      }
      $dias6090Dist = number_format($dias6090DistF, 2, ".", ",");

      $get90DiasDist = "SELECT 
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -90";
      $resultGet90Dist = mysqli_query($getConnection,$get90DiasDist);
      $row90Dist = mysqli_fetch_array($resultGet90Dist);
      if($row90Dist === NULL){
        $dias90DistF = 0;
      } else {
        $dias90DistF = $row90Dist[0]*(-1);
      }
      $dias90Dist = number_format($dias90DistF, 2, ".", ",");

      $print .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                      <h3>Cuentas por Cobrar</h3>
                      <h4>Cartera Vencida Total</h4>
                      <p class="text-red" style="font-weight:bold;font-size: 2em;">$ '.$Morosidad.'</p>
                      <table class="table table-striped table-dark">
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
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias030Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 1);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias030Dist.'</td>';
    }

    if($dias3060Dist > 0){
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias3060Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 2);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias3060Dist.'</td>';
    }

    if($dias6090Dist > 0){
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias6090Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 3);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias6090Dist.'</td>';
    }

    if($dias90Dist > 0){
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias90Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 4);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias90Dist.'</td>';
    }

    $print .=               '</th>
                          </tr>
                        </tbody>
                      </table>
                      <h5>Se presenta el reporte de cuentas por cobrar.</h5>
                      <p class="lead">La información mostrada es de solo carácter informativo.</p>
                    </div>
                  </div>
                </div>';

    echo $print;
    $getConnection->close();
    $mysqliCon->close();
  }

  private function getDashBoardAsesor($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $perid = $_SESSION["data"]["id"];

    //Se define las fechas y trimestres
    $dia  = date("Y-m-d");
    $year=date('Y');
    $month=date('m');
    $day=date('d');
    $dv = date('D');
    switch($dv){
      case 'Mon':
        $diaVis = 'L';
        break;
      case 'Tue':
        $diaVis = 'I';
        break;
      case 'Wed':
        $diaVis = 'M';
        break;
      case 'Thu':
        $diaVis = 'J';
        break;
      case 'Fri':
        $diaVis = 'V';
        break;
    }
    // $diaL = new DateTime('2018-04-16');
    // $diaI = new DateTime('2018-04-17');
    // $diaM = new DateTime('2018-04-18');
    // $diaJ = new DateTime('2018-04-19');
    // $diaV = new DateTime('2018-04-20');

    // echo $diaL->format('D');
    // echo $diaI->format('D');
    // echo $diaM->format('D');
    // echo $diaJ->format('D');
    // echo $diaV->format('D');


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
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $queryPerDat = "SELECT v.foto, v.tel, v.correo
                    FROM vendedores v
                    WHERE vendedorid = $perid";

    $resultadoPerDat = mysqli_query($mysqliCon,$queryPerDat);
    $filaPerDat = mysqli_fetch_array($resultadoPerDat);
    $foto = $filaPerDat[0];
    $tel = $filaPerDat[1];
    $correo = $filaPerDat[2];
    
    $queryPer = "SELECT p.nombre, p.ingreso, p.sermov
                    FROM per p
                    WHERE perid = $perid";

    $resultadoPer = mysqli_query($getConnection,$queryPer);
    $filaPer = mysqli_fetch_array($resultadoPer);
    $nombre = $filaPer[0];
    $ingreso = $filaPer[1];
    $sermov = $filaPer[2];

    if($sermov == 1){
      $zona = 1;
    } elseif($sermov == 2) {
      $zona = 2;
    } else {
      $zona = 0;
    }

    //Se hace la busqueda de ventas totales del Dia
    $queryVtaDia = "SELECT SUM(SUBTOTAL2) AS total, COUNT(SUBTOTAL2) AS contar
                            FROM doc
                          WHERE vendedorid = $perid
                            AND fecha = '$dia'
                            AND tipo = 'F'
                            AND subtotal2 > 0
                            AND FECCAN = 0";
    $resultQueryDia = mysqli_query($getConnection,$queryVtaDia);
    $qVtaDia = mysqli_fetch_row($resultQueryDia);
    if($qVtaDia === NULL){
      $qPedDia = 0;
      $totalVentaDia = 0;
    } else {
      $qPedDia = $qVtaDia[1];
      $totalVentaDia = $qVtaDia[0];
    }
    $formatTotalVentaDia = number_format($totalVentaDia, 2, '.',',');

    //Se hace la busqueda de ventas totales del Mes
    $diaFinal = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $diaFinal, $year));
    $queryVtaMes = "SELECT SUM(SUBTOTAL2) AS total
                            FROM doc
                          WHERE vendedorid = $perid
                            AND (
                                fecha <= '$ultimoDiaMes'
                                AND fecha >= '$primerDiaMes' 
                                )
                            AND tipo = 'F'
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
    $queryVtaTotalDiaAnt = "SELECT SUM(SUBTOTAL2) AS total
                                  FROM doc
                                  WHERE vendedorid = $perid
                                    AND fecha >= '".$fecAnteIni."'
                                    AND fecha <= '".$fecAnteFin."'
                                    AND tipo = 'F'
                                    AND subtotal2 > 0
                                    AND FECCAN = 0";
    $resultQueryvtdAnt = $getConnection->query($queryVtaTotalDiaAnt);
    $qVtDAnt = mysqli_fetch_array($resultQueryvtdAnt);
    if($qVtDAnt === NULL){
      $vAnt = 0;
    } else {
      $vAnt = $qVtDAnt['total'];
    }

    // TODO hacer consultas por vendedor, por tipo de cliente y por tiempo de morosidad.
    $getMorosidad = "SELECT 
                      SUM(d.totalpagado - d.total) as TotalDeuda
                      FROM doc d
                        RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                        RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                      WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidad = mysqli_query($getConnection,$getMorosidad);
    $rowMorosidad = mysqli_fetch_array($resultGetMorosidad);
    if($rowMorosidad === NULL){
    $MorosidadF = 0;
    } else {
    $MorosidadF = $rowMorosidad[0]*(-1);
    }
    $Morosidad = number_format($MorosidadF, 2, ".", ",");

    // Buscamos la cobranza del día
    $getMorosidadDia = "SELECT 
                      SUM(d.totalpagado - d.total) as CobranzaDia
                      FROM doc d
                        RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                        RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                      WHERE c.diavis = '$diaVis'
						            AND d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidadDia = mysqli_query($getConnection,$getMorosidadDia);
    $rowMorosidadDia = mysqli_fetch_array($resultGetMorosidadDia);
    if($rowMorosidadDia === NULL){
    $MorosidadFDia = 0;
    } else {
    $MorosidadFDia = $rowMorosidadDia[0]*(-1);
    }
    $MorosidadDia = number_format($MorosidadFDia, 2, ".", ",");

    // Buscamos los pagos de los clientes con morosidad del día
    $diaInicio = date('Y-m-d 00:00:00');
    $diaFin = date('Y-m-d 23:59:59');
    $getMorosidadPagoDia = "SELECT 
                              sum(pagado) as PagoCobDia
                              FROM doc d
                                LEFT OUTER JOIN cli c ON c.clienteid = d.clienteid
                                LEFT OUTER JOIN pagdoc pd ON pd.docid = d.docid
                              WHERE c.diavis = '$diaVis'
                                AND (fechapag >= '$diaInicio' AND fechapag <= '$diaFin')
                                AND d.total > d.totalpagado
                                AND c.vendedorid = $perid
                                AND (
                                      d.tipo = 'F'
                                      OR d.tipo = 'N'
                                    )
                                AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
    $resultGetMorosidadPagoDia = mysqli_query($getConnection,$getMorosidadPagoDia);
    $rowMorosidadPagoDia = mysqli_fetch_array($resultGetMorosidadPagoDia);
    if($rowMorosidadPagoDia === NULL){
      $MorosidadFPagoDia = 0;
    } else {
      $MorosidadFPagoDia = $rowMorosidadPagoDia[0];
    }
    $MorosidadPagoDia = number_format($MorosidadFPagoDia, 2, ".", ",");

        /*echo          "</div>
                    </div>
                  </section>
                </div>
              </div>";*/

    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row infoCard">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                            <img src="../img/vendedores/'.$foto.'" class="rounded-circle img" alt="'.$foto.'" />
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                            <h5>
                              <span><b>#'.$perid.'</b></span> - '.$nombre.'
                            </h5>
                            <h5>
                              <small class="text-green">ZONA '.$zona.'</small>
                            </h5>
                            <h5>
                              <span>Tel.: '.$tel.'</span>
                            </h5>
                            <p style="font-size: .8em;"><span>Correo: '.$correo.'</span></p>
                            <a class="btn btn-danger btn-enl btn-block" href="javascript:location.reload(true)">Borrar Historial</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Ventas al día</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-center" value="$ '.$formatTotalVentaDia.'" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Pedidos al día</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-center" value="'.$qPedDia.'" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Ventas al mes</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-center" value="$ '.$formatTotalVentaMes.'" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Cobrado</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-center" value="$ '.$MorosidadPagoDia.'" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Cartera Vencida</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-red text-center" value="$ '.$Morosidad.'" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Cobranza del día</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-red text-center" value="$ '.$MorosidadDia.'" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Código a Buscar</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-center" id="codigo" onChange="existencias8(); existencias8Name(); existencias8Costo();" placeholder="Código"/>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Existencias</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                      <input type="text" class="form-control text-center" id="existencias8" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Descripción</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <textarea type="text" class="form-control text-center" rows="2" id="existencias8name" readonly></textarea>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                                  <span>Precio</span>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                    <input type="text" class="form-control text-center" id="existencia8costo" readonly />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <script>
                              // $("#existencias8").hide();
                              // $("#existencias8name").hide();
                              function existencias8(){
                                var codigo = document.getElementById("codigo").value;
                                
                                $.post("../php/busquedas/existencias8.php", {codigo: codigo}, function(existencias){
                                  // console.log(existencias);
                                  document.getElementById("existencias8").value = existencias;
                                  // $("#existencias8").show();
                                });
                              }
                              function existencias8Name(){
                                var codigo = document.getElementById("codigo").value;
                                
                                $.post("../php/busquedas/existencia8name.php", {codigo: codigo}, function(existencias){
                                  // console.log(existencias);
                                  document.getElementById("existencias8name").value = existencias;
                                  // $("#existencias8name").show();
                                });
                              }
                              function existencias8Costo(){
                                var codigo = document.getElementById("codigo").value;
                                
                                $.post("../php/busquedas/existencia8costo.php", {codigo: codigo}, function(existencias){
                                  // console.log(existencias);
                                  document.getElementById("existencia8costo").value = existencias;
                                  // $("#existencia8costo").show();
                                });
                              }
                            </script>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficasGeneral">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                    <h3>Venta Mensual</h3>
                    <h4Venta Mensual de <b>'.$mes.'</b> del <b>'.date("Y").'</b></h4>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 graficos">
                        <p id="diasTotalMes" style="display: none;">'.$diasTotalMes.'</p>
                        <p id="mesActual" style="display: none;">'.$totalVentaMes.'</p>
                        <p id="mAnterior" style="display: none;">'.$vAnt.'</p>
                        <canvas id="areaChart" style="height:450px;"></canvas>
                      </div>
                      <script src="../intranet/js/Chart.js"></script>
                      <script src="../intranet/js/graficas.js"></script>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center paddingT">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <span class="text-redGraf">
                      Ventas del Mes pasado<br /><b style="font-size: 2em;">$ '.number_format($vAnt, 2, ".", ",").'</b>
                    </span>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                    <span class="text-blue">
                      Venta Actual<br /><b style="font-size: 2em;">$ '.number_format($totalVentaMes, 2, ".", ",").'</b>
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center paddingT paddingB">
                <div class="row infoCard2">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                    <h1 class="display-4">Proyección de Cierre</h1>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <p class="lead"><b>Ingrese los días del mes actual a calcular</b></p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <input id="diasActual" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 25" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <p class="lead"><b>Ingrese los días del mes anterior a calcular</b></p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <input id="diasAnterior" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 20" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <p class="lead"><b>Ingrese los días que han pasado</b></p>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <input id="diasConteo" style="text-align:center;border: 1px solid #DBE1EB;font-size: 18px;font-family: Arial, Verdana;padding-left: 7px;padding-right: 7px;padding-top: 10px;padding-bottom: 10px;border-radius: 4px;-moz-border-radius: 4px;-webkit-border-radius: 4px;-o-border-radius: 4px;background: #FFFFFF;background: linear-gradient(left, #FFFFFF, #F7F9FA);background: -moz-linear-gradient(left, #FFFFFF, #F7F9FA);background: -webkit-linear-gradient(left, #FFFFFF, #F7F9FA);background: -o-linear-gradient(left, #FFFFFF, #F7F9FA);color: #2E3133;" type="number" placeholder="Eje. 11" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div id="botonCalcular" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" style="padding: 10px;">
                      <input class="btn btn-success" type="submit" name="" value="Calcular" onClick="calcular();">
                    </div>
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="ventaPorDiaActual"></div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="ventaPorDiaAnterior"></div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="pronosticoMensual"></div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6" id="proyeccionCierre"></div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="ventaPorDiaIgualar"></div>
                    </div>
                    <script src="../intranet/js/calculos.js"></script>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h5 class="text-center lead">Se presenta el reporte de ventas al mes, en comparación con el anterior y con el mejor mes.</h5>
                    <p class="text-center lead">La información mostrada es de solo carácter informativo.</p>
                  </div>
                </div>
              </div>';

      $get030DiasDist = "SELECT 
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -30
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < 0";
      $resultGet030Dist = mysqli_query($getConnection,$get030DiasDist);
      $row030Dist = mysqli_fetch_array($resultGet030Dist);
      if($row030Dist === NULL){
        $dias030DistF = 0;
      } else {
        $dias030DistF = $row030Dist[0]*(-1);
      }
      $dias030Dist = number_format($dias030DistF, 2, ".", ",");

      $get3060DiasDist = "SELECT
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -60
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -30";
      $resultGet3060Dist = mysqli_query($getConnection,$get3060DiasDist);
      $row3060Dist = mysqli_fetch_array($resultGet3060Dist);
      if($row3060Dist === NULL){
        $dias3060DistF = 0;
      } else {
        $dias3060DistF = $row3060Dist[0]*(-1);
      }
      $dias3060Dist = number_format($dias3060DistF, 2, ".", ",");

      $get6090DiasDist = "SELECT 
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                      AND c.vendedorid = $perid
                      AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) >= -90
                      AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -60";
      $resultGet6090Dist = mysqli_query($getConnection,$get6090DiasDist);
      $row6090Dist = mysqli_fetch_array($resultGet6090Dist);
      if($row6090Dist === NULL){
        $dias6090DistF = 0;
      } else {
        $dias6090DistF = $row6090Dist[0]*(-1);
      }
      $dias6090Dist = number_format($dias6090DistF, 2, ".", ",");

      $get90DiasDist = "SELECT 
                    SUM(d.totalpagado - d.total) as TotalDeuda
                    FROM doc d
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                    WHERE d.total > d.totalpagado
                        AND c.vendedorid = $perid
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'N'
                            )
                        AND (SELECT DATEDIFF(d.vence, '".$dia."')) < -90";
      $resultGet90Dist = mysqli_query($getConnection,$get90DiasDist);
      $row90Dist = mysqli_fetch_array($resultGet90Dist);
      if($row90Dist === NULL){
        $dias90DistF = 0;
      } else {
        $dias90DistF = $row90Dist[0]*(-1);
      }
      $dias90Dist = number_format($dias90DistF, 2, ".", ",");

      $print .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT paddingB">
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                      <h3>Cuentas por Cobrar</h3>
                      <h4>Cartera Vencida Total</h4>
                      <p class="text-red" style="font-weight:bold;font-size: 2em;">$ '.$Morosidad.'</p>
                      <table class="table table-striped table-dark">
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
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias030Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 1);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias030Dist.'</td>';
    }

    if($dias3060Dist > 0){
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias3060Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 2);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias3060Dist.'</td>';
    }

    if($dias6090Dist > 0){
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias6090Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 3);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias6090Dist.'</td>';
    }

    if($dias90Dist > 0){
    $print .=                 '<td class="text-center text-redGraf" style="font-weight:bold;">$ '.$dias90Dist.' <a href="#" class="text-redGraf" onClick="showMorosidad('.$perid.', 4);"><i class="fa fa-tags" aria-hidden="true"></i></a></td>';
    } else {
    $print .=                 '<td class="text-center">$ '.$dias90Dist.'</td>';
    }

    $print .=               '</th>
                          </tr>
                        </tbody>
                      </table>
                      <h5>Se presenta el reporte de cuentas por cobrar.</h5>
                      <p class="lead">La información mostrada es de solo carácter informativo.</p>
                    </div>
                  </div>
                </div>';

    echo $print;
    $getConnection->close();
    $mysqliCon->close();
  }

  private function getClientesNuevosMes($params) {
    $paramFunctions   = new Util();
    $paramDb          = new Database();
    $getConnection    = $paramDb->GetLink();

    $month = date('m');

    //Sacamos el mes en que estamos
    switch ($month) {
      case 1:
        $diasTotalMes = 31;
      break;
      case 2:
        $diasTotalMes = 28;
      break;
      case 3:
        $diasTotalMes = 31;
      break;
      case 4:
        $diasTotalMes = 30;
      break;
      case 5:
        $diasTotalMes = 31;
      break;
      case 6:
        $diasTotalMes = 30;
      break;
      case 7:
        $diasTotalMes = 31;
      break;
      case 8:
        $diasTotalMes = 31;
      break;
      case 9:
        $diasTotalMes = 30;
      break;
      case 10:
        $diasTotalMes = 31;
      break;
      case 11:
        $diasTotalMes = 30;
      break;
      case 12:
        $diasTotalMes = 31;
      break;
    }

    $fechaInicioVenc = date('Y-m-01');
    $fechaFinalVenc = date('Y-m-'.$diasTotalMes.'');

    $queryVendedores = "SELECT perid, nombre FROM per WHERE grupo = 'MV' AND categoria NOT LIKE '00' AND sermov > 0";
    $vendedoresEncontrados = mysqli_query($getConnection,$queryVendedores);
    $numRow = mysqli_num_rows($vendedoresEncontrados);
    $i = 1;
    $print = '<div class="container paddingT">
              <h4 class="h4 text-center">CLIENTES NUEVOS <span class="text-tomato">POR VENDEDOR</span></h4>
              <div id="tablaClientesNuevos" class="table-responsive">
                <table class="table table-dark">
                  <thead>
                    <tr class="text-center">
                      <th scope="col">#</th>
                      <th scope="col">VENDEDOR</th>
                      <th scope="col">CLIENTES NUEVOS</th>
                    </tr>
                  </thead>
                  <tbody>';
    while($row= mysqli_fetch_array($vendedoresEncontrados)){
      $vendedor = $row["nombre"];
      $perid = $row["perid"];

      $queryBuscarCli = "SELECT COUNT(c.clienteid) as cantidad
                          FROM cli c
                          WHERE c.vendedorid = $perid
                            AND (
                                  c.fecaltcli < '$fechaFinalVenc'
                                  AND c.fecaltcli > '$fechaInicioVenc'
                                )
                            AND c.catalogo NOT LIKE 'W'";
      $clientesEncontrados = mysqli_query($getConnection, $queryBuscarCli);
      $rowC = mysqli_fetch_array($clientesEncontrados);
      $numCl = $rowC["cantidad"];
      // var_dump($rowC);

      $print .=     '<tr class="text-center">
                      <th scope="row">'.$i.'</th>
                      <td>'.$vendedor.'</td>
                      <td class="text-tomato">'.$numCl.'</td>
                    </tr>';
      $i++;
    }
    $print .=     '</tbody>
                </table>
              </div>
              // <script type="text/javascript">
              //   var f=new Date();
                
              //   function clientesNuevos(){
              //     xmlhttp=new XMLHttpRequest();
              //     xmlhttp.onreadystatechange=function() {
              //       document.getElementById("tablaClientesNuevos").innerHTML=xmlhttp.responseText;
              //     }
              //     xmlhttp.open("POST","../php/busquedas/clientesnuevosvendedores.php", true);
              //     xmlhttp.send();
              //     console.log(xmlhttp);
              //   }
              //   setInterval(clientesNuevos, 10000);
                
              // </script>
            </div>';
    echo $print;
    $getConnection->close();
    // $mysqliCon->close();
  }

  private function ShowDetailMor($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $perid = $paramDb->SecureInput($params["perid"]);
    $tiempoMor = $paramDb->SecureInput($params["tiempoMor"]);
    $hoyMor = date("Y-m-d");
    // TODO buscar morosidad por el tipo de tiempo, vendedor y lista de cliente
    $getMor = "SELECT c.clienteid, c.numero, c.nombre, dom.direccion, dom.numero as numerocli,
                dom.interior, dom.colonia, dom.ciudad, dom.municipio, dom.estado, dom.cp, cfd.folio,
                (d.totalpagado - d.total) as total, (SELECT DATEDIFF(d.vence, '".$hoyMor."')) as dias,
                p.nombre as nombreVen
                    FROM doc d
                      RIGHT OUTER JOIN cfd ON cfd.docid = d.docid
                      RIGHT OUTER JOIN cli c ON c.clienteid = d.clienteid
                      RIGHT OUTER JOIN dom ON dom.clienteid = d.clienteid
                      RIGHT OUTER JOIN per p ON p.perid = c.vendedorid
                    WHERE d.total > d.totalpagado";
    if($perid > 0){
      $getMor .=      " AND c.vendedorid = $perid
                        AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )";
    } else {
      $getMor .=      " AND (
                          d.tipo = 'F'
                          OR d.tipo = 'N'
                        )";
    }
    // dependiendo del tiempo de morosidad (0-30 dias, etc) será el tipo de busqueda
    if($tiempoMor == 1){
      $periodo = "0-30 días";
      $getMor .=      " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) >= -30
                      AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < 0
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } elseif($tiempoMor == 2){
      $periodo = "31-60 días";
      $getMor .=      " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) >= -60
                      AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < -30
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } elseif($tiempoMor == 3){
      $periodo = "61-90 días";
      $getMor .=      " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) >= -90
                      AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < -60
                    ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } elseif($tiempoMor == 4){
      $periodo = "mayor de 90 días";
      $getMor .=      " AND (SELECT DATEDIFF(d.vence, '".$hoyMor."')) < -90
                      ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    } else {
      $periodo = "Total";
      $getMor .=      " ORDER BY (SELECT DATEDIFF(d.vence, '".$hoyMor."')) ASC";
    }
    $getMorosidadEx = $paramDb->Query($getMor);
    try {
      $numRow = $paramDb->NumRows();
      $rows = $paramDb->Rows();

      if($numRow > 0) {
        $total = 0;
        $position = 0;

        // TODO make validation for user: registrado, publico and add column for get price
        $headers = ["#", "NUMERO", "CLIENTE", "FOLIO", "VENDEDOR", "DIAS VENCIDOS", "IMPORTE"];
        $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

        echo '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <h3>CARTERA <spam class="text-tomato">VENCIDA</spam></h3>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 edoctaScroll">
                    <div class="row">';

        // TODO hacer boton de regresar para el vendedor
        if($perid > 0){
          $linkFunctionPersonal = "showPersonal(".$perid.")";
          echo        '<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="max-width:30px;padding:0;">
                        <a class="nav-link" href="#" onClick="'.$linkFunctionPersonal.'">
                          <i class="fas fa-arrow-alt-circle-left fa-lg" aria-hidden="true"></i>
                        </a>
                      </div>
                      <div class="col-11 col-sm-11 col-md-11 col-lg-11 col-xl-11">
                        <h4>
                          Clientes Morosos en <b>'.$periodo.'</b> a la fecha de <b>'.$hoyMor.'</b>
                        </h4>
                      </div>';
        } else {
          $linkFunctionPersonal = 'showInformation("dashBoardDireccion")';
          echo        '<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" style="max-width:30px;padding:0;">
                        <a class="nav-link" href="#" onClick="'.$linkFunctionPersonal.'">
                          <i class="fas fa-arrow-alt-circle-left fa-lg" aria-hidden="true"></i>
                        </a>
                      </div>
                      <div class="col-11 col-sm-11 col-md-11 col-lg-11 col-xl-11">
                        <h4>
                          Clientes Morosos en <b>'.$periodo.'</b> a la fecha de <b>'.$hoyMor.'</b>
                        </h4>
                      </div>';
        }
        echo        "</div>";
        $print = $paramFunctions->drawTableHeader($headers, $classPerColumn);
        $i = 0;
        $suma = 0;
        foreach ($rows as $row) {
          $clienteid = $row["clienteid"];
          $numero = $row["numero"];
          $cliente = $row["nombre"];
          $direccion = $row["direccion"];
          $numerocli = $row["numerocli"];
          $interior = $row["interior"];
          $colonia = $row["colonia"];
          $ciudad = $row["ciudad"];
          $municipio = $row["municipio"];
          $estado = $row["estado"];
          $cp = $row["cp"];
          $folio = $row["folio"];
          $importe = $row["total"];
          $diasVencidos = $row["dias"];
          $nomvend = $row["nombreVen"];
          $i++;
          $suma += $importe;
          // set format
          $formatoImporte = number_format($importe, 2, '.', ',');
          $print .=     "<tr>";
          $print .=       "<td class='text-center'>$i</td>";
          $print .=       "<td class='text-center'>$numero</td>";
          $print .=       "<td class='text-center' width='700'>
                            <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                              <div class='row'>
                                <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 hr'>
                                  $cliente
                                </div>
                                <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                                  Dirección: <span class='text-tomato'>$direccion</span> No. <span class='text-tomato'>$numerocli</span> Int. <span class='text-tomato'>$interior</span> Colonia: <span class='text-tomato'>$colonia</span>
                                </div>
                                <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
                                  Ciudad: <span class='text-tomato'>$ciudad</span> Municipio: <span class='text-tomato'>$municipio</span> Estado: <span class='text-tomato'>$estado</span> C.P.: <span class='text-tomato'>$cp</span>
                                </div>
                          </td>";
          $print .=       "<td class='text-center'>$folio</td>";
          $print .=       "<td class='text-center'>$nomvend</td>";
          $print .=       "<td class='text-center'>$diasVencidos</td>";
          $print .=       "<td class='text-center text-redGraf'>MX$ $formatoImporte</td>";
          $position++;
        }
        $print .=       "</tr>
                        <tr>
                          <th colspan='5' scope='row' style='font-size:2em;text-align:right;'>TOTAL</th>
                          <td colspan='6' class='text-center text-redGraf' style='font-size:2em;font-weight:bold;'>MX$ ".number_format($suma, 2, '.', ',')."</td>
                        </tr>
                    </table>
                  </div>
                </div>";
        echo $print;
      } else {
        $print = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 hg910 centrarSep">';
        $print .= "<div class='row'>
                    <div class='col-md-12 text-center'>
                      <h4>No hay cuentas pendiente o con saldo.</h4>
                      <h4>Su estado de cuenta esta limpio.</h4>
                    </div>
                  </div>
                </div>";
      }
      echo    "</div>";
    } catch (Exception $e){
      echo "Problema al listar la morosidad: " . $e->getMessage();
    }
    $getConnection->close();
  }

  private function getDashBoardMesaQro($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    $_SESSION["codigoEspec"] = [];
    $_SESSION["listaRuta"] = [];
    $a = [];

    // send JSON to get on js and process it request
    $params = array("location"=>"addProduct-to-CFDIcart-partner",
                    "url"=>"../php/shopping/shopping.php",
                    "booleanResponse"=>true,
                    "divResultID"=>"resultFindProductByCFDI");
    $paramsSend = json_encode($params);

    $print = "<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 panel'>
					      <div class='row'>";
    $print .=     "<div class='col-12 col-sm-12 col-md-12 col-lg-3 col-xs-3'>";
    $print .= 		  "<input type='text' class='form-control text-center' id='findFolio' onChange='generalFunctionToRequest($paramsSend)' placeholder='Ingrese folio de factura'/></p>";
    $print .= 		"</div>";
    $print .=     "<div class='col-12 col-sm-12 col-md-12 col-lg-3 col-xs-3'>
                    <div id='espere' class='centrar' style='display:none;'>
                      <p>Un momento por favor, estamos procesando su solicitud.<img src='../img/loading.gif' width='100'/>
                    </div>
                  </div>";
		$print .=     "<div class='col-12 col-sm-12 col-md-12 col-lg-3 col-xs-3'></div>";
		$print .=     "<div class='col-12 col-sm-12 col-md-12 col-lg-3 col-xs-3'></div>";
		$print .=		  "<div class='form-group col-md-12'>"; // result response
		// display response ajax products, when user search by: code, key or title
		$print .= 			"<div id='resultFindProductByCFDI'></div>";
		$print .= 		"</div>";
		$print .=	  "</div>
				      </div>";
    echo $print;
    $getConnection->close();
  }

  private function getDashBoardMesa($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

    $buscarInvTub = "SELECT * FROM controlinv WHERE tipo = 1";
    $tubosEncTub = mysqli_query($mysqliCon,$buscarInvTub);

    $buscarInvPol = "SELECT * FROM controlinv WHERE tipo = 2";
    $tubosEncPol = mysqli_query($mysqliCon,$buscarInvPol);

    $print =  '<div class="col-12 paddingT paddingB">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <h1 class="display-4 text-center">Inventario</h1>
                <div class="row">
                  <div class="col-6 text-center paddingT paddingB">
                    <div class="row">
                      <div class="col-12 text-center">
                        <h3>RESUMEN DE TUBOS</h3>
                      </div>
                      <div class="col-12">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">CODIGO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">IDENTIFICACION</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">PRODUCTO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">TEQUIS</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">QUERETARO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">SOLICITAR</th>
                            </tr>
                          </thead>
                          <tbody>';
    while($rowTub = mysqli_fetch_row($tubosEncTub)){
      $codigo = $rowTub[1];
      $iden = $rowTub[2];
      $tx = $rowTub[3];
      $qro = $rowTub[4];

      $linkEditarProd = "getOutPipes('$codigo')";

      $buscarNomPro = "SELECT descripcio FROM inv WHERE clvprov = $codigo";
      $prodEnc = mysqli_query($getConnection,$buscarNomPro);
      $rowNom = mysqli_fetch_row($prodEnc);
      $nombre = $rowNom[0];
      $print .=             '<tr>
                              <th scope="row" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$codigo.'</th>
                              <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$iden.'</td>
                              <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$nombre.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$tx.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$qro.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;"><a class="nav-linkImg" href="#" onClick="'.$linkEditarProd.'">Pedir</a></td>
                            </tr>';
    }
    $print .=             '</tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 text-center paddingT paddingB">
                    <h3>RESUMEN DE POLVOS</h3>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">CODIGO</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">IDENTIFICACION</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">PRODUCTO</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">TEQUIS</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">QUERETARO</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">SOLICITAR</th>
                        </tr>
                      </thead>
                      <tbody>';
    while($rowTubPol = mysqli_fetch_row($tubosEncPol)){
      $codigoPol = $rowTubPol[1];
      $idenPol = $rowTubPol[2];
      $txPol = $rowTubPol[3];
      $qroPol = $rowTubPol[4];

      $linkEditarProd = "getOutPipes()";

      $buscarNomProPol = "SELECT descripcio FROM inv WHERE clvprov = $codigoPol";
      $prodEncPol = mysqli_query($getConnection,$buscarNomProPol);
      $rowNomPol = mysqli_fetch_row($prodEncPol);
      $nombrePol = $rowNomPol[0];
      
      $print .=         '<tr>
                          <th scope="row" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$codigoPol.'</th>
                          <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$idenPol.'</td>
                          <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$nombrePol.'</td>
                          <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$txPol.'</td>
                          <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$qroPol.'</td>
                          <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;"><a class="nav-linkImg" href="#" onClick="'.$linkEditarProd.'">Pedir</a></td>
                        </tr>';
    }
    $print .=         '</tbody>
                    </table>
                  </div>
                </div>
              </div>';

    echo $print;

    $mysqliCon->close();
    $getConnection->close();
  }

  private function getResumenInvTub($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

    $buscarInvTub = "SELECT * FROM controlinv WHERE tipo = 1";
    $tubosEncTub = mysqli_query($mysqliCon,$buscarInvTub);

    $buscarInvPol = "SELECT * FROM controlinv WHERE tipo = 2";
    $tubosEncPol = mysqli_query($mysqliCon,$buscarInvPol);

    $print =  '<div class="col-12 paddingT paddingB">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <h1 class="display-4 text-center">Inventario</h1>
                <div class="row">
                  <div class="col-12 text-center">
                    <button class="btn btn-primary" onClick="addInv();">Nuevo</button>
                  </div>
                  <div class="col-6 text-center paddingT paddingB">
                    <div class="row">
                      <div class="col-12 text-center">
                        <h3>RESUMEN DE TUBOS</h3>
                      </div>
                      <div class="col-12">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">CODIGO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">IDENTIFICACION</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">PRODUCTO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">TEQUIS</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">QUERETARO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">MODIFICAR</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">MAXIMO QRO</th>
                              <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">SOLICITAR</th>
                            </tr>
                          </thead>
                          <tbody>';
    while($rowTub = mysqli_fetch_row($tubosEncTub)){
      $codigo = $rowTub[1];
      $iden = $rowTub[2];
      $tx = $rowTub[3];
      $qro = $rowTub[4];
      $maxqro = $rowTub[6];

      $pedir = $maxqro - $qro;

      $linkEditarProd = "modInv($codigo)";

      $buscarNomPro = "SELECT descripcio FROM inv WHERE clvprov = $codigo";
      $prodEnc = mysqli_query($getConnection,$buscarNomPro);
      $rowNom = mysqli_fetch_row($prodEnc);
      $nombre = $rowNom[0];
      $print .=             '<tr>
                              <th scope="row" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$codigo.'</th>
                              <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$iden.'</td>
                              <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$nombre.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$tx.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$qro.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;"><a class="nav-linkImg" href="#" onClick="'.$linkEditarProd.'">Editar</a></td>';
      if($maxqro === NULL){
        $print .=             '<td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;"></td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;"></td>';
      }else{
        $print .=             '<td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$maxqro.'</td>
                              <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$pedir.'</td>';
      }                              
      $print .=             '</tr>';
    }
    $print .=             '</tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 text-center paddingT paddingB">
                    <h3>RESUMEN DE POLVOS</h3>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">CODIGO</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">IDENTIFICACION</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">PRODUCTO</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">TEQUIS</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">QUERETARO</th>
                          <th scope="col" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">MODIFICAR</th>
                        </tr>
                      </thead>
                      <tbody>';
    while($rowTubPol = mysqli_fetch_row($tubosEncPol)){
      $codigoPol = $rowTubPol[1];
      $idenPol = $rowTubPol[2];
      $txPol = $rowTubPol[3];
      $qroPol = $rowTubPol[4];

      $linkEditarProd = "modInv($codigoPol)";

      $buscarNomProPol = "SELECT descripcio FROM inv WHERE clvprov = $codigoPol";
      $prodEncPol = mysqli_query($getConnection,$buscarNomProPol);
      $rowNomPol = mysqli_fetch_row($prodEncPol);
      $nombrePol = $rowNomPol[0];
      
      $print .=         '<tr>
                          <th scope="row" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$codigoPol.'</th>
                          <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$idenPol.'</td>
                          <td style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$nombrePol.'</td>
                          <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$txPol.'</td>
                          <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;">'.$qroPol.'</td>
                          <td class="text-green" style="font-weight:bold;vertical-align:middle;font-size: .7em!important;padding: 0.3rem!important;"><a class="nav-linkImg" href="#" onClick="'.$linkEditarProd.'">Editar</a></td>
                        </tr>';
    }
    $print .=         '</tbody>
                    </table>
                  </div>
                </div>
              </div>';

    echo $print;
    $getConnection->close();
    $mysqliCon->close();
  }

  private function getDashBoardComp($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $print =  '<div class="container paddingT paddingB">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <h4 class="display-4 text-center">BUSQUEDA DE <span class="text-tomato">CODIGOS 8</span></h4>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                        <span>Código a Buscar</span>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                            <input type="text" class="form-control text-center" id="codigo" onChange="existencias8(); existencias8Name(); existencias8Costo();" placeholder="Código"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                        <span>Existencias</span>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                            <input type="text" class="form-control text-center" id="existencias8" readonly />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                        <span>Descripción</span>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                          <textarea type="text" class="form-control text-center" rows="2" id="existencias8name" readonly></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 text-input">
                        <span>Precio</span>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="row">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                          <input type="text" class="form-control text-center" id="existencia8costo" readonly />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <script>
                    // $("#existencias8").hide();
                    // $("#existencias8name").hide();
                    function existencias8(){
                      var codigo = document.getElementById("codigo").value;
                      
                      $.post("../php/busquedas/existencias8.php", {codigo: codigo}, function(existencias){
                        // console.log(existencias);
                        document.getElementById("existencias8").value = existencias;
                        // $("#existencias8").show();
                      });
                    }
                    function existencias8Name(){
                      var codigo = document.getElementById("codigo").value;
                      
                      $.post("../php/busquedas/existencia8name.php", {codigo: codigo}, function(existencias){
                        // console.log(existencias);
                        document.getElementById("existencias8name").value = existencias;
                        // $("#existencias8name").show();
                      });
                    }
                    function existencias8Costo(){
                      var codigo = document.getElementById("codigo").value;
                      
                      $.post("../php/busquedas/existencia8costo.php", {codigo: codigo}, function(existencias){
                        // console.log(existencias);
                        document.getElementById("existencia8costo").value = existencias;
                        // $("#existencia8costo").show();
                      });
                    }
                  </script>
                </div>
              </div>';

    echo $print;

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
                <div class="col-md-4 col-sm-4 col-xl-12">
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
                <div class="col-md-4 col-sm-4 col-xl-12">
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
                <div class="col-md-4 col-sm-4 col-xl-12">
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
                <div class="col-md-4 col-sm-4 col-xl-12">
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
                <div class="col-md-4 col-sm-4 col-xl-12">
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
                <div class="col-md-4 col-sm-4 col-xl-12">
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
                        <div class="col-sm-4 col-xl-6">';
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
                        <div class="col-sm-4 col-xl-6">
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
                        <div class="col-sm-4 col-xl-6">
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
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
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
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $getDatoVen = "SELECT p.nombre, p.perid
                FROM per p
                WHERE p.sermov = $zona";
    $DatoVenEnc = mysqli_query($getConnection,$getDatoVen);
    $filaTotal =$DatoVenEnc->num_rows;

    $exeQuGet = $paramDb->Query($getDatoVen);
    if($exeQuGet === false) {
      echo "error-query";
      return false;
    }

    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();

    $email = 0;
    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT hg910">
                <div class="row">
                  <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                    <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                    <img src="../img/barrafmo2.gif" width="200"/>
                  </div>';
    foreach($rows as $row) {
      $nombre = $row["nombre"];
      $perid = $row["perid"];
      $linkFunctionPersonal = "showPersonal(".$perid.")";
      
      $getFotoVen = "SELECT v.foto, v.tel
                        FROM vendedores v
                        WHERE v.vendedorid = $perid";
      $FotoVenEnc = mysqli_query($mysqliCon,$getFotoVen);
      $fotoVen =mysqli_fetch_array($FotoVenEnc);
      $foto = $fotoVen["foto"];

      $print .=   '<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                    <div class="text-center">
                      <a href="#" onclick="'.$linkFunctionPersonal.'">
                        <img class="img-fluid rounded-circle" src="../img/vendedores/'.$foto.'" alt="'.$foto.'" width="200">
                      </a>
                      <div class="card-block">
                        <h4 class="card-title">'.$nombre.'</h4>
                      </div>
                    </div>
                  </div>';
    }
    $print .=   '</div>
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
    $mysqliCon->close();
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
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $getDatoVen = "SELECT p.nombre, p.perid
                FROM per p
                WHERE p.sermov = $zona";
    $DatoVenEnc = mysqli_query($getConnection,$getDatoVen);
    $filaTotal =$DatoVenEnc->num_rows;

    $exeQuGet = $paramDb->Query($getDatoVen);
    if($exeQuGet === false) {
      echo "error-query";
      return false;
    }

    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();

    $email = 0;
    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT hg910">
                <div class="row">
                  <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                    <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                    <img src="../img/barrafmo2.gif" width="200"/>
                  </div>';
    foreach($rows as $row) {
      $nombre = $row["nombre"];
      $perid = $row["perid"];
      $linkFunctionPersonal = "showPersonal(".$perid.")";

      $getFotoVen = "SELECT v.foto, v.tel
                        FROM vendedores v
                        WHERE v.vendedorid = $perid";
      $FotoVenEnc = mysqli_query($mysqliCon,$getFotoVen);
      $fotoVen =mysqli_fetch_array($FotoVenEnc);
      $foto = $fotoVen["foto"];

      $print .=   '<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                    <div class="text-center">
                      <a href="#" onclick="'.$linkFunctionPersonal.'">
                        <img class="img-fluid rounded-circle" src="../img/vendedores/'.$foto.'" alt="'.$foto.'" width="200">
                      </a>
                      <div class="card-block">
                        <h4 class="card-title">'.$nombre.'</h4>
                      </div>
                    </div>
                  </div>';
    }
    $print .=   '</div>
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
    $mysqliCon->close();
  }

  private function getDashBoardSz($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

    if(isset($_SESSION["data"])) {
      $session = $_SESSION["data"];
    }

    $rol = $paramDb->SecureInput($session["rol"]);
    $clienteID = $paramDb->SecureInput($session["username"]);
    $id = $_SESSION["data"]["id"];
    $username = $_SESSION["data"]["name"];
    $pas2 = $_SESSION["data"]["pas2"];
    $pasAnt = $_SESSION["data"]["pasAnt"];
    $correo = $_SESSION["data"]["correo"];

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

    //Se hace la busqueda de ventas totales del Dia
    $dia  = date("Y-m-d");

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

    $getDatoVen = "SELECT p.nombre, p.perid
                FROM per p
                WHERE p.sermov = $zona";
    $DatoVenEnc = mysqli_query($getConnection,$getDatoVen);
    $filaTotal =mysqli_num_rows($DatoVenEnc);

    $exeQuGet = $paramDb->Query($getDatoVen);
    if($exeQuGet === false) {
      echo "error-query";
      return false;
    }

    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();

    $email = 0;
    $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                  <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                    <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                    <img src="../img/barrafmo2.gif" width="200"/>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                    <div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT paddingB text-center">
                        <h3>ASESORES DE <spam class="text-tomato">ZONA '.$zona.'</spam></h3>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="row">';
    foreach($rows as $row) {
      $nombre = $row["nombre"];
      $perid = $row["perid"];

      $getFotoVen = "SELECT v.tel, v.foto
                      FROM vendedores v
                      WHERE v.vendedorid = $perid";
      $FotoVenEnc = mysqli_query($mysqliCon,$getFotoVen);
      $rowFoto = mysqli_fetch_row($FotoVenEnc);
      $tel = $rowFoto[0];
      $foto = $rowFoto[1];

      // Pedidos y ventas por Bajar del día por asesor
      $queryPedDiaBajar = "SELECT COUNT(docid) AS PedidosBajar, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedBajar
                              FROM doc
                            WHERE vendedorid = $perid
                              AND fecha = '$dia'
                              AND tipo = 'C'
                              AND serie NOT LIKE 'CH'
                              AND subtotal2 > 0
                              AND FECCAN = 0
                              AND estado NOT LIKE 'C'";
      $resultQueryDiaBajar = $getConnection->query($queryPedDiaBajar);
      $qPedDiaBajar = mysqli_fetch_array($resultQueryDiaBajar);
      if($qPedDiaBajar === NULL){
        $PedBajar = 0;
        $sumBajar = 0;
      } else {
        //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
        $PedBajar = $qPedDiaBajar["PedidosBajar"];
        $SumaBajNs = $qPedDiaBajar["TotalPedBajar"];
      }
      $sumBajar = "$ ".number_format($SumaBajNs, 2, '.',',');

      // Pedidos y ventas por Surtir del día
      $queryPedDiaSurtir = "SELECT COUNT(docid) AS PedidosSurtir, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedSurtir
                              FROM doc
                            WHERE vendedorid = $perid
                              AND fecha = '$dia'
                              AND tipo = 'N'
                              AND serie NOT LIKE 'CH'
                              AND subtotal2 > 0
                              AND FECCAN = 0";
      $resultQueryDiaSurtir = $getConnection->query($queryPedDiaSurtir);
      $qPedDiaSurtir = mysqli_fetch_array($resultQueryDiaSurtir);
      if($qPedDiaSurtir === NULL){
        $PedSurt = 0;
        $sumSurt = 0;
      } else {
        //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
        $PedSurt = $qPedDiaSurtir["PedidosSurtir"];
        $SumSurNs = $qPedDiaSurtir["TotalPedSurtir"];
      }
      $sumSurt = "$ ".number_format($qPedDiaSurtir["TotalPedSurtir"], 2, '.',',');

      $linkFunctionPersonal = "showPersonal(".$perid.")";

      // Pedidos y ventas por Factura del día
      $queryPedDiaFactura = "SELECT COUNT(docid) AS PedidosFactura, SUM((SELECT (SUBTOTAL2 + SUBTOTAL1) FROM DUAL)) AS TotalPedFactura
                              FROM doc d
                              WHERE vendedorid = $perid
                                AND d.fecha = '$dia'
                                AND tipo = 'F'
                                AND serie NOT LIKE 'CH'
                                AND d.subtotal2 > 0
                                AND d.FECCAN = 0";
      $resultQueryDiaFactura = $getConnection->query($queryPedDiaFactura);
      $qPedDiaFactura = mysqli_fetch_array($resultQueryDiaFactura);
      if($qPedDiaFactura === NULL){
        $PedFactura = 0;
        $sumFactura = 0;
      } else {
        //TODO En vez de buscar el total de ventas, BUSCAR EL NUMERO DE PEDIDOS
        $PedFactura = $qPedDiaFactura["PedidosFactura"];
        $sumFacNS = $qPedDiaFactura["TotalPedFactura"];
      }
      $sumFactura = "$ ".number_format($qPedDiaFactura["TotalPedFactura"], 2, '.',',');

      $print .=           '<div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3 paddingT paddingB">
                            <div class="text-center">
                              <a href="#" onclick="'.$linkFunctionPersonal.'">
                                <img class="img-fluid rounded-circle" src="../img/vendedores/'.$foto.'" alt="'.$foto.'" width="200">
                              </a>
                              <div class="card-block">
                                <h4 class="card-title">'.$nombre.'</h4>
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <h6 class="h6 text-center text-tomato">POR BAJAR</h6>
                                    <p class="lead">'.$PedBajar.'</p>
                                    <p class="lead">'.$sumBajar.'</p>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <h6 class="h6 text-center text-tomato">POR SURTIR</h6>
                                    <p class="lead">'.$PedSurt.'</p>
                                    <p class="lead">'.$sumSurt.'</p>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                                    <h6 class="h6 text-center text-tomato">FACTURADO</h6>
                                    <p class="lead">'.$PedFactura.'</p>
                                    <p class="lead">'.$sumFactura.'</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>';
    }
    $print .=           '</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
    echo $print;
    $getConnection->close();
    $mysqliCon->close();
  }
  private function NuevosClientes($params){
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    $mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $perid = $_SESSION["data"]["id"];
    $rol = $_SESSION["data"]["rol"];
    // var_dump($_SESSION["data"]);

    $rs = "SELECT * FROM nuevoscli";
    $rsB = mysqli_query($mysqliCon, $rs);
    $rsNR = mysqli_num_rows($rsB);
    if($rsNR > 0){
      if($perid == 0 || $perid == NULL || $rol == 'cartera'){
        $perid = 0;

        $linkFunctionPersonal = "newCustomer(".$perid.")";
        $print = '<div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                      <h4 class="display-4">NUEVOS <span class="tomato">CLIENTES</span></h4>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="row">';
        while($row = mysqli_fetch_row($rsB)){
          $clienteid          = $row[0];
          $nombre             = utf8_encode($row[1]);
          $comercial          = utf8_encode($row[2]);
          $rfc                = $row[3];
          $fecalta            = $row[4];
          $direccion          = utf8_encode($row[5]);
          $cp                 = $row[6];
          $colonia            = utf8_encode($row[7]);
          $ciudad             = utf8_encode($row[8]);
          $tel                = $row[9];
          $cel                = $row[10];
          $email              = utf8_encode($row[11]);
          $credito            = number_format($row[12], 2, '.', ',');
          $diascred           = $row[13];
          $metpag             = $row[14];
          $hacerped           = utf8_encode($row[15]);
          $recibirped         = utf8_encode($row[16]);
          $mlocal             = $row[17];
          $tlocal             = $row[18];
          $ladode             = utf8_encode($row[19]);
          $frentede           = utf8_encode($row[20]);
          $vendedorid         = $row[21];
          $activo             = $row[22];

          $fichero_Solicitud  = $row[23];

          $observacion        = $row[36];
          $status             = $row[37];

          $perid = $vendedorid;

          $buscarVendedor = "SELECT nombre FROM per WHERE perid = $vendedorid";
          $vendedorEncontrado = $getConnection->query($buscarVendedor);
          $rowVen = mysqli_fetch_array($vendedorEncontrado);
          $nombreVendedor = $rowVen["nombre"];

          $buscarFotos = 'docs/';

          $print .=   '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB">
                        <div class="row">';
          if($activo === 'N'){
          $print .=       '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 infoCard">
                            <div class="row">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <div class="row">
                                  <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                    <label>Nombre:</label>
                                  </div>
                                  <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                    <span>'.$nombre.'</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <div class="row">
                                  <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                    <label>Negocio:</label>
                                  </div>
                                  <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                    <span>'.$comercial.'</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <div class="row">
                                  <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                    <label>R.F.C.:</label>
                                  </div>
                                  <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                    <span>'.$rfc.'</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                <div class="row">
                                  <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                    <label>Fecha de Alta:</label>
                                  </div>
                                  <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7 input-falsoComersRight">
                                    <span>'.$fecalta.'</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Dirección:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$direccion.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>C.P.:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$cp.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Colonia:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$colonia.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Ciudad:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$ciudad.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Local:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$tel.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Celular:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$cel.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Email:</label>
                                      </div>
                                      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                        <span>'.$email.'</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                        <label>Estatus</label>
                                      </div>';
          if($status == 1){
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight autorizado">
                                          <span>Autorizado</span>
                                        </div>';
          }else if($status == 2){
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight proceso">
                                          <span>Devuelto por Crédito y Cobranza</span>
                                        </div>';
          }else if($status == 3){
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight proceso">
                                          <span>Devuelto por Vendedor</span>
                                        </div>';
          }else{
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                          <span>Ninguno</span>
                                        </div>';
          }
          $print .=                   '</div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <label>Límite de Credito:</label>
                                          </div>
                                          <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5 input-falsoComersRight">
                                            <span>'.$credito.'</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <label>Días de Credito:</label>
                                          </div>
                                          <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5 input-falsoComersRight">
                                            <span>'.$diascred.'</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <label>Método de Pago:</label>
                                          </div>
                                          <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5 input-falsoComersRight">
                                            <span>'.$metpag.'</span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <label>Medidas del Local:</label>
                                          </div>
                                          <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5 input-falsoComersRight">
                                            <span>'.$mlocal.'</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <label>El local es:</label>
                                          </div>
                                          <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5 input-falsoComersRight">
                                            <span>'.$tlocal.'</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <label>Vendedor:</label>
                                          </div>
                                          <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8 input-falsoComersRight">
                                            <span>'.$nombreVendedor.'</span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 centrar">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <label>Levanta Pedido:</label>
                                          </div>
                                          <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8 input-falsoComersRight">
                                            <span>'.$hacerped.'</span>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <div class="row">
                                          <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            <label>Recibe Pedido:</label>
                                          </div>
                                          <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8 input-falsoComersRight">
                                            <span>'.$recibirped.'</span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                    <label>A lado de:</label>
                                  </div>
                                  <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 input-falsoComersLeft">
                                    <span>'.$ladode.'</span>
                                  </div>
                                  <div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                    <label>Frente de:</label>
                                  </div>
                                  <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 input-falsoComersLeft">
                                    <span>'.$frentede.'</span>
                                  </div>
                                </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT paddingB">
                                <div class="row">
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                    <ul class="navImg">
                                      <p>Imágenes</p>';
          if($fichero_Solicitud <> $buscarFotos){
            $print .=                 '<li><a class="nav-linkImg" href="../php/agregar/'.$fichero_Solicitud.'" target="_blanck">Solicitud</a></li>';
          }
          $print .=                 '</ul>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9 charlaCard">
                                    <ul class="navChar">
                                      <p>Historial de Charla</p>';
          $buscarCharla = 'SELECT charla, feccharla
                            FROM converCliNue
                            WHERE vendedorid = '.$vendedorid.'
                              AND clienteid = '.$clienteid.'
                            ORDER BY feccharla DESC';
          $charlaEncontrado = $mysqliCon->query($buscarCharla);
          $numRowsCha = mysqli_num_rows($charlaEncontrado);
          // var_dump($numRowsCha);
          if($numRowsCha > 0){
            while($rowChar = mysqli_fetch_array($charlaEncontrado)){
              $charla = $rowChar["charla"];
              $feccharla = $rowChar["feccharla"];

              $print .=               '<li>'.$feccharla.' - '.$charla.'</li>';
            }
          }else{
            $print .=                 '<li>Sin historial</li>';
          }
          $print .=                 '</ul>
                                  </div>
                                </div>
                              </div>';
          if($observacion <> NULL){
            $print .=         '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <p>Observaciones anteriores</p>
                                <div class="form-group">
                                  <textarea class="form-control" rows="2" readonly>'.utf8_encode($observacion).'</textarea>
                                </div>
                              </div>';
          }
          if($status <> 1){
            $linkEnviarComentario = "EnviarObservacion($clienteid, $perid, '$rol')";
            $datosAutorizado = array("clienteid" => $clienteid,
                                      "nombre" => $nombre,
                                      "nombreVendedor" => $nombreVendedor,
                                      "vendedorid" => $perid);
            $sendAuto = json_encode($datosAutorizado);
            // $linkDatosEmail = "Autorizar('$nombre', '$nombreVendedor')";
            $linkDatosEmail = 'Autorizar('.$sendAuto.')';
            $linkDatosModificar = 'Modificar('.$clienteid.', '.$perid.')';
            $print .=         '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class=row>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                                    <div class="row">
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 centrar">
                                        <button type="button" class="btn btn-outline-danger btn-lg btn-block" onClick="Activar('.$clienteid.')">Mensaje</button>
                                      </div>
                                      <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 comentario" id="comentario'.$clienteid.'">
                                        <div class="row">
                                          <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                            <form>
                                              <div class="form-group">
                                                <label>Ingresar comentario</label>
                                                <textarea class="form-control" id="observacion'.$clienteid.'" rows="2"></textarea>
                                              </div>
                                            </form>
                                          </div>
                                          <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 centrar">
                                            <form>
                                              <div class="form-group">
                                                <button type="button" class="btn btn-outline-warning btn-lg btn-block" onClick="'.$linkEnviarComentario.'">Enviar Informe</button>
                                              </div>
                                              <div class="form-group">
                                                <button type="button" class="btn btn-outline-info btn-lg btn-block" onClick="Cancelar('.$clienteid.')">Cancelar</button>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 centrar">';
          $print .=                 "<button type='button' class='btn btn-outline-primary btn-lg btn-block' onClick='$linkDatosModificar'>Modificar</button>";
          $print .=               '</div>
                                  <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 centrar">';
          $print .=                 "<button type='button' class='btn btn-outline-success btn-lg btn-block' onClick='$linkDatosEmail'>Autorizado</button>";
          $print .=               '</div>
                                </div>
                              </div>';
          }
          $print .=         '</div>
                          </div>';
          }
          $print .=       '</div>
                      </div>';
        }
        $print .=   '</div>
                  </div>
                </div>';
      }else{
        $rs .= " WHERE vendedorid = $perid";
        $rsB = mysqli_query($mysqliCon, $rs);
        $linkFunctionPersonal = "newCustomer(".$perid.")";
        $print = '<div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                    <h4 class="display-4">NUEVOS <span class="tomato">CLIENTES</span></h4>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center paddingB">
                    <button type="button" class="btn btn-outline-success" onClick="'.$linkFunctionPersonal.'">Nuevo Cliente</button>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row">
                      <form>';
        while($row = mysqli_fetch_row($rsB)){
          $clienteid          = $row[0];
          $nombre             = utf8_encode($row[1]);
          $comercial          = utf8_encode($row[2]);
          $rfc                = $row[3];
          $fecalta            = $row[4];
          $direccion          = utf8_encode($row[5]);
          $cp                 = $row[6];
          $colonia            = utf8_encode($row[7]);
          $ciudad             = utf8_encode($row[8]);
          $tel                = $row[9];
          $cel                = $row[10];
          $email              = utf8_encode($row[11]);
          $credito            = $row[12];
          $diascred           = $row[13];
          $metpag             = $row[14];
          $hacerped           = utf8_encode($row[15]);
          $recibirped         = utf8_encode($row[16]);
          $mlocal             = $row[17];
          $tlocal             = $row[18];
          $ladode             = utf8_encode($row[19]);
          $frentede           = utf8_encode($row[20]);
          $vendedorid         = $row[21];
          $activo             = $row[22];

          $fichero_Solicitud  = $row[23];
          
          $observacion        = $row[36];
          $status             = $row[37];

          $buscarVendedor = "SELECT nombre FROM per WHERE perid = $vendedorid";
          $vendedorEncontrado = $getConnection->query($buscarVendedor);
          $rowVen = mysqli_fetch_array($vendedorEncontrado);
          $nombreVendedor = $rowVen["nombre"];

          $print .=     '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingB paddingT">
                          <div class="row">';
          if($activo === 'N'){
          $print .=         '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 infoCard">
                              <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                  <div class="row">
                                    <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                      <label>Nombre:</label>
                                    </div>
                                    <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                      <input type="text" name="nombre'.$clienteid.'" value="'.$nombre.'">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                  <div class="row">
                                    <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                      <label>Negocio:</label>
                                    </div>
                                    <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                      <input type="text" name="comercial'.$clienteid.'" value="'.$comercial.'">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                  <div class="row">
                                    <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                      <label>R.F.C.:</label>
                                    </div>
                                    <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                      <input type="text" name="rfc'.$clienteid.'" value="'.$rfc.'">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                  <div class="row">
                                    <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                      <label>Fecha de Alta:</label>
                                    </div>
                                    <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                      <input type="text" name="fecalta'.$clienteid.'" value="'.$fecalta.'" readonly>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Dirección:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="text" name="direccion'.$clienteid.'" value="'.$direccion.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>C.P.:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="number" name="cp'.$clienteid.'" value="'.$cp.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Colonia:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="text" name="colonia'.$clienteid.'" value="'.$colonia.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Ciudad:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="text" name="ciudad'.$clienteid.'" value="'.$ciudad.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Local:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="tel" name="tel'.$clienteid.'" value="'.$tel.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Celular:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="tel" name="cel'.$clienteid.'" value="'.$cel.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Email:</label>
                                        </div>
                                        <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                                          <input type="email" name="email'.$clienteid.'" value="'.$email.'">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                          <label>Estatus</label>
                                        </div>';
            if($status == 1){
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight autorizado">
                                          <span>Autorizado</span>
                                        </div>';
            }else if($status == 2){
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight proceso">
                                          <span>Devuelto por Crédito y Cobranza</span>
                                        </div>';
            }else if($status == 3){
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight proceso">
                                          <span>Devuelto por Vendedor</span>
                                        </div>';
            }else{
            $print .=                   '<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 input-falsoComersRight">
                                          <span>En lista</span>
                                        </div>';
            }
          $print .=                   '</div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 paddingT">
                                  <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                              <label>Límite de Credito:</label>
                                            </div>
                                            <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                              <input type="number" name="credito'.$clienteid.'" value="'.$credito.'" readonly>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                              <label>Días de Credito:</label>
                                            </div>
                                            <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                              <select class="form-control" name="selectDiasCredito'.$clienteid.'">
                                                <option value="'.$diascred.'">'.$diascred.'</option>
                                                <option value="7">7</option>
                                                <option value="13">13</option>
                                                <option value="21">21</option>
                                                <option value="28">28</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                              <label>Método de Pago:</label>
                                            </div>
                                            <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                              <select class="form-control" name="inputMetPago'.$clienteid.'">
                                                <option value="'.$metpag.'">'.$metpag.'</option>
                                                <option value="Efectivo">7</option>
                                                <option value="Cheque">14</option>
                                                <option value="T. Débito">21</option>
                                                <option value="T. Crédito">28</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                              <label>Medidas del Local:</label>
                                            </div>
                                            <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                              <input type="number" name="mlocal'.$clienteid.'" value="'.$mlocal.'">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-7 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                              <label>El local es:</label>
                                            </div>
                                            <div class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                                              <select class="form-control" name="selectPropiedad'.$clienteid.'" value="'.$tlocal.'">
                                                <option value="'.$tlocal.'">'.$tlocal.'</option>
                                                <option value="Propio">Propio</option>
                                                <option value="Rentado">Rentado</option>
                                              </select>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                              <label>Vendedor:</label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                              <input type="text" name="nombreVendedor'.$clienteid.'" value="'.$nombreVendedor.'" readonly>
                                              <input type="text" name="idVendedor'.$clienteid.'" value="'.$vendedorid.'" readonly style="display:none;">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 centrar">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                              <label>Levanta Pedido:</label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                              <input type="text" name="hacerPed'.$clienteid.'" value="'.$hacerped.'" style="width: 100%; text-align: left!important;">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                          <div class="row">
                                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                              <label>Recibe Pedido:</label>
                                            </div>
                                            <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                              <input type="text" name="recibirPed'.$clienteid.'" value="'.$recibirped.'" style="width: 100%; text-align: left!important;">
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                      <label>A lado de:</label>
                                    </div>
                                    <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                      <input type="text" name="ladoDe'.$clienteid.'" value="'.$ladode.'" style="width: 100%; text-align: left!important;">
                                    </div>
                                    <div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                      <label>Frente de:</label>
                                    </div>
                                    <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-10">
                                      <input type="text" name="frenteDe'.$clienteid.'" value="'.$frentede.'" style="width: 100%; text-align: left!important;">
                                    </div>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 centrar paddingT paddingB">
                                  <div class="row">
                                    <ul class="navImg list-inline">';
            $buscarFotos = 'docs/';
            if($fichero_Solicitud <> $buscarFotos){
            $print .=                 '<li class="list-inline-item"><a class="nav-linkImg" href="../php/agregar/'.$fichero_Solicitud.'" target="_blanck">Solicitud</a></li>';
            }
          $print .=                 '</ul>
                                  </div>
                                </div>';
            if($observacion <> NULL){
            $print .=           '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <p>Observaciónes anteriores</p>
                                  <div class="form-group">
                                    <textarea class="form-control" rows="2" readonly>'.utf8_encode($observacion).'</textarea>
                                  </div>
                                </div>';
            }
            if($status <> 1){
            $print .=           '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                  <div class=row>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 centrar">';
              if($status == 2 || $rol == 'cartera'){
              $print .=               '<button type="button" class="btn btn-outline-danger btn-lg btn-block" onClick="Activar('.$clienteid.')">Mensaje</button>';
              }
            $linkEnviarComentario = "EnviarObservacion($clienteid, $perid, '$rol')";
            $print .=               '</div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10 comentario" id="comentario'.$clienteid.'">
                                      <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
                                          <form>
                                            <div class="form-group">
                                              <label>Ingresar comentario</label>
                                              <textarea class="form-control" id="observacion'.$clienteid.'" rows="2"></textarea>
                                            </div>
                                          </form>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-2 col-xl-2 centrar">
                                          <form>
                                            <div class="form-group">
                                              <button type="button" class="btn btn-outline-warning btn-lg btn-block" onClick="'.$linkEnviarComentario.'">Enviar Informe</button>
                                            </div>
                                            <div class="form-group">
                                              <button type="button" class="btn btn-outline-info btn-lg btn-block" onClick="Cancelar('.$clienteid.')">Cancelar</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>';
            }
          }
        $print .=             '</div>
                            </div>';
      $print .=           '</div>
                        </div>';
        }
      }
    }else{
      $linkFunctionPersonal = "newCustomer(".$perid.")";
      $print = '<div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                  <h4 class="display-4">NUEVOS <span class="text-tomato">CLIENTES</span></h4>
                  <p class="lead">No se encontrarón nuevos clientes</p>
                </div>';
      if($rol <> "cartera"){
        $print .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center paddingB">
                  <button type="button" class="btn btn-outline-success" onClick="'.$linkFunctionPersonal.'">Nuevo Cliente</button>
                </div>';
      }
      $print .= '</div>';
    }
    echo $print;
    $getConnection->close();
    $mysqliCon->close();
  }

  private function getModInv($params){
    $paramDb = new Database();
    $paramFunctions = new Util();
    $mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

    $codigo = $paramDb->SecureInput($params["codigo"]);
    $linkFunctionCancelar = "showInformation('resumenInvTub')";

    $buscarProdMod = "SELECT *
                        FROM controlinv
                        WHERE codigo = $codigo";
    $prodEncoMod = mysqli_query($mysqliCon,$buscarProdMod);
    $rowProMod = mysqli_fetch_row($prodEncoMod);

    $ide = $rowProMod[2];
    $tx = $rowProMod[3];
    $qro = $rowProMod[4];
    $tipo = $rowProMod[5];
    if($tipo == 1){
      $type = "Detalle para Tubos";
    }else if($tipo == 2){
      $type = "Detalle para Polvos";
    }

    $print =  '<div class="container paddingT paddingB">
                <div id="procesando" class="alert alert-success text-center" role="alert" style="max-width: 600px;width: 100%;margin: 0 auto;position: fixed;left: 0;right: 0;top: 100px;padding: 20px;border-radius: 20px;box-shadow: 0 0 10px rgba(0,0,0,.4);z-index:999999;display: flex;align-items: center;justify-content: center;flex-direction: column;margin-top: 200px;">
                  <h2 class="alert-heading">Cargando toda la información necesaria, espere un momento por favor. Gracias.</h2>
                  <img src="../img/barrafmo2.gif" width="200"/>
                </div>
                <h1 class="display-4 text-center paddingB">Modificar <span class="text-tomato">Código</span></h1>
                <form>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="number" value="'.$codigo.'" readonly>
                  </div>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="text" id="ide" name="identificacion" value="'.$ide.'">
                  </div>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="number" id="tx" name="tequis" value="'.$tx.'">
                  </div>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="number" id="qro" name="qro" value="'.$qro.'">
                  </div>
                  <div class="form-group">
                    <select class="form-control" name="tipo" id="tipo">
                      <option value="'.$tipo.'">'.$type.'</option>
                      <option value="1">Detalle para Tubos</option>
                      <option value="2">Detalle para Polvos</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-primary btn-lg btn-block" onClick="ModProdInv('.$codigo.')">MODIFICAR</button>
                    <button type="button" class="btn btn-success btn-lg btn-block" onClick="'.$linkFunctionCancelar.'">Cancelar</button>
                  </div>
                </form>
              </div>';

    echo $print;
    $mysqliCon->close();
  }

  private function getAddInv($params){
    $mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $linkFunctionCancelar = "showInformation('resumenInvTub')";
    $print =  '<div class="container paddingT paddingB">
                <h1 class="display-4 text-center paddingB">Agregar <span class="text-tomato">Código</span></h1>
                <form>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="number" id="codigo" name="codigo" placeholder="Ingrese el Código" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="text" id="ide" name="identificacion" placeholder="Ingrese una identificación" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="number" id="tx" name="tequis" placeholder="Cantidad a Tequis" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <input class="form-control" style="text-align: center!important;" type="number" id="qro" name="qro" placeholder="Cantidad a Qro" autocomplete="off" required>
                  </div>
                  <div class="form-group">
                    <select class="form-control" name="tipo" required>
                      <option value="">Seleccione tipo de Producto</option>
                      <option value="1">Detalle para Tubos</option>
                      <option value="2">Detalle para Polvos</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-primary btn-lg btn-block" onClick="AddProdInv()">Guardar</button>
                    <button type="button" class="btn btn-success btn-lg btn-block" onClick="'.$linkFunctionCancelar.'">Cancelar</button>
                  </div>
                </form>
              </div>';
    echo $print;
    $mysqliCon->close();
  }

  private function getNewCustomer($params){
    $paramDb = new Database();
    $paramFunctions = new Util();

    $mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

    $perid = $paramDb->SecureInput($params["perid"]);

    if(isset($params["clienteid"])){
      $clienteid = $paramDb->SecureInput($params["clienteid"]);
    }else{
      $clienteid = 0;
    }

    if($perid == 0 || $perid == NULL){
      $perid = 0;
    }

    $print = '<div class="container">';
    if($clienteid == 0){
    $print .= '<h4 class="display-4 text-center">Alta de Nuevos Clientes</h4>
              <form enctype="multipart/form-data" action="../php/agregar/nuevocli.php" method="POST">';
    }else{
    $print .= '<h4 class="display-4 text-center">Modificación de Clientes</h4>
              <form enctype="multipart/form-data" action="../php/agregar/modcli.php" method="POST">';
    }
    if($perid == 0){
      $print .= '<div class="form-group">
                  <label>Asesor</label>
                  <input type="text" class="form-control" name="inputVendedor" id="inputVendedor" placeholder="Ingrese el número del asesor" required>
                </div>';
    }else{
      $print .= '<input type="text" class="form-control" style="display:none;" name="inputVendedor" value="'.$perid.'">';
    }
    if($clienteid == 0){
      $print .=   '<div class="form-group">
                    <select class="form-control" name="selectTipoCompra" id="tipoCompra" onChange="tipoCompraCustomer(event);">
                      <option>Contado o Financiado</option>
                      <option value="1">Contado</option>
                      <option value="2">Financiado</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Razon Social</label>
                    <input type="text" class="form-control" name="inputRazonSocial" id="inputRazonSocial" placeholder="Como aparece en su RFC o en su caso nombre completo" required>
                  </div>
                  <div class="form-group">
                    <label>Nombre Comercial</label>
                    <input type="text" class="form-control" name="inputNombreComercial" id="inputNombreComercial" placeholder="Nombre del nogocio" required>
                  </div>
                  <div class="form-group">
                    <label>El cliente factura?</label>
                    <select class="form-control" id="selectFactura" onChange="factura(event)" required>
                      <option>Selecciona...</option>
                      <option value="1">Si</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                  <div class="row" id="Factura">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>RFC del Cliente</label>
                        <input type="text" class="form-control" name="inputFRCSi" id="inputFRCSi" placeholder="R.F.C." required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>Fecha de Alta en Hacienda</label>
                        <input type="date" class="form-control" name="inputAltaHaciendaSi" id="inputAltaHaciendaSi" required>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="NoFactura">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>RFC del Cliente</label>
                        <input type="text" class="form-control" name="inputFRCNo" id="inputFRCNo" value="XAXX 010101 000" readonly required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>Fecha de Alta en Hacienda</label>
                        <input type="date" class="form-control" name="inputAltaHaciendaNo" id="inputAltaHaciendaNo" value="'.date("Y-01-01").'" readonly required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" class="form-control" name="inputDireccion" id="inputDireccion" placeholder="Ingresar dirección correcta" required>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>C.P.</label>
                        <input type="number" min="1" class="form-control" name="inputCP" id="inputCP" placeholder="Ej. 79000" required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Colonia</label>
                        <input type="text" class="form-control" name="inputColonia" id="inputColonia" placeholder="Colonia" required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Ciudad</label>
                        <input type="text" class="form-control" name="inputCiudad" id="inputCiudad" placeholder="Ciudad" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Teléfono Fijo</label>
                        <input type="tel" min="1" class="form-control" name="inputTelFijo" id="inputTelFijo" placeholder="(LADA) Número de 10 dígitos" required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Celular</label>
                        <input type="tel" min="1" class="form-control" name="inputCel" id="inputCel" placeholder="(LADA) Número de 10 dígitos" required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <select class="form-control" id="selectEmail" onChange="emailEvento(event)" required>
                          <option>Email?</option>
                          <option value="1">Si</option>
                          <option value="0">No</option>
                        </select>
                        <input type="email" class="form-control" name="inputEmailSi" id="inputEmailSi" placeholder="name@example.com">
                        <input type="email" class="form-control" name="inputEmailNo" id="inputEmailNo" value="cnmfmo@gmail.com" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Cantidad de Crédito</label>
                        <input type="number" class="form-control" name="inputCantidadCredito" id="inputCantidadCredito" value="9000" readonly required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Días de Crédito</label>
                        <select class="form-control" name="selectDiasCredito" id="selectDiasCredito" required>
                          <option value="1">Selecciona...</option>
                          <option value="0">Cliente de Contado</option>
                          <option value="7">7</option>
                          <option value="14">14</option>
                          <option value="21">21</option>
                          <option value="28">28</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Método de Pago</label>
                        <select class="form-control" name="inputMetPago" id="inputMetPago" required>
                          <option>Selecciona...</option>
                          <option value="Efectivo">Efectivo</option>
                          <option value="Cheque">Cheque</option>
                          <option value="T. Débito">T. Débito</option>
                          <option value="T. Crédito">T. Crédito</option>
                          <option value="Transferencia">Transferencia</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Nombre del responsable de hacer el pedido</label>
                    <input type="text" class="form-control" name="inputResponsableHacerPedidos" id="inputResponsableHacerPedidos" placeholder="Responsable de hacer el Pedido" required>
                  </div>
                  <div class="form-group">
                    <label>Nombre del responsable de recibir el pedido</label>
                    <input type="text" class="form-control" name="inputResponsableRecibirPedidos" id="inputResponsableRecibirPedidos" placeholder="Responsable de recibir el Pedido" required>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>M2 del Local</label>
                        <input type="number" min="1" class="form-control" name="inputM2" id="inputM2" placeholder="Metros Cuadrados" required>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>Propiedad</label>
                        <select class="form-control" name="selectPropiedad" id="selectPropiedad" required>
                          <option>Selecciona...</option>
                          <option value="Propio">Propio</option>
                          <option value="Rentado">Rentado</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>A un lado de</label>
                    <textarea class="form-control" name="textareaALado" id="textareaALado" rows="3" required></textarea>
                  </div>
                  <div class="form-group">
                    <label>Frente de</label>
                    <textarea class="form-control" name="textareaFrente" id="textareaFrente" rows="3" required></textarea>
                  </div>
                  <div class="row text-center">
                    <h4>Favor de subir imagenes preliminares para poder empezar con la verificación con el cliente y poder autorizar su línea de crédito</h4>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <div class="form-group">
                        <label>Solicitud</label>
                        <input type="file" class="btn btn-warning form-control-file" name="imgSolicitud" id="imgSolicitud" required>
                      </div>
                    </div>
                  </div>
                  <script>
                    document.getElementById("Factura").style.display = "none";
                    document.getElementById("NoFactura").style.display = "none";
                    $("#inputEmailSi").hide();
                    $("#inputEmailNo").hide();
                  </script>
                  <div class="form-group">';
      $print .=     "<button type='submit' class='btn btn-success btn-lg btn-block' id='autoriza' onClick='enviarEmail($perid)'>Subir</button>";
      $print .=   '</div>
                </form>
              </div>';
    }else{
      $id = (int)$clienteid;
      $buscarCliente = "SELECT * FROM nuevoscli WHERE clienteid = $id";
      $clienteEncontrado = mysqli_query($mysqliCon, $buscarCliente);
      $row = mysqli_fetch_array($clienteEncontrado);
      // var_dump($id);
      $nombre     = utf8_encode($row["NOMBRE"]);
      $comercial  = utf8_encode($row["COMERCIAL"]);
      $rfc        = utf8_encode($row["RFC"]);
      $fecalta    = $row["FECALTA"];
      $direccion  = utf8_encode($row["DIRECCION"]);
      $cp         = $row["CP"];
      $colonia    = utf8_encode($row["COLONIA"]);
      $ciudad     = utf8_encode($row["CIUDAD"]);
      $tel        = $row["TEL"];
      $cel        = $row["CEL"];
      $email      = utf8_encode($row["EMAIL"]);
      $credito    = $row["CREDITO"];
      $diascred   = $row["DIASCRED"];
      $metpag     = utf8_encode($row["METPAG"]);
      $hacerped   = utf8_encode($row["HACERPED"]);
      $recibirped = utf8_encode($row["RECIBIRPED"]);
      $mlocal     = $row["MLOCAL"];
      $tlocal     = utf8_encode($row["TLOCAL"]);
      $ladode     = utf8_encode($row["LADODE"]);
      $frentede   = utf8_encode($row["FRENTEDE"]);
      $print .=   '<div class="form-group">
                    <label>Razon Social</label>
                    <input type="text" class="form-control" name="inputRazonSocial" id="inputRazonSocial" value="'.$nombre.'">
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>Nombre Comercial</label>
                        <input type="text" class="form-control" name="inputNombreComercial" id="inputNombreComercial" value="'.$comercial.'">
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>RFC del Cliente</label>
                        <input type="text" class="form-control" name="inputFRCSi" id="inputFRCSi" value="'.$rfc.'">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" class="form-control" name="inputDireccion" id="inputDireccion"  value="'.$direccion.'">
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>C.P.</label>
                        <input type="number" min="1" class="form-control" name="inputCP" id="inputCP"  value="'.$cp.'">
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Colonia</label>
                        <input type="text" class="form-control" name="inputColonia" id="inputColonia"  value="'.$colonia.'">
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Ciudad</label>
                        <input type="text" class="form-control" name="inputCiudad" id="inputCiudad"  value="'.$ciudad.'">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Teléfono Fijo</label>
                        <input type="tel" min="1" class="form-control" name="inputTelFijo" id="inputTelFijo"  value="'.$tel.'">
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Celular</label>
                        <input type="tel" min="1" class="form-control" name="inputCel" id="inputCel"  value="'.$cel.'">
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="inputEmailSi" id="inputEmailSi" value="'.$email.'">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Cantida de Crédito</label>
                        <input type="number" class="form-control" name="inputCantidadCredito" id="inputCantidadCredito" value="'.$credito.'" readonly>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Días de Crédito</label>
                        <select class="form-control" name="selectDiasCredito">
                          <option value="'.$diascred.'">'.$diascred.'</option>
                          <option value="0">Cliente de Contado</option>
                          <option value="7">7</option>
                          <option value="13">13</option>
                          <option value="21">21</option>
                          <option value="28">28</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 col-xl-4">
                      <div class="form-group">
                        <label>Método de Pago</label>
                        <select class="form-control" name="inputMetPago" id="inputMetPago">
                          <option value="'.$metpag.'">'.$metpag.'</option>
                          <option value="Efectivo">Efectivo</option>
                          <option value="Cheque">Cheque</option>
                          <option value="T. Débito">T. Débito</option>
                          <option value="T. Crédito">T. Crédito</option>
                          <option value="Transferencia">Transferencia</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Nombre del responsable de hacer el pedido</label>
                    <input type="text" class="form-control" name="inputResponsableHacerPedidos" id="inputResponsableHacerPedidos" value="'.$hacerped.'">
                  </div>
                  <div class="form-group">
                    <label>Nombre del responsable de recibir el pedido</label>
                    <input type="text" class="form-control" name="inputResponsableRecibirPedidos" id="inputResponsableRecibirPedidos" value="'.$recibirped.'">
                  </div>
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>M2 del Local</label>
                        <input type="number" min="1" class="form-control" name="inputM2" id="inputM2" value="'.$mlocal.'">
                      </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label>Propiedad</label>
                        <select class="form-control" name="selectPropiedad" id="selectPropiedad">
                          <option value="'.$tlocal.'">'.$tlocal.'</option>
                          <option value="Propio">Propio</option>
                          <option value="Rentado">Rentado</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>A un lado de</label>
                    <textarea class="form-control" name="textareaALado" id="textareaALado" rows="3" required>'.$ladode.'</textarea>
                  </div>
                  <div class="form-group">
                    <label>Frente de</label>
                    <textarea class="form-control" name="textareaFrente" id="textareaFrente" rows="3" required>'.$frentede.'</textarea>
                  </div>
                  <div class="form-group">';
      $print .=     "<button type='submit' class='btn btn-primary btn-lg btn-block' id='modificar'>Modificar</button>";
      $print .=   '</div>
                </form>
              </div>';
    }
    echo $print;
    $mysqliCon->close();
  }
}

?>