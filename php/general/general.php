<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  session_start();
  require_once("../functions/dml.php");
  $dml = new Dml();

  // variable send by ajax, and get here to be processed
  if(isset($_POST["data"])) {
    $data = $_POST["data"];
    $location = $_POST["location"];

    switch($location) {
      case "get-inferface-to-update":
        $dml->updateTables();
        break;
      case "identify-home":
        $session = $_SESSION["data"];
        $rol = $session["rol"];
        $section = "";

        /*if($rol == "administrador") {
          $section = "report";*/
        if($rol == "administrador") {
          $section = "dashBoardAdmin";
        } elseif($rol == "SZ-01" || $rol == "SZ-02") {
          $section = "dashBoardSz";
        } elseif($rol == "direccion"){
          $section = "dashBoardDirIndex";
        } elseif($rol == "compras"){
          $section = "dashBoardComp";
        } elseif($rol == "cartera"){
          $section = "dashBoardCartera";
        } elseif($rol == "SUBDISTRIBUIDOR" || $rol == "DISTRIBUIDOR" || $rol == "MAYOREO") {
          $section = "dashboard";
        } elseif($rol == 'pedidos'){
          $section = "order";
        } elseif($rol == "ventas") {
          $section = "sale";
        } elseif($rol == "VENDEDORES") {
          $section = "dashBoardAsesor";
        } elseif($rol == "almacen") {
          $section = "dashBoardAlmacen";
        } elseif($rol == "mesaQro") {
          $section = "dashBoardMesaQro";
        } elseif($rol == "mesaTx") {
          $section = "dashBoardMesaQro";
        } elseif($rol == "mesa") {
          $section = "dashBoardMesa";
        }
        echo $section;
        break;
      // NOTE it doesn't work, by file upload.php
      case "uploadCsv":
        $file = $_FILES["csvFile"];
        $table = $_POST["tableUploadCsv"];
        $params = array("file" => $file, "table" => $table);
        $dml->uploadFile($params);
        break;
    }

  } else {
    header("Location: ../../index.php");
  }
} else {
  header("Location: ../../index.php");
}
?>
