<?php
require_once("../class.database.php");
require_once("../functions/util.php");
date_default_timezone_set('America/Mexico_City');

class Order {
  public function getterConfirmStatusOrder($params) {
    $this->confirmStatusOrder($params);
  }
  public function getterGetEdocta($params) {
    $this->getEdocta($params);
  }
  public function getterGetFacturas($params) {
    $this->getFacturas($params);
  }
  public function getterGetOrders($params) {
    $this->getOrders($params);
  }
  public function getterGetOrders2($params) {
    $this->getOrders2($params);
  }
  public function getterGetPublicOrders($params) {
    $this->getPublicOrders($params);
  }
  public function getterGetNotcred($params) {
    $this->getNotcred($params);
  }
  public function getterPublicOrderCode() {
    $this->publicOrderCode();
  }
  public function getterSaveOrder($data, $email, $nombre, $direccion, $postal, $rfc, $celular) {
    $this->saveOrder($data, $email, $nombre, $direccion, $postal, $rfc, $celular);
  }
  public function getterShowDetail($params) {
    $this->showDetail($params);
  }
  public function getterShowDetailOrder($params) {
    $this->showDetailOrder($params);
  }
  public function getterUpdateQuantity($params) {
    $this->updateQuantity($params);
  }

  public function getterUpdateStatus($params) {
    $this->updateStatus($params);
  }

  private function confirmStatusOrder($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $orderID = $paramDb->SecureInput($params["orderID"]);
    $status = $paramDb->SecureInput($params["status"]);

    $updateOrderStatus = "UPDATE pedidos SET status = '$status' ";
    $updateOrderStatus .= "WHERE id = $orderID";

    $executeQuery = $paramDb->UpdateDb($updateOrderStatus);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }
  }

  // TODO remove columns on section Carrito, on Pedidos show all columns. params.section
  private function getEdocta($params) {
    $paramDb        = new Database();
    $paramFunctions = new Util();

    $rol            = $paramDb->SecureInput($params["rol"]);
    $clienteID      = $paramDb->SecureInput($params["username"]);
    $section        = $paramDb->SecureInput($params["section"]); // ["shopping-cart-partner", "orders-record-partner"]
    // @param bManagementOrder = to identify type of user and print more "options"
    $arrayBooleans  = array("bManagementOrder" => false);

    $fecActual = date("Y-m-d");

    $getAllOrders="SELECT d.docid, cfd.tipdoc, cfd.folio, d.feccap, d.vence, d.total, d.totalpagado,
    (SELECT DATEDIFF(d.vence, '$fecActual')) as DiasPasados
                    FROM doc d
                      JOIN cfd ON cfd.docid = d.docid
                      JOIN cli c ON c.clienteid = d.clienteid
                    WHERE c.numero = '$clienteID'
                      AND d.total > d.totalpagado
                    ORDER BY cfd.folio ASC";

    $executeQuery = $paramDb->UpdateDb($getAllOrders);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }

    $totalNumberRows = mysqli_num_rows($executeQuery);;

    /*echo $rol."-";
    echo $totalNumberRows."-";
    echo $clienteID;*/

    if($totalNumberRows > 0) {
      $itemsPerPage = 13;  // items will be display per page
      $baseLimitPage = 10; // record number to will be display on pagination bar
      $totalPages = ceil($totalNumberRows / $itemsPerPage); // total of pages, for segments of $itemsPerPage; not confuse with count(*) productos

      if(isset($params["parametersRequest"]["currentPage"])) {
        $currentPage = $paramDb->SecureInput($params["parametersRequest"]["currentPage"]);
        $limitPages = $paramDb->SecureInput($params["parametersRequest"]["limitPages"]);
        // depend on what page the user is, is the total record, for example if the user
        // is on main page start = 0, if user is on second page start = $itemsPerPage + $itemsPerPage
        // this way constructing query LIMIT 15, 0 or LIMIT 30, 15; depends which pages the user is and value of $itemsPerPage, in this example = 15.
        $start = ($currentPage - 1) * $itemsPerPage;
      } else {
        // on first page doesn't exist variables, assign init value
        $start = 0;
        $currentPage = 1;
        $limitPages = $baseLimitPage;
      }

      $getordersF="SELECT d.docid, cfd.tipdoc, cfd.folio, d.feccap, d.vence, d.total, d.totalpagado,
                    (SELECT DATEDIFF(d.vence, '$fecActual')) as DiasPasados
                    FROM doc d
                      JOIN cfd ON cfd.docid = d.docid
                      JOIN cli c ON c.clienteid = d.clienteid
                    WHERE c.numero = '$clienteID'
                      AND d.total > d.totalpagado
                    ORDER BY cfd.folio ASC
                      LIMIT $start,$itemsPerPage";

      $executeQuery = $paramDb->Query($getordersF);
      try {
        $numRow = $paramDb->NumRows();
        $rows = $paramDb->Rows();
        if($numRow > 0) {

          // NOTE params to pagination
          if($currentPage < $baseLimitPage) {
            $initLimitPages = 1;
            $limitPages = $baseLimitPage;
            if($limitPages > $totalPages) {
              $limitPages = $totalPages;
            }
          }
          else if($currentPage < $limitPages) {
            $initLimitPages = $limitPages - $baseLimitPage;
          }
          else if($currentPage == $limitPages) {
            $initLimitPages = $currentPage;
            $limitPages = $currentPage + $baseLimitPage;
            // when limit pages it's major to totalPages, assign value totalPages; because
            // doesn't exist major number pages than total pages
            if($limitPages > $totalPages) {
              $initLimitPages = $totalPages - $baseLimitPage;
              // to fixed bug when baseLimitPage it's equal to total pages
              if($initLimitPages == 0) {
                $initLimitPages = 1;
              }
              $limitPages = $totalPages;
            }
          }

          if($currentPage < $initLimitPages) {
            $initLimitPages = $initLimitPages - $baseLimitPage;
            $limitPages = $limitPages - $baseLimitPage;
          }

          $paramsPagination = array("url" => "../php/order/order.php",
                          "booleanResponse" => false,
                          "location" => "edocta",
                          "msgSuccess" => "Ok!",
                          "msgError" => "Error en paginacion de pedidos");

          // NOTE encoding json, it's apply in structure.php
          // End params to pagination

          // NOTE section and divResultID, set in conditional because they change depends section and result will be show in different places(divs).
          if($section == "orders-record-partner") {
            $headers = ["#", "FOLIO", "EMISION", "VENCIMIENTO", "TOTAL", "DEUDA", "DIAS RESTANTES"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section; // param to draw table header
            $paramsPagination["divResultID"] = "page-wrapper";
          } else if($section == "shopping-cart-partner") {
            $headers = ["#", "FOLIO", "EMISION", "VENCIMIENTO", "TOTAL", "DEUDA", "DIAS RESTANTES"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section;
            $paramsPagination["divResultID"] = "content-orders-record";
          }

          $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 edocta">
                      <h3>ESTADO DE <spam class="text-tomato">CUENTA</spam></h3>
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 edoctaScroll">';
          $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
          $i=1;

          foreach($rows as $row) {
            $folio = $row["folio"];
            $tipo = $row["tipdoc"];
            $facturaDoc = $tipo."".$folio;
            $feccap = $row["feccap"];
            $vence = $row["vence"];
            $total = $row["total"];
            $totalpagado = $row["totalpagado"];
            $DiasPasados = $row["DiasPasados"];
            
            $deuda = $total - $totalpagado;
            
            $formatoTotal = number_format($total, 2);
            $formatoDeuda = number_format($deuda, 2);

            $atraso = $DiasPasados;

            $print .=       "<tr>";
            $print .=         "<td class='text-center'>$i</td>";
            $print .=         "<td class='text-center'>$facturaDoc</td>";
            $print .=         "<td class='text-center'>$feccap</td>";            
            $print .=         "<td class='text-center text-tomato'>$vence</td>";
            $print .=         "<td class='text-center'>MX$ $formatoTotal</td>";
            $print .=         "<td class='text-center'>MX$ $formatoDeuda</td>";
            $print .=         "<td class='text-center text-tomato'>$atraso</td>";
            /*$print .=         "<td class='text-center'>
                                <a href='#' onclick='showDetailOrder($cajaid)'>
                                  <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
                                </a>
                              </td>";*/
            $print .=       "</tr>";
            $i = $i +1;
          }
          $print .=       '</table>
                        </div>
                      </div>';
          $print .= "</div>";
          echo      "<div class='text-center'";
          // include pagination
          require_once('../pagination/structure.php');
          echo      "</div>";
        } else {
          $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 hg910 centrarSep">';
          $print .=   "<div class='row'>
                        <div class='col-md-12 text-center'>
                          <h4>No hay cuentas pendiente o con saldo.</h4>
                          <h4>Su estado de cuenta esta limpio.</h4>
                        </div>
                      </div>";
          $print .= "</div>";
        }
      } catch (Exception $e) {
        echo "Problema al listar pedidos: " . $e->getMessage();
      }
    } else {
      $print =  '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 hg910 centrarSep">';
      $print .=   "<div class='row'>
                    <div class='col-md-12 text-center'>
                      <h4>No hay cuentas pendiente o con saldo.</h4>
                      <h4>Su estado de cuenta esta limpio.</h4>
                    </div>
                  </div>";
      $print .= "</div>";
    } // end validation num row > 0, do something if doesn't exist order
    echo $print;
  } // end function

  // TODO remove columns on section Carrito, on Pedidos show all columns. params.section
  private function getFacturas($params) {
    $paramDb        = new Database();
    $paramFunctions = new Util();

    $rol            = $paramDb->SecureInput($params["rol"]);
    $clienteID      = $paramDb->SecureInput($params["username"]);
    $section        = $paramDb->SecureInput($params["section"]); // ["shopping-cart-partner", "orders-record-partner"]
    // @param bManagementOrder = to identify type of user and print more "options"
    $arrayBooleans  = array("bManagementOrder" => false);

    $getAllOrders="SELECT d.docid, d.feccap, d.feccan, d.vence, d.total, d.totalpagado, d.tipo
                      FROM doc d
                        JOIN cli c ON c.clienteid = d.clienteid
                      WHERE c.numero = '$clienteID'
                        AND d.tipo = 'F'
                      ORDER BY d.feccap DESC";

    //$getAllOrders = "SELECT * FROM ori WHERE";

    /*if($rol == "MAYOREO" || $rol == "SUBDISTRIBUIDOR" || $rol == "DISTRIBUIDOR") {
    } else {
      $getAllOrders .= " WHERE numcliente = $clienteID";
    }*/

    $executeQuery = $paramDb->UpdateDb($getAllOrders);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }

    $totalNumberRows = mysqli_num_rows($executeQuery);;

    /*echo $rol."-";
    echo $totalNumberRows."-";
    echo $clienteID;*/

    if($totalNumberRows > 0) {
      $itemsPerPage = 13;  // items will be display per page
      $baseLimitPage = 10; // record number to will be display on pagination bar
      $totalPages = ceil($totalNumberRows / $itemsPerPage); // total of pages, for segments of $itemsPerPage; not confuse with count(*) productos

      if(isset($params["parametersRequest"]["currentPage"])) {
        $currentPage = $paramDb->SecureInput($params["parametersRequest"]["currentPage"]);
        $limitPages = $paramDb->SecureInput($params["parametersRequest"]["limitPages"]);
        // depend on what page the user is, is the total record, for example if the user
        // is on main page start = 0, if user is on second page start = $itemsPerPage + $itemsPerPage
        // this way constructing query LIMIT 15, 0 or LIMIT 30, 15; depends which pages the user is and value of $itemsPerPage, in this example = 15.
        $start = ($currentPage - 1) * $itemsPerPage;
      } else {
        // on first page doesn't exist variables, assign init value
        $start = 0;
        $currentPage = 1;
        $limitPages = $baseLimitPage;
      }

      $getordersF="SELECT d.docid, d.feccap, d.feccan, d.vence, d.total, d.totalpagado, d.tipo
                      FROM doc d
                        JOIN cli c ON c.clienteid = d.clienteid
                      WHERE c.numero = '$clienteID'
                        AND d.tipo = 'F'
                      ORDER BY d.feccap DESC
                      LIMIT $start,$itemsPerPage";

      $executeQuery = $paramDb->Query($getordersF);
      try {
        $numRow = $paramDb->NumRows();
        $rows = $paramDb->Rows();
        if($numRow > 0) {

          // NOTE params to pagination
          if($currentPage < $baseLimitPage) {
            $initLimitPages = 1;
            $limitPages = $baseLimitPage;
            if($limitPages > $totalPages) {
              $limitPages = $totalPages;
            }
          }
          else if($currentPage < $limitPages) {
            $initLimitPages = $limitPages - $baseLimitPage;
          }
          else if($currentPage == $limitPages) {
            $initLimitPages = $currentPage;
            $limitPages = $currentPage + $baseLimitPage;
            // when limit pages it's major to totalPages, assign value totalPages; because
            // doesn't exist major number pages than total pages
            if($limitPages > $totalPages) {
              $initLimitPages = $totalPages - $baseLimitPage;
              // to fixed bug when baseLimitPage it's equal to total pages
              if($initLimitPages == 0) {
                $initLimitPages = 1;
              }
              $limitPages = $totalPages;
            }
          }

          if($currentPage < $initLimitPages) {
            $initLimitPages = $initLimitPages - $baseLimitPage;
            $limitPages = $limitPages - $baseLimitPage;
          }

          $paramsPagination = array("url" => "../php/order/order.php",
                          "booleanResponse" => false,
                          "location" => "facturas",
                          "msgSuccess" => "Ok!",
                          "msgError" => "Error en paginacion de pedidos");

          // NOTE encoding json, it's apply in structure.php
          // End params to pagination

          // NOTE section and divResultID, set in conditional because they change depends section and result will be show in different places(divs).
          if($section == "orders-record-partner") {
            $headers = ["No. Pedido", "Fecha", "Monto", "Pagado", "Saldo"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section; // param to draw table header
            $paramsPagination["divResultID"] = "page-wrapper";
          } else if($section == "shopping-cart-partner") {
           $headers = ["No. Pedido", "Fecha", "Monto", "Pagado", "Saldo"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section;
            $paramsPagination["divResultID"] = "content-orders-record";
          }

          $print = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 facturas">
                      <h3>HISTORIAL <spam class="text-tomato">DE FACTURAS</spam></h3>
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12">';
          $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
          foreach($rows as $row) {
            $pedidoID = $row["docid"];
            $fecha = $row["feccap"];
            $referencia = $row["tipo"];
            if($referencia == 'F'){
              $tipo = 'FACTURA';
            }
            $saldo = $row["total"];
            $formatoSaldo = number_format($saldo, 2);
            $pagado = $row["totalpagado"];
            $formatoPagado = number_format($pagado, 2);
            $falta = $pagado - $saldo;
            $formatoFalta = number_format($falta, 2);

            $print .=     "<tr>";
            $print .=       "<td class='text-center'>$pedidoID</td>";
            $print .=       "<td class='text-center'>$fecha</td>";            
            $print .=       "<td class='text-center'>MX$ $formatoSaldo</td>";
            $print .=       "<td class='text-center'>MX$ $formatoPagado</td>";
            $print .=       "<td class='text-center'>MX$ $formatoFalta</td>";
            // $print .=       "<td class='text-center'>
            //                   <a href='#' onclick='showDetailOrder($cajaid)'>
            //                     <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
            //                   </a>
            //                 </td>";
            $print .=     "</tr>";
          }
          $print .=      '</table>';
          $print .=     "</div>";
          $print .=   "</div>"; // div overflow-x:auto
          echo $print;
          echo        "<div class='text-center'>";
          // include pagination
          require_once('../pagination/structure.php');
          echo        "</div>";
        } else {
          echo        "<div class='row'>
                        <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
                          <h4>No tienes ninguna factura</h4>
                        </div>
                      </div>";
        }
      } catch (Exception $e) {
        echo "Problema al listar pedidos: " . $e->getMessage();
      }
    } // end validation num row > 0, do something if doesn't exist order
  } // end function

  // TODO remove columns on section Carrito, on Pedidos show all columns. params.section
  private function getOrders($params) {
    $paramDb        = new Database();
    $paramFunctions = new Util();

    $rol            = $paramDb->SecureInput($params["rol"]);
    $clienteID      = $paramDb->SecureInput($params["username"]);
    $section        = $paramDb->SecureInput($params["section"]); // ["shopping-cart-partner", "orders-record-partner"]
    // @param bManagementOrder = to identify type of user and print more "options"
    $arrayBooleans  = array("bManagementOrder" => false);

    $getAllOrders="SELECT d.docid, d.feccap, d.feccan, d.vence, d.total, d.totalpagado, d.tipo
                      FROM doc d
                        JOIN cli c ON c.clienteid = d.clienteid
                      WHERE c.numero = '$clienteID'
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'C'
                            )
                      ORDER BY d.feccap DESC";

    //$getAllOrders = "SELECT * FROM ori WHERE";

    /*if($rol == "MAYOREO" || $rol == "SUBDISTRIBUIDOR" || $rol == "DISTRIBUIDOR") {
    } else {
      $getAllOrders .= " WHERE numcliente = $clienteID";
    }*/

    $executeQuery = $paramDb->UpdateDb($getAllOrders);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }

    $totalNumberRows = mysqli_num_rows($executeQuery);

    /*echo $rol."-";
    echo $totalNumberRows."-";
    echo $clienteID;*/

    if($totalNumberRows > 0) {
      $itemsPerPage = 13;  // items will be display per page
      $baseLimitPage = 10; // record number to will be display on pagination bar
      $totalPages = ceil($totalNumberRows / $itemsPerPage); // total of pages, for segments of $itemsPerPage; not confuse with count(*) productos

      if(isset($params["parametersRequest"]["currentPage"])) {
        $currentPage = $paramDb->SecureInput($params["parametersRequest"]["currentPage"]);
        $limitPages = $paramDb->SecureInput($params["parametersRequest"]["limitPages"]);
        // depend on what page the user is, is the total record, for example if the user
        // is on main page start = 0, if user is on second page start = $itemsPerPage + $itemsPerPage
        // this way constructing query LIMIT 15, 0 or LIMIT 30, 15; depends which pages the user is and value of $itemsPerPage, in this example = 15.
        $start = ($currentPage - 1) * $itemsPerPage;
      } else {
        // on first page doesn't exist variables, assign init value
        $start = 0;
        $currentPage = 1;
        $limitPages = $baseLimitPage;
      }

      /*$getorders = "SELECT c.cajaid, c.fecha, c.mov, c.hora, c.referencia, c.saldo, c.ncaja, p.docid, p.tipodoc, p.pagado, p.formapago, p.fechaaplicada, p.aplicado, p.recibo
          FROM caj c
            JOIN pag p ON p.cajaid = c.cajaid
          WHERE c.clienteid = $clienteID
          ORDER BY fechaaplicada DESC";*/

      $getorders="SELECT d.docid, d.anterior, d.feccap, d.feccan, d.vence, d.total, d.totalpagado, d.tipo
                      FROM doc d
                        JOIN cli c ON c.clienteid = d.clienteid
                      WHERE c.numero = '$clienteID'
                        AND (
                              d.tipo = 'F'
                              OR d.tipo = 'C'
                            )
                      ORDER BY d.anterior DESC
                      LIMIT $start,$itemsPerPage";

      $executeQuery = $paramDb->Query($getorders);
      try {
        $numRow = $paramDb->NumRows();
        $rows = $paramDb->Rows();
        if($numRow > 0) {

          // NOTE params to pagination
          if($currentPage < $baseLimitPage) {
            $initLimitPages = 1;
            $limitPages = $baseLimitPage;
            if($limitPages > $totalPages) {
              $limitPages = $totalPages;
            }
          }
          else if($currentPage < $limitPages) {
            $initLimitPages = $limitPages - $baseLimitPage;
          }
          else if($currentPage == $limitPages) {
            $initLimitPages = $currentPage;
            $limitPages = $currentPage + $baseLimitPage;
            // when limit pages it's major to totalPages, assign value totalPages; because
            // doesn't exist major number pages than total pages
            if($limitPages > $totalPages) {
              $initLimitPages = $totalPages - $baseLimitPage;
              // to fixed bug when baseLimitPage it's equal to total pages
              if($initLimitPages == 0) {
                $initLimitPages = 1;
              }
              $limitPages = $totalPages;
            }
          }

          if($currentPage < $initLimitPages) {
            $initLimitPages = $initLimitPages - $baseLimitPage;
            $limitPages = $limitPages - $baseLimitPage;
          }

          $paramsPagination = array("url" => "../php/order/order.php",
                          "booleanResponse" => false,
                          "location" => "getorders",
                          "msgSuccess" => "Ok!",
                          "msgError" => "Error en paginacion de pedidos");

          // NOTE encoding json, it's apply in structure.php
          // End params to pagination

          // NOTE section and divResultID, set in conditional because they change depends section and result will be show in different places(divs).
          if($section == "orders-record-partner") {
            $headers = ["No. Pedido", "Folio", "Fecha", "Documento", "Monto", "Pagado", "Saldo"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section; // param to draw table header
            $paramsPagination["divResultID"] = "page-wrapper";
          } else if($section == "shopping-cart-partner") {
           $headers = ["No. Pedido", "Folio", "Fecha", "Documento", "Monto", "Pagado", "Saldo"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section;
            $paramsPagination["divResultID"] = "content-orders-record";
          }

          $print = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 historial">
                      <h3>HIST<spam class="text-tomato">ORIAL</spam></h3>';
          $print .= '<div class="row">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12">';
          $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
          foreach($rows as $row) {
            $pedidoID = $row["docid"];
            $folio = $row["anterior"];
            $fecha = $row["feccap"];
            $referencia = $row["tipo"];
            if($referencia == 'F'){
              $tipo = 'FACTURA';
            } elseif($referencia == 'C'){
              $tipo = 'NOTA DE CREDITO';
            }
            $saldo = $row["total"];
            $formatoSaldo = number_format($saldo, 2);
            $pagado = $row["totalpagado"];
            $formatoPagado = number_format($pagado, 2);
            $falta = $pagado - $saldo;
            $formatoFalta = number_format($falta, 2);
            
            $print .=      "<tr>";
            $print .=        "<td class='text-center'>$pedidoID</td>";
            $print .=        "<td class='text-center' scope='rowgroup'>$folio</td>";
            $print .=        "<td class='text-center'>$fecha</td>";
            $print .=        "<td class='text-center'>$tipo</td>";
            $print .=        "<td class='text-center'>MX$ $formatoSaldo</td>";
            $print .=        "<td class='text-center'>MX$ $formatoPagado</td>";
            $print .=        "<td class='text-center'>MX$ $formatoFalta</td>";
            /*$print .=      "<td class='text-center'>
                              <a href='#' onclick='showDetailOrder($cajaid)'>
                                <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
                              </a>
                            </td>";*/
            $print .=     "</tr>";
          }
          $print .=     '</table>';
          $print .=   "</div>
                    </div>";
          echo $print;
          echo      "<div class='text-center'>";
          // include pagination
          require_once('../pagination/structure.php');
          echo      "</div>
                  </div>";
        } else {
          echo      "<div class='row'>
                      <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
                        <h4>No tienes ningún pedido</h4>
                      </div>
                    </div>";
        }
      } catch (Exception $e) {
        echo "Problema al listar pedidos: " . $e->getMessage();
      }
    } // end validation num row > 0, do something if doesn't exist order
  } // end function

  // TODO remove columns on section Carrito, on Pedidos show all columns. params.section
  private function getOrders2($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $rol = $paramDb->SecureInput($params["rol"]);
    $clienteID = $paramDb->SecureInput($params["username"]);
    $section = $paramDb->SecureInput($params["section"]); // ["shopping-cart-partner", "orders-record-partner"]
    // @param bManagementOrder = to identify type of user and print more "options"
    $arrayBooleans = array("bManagementOrder" => false);

    $getAllOrders = "SELECT p.* FROM pedidos p ORDER BY id";

    $result = mysqli_query($getConnection,$getAllOrders);
    $totalNumberRows = mysqli_num_rows($result);

    if($totalNumberRows > 0) {
      $itemsPerPage = 5;  // items will be display per page
      $baseLimitPage = 10; // record number to will be display on pagination bar
      $totalPages = ceil($totalNumberRows / $itemsPerPage); // total of pages, for segments of $itemsPerPage; not confuse with count(*) productos

      if(isset($params["parametersRequest"]["currentPage"])) {
        $currentPage = $paramDb->SecureInput($params["parametersRequest"]["currentPage"]);
        $limitPages = $paramDb->SecureInput($params["parametersRequest"]["limitPages"]);
        // depend on what page the user is, is the total record, for example if the user
        // is on main page start = 0, if user is on second page start = $itemsPerPage + $itemsPerPage
        // this way constructing query LIMIT 15, 0 or LIMIT 30, 15; depends which pages the user is and value of $itemsPerPage, in this example = 15.
        $start = ($currentPage - 1) * $itemsPerPage;
      } else {
        // on first page doesn't exist variables, assign init value
        $start = 0;
        $currentPage = 1;
        $limitPages = $baseLimitPage;
      }

      /*$getorders = "SELECT p.id, p.folio, p.fechapedido, p.status, cli.nombre, co.correo, p.statusEmail, p.medioPago, pre.precio as precio,
                          (
                            SELECT CASE
                            WHEN dp.cantidadSurtida > 0 THEN dp.cantidadSurtida
                            ELSE dp.cantidadSolicitada
                            END
                          ) AS cantidadQuery,
                          SUM((SELECT (precioSubtotal * cantidadQuery) FROM DUAL)) as total
                      FROM pedidos p
                        JOIN cli ON cli.clienteid = p.clienteID
                        JOIN descripcionPedido dp ON dp.pedidoID = p.id
                        JOIN precios pre ON pre.articuloid = dp.productoID
                        JOIN correos co ON co.clienteid = cli.clienteid
                      ORDER BY id, fechaPedido DESC
                      LIMIT $start, $itemsPerPage";*/

      $getorders = "SELECT p.id, p.folio, p.fechapedido, p.status, cli.nombre, cli.lista, co.correo, p.statusEmail, p.medioPago, pre.pimpuesto as impuesto,
                          (
                            SELECT CASE
                            WHEN dp.cantidadSurtida > 0 THEN SUM((SELECT (pre.precio * dp.cantidadSurtida) FROM DUAL))
                            ELSE SUM((SELECT (pre.precio * dp.cantidadSolicitada) FROM DUAL))
                            END
                          ) AS subtotal
                      FROM pedidos p
                        JOIN cli ON cli.clienteid = p.clienteID
                        JOIN descripcionPedido dp ON dp.pedidoID = p.id
                        JOIN precios pre ON pre.articuloid = dp.productoID
                        JOIN inv i ON i.articuloid = dp.productoID
                        JOIN correos co ON co.clienteid = cli.clienteid
                      WHERE pre.nprecio = 1
                        AND pre.unidadid = i.unibasid
                      ORDER BY id, fechaPedido DESC
                      LIMIT $start, $itemsPerPage";
      $arrayBooleans["bManagementOrder"] = true;

      $executeQuery = $paramDb->Query($getorders);
      try {
        $numRow = $paramDb->NumRows();
        $rows = $paramDb->Rows();
        if($numRow > 0) {

          // NOTE params to pagination
          if($currentPage < $baseLimitPage) {
            $initLimitPages = 1;
            $limitPages = $baseLimitPage;
            if($limitPages > $totalPages) {
              $limitPages = $totalPages;
            }
          }
          else if($currentPage < $limitPages) {
            $initLimitPages = $limitPages - $baseLimitPage;
          }
          else if($currentPage == $limitPages) {
            $initLimitPages = $currentPage;
            $limitPages = $currentPage + $baseLimitPage;
            // when limit pages it's major to totalPages, assign value totalPages; because
            // doesn't exist major number pages than total pages
            if($limitPages > $totalPages) {
              $initLimitPages = $totalPages - $baseLimitPage;
              // to fixed bug when baseLimitPage it's equal to total pages
              if($initLimitPages == 0) {
                $initLimitPages = 1;
              }
              $limitPages = $totalPages;
            }
          }

          if($currentPage < $initLimitPages) {
            $initLimitPages = $initLimitPages - $baseLimitPage;
            $limitPages = $limitPages - $baseLimitPage;
          }

          $paramsPagination = array("url" => "../php/order/order.php",
                          "booleanResponse" => false,
                          "location" => "getorders",
                          "msgSuccess" => "Ok!",
                          "msgError" => "Error en paginacion de pedidos");

          // NOTE encoding json, it's apply in structure.php
          // End params to pagination

          // NOTE section and divResultID, set in conditional because they change depends section and result will be show in different places(divs).
          if($section == "orders-record-partner") {
            /*$headers = ["#", "Folio", "Fecha Pedido", "Status", "SubTotal", "I.V.A", "Total", "Cliente", "Email", "Detalle"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];*/
            $headers = ["#", "Folio", "Fecha Pedido", "Status", "Cliente", "Email", "Detalle"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section; // param to draw table header
            $paramsPagination["divResultID"] = "page-wrapper";
          } else if($section == "shopping-cart-partner") {
            $headers = ["#", "Folio", "Fecha pedido", "Status", "Total", "Detalle"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section;
            $paramsPagination["divResultID"] = "content-orders-record";
          }

          $print = '<div class="panel panel-default" style="margin: 40px 15px 0 250px !important;">
                      <div class="panel-heading">
                        <h4>Pedidos</h4>
                      </div>
                      <div class="panel-body">';
          $print .= '<div>';
          $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
          foreach($rows as $row) {
            $pedidoID = $row["id"];
            $folio = $row["folio"];
            $fechaPedido = $row["fechapedido"];
            $status = $row["status"];
            $numPrecio = $row["lista"];
            /*$subtotalPed = $row["subtotal"];
            $formatoSubTotal = number_format($subtotalPed, 2);
            $impuesto = $row["impuesto"] / 100;
            $iva = $subtotalPed * $impuesto;
            $formatoImpuesto = number_format($iva, 2);
            $total = $subtotalPed + $iva;
            $formatoTotal = number_format($total, 2);*/
            $cliente = $row["nombre"];
            // validate if order has email or get from client
            $email = $paramFunctions->identifyEmailOrder($row);


            $print .= "<tr>";
              $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$pedidoID</td>";
              $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$folio</td>";
              $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$fechaPedido</td>";

              // NOTE print column to insert quantity of product that exist on stock if user has access,
              // else print label with quantity
              if($arrayBooleans["bManagementOrder"]) {
                $params = array("orderID"=>$pedidoID,
                                "jQueryID"=> "txtOrderStatus$pedidoID",
                                "url"=>"../php/order/order.php",
                                "booleanResponse"=>true,
                                "location"=>"updateorderstatus",
                                "msgSuccess"=>"Se ha cambiado el status",
                                "msgError"=>"No se ha podido cambiar el status");
                $paramsSend = json_encode($params);

                $print .= "<td class='text-center'>";
                  $print .= "<select name='txtOrderStatus$pedidoID' id='txtOrderStatus$pedidoID' class='form-control'
                             onchange='generalFunctionToRequest($paramsSend)'>";
                      // print status, and validate to select default item field if it's equal from database
                      $contentStatus = ["proceso", "comprobando", "cancelado", "confirmado", "terminado"];
                      $print .= $paramFunctions->selectDefaultOption($status, $contentStatus);
                  $print .=  "</select>";
                $print .=  "</td>";
              } else {
                $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold; color:blue'>$status</td>";
              }

              /*$print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$ $formatoSubTotal</td>";
              $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$ $formatoImpuesto</td>";
              $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$ $formatoTotal</td>";*/

              // print only when user it's on section Pedidos
              if($section == "orders-record-partner") {
                $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$cliente</td>";
                $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$email</td>";
              }

              $print .= "<td class='text-center'>
                          <a href='#' onclick='showDetailOrder($pedidoID, $numPrecio)'>
                            <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
                          </a>
                        </td>";
            $print .= "</tr>";
          }
          $print .= '</table>';
          $print .= "</div>";
          $print .= "</div>";
          $print .= "</div>"; // div overflow-x:auto
          echo $print;

          // include pagination
          require_once('../pagination/structure.php');

        } else {
          echo "<div class='row'>
                  <div class='col-md-12'>
                    <h4>No tienes ningún pedido</h4>
                  </div>
                </div>";
        }
      } catch (Exception $e) {
        echo "Problema al listar pedidos: " . $e->getMessage();
      }

    } // end validation num row > 0, do something if doesn't exist order

  } // end function

  // TODO remove columns on section Carrito, on Pedidos show all columns. params.section
  private function getNotcred($params) {
    $paramDb        = new Database();
    $paramFunctions = new Util();

    $rol            = $paramDb->SecureInput($params["rol"]);
    $clienteID      = $paramDb->SecureInput($params["username"]);
    $section        = $paramDb->SecureInput($params["section"]); // ["shopping-cart-partner", "orders-record-partner"]
    // @param bManagementOrder = to identify type of user and print more "options"
    $arrayBooleans  = array("bManagementOrder" => false);

    $getAllOrders="SELECT d.docid, d.feccap, d.feccan, d.vence, d.total, d.totalpagado, d.tipo
                      FROM doc d
                        JOIN cli c ON c.clienteid = d.clienteid
                      WHERE c.numero = '$clienteID'
                        AND d.tipo = 'C'
                      ORDER BY d.feccap DESC";

    //$getAllOrders = "SELECT * FROM ori WHERE";

    /*if($rol == "MAYOREO" || $rol == "SUBDISTRIBUIDOR" || $rol == "DISTRIBUIDOR") {
    } else {
      $getAllOrders .= " WHERE numcliente = $clienteID";
    }*/

    $executeQuery = $paramDb->UpdateDb($getAllOrders);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }

    $totalNumberRows = mysqli_num_rows($executeQuery);;

    /*echo $rol."-";
    echo $totalNumberRows."-";
    echo $clienteID;*/

    if($totalNumberRows > 0) {
      $itemsPerPage = 13;  // items will be display per page
      $baseLimitPage = 10; // record number to will be display on pagination bar
      $totalPages = ceil($totalNumberRows / $itemsPerPage); // total of pages, for segments of $itemsPerPage; not confuse with count(*) productos

      if(isset($params["parametersRequest"]["currentPage"])) {
        $currentPage = $paramDb->SecureInput($params["parametersRequest"]["currentPage"]);
        $limitPages = $paramDb->SecureInput($params["parametersRequest"]["limitPages"]);
        // depend on what page the user is, is the total record, for example if the user
        // is on main page start = 0, if user is on second page start = $itemsPerPage + $itemsPerPage
        // this way constructing query LIMIT 15, 0 or LIMIT 30, 15; depends which pages the user is and value of $itemsPerPage, in this example = 15.
        $start = ($currentPage - 1) * $itemsPerPage;
      } else {
        // on first page doesn't exist variables, assign init value
        $start = 0;
        $currentPage = 1;
        $limitPages = $baseLimitPage;
      }

      $getordersF="SELECT d.docid, d.feccap, d.feccan, d.vence, d.total, d.totalpagado, d.tipo
                      FROM doc d
                        JOIN cli c ON c.clienteid = d.clienteid
                      WHERE c.numero = '$clienteID'
                        AND d.tipo = 'C'
                      ORDER BY d.feccap DESC
                      LIMIT $start,$itemsPerPage";

      $executeQuery = $paramDb->Query($getordersF);
      try {
        $numRow = $paramDb->NumRows();
        $rows = $paramDb->Rows();
        if($numRow > 0) {

          // NOTE params to pagination
          if($currentPage < $baseLimitPage) {
            $initLimitPages = 1;
            $limitPages = $baseLimitPage;
            if($limitPages > $totalPages) {
              $limitPages = $totalPages;
            }
          }
          else if($currentPage < $limitPages) {
            $initLimitPages = $limitPages - $baseLimitPage;
          }
          else if($currentPage == $limitPages) {
            $initLimitPages = $currentPage;
            $limitPages = $currentPage + $baseLimitPage;
            // when limit pages it's major to totalPages, assign value totalPages; because
            // doesn't exist major number pages than total pages
            if($limitPages > $totalPages) {
              $initLimitPages = $totalPages - $baseLimitPage;
              // to fixed bug when baseLimitPage it's equal to total pages
              if($initLimitPages == 0) {
                $initLimitPages = 1;
              }
              $limitPages = $totalPages;
            }
          }

          if($currentPage < $initLimitPages) {
            $initLimitPages = $initLimitPages - $baseLimitPage;
            $limitPages = $limitPages - $baseLimitPage;
          }

          $paramsPagination = array("url" => "../php/order/order.php",
                          "booleanResponse" => false,
                          "location" => "notcred",
                          "msgSuccess" => "Ok!",
                          "msgError" => "Error en paginacion de pedidos");

          // NOTE encoding json, it's apply in structure.php
          // End params to pagination

          // NOTE section and divResultID, set in conditional because they change depends section and result will be show in different places(divs).
          if($section == "orders-record-partner") {
            $headers = ["No. Pedido", "Fecha", "Monto", "Pagado", "Saldo"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section; // param to draw table header
            $paramsPagination["divResultID"] = "page-wrapper";
          } else if($section == "shopping-cart-partner") {
           $headers = ["No. Pedido", "Fecha", "Monto", "Pagado", "Saldo"];
            $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center"];
            $paramsPagination["section"] = $section;
            $paramsPagination["divResultID"] = "content-orders-record";
          }

          $print = '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 credito">
                      <h3>HISTORIAL DE NOTAS <spam class="text-tomato">DE CRÉDITO<spam></h3>
                      <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12">';
          $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
          foreach($rows as $row) {
            $pedidoID = $row["docid"];
            $fecha = $row["feccap"];
            $referencia = $row["tipo"];
            if($referencia == 'C'){
              $tipo = 'NOTAS DE CREDITO';
            }
            $saldo = $row["total"];
            $formatoSaldo = number_format($saldo, 2);
            $pagado = $row["totalpagado"];
            $formatoPagado = number_format($pagado, 2);
            $falta = $pagado - $saldo;
            $formatoFalta = number_format($falta, 2);

            $print .=       "<tr>";
            $print .=         "<td class='text-center'>$pedidoID</td>";
            $print .=         "<td class='text-center'>$fecha</td>";
            $print .=         "<td class='text-center'>MX$ $formatoSaldo</td>";
            $print .=         "<td class='text-center'>MX$ $formatoPagado</td>";
            $print .=         "<td class='text-center'>MX$ $formatoFalta</td>";
            /*$print .= "<td class='text-center'>
                        <a href='#' onclick='showDetailOrder($cajaid)'>
                          <span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
                        </a>
                      </td>";*/
            $print .=       "</tr>";
          }
          $print .=       '</table>';
          $print .=     "</div>";
          $print .=   "</div>"; // div overflow-x:auto
          echo $print;
          echo        "<div class='text-center'>";
          // include pagination
          require_once('../pagination/structure.php');
          echo        "</div>";
        } else {
          echo "<div class='row'>
                  <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
                    <h4>No tienes ningún pedido</h4>
                  </div>
                </div>";
        }
      } catch (Exception $e) {
        echo "Problema al listar pedidos: " . $e->getMessage();
      }
    } // end validation num row > 0, do something if doesn't exist order
  } // end function

  private function getPublicOrders($params) {
    $paramFunctions = new Util();
    if(isset($params["orderCode"]) && $params["orderCode"] != "") {
      $paramDb = new Database();
      $getConnection = $paramDb->GetLink();

      $rol = $paramDb->SecureInput($params["rol"]);
      $clienteID = $paramDb->SecureInput($params["clienteID"]);
      $folio = $paramDb->SecureInput($params["orderCode"]);

      // TODO
      // 1.- make it query simple and get price precios.publico, because client always will be "publico"
      // 2.- CHECK WHAT TYPE OF QUANTITY EXIST, IF THERE IS cantidadSurtida multiply by price; if not, cantidadSolicitada multiply by price
      $getpublicorders = "SELECT p.id as pedidoID, c.correo as emailClient, c.nombreCompleto, p.email as emailOrder,
                        p.*,
                        (
                          SELECT sbq1Ro.rol
                              FROM pedidos sbq1Pe
                                JOIN clientes sbq1Cl ON sbq1Cl.id = sbq1Pe.clienteID
                                JOIN roles sbq1Ro ON sbq1Ro.id = sbq1Cl.roleID
                                  WHERE sbq1Pe.id = p.id
                        ) as rolQuery,
                        (
                          SELECT CASE
                              WHEN rolQuery = 'mayorista' THEN pre.mayorista
                              WHEN rolQuery = 'distribuidor' THEN pre.distribuidor
                              WHEN rolQuery = 'subdistribuidor' THEN pre.subdistribuidor
                              ELSE pre.mayorista
                            END
                        ) AS precio,
                        (
                          SELECT CASE
                            WHEN dp.cantidadSurtida > 0 THEN dp.cantidadSurtida
                          ELSE dp.cantidadSolicitada
                          END
                        ) AS cantidadQuery,
                          SUM((SELECT (precio * cantidadQuery) FROM DUAL)) as total
                          FROM pedidos p
                            JOIN descripcionPedido dp ON dp.pedidoID = p.id
                            JOIN productos pro ON pro.id = dp.productoID
                            JOIN precios pre ON pre.id = pro.id
                            JOIN clientes c ON c.id = p.id
                              WHERE p.clienteID = $clienteID
                              AND p.folio = '$folio'
                              AND p.status IN ('proceso', 'comprobando')
                              GROUP BY p.id
                              ORDER BY p.id, p.fechaPedido DESC";


      $executeQuery = $paramDb->Query($getpublicorders);

      try {
        $numRow = $paramDb->NumRows();
        $rows = $paramDb->Rows();
        $getConnection->close();

        if($numRow > 0) {
          /*$headers = ["#", "Folio", "Fecha pedido", "Status", "Total", "Cliente Email", "Detalle"];
          $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];*/

          $headers = ["#", "Folio", "Fecha pedido", "Status", "Cliente", "Email", "Detalle"];
          $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

            echo "<div class='panel panel-default' style='margin:40px 0 40px 0;'>";
            echo "<div class='panel-heading'>";
            echo "<h4>MI PEDIDO</h4>";
            echo "</div>";
            echo "<div class='panel-body' style='font-size:1.2em;'>";
            $print = "<div>";
            $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
            foreach ($rows as $row) {
              $pedidoID = $row["pedidoID"];
              $folio = $row["folio"];
              $fechaPedido = $row["fechaPedido"];
              $status = $row["status"];
              $cliente = $row["nombreCompleto"];
              $total = $row["total"];
              $totalFormat = number_format($total, 2);

              $clienteEmail = $row["email"];
              $params = array("pedidoID"=>$pedidoID,
                              "location"=>"showdetailorder",
                              "url"=>"php/order/order.php",
                              "booleanResponse"=>false,
                              "divResultID"=>"resultPublicOrderCode",
                              "msgSuccess"=>"Ok!",
                              "msgError"=>"Error al mostrar detalle de pedido");
              $paramsSend = json_encode($params);

              /*$print .=
                      "<tr class='active'>
                        <td class='text-center'>$pedidoID</td>
                        <td class='text-center'>$folio</td>
                        <td class='text-center'>$fechaPedido</td>
                        <td class='text-center'>$status</td>
                        <td class='text-center'>$$totalFormat</td>
                        <td class='text-center'>$clienteEmail</td>
                        <td class='text-center'>
                          <a href='#' onclick='generalFunctionToRequest($paramsSend)'>
                            <span class='glyphicon glyphicon-option-horizontal glyphicon-bigger-20'></span>
                          </a>
                        </td>
                      </tr>";*/
              $print .=
                      "<tr class='active'>
                        <td class='text-center'>$pedidoID</td>
                        <td class='text-center'>$folio</td>
                        <td class='text-center'>$fechaPedido</td>
                        <td class='text-center'>$status</td>
                        <td class='text-center'>$cliente</td>
                        <td class='text-center'>$clienteEmail</td>
                        <td class='text-center'>
                          <a href='#' onclick='generalFunctionToRequest($paramsSend)'>
                            <span class='glyphicon glyphicon-option-horizontal glyphicon-bigger-20'></span>
                          </a>
                        </td>
                      </tr>";

            }
          $print .= '</div>'; // overflow-x:auto
          echo $print;

        } else {
          $message = "No se encontró nigún pedido con el Folio: " . $folio;
          $paramFunctions->showDivMessage($message);
        }
      } catch (Exception $e) {
        echo "Problema al listar pedidos: " . $e->getMessage();
      }
    } else {
      $message = "Favor de escribir Folio";
      $paramFunctions->showDivMessage($message);
    }
  }

  private function publicOrderCode() {
    $params = array("jQueryID"=>"txtPublicOrderCode",
                    "url"=> "php/order/order.php",
                    "location" => "getpublicorders",
                    "booleanResponse" => false,
                    "divResultID" => "resultPublicOrderCode");
    $paramsSend = json_encode($params);
    // NOTE for test width set background-color for each div
    $print =
      "<div class='row' style='margin:20px 0 500px 0'>
          <div class='col-md-2'>
            <input type='text' name='txtPublicOrderCode' id='txtPublicOrderCode'
               class='form-control' placeholder='Folio de pedido'/>
          </div>
          <div class='col-md-10'>
            <button class='btn btn-secondary'
               onclick='generalFunctionToRequest($paramsSend);'>
              Buscar
            </button>
          </div>
        </div>
      </div> <!-- row -->";

    $print .=   "<div class='col-md-12' style='margin:-500px 0 20px 0;' id='resultPublicOrderCode'>";
    $print .=   "</div>";
    $print .=   "<div id='resultModalPaymentMethod'></div>";
    echo $print;
  }

  private function saveOrder($data, $email, $nombre, $direccion, $postal, $rfc, $celular) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    // first save pedido, then get id return of query to save descripcionPedido
    // pedidos: clienteID=1, fechaPedido=date(), status=proceso  

    $getLastOrderID = "SELECT MAX(id) as lastID FROM pedidos";
    $executeQueryLastOrderID = $paramDb->Query($getLastOrderID);
    $rows = $paramDb->Rows();
    $lastOrderID = $rows[0]["lastID"];
    $lastOrderID = (int)$lastOrderID + 1;
    $currentDate = date("Y-m-d H:i:s");

    $folio = $paramFunctions->generateRandomString(5, $lastOrderID);
    $nuevoPedido = "INSERT INTO pedidos (fechaPedido, status, email, folio, clienteID) VALUES ";
    $nuevoPedido .= " ('$currentDate', 'proceso', '$email', '$folio', '$lastOrderID')";
    $executeQuery = $paramDb->UpdateDb($nuevoPedido);
    // TODO send Email folio, module done; but problems on server

    if($executeQuery) {
      // $connection->insert_id; // get the last insert on whatever table, it's doesn't work for this
      // array multidimensional that has data to save on db, and true or false if it's string
      $params = [];
      // NOTE It's very IMPORTANT to set arrays on the same consecutive order as put on this content array.
      $elementsID = ["tipo", "piece", "cantidad", "productoID", "pedidoID"];
      $insertDescripcionPedido = "INSERT INTO descripcionPedido (tipoPieza, piezas, cantidadSolicitada, productoID, pedidoID) VALUES ";
      $values = "";
      for($i = 0; $i < count($data); $i++) {
        $productID =  $paramDb->SecureInput($data[$i]["productID"]);
        $title =      $paramDb->SecureInput($data[$i]["title"]);
        $quantity =   $paramDb->SecureInput($data[$i]["quantity"]);
        $price =      $paramDb->SecureInput($data[$i]["price"]);
        $price =      str_replace(',', '', $price);
        $type =       $paramDb->SecureInput($data[$i]["type"]);
        $piece =      $paramDb->SecureInput($data[$i]["piece"]);
        // descripcionPedido: productID, cantidad
        $currentParam = array(  array("tipo" => $type, "string" => 'true'),
                                array("piece" => $piece, "string" => 'false'),
                                array("cantidad" => $quantity, "string" => 'false'),
                                array("productoID" => $productID, "string" => 'false'),
                                array("pedidoID" => $lastOrderID, "string" => 'false')
                        );
        array_push($params, $currentParam);
      }
      $values = $paramFunctions->constructSql($params, $elementsID);
      $insertDescripcionPedido .= $values;
      $executeQueryDP = $paramDb->UpdateDb($insertDescripcionPedido);

      $agregarCliente = "INSERT INTO clientes (nombreCompleto, clave, direccion, cp, rfc, telefono, correo, pedidoID) VALUES ('".$nombre."', '0000000', '".$direccion."', ".$postal.", '".$rfc."', '".$celular."', '".$email."', '".$lastOrderID."')";
      $executeQuery = $paramDb->UpdateDb($agregarCliente);

      $getConnection->close();

      if($executeQueryDP) {
        echo "true";
      } else {
        echo "false";
      }
    } else {
      echo "false";
    }

    $datosPDF = "SELECT dp.pedidoID, p.folio, p.fechaPedido, p.status, c.telefono, c.correo
          FROM pedidos p
          JOIN descripcionPedido dp ON dp.pedidoID = p.id
          JOIN clientes c ON c.id = dp.pedidoID
          WHERE dp.pedidoID = $pedidoID";
    $sacarDatosParaPDF = $paramDb->Query($datosPDF);
    $rows = $paramDb->Rows();
    $idOrder = $rows[0]["pedidoID"];
    $folio = $rows[1]["folio"];
    $fecha = $rows[2]["fechaPedido"];
    $status = $rows[3]["statusPedido"];
    $tel = $row[4]["telefono"];
    $email = $row[5]["correo"];

    require('../mail/PHPMailer/class.phpmailer.php');
    require('../mail/PHPMailer/class.smtp.php');

    $mensaje = '
    <!DOCTYPE html>
    <html lang="es">

      <head>
        <mtas charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <style>
          .wrapper {
      width: 100%; }

      #outlook a {
        padding: 0; }

      body {
        width: 100% !important;
        min-width: 100%;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        margin: 0;
        Margin: 0;
        padding: 0;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
          background: #48a19d !important;
      }

      .ExternalClass {
        width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }

      #backgroundTable {
        margin: 0;
        Margin: 0;
        padding: 0;
        width: 100% !important;
        line-height: 100% !important; }

      img {
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
        width: auto;
        max-width: 100%;
        clear: both;
        display: block; }

      center {
        width: 100%;
        min-width: 580px; }

      a img {
        border: none; }

      p {
        margin: 0 0 0 10px;
        Margin: 0 0 0 10px; }

      table {
        border-spacing: 0;
        border-collapse: collapse; }

      td {
        word-wrap: break-word;
        -webkit-hyphens: auto;
        -moz-hyphens: auto;
        hyphens: auto;
        border-collapse: collapse !important; }

      table, tr, td {
        padding: 0;
        vertical-align: top;
        text-align: left; }

      @media only screen {
        html {
          min-height: 100%;
          background: #f3f3f3; } }

      table.body {
        background: #f3f3f3;
        height: 100%;
        width: 100%; }

      table.container {
        background: #fefefe;
        width: 580px;
        margin: 0 auto;
        Margin: 0 auto;
        text-align: inherit; }

      table.row {
        padding: 0;
        width: 100%;
        position: relative; }

      table.spacer {
        width: 100%; }
        table.spacer td {
          mso-line-height-rule: exactly; }

      table.container table.row {
        display: table; }

      td.columns,
      td.column,
      th.columns,
      th.column {
        margin: 0 auto;
        Margin: 0 auto;
        padding-left: 16px;
        padding-bottom: 16px; }
        td.columns .column,
        td.columns .columns,
        td.column .column,
        td.column .columns,
        th.columns .column,
        th.columns .columns,
        th.column .column,
        th.column .columns {
          padding-left: 0 !important;
          padding-right: 0 !important; }
          td.columns .column center,
          td.columns .columns center,
          td.column .column center,
          td.column .columns center,
          th.columns .column center,
          th.columns .columns center,
          th.column .column center,
          th.column .columns center {
            min-width: none !important; }

      td.columns.last,
      td.column.last,
      th.columns.last,
      th.column.last {
        padding-right: 16px; }

      td.columns table:not(.button),
      td.column table:not(.button),
      th.columns table:not(.button),
      th.column table:not(.button) {
        width: 100%; }

      td.large-1,
      th.large-1 {
        width: 32.33333px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-1.first,
      th.large-1.first {
        padding-left: 16px; }

      td.large-1.last,
      th.large-1.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-1,
      .collapse > tbody > tr > th.large-1 {
        padding-right: 0;
        padding-left: 0;
        width: 48.33333px; }

      .collapse td.large-1.first,
      .collapse th.large-1.first,
      .collapse td.large-1.last,
      .collapse th.large-1.last {
        width: 56.33333px; }

      td.large-1 center,
      th.large-1 center {
        min-width: 0.33333px; }

      .body .columns td.large-1,
      .body .column td.large-1,
      .body .columns th.large-1,
      .body .column th.large-1 {
        width: 8.33333%; }

      td.large-2,
      th.large-2 {
        width: 80.66667px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-2.first,
      th.large-2.first {
        padding-left: 16px; }

      td.large-2.last,
      th.large-2.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-2,
      .collapse > tbody > tr > th.large-2 {
        padding-right: 0;
        padding-left: 0;
        width: 96.66667px; }

      .collapse td.large-2.first,
      .collapse th.large-2.first,
      .collapse td.large-2.last,
      .collapse th.large-2.last {
        width: 104.66667px; }

      td.large-2 center,
      th.large-2 center {
        min-width: 48.66667px; }

      .body .columns td.large-2,
      .body .column td.large-2,
      .body .columns th.large-2,
      .body .column th.large-2 {
        width: 16.66667%; }

      td.large-3,
      th.large-3 {
        width: 129px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-3.first,
      th.large-3.first {
        padding-left: 16px; }

      td.large-3.last,
      th.large-3.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-3,
      .collapse > tbody > tr > th.large-3 {
        padding-right: 0;
        padding-left: 0;
        width: 145px; }

      .collapse td.large-3.first,
      .collapse th.large-3.first,
      .collapse td.large-3.last,
      .collapse th.large-3.last {
        width: 153px; }

      td.large-3 center,
      th.large-3 center {
        min-width: 97px; }

      .body .columns td.large-3,
      .body .column td.large-3,
      .body .columns th.large-3,
      .body .column th.large-3 {
        width: 25%; }

      td.large-4,
      th.large-4 {
        width: 177.33333px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-4.first,
      th.large-4.first {
        padding-left: 16px; }

      td.large-4.last,
      th.large-4.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-4,
      .collapse > tbody > tr > th.large-4 {
        padding-right: 0;
        padding-left: 0;
        width: 193.33333px; }

      .collapse td.large-4.first,
      .collapse th.large-4.first,
      .collapse td.large-4.last,
      .collapse th.large-4.last {
        width: 201.33333px; }

      td.large-4 center,
      th.large-4 center {
        min-width: 145.33333px; }

      .body .columns td.large-4,
      .body .column td.large-4,
      .body .columns th.large-4,
      .body .column th.large-4 {
        width: 33.33333%; }

      td.large-5,
      th.large-5 {
        width: 225.66667px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-5.first,
      th.large-5.first {
        padding-left: 16px; }

      td.large-5.last,
      th.large-5.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-5,
      .collapse > tbody > tr > th.large-5 {
        padding-right: 0;
        padding-left: 0;
        width: 241.66667px; }

      .collapse td.large-5.first,
      .collapse th.large-5.first,
      .collapse td.large-5.last,
      .collapse th.large-5.last {
        width: 249.66667px; }

      td.large-5 center,
      th.large-5 center {
        min-width: 193.66667px; }

      .body .columns td.large-5,
      .body .column td.large-5,
      .body .columns th.large-5,
      .body .column th.large-5 {
        width: 41.66667%; }

      td.large-6,
      th.large-6 {
        width: 274px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-6.first,
      th.large-6.first {
        padding-left: 16px; }

      td.large-6.last,
      th.large-6.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-6,
      .collapse > tbody > tr > th.large-6 {
        padding-right: 0;
        padding-left: 0;
        width: 290px; }

      .collapse td.large-6.first,
      .collapse th.large-6.first,
      .collapse td.large-6.last,
      .collapse th.large-6.last {
        width: 298px; }

      td.large-6 center,
      th.large-6 center {
        min-width: 242px; }

      .body .columns td.large-6,
      .body .column td.large-6,
      .body .columns th.large-6,
      .body .column th.large-6 {
        width: 50%; }

      td.large-7,
      th.large-7 {
        width: 322.33333px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-7.first,
      th.large-7.first {
        padding-left: 16px; }

      td.large-7.last,
      th.large-7.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-7,
      .collapse > tbody > tr > th.large-7 {
        padding-right: 0;
        padding-left: 0;
        width: 338.33333px; }

      .collapse td.large-7.first,
      .collapse th.large-7.first,
      .collapse td.large-7.last,
      .collapse th.large-7.last {
        width: 346.33333px; }

      td.large-7 center,
      th.large-7 center {
        min-width: 290.33333px; }

      .body .columns td.large-7,
      .body .column td.large-7,
      .body .columns th.large-7,
      .body .column th.large-7 {
        width: 58.33333%; }

      td.large-8,
      th.large-8 {
        width: 370.66667px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-8.first,
      th.large-8.first {
        padding-left: 16px; }

      td.large-8.last,
      th.large-8.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-8,
      .collapse > tbody > tr > th.large-8 {
        padding-right: 0;
        padding-left: 0;
        width: 386.66667px; }

      .collapse td.large-8.first,
      .collapse th.large-8.first,
      .collapse td.large-8.last,
      .collapse th.large-8.last {
        width: 394.66667px; }

      td.large-8 center,
      th.large-8 center {
        min-width: 338.66667px; }

      .body .columns td.large-8,
      .body .column td.large-8,
      .body .columns th.large-8,
      .body .column th.large-8 {
        width: 66.66667%; }

      td.large-9,
      th.large-9 {
        width: 419px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-9.first,
      th.large-9.first {
        padding-left: 16px; }

      td.large-9.last,
      th.large-9.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-9,
      .collapse > tbody > tr > th.large-9 {
        padding-right: 0;
        padding-left: 0;
        width: 435px; }

      .collapse td.large-9.first,
      .collapse th.large-9.first,
      .collapse td.large-9.last,
      .collapse th.large-9.last {
        width: 443px; }

      td.large-9 center,
      th.large-9 center {
        min-width: 387px; }

      .body .columns td.large-9,
      .body .column td.large-9,
      .body .columns th.large-9,
      .body .column th.large-9 {
        width: 75%; }

      td.large-10,
      th.large-10 {
        width: 467.33333px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-10.first,
      th.large-10.first {
        padding-left: 16px; }

      td.large-10.last,
      th.large-10.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-10,
      .collapse > tbody > tr > th.large-10 {
        padding-right: 0;
        padding-left: 0;
        width: 483.33333px; }

      .collapse td.large-10.first,
      .collapse th.large-10.first,
      .collapse td.large-10.last,
      .collapse th.large-10.last {
        width: 491.33333px; }

      td.large-10 center,
      th.large-10 center {
        min-width: 435.33333px; }

      .body .columns td.large-10,
      .body .column td.large-10,
      .body .columns th.large-10,
      .body .column th.large-10 {
        width: 83.33333%; }

      td.large-11,
      th.large-11 {
        width: 515.66667px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-11.first,
      th.large-11.first {
        padding-left: 16px; }

      td.large-11.last,
      th.large-11.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-11,
      .collapse > tbody > tr > th.large-11 {
        padding-right: 0;
        padding-left: 0;
        width: 531.66667px; }

      .collapse td.large-11.first,
      .collapse th.large-11.first,
      .collapse td.large-11.last,
      .collapse th.large-11.last {
        width: 539.66667px; }

      td.large-11 center,
      th.large-11 center {
        min-width: 483.66667px; }

      .body .columns td.large-11,
      .body .column td.large-11,
      .body .columns th.large-11,
      .body .column th.large-11 {
        width: 91.66667%; }

      td.large-12,
      th.large-12 {
        width: 564px;
        padding-left: 8px;
        padding-right: 8px; }

      td.large-12.first,
      th.large-12.first {
        padding-left: 16px; }

      td.large-12.last,
      th.large-12.last {
        padding-right: 16px; }

      .collapse > tbody > tr > td.large-12,
      .collapse > tbody > tr > th.large-12 {
        padding-right: 0;
        padding-left: 0;
        width: 580px; }

      .collapse td.large-12.first,
      .collapse th.large-12.first,
      .collapse td.large-12.last,
      .collapse th.large-12.last {
        width: 588px; }

      td.large-12 center,
      th.large-12 center {
        min-width: 532px; }

      .body .columns td.large-12,
      .body .column td.large-12,
      .body .columns th.large-12,
      .body .column th.large-12 {
        width: 100%; }

      td.large-offset-1,
      td.large-offset-1.first,
      td.large-offset-1.last,
      th.large-offset-1,
      th.large-offset-1.first,
      th.large-offset-1.last {
        padding-left: 64.33333px; }

      td.large-offset-2,
      td.large-offset-2.first,
      td.large-offset-2.last,
      th.large-offset-2,
      th.large-offset-2.first,
      th.large-offset-2.last {
        padding-left: 112.66667px; }

      td.large-offset-3,
      td.large-offset-3.first,
      td.large-offset-3.last,
      th.large-offset-3,
      th.large-offset-3.first,
      th.large-offset-3.last {
        padding-left: 161px; }

      td.large-offset-4,
      td.large-offset-4.first,
      td.large-offset-4.last,
      th.large-offset-4,
      th.large-offset-4.first,
      th.large-offset-4.last {
        padding-left: 209.33333px; }

      td.large-offset-5,
      td.large-offset-5.first,
      td.large-offset-5.last,
      th.large-offset-5,
      th.large-offset-5.first,
      th.large-offset-5.last {
        padding-left: 257.66667px; }

      td.large-offset-6,
      td.large-offset-6.first,
      td.large-offset-6.last,
      th.large-offset-6,
      th.large-offset-6.first,
      th.large-offset-6.last {
        padding-left: 306px; }

      td.large-offset-7,
      td.large-offset-7.first,
      td.large-offset-7.last,
      th.large-offset-7,
      th.large-offset-7.first,
      th.large-offset-7.last {
        padding-left: 354.33333px; }

      td.large-offset-8,
      td.large-offset-8.first,
      td.large-offset-8.last,
      th.large-offset-8,
      th.large-offset-8.first,
      th.large-offset-8.last {
        padding-left: 402.66667px; }

      td.large-offset-9,
      td.large-offset-9.first,
      td.large-offset-9.last,
      th.large-offset-9,
      th.large-offset-9.first,
      th.large-offset-9.last {
        padding-left: 451px; }

      td.large-offset-10,
      td.large-offset-10.first,
      td.large-offset-10.last,
      th.large-offset-10,
      th.large-offset-10.first,
      th.large-offset-10.last {
        padding-left: 499.33333px; }

      td.large-offset-11,
      td.large-offset-11.first,
      td.large-offset-11.last,
      th.large-offset-11,
      th.large-offset-11.first,
      th.large-offset-11.last {
        padding-left: 547.66667px; }

      td.expander,
      th.expander {
        visibility: hidden;
        width: 0;
        padding: 0 !important; }

      table.container.radius {
        border-radius: 0;
        border-collapse: separate; }

      .block-grid {
        width: 100%;
        max-width: 580px; }
        .block-grid td {
          display: inline-block;
          padding: 8px; }

      .up-2 td {
        width: 274px !important; }

      .up-3 td {
        width: 177px !important; }

      .up-4 td {
        width: 129px !important; }

      .up-5 td {
        width: 100px !important; }

      .up-6 td {
        width: 80px !important; }

      .up-7 td {
        width: 66px !important; }

      .up-8 td {
        width: 56px !important; }

      table.text-center,
      th.text-center,
      td.text-center,
      h1.text-center,
      h2.text-center,
      h3.text-center,
      h4.text-center,
      h5.text-center,
      h6.text-center,
      p.text-center,
      span.text-center {
        text-align: center; }

      table.text-left,
      th.text-left,
      td.text-left,
      h1.text-left,
      h2.text-left,
      h3.text-left,
      h4.text-left,
      h5.text-left,
      h6.text-left,
      p.text-left,
      span.text-left {
        text-align: left; }

      table.text-right,
      th.text-right,
      td.text-right,
      h1.text-right,
      h2.text-right,
      h3.text-right,
      h4.text-right,
      h5.text-right,
      h6.text-right,
      p.text-right,
      span.text-right {
        text-align: right; }

      span.text-center {
        display: block;
        width: 100%;
        text-align: center; }

      @media only screen and (max-width: 596px) {
        .small-float-center {
          margin: 0 auto !important;
          float: none !important;
          text-align: center !important; }
        .small-text-center {
          text-align: center !important; }
        .small-text-left {
          text-align: left !important; }
        .small-text-right {
          text-align: right !important; } }

      img.float-left {
        float: left;
        text-align: left; }

      img.float-right {
        float: right;
        text-align: right; }

      img.float-center,
      img.text-center {
        margin: 0 auto;
        Margin: 0 auto;
        float: none;
        text-align: center; }

      table.float-center,
      td.float-center,
      th.float-center {
        margin: 0 auto;
        Margin: 0 auto;
        float: none;
        text-align: center; }

      .hide-for-large {
        display: none !important;
        mso-hide: all;
        overflow: hidden;
        max-height: 0;
        font-size: 0;
        width: 0;
        line-height: 0; }
        @media only screen and (max-width: 596px) {
          .hide-for-large {
            display: block !important;
            width: auto !important;
            overflow: visible !important;
            max-height: none !important;
            font-size: inherit !important;
            line-height: inherit !important; } }

      table.body table.container .hide-for-large * {
        mso-hide: all; }

      @media only screen and (max-width: 596px) {
        table.body table.container .hide-for-large,
        table.body table.container .row.hide-for-large {
          display: table !important;
          width: 100% !important; } }

      @media only screen and (max-width: 596px) {
        table.body table.container .callout-inner.hide-for-large {
          display: table-cell !important;
          width: 100% !important; } }

      @media only screen and (max-width: 596px) {
        table.body table.container .show-for-large {
          display: none !important;
          width: 0;
          mso-hide: all;
          overflow: hidden; } }

      body,
      table.body,
      h1,
      h2,
      h3,
      h4,
      h5,
      h6,
      p,
      td,
      th,
      a {
        color: #0a0a0a;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: normal;
        padding: 0;
        margin: 0;
        Margin: 0;
        text-align: left;
        line-height: 1.3; }

      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
        color: inherit;
        word-wrap: normal;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: normal;
        margin-bottom: 10px;
        Margin-bottom: 10px; }

      h1 {
        font-size: 34px; }

      h2 {
        font-size: 30px; }

      h3 {
        font-size: 28px; }

      h4 {
        font-size: 24px; }

      h5 {
        font-size: 20px; }

      h6 {
        font-size: 18px; }

      body,
      table.body,
      p,
      td,
      th {
        font-size: 16px;
        line-height: 1.3; }

      p {
        margin-bottom: 10px;
        Margin-bottom: 10px; }
        p.lead {
          font-size: 20px;
          line-height: 1.6; }
        p.subheader {
          margin-top: 4px;
          margin-bottom: 8px;
          Margin-top: 4px;
          Margin-bottom: 8px;
          font-weight: normal;
          line-height: 1.4;
          color: #8a8a8a; }

      small {
        font-size: 80%;
        color: #cacaca; }

      a {
        color: #2199e8;
        text-decoration: none; }
        a:hover {
          color: #147dc2; }
        a:active {
          color: #147dc2; }
        a:visited {
          color: #2199e8; }

      h1 a,
      h1 a:visited,
      h2 a,
      h2 a:visited,
      h3 a,
      h3 a:visited,
      h4 a,
      h4 a:visited,
      h5 a,
      h5 a:visited,
      h6 a,
      h6 a:visited {
        color: #2199e8; }

      pre {
        background: #f3f3f3;
        margin: 30px 0;
        Margin: 30px 0; }
        pre code {
          color: #cacaca; }
          pre code span.callout {
            color: #8a8a8a;
            font-weight: bold; }
          pre code span.callout-strong {
            color: #ff6908;
            font-weight: bold; }

      table.hr {
        width: 100%; }
        table.hr th {
          height: 0;
          max-width: 580px;
          border-top: 0;
          border-right: 0;
          border-bottom: 1px solid #0a0a0a;
          border-left: 0;
          margin: 20px auto;
          Margin: 20px auto;
          clear: both; }

      .stat {
        font-size: 40px;
        line-height: 1; }
        p + .stat {
          margin-top: -16px;
          Margin-top: -16px; }

      span.preheader {
        display: none !important;
        visibility: hidden;
        mso-hide: all !important;
        font-size: 1px;
        color: #f3f3f3;
        line-height: 1px;
        max-height: 0px;
        max-width: 0px;
        opacity: 0;
        overflow: hidden; }

      table.button {
        width: auto;
        margin: 0 0 16px 0;
        Margin: 0 0 16px 0; }
        table.button table td {
          text-align: left;
          color: #fefefe;
          background: #2199e8;
          border: 2px solid #2199e8; }
          table.button table td a {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            color: #fefefe;
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px 8px 16px;
            border: 0 solid #2199e8;
            border-radius: 3px; }
        table.button.radius table td {
          border-radius: 3px;
          border: none; }
        table.button.rounded table td {
          border-radius: 500px;
          border: none; }

      table.button:hover table tr td a,
      table.button:active table tr td a,
      table.button table tr td a:visited,
      table.button.tiny:hover table tr td a,
      table.button.tiny:active table tr td a,
      table.button.tiny table tr td a:visited,
      table.button.small:hover table tr td a,
      table.button.small:active table tr td a,
      table.button.small table tr td a:visited,
      table.button.large:hover table tr td a,
      table.button.large:active table tr td a,
      table.button.large table tr td a:visited {
        color: #fefefe; }

      table.button.tiny table td,
      table.button.tiny table a {
        padding: 4px 8px 4px 8px; }

      table.button.tiny table a {
        font-size: 10px;
        font-weight: normal; }

      table.button.small table td,
      table.button.small table a {
        padding: 5px 10px 5px 10px;
        font-size: 12px; }

      table.button.large table a {
        padding: 10px 20px 10px 20px;
        font-size: 20px; }

      table.button.expand,
      table.button.expanded {
        width: 100% !important; }
        table.button.expand table,
        table.button.expanded table {
          width: 100%; }
          table.button.expand table a,
          table.button.expanded table a {
            text-align: center;
            width: 100%;
            padding-left: 0;
            padding-right: 0; }
        table.button.expand center,
        table.button.expanded center {
          min-width: 0; }

      table.button:hover table td,
      table.button:visited table td,
      table.button:active table td {
        background: #147dc2;
        color: #fefefe; }

      table.button:hover table a,
      table.button:visited table a,
      table.button:active table a {
        border: 0 solid #147dc2; }

      table.button.secondary table td {
        background: #777777;
        color: #fefefe;
        border: 0px solid #777777; }

      table.button.secondary table a {
        color: #fefefe;
        border: 0 solid #777777; }

      table.button.secondary:hover table td {
        background: #919191;
        color: #fefefe; }

      table.button.secondary:hover table a {
        border: 0 solid #919191; }

      table.button.secondary:hover table td a {
        color: #fefefe; }

      table.button.secondary:active table td a {
        color: #fefefe; }

      table.button.secondary table td a:visited {
        color: #fefefe; }

      table.button.success table td {
        background: #3adb76;
        border: 0px solid #3adb76; }

      table.button.success table a {
        border: 0 solid #3adb76; }

      table.button.success:hover table td {
        background: #23bf5d; }

      table.button.success:hover table a {
        border: 0 solid #23bf5d; }

      table.button.alert table td {
        background: #ec5840;
        border: 0px solid #ec5840; }

      table.button.alert table a {
        border: 0 solid #ec5840; }

      table.button.alert:hover table td {
        background: #e23317; }

      table.button.alert:hover table a {
        border: 0 solid #e23317; }

      table.button.warning table td {
        background: #ffae00;
        border: 0px solid #ffae00; }

      table.button.warning table a {
        border: 0px solid #ffae00; }

      table.button.warning:hover table td {
        background: #cc8b00; }

      table.button.warning:hover table a {
        border: 0px solid #cc8b00; }

      table.callout {
        margin-bottom: 16px;
        Margin-bottom: 16px; }

      th.callout-inner {
        width: 100%;
        border: 1px solid #cbcbcb;
        padding: 10px;
        background: #fefefe; }
        th.callout-inner.primary {
          background: #def0fc;
          border: 1px solid #444444;
          color: #0a0a0a; }
        th.callout-inner.secondary {
          background: #ebebeb;
          border: 1px solid #444444;
          color: #0a0a0a; }
        th.callout-inner.success {
          background: #e1faea;
          border: 1px solid #1b9448;
          color: #fefefe; }
        th.callout-inner.warning {
          background: #fff3d9;
          border: 1px solid #996800;
          color: #fefefe; }
        th.callout-inner.alert {
          background: #fce6e2;
          border: 1px solid #b42912;
          color: #fefefe; }

      .thumbnail {
        border: solid 4px #fefefe;
        box-shadow: 0 0 0 1px rgba(10, 10, 10, 0.2);
        display: inline-block;
        line-height: 0;
        max-width: 100%;
        transition: box-shadow 200ms ease-out;
        border-radius: 3px;
        margin-bottom: 16px; }
        .thumbnail:hover, .thumbnail:focus {
          box-shadow: 0 0 6px 1px rgba(33, 153, 232, 0.5); }

      table.menu {
        width: 580px; }
        table.menu td.menu-item,
        table.menu th.menu-item {
          padding: 10px;
          padding-right: 10px; }
          table.menu td.menu-item a,
          table.menu th.menu-item a {
            color: #2199e8; }

      table.menu.vertical td.menu-item,
      table.menu.vertical th.menu-item {
        padding: 10px;
        padding-right: 0;
        display: block; }
        table.menu.vertical td.menu-item a,
        table.menu.vertical th.menu-item a {
          width: 100%; }

      table.menu.vertical td.menu-item table.menu.vertical td.menu-item,
      table.menu.vertical td.menu-item table.menu.vertical th.menu-item,
      table.menu.vertical th.menu-item table.menu.vertical td.menu-item,
      table.menu.vertical th.menu-item table.menu.vertical th.menu-item {
        padding-left: 10px; }

      table.menu.text-center a {
        text-align: center; }

      .menu[align="center"] {
        width: auto !important; }

      body.outlook p {
        display: inline !important; }

      @media only screen and (max-width: 596px) {
        table.body img {
          width: auto;
          height: auto; }
        table.body center {
          min-width: 0 !important; }
        table.body .container {
          width: 95% !important; }
        table.body .columns,
        table.body .column {
          height: auto !important;
          -moz-box-sizing: border-box;
          -webkit-box-sizing: border-box;
          box-sizing: border-box;
          padding-left: 16px !important;
          padding-right: 16px !important; }
          table.body .columns .column,
          table.body .columns .columns,
          table.body .column .column,
          table.body .column .columns {
            padding-left: 0 !important;
            padding-right: 0 !important; }
        table.body .collapse .columns,
        table.body .collapse .column {
          padding-left: 0 !important;
          padding-right: 0 !important; }
        td.small-1,
        th.small-1 {
          display: inline-block !important;
          width: 8.33333% !important; }
        td.small-2,
        th.small-2 {
          display: inline-block !important;
          width: 16.66667% !important; }
        td.small-3,
        th.small-3 {
          display: inline-block !important;
          width: 25% !important; }
        td.small-4,
        th.small-4 {
          display: inline-block !important;
          width: 33.33333% !important; }
        td.small-5,
        th.small-5 {
          display: inline-block !important;
          width: 41.66667% !important; }
        td.small-6,
        th.small-6 {
          display: inline-block !important;
          width: 50% !important; }
        td.small-7,
        th.small-7 {
          display: inline-block !important;
          width: 58.33333% !important; }
        td.small-8,
        th.small-8 {
          display: inline-block !important;
          width: 66.66667% !important; }
        td.small-9,
        th.small-9 {
          display: inline-block !important;
          width: 75% !important; }
        td.small-10,
        th.small-10 {
          display: inline-block !important;
          width: 83.33333% !important; }
        td.small-11,
        th.small-11 {
          display: inline-block !important;
          width: 91.66667% !important; }
        td.small-12,
        th.small-12 {
          display: inline-block !important;
          width: 100% !important; }
        .columns td.small-12,
        .column td.small-12,
        .columns th.small-12,
        .column th.small-12 {
          display: block !important;
          width: 100% !important; }
        table.body td.small-offset-1,
        table.body th.small-offset-1 {
          margin-left: 8.33333% !important;
          Margin-left: 8.33333% !important; }
        table.body td.small-offset-2,
        table.body th.small-offset-2 {
          margin-left: 16.66667% !important;
          Margin-left: 16.66667% !important; }
        table.body td.small-offset-3,
        table.body th.small-offset-3 {
          margin-left: 25% !important;
          Margin-left: 25% !important; }
        table.body td.small-offset-4,
        table.body th.small-offset-4 {
          margin-left: 33.33333% !important;
          Margin-left: 33.33333% !important; }
        table.body td.small-offset-5,
        table.body th.small-offset-5 {
          margin-left: 41.66667% !important;
          Margin-left: 41.66667% !important; }
        table.body td.small-offset-6,
        table.body th.small-offset-6 {
          margin-left: 50% !important;
          Margin-left: 50% !important; }
        table.body td.small-offset-7,
        table.body th.small-offset-7 {
          margin-left: 58.33333% !important;
          Margin-left: 58.33333% !important; }
        table.body td.small-offset-8,
        table.body th.small-offset-8 {
          margin-left: 66.66667% !important;
          Margin-left: 66.66667% !important; }
        table.body td.small-offset-9,
        table.body th.small-offset-9 {
          margin-left: 75% !important;
          Margin-left: 75% !important; }
        table.body td.small-offset-10,
        table.body th.small-offset-10 {
          margin-left: 83.33333% !important;
          Margin-left: 83.33333% !important; }
        table.body td.small-offset-11,
        table.body th.small-offset-11 {
          margin-left: 91.66667% !important;
          Margin-left: 91.66667% !important; }
        table.body table.columns td.expander,
        table.body table.columns th.expander {
          display: none !important; }
        table.body .right-text-pad,
        table.body .text-pad-right {
          padding-left: 10px !important; }
        table.body .left-text-pad,
        table.body .text-pad-left {
          padding-right: 10px !important; }
        table.menu {
          width: 100% !important; }
          table.menu td,
          table.menu th {
            width: auto !important;
            display: inline-block !important; }
          table.menu.vertical td,
          table.menu.vertical th, table.menu.small-vertical td,
          table.menu.small-vertical th {
            display: block !important; }
        table.menu[align="center"] {
          width: auto !important; }
        table.button.small-expand,
        table.button.small-expanded {
          width: 100% !important; }
          table.button.small-expand table,
          table.button.small-expanded table {
            width: 100%; }
            table.button.small-expand table a,
            table.button.small-expanded table a {
              text-align: center !important;
              width: 100% !important;
              padding-left: 0 !important;
              padding-right: 0 !important; }
          table.button.small-expand center,
          table.button.small-expanded center {
            min-width: 0; } }
          </style>
          <style>
            body,
            html,
            .body {
              background: #f3f3f3 !important;
            }
          </style>  
        </head>

        <body>
          <!-- <style> -->
          <table class="body" data-made-with-foundation="">
            <tr>
              <td class="float-center" align="center" valign="top">
                <center data-parsed="">
                  <table class="spacer float-center">
                    <tbody>
                      <tr>
                        <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                      </tr>
                    </tbody>
                  </table>
                  <table align="center" class="container float-center">
                    <tbody>
                      <tr>
                        <td>
                          <table class="spacer">
                            <tbody>
                              <tr>
                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                              </tr>
                            </tbody>
                          </table>
                          <table class="row">
                            <tbody>
                              <tr>
                                <th class="small-12 large-12 columns first last">
                                  <table>
                                    <tr>
                                      <th>
                                        <h1>NUEVO PEDIDO INGRESADO</h1>
                                        <p>Hemos recibido un nuevo pedido desde la website para que se surta lo m&aacute;s pronto posible. A continuaci$oacute;n se desgloza los datos.</p>
                                        <table class="spacer">
                                          <tbody>
                                            <tr>
                                              <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                        <table class="callout">
                                          <tr>
                                            <th class="callout-inner secondary">
                                              <table class="row">
                                                <tbody>
                                                  <tr>
                                                    <th class="small-12 large-6 columns first">
                                                      <table>
                                                        <tr>
                                                          <th>
                                                            <p> <strong>Fecha de Pedido</strong><br> '.$fecha.' </p>
                                                            <p> <strong>Email</strong><br> '.$email.' </p>
                                                            <p> <strong>Nombre</strong><br> '.iconv("UTF-8", "ISO-8859-1", $nombre).' </p>
                                                          </th>
                                                        </tr>
                                                      </table>
                                                    </th>
                                                    <th class="small-12 large-6 columns last">
                                                      <table>
                                                        <tr>
                                                          <th>
                                                            <p><strong>N%uacute;mero de Pedido</strong><br> '.$idOrder.' </p>
                                                            <p><strong>Folio</strong><br> '.$folio.' </p>
                                                            <p> <strong>Teléfono</strong><br> '.iconv("UTF-8", "ISO-8859-1", $tel).' </p>
                                                          </th>
                                                        </tr>
                                                      </table>
                                                    </th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                            </th>
                                            <th class="expander"></th>
                                          </tr>
                                        </table>
                                        <hr>
                                      </th>
                                    </tr>
                                  </table>
                                </th>
                              </tr>
                            </tbody>
                          </table>
                          <table class="row footer text-center">
                            <tbody>
                              <tr>
                                <th class="small-12 large-3 columns first">
                                  <table>
                                    <tr>
                                      <th> <p><a href="http://www.ferremayoristas.com.mx/mail/reporte.php">REPORTE COMPLETO</a></p> </th>
                                    </tr>
                                  </table>
                                </th>
                                <th class="small-12 large-3 columns">
                                  <table>
                                    <tr>
                                      <th>
                                        <p> 01 (442) 196 8555 <br> www.ferremayoristas.com.mx</p>
                                      </th>
                                    </tr>
                                  </table>
                                </th>
                                <th class="small-12 large-3 columns last">
                                  <table>
                                    <tr>
                                      <th>
                                        <p> El Pocito 76902<br> Corregidora Qro.</p>
                                      </th>
                                    </tr>
                                  </table>
                                </th>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </center>
              </td>
            </tr>
          </table>
        </body>

      </html>';
      $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

      try {
        $mail->SetFrom('contacto@ferremayoristas.com.mx', 'Ferremayoristas Olvera S.A. de C.V.');
        $mail->AddAddress('vleal@ferremayoristas.com.mx', 'Victor Leal');
        $mail->AddAddress('direccion@codigototal.com.mx', 'Carlos Ramírez');
        $mail->IsHTML(true);
        $mail->Subject = utf8_decode('NUEVO PEDIDO DE LA TIENDA');
        $mail->Body = $mensaje;
        $mail->Send();
        $body = utf8_decode('
        <!DOCTYPE html>
        <html lang="es">

          <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width">
            <title>Title</title>
            <style>
              .wrapper {
          width: 100%; }

        #outlook a {
          padding: 0; }

        body {
          width: 100% !important;
          min-width: 100%;
          -webkit-text-size-adjust: 100%;
          -ms-text-size-adjust: 100%;
          margin: 0;
          Margin: 0;
          padding: 0;
          -moz-box-sizing: border-box;
          -webkit-box-sizing: border-box;
          box-sizing: border-box; }

        .ExternalClass {
          width: 100%; }
          .ExternalClass,
          .ExternalClass p,
          .ExternalClass span,
          .ExternalClass font,
          .ExternalClass td,
          .ExternalClass div {
            line-height: 100%; }

        #backgroundTable {
          margin: 0;
          Margin: 0;
          padding: 0;
          width: 100% !important;
          line-height: 100% !important; }

        img {
          outline: none;
          text-decoration: none;
          -ms-interpolation-mode: bicubic;
          width: auto;
          max-width: 100%;
          clear: both;
          display: block; }

        center {
          width: 100%;
          min-width: 580px; }

        a img {
          border: none; }

        p {
          margin: 0 0 0 10px;
          Margin: 0 0 0 10px; }

        table {
          border-spacing: 0;
          border-collapse: collapse; }

        td {
          word-wrap: break-word;
          -webkit-hyphens: auto;
          -moz-hyphens: auto;
          hyphens: auto;
          border-collapse: collapse !important; }

        table, tr, td {
          padding: 0;
          vertical-align: top;
          text-align: left; }

        @media only screen {
          html {
            min-height: 100%;
            background: #f3f3f3; } }

        table.body {
          background: #f3f3f3;
          height: 100%;
          width: 100%; }

        table.container {
          background: #fefefe;
          width: 580px;
          margin: 0 auto;
          Margin: 0 auto;
          text-align: inherit; }

        table.row {
          padding: 0;
          width: 100%;
          position: relative; }

        table.spacer {
          width: 100%; }
          table.spacer td {
            mso-line-height-rule: exactly; }

        table.container table.row {
          display: table; }

        td.columns,
        td.column,
        th.columns,
        th.column {
          margin: 0 auto;
          Margin: 0 auto;
          padding-left: 16px;
          padding-bottom: 16px; }
          td.columns .column,
          td.columns .columns,
          td.column .column,
          td.column .columns,
          th.columns .column,
          th.columns .columns,
          th.column .column,
          th.column .columns {
            padding-left: 0 !important;
            padding-right: 0 !important; }
            td.columns .column center,
            td.columns .columns center,
            td.column .column center,
            td.column .columns center,
            th.columns .column center,
            th.columns .columns center,
            th.column .column center,
            th.column .columns center {
              min-width: none !important; }

        td.columns.last,
        td.column.last,
        th.columns.last,
        th.column.last {
          padding-right: 16px; }

        td.columns table:not(.button),
        td.column table:not(.button),
        th.columns table:not(.button),
        th.column table:not(.button) {
          width: 100%; }

        td.large-1,
        th.large-1 {
          width: 32.33333px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-1.first,
        th.large-1.first {
          padding-left: 16px; }

        td.large-1.last,
        th.large-1.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-1,
        .collapse > tbody > tr > th.large-1 {
          padding-right: 0;
          padding-left: 0;
          width: 48.33333px; }

        .collapse td.large-1.first,
        .collapse th.large-1.first,
        .collapse td.large-1.last,
        .collapse th.large-1.last {
          width: 56.33333px; }

        td.large-1 center,
        th.large-1 center {
          min-width: 0.33333px; }

        .body .columns td.large-1,
        .body .column td.large-1,
        .body .columns th.large-1,
        .body .column th.large-1 {
          width: 8.33333%; }

        td.large-2,
        th.large-2 {
          width: 80.66667px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-2.first,
        th.large-2.first {
          padding-left: 16px; }

        td.large-2.last,
        th.large-2.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-2,
        .collapse > tbody > tr > th.large-2 {
          padding-right: 0;
          padding-left: 0;
          width: 96.66667px; }

        .collapse td.large-2.first,
        .collapse th.large-2.first,
        .collapse td.large-2.last,
        .collapse th.large-2.last {
          width: 104.66667px; }

        td.large-2 center,
        th.large-2 center {
          min-width: 48.66667px; }

        .body .columns td.large-2,
        .body .column td.large-2,
        .body .columns th.large-2,
        .body .column th.large-2 {
          width: 16.66667%; }

        td.large-3,
        th.large-3 {
          width: 129px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-3.first,
        th.large-3.first {
          padding-left: 16px; }

        td.large-3.last,
        th.large-3.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-3,
        .collapse > tbody > tr > th.large-3 {
          padding-right: 0;
          padding-left: 0;
          width: 145px; }

        .collapse td.large-3.first,
        .collapse th.large-3.first,
        .collapse td.large-3.last,
        .collapse th.large-3.last {
          width: 153px; }

        td.large-3 center,
        th.large-3 center {
          min-width: 97px; }

        .body .columns td.large-3,
        .body .column td.large-3,
        .body .columns th.large-3,
        .body .column th.large-3 {
          width: 25%; }

        td.large-4,
        th.large-4 {
          width: 177.33333px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-4.first,
        th.large-4.first {
          padding-left: 16px; }

        td.large-4.last,
        th.large-4.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-4,
        .collapse > tbody > tr > th.large-4 {
          padding-right: 0;
          padding-left: 0;
          width: 193.33333px; }

        .collapse td.large-4.first,
        .collapse th.large-4.first,
        .collapse td.large-4.last,
        .collapse th.large-4.last {
          width: 201.33333px; }

        td.large-4 center,
        th.large-4 center {
          min-width: 145.33333px; }

        .body .columns td.large-4,
        .body .column td.large-4,
        .body .columns th.large-4,
        .body .column th.large-4 {
          width: 33.33333%; }

        td.large-5,
        th.large-5 {
          width: 225.66667px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-5.first,
        th.large-5.first {
          padding-left: 16px; }

        td.large-5.last,
        th.large-5.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-5,
        .collapse > tbody > tr > th.large-5 {
          padding-right: 0;
          padding-left: 0;
          width: 241.66667px; }

        .collapse td.large-5.first,
        .collapse th.large-5.first,
        .collapse td.large-5.last,
        .collapse th.large-5.last {
          width: 249.66667px; }

        td.large-5 center,
        th.large-5 center {
          min-width: 193.66667px; }

        .body .columns td.large-5,
        .body .column td.large-5,
        .body .columns th.large-5,
        .body .column th.large-5 {
          width: 41.66667%; }

        td.large-6,
        th.large-6 {
          width: 274px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-6.first,
        th.large-6.first {
          padding-left: 16px; }

        td.large-6.last,
        th.large-6.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-6,
        .collapse > tbody > tr > th.large-6 {
          padding-right: 0;
          padding-left: 0;
          width: 290px; }

        .collapse td.large-6.first,
        .collapse th.large-6.first,
        .collapse td.large-6.last,
        .collapse th.large-6.last {
          width: 298px; }

        td.large-6 center,
        th.large-6 center {
          min-width: 242px; }

        .body .columns td.large-6,
        .body .column td.large-6,
        .body .columns th.large-6,
        .body .column th.large-6 {
          width: 50%; }

        td.large-7,
        th.large-7 {
          width: 322.33333px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-7.first,
        th.large-7.first {
          padding-left: 16px; }

        td.large-7.last,
        th.large-7.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-7,
        .collapse > tbody > tr > th.large-7 {
          padding-right: 0;
          padding-left: 0;
          width: 338.33333px; }

        .collapse td.large-7.first,
        .collapse th.large-7.first,
        .collapse td.large-7.last,
        .collapse th.large-7.last {
          width: 346.33333px; }

        td.large-7 center,
        th.large-7 center {
          min-width: 290.33333px; }

        .body .columns td.large-7,
        .body .column td.large-7,
        .body .columns th.large-7,
        .body .column th.large-7 {
          width: 58.33333%; }

        td.large-8,
        th.large-8 {
          width: 370.66667px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-8.first,
        th.large-8.first {
          padding-left: 16px; }

        td.large-8.last,
        th.large-8.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-8,
        .collapse > tbody > tr > th.large-8 {
          padding-right: 0;
          padding-left: 0;
          width: 386.66667px; }

        .collapse td.large-8.first,
        .collapse th.large-8.first,
        .collapse td.large-8.last,
        .collapse th.large-8.last {
          width: 394.66667px; }

        td.large-8 center,
        th.large-8 center {
          min-width: 338.66667px; }

        .body .columns td.large-8,
        .body .column td.large-8,
        .body .columns th.large-8,
        .body .column th.large-8 {
          width: 66.66667%; }

        td.large-9,
        th.large-9 {
          width: 419px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-9.first,
        th.large-9.first {
          padding-left: 16px; }

        td.large-9.last,
        th.large-9.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-9,
        .collapse > tbody > tr > th.large-9 {
          padding-right: 0;
          padding-left: 0;
          width: 435px; }

        .collapse td.large-9.first,
        .collapse th.large-9.first,
        .collapse td.large-9.last,
        .collapse th.large-9.last {
          width: 443px; }

        td.large-9 center,
        th.large-9 center {
          min-width: 387px; }

        .body .columns td.large-9,
        .body .column td.large-9,
        .body .columns th.large-9,
        .body .column th.large-9 {
          width: 75%; }

        td.large-10,
        th.large-10 {
          width: 467.33333px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-10.first,
        th.large-10.first {
          padding-left: 16px; }

        td.large-10.last,
        th.large-10.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-10,
        .collapse > tbody > tr > th.large-10 {
          padding-right: 0;
          padding-left: 0;
          width: 483.33333px; }

        .collapse td.large-10.first,
        .collapse th.large-10.first,
        .collapse td.large-10.last,
        .collapse th.large-10.last {
          width: 491.33333px; }

        td.large-10 center,
        th.large-10 center {
          min-width: 435.33333px; }

        .body .columns td.large-10,
        .body .column td.large-10,
        .body .columns th.large-10,
        .body .column th.large-10 {
          width: 83.33333%; }

        td.large-11,
        th.large-11 {
          width: 515.66667px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-11.first,
        th.large-11.first {
          padding-left: 16px; }

        td.large-11.last,
        th.large-11.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-11,
        .collapse > tbody > tr > th.large-11 {
          padding-right: 0;
          padding-left: 0;
          width: 531.66667px; }

        .collapse td.large-11.first,
        .collapse th.large-11.first,
        .collapse td.large-11.last,
        .collapse th.large-11.last {
          width: 539.66667px; }

        td.large-11 center,
        th.large-11 center {
          min-width: 483.66667px; }

        .body .columns td.large-11,
        .body .column td.large-11,
        .body .columns th.large-11,
        .body .column th.large-11 {
          width: 91.66667%; }

        td.large-12,
        th.large-12 {
          width: 564px;
          padding-left: 8px;
          padding-right: 8px; }

        td.large-12.first,
        th.large-12.first {
          padding-left: 16px; }

        td.large-12.last,
        th.large-12.last {
          padding-right: 16px; }

        .collapse > tbody > tr > td.large-12,
        .collapse > tbody > tr > th.large-12 {
          padding-right: 0;
          padding-left: 0;
          width: 580px; }

        .collapse td.large-12.first,
        .collapse th.large-12.first,
        .collapse td.large-12.last,
        .collapse th.large-12.last {
          width: 588px; }

        td.large-12 center,
        th.large-12 center {
          min-width: 532px; }

        .body .columns td.large-12,
        .body .column td.large-12,
        .body .columns th.large-12,
        .body .column th.large-12 {
          width: 100%; }

        td.large-offset-1,
        td.large-offset-1.first,
        td.large-offset-1.last,
        th.large-offset-1,
        th.large-offset-1.first,
        th.large-offset-1.last {
          padding-left: 64.33333px; }

        td.large-offset-2,
        td.large-offset-2.first,
        td.large-offset-2.last,
        th.large-offset-2,
        th.large-offset-2.first,
        th.large-offset-2.last {
          padding-left: 112.66667px; }

        td.large-offset-3,
        td.large-offset-3.first,
        td.large-offset-3.last,
        th.large-offset-3,
        th.large-offset-3.first,
        th.large-offset-3.last {
          padding-left: 161px; }

        td.large-offset-4,
        td.large-offset-4.first,
        td.large-offset-4.last,
        th.large-offset-4,
        th.large-offset-4.first,
        th.large-offset-4.last {
          padding-left: 209.33333px; }

        td.large-offset-5,
        td.large-offset-5.first,
        td.large-offset-5.last,
        th.large-offset-5,
        th.large-offset-5.first,
        th.large-offset-5.last {
          padding-left: 257.66667px; }

        td.large-offset-6,
        td.large-offset-6.first,
        td.large-offset-6.last,
        th.large-offset-6,
        th.large-offset-6.first,
        th.large-offset-6.last {
          padding-left: 306px; }

        td.large-offset-7,
        td.large-offset-7.first,
        td.large-offset-7.last,
        th.large-offset-7,
        th.large-offset-7.first,
        th.large-offset-7.last {
          padding-left: 354.33333px; }

        td.large-offset-8,
        td.large-offset-8.first,
        td.large-offset-8.last,
        th.large-offset-8,
        th.large-offset-8.first,
        th.large-offset-8.last {
          padding-left: 402.66667px; }

        td.large-offset-9,
        td.large-offset-9.first,
        td.large-offset-9.last,
        th.large-offset-9,
        th.large-offset-9.first,
        th.large-offset-9.last {
          padding-left: 451px; }

        td.large-offset-10,
        td.large-offset-10.first,
        td.large-offset-10.last,
        th.large-offset-10,
        th.large-offset-10.first,
        th.large-offset-10.last {
          padding-left: 499.33333px; }

        td.large-offset-11,
        td.large-offset-11.first,
        td.large-offset-11.last,
        th.large-offset-11,
        th.large-offset-11.first,
        th.large-offset-11.last {
          padding-left: 547.66667px; }

        td.expander,
        th.expander {
          visibility: hidden;
          width: 0;
          padding: 0 !important; }

        table.container.radius {
          border-radius: 0;
          border-collapse: separate; }

        .block-grid {
          width: 100%;
          max-width: 580px; }
          .block-grid td {
            display: inline-block;
            padding: 8px; }

        .up-2 td {
          width: 274px !important; }

        .up-3 td {
          width: 177px !important; }

        .up-4 td {
          width: 129px !important; }

        .up-5 td {
          width: 100px !important; }

        .up-6 td {
          width: 80px !important; }

        .up-7 td {
          width: 66px !important; }

        .up-8 td {
          width: 56px !important; }

        table.text-center,
        th.text-center,
        td.text-center,
        h1.text-center,
        h2.text-center,
        h3.text-center,
        h4.text-center,
        h5.text-center,
        h6.text-center,
        p.text-center,
        span.text-center {
          text-align: center; }

        table.text-left,
        th.text-left,
        td.text-left,
        h1.text-left,
        h2.text-left,
        h3.text-left,
        h4.text-left,
        h5.text-left,
        h6.text-left,
        p.text-left,
        span.text-left {
          text-align: left; }

        table.text-right,
        th.text-right,
        td.text-right,
        h1.text-right,
        h2.text-right,
        h3.text-right,
        h4.text-right,
        h5.text-right,
        h6.text-right,
        p.text-right,
        span.text-right {
          text-align: right; }

        span.text-center {
          display: block;
          width: 100%;
          text-align: center; }

        @media only screen and (max-width: 596px) {
          .small-float-center {
            margin: 0 auto !important;
            float: none !important;
            text-align: center !important; }
          .small-text-center {
            text-align: center !important; }
          .small-text-left {
            text-align: left !important; }
          .small-text-right {
            text-align: right !important; } }

        img.float-left {
          float: left;
          text-align: left; }

        img.float-right {
          float: right;
          text-align: right; }

        img.float-center,
        img.text-center {
          margin: 0 auto;
          Margin: 0 auto;
          float: none;
          text-align: center; }

        table.float-center,
        td.float-center,
        th.float-center {
          margin: 0 auto;
          Margin: 0 auto;
          float: none;
          text-align: center; }

        .hide-for-large {
          display: none !important;
          mso-hide: all;
          overflow: hidden;
          max-height: 0;
          font-size: 0;
          width: 0;
          line-height: 0; }
          @media only screen and (max-width: 596px) {
            .hide-for-large {
              display: block !important;
              width: auto !important;
              overflow: visible !important;
              max-height: none !important;
              font-size: inherit !important;
              line-height: inherit !important; } }

        table.body table.container .hide-for-large * {
          mso-hide: all; }

        @media only screen and (max-width: 596px) {
          table.body table.container .hide-for-large,
          table.body table.container .row.hide-for-large {
            display: table !important;
            width: 100% !important; } }

        @media only screen and (max-width: 596px) {
          table.body table.container .callout-inner.hide-for-large {
            display: table-cell !important;
            width: 100% !important; } }

        @media only screen and (max-width: 596px) {
          table.body table.container .show-for-large {
            display: none !important;
            width: 0;
            mso-hide: all;
            overflow: hidden; } }

        body,
        table.body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        td,
        th,
        a {
          color: #0a0a0a;
          font-family: Helvetica, Arial, sans-serif;
          font-weight: normal;
          padding: 0;
          margin: 0;
          Margin: 0;
          text-align: left;
          line-height: 1.3; }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
          color: inherit;
          word-wrap: normal;
          font-family: Helvetica, Arial, sans-serif;
          font-weight: normal;
          margin-bottom: 10px;
          Margin-bottom: 10px; }

        h1 {
          font-size: 34px; }

        h2 {
          font-size: 30px; }

        h3 {
          font-size: 28px; }

        h4 {
          font-size: 24px; }

        h5 {
          font-size: 20px; }

        h6 {
          font-size: 18px; }

        body,
        table.body,
        p,
        td,
        th {
          font-size: 16px;
          line-height: 1.3; }

        p {
          margin-bottom: 10px;
          Margin-bottom: 10px; }
          p.lead {
            font-size: 20px;
            line-height: 1.6; }
          p.subheader {
            margin-top: 4px;
            margin-bottom: 8px;
            Margin-top: 4px;
            Margin-bottom: 8px;
            font-weight: normal;
            line-height: 1.4;
            color: #8a8a8a; }

        small {
          font-size: 80%;
          color: #cacaca; }

        a {
          color: #2199e8;
          text-decoration: none; }
          a:hover {
            color: #147dc2; }
          a:active {
            color: #147dc2; }
          a:visited {
            color: #2199e8; }

        h1 a,
        h1 a:visited,
        h2 a,
        h2 a:visited,
        h3 a,
        h3 a:visited,
        h4 a,
        h4 a:visited,
        h5 a,
        h5 a:visited,
        h6 a,
        h6 a:visited {
          color: #2199e8; }

        pre {
          background: #f3f3f3;
          margin: 30px 0;
          Margin: 30px 0; }
          pre code {
            color: #cacaca; }
            pre code span.callout {
              color: #8a8a8a;
              font-weight: bold; }
            pre code span.callout-strong {
              color: #ff6908;
              font-weight: bold; }

        table.hr {
          width: 100%; }
          table.hr th {
            height: 0;
            max-width: 580px;
            border-top: 0;
            border-right: 0;
            border-bottom: 1px solid #0a0a0a;
            border-left: 0;
            margin: 20px auto;
            Margin: 20px auto;
            clear: both; }

        .stat {
          font-size: 40px;
          line-height: 1; }
          p + .stat {
            margin-top: -16px;
            Margin-top: -16px; }

        span.preheader {
          display: none !important;
          visibility: hidden;
          mso-hide: all !important;
          font-size: 1px;
          color: #f3f3f3;
          line-height: 1px;
          max-height: 0px;
          max-width: 0px;
          opacity: 0;
          overflow: hidden; }

        table.button {
          width: auto;
          margin: 0 0 16px 0;
          Margin: 0 0 16px 0; }
          table.button table td {
            text-align: left;
            color: #fefefe;
            background: #2199e8;
            border: 2px solid #2199e8; }
            table.button table td a {
              font-family: Helvetica, Arial, sans-serif;
              font-size: 16px;
              font-weight: bold;
              color: #fefefe;
              text-decoration: none;
              display: inline-block;
              padding: 8px 16px 8px 16px;
              border: 0 solid #2199e8;
              border-radius: 3px; }
          table.button.radius table td {
            border-radius: 3px;
            border: none; }
          table.button.rounded table td {
            border-radius: 500px;
            border: none; }

        table.button:hover table tr td a,
        table.button:active table tr td a,
        table.button table tr td a:visited,
        table.button.tiny:hover table tr td a,
        table.button.tiny:active table tr td a,
        table.button.tiny table tr td a:visited,
        table.button.small:hover table tr td a,
        table.button.small:active table tr td a,
        table.button.small table tr td a:visited,
        table.button.large:hover table tr td a,
        table.button.large:active table tr td a,
        table.button.large table tr td a:visited {
          color: #fefefe; }

        table.button.tiny table td,
        table.button.tiny table a {
          padding: 4px 8px 4px 8px; }

        table.button.tiny table a {
          font-size: 10px;
          font-weight: normal; }

        table.button.small table td,
        table.button.small table a {
          padding: 5px 10px 5px 10px;
          font-size: 12px; }

        table.button.large table a {
          padding: 10px 20px 10px 20px;
          font-size: 20px; }

        table.button.expand,
        table.button.expanded {
          width: 100% !important; }
          table.button.expand table,
          table.button.expanded table {
            width: 100%; }
            table.button.expand table a,
            table.button.expanded table a {
              text-align: center;
              width: 100%;
              padding-left: 0;
              padding-right: 0; }
          table.button.expand center,
          table.button.expanded center {
            min-width: 0; }

        table.button:hover table td,
        table.button:visited table td,
        table.button:active table td {
          background: #147dc2;
          color: #fefefe; }

        table.button:hover table a,
        table.button:visited table a,
        table.button:active table a {
          border: 0 solid #147dc2; }

        table.button.secondary table td {
          background: #777777;
          color: #fefefe;
          border: 0px solid #777777; }

        table.button.secondary table a {
          color: #fefefe;
          border: 0 solid #777777; }

        table.button.secondary:hover table td {
          background: #919191;
          color: #fefefe; }

        table.button.secondary:hover table a {
          border: 0 solid #919191; }

        table.button.secondary:hover table td a {
          color: #fefefe; }

        table.button.secondary:active table td a {
          color: #fefefe; }

        table.button.secondary table td a:visited {
          color: #fefefe; }

        table.button.success table td {
          background: #3adb76;
          border: 0px solid #3adb76; }

        table.button.success table a {
          border: 0 solid #3adb76; }

        table.button.success:hover table td {
          background: #23bf5d; }

        table.button.success:hover table a {
          border: 0 solid #23bf5d; }

        table.button.alert table td {
          background: #ec5840;
          border: 0px solid #ec5840; }

        table.button.alert table a {
          border: 0 solid #ec5840; }

        table.button.alert:hover table td {
          background: #e23317; }

        table.button.alert:hover table a {
          border: 0 solid #e23317; }

        table.button.warning table td {
          background: #ffae00;
          border: 0px solid #ffae00; }

        table.button.warning table a {
          border: 0px solid #ffae00; }

        table.button.warning:hover table td {
          background: #cc8b00; }

        table.button.warning:hover table a {
          border: 0px solid #cc8b00; }

        table.callout {
          margin-bottom: 16px;
          Margin-bottom: 16px; }

        th.callout-inner {
          width: 100%;
          border: 1px solid #cbcbcb;
          padding: 10px;
          background: #fefefe; }
          th.callout-inner.primary {
            background: #def0fc;
            border: 1px solid #444444;
            color: #0a0a0a; }
          th.callout-inner.secondary {
            background: #ebebeb;
            border: 1px solid #444444;
            color: #0a0a0a; }
          th.callout-inner.success {
            background: #e1faea;
            border: 1px solid #1b9448;
            color: #fefefe; }
          th.callout-inner.warning {
            background: #fff3d9;
            border: 1px solid #996800;
            color: #fefefe; }
          th.callout-inner.alert {
            background: #fce6e2;
            border: 1px solid #b42912;
            color: #fefefe; }

        .thumbnail {
          border: solid 4px #fefefe;
          box-shadow: 0 0 0 1px rgba(10, 10, 10, 0.2);
          display: inline-block;
          line-height: 0;
          max-width: 100%;
          transition: box-shadow 200ms ease-out;
          border-radius: 3px;
          margin-bottom: 16px; }
          .thumbnail:hover, .thumbnail:focus {
            box-shadow: 0 0 6px 1px rgba(33, 153, 232, 0.5); }

        table.menu {
          width: 580px; }
          table.menu td.menu-item,
          table.menu th.menu-item {
            padding: 10px;
            padding-right: 10px; }
            table.menu td.menu-item a,
            table.menu th.menu-item a {
              color: #2199e8; }

        table.menu.vertical td.menu-item,
        table.menu.vertical th.menu-item {
          padding: 10px;
          padding-right: 0;
          display: block; }
          table.menu.vertical td.menu-item a,
          table.menu.vertical th.menu-item a {
            width: 100%; }

        table.menu.vertical td.menu-item table.menu.vertical td.menu-item,
        table.menu.vertical td.menu-item table.menu.vertical th.menu-item,
        table.menu.vertical th.menu-item table.menu.vertical td.menu-item,
        table.menu.vertical th.menu-item table.menu.vertical th.menu-item {
          padding-left: 10px; }

        table.menu.text-center a {
          text-align: center; }

        .menu[align="center"] {
          width: auto !important; }

        body.outlook p {
          display: inline !important; }

        @media only screen and (max-width: 596px) {
          table.body img {
            width: auto;
            height: auto; }
          table.body center {
            min-width: 0 !important; }
          table.body .container {
            width: 95% !important; }
          table.body .columns,
          table.body .column {
            height: auto !important;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding-left: 16px !important;
            padding-right: 16px !important; }
            table.body .columns .column,
            table.body .columns .columns,
            table.body .column .column,
            table.body .column .columns {
              padding-left: 0 !important;
              padding-right: 0 !important; }
          table.body .collapse .columns,
          table.body .collapse .column {
            padding-left: 0 !important;
            padding-right: 0 !important; }
          td.small-1,
          th.small-1 {
            display: inline-block !important;
            width: 8.33333% !important; }
          td.small-2,
          th.small-2 {
            display: inline-block !important;
            width: 16.66667% !important; }
          td.small-3,
          th.small-3 {
            display: inline-block !important;
            width: 25% !important; }
          td.small-4,
          th.small-4 {
            display: inline-block !important;
            width: 33.33333% !important; }
          td.small-5,
          th.small-5 {
            display: inline-block !important;
            width: 41.66667% !important; }
          td.small-6,
          th.small-6 {
            display: inline-block !important;
            width: 50% !important; }
          td.small-7,
          th.small-7 {
            display: inline-block !important;
            width: 58.33333% !important; }
          td.small-8,
          th.small-8 {
            display: inline-block !important;
            width: 66.66667% !important; }
          td.small-9,
          th.small-9 {
            display: inline-block !important;
            width: 75% !important; }
          td.small-10,
          th.small-10 {
            display: inline-block !important;
            width: 83.33333% !important; }
          td.small-11,
          th.small-11 {
            display: inline-block !important;
            width: 91.66667% !important; }
          td.small-12,
          th.small-12 {
            display: inline-block !important;
            width: 100% !important; }
          .columns td.small-12,
          .column td.small-12,
          .columns th.small-12,
          .column th.small-12 {
            display: block !important;
            width: 100% !important; }
          table.body td.small-offset-1,
          table.body th.small-offset-1 {
            margin-left: 8.33333% !important;
            Margin-left: 8.33333% !important; }
          table.body td.small-offset-2,
          table.body th.small-offset-2 {
            margin-left: 16.66667% !important;
            Margin-left: 16.66667% !important; }
          table.body td.small-offset-3,
          table.body th.small-offset-3 {
            margin-left: 25% !important;
            Margin-left: 25% !important; }
          table.body td.small-offset-4,
          table.body th.small-offset-4 {
            margin-left: 33.33333% !important;
            Margin-left: 33.33333% !important; }
          table.body td.small-offset-5,
          table.body th.small-offset-5 {
            margin-left: 41.66667% !important;
            Margin-left: 41.66667% !important; }
          table.body td.small-offset-6,
          table.body th.small-offset-6 {
            margin-left: 50% !important;
            Margin-left: 50% !important; }
          table.body td.small-offset-7,
          table.body th.small-offset-7 {
            margin-left: 58.33333% !important;
            Margin-left: 58.33333% !important; }
          table.body td.small-offset-8,
          table.body th.small-offset-8 {
            margin-left: 66.66667% !important;
            Margin-left: 66.66667% !important; }
          table.body td.small-offset-9,
          table.body th.small-offset-9 {
            margin-left: 75% !important;
            Margin-left: 75% !important; }
          table.body td.small-offset-10,
          table.body th.small-offset-10 {
            margin-left: 83.33333% !important;
            Margin-left: 83.33333% !important; }
          table.body td.small-offset-11,
          table.body th.small-offset-11 {
            margin-left: 91.66667% !important;
            Margin-left: 91.66667% !important; }
          table.body table.columns td.expander,
          table.body table.columns th.expander {
            display: none !important; }
          table.body .right-text-pad,
          table.body .text-pad-right {
            padding-left: 10px !important; }
          table.body .left-text-pad,
          table.body .text-pad-left {
            padding-right: 10px !important; }
          table.menu {
            width: 100% !important; }
            table.menu td,
            table.menu th {
              width: auto !important;
              display: inline-block !important; }
            table.menu.vertical td,
            table.menu.vertical th, table.menu.small-vertical td,
            table.menu.small-vertical th {
              display: block !important; }
          table.menu[align="center"] {
            width: auto !important; }
          table.button.small-expand,
          table.button.small-expanded {
            width: 100% !important; }
            table.button.small-expand table,
            table.button.small-expanded table {
              width: 100%; }
              table.button.small-expand table a,
              table.button.small-expanded table a {
                text-align: center !important;
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important; }
            table.button.small-expand center,
            table.button.small-expanded center {
              min-width: 0; } }
            
            </style>  

            <style>
              body,
              html,
              .body {
                background: #f3f3f3 !important;
              }
              
              .container.header {
                background: #f3f3f3;
              }
              
              .body-border {
                border-top: 8px solid #663399;
              }
            </style>
          </head>

          <body>
            <!-- <style> -->
            <table class="body" data-made-with-foundation="">
              <tr>
                <td class="float-center" align="center" valign="top">
                  <center data-parsed="">
                    <table class="spacer float-center">
                      <tbody>
                        <tr>
                          <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                        </tr>
                      </tbody>
                    </table>
                    <table align="center" class="container header float-center">
                      <tbody>
                        <tr>
                          <td>
                            <table class="row">
                              <tbody>
                                <tr>
                                  <th class="small-12 large-12 columns first last">
                                    <table>
                                      <tr>
                                        <th>
                                          <h1 class="text-center">Gracias por tu pedido!</h1>
                                          <center data-parsed="">
                                            <table align="center" class="menu text-center float-center">
                                              <tr>
                                                <td>
                                                  <table>
                                                    <tr align="center" class="text-center">
                                                    </tr>
                                                  </table>
                                                </td>
                                              </tr>
                                            </table>
                                          </center>
                                        </th>
                                        <th class="expander"></th>
                                      </tr>
                                    </table>
                                  </th>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table align="center" class="container body-border float-center">
                      <tbody>
                        <tr>
                          <td>
                            <table class="row">
                              <tbody>
                                <tr>
                                  <th class="small-12 large-12 columns first last">
                                    <table>
                                      <tr>
                                        <th>
                                          <table class="spacer">
                                            <tbody>
                                              <tr>
                                                <td height="32px" style="font-size:32px;line-height:32px;">&#xA0;</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                          <center data-parsed=""> <a href="http://www.ferremayoristas.com.mx/"><img src="http://www.ferremayoristas.com.mx/assets/img/logo.png" style="width:80%;" align="center" class="float-center"></a> </center>
                                          <table class="spacer">
                                            <tbody>
                                              <tr>
                                                <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                          <h5 style="text-align:center;">Ideas y soluciones para el hogar y los negocios</h5>
                                          <p> Hola <strong>'.$nombre.'</strong>,</p>
                                          <p style="text-align:justify;">Gracias por realizar tu pedido en nuestra tienda virtual, si necesitas saber el eststus de tu folio, te invitamos a que lo realices en nuestra website en el apartado de Ayuda > Mi Pedido, con tu folio.</p>
                                          <p style="text-align:justify;">Informaci&oacute; de tu pedido:</p>
                                          <table class="row">
                                            <tbody>
                                              <tr>
                                                <th class="small-12 large-6 columns first">
                                                  <table>
                                                    <tr>
                                                      <th>
                                                        <p> <strong>Fecha de Pedido</strong><br> '.$fecha.' </p>
                                                        <p><strong>N%uacute;mero de Pedido</strong><br> '.$idOrder.' </p>
                                                        <p><strong>Folio</strong><br> '.$folio.' </p>
                                                      </th>
                                                    </tr>
                                                  </table>
                                                </th>
                                              </tr>
                                            </tbody>
                                          </table>
                                          <center data-parsed="">
                                            <table align="center" class="menu float-center">
                                              <tr>
                                                <td>
                                                  <table>
                                                    <tr><center>
                                                      <th class="menu-item float-center"><a href="https://www.ferremayoristas.com.mx/tienda">Tienda |</a></th>
                                                      <th class="menu-item float-center"><a href="https://www.facebook.com/FerremayoristasOlvera/"> Facebook |</a></th>
                                                      <th class="menu-item float-center"><a href="https://twitter.com/fmomx"> Twitter |</a></th>
                                                      <th class="menu-item float-center"><a href="#"> (442) 196 8555</a></th>
                                                    </center></tr>
                                                  </table>
                                                </td>
                                              </tr>
                                            </table>
                                          </center>
                                        </th>
                                        <th class="expander"></th>
                                      </tr>
                                    </table>
                                  </th>
                                </tr>
                              </tbody>
                            </table>
                            <table class="spacer">
                              <tbody>
                                <tr>
                                  <td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </center>
                </td>
              </tr>
            </table>
          </body>

        </html>');
        $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

        try {
          $mail->SetFrom('contacto@ferremayoristas.com.mx', 'Ferremayoristas Olvera S.A. de C.V.');
          $mail->AddAddress($email, utf8_decode($nombre));
          $mail->IsHTML(true);
          $mail->Subject = utf8_decode('Respuesta de Confirmación');
          $mail->Body = $body;
          $mail->Send();
          } catch (phpmailerException $mail) {
          } catch (Exception $mail) {
        }
      } catch (phpmailerException $mail) {
      } catch (Exception $mail) {
      }
  }

  private function showDetailOrder($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $pedidoID = $paramDb->SecureInput($params["pedidoID"]);
    $numPrecio = $paramDb->SecureInput($params["numPrecio"]);
    $userLocation = $params["ubicacionUsuario"];
    // @param bManagementOrder = to identify type of user and print more "options"
    // @param bPrintButtonConfirmOrder = to print if all quantity exist its diferent to zero
    // @param bStatusPedido = check if the are products to make order, and exist the case that product its not available, then check status
    $arrayBooleans = array("bManagementOrder" => false,
                            "bPrintButtonConfirmOrder" => false,
                            "bStatusPedido" => false);

    if($params["ubicacionUsuario"] == "registrado") {
      $rol = $_SESSION["data"]["rol"];
      if($rol == 'administrador' || $rol == 'pedidos') {
        $arrayBooleans["bManagementOrder"] = true;
      }
    } // else, it's user: publico

    /*// TODO Testing and check results are correct, or not; add GROUP AND ORDER BY p.id
    $getOrder = "SELECT dp.*, c.*, pro.titulo, p.fechaPedido, dp.tipoPieza, dp.piezas, p.status as statusPedido,
                (
                  SELECT sbq1Ro.rol
                    FROM pedidos sbq1Pe
                      JOIN clientes sbq1Cl ON sbq1Cl.id = sbq1Pe.clienteID
                      JOIN roles sbq1Ro ON sbq1Ro.id = sbq1Cl.roleID
                      WHERE sbq1Pe.id = p.id
                 ) as rolQuery,
                 (
                  SELECT CASE
                      WHEN rolQuery = 'mayorista' THEN pre.mayorista
                      WHEN rolQuery = 'distribuidor' THEN pre.distribuidor
                      WHEN rolQuery = 'subdistribuidor' THEN pre.subdistribuidor
                    ELSE pre.mayorista
                    END
                  ) AS precio
                  FROM pedidos p
                  JOIN clientes c ON c.id = p.clienteID
                  JOIN descripcionPedido dp ON dp.pedidoID = p.id
                  JOIN productos pro ON pro.id = dp.productoID
                  JOIN precios pre ON pre.id = pro.id
                  WHERE dp.pedidoID = $pedidoID";*/

    // TODO Testing and check results are correct, or not; add GROUP AND ORDER BY p.id
    $getOrder = "SELECT i.descripcio, dp.productoID, i.clvprov, pre.precio, dp.tipoPieza, dp.cantidadSolicitada, dp.cantidadSurtida, p.status as statusPedido, p.fechaPedido, dp.piezas, cli.nombre, pre.pimpuesto
                 FROM pedidos p
                  JOIN descripcionPedido dp ON dp.pedidoID = p.id
                  JOIN inv i ON i.articuloid = dp.productoID
                  JOIN precios pre ON pre.ARTICULOID = i.ARTICULOID
                  JOIN cli ON cli.clienteid = p.clienteID
                 WHERE dp.pedidoID = $pedidoID
                  AND pre.nprecio = $numPrecio
                    AND pre.unidadid = i.unibasid";

    $executeQuery = $paramDb->Query($getOrder);
    try {
      $numRow = $paramDb->NumRows();
      $rows = $paramDb->Rows();
      $buscarFecha = mysqli_query($getConnection,$getOrder);
      $filaFecha = mysqli_fetch_array($buscarFecha);
      $fechaPedido = $filaFecha[8];
      $getConnection->close();

      if($numRow > 0) {
        $total = 0;
        $position = 0;
        // TODO make validation for user: registrado, publico and add column for get price
        $headers = ["#", "Producto", "Código", "Precio", "Cantidad Solicitada", "Cantidad Surtida", "Subtotal"];
        $classPerColumn = ["text-center", "text-left", "text-center", "text-center", "text-center", "text-center","text-right"];

        // NOTE, if want to change order of columns; separate size for each array; i mean declarated different arrays with headers of table
        if($arrayBooleans["bManagementOrder"]) {
          array_push($headers, "Guardar");
          array_push($classPerColumn, "text-center");
        }

        echo "<div>";
        echo "<div class='panel panel-default' style='margin:40px 15px 10px 250px;'>";
        echo "<div class='panel-heading'>";
        echo "<h4>Descripción del Pedido <b>No. ".$pedidoID."</b> | Fecha y Hora del Pedido: <b>".$fechaPedido."</b></h4>";
        echo "</div>";
        echo "<div class='panel-body'>";
        $print = $paramFunctions->drawTableHeader($headers, $classPerColumn);
        $i = 0;
        foreach ($rows as $row) {
          $dbPedidoID = $pedidoID;
          $productoID = $row["productoID"];
          $producto = $row["descripcio"];
          $codigo = $row["clvprov"];
          $precio = $row["precio"];
          $impuestoP = $row["pimpuesto"] / 100;
          $piezas = $row["cantidadSolicitada"];
          $cantidadSolicitada = $row["cantidadSolicitada"];
          $cantidadSurtida = intval($row["cantidadSurtida"]);
          $statusPedido = $row["statusPedido"];
          $i++;
          if($statusPedido == "comprobando") {
            $arrayBooleans["bStatusPedido"] = true;
          }

          // if the existing quantity it's bigger than zero, get and multiply with quantity
          if($cantidadSurtida > 0) {
            $subtotal = $cantidadSurtida * ((double)$precio);
            $arrayBooleans["bPrintButtonConfirmOrder"] = true;
          } else {
            $subtotal = $cantidadSolicitada * ((double)$precio);
            //$subtotal = $subtotal * $piezas;
            $arrayBooleans["bPrintButtonConfirmOrder"] = false;
          }
          $total += $subtotal;
          // set format
          $formatoPrecio = number_format($precio, 2);
          $formatoSubtotal = number_format($subtotal, 2);
          $cliente = $row["nombre"];

          $print .= "<tr>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$i</td>";
            $print .= "<td class='text-left' style='vertical-align:middle; font-weight:bold;'>$producto</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$codigo</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$$formatoPrecio</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$cantidadSolicitada</td>";

            // print input to put quantity of existing product to order  or number
            if($arrayBooleans["bManagementOrder"]) {
              $print .=
                      "<td>
                        <input type='number' name='txtExistingQuantity$productoID' id='txtExistingQuantity$productoID'
                         class='form-control' value='$cantidadSurtida' />
                      </td>";
            } else {
              $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$cantidadSurtida</td>";
            }

            $print .= "<td class='text-right' style='vertical-align:middle; font-weight:bold;'>$$formatoSubtotal</td>";
            // print button and send params to update existing quantity
            if($arrayBooleans["bManagementOrder"]) {
              $params = array("orderID"=>$dbPedidoID,
                              "productID"=>$productoID,
                              "numPrecio"=>$numPrecio,
                              "jQueryID"=>"txtExistingQuantity$productoID",
                              "url"=> "../php/order/order.php",
                              "location" => "updatequantityexisting",
                              "booleanResponse" => true,
                              "msgSuccess" => "La cantidad existente ha sido guardada",
                              "msgError" => "Problemas al guardar cantidad existente");
              $paramsSend = json_encode($params);

              $print .= "<td>
                          <button class='btn btn-secondary' onclick='generalFunctionToRequest($paramsSend)'>
                            Guardar c/surtida
                          </button>
                        </td>";
            }

          $print .= "</tr>";
          $position++;
        }
        $formatoTotal = number_format($total, 2);
        $print .= "</tbody>";
        $print .= "</table>";
        $print .= "<div class='container-fluid'>";
        $print .=   "<div class='row'>";
        $print .=     "<div class='col-md-12' style='text-align: left !important;'>";
        $print .=      "<div class='col-md-1' style='text-align: left !important;'>";
        $print .=         "<p class='' style='vertical-align:middle; font-weight:bold;font-size:1.6em;'>SUBTOTAL </p>";
        $print .=      "</div>";
        $print .=      "<div class='col-md-3' style='text-align: left !important;'>";
        $print .=         "<p class='' style='text-align:right; font-weight:bold;font-size:1.6em;'>$ $formatoTotal</p>";
        $print .=      "</div>";
        $print .=     "</div>";
        $calculoIva = $total * $impuestoP;
        $formatoIva = number_format($calculoIva, 2);
        $print .=     "<div class='col-md-12' style='text-align: left !important;'>";
        $print .=      "<div class='col-md-1' style='text-align: left !important;'>";
        $print .=       "<p class='' style='vertical-align:middle; font-weight:bold;font-size:1.6em;'>I.V.A. </p>";
        $print .=      "</div>";
        $print .=      "<div class='col-md-3' style='text-align: left !important;'>";
        $print .=       "<p class='' style='text-align:right; font-weight:bold;font-size:1.6em;'>$ $formatoIva</p>";
        $print .=      "</div>";
        $print .=     "</div>";
        $totalP = $total + $calculoIva;
        $totalPagar = number_format($totalP, 2);
        $print .=     "<div class='col-md-12' style='text-align: left !important;'>";
        $print .=      "<div class='col-md-1' style='text-align: left !important;'>";
        $print .=       "<p class='' style='vertical-align:middle; font-weight:bold;font-size:1.6em;'>TOTAL </p>";
        $print .=      "</div>";
        $print .=       "<div class='col-md-3' style='text-align: left !important;'>";
        $print .=       "<p class='' style='text-align:right; font-weight:bold;font-size:1.6em;'>$ $totalPagar</p>";
        $print .=      "</div>";
        $print .=     "</div>";
        $print .=   "</div>"; // overflow-x:auto

        // print button and send params to confirm order
        if($arrayBooleans["bPrintButtonConfirmOrder"] == true ||
            $arrayBooleans["bPrintButtonConfirmOrder"] == false &&
            $arrayBooleans["bStatusPedido"] == true) {
          // check if it's user public or registered
          $url = ($arrayBooleans["bManagementOrder"]) ? "../php/order/order.php" : "php/order/order.php";
          // TODO change this comment code, when user select payment method: cash or credit card
          /*$paramsConfirm = array("orderID"=>$dbPedidoID,
                       "url"=> $url, "location" => "confirm-status-order",
                       "status" => "confirmado", "booleanResponse" => true,
                       "msgSuccess" => "La orden se ha confirmado correctamente",
                       "msgError" => "Problemas al confirmarla orden");
          $paramsConfirmSend = json_encode($paramsConfirm);*/
          $paramsConfirm = array("orderID"=>$dbPedidoID,
                      "url"=> $url,
                      "location" => "show-modal-payment-method",
                      "section" => "publicModalPaymentMethod",
                      "divResultID" => "resultModalPaymentMethod",
                      "booleanResponse" => false);
          $paramsConfirmSend = json_encode($paramsConfirm);

          $paramsCancel = array("orderID"=>$dbPedidoID,
                      "url"=> $url, "location" => "confirm-status-order",
                      "status" => "cancelado", "booleanResponse" => true,
                      "msgSuccess" => "La orden se ha confirmado correctamente",
                      "msgError" => "Problemas al confirmarla orden");
          $paramsCancelSend = json_encode($paramsCancel);

        }

        //$print .= "<div class='container-fluid pull-right'>";
        //$print .=   "<div class='row'>";
        //$print .=     "<label class='font-size-1 text-center' style='vertical-align:middle; font-weight:bold; color:green'>Total: $$formatoTotal</label>";
        //$print .=     "</br>";
        // NOTE only print when check the quantity for user administrador or pedidos
        /*if($arrayBooleans["bPrintButtonConfirmOrder"] == true ||
            $arrayBooleans["bPrintButtonConfirmOrder"] == false &&
            $arrayBooleans["bStatusPedido"] == true) {
          $print .=    "<button class='btn btn-success' onclick='generalFunctionToRequest($paramsConfirmSend)'>";
          $print .=       "Confirmar orden";
          $print .=     "</button>";

          $print .=    "<button class='btn btn-danger' onclick='generalFunctionToRequest($paramsCancelSend)'>";
          $print .=       "Cancelar orden";
          $print .=     "</button>";
        }*/

        //$print .=   "</div>";
        //$print .= "</div>";

        echo $print;
      } else {
        $message = "No tienes ningún pedido para visualizar el detalle";
        $paramFunctions->showDivMessage($message);
      }
    } catch (Exception $e) {
      echo "Problema al listar productos: " . $e->getMessage();
    }
  }

  private function showDetail($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $pedidoID = $paramDb->SecureInput($params["pedidoID"]);
    $userLocation = $params["ubicacionUsuario"];

    // TODO Testing and check results are correct, or not; add GROUP AND ORDER BY p.id
    $getOrder = "SELECT d.desartid, i.clave, i.clvprov, i.descripcio, d.descantidad, d.desventa, d.desdescuento, doc.total, doc.subtotal2, doc.impuesto
            FROM des d
              JOIN inv i ON i.articuloid = d.desartid
              JOIN doc ON doc.docid = d.desdocid
            WHERE d.desdocid = $pedidoID";

    $executeQuery = $paramDb->Query($getOrder);
    try {
      $numRow = $paramDb->NumRows();
      $rows = $paramDb->Rows();
      $getConnection->close();

      if($numRow > 0) {
        $total = 0;
        $position = 0;
        // TODO make validation for user: registrado, publico and add column for get price
        $headers = ["#", "ID", "CLAVE", "CODIGO", "DESCRIPCION", "CANTIDAD", "DESCUENTO", "PRECIO", "SUBTOTAL"];
        $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

        echo "<div>";
        echo "<div class='panel panel-default' style='margin:40px 15px 10px 250px;'>";
        echo "<div class='panel-heading'>";
        echo "<h4>Descripción del Pedido No.: <b>".$pedidoID."</b></h4>";
        echo "</div>";
        echo "<div class='panel-body'>";
        $print = $paramFunctions->drawTableHeader($headers, $classPerColumn);
        $i = 0;
        
        foreach ($rows as $row) {
          $dbPedidoID = $pedidoID;
          $productoID = $row["desartid"];
          $clave = $row["clave"];
          $codigo = $row["clvprov"];
          $descripcio = $row["descripcio"];
          $cantidad = number_format($row["descantidad"],0);
          $precio = $row["desventa"];
          $descuento = $row["desdescuento"];
          $subtotal = $row["subtotal2"];
          $iva = $row["impuesto"];
          $total = $row["total"];

          $subT = $row["descantidad"] * $precio;

          $i++;

          // set format
          $formatoPrecio = number_format($precio, 2, '.', ',');
          

          $print .= "<tr>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$i</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$productoID</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$clave</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$codigo</td>";
            $print .= "<td class='text-left' style='vertical-align:middle; font-weight:bold;'>$descripcio</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$cantidad</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>".number_format($descuento,0)." %</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>MX$ $formatoPrecio</td>";
            $print .= "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>MX$ ".number_format($subT, 2, '.', ',')."</td>";

          $print .= "</tr>";
          $position++;
        }

        $formatoTotal = number_format($subtotal, 2, '.', ',');
        $print .= "</tbody>";
        $print .= "</table>";
        $print .= "<div class='container-fluid'>";
        $print .=   "<div class='row'>";
        $print .=     "<div class='col-md-12' style='text-align: left !important;'>";
        $print .=      "<div class='col-md-1' style='text-align: left !important;'>";
        $print .=         "<p class='' style='vertical-align:middle; font-weight:bold;font-size:1.6em;'>SUBTOTAL </p>";
        $print .=      "</div>";
        $print .=      "<div class='col-md-3' style='text-align: left !important;'>";
        $print .=         "<p class='' style='text-align:right; font-weight:bold;font-size:1.6em;'>$ $formatoTotal</p>";
        $print .=      "</div>";
        $print .=     "</div>";

        $formatoIva = number_format($iva, 2, '.', ',');
        $print .=     "<div class='col-md-12' style='text-align: left !important;'>";
        $print .=      "<div class='col-md-1' style='text-align: left !important;'>";
        $print .=       "<p class='' style='vertical-align:middle; font-weight:bold;font-size:1.6em;'>I.V.A. </p>";
        $print .=      "</div>";
        $print .=      "<div class='col-md-3' style='text-align: left !important;'>";
        $print .=       "<p class='' style='text-align:right; font-weight:bold;font-size:1.6em;'>$ $formatoIva</p>";
        $print .=      "</div>";
        $print .=     "</div>";

        $totalFinal = number_format($total, 2, '.', ',');
        $print .=     "<div class='col-md-12' style='text-align: left !important;'>";
        $print .=      "<div class='col-md-1' style='text-align: left !important;'>";
        $print .=       "<p class='' style='vertical-align:middle; font-weight:bold;font-size:1.6em;'>TOTAL </p>";
        $print .=      "</div>";
        $print .=       "<div class='col-md-3' style='text-align: left !important;'>";
        $print .=       "<p class='' style='text-align:right; font-weight:bold;font-size:1.6em;'>$ $totalFinal</p>";
        $print .=      "</div>";
        $print .=     "</div>";
        $print .=   "</div>"; // overflow-x:auto

        echo $print;
      } else {
        $message = "No tienes ningún pedido para visualizar el detalle";
        $paramFunctions->showDivMessage($message);
      }
    } catch (Exception $e) {
      echo "Problema al listar productos: " . $e->getMessage();
    }
  }

  private function updateQuantity($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $pedidoID = $paramDb->SecureInput($params["pedidoID"]);
    $productoID = $paramDb->SecureInput($params["productoID"]);
    $cantidad = $paramDb->SecureInput($params["cantidad"]);
    $numPrecio = $paramDb->SecureInput($params["numPrecio"]);
    $tipoCantidad = $paramDb->SecureInput($params["tipoCantidad"]);

    // TODO validation kind of quantity: cantidadSolicitada or cantidadSurtida, and update field
    $updateQuantity = "UPDATE descripcionPedido SET cantidadSurtida = $cantidad ";
    $updateQuantity .= "WHERE pedidoID = $pedidoID AND productoID = $productoID";
    $executeQuery = $paramDb->UpdateDb($updateQuantity);

    if($executeQuery) {
      $updateStatus = "UPDATE pedidos SET status = 'comprobando' WHERE id = $pedidoID";
      $executeQuery = $paramDb->UpdateDb($updateStatus);
      $getConnection->close();

      if($executeQuery) {
        echo "true";
      } else {
        echo "false";
      }
    } else {
      // TODO register error on file logs or table
      echo "false";
    }

  }

  private function updateStatus($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $pedidoID = $paramDb->SecureInput($params["pedidoID"]);
    $toStatus = $paramDb->SecureInput($params["toStatus"]);

    $updateStatus = "UPDATE pedidos SET status = '$toStatus' WHERE id = $pedidoID";
    $executeQuery = $paramDb->UpdateDb($updateStatus);

    if($executeQuery) {
      echo "true";
    } else {
      echo "false";
    }
  }

} // end class
?>
