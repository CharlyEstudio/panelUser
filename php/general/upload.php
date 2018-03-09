<?php
// comprobamos que sea una petición ajax
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
    require_once("../class.database.php");
    $paramDb = new Database();

    $params = json_decode($_POST["params"]);
    $typeFileUpload = $params->location;
    $directory = $params->directory;

    // obtenemos el archivo a subir
    switch($typeFileUpload) {
      case "uploadCsv":
        $fileName = $_FILES["csvFile"]["name"];
        $type = $_FILES["csvFile"];
        $table = $_POST["tableUploadCsv"];
        break;
      case "uploadPdf":
        $fileName = $_FILES["pdfFile"]["name"];
        $type = $_FILES["pdfFile"];
        break;
      case "uploadJpg":
        $fileName = $_FILES["jpgFile"]["name"];
        $type = $_FILES["jpgFile"];
        break;
    }
    // comprobamos si existe un directorio para subir el archivo
    // si no es así, lo creamos
    if(!is_dir($directory)) {
        mkdir($directory, 0777);
    }

    // comprobamos si el archivo ha subido
    if ($fileName && move_uploaded_file($type["tmp_name"], $directory.$fileName)) {
      $dirFile = $directory.$fileName;
      $statusUpload = false;

      switch ($typeFileUpload) {
        case 'uploadCsv':
          $realPath = realpath($dirFile);
          $sql = "LOAD DATA INFILE '$realPath' INTO TABLE $table ";
          $sql .= "FIELDS TERMINATED BY '\\t' ";
          $sql .= "OPTIONALLY ENCLOSED BY '\"' ";
          $sql .= "ESCAPED BY '\"' ";
          $sql .= "LINES TERMINATED BY '\\n'";
          $executeQuery = $paramDb->UpdateDb($sql);
          if($executeQuery) {
            $statusUpload = true;
            unlink($dirFile); // delete file after insert
          }
          break;
        default:
          $statusUpload = true;
          break;
      }


      if($statusUpload) {
        echo "upload";
      } else {
        "error";
      }
    } else {
      echo "error";
    }
} else {
    // throw new Exception("Error Processing Request", 1);
    header("Location: ../../index.php");
}

?>
