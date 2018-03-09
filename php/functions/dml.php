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
	$alta = $paramFunctions->sanitize($params["alta"]);
	$section = $params["section"];

	$getIcono = "SELECT invdescuento
					FROM inv
					WHERE clvprov = ".$productoID."";
    $paramIcono = mysqli_query($getConnection,$getIcono);
    $filaIcono = mysqli_fetch_array($paramIcono);
    $validarIcono = number_format($filaIcono[0]);

    $date=new DateTime();
	$mesActual = $date->format('Y-m-01');
	$mesActual = strtotime($mesActual);
	$mesActual = number_format($mesActual);
	$mesAlta = strtotime($alta);
	$mesAlta = number_format($mesAlta);

	if($validarIcono > 0){
		$getproducts = "SELECT i.articuloid, i.descripcio, i.clave, i.clvprov, i.invdescuento, pre.precio, pre.pimpuesto, e.promotruperid, e.medida, ma.id as marcaID, ma.nombre, ma.logo, promo.icono, promo.valor, cat.categoria, cat.id as categoriaID
			FROM inv i
		    	JOIN precios pre ON pre.unidadid = i.unibasid
		        JOIN enl e ON e.codigo = i.clvprov
		        JOIN marca ma ON ma.id = e.marcaid
		        JOIN promotruper promo ON promo.id = e.promotruperid
		       	JOIN categoria cat ON cat.id = e.categoriaid
		    WHERE i.clvprov = $productoID
		    	AND pre.nprecio = 3";

		$parameters = array("query" => $getproducts);
		$row = $queries->getterExecuteGeneralQuery($parameters);
		$info = $row;

		$id = $row[0]["articuloid"];
		$titulo = $row[0]["descripcio"];
		$clave = $row[0]["clave"];
		$codigo = $row[0]["clvprov"];
		$precio = $row[0]["precio"];
		$impuesto = $row[0]["pimpuesto"];
		$promoID = $validarIcono;
		$valorDescuento = $row[0]["invdescuento"];
		$icono = number_format($valorDescuento);

		$getAnexo = "SELECT c.unitario, c.caja, c.master, i.imagen
				FROM cantidades c
					JOIN imagenes i ON i.codigo = c.codigo
			    WHERE i.codigo = $productoID";

		$paramAnexo = mysqli_query($getConnection,$getAnexo);
		$filaAnexo = mysqli_fetch_array($paramAnexo);
		$info = $paramAnexo->num_rows;

	    if($info > 0){
			$unitario = $filaAnexo[0];
			$caja = $filaAnexo[1];
			$master = $filaAnexo[2];
			$imagen = $filaAnexo[3];
			$idmarca = $row[0]["marcaID"];
			$nomMarca = $row[0]["nombre"];
			$categoria = $row[0]["categoria"];
			$categoriaID = $row[0]["categoriaID"];
			$logo = $row[0]["logo"];
		} else {
			$unitario = 1;
			$caja = 1;
			$master = 1;
			$imagen = "newpro.gif";;
			$idmarca = 0;
			$nomMarca = 'Producto Nuevo';
			$categoria = 'Sin Categoria';
			$categoriaID = 0;
			$logo = "productonuevo2000x763.png";
		}

		if($promoID > 0) {
			$valorDescuento = $valorDescuento / 100;
			$sacarDescuento = $precio * $valorDescuento;
			$precio = $precio - $sacarDescuento;
			$subtotal = number_format($precio, 2);
			$impuesto = $impuesto / 100;
			$sacariva = $precio * $impuesto;
			$iva = number_format($sacariva, 2);
			$resultado = $precio + $sacariva;
			$total = number_format($resultado, 2);
		} else {
			$subtotal = number_format($precio, 2);
			$impuesto = $impuesto / 100;
			$sacariva = $precio * $impuesto;
			$iva = number_format($sacariva, 2);
			$resultado = $pre + $sacariva;
			$total = number_format($resultado, 2);
		}
	} elseif($validarIcono == 0){
		if($mesAlta >= $mesActual){
			$getproducts = "SELECT i.clave, i.clvprov, i.descripcio, i.codbar, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, uni.nunidad
	                      FROM inv i
	                        JOIN precios pre ON pre.unidadid = i.unibasid
	                        JOIN unidades uni ON uni.unidadid = i.unibasid
	                      WHERE i.clvprov = $productoID
	                        AND pre.nprecio = 3
	                      ORDER BY i.descripcio";

			$parameters = array("query" => $getproducts);
			$row = $queries->getterExecuteGeneralQuery($parameters);
			$info = $row;

			$id = $row[0]["unibasid"];
			$titulo = $row[0]["descripcio"];
			$clave = $row[0]["clave"];
			$codigo = $row[0]["clvprov"];
			$precio = $row[0]["precio"];
			$impuesto = $row[0]["pimpuesto"];
			$subtotal = number_format($precio, 2);
			$impuesto = $impuesto / 100;
			$sacariva = $precio * $impuesto;
			$iva = number_format($sacariva, 2);
			$resultado = $precio + $sacariva;
			$total = number_format($resultado, 2);
			$promoID = $validarIcono;

			$idmarca = 0;
			$imgMin = "newpro.gif";
			$imgNor = "newpro.gif";
			$imagen = "newpro.gif";
			$logo = "productonuevo2000x763.png";
			$nomMarca = "Nuevo";
			$unitario = 1;
			$caja = 1;
			$master = 1;
			$categoria = "Nuevo";
			$categoriaID = "Nuevo";
			$icono = "productonuevo2000x763.png";
		}else{
			$getproducts = "SELECT i.articuloid, i.descripcio, i.clave, i.clvprov, i.invdescuento, pre.precio, pre.pimpuesto, e.promotruperid, e.medida, ma.id as marcaID, ma.nombre, ma.logo, promo.icono, promo.valor, cat.categoria, cat.id as categoriaID
				FROM inv i
			    	JOIN precios pre ON pre.unidadid = i.unibasid
			        JOIN enl e ON e.codigo = i.clvprov
			        JOIN marca ma ON ma.id = e.marcaid
			        JOIN promotruper promo ON promo.id = e.promotruperid
			       	JOIN categoria cat ON cat.id = e.categoriaid
			    WHERE i.clvprov = $productoID
			    	AND pre.nprecio = 3";

			$parameters = array("query" => $getproducts);
			$row = $queries->getterExecuteGeneralQuery($parameters);
			$info = $row;

			$id = $row[0]["articuloid"];
			$titulo = $row[0]["descripcio"];
			$clave = $row[0]["clave"];
			$codigo = $row[0]["clvprov"];
			$precio = $row[0]["precio"];
			$impuesto = $row[0]["pimpuesto"];
			$promoID = $validarIcono;
			$valorDescuento = $row[0]["invdescuento"];
			$idmarca = $row[0]["marcaID"];
			$logo = $row[0]["logo"];
			$nomMarca = $row[0]["nombre"];
			$categoria = $row[0]["categoria"];
			$categoriaID = $row[0]["categoriaID"];
			
			$subtotal = number_format($precio, 2);
			$impuesto = $impuesto / 100;
			$sacariva = $precio * $impuesto;
			$iva = number_format($sacariva, 2);
			$resultado = $precio + $sacariva;
			$total = number_format($resultado, 2);
			$promoID = $validarIcono;

			$getproducts = "SELECT c.unitario, c.caja, c.master, i.imagen
					FROM cantidades c
						JOIN imagenes i ON i.codigo = c.codigo
				    WHERE i.codigo = $productoID";

			$parameters = array("query" => $getproducts);
			$row = $queries->getterExecuteGeneralQuery($parameters);
			$unitario = $row[0]["unitario"];
			$caja = $row[0]["caja"];
			$master = $row[0]["master"];
			$imagen = $row[0]["imagen"];
			$icono = "";
		}
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
						    <div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
						    <div class='row'>
							    <div class='col-12 col-sm-12 col-md-7 col-lg-7 col-xs-7'>
								    <h4 id='titulo$id myModalLabel' class='display-4' style='text-align:left;font-size:1.5em;'><strong>$titulo</strong></h4>
							    </div>";
	$parametros = array("categoriaID" => $categoriaID,
						"imagenNormal" => $imagen);
	$enviarParametros = json_encode($parametros);
	if ($validarIcono > 0) {
		if($info > 0){
		$modal .=			    "<div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2'>
								    <img class='img-fluid' src='./img/iconos/".$icono."porciento2000x763.png' alt='' width='110'>
							    </div>
							    <div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2' id='closemodal5'>
								    <a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'><img class='img-fluid' src='./img/marcas/".$logo."' alt='' width='100%' /></a>
							    </div>";
		} else {
			$modal .=			    "<div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2'>
								    <img class='img-fluid' src='./img/iconos/".$icono."porciento2000x763.png' alt='' width='110'>
							    </div>
							    <div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2' id='closemodal5'>
								    <img class='img-fluid' src='./img/marcas/".$logo."' alt='' width='100%' />
							    </div>";
		}
	} elseif($validarIcono == 0) {
		if($mesAlta < $mesActual){
			$modal .=			"<div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2'>
								    <img class='img-fluid' src='./img/iconos/".$icono."' alt='' width='110'>
						    	</div>
							    <div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2' id='closemodal5'>
								    <a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'><img class='img-fluid' src='./img/marcas/".$logo."' alt='' width='100%' /></a>
							    </div>";
		} else {
			$logo = "productonuevo2000x763.png";
			$modal .=		    "<div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2'></div>
							    <div class='col-5 col-sm-5 col-md-2 col-lg-2 col-xs-2'><img class='img-fluid' src='./img/marcas/".$logo."' alt='' width='100%' /></div>";
		}
	}
	$modal .=                   "<div class='col-2 col-sm-2 col-md-1 col-lg-1 col-xs-1'>
								    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							    </div>
							</div>
							</div>
						</div>
						<div class='modal-body'>
							<div class='container-fluid'>
								<div class='row'>
									<div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xs-6' style='height:410px'>
										<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
											<a href='#' onClick='addModalEffect()'>
												<img class='img-fluid' src='img/img_pro/img/".$imagen."' alt='$titulo'/>
												<i class='fa fa-search-plus fa-lg' aria-hidden='true' style='color: gray !important'></i>
											</a>
											<div id='modalPicture' class='modal fade bd-example-modal-lg' data-backdrop='static' data-keyboard='false' tabindex='-1' role='dialog' aria-labelledby='modalPicture' aria-hidden='true'>
												<div class='modal-dialog modal-lg'>
													<div class='modal-content'>
														<div class='modal-body text-center'>
															<div id='carouselExampleControls' class='carousel slide' data-ride='carousel'>
																<div class='carousel-inner' role='listbox'>";
	$getRelImg = "SELECT imagen, secundaria, cantidad FROM imagenes WHERE codigo = $productoID";
	$relImgEnc = mysqli_query($getConnection,$getRelImg);
    $rowImgEnc = mysqli_fetch_array($relImgEnc);
    $cantImg = $rowImgEnc[2];
    $imgPri = $rowImgEnc[0];
    $imgSec = $rowImgEnc[1];

	for($i=1; $i <= $cantImg; $i++){
		
		if($i == 1){
			$modal .=												"<div class='carousel-item active'>
																		<img class='img-fluid' src='img/img_pro/img/".$imgPri."' width='500' height='500' alt='$titulo'/>
																		<p>".$i."/".$cantImg."</p>
																	</div>";
		} else {
			$modal .=												"<div class='carousel-item'>
																		<img class='img-fluid' src='img/img_pro/img_sec/".$imgSec."+".$i.".jpg' width='500' height='500' alt='$titulo'/>
																		<p>".$i."/".$cantImg."</p>
																	</div>";
		}
	}
	$modal .=													"</div>
																<a class='carousel-control-prev' href='#carouselExampleControls' role='button' data-slide='prev' style='color: #000000 !important'>
																	<span class='carousel-control-prev-icon' aria-hidden='true'><i class='fa fa-chevron-left fa-2x' aria-hidden='true'></i></span>
																	<span class='sr-only'>Anterior</span>
																</a>
																<a class='carousel-control-next' href='#carouselExampleControls' role='button' data-slide='next' style='color: #000000 !important'>
																	<span class='carousel-control-next-icon' aria-hidden='true'><i class='fa fa-chevron-right fa-2x' aria-hidden='true'></i></span>
																	<span class='sr-only'>Siguiente</span>
																</a>
															</div>
														</div>
														<div class='modal-footer text-center' style='display:block !important'>
															<button type='button' class='btn btn-secondary text-center' id='boton-salir' onclick='addModalEffectOf()'>Cerrar</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
											<div class='row'>
												<div class='col-6 col-sm-6 col-md-6 col-lg-6 col-xs-6 text-center'>
													<a href='#'><img class='img-fluid' style='border:1px solid #C5C5C5' src='img/img_pro/img/".$imagen."' alt='...'  width='50%'/></a>
												</div>
												<div class='col-6 col-sm-6 col-md-6 col-lg-6 col-xs-6 text-center'>
													<a href='#'><img class='img-fluid' style='border:1px solid #C5C5C5' src='img/img_pro/img/".$imagen."' alt='...'  width='50%'/></a>
												</div>
											</div>
										</div>-->
									</div>
									<div class='col-12 col-sm-12 col-md-6 col-lg-6 col-xs-6'>
										<div class='row'>
											<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
												<div class='row'>
													<div class='col-6 col-sm-6 col-md-6 col-lg-6 col-xs-6 text-center'>
														<p class='color2' id ='clave$id'><b>Clave: $clave</b></p>
													</div>
													<div class='col-6 col-sm-6 col-md-6 col-lg-6 col-xs-6 text-center'>
														<p id ='codigo$id' class='color2'><b>Código: $codigo</b></p>
													</div>
												</div>
											</div>
											<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
												<div class='row'>
													<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12' style='padding:0;margin:5px 0;'>
														<div class='row'>
															<div class='col-8 col-sm-8 col-md-8 col-lg-8 col-xs-8 radio' style='padding:0'>
																<label style='margin:0'>";
	$params = array("quantityType" => "single",
					"quantity" => $unitario,
					"price"=> $precio,
					"productID" => $id);
	$paramsSend = json_encode($params);
	$modal .=														"<input class='radiobtn' type='radio' name='tipocantidad' value='".$unitario."' onclick='enableDisableRadiobuttons($paramsSend)'>
																	<span></span>
																	<b class='lead' style='font-size:.8em !important;'>Contenido Neto: <strong>".$unitario."</strong> Pieza(s)</b>
																</label>
															</div>
															<div class='col-4 col-sm-4 col-md-4 col-lg-4 col-xs-4' style='padding:0'>
																<input type='number' id='single$id' name='quantity$id' value='' class='form-control' placeholder='Pieza(s)' min='1'
													 onblur='calculateTotal($paramsSend)' disabled/>
															</div>
														</div>
													</div>
													<div class='col-12' style='padding:0;margin:5px 0;'>
														<div class='row'>
															<div class='col-8 col-sm-8 col-md-8 col-lg-8 col-xs-8 radio' style='padding:0'>";
	$params = array("quantityType" => "box",
					"quantity" => $caja,
					"price" => $precio,
					"productID" => $id);
	$paramsSend = json_encode($params);
	$modal .=													"<label style='margin:0'>
																	<input class='radiobtn' type='radio' name='tipocantidad' value='".$caja."' onclick='enableDisableRadiobuttons($paramsSend)'>
																	<span></span>
																	<b class='lead' style='font-size:.8em !important;'>Contenido en 1 Caja: <strong>".$caja."</strong> Pieza(s)</b>
																</label>
															</div>
															<div class='col-4 col-sm-4 col-md-4 col-lg-4 col-xs-4' style='padding:0'>
																<input type='number' id='box$id' name='quantity$id' value='' class='form-control' placeholder='Caja(s)' min='1'
													 onblur='calculateTotal($paramsSend)' disabled/>
															</div>
														</div>
													</div>
													<div class='col-12' style='padding:0;margin:5px 0;'>
														<div class='row'>
															<div class='col-8 col-sm-8 col-md-8 col-lg-8 col-xs-8 radio' style='padding:0'>";
	$params = array("quantityType" => "master",
					"quantity" => $master,
					"price" => $precio,
					"productID" => $id);
	$paramsSend = json_encode($params);
	$modal .=													"<label style='margin:0'>
																	<input class='radiobtn' type='radio' name='tipocantidad' value='".$master."' onclick='enableDisableRadiobuttons($paramsSend)'>
																	<span></span>
																	<b class='lead' style='font-size:.8em !important;'>Contenido en 1 Master: <strong>".$master."</strong> Pieza(s)</b>
																</label>
															</div>
															<div class='col-4 col-sm-4 col-md-4 col-lg-4 col-xs-4' style='padding:0'>
																<input type='number' id='master$id' name='quantity$id' value='' class='form-control' placeholder='Master(s)' min='1'
													 onblur='calculateTotal($paramsSend)' disabled/>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
												<div class='row'>
													<div class='col-6 col-sm-6 col-md-6 col-lg-6 col-xs-6'>
														<div class='row'>
															<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center' style='padding:0'>
																<p class='lead' style='margin:0;padding:0;'>Precio Neto</p>
																<p style='display:none;' id ='sub$id'>".$subtotal."</p>
															</div>
															<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center' style='padding:0'>
																<p id ='subtotal$id'><b class='lead'><strong>$ ".$subtotal."*</strong></b></p>
															</div>
														</div>
													</div>
													<div class='col-6 col-sm-6 col-md-6 col-lg-6 col-xs-6'>
														<div class='row'>
															<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
																<p class='lead' style='margin:0;padding:0;'>I.V.A.</p>
															</div>
															<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
																<p id='impuesto$id'><b class='lead'><strong>$ ".$iva."</strong></b></p>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
												<div class='row'>
													<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
														<p class='color' style='margin:0' id='total$id'><b style='font-size:2em'>$ ".$total."MXN*</b></p>
													</div>
												</div>
											</div>
											<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center' style='margin:5px 0'>
												<!--<button type='button' class='btn-agregar' id='addCar$id' name='addCar$id' class='btn btn-secondary margin-top-3' data-toggle='tooltip' title='Agregar producto a carrito!' onclick='addShoppingCar(".$id.", ".$location2.", ".$unitario.", ".$caja.", ".$master.")'>Agregar</button>-->
											</div>";
	if($categoria == "Nuevo"){
		$modal .=							"<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
												Categoria: <strong>".$categoria."</strong>
											</div>";
	} else {
		if($info > 0){
			$modal .=							"<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
													Categoria: <button type='button' id='closemodal' data-dismiss='modal'><a style='font-size:1em;' href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy($enviarParametros)'>".$categoria."</a></button>
												</div>";
		} else {
			$modal .=							"<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
												Categoria: <strong>Producto Nuevo</strong>
											</div>";
		}
	}
	$modal .=							"</div>
									</div>
									<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
										<p class='lead' style='margin:0;'><b style='font-size:.8em;'>Envío sin Costo en compras mayores de $ 400.00 MXN dentro de la zona de Querétaro.</b></p>
									</div>
								</div>
								<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
									<hr style='margin:5px 0'><h5 style='font-size:.8em !important;'><strong>También te puede interesar:</strong></h5 style='font-size:.8em !important;'>
									<div class='row'>";
	$verCoincidencias = "SELECT i.clvprov, i.descripcio, i.invdescuento
                      FROM inv i
                      	JOIN precios pre ON pre.unidadid = i.unibasid
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

	$indce = 0;

	foreach($rows as $row) {
		$id2 = $row["clvprov"];
		$titulo2 = $row["descripcio"];
		$iconodes = $row["invdescuento"];
		$iconodes = number_format($iconodes);

		$getimg = "SELECT imagen FROM imagenes WHERE codigo = ".$id2."";
		$imgenc = mysqli_query($getConnection,$getimg);
		$filaimg = mysqli_fetch_array($imgenc);
		$imgMin2 = $filaimg[0];
		$indce++;

		if($filaimg > 0) {
			$imgMin2 = $filaimg[0];
		} else {
			$imgMin2 = 'newpro.gif';
		}

		$para = array("productoID" => $id2,
				"nombreProducto" => $titulo2);
		$paraSend = json_encode($para);								

		if($iconodes > 0) {
			$modal .=					"<div class='col-12 col-sm-12 col-md-3 col-lg-3 col-xs-3'>
											<div class='row'>
												<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center' id='closemodal".$indce."'>
													<a data-dismiss='modal' href='#' id='clickProductInterestBy' onclick='clickProductInterestBy($paraSend)'>
														<img class='img-fluid' src='img/img_pro/img/".$imgMin2."' alt='$titulo' style='position:relative !important;' width='100'/>
													</a>
													<img src='img/iconos/".$iconodes."porciento2000x763.png' alt='...' style='width:75px !important;position: absolute !important;margin-left:-40px;'/>
												</div>
												<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12' style='padding:0'>
													<p style='font-size:.7em !important;margin:0;' class='margin-top text-center lead' id='titulo$id'><strong>$titulo2</strong></p>
												</div>
											</div>
										</div>";
		} else {
			$modal .=					"<div class='col-12 col-sm-12 col-md-3 col-lg-3 col-xs-3'>
											<div class='row'>
												<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center'>
													<a id='closemodal".$indce."' data-dismiss='modal' href='#' id='clickProductInterestBy' onclick='clickProductInterestBy($paraSend)'>
														<img src='img/img_pro/img/".$imgMin2."' alt='$titulo'  width='100'/>
													</a>
												</div>
													<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12' style='padding:0'>
														<p style='font-size:.7em !important;margin:0;' class='margin-top text-center lead' id='titulo$id'><strong>$titulo2</strong></p>
													</div>
												</div>
											</div>";
		}
	}
	$modal .=							"</div>
									</div>";
	if($validarIcono > 0) {
	$modal .=					"</div>
								<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
									<hr style='margin:0'>
									<p style='font-size:.7em !important;margin:0;' class='text-center lead'><strong>*El precio reflejado es conforme a PROMOTRUPER, y solo es válido para todo este mes.</strong></p>
								</div>";
	} elseif($validarIcono == 0) {
		if($mesAlta < $mesActual){
			$modal .=		"</div>
							<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
								<hr style='margin:0'>
								<p style='font-size:.8em !important;margin:0;' class='text-center lead'><strong>* Precios sujetos a cambio sin previo aviso.</strong></p>
							</div>";
		} else {
			$modal .=		"</div>
							<div class='col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>
								<hr style='margin:0'>
								<p style='font-size:.8em !important;margin:0;' class='text-center lead'><strong>* Este producto es nuevo y está sujeto a cambio sin previo aviso.></p>
							</div>";
		}
	}
	$modal .=			"</div>
					</div>
				</div>
			</div>"; //fin modal
	echo $modal;
	$getConnection->close();
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
