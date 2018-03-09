<?php
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
  session_start();
  require_once("../classes/class.shopping.php");
  require_once("../functions/util.php");
  $shopping = new Shopping();
  $paramFunctions = new Util();

  // variable send by ajax, and get here to be processed
  if(isset($_POST["data"])) {
    $data = $_POST["data"];
    $location = $_POST["location"];
    // echo $data;
    // echo "value of localStorage: " . gettype($data);
    switch($location) {
      case 'addProduct-to-shoppingcart-partner':
        $productID = $paramFunctions->sanitize($_POST["productID"]);
        $shopping->getterAddProductToShoppingCartPartner($productID);
        break;
      case 'getDashBoard':
        //TODO enlace para abrir el dashboard
        $shopping->getterGetDashBoardPartner();
        break;
      case "deleteproductshoppingcarpartner":
        $productID = $paramFunctions->sanitize($_POST["productID"]);
        $shopping->getterDeleteProductShoppingCarPartner($productID);
        break;
      case "deleteshoppingcarpartner":
        $shopping->getterDeleteShoppingCarPartner();
        break;
      case "find-toAdd-product-to-shoppingcart-partner":
        $findProductBy = $_POST["findProductBy"];
        $findProductBy = $paramFunctions->sanitize($findProductBy);
        $findProductBy = $paramFunctions->specialChars($findProductBy);

        $shopping->getterFindProduct($findProductBy);
        break;
      case "getshoppingpublic":
        // user public want to buy some products, that user doesn't logged
        $shopping->getterGetShoppingCarPublic($data);
        break;
      case "getshoppingpartner":
        // role: constructor, socio, user has been logged
        $shopping->getterGetShoppingCarPartner();
        break;
      case "saveAllQuantity-shoppingCartPartner":
        $array = $_POST["arrayContentUpdateQuantities"];
        $params = array("updateQuantities" => $array);
        $shopping->getterSaveAllQuantityShoppinCarPartner($params);
        break;
      case "savequantityshoppingcarpartner":
        $productID = $paramFunctions->sanitize($_POST["productID"]);
        $quantity = $paramFunctions->sanitize($_POST["quantity"]);

        $params = array("productID" => $productID,
                        "quantity" => $quantity);
        $shopping->getterSaveQuantityShoppingCarPartner($params);
        break;
      case "saveorderpartner":
        $shopping->getterSaveOrderPartner();
        break;
    }

  } else {
    header("Location: ../../index.php");
  }
} else {
  header("Location: ../../index.php");
}

?>
