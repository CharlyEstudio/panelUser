<?php
if(!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
  session_start();
  require_once("../classes/class.product.php");
  require_once("../functions/dml.php");
  require_once("../functions/util.php");
  $product = new Product();
  $dml = new Dml();
  $paramFunctions = new Util();

  // variable send by ajax, and get here to be processed
  if(isset($_POST["data"])) {
    $location = $_POST["location"];
    switch($location) {
      case "findPublicProduct":
        $findPublicProductBy = $_POST["findPublicProductBy"];
        $findPublicProductBy = $paramFunctions->sanitize($findPublicProductBy);
        $findPublicProductBy = $paramFunctions->specialChars($findPublicProductBy);

        $params = array("findPublicProductBy" => $findPublicProductBy);
        $product->getterFindPublicProductBy($params);
        break;
      case "clickProductCategory":
        $clickProductCategoryBy = $_POST["clickProductCategoryBy"];
        $clickProductCategoryBy = $paramFunctions->sanitize($clickProductCategoryBy);
        $clickProductCategoryBy = $paramFunctions->specialChars($clickProductCategoryBy);

        $params = array("clickProductCategoryBy" => $clickProductCategoryBy);
        $product->getterClickProductCategoryBy($params);
        break;
      case "clickProductMarca":
        $clickProductMarcaBy = $_POST["clickProductMarcaBy"];
        $clickProductMarcaBy = $paramFunctions->sanitize($clickProductMarcaBy);
        $clickProductMarcaBy = $paramFunctions->specialChars($clickProductMarcaBy);

        $params = array("clickProductMarcaBy" => $clickProductMarcaBy);
        $product->getterClickProductMarcaBy($params);
        break;
      case "clickProductInterest":
        $clickProductInterestBy = $_POST["clickProductInterestBy"];
        $clickProductInterestBy = $paramFunctions->sanitize($clickProductInterestBy);
        $clickProductInterestBy = $paramFunctions->specialChars($clickProductInterestBy);

        $params = array("clickProductInterestBy" => $clickProductInterestBy);
        $product->getterClickProductInterestBy($params);
        break;
      case "find-productBy-crud":
        $productBy = $_POST["findProductBy"];
        $productBy = $paramFunctions->sanitize($productBy);
        $productBy = $paramFunctions->specialChars($productBy);

        $params = array("findCrudProductBy" => $productBy);
        $product->getterFindCrudProductBy($params);
        break;
      case "getproducts":
        // because scope of variables doesn't exist in function
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
        $params["parametersRequest"] = $paramsUser;
        $product->getterGetProducts($params);
        break;
      case "get-products-crud":
        $product->getterGetProductsCrud();
        break;
      case "getProduct-to-update-crud":
        $productID = $_POST["productID"];
        $productID = $paramFunctions->sanitize($productID);

        $params = array("productoID" => $productID);
        $product->getterGetProductToUpdateCrud($params);
        break;
      case "newProduct-crud":
        $product->getterNewProductCrud();
        break;
      case "saveNewProduct-crud":
        $registrosFormulario = $_POST["dataForm"];
        $params = array("registrosFormulario"=>$registrosFormulario);
        $product->getterSaveNewProductCrud($params);
        break;
      case "showModalFindPublicProduct":
      case "showModalPublicProduct":
      case "showModalProductRegisteredUser":
        $productID = $paramFunctions->sanitize($_POST["productID"]);
        $alta = $paramFunctions->sanitize($_POST["alta"]);
        $section = $paramFunctions->sanitize($_POST["section"]);
        $params = array("productoID" => $productID,
                        "alta" => $alta,
                        "section" => $section);
        $dml->drawModalProduct($params);
        break;
      case "setProduct-to-update-crud":
        $productID = $paramFunctions->sanitize($_POST["elementID"]);
        $registrosFormulario = $_POST["dataForm"];
        $registrosDB = $_POST["dataDB"];
        $params = array("productoID" => $productID,
                        "registrosFormulario" => $registrosFormulario,
                        "registrosDB" => $registrosDB);
        $product->getterSetProductToupdateCrud($params);
        break;
    }

  } else {
    header("Location: ../../index.php");
  }

} else {
  header("Location: ../../index.php");
 }
?>
