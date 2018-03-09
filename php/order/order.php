<?php
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
  session_start();
  require_once("../classes/class.order.php");
  require_once("../functions/dml.php");
  require_once("../functions/util.php");
  $order = new Order();
  $dml = new Dml();
  $paramFunctions = new Util();

  // variable send by ajax, and get here to be processed
  if(isset($_POST["data"])) {
    $data = $_POST["data"];
    $location = $_POST["location"];
    // confirm-status-order
    switch($location) {
      case "confirm-status-order":
        $orderID = $paramFunctions->sanitize($_POST["orderID"]);
        $status = $paramFunctions->sanitize($_POST["status"]);

        $params = array("orderID"=> $orderID,
                        "status" => $status);
        $order->getterConfirmStatusOrder($params);
        break;
      case "edocta":
        // send params to identify what kind of user has been logged and show data
        // check if user has been logged get data from session
        if(isset($_SESSION["data"])) {
          $session = $_SESSION["data"];
          $section = $_POST["section"]; // get section of page that request to draw table of order
          // pagination params, because scope of variables doesn't exist in function
          if(isset($_POST["currentPage"])) {
            $currentPage = $_POST["currentPage"];
            $currentPage = $paramFunctions->sanitize($currentPage);

            $limitPages = $_POST["limitPages"];
            $limitPages = $paramFunctions->sanitize($limitPages);

            $paramsUser = array("currentPage" => $currentPage,
                                "limitPages" => $limitPages);
          } else {
            $paramsUser = 'empty'; // send whatever, because i validate subnode, like: ["parametersRequest"]["currentPage"]
          }

          $params = array("rol" => $session["rol"],
                          "username" => $session["username"],
                          "section" => $section,
                          "parametersRequest" => $paramsUser);
          $order->getterGetEdocta($params);
        }
        break;
      case "facturas":
        // send params to identify what kind of user has been logged and show data
        // check if user has been logged get data from session
        if(isset($_SESSION["data"])) {
          $session = $_SESSION["data"];
          $section = $_POST["section"]; // get section of page that request to draw table of order
          // pagination params, because scope of variables doesn't exist in function
          if(isset($_POST["currentPage"])) {
            $currentPage = $_POST["currentPage"];
            $currentPage = $paramFunctions->sanitize($currentPage);

            $limitPages = $_POST["limitPages"];
            $limitPages = $paramFunctions->sanitize($limitPages);

            $paramsUser = array("currentPage" => $currentPage,
                                "limitPages" => $limitPages);
          } else {
            $paramsUser = 'empty'; // send whatever, because i validate subnode, like: ["parametersRequest"]["currentPage"]
          }

          $params = array("rol" => $session["rol"],
                          "username" => $session["username"],
                          "section" => $section,
                          "parametersRequest" => $paramsUser);
          $order->getterGetFacturas($params);
        }
        break;
      case "getorders":
        // send params to identify what kind of user has been logged and show data
        // check if user has been logged get data from session
        if(isset($_SESSION["data"])) {
          $session = $_SESSION["data"];
          $section = $_POST["section"]; // get section of page that request to draw table of order
          // pagination params, because scope of variables doesn't exist in function
          if(isset($_POST["currentPage"])) {
            $currentPage = $_POST["currentPage"];
            $currentPage = $paramFunctions->sanitize($currentPage);

            $limitPages = $_POST["limitPages"];
            $limitPages = $paramFunctions->sanitize($limitPages);

            $paramsUser = array("currentPage" => $currentPage,
                                "limitPages" => $limitPages);
          } else {
            $paramsUser = 'empty'; // send whatever, because i validate subnode, like: ["parametersRequest"]["currentPage"]
          }

          $params = array("rol" => $session["rol"],
                          "username" => $session["username"],
                          "section" => $section,
                          "parametersRequest" => $paramsUser);
          $order->getterGetOrders($params);
        }
        break;
      case "getorders2":
        // send params to identify what kind of user has been logged and show data
        // check if user has been logged get data from session
        if(isset($_SESSION["data"])) {
          $session = $_SESSION["data"];
          $section = $_POST["section"]; // get section of page that request to draw table of order
          // pagination params, because scope of variables doesn't exist in function
          if(isset($_POST["currentPage"])) {
            $currentPage = $_POST["currentPage"];
            $currentPage = $paramFunctions->sanitize($currentPage);

            $limitPages = $_POST["limitPages"];
            $limitPages = $paramFunctions->sanitize($limitPages);

            $paramsUser = array("currentPage" => $currentPage,
                                "limitPages" => $limitPages);
          } else {
            $paramsUser = 'empty'; // send whatever, because i validate subnode, like: ["parametersRequest"]["currentPage"]
          }

          $params = array("rol" => $session["rol"],
                          "username" => $session["username"],
                          "section" => $section,
                          "parametersRequest" => $paramsUser);
          $order->getterGetOrders2($params);
        }
        break;
      case 'getpublicorders':
        // TODO i need to get id of client, because at this point always the same user save
        // get order code, rol=publico and clientID=1 and send it to function
        $orderCode = $_POST["orderCode"];
        $orderCode = $paramFunctions->sanitize($orderCode);

        $params = array("rol"=> "mayorista",
                        "clienteID" => 1,
                        "orderCode" => $orderCode);
        $order->getterGetPublicOrders($params);        
        break;
      case "notcred":
        // send params to identify what kind of user has been logged and show data
        // check if user has been logged get data from session
        if(isset($_SESSION["data"])) {
          $session = $_SESSION["data"];
          $section = $_POST["section"]; // get section of page that request to draw table of order
          // pagination params, because scope of variables doesn't exist in function
          if(isset($_POST["currentPage"])) {
            $currentPage = $_POST["currentPage"];
            $currentPage = $paramFunctions->sanitize($currentPage);

            $limitPages = $_POST["limitPages"];
            $limitPages = $paramFunctions->sanitize($limitPages);

            $paramsUser = array("currentPage" => $currentPage,
                                "limitPages" => $limitPages);
          } else {
            $paramsUser = 'empty'; // send whatever, because i validate subnode, like: ["parametersRequest"]["currentPage"]
          }

          $params = array("rol" => $session["rol"],
                          "username" => $session["username"],
                          "section" => $section,
                          "parametersRequest" => $paramsUser);
          $order->getterGetNotcred($params);
        }
        break;
      case 'publicordercode':
        $order->getterPublicOrderCode();  // show input for put order code
        break;
      case "saveorder":
        // NOTE save for clienteID=1 because all workflow of shopping car partner it's on session
        $email = $_POST["email"];
        $email = $paramFunctions->sanitize($email);
        $email = $paramFunctions->specialChars($email);
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        $postal = $_POST["postal"];
        $rfc = $_POST["rfc"];
        $celular = $_POST["celular"];
        $correo = $_POST["email"];

        $order->getterSaveOrder($data, $email, $nombre, $direccion, $postal, $rfc, $celular);
        break;
      case "showdetailorder":
        $pedidoID = $_POST["pedidoID"];
        $pedidoID = $paramFunctions->sanitize($pedidoID);

        $numPrecio = $_POST["numPrecio"];
        $numPrecio = $paramFunctions->sanitize($numPrecio);

        $userLocation = $_POST["userLocation"];
        $userLocation = $paramFunctions->sanitize($userLocation);

        $params = array("pedidoID"=>$pedidoID,
                        "numPrecio"=>$numPrecio,
                        "ubicacionUsuario"=>$userLocation);
        $order->getterShowDetailOrder($params);
        break;
      case "showdetail":
        $pedidoID = $_POST["pedidoID"];
        $pedidoID = $paramFunctions->sanitize($pedidoID);

        $userLocation = $_POST["userLocation"];
        $userLocation = $paramFunctions->sanitize($userLocation);

        $params = array("pedidoID"=>$pedidoID,
                        "ubicacionUsuario"=>$userLocation);
        $order->getterShowDetail($params);
        break;
      case "show-modal-payment-method":
        $orderID = $paramFunctions->sanitize($_POST["orderID"]);
        $section = $paramFunctions->sanitize($_POST["section"]);
        $params = array("orderID" => $orderID,
                        "section" => $section);
        $dml->drawModalPaymentMethod($params);
        break;
      case "updateorderstatus":
        $pedidoID = $paramFunctions->sanitize($_POST["orderID"]);
        $toStatus = $paramFunctions->sanitize($_POST["orderStatus"]);

        $params = array("pedidoID"=>$pedidoID,
                        "toStatus"=>$toStatus);
        $order->getterUpdateStatus($params);
        break;
      case "updatequantityexisting":
        $pedidoID = $paramFunctions->sanitize($_POST["orderID"]);
        $productoID = $paramFunctions->sanitize($_POST["productID"]);
        $numPrecio = $paramFunctions->sanitize($_POST["numPrecio"]);
        $cantidadExistente = $paramFunctions->sanitize($_POST["existingQuantity"]);

        $params = array("pedidoID"=>$pedidoID,
                        "productoID"=>$productoID,
                        "cantidad"=>$cantidadExistente,
                        "numPrecio"=>$numPrecio,
                        "tipoCantidad"=>"surtida");
        $order->getterUpdateQuantity($params);
        break;
    }
  } else {
    header("Location: ../../index.php");
  }
} else {
  header("Location: ../../index.php");
}
?>
