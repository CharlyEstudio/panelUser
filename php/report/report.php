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
      case"getDashBoardSz":
        $session = $_SESSION["data"];
        $report->getterDashBoardSz($session);
        break;
      case"getDashBoardDirIndex":
        $session = $_SESSION["data"];
        $report->getterDashBoardDirIndex($session);
        break;
      case"getDashBoardDireccion":
        $session = $_SESSION["data"];
        $report->getterDashBoardDireccion($session);
        break;
      case"getEnlaceZona1":
        $session = $_SESSION["data"];
        $report->getterEnlaceZona1($session);
        break;
      case"getEnlaceZona2":
        $session = $_SESSION["data"];
        $report->getterEnlaceZona2($session);
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
