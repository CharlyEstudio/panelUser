<?php
require_once("../class.database.php");
require_once("../functions/util.php");

class Shopping {

  public function getterAddProductToShoppingCartPartner($productID) {
	 $this->addProductToShoppingCartPartner($productID);
  }

  public function getterDeleteProductShoppingCarPartner($productID) {
	 $this->deleteProductShoppingCarPartner($productID);
  }

  public function getterDeleteShoppingCarPartner() {
	 $this->deleteShoppinCarPartner();
  }
  // update content for shoppingCar partner, section div id=content-shoppingCar-partner
  // only that section, and not section where input is and where appears it history
  public function getterFindProduct($findProductBy) {
	 $this->findProduct($findProductBy);
  }

  public function getterGetShoppingCarPublic($data) {
	 $this->getShoppingCarPublic($data);
  }

  public function getterGetShoppingCarPartner() {
	 $this->getShoppingCarPartner();
  }

  public function getterGetDashBoardPartner() {
	 $this->getDashBoardPartner();
  }

  public function getterSaveOrderPartner() {
	 $this->saveOrderPartner();
  }

  public function getterSaveAllQuantityShoppinCarPartner($params) {
	 $this->saveAllQuantityShoppinCarPartner($params);
  }

  public function getterSaveQuantityShoppingCarPartner($params) {
	 $this->saveQuantityShoppingCarPartner($params);
  }

  //**** PRIVATE FUNCTIONS ****//

	private function addProductToShoppingCartPartner($paramProductID) {
		$paramDb = new Database();
		$paramFunctions = new Util();
		$getConnection = $paramDb->GetLink();

		$paramProductID = $paramDb->SecureInput($paramProductID);
		$rol = $_SESSION["data"]["rol"];

		$unidadID = "SELECT i.unibasid
							FROM inv i
								JOIN precios pre ON pre.unidadid = i.unibasid
							WHERE i.clvprov = $paramProductID
							LIMIT 0,1";
		$buscarUnidadID = mysqli_query($getConnection,$unidadID);
		$encontrarUnidadID = mysqli_fetch_array($buscarUnidadID);
		$unidadbasid = $encontrarUnidadID["unibasid"];

		if($rol == 'DISTRIBUIDOR') {
			$getproduct = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, i.invdescuento, pre.precio, pre.nprecio
								FROM inv i
									JOIN precios pre ON pre.articuloid =i.articuloid
								WHERE i.clvprov = $paramProductID AND pre.nprecio = 1 AND pre.unidadid = $unidadbasid";
		} else if($rol == 'SUBDISTRIBUIDOR') {
			$getproduct = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, i.invdescuento, pre.precio, pre.nprecio
								FROM inv i
									JOIN precios pre ON pre.articuloid =i.articuloid
								WHERE i.clvprov = $paramProductID AND pre.nprecio = 2 AND pre.unidadid = $unidadbasid";
		} else if($rol == 'MAYOREO') {
			$getproduct = "SELECT i.articuloid, i.clave, i.descripcio, pre.precio
								FROM inv i
									JOIN precios pre ON pre.articuloid =i.articuloid
								WHERE i.clvprov = $paramProductID AND pre.nprecio = 3 AND pre.unidadid = $unidadbasid";
		}
		$executeQuery = $paramDb->Query($getproduct);

		try {
			$numRow = $paramDb->NumRows();
			$rows = $paramDb->Rows();

			if($numRow > 0) {
				foreach ($rows as $row) {
					$productID = $row["articuloid"];
					$productCode = $row["clave"];
					$product = $row["descripcio"];
					$price = $row["precio"];
					$codigo = $row["clvprov"];

					$getImg = "SELECT imagen
						  FROM imagenes
						  WHERE codigo = $codigo";
					$resImg = mysqli_query($getConnection,$getImg);
					$filaImg = mysqli_fetch_array($resImg);
					$imagen = $filaImg[0];

					// add elements to array
					$product = array(
						"productID" => $productID,
						"productCode" => $productCode,
						"product" => $product,
						"price" => $price,
						"imagen" => $imagen,
						"quantity" => 1
					);
					if(!isset($_SESSION["shoppingCarPartner"])) {
						$_SESSION["shoppingCarPartner"] = [];
						array_push($_SESSION["shoppingCarPartner"], $product);
					} else {
						// check if exist element on array, if exist don't push
						$existElement = $paramFunctions->findElementOnArray($_SESSION["shoppingCarPartner"], $productCode);
						if(!$existElement) {
							array_push($_SESSION["shoppingCarPartner"], $product);
						}
					}
					echo $this->getTableShoppingCarPartner();
					// call function
				}
			} else {
				$message = "No se encontró producto con código : $paramProductID";
				$paramFunctions->showDivMessage($message);
			}
		} catch (Exception $e) {
			echo "Problema al listar pedidos: " . $e->getMessage();
		}
		$getConnection->close();
	}

  private function deleteProductShoppingCarPartner($productID) {
	 $paramFunctions = new Util();
	 $array = $_SESSION["shoppingCarPartner"];
	 $_SESSION["shoppingCarPartner"] = [];

	 // NOTE unset element inside function; return array to loop and push session
	 $resultArray = $paramFunctions->deleteElementArray($array, $productID);
	 foreach ($resultArray as $array) {
		array_push($_SESSION["shoppingCarPartner"], $array);
	 }
  }

  private function deleteShoppinCarPartner() {
	 unset($_SESSION["shoppingCarPartner"]);
  }

  // TODO buscar en carrito
  private function findProduct($findProductBy) {
	 $paramDb = new Database();
	 $paramFunctions = new Util();
	 $getConnection = $paramDb->GetLink();
	 $findProductBy = $paramDb->SecureInput($findProductBy);
	 $rol = $_SESSION["data"]["rol"];

	 if($rol == "DISTRIBUIDOR"){
	 	$lista = 1;
	 } else if($rol == "SUBDISTRIBUIDOR"){
	 	$lista = 2;
	 } else if($rol == "MAYOREO"){
	 	$lista = 3;
	 }

	 $getproducts = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, p.precio FROM inv i
	 					JOIN precios p ON p.articuloid = i.articuloid
                        JOIN alm a ON a.articuloid = i.articuloid
						WHERE
							p.unidadid = i.unibasid
							AND p.nprecio = 1
							AND a.existencia > 0
							AND
							  (
								 i.descripcio LIKE '%$findProductBy%'
								 OR i.clvprov LIKE '$findProductBy%'
							  )
							OR
								i.clave LIKE '%$findProductBy%'
							ORDER BY CASE WHEN i.clave LIKE '$findProductBy%'
								THEN 1
								ELSE 2
								END, i.clave";

	 $exeQuGetPagination = $paramDb->Query($getproducts);
	  if($exeQuGetPagination === false) {
		echo "error-query";
		return false;
	  }

	  $numRow = $paramDb->NumRows();
	  $rows = $paramDb->Rows();

	 $headers = ["Imagen", "Clave", "Código", "Producto", "Precio", "Agregar"];
	 $classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

	 // NOTE draw table header, only print header, the body it's on loop
	 $print = $paramFunctions->drawTableHeader($headers, $classPerColumn);
	 if($numRow > 0) {
		foreach($rows as $row) {
		  $id = $row["articuloid"];
		  $clave = $row["clave"];
		  $codigo = $row["clvprov"];
		  $producto = $row["descripcio"];
		  $precio = number_format($row["precio"], 2, ".", ",");

		  $getImagen = "SELECT imagen
						FROM imagenes
						WHERE codigo = $codigo";
		  $resultImg = mysqli_query($getConnection,$getImagen);
		  $filaImg = mysqli_fetch_array($resultImg);
		  $imagen = $filaImg[0];

		  /*$imagen = "product.gif";*/

		  // TODO display image, depend size, get and show url
		  //$imagen = "imagen" . $id;
		  //$imagen = $codigo;
		  // send JSON to get on js and process it request
		  $params = array("productoID"=>$codigo,
								"location"=>"addProduct-to-shoppingcart-partner",
								"url"=>"../php/shopping/shopping.php",
								"booleanResponse"=>true,
								"divResultID"=>"content-shoppingCar-partner",
								"msgSuccess"=>"Producto agregado correctamente",
								"msgError"=>"Error al agregar producto al carrito");
		  $paramsSend = json_encode($params);

		  // JSON to show modal when user will see description and images about product
		  $paramsModal = array("productID"=>$id,
								"location"=>"showModalProductRegisteredUser",
								"section"=>"productRegisteredUser",
								"url"=>"../php/product/product.php",
								"booleanResponse"=>false,
								"divResultID"=>"resultModalProduct",
								"msgSuccess"=>"Ok!",
								"msgError"=>"Error mostrar informacion del producto");
		  $paramsModalSend = json_encode($paramsModal);

		  $print .= "<tr>";
		  $print .=   "<td class='text-center' style='width:200px;'>";
		  $print .=     "<img class='img-fluid' src='../img/img_pro/img/".$imagen."' alt='$producto' width='100'/>";
		  $print .=    "</td>";

		  $print .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$clave</td>";
		  $print .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$codigo</td>";
		  $print .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$producto</td>";
		  $print .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$ $precio</td>";
		  $print .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>";
		  $print .=     "<button type='button' class='btn btn-success btnOk' onclick='generalFunctionToRequest($paramsSend)'><i class='fa fa-plus' aria-hidden='true'></i></button>";
		  $print .=   "</td>";
		  $print .= "</tr>";
		}
		$print .= "</table>";
		echo $print;
	 } else {
	 	echo "<h4>No se encontro nada en la busqueda, por favor intente con otro nombre, código o clave. Gracias</h4>";
	 }
	 $getConnection->close();
  }


  // general function to print data table, call in others functions from here
function getTableShoppingCarPartner() {
	$paramFunctions = new Util();
	$shopping = $_SESSION["shoppingCarPartner"];
	$length = count($shopping);

	if($length > 0) {
		$headers = ["PROMOTRUPER", "Clave", "Producto", "Imagen", "Precio", "Inner", "Cantidad", "Subtotal", "Eliminar"];
		$classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

		$print = "<div style='overflow-x:hidden;'>";
		// NOTE draw table header, only print header, the body it's on loop
		$print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
		$subtotal = 0;
		$total = 0;

		for ($i=0; $i < $length; $i++) {
			if(isset($shopping[$i])) {
				$productID = $shopping[$i]["productID"];
				$productCode = $shopping[$i]["productCode"];
				$product = $shopping[$i]["product"];
				$imagen = $shopping[$i]["imagen"];
				$quantity = $shopping[$i]["quantity"];

				$params = array("productID" => $productID,
								"location" => "deleteProductShoppingCarPartner");
				$paramsSendDelete = json_encode($params);

				$paramDb = new Database();
				$getConnection = $paramDb->GetLink();

				$getCant = "SELECT ca.unitario, ca.caja, ca.master, i.invdescuento
								FROM cantidades ca
									JOIN inv i ON i.clvprov = ca.codigo
								WHERE i.clave = '$productCode'";
				$resCant = mysqli_query($getConnection,$getCant);
				$filaCant = mysqli_fetch_array($resCant);
				$unitario = $filaCant[0];
				$caja = $filaCant[1];
				$master = $filaCant[2];
				$desc = $filaCant[3];

				$price = $shopping[$i]["price"];
				$price = ($price * 0.16) + $price;

				if($desc > 0){
					$des = $desc / 100;
					$priceAnt = $price;
					$priceFormatAnt = number_format($priceAnt, 2);
					$price = $price - ($price * $des);
				}
				$priceFormat = number_format($price, 2);

				$subtotal =  $quantity * ((double)$price);
				$subtotalFormat = number_format($subtotal, 2);

				$total += $subtotal;
				$totalFormat = number_format($total, 2);

				$params2 = array("productoID" => $productID);
				$paramsSend2 = json_encode($params2);

				$print .= 	"<tr>
							<td class='text-center' style='font-weight:bold;vertical-align:middle;'>";
				if($desc > 0){
					$promocion = number_format($desc);
					$print .= 	"<img class='img-fluid' src='../img/iconos/".$promocion."porciento2000x763.png' width='100' alt='($product)'/>";
				} else {
					$print .= 	"<p>SIN PROMO</p>";
				}
				$print .= 	"</td>
							<td class='text-center' style='font-weight:bold;vertical-align:middle;'>$productCode</td>
							<td class='text-center' style='font-weight:bold;vertical-align:middle;'>$product</td>
							<td class='text-center' width='15%'><img class='img-fluid' src='../img/img_pro/img/".$imagen."' width='100' alt='($product)'/></td>";
				if($desc > 0){
					$print .="<td class='text-center' style='font-weight:bold;vertical-align:middle;'>
								<p Style='text-decoration:line-through;'>MX$ $priceFormatAnt</p>
								<p style='font-weight:bold;color:red; font-size: 1.2em;'>MX$ $priceFormat</p>
							</td>";
				} else {
					$print .="<td class='text-center' style='font-weight:bold;vertical-align:middle;'>
								<p style='font-weight:bold;'>MX$ $priceFormat</p>
							</td>";
				}
				$print .=	"<td class='text-center' style='font-weight:bold;vertical-align:middle;'>
								<p>Mínimo: $unitario</p>
								<p>Caja: $caja</p>
								<p>Master: $master</p>
								<!--<select name='inner' id='inner$productID' onChange='calculatePiezas($paramsSend2)'>
									<option value=''>Seleccione...</option>
									<option value='$unitario'>Unitario</option>
									<option value='$caja'>Caja</option>
									<option value='$master'>Master</option>
								</select>-->
							</td>
							<td class='text-center' style='vertical-align:middle;' width='8%'' id='actualizarQuantity$productID'>
								<input type='number' class='form-control text-center' style='vertical-align:middle; font-weight:bold;' id='quantity$productID' min=1 value='$quantity' onChange='calculatePiezas($paramsSend2)'/>
							</td>
							<td class='text-center' style='vertical-align:middle;' id='actualizarPrice".$productID."'><b style='display:none' id='price$productID'>$price</b><b>MX$ $subtotalFormat<b></td>
							<td class='text-center' style='vertical-align:middle;'>
								<button type='button' class='btn btn-danger'
									data-toggle='confirmation' data-placement='top'
									data-title='¿Eliminar?'
									data-btn-ok-icon='icon-like'
									data-btn-ok-label='Sí'
									data-btn-cancel-icon='icon-close'
									data-btn-cancel-label='No'
									data-sendparameter='$paramsSendDelete'>
									<i class='fa fa-trash-o' aria-hidden='true'></i>
								</button>
							</td>
							<!--<td class='text-center' style='vertical-align:middle'>
								<button type='button' class='btn btn-primary'
									onclick='saveQuantityShoppinCarPartner($productID)'
									data-toggle='tooltip' title='Actualiza/Guarda todos los cambios!!'>
									Actualizar
									<span class='fa fa-floppy-o' aria-hidden='true'></span>
								</button>
							</td>-->
						</tr>";
			}
		}
		$print .=		"<tr>
							<!--<td colspan='7'>
								<a href='#' class='btn btn-primary' onclick='saveAllQuantityShoppinCarPartner()'>Actualizar Todo</a>
							</td>-->
							<td colspan='9' style='text-align:right;'>
								<label style='font-size: 1.5em;' id='total'>Total $ <b>$totalFormat</b></label>
								<p>Precio con I.V.A.</p>
							</td>
						</tr>";
		$print .=	"</table>";
		$print .="</div>"; // overflow-x:auto

	if(isset($_SESSION["shoppingCarPartner"]) && (count($_SESSION["shoppingCarPartner"])) > 0) {
	  // button to delete shopping car (session), and button to save on table
	  // pedidos loop products on session and save on table descripcionPedido
	  $print .=
			"<div class='row'>
			  <div class='col-md-6'>
				 <button type='button' class='btn btn-danger pull-left'
				  onclick='deleteShoppinCarPartner()'
				  data-toggle='tooltip' title='Eliminar todos los productos!'>
					Vaciar Carrito
					</span>
				 </button>
			  </div>
			  <div class='col-md-6'>";
	  // params to send general generalFunctionToRequest to proccess order
	  $params = array("url" => "../php/shopping/shopping.php",
						"location" => "saveorderpartner",
						"booleanResponse" => true, // params to know if the response it's success or print html
						"msgSuccess" => "Su pedido ha sido Enviado",
						"msgError" => "Problemas al enviar pedido");
	  $paramsSend = json_encode($params);

	  $print .=
			"<button type='button' class='btn btn-success pull-right'
				onclick='generalFunctionToRequest($paramsSend)'
				title='Procesar pedido!'>
					Procesar Pedido
				 </button>
			  </div>
			</div>";
	}

	// NOTE print after all files js, because there is a conflict
	echo <<<CONFIRMATION
	  <script>
		 $('[data-toggle="confirmation"]').confirmation({
			onConfirm: function(event, result) {
			  event.preventDefault();
			  // console.log($(this));
			  var jsonParameter = $(this)[0].sendparameter;
			  deleteProductShoppinCarPartner(jsonParameter.productID);
			},
			onCancel: function() {
			  // do something when user cancel
			}
		 });
	  </script>
CONFIRMATION;
	} else {  // validate length of array
	 $print =
		"<div class='row'>
		  <div class='col-md-12'>
			 <h4 style='font-weight:bold; color:red;'>Nada Solicitado</h4>
		  </div>
		</div>";
	}

	 return $print;
	}

  private function getShoppingCarPublic($data) {
	 if($data != "false") {
		$total = 0;
		$result = json_decode($data, true);
		$contentProducts = [];
		echo "<div style='overflow-x:hidden;'>";
		echo "<div class='col-md-12' style='margin:0 0 0 10px;padding:0;'>";
		echo "<div class='col-md-10' style='margin:20px 0 20px 0;padding:0 5px 0 0;'>";
		echo "<div class='panel panel-default' style='margin:0;'>";
		echo "<div class='panel-heading'>";
		echo "<h4>CARRITO DE COMPRAS</h4>";
		echo "</div>";
		echo "<div class='panel-body'>";
		echo "<table class='table table-responsive table-ondensed' id='containerShoppingCar' style='margin:10px 10px 10px 0;'>";
		  echo "<thead>";
		  echo    "<tr>
						<th class='text-center' style='vertical-align:middle; font-weight:bold'>
						  Producto
						</th>
						<th class='text-center' style='vertical-align:middle; font-weight:bold'>
						  Contenido
						</th>
						<th class='text-center'style='vertical-align:middle; font-weight:bold'>
						  No. Piezas
						</th>
						<th class='text-center' style='vertical-align:middle; font-weight:bold'>
						  Cantidad
						</th>
						<th class='text-center' style='vertical-align:middle; font-weight:bold'>
						  Precio
						</th>
						<th class='text-center' style='vertical-align:middle; font-weight:bold'>
						  Total
						</th>
						<th class='text-center' style='vertical-align:middle; font-weight:bold'>
						  Eliminar
						</th>
						<th>
						</th>
					 </tr>";
		  echo "</thead>";
		for($i = 0; $i < count($result); $i++) {
		  $id = $result[$i]["product"];
		  $title = $result[$i]["title"];
		  $quantity = $result[$i]["quantity"];
		  $type = $result[$i]["type"];
		  $price = $result[$i]["price"];
		  $piece = $result[$i]["piece"];

		  $precioPorCaja = $price * $piece;
		  $subtotal = $precioPorCaja * $quantity;

		  /*$price = str_replace(',', '', $price);*/
		  /*$subt = $price * ((double)$price);*/
		  /*$subtotal = $piece * ((double)$subt);*/
		  

		  $total = ((double)$total) + ((double)$subtotal);


		  // formating number
		  $priceFormat = number_format($price, 2);
		  $subtotalFormat = number_format($subtotal, 2);
		  $totalFormat = number_format($total, 2);

		  $iva = 0.16;

		  $calculoIva = $total * $iva;
		  $formatoIva = number_format($calculoIva, 2);

		  $calculoFinal = $total + $calculoIva;
		  $formatoTotal = number_format($calculoFinal, 2);

		  // NOTE if you want to send title, check because it has simple quotes ('), then remove and construc then send.
		  // construct array for each product, then send as a JSON
		  $title = str_replace("'", "", $title); // remove semi quotes
		  $arrayProduct = array("productID" => $id,
										"quantity"  => $quantity,
										"title"     => $title,
										"price"     => $price,
										"piece"     => $piece,
										"type"      => $type);
		  array_push($contentProducts, $arrayProduct);

		  // construc params to send for each product
		  $params = array("productID"   => $id,
							"location"    => "deleteProductShoppingCarPublic");
		  $paramsSendDelete = json_encode($params);

		  $print = "<tr>
						  <td style='vertical-align:middle; font-weight:bold;'><p id='titulo$id'>$title</p>
						  </td>
						  <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
							 <p id='type$id'>$type</p>
						  </td>
						  <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
							 <p>$quantity</p>
						  </td>
						  <td width='5%' class='text-center' style='vertical-align:middle; font-weight:bold;'>
							 <input type='number' id='quanshop$id' min='1' class='form-control'>
						  </td>
						  <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
							 <p id='precio$id'>$$priceFormat</p>
						  </td>
						  <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
							 <p>$$subtotalFormat</p>
						  </td>
						  <td class='text-center' style='vertical-align:middle; font-weight:bold;'>";
		  $print .=     "<a href='#'>";
		  $print .=
						  "   <span class='fa fa-trash fa-2x' aria-hidden='true' 
								  data-toggle='confirmation'
								  data-placement='top'
								  data-title='¿Elminar?'
								  data-btn-ok-icon='icon-like'
								  data-btn-ok-label='Sí'
								  data-btn-cancel-icon='icon-close'
								  data-btn-cancel-label='No'
								  data-sendparameter='$paramsSendDelete'>
								</span>
							 </a>
						  </td>
						  <td style='text-align: center'>
							 <a href='#' class='btn btn-primary' onclick='saveAllQuantityShoppingCartPublic(event)'>Actualizar</a>
						  </td>
						</tr>";
		  echo $print;
		}
		// send params to save on database
		$arrayProcessOrder = json_encode($contentProducts);
		echo    "<tr>
					 <td colspan='6'>
					 </td>
					 <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
						<p style='font-size: 1.5em;'>Sub-Total:</p>
					 </td>
					 <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
						<p style='font-size: 1.5em;'>$$totalFormat</p>
					 </td>

				  </tr>
				  <tr>
					 <td colspan='6' style='border:none;'>
					 </td>
					 <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
						<p style='font-size: 1.5em;'>I.V.A. (16%)</p>
					 </td>
					 <td class='text-center' style='vertical-align:middle; font-weight:bold;'>
						<p style='font-size: 1.5em;'>$$formatoIva</p>
					 </td>
				  </tr>
				  <tr>
					 <td colspan='6' style='border:none;'>
					 </td>
					 <td class='text-center' style='vertical-align:middle; font-weight:bold;background-color:grey !important;color:#fff'>
						<p style='font-size: 2em;'>Total</p>
					 </td>
					 <td class='text-center' style='vertical-align:middle; font-weight:bold;background-color:grey !important;color:#fff'>
						<p style='font-size: 2em;'>$$formatoTotal</p>
					 </td>
				  </tr>";
		echo "</table>";
		echo "</div>";
		echo "<div class='row' style='margin: 10px 50px 50px 50px;'>
				  <button type='button' class='btn btn-danger pull-left'
					onclick='deleteLocalStorage()'
					data-toggle='tooltip' title='Eliminar todos los productos!'>
					 Vaciar Carrito
				  </button>";
		echo    '<div class="modal fade" id="confirm-order" tabindex="-1" role="dialog"
					  aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
						  <div class="modal-content">
							 <div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="myModalLabel">Confirmar Pedido</h4>
							 </div>

							 <div class="modal-body">
								<div class="row">
								  <div class="col-xs-12">
									 <div class="well">
										<form>
										  <div class="form-group">
											 <label for="username" class="control-label">Email</label>
											 <input type="email" name="emailOrder" class="form-control text-center" id="emailOrder" value="" required title="Ingresa tu email!" placeholder="example@gmail.com">
											 <span class="help-block"></span>
										  </div>
										  <div class="form-group">
											 <label class="control-label">Nombre Completo</label>
											 <input type="text" class="form-control text-center" id="nombre" name="nombre" value="" required title="Ingresa tu Nombre completo!">
											 <span class="help-block"></span>
										  </div>
										  <div class="form-group">
											 <label class="control-label">Dirección Completa</label>
											 <textarea rows="3" class="form-control" id="direccion" name="direccion" value="" required title="Ingresa tu Dirección completa!"></textarea>
											 <span class="help-block"></span>
										  </div>
										  <div class="form-group">
											 <label class="control-label">Código Postal</label>
											 <input type="text" class="form-control text-center" id="postal" name="postal" value="" required title="Ingresa tu Código Postal para tu envío!">
											 <span class="help-block"></span>
										  </div>
										  <div class="form-group">
											 <label class="control-label">RFC</label>
											 <input type="text" class="form-control text-center" id="rfc" name="rfc" value="" title="Ingresa tu RFC con homoclave, si necesitas factura!">
											 <span class="help-block"></span>
										  </div>
										  <div class="form-group">
											 <label class="control-label">Teléfono / Celular</label>
											 <input type="text" class="form-control text-center" id="celular" name="celular" value="" required title="Ingresa un número para comunicarnos!">
											 <span class="help-block"></span>
										  </div>
										  <button type="button" class="btn btn-default" data-dismiss="modal">
											 Cancel
										  </button>';
		echo                      "<button type='button' class='btn btn-success btn-ok' onclick='processOrder(".$arrayProcessOrder.")'>
											 Enviar Pedido
										  </button>";
		echo                    '</form>
									 </div>
								  </div>
								</div>
							 </div>';
		echo          "</div>
						</div>
				  </div>
				  <button type='button' class='btn btn-success pull-right'
					data-toggle='modal' data-target='#confirm-order' title='Procesar pedido!'>
					 Procesar Pedido
				  </button>
				</div>";
		/*echo "</div>";*/
		echo "</div>";
		echo "</div>";
		echo "<div class='col-md-2' style='margin:20px 0 20px 0;padding:0 18px 0 5px;'>";
		echo    "<div class='panel panel-default' style='margin:0'>";
		echo      "<div class='panel-heading'>";
		echo        "<h4>MÉTODOS DE ENTREGA</h4>";
		echo      "</div>";
		echo      "<div class='panel-body'>";
		echo        "<p>Pautas de envio</p>";
		echo      "</div>";
		echo    "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>"; // div overflow-x:auto

		// NOTE print after all files js, because there is a conflict
		echo <<<CONFIRMATION
		  <script>
			 $('[data-toggle="confirmation"]').confirmation({
				onConfirm: function(result) {
				  // console.log($(this));
				  var jsonParameter = $(this)[0].sendparameter;
				  deleteElementShoppingCar(jsonParameter.productID);
				},
				onCancel: function() {
				  // do something when user cancel
				}
			 });
		  </script>
CONFIRMATION;

	 } else {
		// shopping cart it's empty
	 }

  }

  private function getShoppingCarPartner() {
  	 /*echo "<div id='espere' style='position:relative;display:flex;align-items:center;justify-content:center;'><!--<img src='../img/img_pro/219.gif'/>--> <p>Un momento por favor, estamos procesando su solicitud. Gracias.</p></div>";*/
	 $print = "<div class='row' style='margin: 40px 15px 0 250px;display:;'>";

	 // display response ajax records orders
	 /*$print .=   "<div class='col-md-12'>";
	 $print .=     "<div class='panel panel-default responsiveCarrito'>";
	 $print .=       "<div class='panel-heading'>";
	 $print .=         "<h3 class='panel-title' style='font-weight:bold'>Pedidos Realizados</h3>";
	 $print .=       "</div>"; // panel-heading

	 $print .=       "<div class='panel-body fixed-panel' id='content-orders-record'>";
	 $print .=       "</div>"; // panel-body
	 $print .=      "</div>"; // panel-default
	 $print .=    "</div>"; // col-md-12
	 $print .=   "<div class='col-md-12'>";
	 $print .=     "<div class='panel panel-default'>";
	 $print .=       "<div class='panel-heading'>";
	 $print .=         "<h3 class='panel-title'><i class='fa fa-search' aria-hidden='true'></i> Buscar producto </h3>";
	 $print .=       "</div>"; // panel-heading*/

	 $print .=       "<div class='panel-body'>";
	 $print .=         "<div class='form-group col-md-12'>"; // content
	 $print .=           "<div class='form-group col-md-7'>"; // input search
	 $print .=             "<p style='font-weight:bold'>Ingresa tu busqueda: ";
	 $print .=             "<input type='text' class='form-control' id='findProductBy' placeholder='Código, Clave ó Descripción'/></p><div id='espere'><p>Un momento por favor, estamos procesando su solicitud.<img src='../img/img_pro/loading.gif' width='100'/></p></div>";
	 $print .=           "</div>"; // col-md-7 to size input search
	 $print .=           "<div class='form-group col-md-12'>"; // result response
	 // display response ajax products, when user search by: code, key or title
	 $print .=             "<div id='resultFindProductBy' class='responsiveCarrito'></div>";
	 $print .=           "</div>"; // col-md-12 result response
	 $print .=          "</div>"; // col-md-12 content
	 $print .=        "</div>"; // panel-body

	 $print .=     "</div>"; // panel-default
	 $print .=   "</div>"; // col-md-12

	 $print .= "</div>"; // row

	 $print .= "<hr style='margin:0'>";

	 $print .= "<div class='row' style='margin: 20px 15px 0 250px;'>";
	 // NOTE update dynamically ($_SESSION php) this div id=content-shoppingCar-partner by response ajax
	 $print .=   "<div class='col-md-12'>";
	 $print .=     "<div class='panel panel-default responsiveCarrito2'>";
	 $print .=       "<div class='panel-heading'>";
	 $print .=         "<h3 class='panel-title' style='font-weight:bold'><i class='fa fa-cart-arrow-down' aria-hidden='true'></i> Pedido Actual</h3>";
	 $print .=       "</div>"; // panel-heading

	 $print .=       "<div class='panel-body fixed-panel2' id='content-shoppingCar-partner'>";
	 // validate if exist session, then loop to show products
	 if(!isset($_SESSION["shoppingCarPartner"])) {
		$print .= "<h4 style='font-weight:bold; color: red'>Sin productos...</h4>";
	 } else {
		$print .= $this->getTableShoppingCarPartner();
	 }

	 $print .= "</div>"; // End panel-body
	 $print .=  "</div>"; // End div col-md-12
	 $print .= "</div>"; // End div row

	 echo $print;
  }

  //TODO hacer el dashboard de la intranet
  private function getDashBoardPartner() {
	$paramDb = new Database();
	$paramFunctions = new Util();
	$getConnection = $paramDb->GetLink();

	if(isset($_SESSION["data"])) {
		$session = $_SESSION["data"];
	}

	$rol = $paramDb->SecureInput($session["rol"]);
	$clienteID = $paramDb->SecureInput($session["username"]);
	$id = $_SESSION["data"]["id"];
	/*$id = 4;*/
	$username = $_SESSION["data"]["name"];
	$rfc = $_SESSION["data"]["rfc"];
	$saldo = $_SESSION["data"]["saldo"];
	$formatoSaldo = number_format($saldo, 2, '.',',');
	$limite = $_SESSION["data"]["limite"];
	$formatoLimite = number_format($limite, 2, '.',',');
	$dispo = $limite - $saldo;
	$dispo = number_format($dispo, 2, '.',',');
	$disponible = $limite - $saldo;
	$vendedor = $_SESSION["data"]["vendedor"];
	$vendedorID = $_SESSION["data"]["vendedorid"];
	$diascredito = $_SESSION["data"]["diacredito"];
	$diasvisita = $_SESSION["data"]["diavis"];
	$ultimacompra = $_SESSION["data"]["ucompra"];
	$compraReciente = $_SESSION["data"]["compraReciente"];
	$pas2 = $_SESSION["data"]["pas2"];
	$pasAnt = $_SESSION["data"]["pasAnt"];
	$correo = $_SESSION["data"]["correo"];
	$arrayBooleans = array("bManagementOrder" => false);

	switch ($diasvisita) {
		case 'D':
			$dia = 'Domingo';
			break;
		case 'L':
			$dia = 'Lunes';
			break;
		case 'M':
			$dia = 'Martes';
			break;
		case 'I':
			$dia = 'Miércoles';
			break;
		case 'J':
			$dia = 'Jueves';
			break;
		case 'V':
			$dia = 'Viernes';
			break;
		case 'S':
			$dia = 'Sábado';
			break;
		default:
			$dia = 'Sin Visita';
			break;
	}

	$date=new DateTime();
	$fechaActualDia = $date->format('d');
	$fechaActualMes = $date->format('m');

	$UltimaComp = strtotime($ultimacompra);
	$diasUltimaCompra = idate('d' ,$UltimaComp);
	$mesUltimaCOmpra = idate('m',$UltimaComp);

	switch ($mesUltimaCOmpra) {
		case '1':
			$diasTotalMesUltimaCompra = 31;
			break;

		case '2':
			$diasTotalMesUltimaCompra = 28;
			break;

		case '3':
			$diasTotalMesUltimaCompra = 31;
			break;

		case '4':
			$diasTotalMesUltimaCompra = 30;
			break;

		case '5':
			$diasTotalMesUltimaCompra = 31;
			break;

		case '6':
			$diasTotalMesUltimaCompra = 30;
			break;

		case '7':
			$diasTotalMesUltimaCompra = 31;
			break;

		case '8':
			$diasTotalMesUltimaCompra = 31;
			break;

		case '9':
			$diasTotalMesUltimaCompra = 30;
			break;

		case '10':
			$diasTotalMesUltimaCompra = 31;
			break;

		case '11':
			$diasTotalMesUltimaCompra = 30;
			break;

		case '12':
			$diasTotalMesUltimaCompra = 31;
			break;
	}

	//Buscar actualización de la base de datos
    $con1 = "SELECT MAX(id) as actid FROM actualizacion";
	$res1 = mysqli_query($getConnection,$con1);
	$fil1 = mysqli_fetch_array($res1);
	$idActualizacion = $fil1["actid"];

	$con2 = "SELECT * FROM actualizacion WHERE id=$idActualizacion";
	$res2 = mysqli_query($getConnection,$con2);
	$fil2 = mysqli_fetch_array($res2);
	$dateAct = $fil2["date"];
	$timeAct = $fil2["time"];

	$actualizacion = $dateAct." a las ".$timeAct;

	$sumaDeDias = $diasUltimaCompra + $diascredito;
	$fecActual2 = date("Y-m-d");
	$getGraphVencido2 = "SELECT docid, feccap, feccan, vence, total, totalpagado
							FROM doc
							WHERE clienteid = $id
								AND totalpagado < total
								AND feccan = 0
								AND tipo NOT LIKE 'C'
								AND vence < '$fecActual2'
							ORDER BY feccap ASC";

	$numVec2 = mysqli_query($getConnection, $getGraphVencido2);
	$numeroVeces = $numVec2->num_rows;

	if($numeroVeces > 0){
		if($fechaActualMes == $mesUltimaCOmpra){
			if($sumaDeDias >= $diasTotalMesUltimaCompra){
				$diasRestantesNewMes = $sumaDeDias - $diasTotalMesUltimaCompra;
				$nextMes = $mesUltimaCOmpra + 1;
				$fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");
			} else {
				$fechaLimite = date("Y-$mesUltimaCOmpra-$sumaDeDias");//39
				$diasRestantesNewMes = $sumaDeDias;
			}
		} elseif($sumaDeDias >= $diasTotalMesUltimaCompra){
			$diasRestantesNewMes = $diasTotalMesUltimaCompra - $sumaDeDias;
			if($diasRestantesNewMes > 0){
				$nextMes = $mesUltimaCOmpra + 1;
				$fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");//39
			} else {
				$fechaLimite = 'Factura(s) Vencida(s)';
			}
		} else {
			$diasRestantesNewMes = ($diasTotalMesUltimaCompra - $sumaDeDias) - $fechaActualDia;
			$fechaLimite = 'Factura(s) Vencida(s)';
			$nextMes = $mesUltimaCOmpra;
		}
	} else {
		$diasRestantesNewMes = 0;
		$fechaLimite = 'Sin Vencimiento';
		$nextMes = 0;
	}

	if($pas2==''){
		echo 	'<div id="myLargeModalLabel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header text-center">
								<p class="lead">Bienvenido a su Escritorio Virtual FMO, este es su primer inicio de sesión y le invitamos a realizar el cambio de su contraseña; si no lo realiza, no podrá accesar nuevamente a su escritorio hasta que termine el mes, <b>ya que su contraseña se resetea cada inicio de mes.</b></p>
							</div>
							<div class="modal-body" style="display:flex; align-items: center; justify-content: center;">
								<form>
									<div class="form-group" style="text-align: center;">
										<input style="width:300px;" class="form-control text-center" id="usuario" name="usuario" value="'.$clienteID.'" type="text" readonly="readonly">
									</div>
									<div class="form-group" style="text-align: center;display:;">
										<input style="width:300px;" class="form-control text-center" id="email" name="email" value="'.$correo.'" type="email" readonly="readonly">
									</div>
									<div class="form-group">
										<input autofocus style="width:300px;" class="form-control text-center" id="passwordNew" name="password" onChange="verificarPassword()" placeholder="Nuevo Password" type="text" autocomplete="off" required>
										<p id="pasAnt" style="display:none;">'.$pasAnt.'</p>
									</div>
									<script>
										function verificarPassword(){
											var pasAnt 	= document.getElementById("pasAnt").innerHTML;
											var pasNew 	= document.getElementById("passwordNew").value;
											var usuario = document.getElementById("usuario").value;
											var email 	= document.getElementById("email").value;
											console.log(pasAnt, pasNew, usuario, email);
											if(pasAnt === pasNew){
												alert("El nuevo password debe ser diferente.");
											} else {
												$.post("../php/classes/cambiarpass.php", {usuario: usuario, password: pasNew, emial: email});
												alert("Su contraseña se cambio correctamente, debe de inciar sesión con los nuevos datos. Gracias!.");
												Session.Clear();
												Session.Abandon();
											}
										}
									</script>
									<div id="mensajePas"></div>
									<div class="form-group text-center" id="botonEnviar" style="display: none;">
										<button class="btn btn-danger pull-center" type="submit">Enviar</button>
									</div>
								</form>
								<!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
							</div>
						</div>
					</div>
				</div>
				<script>$(document).ready(function(){$("#myLargeModalLabel").modal("show");});</script>';
	}

	$print =	'<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
					<div class="content-wrapper row" style="margin-top: 40px;">
						<!-- Content Header (Page header) -->
						<section class="content-header col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
							<span style="font-size: 1.5em;font-weight:bold;color:red;">Versión &beta;eta | Última actualización: '.$actualizacion.'</span>
							<h1>
								<span style="font-weight:bold;">#'.$clienteID.'</span> - '.$username.'
								<small>'.$rol.'</small>
							</h1>
						</section>
						<!-- Main content -->
						<section class="content col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
							<!-- Info boxes -->
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
									<div class="info-box">
										<span class="info-box-icon bg-red"><i class="fas fa-dollar-sign" aria-hidden="true"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Saldo en la Cuenta</span>
											<span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatoSaldo.'</span>
										</div>
										<!-- /.info-box-content -->
									</div>
									<!-- /.info-box -->
								</div>
								<!-- /.col -->
								<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
									<div class="info-box">
										<span class="info-box-icon bg-aqua"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Saldo Disponible</span>
											<span class="info-box-number" style="font-size: 1.7em !important;">$ '.$dispo.'</span>
										</div>
										<!-- /.info-box-content -->
									</div>
									<!-- /.info-box -->
								</div>
								<!-- /.col -->
								<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
									<div class="info-box">
										<span class="info-box-icon bg-green"><i class="fa fa-lock" aria-hidden="true"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Limite de Crédito</span>
											<span class="info-box-number" style="font-size: 1.7em !important;">$ '.$formatoLimite.'</span>
										</div>
										<!-- /.info-box-content -->
									</div>
									<!-- /.info-box -->
								</div>
							</div>
							<!-- /.row -->
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
									<div class="info-box">
										<span class="info-box-icon bg-yellow"><i class="fas fa-stopwatch" aria-hidden="true"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Días de Crédito</span>
											<span class="info-box-number" style="font-size: 1.7em !important;">'.$diascredito.'</span>
										</div>
										<!-- /.info-box-content -->
									</div>
									<!-- /.info-box -->
								</div>
								<!-- /.col -->
								<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
									<div class="info-box">
										<span class="info-box-icon bg-fuchsia"><i class="far fa-calendar-plus" aria-hidden="true"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Última Compra</span>
											<span class="info-box-number" style="font-size: 1.7em !important;">'.$compraReciente.'</span>
										</div>
										<!-- /.info-box-content -->
									</div>
									<!-- /.info-box -->
								</div>
								<!-- /.col -->
								<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
									<div class="info-box">
										<span class="info-box-icon bg-maroon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
										<div class="info-box-content">
											<span class="info-box-text">Próximo Vencimiento</span>
											<span class="info-box-number" style="font-size: 1.7em !important;">'.$fechaLimite.'</span>
										</div>
										<!-- /.info-box-content -->
									</div>
									<!-- /.info-box -->
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
									<div class="box">
										<div class="box-header with-border">';

	$trim1 = "<strong>Compras: 01 Enero, ".date("Y")." - 31 Marzo, ".date("Y")."</strong>";
	$trim2 = "<strong>Compras: 01 Abril, ".date("Y")." - 30 Junio, ".date("Y")."</strong>";
	$trim3 = "<strong>Compras: 01 Julio, ".date("Y")." - 30 Septiembre, ".date("Y")."</strong>";
	$trim4 = "<strong>Compras: 01 Octubre, ".date("Y")." - 31 Diciembre, ".date("Y")."</strong>";

	$newMesActual = new DateTime();
	$MesActual = $newMesActual->format('m');
	
	if($MesActual < 4){
		$periodo		= '1er. Periodo';
		$print .=							'<h3 class="box-title">'.$periodo.' Trimestral del '.date("Y").'</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
													<p class="text-center">';
	$print .=											$trim1;
		$fechaInicio 	= date("Y-01-01");
		$fechaFinal 	= date("Y-03-31");
		
	} elseif($MesActual < 7){
		$periodo		= '2do. Periodo';
		$print .=							'<h3 class="box-title">'.$periodo.' Trimestral del '.date("Y").'</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
													<p class="text-center">';
	$print .=											$trim2;
		$fechaInicio 	= date("Y-04-01");
		$fechaFinal 	= date("Y-06-30");
	} elseif($MesActual < 10){
		$periodo		= '3er. Periodo';
		$print .=							'<h3 class="box-title">'.$periodo.' Trimestral del '.date("Y").'</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
													<p class="text-center">';
	$print .=											$trim3;
		$fechaInicio 	= date("Y-07-01");
		$fechaFinal 	= date("Y-09-30");
	} elseif($MesActual > 9){
		$periodo		= '4to. Periodo';
		$print .=							'<h3 class="box-title">'.$periodo.' Trimestral del '.date("Y").'</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
													<p class="text-center">';
		$print .=										$trim4;
		$fechaInicio 	= date("Y-10-01");
		$fechaFinal 	= date("Y-12-31");
	}

	$print .=										'</p>
													<!-- DONUT CHART -->
													<div clas="row">
														<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">';

	$getGraphCompras = "SELECT de.desventa, de.descantidad
			FROM doc d
				JOIN des de ON de.desdocid = d.docid
				JOIN inv i ON i.articuloid = de.desartid
			where d.clienteid = $id
				AND de.desartid > 14
                AND d.subtotal2 > 0
				AND de.descantidad > 0
				AND d.feccap >= '$fechaInicio'
				AND d.feccap <= '$fechaFinal'
                AND i.clvprov NOT LIKE '8%'
				AND i.clvprov NOT LIKE '6%'
				AND d.tipo = 'F'
			ORDER BY d.feccap ASC";

  	$query_execute = $getConnection->query($getGraphCompras);
    $numeroArt = $query_execute->num_rows;
  	$total = 0;

	while($row = $query_execute->fetch_array()){
		$importe = $row["desventa"];
		$cantidad = $row["descantidad"];

		$total += $importe * $cantidad;
	}

	$year = date("Y");

	//Semanas del Mes
	if($MesActual < 4){
		$semana1 = "feccap >= '$year-01-01' AND feccap <= '$year-01-07' ";
		$semana2 = "feccap >= '$year-01-08' AND feccap <= '$year-01-14' ";
		$semana3 = "feccap >= '$year-01-15' AND feccap <= '$year-01-21' ";
		$semana4 = "feccap >= '$year-01-21' AND feccap <= '$year-01-28' ";
		$semana5 = "feccap >= '$year-01-29' AND feccap <= '$year-01-31' ";
		$semana6 = "feccap >= '$year-02-01' AND feccap <= '$year-02-07' ";
		$semana7 = "feccap >= '$year-02-08' AND feccap <= '$year-02-14' ";
		$semana8 = "feccap >= '$year-02-15' AND feccap <= '$year-02-21' ";
		$semana9 = "feccap >= '$year-02-21' AND feccap <= '$year-02-28' ";
		$semana10 = "feccap >= '$year-02-29' AND feccap <= '$year-02-30' ";
		$semana11 = "feccap >= '$year-03-01' AND feccap <= '$year-03-07' ";
		$semana12 = "feccap >= '$year-03-08' AND feccap <= '$year-03-14' ";
		$semana13 = "feccap >= '$year-03-15' AND feccap <= '$year-03-21' ";
		$semana14 = "feccap >= '$year-03-21' AND feccap <= '$year-03-28' ";
		$semana15 = "feccap >= '$year-03-29' AND feccap <= '$year-03-31' ";

		//Semana 1 Mes 1
		$getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana1
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasA = $getComprasSemanalA->num_rows;

		if($numComprasA > 0){
			$numComprasMes1Sem1 = $numComprasA;
		} else {
			$numComprasMes1Sem1 = 0;
		}

		//Semana 2 Mes 1
		$getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana2
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasB = $getComprasSemanalB->num_rows;

		if($numComprasB > 0){
			$numComprasMes1Sem2 = $numComprasB;
		} else {
			$numComprasMes1Sem2 = 0;
		}

		//Semana 3 Mes 1
		$getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana3
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasC = $getComprasSemanalC->num_rows;

		if($numComprasC > 0){
			$numComprasMes1Sem3 = $numComprasC;
		} else {
			$numComprasMes1Sem3 = 0;
		}

		//Semana 4 Mes 1
		$getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana4
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasD = $getComprasSemanalD->num_rows;

		if($numComprasD > 0){
			$numComprasMes1Sem4 = $numComprasD;
		} else {
			$numComprasMes1Sem4 = 0;
		}

		//Semana 1 Mes 2
		$getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana6
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasE = $getComprasSemanalE->num_rows;

		if($numComprasE > 0){
			$numComprasMes2Sem1 = $numComprasE;
		} else {
			$numComprasMes2Sem1 = 0;
		}

		//Semana 2 Mes 2
		$getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana7
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasF = $getComprasSemanalF->num_rows;

		if($numComprasF > 0){
			$numComprasMes2Sem2 = $numComprasF;
		} else {
			$numComprasMes2Sem2 = 0;
		}

		//Semana 3 Mes 2
		$getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana8
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasG = $getComprasSemanalG->num_rows;

		if($numComprasG > 0){
			$numComprasMes2Sem3 = $numComprasG;
		} else {
			$numComprasMes2Sem3 = 0;
		}

		//Semana 4 Mes 2
		$getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana9
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasH = $getComprasSemanalH->num_rows;

		if($numComprasH > 0){
			$numComprasMes2Sem4 = $numComprasH;
		} else {
			$numComprasMes2Sem4 = 0;
		}

		//Semana 1 Mes 3
		$getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana11
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasI = $getComprasSemanalI->num_rows;

		if($numComprasI > 0){
			$numComprasMes3Sem1 = $numComprasI;
		} else {
			$numComprasMes3Sem1 = 0;
		}

		//Semana 2 Mes 3
		$getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana12
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasJ = $getComprasSemanalJ->num_rows;

		if($numComprasJ > 0){
			$numComprasMes3Sem2 = $numComprasJ;
		} else {
			$numComprasMes3Sem2 = 0;
		}

		//Semana 3 Mes 3
		$getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana13
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasK = $getComprasSemanalK->num_rows;

		if($numComprasK > 0){
			$numComprasMes3Sem3 = $numComprasK;
		} else {
			$numComprasMes3Sem3 = 0;
		}

		//Semana 4 Mes 3
		$getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana14
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasL = $getComprasSemanalL->num_rows;

		if($numComprasL > 0){
			$numComprasMes3Sem4 = $numComprasL;
		} else {
			$numComprasMes3Sem4 = 0;
		}
	} elseif($MesActual < 7){
		$semana1 = "feccap >= '$year-04-01' AND feccap <= '$year-04-07' ";
		$semana2 = "feccap >= '$year-04-08' AND feccap <= '$year-04-14' ";
		$semana3 = "feccap >= '$year-04-15' AND feccap <= '$year-04-21' ";
		$semana4 = "feccap >= '$year-04-21' AND feccap <= '$year-04-28' ";
		$semana5 = "feccap >= '$year-04-29' AND feccap <= '$year-04-30' ";
		$semana6 = "feccap >= '$year-05-01' AND feccap <= '$year-05-07' ";
		$semana7 = "feccap >= '$year-05-08' AND feccap <= '$year-05-14' ";
		$semana8 = "feccap >= '$year-05-15' AND feccap <= '$year-05-21' ";
		$semana9 = "feccap >= '$year-05-21' AND feccap <= '$year-05-28' ";
		$semana10 = "feccap >= '$year-05-29' AND feccap <= '$year-05-31' ";
		$semana11 = "feccap >= '$year-06-01' AND feccap <= '$year-06-07' ";
		$semana12 = "feccap >= '$year-06-08' AND feccap <= '$year-06-14' ";
		$semana13 = "feccap >= '$year-06-15' AND feccap <= '$year-06-21' ";
		$semana14 = "feccap >= '$year-06-21' AND feccap <= '$year-06-28' ";
		$semana15 = "feccap >= '$year-06-29' AND feccap <= '$year-06-30' ";

		//Semana 1 Mes 1
		$getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana1
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasA = $getComprasSemanalA->num_rows;

		if($numComprasA > 0){
			$numComprasMes1Sem1 = $numComprasA;
		} else {
			$numComprasMes1Sem1 = 0;
		}

		//Semana 2 Mes 1
		$getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana2
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasB = $getComprasSemanalB->num_rows;

		if($numComprasB > 0){
			$numComprasMes1Sem2 = $numComprasB;
		} else {
			$numComprasMes1Sem2 = 0;
		}

		//Semana 3 Mes 1
		$getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana3
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasC = $getComprasSemanalC->num_rows;

		if($numComprasC > 0){
			$numComprasMes1Sem3 = $numComprasC;
		} else {
			$numComprasMes1Sem3 = 0;
		}

		//Semana 4 Mes 1
		$getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana4
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasD = $getComprasSemanalD->num_rows;

		if($numComprasD > 0){
			$numComprasMes1Sem4 = $numComprasD;
		} else {
			$numComprasMes1Sem4 = 0;
		}

		//Semana 1 Mes 2
		$getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana6
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasE = $getComprasSemanalE->num_rows;

		if($numComprasE > 0){
			$numComprasMes2Sem1 = $numComprasE;
		} else {
			$numComprasMes2Sem1 = 0;
		}

		//Semana 2 Mes 2
		$getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana7
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasF = $getComprasSemanalF->num_rows;

		if($numComprasF > 0){
			$numComprasMes2Sem2 = $numComprasF;
		} else {
			$numComprasMes2Sem2 = 0;
		}

		//Semana 3 Mes 2
		$getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana8
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasG = $getComprasSemanalG->num_rows;

		if($numComprasG > 0){
			$numComprasMes2Sem3 = $numComprasG;
		} else {
			$numComprasMes2Sem3 = 0;
		}

		//Semana 4 Mes 2
		$getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana9
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasH = $getComprasSemanalH->num_rows;

		if($numComprasH > 0){
			$numComprasMes2Sem4 = $numComprasH;
		} else {
			$numComprasMes2Sem4 = 0;
		}

		//Semana 1 Mes 3
		$getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana11
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasI = $getComprasSemanalI->num_rows;

		if($numComprasI > 0){
			$numComprasMes3Sem1 = $numComprasI;
		} else {
			$numComprasMes3Sem1 = 0;
		}

		//Semana 2 Mes 3
		$getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana12
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasJ = $getComprasSemanalJ->num_rows;

		if($numComprasJ > 0){
			$numComprasMes3Sem2 = $numComprasJ;
		} else {
			$numComprasMes3Sem2 = 0;
		}

		//Semana 3 Mes 3
		$getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana13
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasK = $getComprasSemanalK->num_rows;

		if($numComprasK > 0){
			$numComprasMes3Sem3 = $numComprasK;
		} else {
			$numComprasMes3Sem3 = 0;
		}

		//Semana 4 Mes 3
		$getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana14
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasL = $getComprasSemanalL->num_rows;

		if($numComprasL > 0){
			$numComprasMes3Sem4 = $numComprasL;
		} else {
			$numComprasMes3Sem4 = 0;
		}
	} elseif($MesActual < 10){
		$semana1 = "feccap >= '$year-07-01' AND feccap <= '$year-07-07' ";
		$semana2 = "feccap >= '$year-07-08' AND feccap <= '$year-07-14' ";
		$semana3 = "feccap >= '$year-07-15' AND feccap <= '$year-07-21' ";
		$semana4 = "feccap >= '$year-07-21' AND feccap <= '$year-07-28' ";
		$semana5 = "feccap >= '$year-07-29' AND feccap <= '$year-07-31' ";
		$semana6 = "feccap >= '$year-08-01' AND feccap <= '$year-08-07' ";
		$semana7 = "feccap >= '$year-08-08' AND feccap <= '$year-08-14' ";
		$semana8 = "feccap >= '$year-08-15' AND feccap <= '$year-08-21' ";
		$semana9 = "feccap >= '$year-08-21' AND feccap <= '$year-08-28' ";
		$semana10 = "feccap >= '$year-08-29' AND feccap <= '$year-08-31' ";
		$semana11 = "feccap >= '$year-09-01' AND feccap <= '$year-09-07' ";
		$semana12 = "feccap >= '$year-09-08' AND feccap <= '$year-09-14' ";
		$semana13 = "feccap >= '$year-09-15' AND feccap <= '$year-09-21' ";
		$semana14 = "feccap >= '$year-09-21' AND feccap <= '$year-09-28' ";
		$semana15 = "feccap >= '$year-09-29' AND feccap <= '$year-09-30' ";

		//Semana 1 Mes 1
		$getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana1
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasA = $getComprasSemanalA->num_rows;

		if($numComprasA > 0){
			$numComprasMes1Sem1 = $numComprasA;
		} else {
			$numComprasMes1Sem1 = 0;
		}

		//Semana 2 Mes 1
		$getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana2
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasB = $getComprasSemanalB->num_rows;

		if($numComprasB > 0){
			$numComprasMes1Sem2 = $numComprasB;
		} else {
			$numComprasMes1Sem2 = 0;
		}

		//Semana 3 Mes 1
		$getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana3
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasC = $getComprasSemanalC->num_rows;

		if($numComprasC > 0){
			$numComprasMes1Sem3 = $numComprasC;
		} else {
			$numComprasMes1Sem3 = 0;
		}

		//Semana 4 Mes 1
		$getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana4
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasD = $getComprasSemanalD->num_rows;

		if($numComprasD > 0){
			$numComprasMes1Sem4 = $numComprasD;
		} else {
			$numComprasMes1Sem4 = 0;
		}

		//Semana 1 Mes 2
		$getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana6
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasE = $getComprasSemanalE->num_rows;

		if($numComprasE > 0){
			$numComprasMes2Sem1 = $numComprasE;
		} else {
			$numComprasMes2Sem1 = 0;
		}

		//Semana 2 Mes 2
		$getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana7
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasF = $getComprasSemanalF->num_rows;

		if($numComprasF > 0){
			$numComprasMes2Sem2 = $numComprasF;
		} else {
			$numComprasMes2Sem2 = 0;
		}

		//Semana 3 Mes 2
		$getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana8
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasG = $getComprasSemanalG->num_rows;

		if($numComprasG > 0){
			$numComprasMes2Sem3 = $numComprasG;
		} else {
			$numComprasMes2Sem3 = 0;
		}

		//Semana 4 Mes 2
		$getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana9
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasH = $getComprasSemanalH->num_rows;

		if($numComprasH > 0){
			$numComprasMes2Sem4 = $numComprasH;
		} else {
			$numComprasMes2Sem4 = 0;
		}

		//Semana 1 Mes 3
		$getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana11
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasI = $getComprasSemanalI->num_rows;

		if($numComprasI > 0){
			$numComprasMes3Sem1 = $numComprasI;
		} else {
			$numComprasMes3Sem1 = 0;
		}

		//Semana 2 Mes 3
		$getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana12
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasJ = $getComprasSemanalJ->num_rows;

		if($numComprasJ > 0){
			$numComprasMes3Sem2 = $numComprasJ;
		} else {
			$numComprasMes3Sem2 = 0;
		}

		//Semana 3 Mes 3
		$getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana13
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasK = $getComprasSemanalK->num_rows;

		if($numComprasK > 0){
			$numComprasMes3Sem3 = $numComprasK;
		} else {
			$numComprasMes3Sem3 = 0;
		}

		//Semana 4 Mes 3
		$getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana14
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasL = $getComprasSemanalL->num_rows;

		if($numComprasL > 0){
			$numComprasMes3Sem4 = $numComprasL;
		} else {
			$numComprasMes3Sem4 = 0;
		}
	} elseif($MesActual > 9){
		$semana1 = "feccap >= '$year-10-01' AND feccap <= '$year-10-07' ";
		$semana2 = "feccap >= '$year-10-08' AND feccap <= '$year-10-14' ";
		$semana3 = "feccap >= '$year-10-15' AND feccap <= '$year-10-21' ";
		$semana4 = "feccap >= '$year-10-21' AND feccap <= '$year-10-28' ";
		$semana5 = "feccap >= '$year-10-29' AND feccap <= '$year-10-31' ";
		$semana6 = "feccap >= '$year-11-01' AND feccap <= '$year-11-07' ";
		$semana7 = "feccap >= '$year-11-08' AND feccap <= '$year-11-14' ";
		$semana8 = "feccap >= '$year-11-15' AND feccap <= '$year-11-21' ";
		$semana9 = "feccap >= '$year-11-21' AND feccap <= '$year-11-28' ";
		$semana10 = "feccap >= '$year-11-29' AND feccap <= '$year-11-30' ";
		$semana11 = "feccap >= '$year-12-01' AND feccap <= '$year-12-07' ";
		$semana12 = "feccap >= '$year-12-08' AND feccap <= '$year-12-14' ";
		$semana13 = "feccap >= '$year-12-15' AND feccap <= '$year-12-21' ";
		$semana14 = "feccap >= '$year-12-21' AND feccap <= '$year-12-28' ";
		$semana15 = "feccap >= '$year-12-29' AND feccap <= '$year-12-31' ";

		//Semana 1 Mes 1
		$getComprasSemanalA = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana1
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasA = $getComprasSemanalA->num_rows;

		if($numComprasA > 0){
			$numComprasMes1Sem1 = $numComprasA;
		} else {
			$numComprasMes1Sem1 = 0;
		}

		//Semana 2 Mes 1
		$getComprasSemanalB = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana2
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasB = $getComprasSemanalB->num_rows;

		if($numComprasB > 0){
			$numComprasMes1Sem2 = $numComprasB;
		} else {
			$numComprasMes1Sem2 = 0;
		}

		//Semana 3 Mes 1
		$getComprasSemanalC = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana3
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasC = $getComprasSemanalC->num_rows;

		if($numComprasC > 0){
			$numComprasMes1Sem3 = $numComprasC;
		} else {
			$numComprasMes1Sem3 = 0;
		}

		//Semana 4 Mes 1
		$getComprasSemanalD = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana4
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasD = $getComprasSemanalD->num_rows;

		if($numComprasD > 0){
			$numComprasMes1Sem4 = $numComprasD;
		} else {
			$numComprasMes1Sem4 = 0;
		}

		//Semana 1 Mes 2
		$getComprasSemanalE = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana6
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasE = $getComprasSemanalE->num_rows;

		if($numComprasE > 0){
			$numComprasMes2Sem1 = $numComprasE;
		} else {
			$numComprasMes2Sem1 = 0;
		}

		//Semana 2 Mes 2
		$getComprasSemanalF = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana7
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasF = $getComprasSemanalF->num_rows;

		if($numComprasF > 0){
			$numComprasMes2Sem2 = $numComprasF;
		} else {
			$numComprasMes2Sem2 = 0;
		}

		//Semana 3 Mes 2
		$getComprasSemanalG = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana8
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasG = $getComprasSemanalG->num_rows;

		if($numComprasG > 0){
			$numComprasMes2Sem3 = $numComprasG;
		} else {
			$numComprasMes2Sem3 = 0;
		}

		//Semana 4 Mes 2
		$getComprasSemanalH = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana9
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasH = $getComprasSemanalH->num_rows;

		if($numComprasH > 0){
			$numComprasMes2Sem4 = $numComprasH;
		} else {
			$numComprasMes2Sem4 = 0;
		}

		//Semana 1 Mes 3
		$getComprasSemanalI = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana11
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasI = $getComprasSemanalI->num_rows;

		if($numComprasI > 0){
			$numComprasMes3Sem1 = $numComprasI;
		} else {
			$numComprasMes3Sem1 = 0;
		}

		//Semana 2 Mes 3
		$getComprasSemanalJ = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana12
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasJ = $getComprasSemanalJ->num_rows;

		if($numComprasJ > 0){
			$numComprasMes3Sem2 = $numComprasJ;
		} else {
			$numComprasMes3Sem2 = 0;
		}

		//Semana 3 Mes 3
		$getComprasSemanalK = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana13
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasK = $getComprasSemanalK->num_rows;

		if($numComprasK > 0){
			$numComprasMes3Sem3 = $numComprasK;
		} else {
			$numComprasMes3Sem3 = 0;
		}

		//Semana 4 Mes 3
		$getComprasSemanalL = $getConnection->query("SELECT docid, tipo, feccap, feccan, clienteid
															FROM doc
															where $semana14
															AND feccan = 0
															AND (
																	tipo = 'F'
																	OR tipo = 'R'
																)
															AND clienteid = $id");
		$numComprasL = $getComprasSemanalL->num_rows;

		if($numComprasL > 0){
			$numComprasMes3Sem4 = $numComprasL;
		} else {
			$numComprasMes3Sem4 = 0;
		}
	}
  	
	// Mes y Días
	$buscarMes=new DateTime();
	$mesNum = $buscarMes->format('m');
	switch ($mesNum) {
		case 1:
			$mes='Enero';
			$diasMes = 31;
			break;
		case 2:
			$mes='Febrero';
			$diasMes = 28;
			break;
		case 3:
			$mes='Marzo';
			$diasMes = 31;
			break;
		case 4:
			$mes='Abril';
			$diasMes = 30;
			break;
		case 5:
			$mes='Mayo';
			$diasMes = 31;
			break;
		case 6:
			$mes='Junio';
			$diasMes = 30;
			break;
		case 7:
			$mes='Julio';
			$diasMes = 31;
			break;
		case 8:
			$mes='Agosto';
			$diasMes = 31;
			break;
		case 9:
			$mes='Septiembre';
			$diasMes = 30;
			break;
		case 10:
			$mes='Octubre';
			$diasMes = 31;
			break;
		case 11:
			$mes='Noviembre';
			$diasMes = 30;
			break;
		case 12:
			$mes='Diciembre';
			$diasMes = 31;
			break;
	}

	$print .=												'<h4 class="text-center">Compras Totales</h4>
															<p class="text-center">$ 20,000.00 MXN Minimo</p>
															<p id="total" style="display: none;">'.$total.'</p>
															<canvas id="comprasTri"></canvas>
														</div>
														<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12" id="asignar">
															<h4 class="text-center">Facturas Vencidas</h4>
															<p class="text-center">No debe tener ninguna vencida</p>
															<p style="display:none;" id="faltaVenTota">$faltaVenTota</p>
															<div class="row">
																<div class="col-sm-12">
																	<p style="text-align:center;font-size:10em;font-weight:bold;color: #F02C2C !important; margin-top: 30px;">'.$numeroVeces.'</p>
																</div>
																<div style="display:none;">
																	<canvas id="facturasTri"></canvas>
																</div>
															</div>
														</div>
														<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
															<h4 class="text-center">Compras Semanales</h4>
															<p id="mesNum" style="display: none;">'.$mesNum.'</p>
															<p id="numComprasMes1Sem1" style="display:none;">'.$numComprasMes1Sem1.'</p>
															<p id="numComprasMes1Sem2" style="display:none;">'.$numComprasMes1Sem2.'</p>
															<p id="numComprasMes1Sem3" style="display:none;">'.$numComprasMes1Sem3.'</p>
															<p id="numComprasMes1Sem4" style="display:none;">'.$numComprasMes1Sem4.'</p>
															<p id="numComprasMes2Sem1" style="display:none;">'.$numComprasMes2Sem1.'</p>
															<p id="numComprasMes2Sem2" style="display:none;">'.$numComprasMes2Sem2.'</p>
															<p id="numComprasMes2Sem3" style="display:none;">'.$numComprasMes2Sem3.'</p>
															<p id="numComprasMes2Sem4" style="display:none;">'.$numComprasMes2Sem4.'</p>
															<p id="numComprasMes3Sem1" style="display:none;">'.$numComprasMes3Sem1.'</p>
															<p id="numComprasMes3Sem2" style="display:none;">'.$numComprasMes3Sem2.'</p>
															<p id="numComprasMes3Sem3" style="display:none;">'.$numComprasMes3Sem3.'</p>
															<p id="numComprasMes3Sem4" style="display:none;">'.$numComprasMes3Sem4.'</p>
															<canvas id="barChart"></canvas>
														</div>
														<script src="../intranet/dist/js/pages/dashboard2.js"></script>
													</div>
													<!-- /.box-body -->
												</div>
												<!-- /.col -->
											</div>
											<!-- /.row -->
										</div>
										<!-- ./box-body -->
										<div class="box-footer">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">';
	if($total >= 20000){
		$print .=				  					'<div class="description-block border-right">
														<span class="description-percentage text-green"><i class="fa fa-check"></i> Aprobando</span>';
	} else {
		$print .=				  					'<div class="description-block border-right">
														<span class="description-percentage text-yellow"><i class="fa fa-times"></i> Reprobando</span>';
	}

	$print .=											'<h5 class="description-header">Sus compras trimestrales debe ser mayor o igual a $20,000.00 pesos.</h5>
														<span class="description-text">No entran códigos que inicien con 8/5/6, pero si entran de la marca Klintek</span>
							  						</div>
							  						<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
								  					<div class="description-block border-right">';
	if($numeroVeces == 0){
		$print .=				  						'<span class="description-percentage text-green"><i class="fa fa-check"></i> Aprobando</span>';
	} else {
		$print .=				  						'<span class="description-percentage text-yellow"><i class="fa fa-times"></i> Reprobando</span>';
	}
	$print .=											'<h5 class="description-header">No tener ninguna factura vencidad antes de que termine el trimestre.</h5>
														<span class="description-text">Excepto si el último día de pago cae en domingo, se pasa al día siguiente.</span>
								  					</div>
								  					<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
													<div class="description-block border-right">';
	/*if($numComprasMes1Sem1 >= 2){
		$print .=				  						'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem1 </span>';
		if($numComprasMes1Sem2 >= 2){
			$print .=				  					'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem2 </span>';
			if($numComprasMes1Sem3 >= 2){
				$print .=				  				'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			} else {
				$print .=				  				'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			}
		} else {
			$print .=				  					'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem2 </span>';
			if($numComprasMes1Sem3 >= 2){
				$print .=				  				'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			} else {
				$print .=				  				'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			}
		}
	} else {
		$print .=				  						'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem1 </span>';
		if($numComprasMes1Sem2 >= 2){
			$print .=				  					'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem2 </span>';
			if($numComprasMes1Sem3 >= 2){
				$print .=				  				'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			} else {
				$print .=				  				'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			}
		} else {
			$print .=				  					'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem2 </span>';
			if($numComprasMes1Sem3 >= 2){
				$print .=				  				'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			} else {
				$print .=				  				'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem3 </span>';
				if($numComprasMes1Sem4 >= 2){
					$print .=				  			'<span class="description-percentage text-green"><i class="fa fa-check"></i> Sem4 </span>';
				} else {
					$print .=				  			'<span class="description-percentage text-red"><i class="fa fa-times"></i> Sem4 </span>';
				}
			}
		}
	}*/
	$print .=											'<h5 class="description-header">Debe de tener registrado por lo menos 2 compras por semana distinita.</h5>
														<span class="description-text">No se puede registrar 8 compras al principio o al final del mes.</span>
								  					</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
											</div>
											<!-- /.row -->
											<p class="text-center lead">La información mostrada es de solo carácter informativo y está sujeto a evaluación.</p>
										</div>
										<!-- /.box-footer -->
									</div>
									<!-- /.box -->
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->
					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<div class="col-sm-12 col-md-12 col-lg-8 col-xs-8 col-12">
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
									<div class="box box-warning direct-chat direct-chat-warning">
										<div class="box-header with-border">
											<h3 class="box-title">PROMOTRUPER <b>'.$mes.' '.date('Y').'</b></h3>
											<div class="box-tools pull-right">
												<div id="actualizarCarrito"></div>
								  			</div>
										</div>
										<div class="box-body no-padding">
											<div class="row">
												<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
													<div class="pad" style="overflow-x:auto; height:360px;">';
	if($rol == "DISTRIBUIDOR"){
		$numPrecio = 1;
	} elseif ($rol == "SUBDISTRIBUIDOR"){
		$numPrecio = 2;
	} elseif ($rol == "MAYOREO"){
		$numPrecio = 3;
	}
	$getNumPromo = "SELECT COUNT(i.invdescuento)
						FROM inv i
							JOIN precios pre ON pre.unidadid = i.unibasid
						WHERE i.invdescuento > 0
							AND pre.nprecio = $numPrecio
						ORDER BY i.clvprov";
	$allNumPromo = mysqli_query($getConnection,$getNumPromo);
	// $totalNumberPromo = mysqli_num_rows($allNumPromo);

	if($allNumPromo > 0) {
		$getPromo = "SELECT i.articuloid, i.clave, i.clvprov, img.imagen, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento
						FROM enl
							JOIN inv i ON i.ARTICULOID = enl.articuloID
							JOIN precios pre ON pre.unidadid = enl.articuloID
							JOIN imagenes img ON img.id = enl.imagenID
						WHERE i.invdescuento > 0
							AND pre.nprecio = $numPrecio
						ORDER BY i.invdescuento DESC";
		$executeQuery = $paramDb->Query($getPromo);
		$numRowPromo = $paramDb->NumRows();
		$rows = $paramDb->Rows();
		if($numRowPromo > 0) {
			// $headersPromo = ["Clave", "Código", "Descripción", "Precio", "Descuento", "Precio Promoción", "Agregar"];
			// $classPerColumnPromo = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

			$headersPromo = ["Clave", "Código", "Imagen", "Descripción", "Precio", "Descuento", "Precio Promoción", "Agregar"];
			$classPerColumnPromo = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

			$print .= $paramFunctions->drawTableHeader($headersPromo, $classPerColumnPromo);
			foreach($rows as $row) {
				$clave = $row["clave"];
				$codigo = $row["clvprov"];
				$titulo = $row["descripcio"];
				$precio = $row["precio"];
				$impuesto = $row["pimpuesto"];
				$impuesto = $impuesto / 100;
				$precioConIva = $precio + ($precio * $impuesto);
				$imagen = $row["imagen"];
				$precioFormato = number_format($precioConIva, 2);
				$descPromo = $row["invdescuento"];
				$descPromDec = $descPromo / 100;
				$numDesc = number_format($descPromo);
				$preDesc = $precioConIva - ($precioConIva * $descPromDec);
				$preDescFomrato = number_format($preDesc, 2);

				$paramsPromo = array("productoID"=>$codigo,
								"location"=>"addProduct-to-shoppingcart-partner",
								"url"=>"../php/shopping/shopping.php",
								"booleanResponse"=>true,
								"divResultID"=>"content-shoppingCar-partner",
								"msgSuccess"=>"Producto agregado correctamente",
								"msgError"=>"Error al agregar producto al carrito");
				$paramsSendPromo = json_encode($paramsPromo);				

				if($precio > 0){
					$print .= 								"<tr>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	$clave
																</td>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	$codigo
																</td>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	<img src='../img/img_pro/img/".$imagen."' width='100'/>
																</td>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	$titulo
																</td>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	MX$ $precioFormato
																</td>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	<img src='../img/iconos/".$numDesc."porciento2000x763.png' width='100'/>
																</td>";
					$print .= 									"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
																	<p style='font-weight:bold; color: red;'>MX$ $preDescFomrato</p>
																</td>";
					$print .=   								"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>";
					$print .=     									"<button type='button' class='btn btn-success btnOk' onclick='generalFunctionToRequest($paramsSendPromo)'><i class='fa fa-plus' aria-hidden='true'></i></button>";
					$print .=   								"</td>
															</tr>";
				}
			}
			$print .= 									'</table>';
		} else {
			$print .= 				"<h4>No hay PROMOTRUPER este mes.</h4>";
		}
	}

	$print .=										'</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- MAP & BOX PANE -->
							<div class="box box-success">
								<div class="box-header with-border">
									<h3 class="box-title">Historial</h3>
									<div class="box-tools pull-right">
										<!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
										<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
						  			</div>
								</div>
								<!-- /.box-header -->
								<div class="box-body no-padding">
									<div class="row">
										<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
											<div class="pad" style="overflow-x:auto; height:360px;">';
	$getAllOrders = "SELECT clienteid FROM doc WHERE clienteid = $id";
	$allNumOrders = mysqli_query($getConnection,$getAllOrders);
	$numOrders = mysqli_fetch_row($allNumOrders);

	if($numOrders > 0) {
		$getorders = "SELECT docid, numero, tipo, fecha, total, totalpagado, impuesto, nota, clienteid
		FROM doc
		WHERE (
				tipo = 'F'
				OR tipo = 'R'
			)
			AND clienteid = $id
		ORDER BY fecha DESC";

		$executeQuery = $paramDb->Query($getorders);
		$numRow = $paramDb->NumRows();
		$rows = $paramDb->Rows();
		if($numRow > 0) {
			$headers = ["No. Pedido", "Fecha", "Documento", "Monto", "Pagado", "Saldo", "", "", "Detalles"];
			$classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

			$print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
			foreach($rows as $row) {
				$pedidoID = $row["docid"];
				$fecha = $row["fecha"];
				$referencia = $row["tipo"];
				$saldo = $row["total"];
				$formatoSaldo = number_format($saldo, 2);
				$pagado = $row["totalpagado"];
				$formatoPagado = number_format($pagado, 2);
				$falta = $pagado - $saldo;

				if($falta >= 0){
					$restante = 'Liquidada';
				} elseif($falta < 0){
					$restante = "MX$ ".number_format($falta, 2, '.', ',');
				}

				if($referencia == "F"){
					$referencia = "FACTURA";
				} elseif($referencia == "N") {
					$referencia = "REMISION";
				} elseif ($referencia == "R"){
					$referencia = "NOTA DE CREDITO";
				}

				if($saldo > 0){
					$print .= 				"<tr>";
					$print .= 					"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$pedidoID</td>";
					$print .= 					"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$fecha</td>";
					$print .= 					"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$referencia</td>";
					$print .= 					"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>MX$ $formatoSaldo</td>";
					$print .= 					"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>MX$ $formatoPagado</td>";
					$docID = $row["docid"];
					$pedidoIDPrueba = 1;
					if($restante == 'Liquidada'){
						$print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$restante</td>";
						$print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
													<a href='../php/classes/factura.php?f=$docID&n=$id&u=$clienteID&r=$referencia'>
														<i class='fas fa-file-alt' style='font-size:25px!important;'></i>
													</a>
												</td>";
						$print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'>
													<a href='../php/classes/xml.php?f=$docID'>
														<i class='far fa-file-code' style='font-size:25px!important;'></i>
													</a>
												</td>
												<script defer src='https://use.fontawesome.com/releases/v5.0.8/js/all.js'></script>";
						// $print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'></td>";
						// $print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'></td>";
					} else {
						$print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold; color: white; background-color: #F88686;'>$restante</td>";
						$print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'><a href='../php/classes/factura.php?f=$docID&n=$id&u=$clienteID&r=$referencia'><i class='fa fa-file-pdf-o fa-2x' aria-hidden='true'></i></a></td>";
						$print .= 				"<td class='text-center' style='vertical-align:middle; font-weight:bold;'><a href='../php/classes/xml.php?f=$docID'><i class='fa fa-file-code-o fa-2x' aria-hidden='true'></i></a></td>";
					}
					$print .= 					"<td class='text-center'>
													<a href='#' onclick='showDetail($docID)'>
														<span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
													</a>
												</td>";
					$print .= 				"</tr>";
				}
			}
			$print .= 				'</table>';
		} else {
			$print .= 				"<h4>No tienes ningún pedido</h4>";
		}
	} // end validation num row > 0, do something if doesn't exist order
	$print .=					'</div>
								<!-- Map will be created here
								<div id="world-map-markers" style="height: 325px;"></div> -->
							</div>
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">En Ruta</h3>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			<div class="col-sm-12 col-md-12 col-lg-4 col-xs-4 col-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Pedidos Realizados</h3>
					</div>
					<div class="box-body no-padding">
						<div class="row">
							<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
								<div class="pad" style="overflow-x:auto; height:200px;">';

	$buscarPedidosRealizados = ("SELECT id, fechaPedido, status, folio FROM pedidos WHERE clienteid = $id and status NOT LIKE 'terminado'");
	$queryPedidosRealizados = $getConnection->query($buscarPedidosRealizados);
	$rowsEncontrados = $queryPedidosRealizados->num_rows;
	$montototal = 0;
	if($rowsEncontrados > 0){
		$headers = ["ID", "FOLIO", "FECHA DEL PEDIDO", "ESTATUS", "DETALLES"];
		$classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center"];
		$print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
		while($filaPedidosRealizados = $queryPedidosRealizados->fetch_array()){
			$idPedido = $filaPedidosRealizados[0];
			$fechaDelPedido = $filaPedidosRealizados[1];
			$statusPedido = $filaPedidosRealizados[2];
			$folioPedido = $filaPedidosRealizados[3];
			$print .=						"<tr>
												<td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$idPedido."</td>
												<td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$folioPedido."</td>
												<td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$fechaDelPedido."</td>
												<td class='text-center' style='vertical-align:middle; font-weight:bold;'>".$statusPedido."</td>
												<td class='text-center'>
													<a href='#' onclick='showDetailOrder($idPedido, $numPrecio)'>
													<span class='fa fa-list-alt fa-2x' aria-didden='true'></span>
													</a>
												</td>
											</tr>";
		}
	} else {
			$print .=						"<tr><p><b>Sin pedidos encontrados</b></p></tr>";
	}

	$print .=						'</table>
								</div>
							</div>
						</div>
					</div>
				</div>';

	$inicioFechaCompMesActual = date("Y-m-01");
	$finFechaCompMesActual = date("Y-m-$diasMes");
	$buscarMonto=("SELECT subtotal2 FROM doc
			where clienteid = $id
				AND (
						feccap >= '$inicioFechaCompMesActual'
                		AND feccap <= '$finFechaCompMesActual'
                	)
                AND tipo = 'F'
            ORDER BY docid");
	$queryMonto = $getConnection->query($buscarMonto);
	$montototal = 0;
	while($filaMonto = $queryMonto->fetch_array()){
		$monto = $filaMonto['subtotal2'];
		$montototal += $monto;
	}
	$montoTri = 20000;
	$montoMes = ($montoTri / 3) * 2;
	$porcentaje = ($montototal * 100) / $montoMes;

	$print .=	'<!-- Info Boxes Style 2 -->
				<div class="info-box bg-yellow">
					<span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Compras del Mes</span>
						<span class="info-box-number">$ '.number_format($montototal,2,".",",").'</span>
						<div class="progress">
							<div class="progress-bar" style="width: '.$porcentaje.'%"></div>
						</div>
						<span class="progress-description">
							Total de tus compras en este mes
						</span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
				<div class="box box-default">
					<div class="box-header with-border">
						<h3 class="box-title">Facebook</h3>
					</div>
					<div class="box-body">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12">
							<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FFerremayoristasOlvera%2F&tabs=timeline&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=279354469090128" width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
						</div>
					</div>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	</div>
	<!--<script>
		var facturasVencidas = document.getElementById("numeroVeces").innerHTML;
		var veces = 0;

		if(veces === 0){
			if(facturasVencidas > 0){
			  alert("Tiene facturas vencidas, favor de realizar los pagos correspondientes para que no afecte su historial con nosotros. Gracias");
			  veces = 1;
			}
		}
	</script>-->'; // End div row
	 echo $print;
	 $getConnection->close();
  }

private function saveOrderPartner() {
	$paramDb = new Database();
	$paramFunctions = new Util();
	$getConnection = $paramDb->GetLink();

	// get data from sessions, partner and user
	$shoppingCarPartner = $_SESSION["shoppingCarPartner"];
	$user = $_SESSION["data"];

	$getLastOrderID = "SELECT MAX(id) as lastID FROM pedidos";
	$result = mysqli_query($getConnection,$getLastOrderID);
	$rows = mysqli_fetch_array($result);
	$lastOrderID = $rows[0];
	$lastOrderID = (int)$lastOrderID + 1;
	$currentDate = date("Y-m-d H:i:s");
	try {
		// get from session user
		$clientID = $paramFunctions->sanitize($user["id"]);
		$clientID = $paramDb->SecureInput($clientID);

		$traerDatos = "SELECT d.nombre,co.correo, t.tel
							FROM dom d
								JOIN correos co ON co.clienteid = d.clienteid
								JOIN tel t ON t.clienteid = d.clienteid
							WHERE d.clienteid = $clientID";
		$executeQueryTrearDatos = mysqli_query($getConnection, $traerDatos);
		$row2 = mysqli_fetch_array($executeQueryTrearDatos);

		$email = $paramFunctions->sanitize($row2["correo"]);
		$email = $paramFunctions->specialChars($email);
		$email = $paramDb->SecureInput($email);

		$nombre = $row2["nombre"];
		$tel = $row2["tel"];

		$folio = $paramFunctions->generateRandomString(5, $lastOrderID);
		// insert into pedidos
		$nuevoPedido = "INSERT INTO pedidos (fechaPedido, status, email, folio, clienteID) VALUES ";
		$nuevoPedido .= " ('$currentDate', 'En proceso', '$email', '$folio', $clientID)";
		$executeQueryNewOrder = mysqli_query($getConnection, $nuevoPedido);

		// TODO send Email folio, module done; but problems on server

		if($executeQueryNewOrder) {
			//$getPedidoIDLastInsert = $getConnection->insert_id; // get the last insert
			// array multidimensional that has data to save on db, and true or false if it's string
			$params = [];
			// NOTE It's very IMPORTANT to set arrays on the same consecutive order as put on this content array.
			$elementsID = ["cantidad", "productoID", "pedidoID"];
			$insertDescripcionPedido = "INSERT INTO descripcionPedido (cantidadSolicitada, productoID, pedidoID) VALUES ";
			$values = "";
			// construc string query to execute when end´s loop
			foreach ($shoppingCarPartner as $key) {
				$this->getterDeleteShoppingCarPartner();
				$productID = $key["productID"];
				$quantity = $key["quantity"];

				// constructSql to save descripcionPedido
				// descripcionPedido: productID, cantidad
				$currentParam = array(	array("cantidad" => $quantity, "string" => 'false'),
										array("productoID" => $productID, "string" => 'false'),
										array("pedidoID" => $lastOrderID, "string" => 'false')
									);
				array_push($params, $currentParam);
			}
			$values = $paramFunctions->constructSql($params, $elementsID);
			$insertDescripcionPedido .= $values;
			$executeQueryDP = $paramDb->UpdateDb($insertDescripcionPedido);
		}
	} catch (Exception $e) {
		echo "Problemas al conseguir el último ID de la tabla pedidos: " . $e->getMessage();
	}

	/*se envia correo*/
	require('PHPMailer/class.phpmailer.php');
	require('PHPMailer/class.smtp.php');

	$mensaje = '
	<!DOCTYPE html>
	<html lang="es">
	<head>
	<mtas charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<style>
	.wrapper {width: 100%;}
	#outlook a {padding: 0;}
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
	.ExternalClass {width: 100%;}
	.ExternalClass,
	.ExternalClass p,
	.ExternalClass span,
	.ExternalClass font,
	.ExternalClass td,
	.ExternalClass div {line-height: 100%;}
	#backgroundTable {
	margin: 0;
	Margin: 0;
	padding: 0;
	width: 100% !important;
	line-height: 100% !important;
	}
	img {
	outline: none;
	text-decoration: none;
	-ms-interpolation-mode: bicubic;
	width: auto;
	max-width: 100%;
	clear: both;
	display: block;
	}
	center {
	width: 100%;
	min-width: 580px;
	}
	a img {border: none;}
	p {
	margin: 0 0 0 10px;
	Margin: 0 0 0 10px;
	}
	table {
	border-spacing: 0;
	border-collapse: collapse;
	}
	td {
	word-wrap: break-word;
	-webkit-hyphens: auto;
	-moz-hyphens: auto;
	hyphens: auto;
	border-collapse: collapse !important;
	}

	table, tr, td {
	padding: 0;
	vertical-align: top;
	text-align: left;
	}
	@media only screen {
	html {
	min-height: 100%;
	background: #f3f3f3;
	}
	}

	table.body {
	background: #f3f3f3;
	height: 100%;
	width: 100%;
	}

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
	<p>Hemos recibido un nuevo pedido desde la website para que se surta lo m&aacute;s pronto posible. A continuaci&oacute;n se desgloza los datos.</p>
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
	<p> <strong>Fecha de Pedido</strong><br> '.$currentDate.' </p>
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
	<p><strong>N&uacute;mero de Pedido</strong><br> '.$lastOrderID.' </p>
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
		//$mail->AddAddress('vleal@ferremayoristas.com.mx', 'Victor Leal');
		$mail->AddAddress('contacto@ferremayoristas.com.mx', 'Carlos Ramírez');
		$mail->IsHTML(true);
		$mail->Subject = utf8_decode('NUEVO PEDIDO DE LA TIENDA');
		$mail->Body = $mensaje;
		$mail->Send();

		/*envio a cliente*/
		$rol = $_SESSION["rol"];
		if($rol = "DISTRIBUIDOR"){
			$tipoUser = 1;
		} elseif($rol = "SUBDISTRIBUIDOR"){
			$tipoUser = 2;
		} elseif($rol = "MAYOREO"){
			$tipoUser = 3;
		}

		$rol = $_SESSION["rol"];
		if($rol = "DISTRIBUIDOR"){
			$rolUser = "Distribuidor";
		} elseif($rol = "SUBDISTRIBUIDOR"){
			$rolUser = "Subdistribuidor";
		} elseif($rol = "MAYOREO"){
			$rolUser = "Mayorista";
		}

   		$traerPedido = "SELECT i.clvprov, i.descripcio, dp.cantidadSolicitada, i.invdescuento, p.precio, p.pimpuesto, img.imagen
							FROM inv i
								JOIN descripcionPedido dp ON dp.productoId = i.articuloid
								JOIN precios p ON p.unidadid = i.unibasid
								JOIN imagenes img ON img.codigo = i.clvprov
							WHERE dp.pedidoID = ".$lastOrderID."
								AND p.nprecio = ".$tipoUser."";

		$artPed = mysqli_query($getConnection,$traerPedido);

		$precio_subtotal = 0; // variable para almacenar el subtotal

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
		border-top: 8px solid #C4310F;
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
		<center data-parsed=""> <a href="http://www.ferremayoristas.com.mx/"><img src="http://www.ferremayoristas.com.mx/assets/img/logo2.png" style="width:80%;" align="center" class="float-center"></a> </center>
		<table class="spacer">
		<tbody>
		<tr>
		<td height="16px" style="font-size:16px;line-height:16px;">&#xA0;</td>
		</tr>
		</tbody>
		</table>
		<p> Hola <strong>'.$rolUser.'</strong>,</p>
		<p style="text-align:justify;">Gracias por realizar tu pedido en tu Escritorio Virtual, si necesitas saber el estatus de tu folio, te invitamos a que lo realices en tu Escritorio Virtual en el men&uacute; <b>Pedidos</b> e ingresa tu <b>Folio</b>.</p>
		<p style="text-align:justify;">Informaci&oacute;n de tu pedido:</p>
		<table class="row">
		<tbody>
		<tr>
		<th class="small-12 large-6 columns first">
		  <table>
		  	<thead style="border: 1px black solid; background-color: lightgray;">
				<td>
					<p><b>CLIENTE: '.$nombre.'</b></p>
					<p><b>FOLIO: '.$folio.'</b></p>
					<p><b>FECHA Y HORA DE PEDIDO: '.$currentDate.'</b></p>
				</td>
		  	</thead>
		  	<caption><b>ORDEN DE PEDIDO</b></caption>
		  	<tr>
				<th abbr="Imagen del producto" scope="col" class="small-4" style="text-align:center;"><b>IMAGEN</b></th>
				<th abbr="Datos del producto" scope="col" class="small-8" style="text-align:center;"><b>DATOS</b></th>
			</tr>
		  	<tbody>');
			while($row3 = mysqli_fetch_array($artPed)){
				$codigo = $row3[0];
				$descripcion = $row3[1];
				$cantidad = number_format($row3[2],0,'.',',');
				$precio = $row3[4];
				$precioFor = number_format($precio, 2, '.', ',');
				$desc = $row3[3];
				$desForm = number_format($desc, 2);
				$unidad = "PZ";
				$impuesto = $row3[5];
				$iva = $impuesto / 100;
				$imagen = $row3[6];

				if($desc = 0){
					$importe = $precio * $row3[2];
				} else {
					$des = $desc / 100;
					$importe = $precio - ($precio * $des);
					$importe = $importe * $row3[2];
				}

				$importeForm = number_format($importe, 2, '.', ',');

				$body .= utf8_decode("<tr class='small-12' style='border: 1px black solid;padding: 10px 0 5px 0;'>
										<td class='small-4'>
											<img src='http://www.ferremayoristas.com.mx/tienda/img/img_pro/img/".$imagen."' style='width:100%;margin-top:20px;'/>
											<!--<img src='http://www.ferremayoristas.com.mx/assets/img/logo2.png' style='width:50%;'>-->
										</td>
										<td class='small-8' style='text-align: left;'>
											<h5><b>".$codigo." - ".$descripcion."</b></h5>
											<p>CANT.: ".$cantidad."".$unidad."</p>
											<p>PRECIO: MX$ ".$precioFor." DESC.: ".$desForm." %</p>
											<p>SUBTOTAL: MX$ ".$importeForm."</p>
										</td>
									</tr>");
				//Cálculo del subtotal  
				$precio_subtotal += $importe * $cantidad;
			}
	$precioSubtotalForm = number_format($precio_subtotal, 2, '.', ',');
	$add_iva = $precio_subtotal * 0.16;
	$addIvaForm = number_format($add_iva,2, '.', ',');
	$total_mas_iva = $precio_subtotal + $add_iva; //Sin envio
	$totalMasIvaForm = number_format($total_mas_iva, 2, '.', ',');
	$body .=utf8_decode('<tr class="small-12" style="border: 1px black solid;background-color: lightgray;">
							<td class="small-4" style="text-align:right;">
								<p><b>SubTotal:</b></p>
								<p><b>I.V.A.:</b></p>
								<p><b>Total:</b></p>
							</td>
							<td class="small-8" style="text-align:left;">
								<p><b>MX$ '.$precioSubtotalForm.'</b></p>
								<p><b>MX$ '.$addIvaForm.'</b></p>
								<p><b>MX$ '.$totalMasIvaForm.'</b></p>
							</td>
						</tr>
		</tbody>
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
			$mail->AddAddress('vleal@ferremayoristas.com.mx', 'Gerencia');
			$mail->IsHTML(true);
			$titulo = 'Pedido Levantado con Folio '.$folio;
			$mail->Subject = utf8_decode($titulo);
			$mail->Body = $body;
			$mail->Send();
		} catch (phpmailerException $mail) {
		} catch (Exception $mail) {
		}
	} catch (phpmailerException $mail) {
	} catch (Exception $mail) {
	}
	$getConnection->close();
}

  private function saveAllQuantityShoppinCarPartner($params) {
	 // TODO sanitize
	 $paramFunctions = new Util();
	 $array = $params["updateQuantities"];
	 $shopping = $_SESSION["shoppingCarPartner"];
	 $length = count($shopping);
	 $position = 0;

	 foreach($array as $element) {
		$productID = isset($element['productID']) ? $element['productID']: null;
		$productID = $paramFunctions->sanitize($productID);

		$quantity = isset($element['quantity']) ? $element['quantity']: null;
		$quantity = $paramFunctions->sanitize($quantity);

		if(isset($shopping[$position]["productID"])) {
		  $_SESSION["shoppingCarPartner"][$position]["quantity"] = intval($quantity);
		}
		$position++;
	 }

	 echo $this->getTableShoppingCarPartner();
  }

  private function saveQuantityShoppingCarPartner($params) {
	 $paramFunctions = new Util();
	 $productID = $paramFunctions->sanitize($params["productID"]);
	 $quantity = $paramFunctions->sanitize(intval($params["quantity"]));

	 $shopping = $_SESSION["shoppingCarPartner"];
	 $paramFunctions->updateElementArray($shopping, $productID, $quantity);
  }
}

?>
