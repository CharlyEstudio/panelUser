<?php
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
  session_start();
  require_once("../classes/class.report.php");
  require_once("../functions/util.php");
  $report = new Report();
  $paramFunctions = new Util();

  // variable send by ajax, and get here to be processed
  if(isset($_POST["data"])) {
    $data = $_POST["data"];
    $location = $_POST["location"];

    switch($location) {
      case"getDashBoardAdmin":
        $session = $_SESSION["data"];
        $report->getterDashBoardAdmin($session);
        break;
      case"getDashBoardComp":
        $session = $_SESSION["data"];
        $report->getterDashBoardComp($session);
        break;
      case"getResumenInvTub":
        $session = $_SESSION["data"];
        $report->getterResumenInvTub($session);
        break;
      case"getDashBoardMesaQro":
        $session = $_SESSION["data"];
        $report->getterDashBoardMesaQro($session);
        break;
      case"getDashBoardSz":
        $session = $_SESSION["data"];
        $report->getterDashBoardSz($session);
        break;
      case"getDashBoardCartera":
        $session = $_SESSION["data"];
        $report->getterDashBoardCartera($session);
        break;
      case"getDashBoardDirIndex":
        $session = $_SESSION["data"];
        $report->getterDashBoardDirIndex($session);
        break;
      case"getDashBoardAlamcen":
        $session = $_SESSION["data"];
        $report->getterDashBoardAlamcen($session);
        break;
      case"getDashBoardMesa":
        $session = $_SESSION["data"];
        $report->getterDashBoardMesa($session);
        break;
      case"getOutPipes":
        $session = $_SESSION["data"];
        $report->getterOutPipes($session);
        break;
      case"getDashBoardDireccion":
        $session = $_SESSION["data"];
        $report->getterDashBoardDireccion($session);
        break;
      case"getReportService":
        $session = $_SESSION["data"];
        $report->getterReportService($session);
        break;
      case"getBackOrder":
        $session = $_SESSION["data"];
        $report->getterBackOrder($session);
        break;
      case"showBackOrderActual":
        $session = $_SESSION["data"];
        $report->getterShowBackOrderActual($session);
        break;
      case"showBackOrderReng":
        $session = $_SESSION["data"];
        $proveedor = $paramFunctions->sanitize($_POST["proveedor"]);
        $params = array("session"=> $session,
                        "proveedor" => $proveedor);
        $report->getterShowBackOrderReng($params);
        break;
      case"getEnlaceZona1":
        $session = $_SESSION["data"];
        $report->getterEnlaceZona1($session);
        break;
      case"getEnlaceZona2":
        $session = $_SESSION["data"];
        $report->getterEnlaceZona2($session);
        break;
      case"getNuevosClientes":
        $session = $_SESSION["data"];
        $report->getterNuevosClientes($session);
        break;
      case"getreport":
        $session = $_SESSION["data"];
        $report->getterGetReport($session);
        break;
      case"getReporteVendedor":
        $perID = $paramFunctions->sanitize($_POST["perID"]);
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "perID" => $perID);
        $report->getterGetReporteVendedor($params);
        break;
      case"getDashBoardAsesor":
        $session = $_SESSION["data"];
        $params = array("session"=> $session);
        $report->getterGetDashBoardAsesor($params);
        break;
      case"getPedidosPorHora":
        $session = $_SESSION["data"];
        $params = array("session"=> $session);
        $report->getterGetPedidosPorHora($params);
        break;
      case"getReporteVendedorSession":
        $perID = $_SESSION["data"]["id"];
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "perID" => $perID);
        $report->getterGetReporteVendedor($params);
        break;
      case"getReportePedidosDia":
        $pedidosDia = $paramFunctions->sanitize($_POST["tipoPedido"]);
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "tipoPedido" => $pedidosDia);
        $report->getterGetReportePedidosDia($params);
        break;
      case"getClientesNuevosMes":
        $session = $_SESSION["data"];
        $params = array("session"=> $session);
        $report->getterGetClientesNuevosMes($params);
        break;
      case"getAddInv":
        $session = $_SESSION["data"];
        $params = array("session"=> $session);
        $report->getterGetAddInv($params);
        break;
      case"getModInv":
        $codigo = $paramFunctions->sanitize($_POST["codigo"]);
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "codigo" => $codigo);
        $report->getterGetModInv($params);
        break;
      case"getNewCustomer":
        $perid = $paramFunctions->sanitize($_POST["perid"]);
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "perid" => $perid);
        $report->getterGetNewCustomer($params);
        break;
      case"getModCustomer":
        $perid = $paramFunctions->sanitize($_POST["perid"]);
        $clienteid = $paramFunctions->sanitize($_POST["clienteid"]);
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "perid" => $perid,
                        "clienteid" => $clienteid);
        $report->getterGetNewCustomer($params);
        break;
      case"getNewCustomerVen":
        $perid = $_SESSION["data"]["id"];
        $session = $_SESSION["data"];
        $params = array("session"=> $session,
                        "perid" => $perid);
        $report->getterGetNewCustomer($params);
        break;
      case "showdetailMor":
        $perid = $paramFunctions->sanitize($_POST["perid"]);
        $tiempoMor = $paramFunctions->sanitize($_POST["tiempoMor"]);
        $session = $_SESSION["data"];
        $paramsMor = array("session"=> $session,
                            "perid"=> $perid,
                            "tiempoMor" => $tiempoMor);
        $report->getterGetShowDetailMor($paramsMor);
        break;
    }

  } else {
    header("Location: ../../index.php");
  }
} else {
  header("Location: ../../index.php");
}
?>
