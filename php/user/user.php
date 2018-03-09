<?php
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
  session_start();
  require_once("../classes/class.user.php");
  require_once("../functions/util.php");
  $user = new User();
  $paramFunctions = new Util();

  // variable send by ajax, and get here to be processed
  if(isset($_POST["data"])) {
    $location = $_POST["location"];
    switch($location) {
      case "find-userBy-crud":
        $userBy = $_POST["findUserBy"];
        $userBy = $paramFunctions->sanitize($userBy);
        $userBy = $paramFunctions->specialChars($userBy);

        $params = array("findCrudUserBy" => $userBy);
        $user->getterFindCrudUserBy($params);
        break;
      case "get-users-crud":
        $user->getterGetUsersCrud();
        break;
      case "getUser-to-update-crud":
        $userID = $_POST["userID"];
        $userID = $paramFunctions->sanitize($userID);

        $params = array("usuarioID" => $userID);
        $user->getterGetUserToUpdateCrud($params);
        break;
      case "newUser-crud":
        $user->getterNewUserCrud();
        break;
      case "saveNewUser-crud":
        $registrosFormulario = $_POST["dataForm"];
        $params = array("registrosFormulario"=>$registrosFormulario);
        $user->getterSaveNewUserCrud($params);
        break;
      case "setUser-to-update-crud":
        $userID = $_POST["elementID"];
        $userID = $paramFunctions->sanitize($userID);

        $registrosFormulario = $_POST["dataForm"];
        $registrosDB = $_POST["dataDB"];
        $params = array("usuarioID" => $userID,
                        "registrosFormulario" => $registrosFormulario,
                        "registrosDB" => $registrosDB);
        $user->getterSetUserToupdateCrud($params);
        break;
    }
  } else {
    header("Location: ../../index.php");
  }

} else {
  header("Location: ../../index.php");
}
?>
