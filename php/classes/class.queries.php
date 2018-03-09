<?php
require_once("../class.database.php");

class Queries {
  public function getterExecuteGeneralQuery($params) {
    return $this->executeGeneralQuery($params);
  }

  public function getterGetAllValuesByOnlyColumn($params) {
    return $this->getAllValuesByOnlyColumn($params);
  }

  public function getterGetSpecificValue($params) {
    return $this->getSpecificValue($params);
  }

  private function executeGeneralQuery($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $getquery = $params["query"];

    $executeQuery = $paramDb->Query($getquery);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    $getConnection->close();
    return $rows;
  }

  private function getAllValuesByOnlyColumn($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $getquery = $params["query"];
    $column = $params["column"];
    $result = [];

    $executeQuery = $paramDb->Query($getquery);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    $getConnection->close();

    foreach ($rows as $row) {
      $getColumn = $row[$column];
      array_push($result, $getColumn);
    }
    return $result;
  }

  // NOTE return $rows[0], because always the query it have a conditional: WHERE
  private function getSpecificValue($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $getquery = $params["query"];
    $column = $params["column"];

    $executeQuery = $paramDb->Query($getquery);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    $getConnection->close();

    return $rows[0][$column];
  }


}
?>
