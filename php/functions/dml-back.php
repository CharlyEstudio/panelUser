<?php
require_once("../classes/class.queries.php");

class Dml {
  public function addProduct() {
	$dataForm = ["clave", "codigo", "titulo", "mayorista", "distribuidor", "subdistribuidor"]; // get value that user set and process it on js
	$params = array("dataForm"=> $dataForm,
					"location"=>"saveNewProduct-crud",
					"url"=>"../php/product/product.php",
					"booleanResponse"=>true,
					"msgSuccess"=>"Producto guardado correctamente!",
					"msgError"=>"Error al intentar guardar producto");
	$paramsSend = json_encode($params);
//hola
	$print =  "<div class='form-group col-md-12' style='margin: 40px 15px 10px 250px;'>";
	$print .=   "<form class='form-horizontal'>";
	$print .=     "<div class='form-group'>";
	$print .=       "<label for='titulo' class='control-label col-md-1'>Producto:</label>";
	$print .=       "<div class='col-md-8'>";
	$print .=         "<input type='text' class='form-control' name='titulo' id='titulo' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='clave' class='control-label col-md-1'>Clave:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='text' class='form-control' name='clave' id='clave' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='codigo' class='control-label col-md-1'>Código:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='codigo' id='codigo' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='mayorista' class='control-label col-md-1'>Mayorista:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='mayorista' id='mayorista' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='distribuidor' class='control-label col-md-1'>Distribuidor:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='distribuidor' id='distribuidor' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='subdistribuidor' class='control-label col-md-1'>Subdistribuidor:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='subdistribuidor' id='subdistribuidor' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group margin-top'>";
	$print .=       "<div class='col-md-4 text-center'>";
	$print .=         "<button type='button' class='btn btn-success' onclick='generalFunctionToRequest($paramsSend)'>Guardar</button>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=   "<form>";
	$print .= "</div>";

	return $print;
  }

  public function addUser() {
	$queries = new Queries();
	$paramFunctions = new Util();

	$dataForm = ["username",
				  "password",
				  "rol",
				  "nombreCompleto",
				  "clave",
				  "direccion",
				  "rfc",
				  "telefono",
				  "correo"]; // get value that user set and process it on js

	// get all data roles and then send to print options and select by default from database
	$paramsQueries = array("query"=>"SELECT rol FROM roles WHERE rol NOT IN ('mayorista')",
							"column"=>"rol");
	$arregloRoles = $queries->getterGetAllValuesByOnlyColumn($paramsQueries);

	$params = array("dataForm"=> $dataForm,
					"location"=>"saveNewUser-crud",
					"url"=>"../php/user/user.php",
					"booleanResponse"=>true,
					"msgSuccess"=>"Usuario guardado correctamente!",
					"msgError"=>"Error al intentar guardar usuario");
	$paramsSend = json_encode($params);

	$print =  "<div class='form-group col-md-12' style='margin: 40px 15px 10px 250px;'>";
	$print .=   "<form class='form-horizontal'>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='username' class='control-label col-md-1'>Username:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='text' class='form-control' name='username' id='username' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='password' class='control-label col-md-1'>Password:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='password' class='form-control' name='password' id='password' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='rol' class='control-label col-md-1'>Rol:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<select name='rol' id='rol' class='form-control'>";
	$print .=         $paramFunctions->selectDefaultOption('subdistribuidor', $arregloRoles);
	$print .=         "</select>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='nombreCompleto' class='control-label col-md-1'>Nombre:</label>";
	$print .=       "<div class='col-md-6'>";
	$print .=         "<input type='text' class='form-control' name='nombreCompleto' id='nombreCompleto' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='clave' class='control-label col-md-1'>Clave:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='clave' id='clave' data-maxlength='7' />";
	$print .=         "<label>longitud máxima 7</label>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='direccion' class='control-label col-md-1'>Dirección:</label>";
	$print .=       "<div class='col-md-6'>";
	$print .=         "<input type='text' class='form-control' name='direccion' id='direccion' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='rfc' class='control-label col-md-1'>RFC:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='text' class='form-control' name='rfc' id='rfc' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='telefono' class='control-label col-md-1'>Telefono:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='telefono' id='telefono' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='correo' class='control-label col-md-1'>Correo:</label>";
	$print .=       "<div class='col-md-3'>";
	$print .=         "<input type='email' class='form-control' name='correo' id='correo' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group margin-top'>";
	$print .=       "<div class='col-md-4 text-center'>";
	$print .=         "<button type='button' class='btn btn-success' onclick='generalFunctionToRequest($paramsSend)'>Guardar</button>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=   "<form>";
	$print .= "</div>";

	return $print;
  }

  public function drawModalPaymentMethod($params) {
	$modal = '<div class="modal fade" id="modal-payment-method" tabindex="-1" role="dialog"
			   aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Seleccione medio de pago</h4>
						</div>';

	$modal .=           '<div class="modal-body">';

	$modal .=             "<p class='margin-top' id='test'>testing</p>";
	$modal .=             "<div class='radio'>";
	$modal .=               "<label> <input type='radio' name='paymentMethod' id='paymentMethodCash' value='efectivo' onclick='generalFunctionToRequest($paramsSend)'/> Efectivo</label>";
	$modal .=               "<i class='fa fa-money margin-left-2' aria-hidden='true'></i>";
	$modal .=             "</div>";

	$modal .=             "<div class='radio'>";
	$modal .=               "<label> <input type='radio' name='paymentMethod' id='paymentMethodCard' value='tarjeta' onclick='generalFunctionToRequest($paramsSend)'/> Tarjeta</label>";
	$modal .=               "<i class='fa fa-credit-card margin-left-2' aria-hidden='true'></i>";
	$modal .=             "</div>";

	$modal .=            '</div>'; // modal-body

	$modal .=           '<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">
							  Cerrar
							</button>';
	$modal .=           '</div>
					</div>
				</div>
			</div>';
	echo $modal;
  }
  
  public function drawModalProduct($params) {
	$paramDb = new Database();
	$getConnection = $paramDb->GetLink();
	$queries = new Queries();
	$paramFunctions = new Util();
	$productoID = $paramFunctions->sanitize($params["productoID"]);

	$section = $params["section"];

	$conexion=new mysqli("67.227.237.109","zizaram1_fUser",",[uJz^WP6q;U")
            or die('No se pudo conectar: ' . mysql_error());
        $link = $conexion->select_db("zizaram1_ferre") or die('No se pudo seleccionar la base de datos');

	$getIcono = "SELECT promotruperID FROM productos WHERE id = ".$productoID."";
    $paramIcono = mysqli_query($conexion,$getIcono);
    $filaIcono = mysqli_fetch_array($paramIcono);
    $validarIcono = $filaIcono[0];

	if($validarIcono > 0){
		$getproducts = "SELECT i.articuloid, i.descripcio, i.clave, i.clvprov, i.invdescuento, pre.precio
						FROM inv i
							JOIN precios pre ON pre.unidadid = i.unibasid
						WHERE i.clvprov = $productoID
							AND pre.nprecio = 3";

		$parameters = array("query" => $getproducts);
		$row = $queries->getterExecuteGeneralQuery($parameters);

		$id = $row[0]["productoID"]; // Id del Producto - Tabla Producto
		$titulo = $row[0]["titulo"]; // Titulo del Producto - Tabla Producto
		$clave = $row[0]["clave"]; // Clave del producto - Tabla Producto
		$idmarca = $row[0]["marcaID"]; // Id de la Marca - Tabla de Producto
		$codigo = $row[0]["codigo"]; // Codigo del Producto - Tabla Producto

		$idCat = $row[0]["categoID"]; // ID de la categoria - Tabla Categoria
		$imagen = $row[0]["imagen"]; // Imagen del Producto - Tabla Imagenes
		$imgMin = $row[0]["img_min"]; // Imagen Miniatura-GIF del Producto - Tabla Imagenes
		$imgNor = $row[0]["img_nor"]; // Imagen Normal-JPG del Producto - Tabla Imagenes

		$icono = $row[0]["icono"]; // Icono de la Oferta - Tabla Promotruper
		$promoID = $row[0]["promoID"]; // ID del Icono de la oferta - Tabla Promotruper
		$valorDescuento = $row[0]["valorDescuento"]; // Valor del Descuento - Tabla Promotruper

		$logo = $row[0]["logo"]; // Imagen de la Marca - Tabla Marca
		$nomMarca = $row[0]["nombre"]; // Nombre de la Marca - Tabla de Producto
		$unitario = $row[0]['unitario']; // Cantidad Unitario - Tabla Cantidades
		$caja = $row[0]['caja']; // Cantidad Caja - Tabla Cantidades
		$master = $row[0]['master']; // Cantidad Master - Tabla Cantidades
		$categoria = $row[0]['categoria'];
		$categoriaID = $row[0]['categoriaID'];

		$pre = $row[0]["mayorista"]; // Precio Mayorista - Tabla Precios

		if(($promoID == 7) || ($promoID == 0) || ($promoID == 6)) {
			$precio = $pre;
			$subtotal = number_format($precio, 2);
			$sacariva = $precio * 0.16;
			$iva = number_format($sacariva, 2);
			$resultado = $pre + $sacariva;
			$total = number_format($resultado, 2);
		} else {
			$sacarDescuento = $pre * $valorDescuento;
			$precio = $pre + $sacarDescuento;
			$subtotal = number_format($precio, 2);
			$sacariva = $precio*0.16;
			$iva = number_format($sacariva, 2);
			$resultado = $precio + $sacariva;
			$total = number_format($resultado, 2);
		}
	} else {
		$getproducts = "SELECT i.clave, i.clvprov, i.descripcio, i.codbar, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, uni.nunidad
                      FROM inv i
                        JOIN precios pre ON pre.unidadid = i.unibasid
                        JOIN unidades uni ON uni.unidadid = i.unibasid
                      WHERE i.invdescuento > 0
                        AND pre.nprecio = 3
                      ORDER BY i.descripcio";

		$parameters = array("query" => $getproducts);
		$row = $queries->getterExecuteGeneralQuery($parameters);

		$id = $row[0]["unibasid"];
		$titulo = $row[0]["descripcio"];
		$clave = $row[0]["clave"];
		$idmarca = 0;
		$codigo = $row[0]["clvprov"];
		$idCat = 0;
		$imagen = 'product.png';
		$imgMin = 'product.gif';
		$imgNor = 'product.png';

		$logo = '.gif';
		$nomMarca = 'NombreMarca';
		$unitario = 1;
		$caja = 2;
		$master = 3;
		$categoria = 'Categoría';
		$categoriaID = 0;

		$precio = $row[0]["precio"];
		$subtotal = number_format($precio, 2);
		$sacariva = $precio*0.16;
		$iva = number_format($sacariva, 2);
		$resultado = $precio + $sacariva;
		$total = number_format($resultado, 2);
	}

	$location = "'listProducts'";
	$location2 = '"listProducts"';
	


	// TODO Implementaciòn roles de usuario y registrados
	if($section == "productRegisteredUser") {
	  $tipoUsuario = $_SESSION["data"]["rol"];
	  $imagen = "../" . $imagen;
	  $precio = $row[0][$tipoUsuario];
	  $subtotal = number_format($precio, 2);
	}

	$params = array("marcaID" => $idmarca,
					"nombreMarca" => $nomMarca);
	$paramsSend = json_encode($params);

	$modal = "<div class='modal fade' id='modal-product-description' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
				<div class='modal-dialog modal-lg modal-prod'>
					<div class='modal-content'>
						<div class='modal-header' style='display: flex;align-items: center;justify-content: center;'>
							<div class='col-md-7'>
								<h4 id='titulo$id myModalLabel' class='modal-title' style='text-align:left;font-size:1.5em;'><strong>$titulo</strong></h4>
							</div>";
	if ($validarIcono > 0) {
		$modal .=			"<div class='col-md-2'>
								<img src='./img/iconos/".$icono."' alt='' width='110'>
							</div>";
	} else {
		$modal .=			"<div class='col-md-2'></div>";
	}
	$parametros = array("categoriaID" => $categoriaID,
						"imagenNormal" => $imgNor);
	$enviarParametros = json_encode($parametros);
	$modal .=               "<div class='col-md-2'>
								<button type='button' class='btn btn-default' id='closemodal5' data-dismiss='modal'><a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'><img src='./img/marcas/".$logo."' alt='' width='100%' /></a></button>
							</div>
							<div class='col-md-1'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							</div>
						</div>
						<div class='modal-body'>
							<table>
								<tbody>
									<form>
										<tr>
											<td width='100' colspan='6' rowspan='8' class='product-image-20'>
												<div>
													<img src='img/img_pro/img-nor/".$imgNor."' alt='' width='500'/>
												</div>
											</td>
											<td width='500' colspan='6'></td>
											</tr>
											<tr>
											<td colspan='3' rowspan='2'><p class='color2' id ='clave$id'><b>Clave: $clave</b></p></td>
											<td colspan='3' rowspan='2'><p id ='codigo$id' class='color2'><b>Código: $codigo</b></p></td>
										</tr>
										<tr>

										</tr>";
	$params = array("quantityType" => "single",
					"quantity" => $unitario,
					"price"=> $precio,
					"productID" => $id);
	$paramsSend = json_encode($params);
	$modal .=                           "<tr>
											<td colspan='3' style='text-align: left !important;'>
												<div class='radio'>
													<label>
														<input class='radiobtn' type='radio' name='tipocantidad' value='".$unitario."' onclick='enableDisableRadiobuttons($paramsSend)'>
														<span></span>
														<b>Contenido Neto: ".$unitario." Pieza(s)</b>
													</label>
												</div>
											</td>
											<td colspan='3'>
												<div>
													<input type='number' id='single$id' name='quantity$id' value='' class='form-control' placeholder='Pieza(s)' min='1'
													 onblur='calculateTotal($paramsSend)' disabled/>
												</div>
											</td>
										</tr>";
	$params = array("quantityType" => "box",
					"quantity" => $caja,
					"price" => $precio,
					"productID" => $id);
	$paramsSend = json_encode($params);
	$modal .=                           "<tr>
											<td colspan='3' style='text-align: left !important;'>
												<div class='radio'>
													<label>
														<input class='radiobtn' type='radio' name='tipocantidad' value='".$caja."' onclick='enableDisableRadiobuttons($paramsSend)'>
														<span></span>
														<b>Contenido en 1 Caja: ".$caja." Pieza(s)</b>
													</label>
												</div>
											</td>
											<td colspan='3'>
												<div>
													<input type='number' id='box$id' name='quantity$id' value='' class='form-control' placeholder='Caja(s)' min='1'
													 onblur='calculateTotal($paramsSend)' disabled/>
												</div>
											</td>
										</tr>";
	$params = array("quantityType" => "master",
					"quantity" => $master,
					"price" => $precio,
					"productID" => $id);
	$paramsSend = json_encode($params);                                        
	 $modal .=                          "<tr>
											<td colspan='3' style='text-align: left !important;'>
												<div class='radio'>
													<label>
														<input class='radiobtn' type='radio' name='tipocantidad' value='".$master."' onclick='enableDisableRadiobuttons($paramsSend)'>
														<span></span>
														<b>Contenido en 1 Master: ".$master." Pieza(s)</b>
													</label>
												</div>
											</td>
											<td colspan='3'>
												<div>
													<input type='number' id='master$id' name='quantity$id' value='' class='form-control' placeholder='Master(s)' min='1'
													 onblur='calculateTotal($paramsSend)' disabled/>
												</div>
											</td>
										</tr>
										<tr>";
	$modal .=								"<td colspan='3'>
												<p id ='subtotal$id'><b>Precio Neto: $ ".$subtotal."MXN*</b></p>
												<p style='display:none;' id ='sub$id'>".$subtotal."</p>
											</td>";
	$modal .=								"<td colspan='3' rowspan='3'>
												<!--<button type='button' class='btn-agregar' id='addCar$id' name='addCar$id' class='btn btn-secondary margin-top-3' data-toggle='tooltip' title='Agregar producto a carrito!' onclick='addShoppingCar(".$id.", ".$location2.", ".$unitario.", ".$caja.", ".$master.")'>Agregar</button>-->
											</td>
										</tr>";
	$modal .=                           "<tr>
											<td colspan='3'>
												<p id='impuesto$id'><b>I.V.A.: $ ".$iva."MXN</b></p>
											</td>
										</tr>
										<tr>
											<td colspan='6'>
												<div class='col-md-12'>
													<div class='col-md-6'>
														<a href='#'><img style='border:1px solid #C5C5C5' src='img/img_pro/img-min/".$imgMin."' alt='...'  width='50%'/></a>
													</div>
													<div class='col-md-6'>
														<a href='#'><img style='border:1px solid #C5C5C5' src='img/img_pro/img-min/".$imgMin."' alt='...' width='50%'/></a>
													</div>
												</div>
												<div class='col-md-12' style='padding:5px 0 0 0'>
													<p><a href='#'>Política de Garantías</a></p>
												</div>
											</td>
											<td colspan='3'>
												<p class='color' id='total$id'><b style='font-size:2em'>$ ".$total."MXN</b></p>
												<p>Pesos Mexcianos</p>
											</td>
										</tr>
										<tr>
											<td colspan='12' style='text-align:left;'>
												<div class='col-md-12' style='padding:0;'>
													<div class='col-md-7' style='padding:0;'>
														<p><b>Envío sin Costo en compras mayores de $ 100.00 MNX dentro de la zona de Querétaro.</b></p>
													</div>
													<div class='col-md-5' style='text-align:left;padding:0;'>
														Categoria: <button type='button' id='closemodal' data-dismiss='modal'><a style='font-size:1em;' href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy($enviarParametros)'>".$categoria."</a></button>
													</div>
												</div>
												<div class='col-md-12' style='padding:0;'>
													<hr style='padding:0;margin:2px 0 5px 0;'>
													<h4 style='margin:0 0 15px 0;'><strong>También te puede interesar:</strong></h4>
												</div>
											</td>
										</tr>
										<tr>
											<td colspan='12'>
												<div class='col-md-12'>";
/*    $para = array("productoID" => $id,
				"nombreProducto" => $titulo);
	$paraSend = json_encode($para);*/

	$verCoincidencias = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.codbar, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, uni.nunidad
                      FROM inv i
                        JOIN precios pre ON pre.unidadid = i.unibasid
                        JOIN unidades uni ON uni.unidadid = i.unibasid
                      WHERE i.invdescuento > 0
                        AND pre.nprecio = 3
                      ORDER BY rand() LIMIT 0,4";

	$executeQuery = $paramDb->Query($verCoincidencias);
	if($executeQuery === false) {
	  echo "error-query";
	  return false;
	}

	$numRow = $paramDb->NumRows();
	$rows = $paramDb->Rows();
	$getConnection->close();

	$indce = 0;

	foreach($rows as $row) {
		$id2 = $row["articuloid"];
		$titulo2 = $row["descripcio"];
		$imgMin2 = 'product.gif';
		$indce++;

		$para = array("productoID" => $id2,
				"nombreProducto" => $titulo2);
		$paraSend = json_encode($para);
	
		$modal .=                                   "<div class='col-md-3'>
														<div class='col-md-12'>
															<button type='button' class='btn btn-default' id='closemodal".$indce."' data-dismiss='modal'><a href='#' id='clickProductInterestBy' onclick='clickProductInterestBy($paraSend)'><img src='img/img_pro/img-min/".$imgMin2."' alt='...'  width='100'/></a></button>
														</div>
														<div class='col-md-12'>
															<p style='font-size:.8em;' class='margin-top text-center' id='titulo$id'><strong>$titulo2</strong></p>
														</div>
													</div>";
	}
	$modal .=                                   "</div>
											</td>
										</tr>
										<tr>";
	if($validarIcono > 0) {
		$modal .=							"<td colspan='12'>
												<hr>
													<h4><strong>*El precio reflejado ya es con descuento, y solo es válido para todo este mes.</strong></h4>
											</td>";
	} else {
		$modal .=							"<td colspan='12'>
												<hr>
												<h4><strong>* Precios sujetos a cambio sin previo aviso.</strong></h4>
											</td>";
	}
	$modal .=							"</tr>
									</form>
								</tbody>
							</table>
						</div>
					</div>    
				</div>
			</div>";
	echo $modal;
  }

  public function updateProduct($params) {
	$rows = $params["rows"];
	$dataForm = ["clave", "codigo", "titulo", "mayoreo", "distribuidor", "subdistribuidor"]; // get value that user set and process it on js

	$productoID = $rows[0]["productoID"];
	$titulo = $rows[0]["titulo"];
	$clave = $rows[0]["clave"];
	$codigo = $rows[0]["codigo"];
	$publico = $rows[0]["mayorista"];
	$constructor = $rows[0]["distribuidor"];
	$socio = $rows[0]["subdistribuidor"];

	// send value from database to update only value that user change on form
	$dataDB = array("clave"=>$clave,
					"codigo"=>$codigo,
					"titulo"=>$titulo,
					"mayorista"=>$mayorista,
					"distribuidor"=>$distribuidor,
					"subdistribuidor"=>$subdistribuidor);

	$params = array("elementID"=>$productoID,
					"dataForm"=> $dataForm,
					"dataDB"=> $dataDB,
					"location"=>"setProduct-to-update-crud",
					"url"=>"../php/product/product.php",
					"booleanResponse"=>true,
					"msgSuccess"=>"Producto actualizado correctamente!",
					"msgError"=>"Error al intentar actualizar producto");
	$paramsSend = json_encode($params);


	$print =  "<div class='form-group col-md-12' style='margin: 40px 15px 10px 250px;'>";
	$print .=   "<form class='form-horizontal'>";
	$print .=     "<div class='form-group'>";
	$print .=       "<label for='titulo' class='control-label col-md-1'>Producto:</label>";
	$print .=       "<div class='col-md-8'>";
	$print .=         "<input type='text' class='form-control' name='titulo' id='titulo' value='$titulo' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='clave' class='control-label col-md-1'>Clave:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='text' class='form-control' name='clave' id='clave' value='$clave' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='codigo' class='control-label col-md-1'>Código:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='codigo' id='codigo' value='$codigo' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='mayorista' class='control-label col-md-1'>Mayorista:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='mayorista' id='mayorista' value='$mayorista' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='distribuidor' class='control-label col-md-1'>Distribuidor:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='distribuidor' id='distribuidor' value='$distribuidor' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='subdistribuidor' class='control-label col-md-1'>Subdistribuidor:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='subdistribuidor' id='subdistribuidor' value='$subdistribuidor' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group margin-top'>";
	$print .=       "<div class='col-md-4 text-center'>";
	$print .=         "<button type='button' class='btn btn-success' onclick='generalFunctionToRequest($paramsSend)'>Guardar</button>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=   "<form>";
	$print .= "</div>";

	return $print;
  }

  public function updateTables() {
	$params = array("location"=>"uploadCsv",
					"extension"=>"csv",
					"directory"=>"../../assets/csv/",
					"url"=>"../php/general/upload.php",
					"msgSuccess"=>"El archivo se ha subido correctamente.",
					"msgError"=>"Ha ocurrido un error al subir archivo");
	$paramsCsvSend = json_encode($params);

	$print = "<div class='row' style='margin: 40px 15px 10px 250px;'>";

	$print .=   "<div class='col-md-6'>";
	$print .=     "<div class='panel panel-default'>";
	$print .=       "<div class='panel-heading'>";
	$print .=         "<h3 class='panel-title'><i class='fa fa-bar-chart-o fa-fw'></i>Actualizar precios y productos</h3>";
	$print .=       "</div>"; // panel-heading

	$print .=       "<div class='panel-body'>";

	$print .=         "<form enctype='multipart/form-data' id='frmUpdateTables' class='formulario'>";
	$print .=           "<div class='form-group col-md-11'>";

	$print .=              "<div class='col-md-10'>";
	$print .=               "<select class='form-control' id='tableUploadCsv' name='tableUploadCsv'>";
	$print .=                 "<option value='empty'>Elige una tabla</option>";
	$print .=                 "<option value='precios'>Precios</option>";
	$print .=                 "<option value='productos'>Productos</option>";
	$print .=               "</select>";

	$print .=               "<label class='btn btn-default btn-file'>";
	$print .=                 "Seleccionar CSV <input type='file' name='csvFile' id='csvFile' style='display: none;' onchange='listenerCsvFile();' />";
	$print .=               "</label>";
	$print .=               "<button type='button' class='btn btn-default' name='uploadCsv' id='uploadCsv' onclick='loadFile($paramsCsvSend);'>Cargar datos</button>";
	$print .=               "<div class='messagesUploadFile'></div>";

	$print .=             "<h4 class='margin-top'>ESPECIFICACIONES:</h4>";
	$print .=             "<p>Nombre de archivo: (precios.csv, productos.csv)</p>";
	$print .=             "<p>Estructura de archivo: (Celdas separadas por tabulador, precio sin signo de peso $ sólo número con decimales, utilzar comillas simples ' en vez de \" comillas dobles)</p>";
	$print .=             "<input type='hidden' name='params' value='$paramsCsvSend'/>";
	$print .=             "</div>"; // col-md-10

	$print .=           "</div>"; // col-md-11
	$print .=         "</form>";
	$print .=       "</div>"; // panel-body
	$print .=       "</div>"; // panel-default
	$print .=      "</div>"; // col-md-6


	$params["location"] = "uploadPdf";
	$params["extension"] = "pdf";
	$params["directory"] = "../../assets/catalogo/";
	$paramsPdfSend = json_encode($params);

	$print .=   "<div class='col-md-6'>";
	$print .=     "<div class='panel panel-default'>";
	$print .=       "<div class='panel-heading'>";
	$print .=         "<h3 class='panel-title'><i class='fa fa-bar-chart-o fa-fw'></i>Actualizar catálogo</h3>";
	$print .=       "</div>"; // panel-heading

	$print .=       "<div class='panel-body'>";
	$print .=         "<form enctype='multipart/form-data' id='frmUploadCatalogo' class='formulario'>";
	$print .=           "<div class='col-md-10'>";
	$print .=             "<label class='btn btn-default btn-file'>";
	$print .=               "Seleccionar Pdf <input type='file' name='pdfFile' id='pdfFile' style='display: none;' />";
	$print .=             "</label>";
	$print .=             "<button type='button' class='btn btn-default' name='uploadPdf' id='uploadPdf' onclick='loadFile($paramsPdfSend);'>Cargar pdf</button>";
	$print .=             "<h4 class='margin-top'>ESPECIFICACIONES:</h4>";
	$print .=             "<p>Nombre de archivo: (ferremayoristas.pdf, trupper.pdf)</p>";
	$print .=             "<p>Peso máximo: (5 MB)</p>";
	$print .=             "<input type='hidden' name='params' value='$paramsPdfSend'/>";
	$print .=           "</div>"; // col-md-10
	$print .=          "</form>";
	$print .=         "</div>"; // panel-body
	$print .=         "</div>"; // panel-default
	$print .=       "</div>"; // col-md-6

	$print .=   "</div>"; // row


	$params["location"] = "uploadJpg";
	$params["extension"] = "jpg";
	$params["directory"] = "../../img/slider/";
	$paramsJpgSend = json_encode($params);

	$print .= "<div class='row' style='margin: 40px 15px 10px 250px;'>";
	$print .=   "<div class='col-md-12'>";
	$print .=     "<div class='panel panel-default'>";
	$print .=       "<div class='panel-heading'>";
	$print .=         "<h3 class='panel-title'><i class='fa fa-bar-chart-o fa-fw'></i>Actualizar slider</h3>";
	$print .=       "</div>"; // panel-heading

	$print .=       "<div class='panel-body'>";
	$print .=         "<form enctype='multipart/form-data' id='frmUploadSlider' class='formulario'>";
	$print .=           "<div class='col-md-10'>";
	$print .=             "<label class='btn btn-default btn-file'>";
	$print .=               "Seleccionar Imagen <input type='file' name='jpgFile' id='jpgFile' style='display: none;' />";
	$print .=             "</label>";
	$print .=             "<button type='button' class='btn btn-default' name='uploadJpg' id='uploadJpg' onclick='loadFile($paramsJpgSend);'>Cargar imagen</button>";
	$print .=             "<h4 class='margin-top'>ESPECIFICACIONES:</h4>";
	$print .=             "<p>Nombre de imagen: (1.jpg, 2.jpg, 3.jpg, 4.jpg)</p>";
	$print .=             "<p>Dimensiones de imagen: (1280 pixeles X 720 pixeles)</p>";
	$print .=             "<input type='hidden' name='params' value='$paramsJpgSend'/>";
	$print .=           "</div>"; // col-md-10
	$print .=          "</form>";
	$print .=         "</div>"; // panel-body
	$print .=         "</div>"; // panel-default
	$print .=       "</div>"; // col-md-6
	$print .=  "</div>"; // row

	echo $print;
  }

  public function uploadFile($params) {
	// obtenemos el archivo a subir
	$file = $params['file']['name'];

	// comprobamos si existe un directorio para subir el archivo
	// si no es así, lo creamos
	if(!is_dir("../../csv")) {
		mkdir("../../csv", 0777);
	}

	// comprobamos si el archivo ha subido
	if ($file && move_uploaded_file($params['file']['tmp_name'],"../../csv/".$file)) {
	   sleep(3); // retrasamos la petición 3 segundos
	   echo $file; // devolvemos el nombre del archivo para pintar la imagen
	}
  }

  public function updateUser($params) {
	$queries = new Queries();
	$paramFunctions = new Util();

	$rows = $params["rows"];
	$dataForm = ["username",
				  "password",
				  "rol",
				  "nombreCompleto",
				  "clave",
				  "direccion",
				  "rfc",
				  "telefono",
				  "correo"]; // get value that user set and process it on js

	$usuarioID = $rows[0]["usuarioID"];
	$username = $rows[0]["username"];
	$password = $rows[0]["password"];
	$rol = $rows[0]["rol"];
	$nombreCompleto = $rows[0]["nombreCompleto"];
	$clave = $rows[0]["clave"];
	$direccion = $rows[0]["direccion"];
	$rfc = $rows[0]["rfc"];
	$telefono = $rows[0]["telefono"];
	$correo = $rows[0]["correo"];

	// get all data roles and then send to print options and select by default from database
	$paramsQueries = array("query"=>"SELECT rol FROM roles",
							"column"=>"rol");
	$arregloRoles = $queries->getterGetAllValuesByOnlyColumn($paramsQueries);

	// send value from database to update only value that user change on form
	$dataDB = array("username"=>$username,
					"password"=>$password,
					"rol"=>$rol,
					"nombreCompleto"=>$nombreCompleto,
					"clave"=>$clave,
					"direccion"=>$direccion,
					"rfc"=>$rfc,
					"telefono"=>$telefono,
					"correo"=>$correo);

	$params = array("elementID"=>$usuarioID,
					"dataForm"=> $dataForm,
					"dataDB"=> $dataDB,
					"location"=>"setUser-to-update-crud",
					"url"=>"../php/user/user.php",
					"booleanResponse"=>true,
					"msgSuccess"=>"Usuario actualizado correctamente!",
					"msgError"=>"Error al intentar actualizar usuario");
	$paramsSend = json_encode($params);


	$print =  "<div class='form-group col-md-12' style='margin: 40px 15px 10px 250px;'>";
	$print .=   "<form class='form-horizontal'>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='username' class='control-label col-md-1'>Username:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='text' class='form-control' name='username' id='username' value='$username' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='password' class='control-label col-md-1'>Password:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='password' class='form-control' name='password' id='password' value='$password' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='rol' class='control-label col-md-1'>Rol:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<select name='rol' id='rol' class='form-control'>";
	$print .=         $paramFunctions->selectDefaultOption($rol, $arregloRoles);
	$print .=         "</select>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='nombreCompleto' class='control-label col-md-1'>Nombre:</label>";
	$print .=       "<div class='col-md-6'>";
	$print .=         "<input type='text' class='form-control' name='nombreCompleto' id='nombreCompleto' value='$nombreCompleto' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='clave' class='control-label col-md-1'>Clave:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='clave' id='clave' value='$clave' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='direccion' class='control-label col-md-1'>Dirección:</label>";
	$print .=       "<div class='col-md-6'>";
	$print .=         "<input type='text' class='form-control' name='direccion' id='direccion' value='$direccion' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='rfc' class='control-label col-md-1'>RFC:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='text' class='form-control' name='rfc' id='rfc' value='$rfc' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='telefono' class='control-label col-md-1'>Telefono:</label>";
	$print .=       "<div class='col-md-2'>";
	$print .=         "<input type='number' class='form-control' name='telefono' id='telefono' value='$telefono' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group'>";
	$print .=       "<label for='correo' class='control-label col-md-1'>Correo:</label>";
	$print .=       "<div class='col-md-3'>";
	$print .=         "<input type='email' class='form-control' name='correo' id='correo' value='$correo' />";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=     "<div class='form-group margin-top'>";
	$print .=       "<div class='col-md-4 text-center'>";
	$print .=         "<button type='button' class='btn btn-success' onclick='generalFunctionToRequest($paramsSend)'>Guardar</button>";
	$print .=       "</div>";
	$print .=     "</div>";

	$print .=   "<form>";
	$print .= "</div>";

	return $print;

  }


}

?>
