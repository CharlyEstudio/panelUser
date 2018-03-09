<?php
	if(!isset($page)) {
		header("Location: ../../index.php");
	} else {
		require_once("php/class.database.php");
		require_once("php/functions/util.php");
		$paramDb = new Database();
    	$getConnection = $paramDb->GetLink();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="nav-top">
	<div class="col-6">
		<ul class="navbar-nav mr-auto" style="font-size:.7em;">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle marcas" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Truper <span class="sr-only">(current)</span></a>
				<div class="dropdown-menu" style="z-index: 99999;">
					<?php
						$getmarca = "SELECT id, nombre FROM marca ORDER BY importanciaID LIMIT 1,7";

						$exeQuGetPagination = $paramDb->Query($getmarca);
						if($exeQuGetPagination === false) {
							echo "error-query";
							return false;
						}

						$numRow = $paramDb->NumRows();
    					$rows = $paramDb->Rows();

					    foreach($rows as $row){
							$params = array("marcaID" => $row["id"],
											"nombreMarca" => $row["nombre"]);
							$paramsSend = json_encode($params);

							echo "<a class='dropdown-item' href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row["nombre"])."</a>";
						}
					?>
				</div>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle marcas" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Ferremayoristas</a>
				<div class="dropdown-menu" style="z-index: 99999;overflow-y: scroll;height: 300px">
					<?php
						$getmarca = "SELECT id, nombre FROM marca ORDER BY importanciaID LIMIT 8, 35";

						$exeQuGetPagination = $paramDb->Query($getmarca);
						if($exeQuGetPagination === false) {
							echo "error-query";
							return false;
						}

						$numRow = $paramDb->NumRows();
    					$rows = $paramDb->Rows();

					    foreach($rows as $row){
							$params = array("marcaID" => $row["id"],
											"nombreMarca" => $row["nombre"]);
							$paramsSend = json_encode($params);

							echo "<a class='dropdown-item' href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row["nombre"])."</a>";
						}
					?>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle marcas" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Categorias</a>
				<div class="dropdown-menu" style="z-index: 99999;overflow-y: scroll;height: 300px; width: 250px;">
					<form method="POST" accept-charset="utf-8" style="padding: 5px;">
						<input id="buscar-categoria" class="form-control empty" type="text" name="buscarCategoria" placeholder="Buscar categoria" autocomplete="off" onkeyup="buscar()">
					</form>
					<div id="resultadoBusqueda" style="margin-top: 10px;"></div>
					<div>
						<?php
							$getcate = "SELECT id as categoriaID, categoria FROM categoria ORDER BY importanciaID";
							$exeQuGetPagination = $paramDb->Query($getcate);
							if($exeQuGetPagination === false) {
								echo "error-query";
								return false;
							}

							$numRow = $paramDb->NumRows();
	    					$rows = $paramDb->Rows();

							foreach($rows as $row){
								$params = array("categoriaID" => $row["categoriaID"],
												"categoria" => $row["categoria"]);
								$paramsSend = json_encode($params);

								echo "<a class='dropdown-item' href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy($paramsSend)'>".utf8_encode($row["categoria"])."</a>";
							}
						?>
					</div>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link marcas lead" href="http://www.ferremayoristas.com.mx/ferroteca/">Ferroteca (Videos)</a>
			</li>
		</ul>
	</div>
	<div class="col-6">
		<ul class="navbar-nav mr-auto" style="font-size:.7em; float: right;">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle marcas" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Ayuda</a>
				<div class="dropdown-menu" style="z-index: 99999;">
					<a class="dropdown-item" href="#" onclick="showInformation('publicorder');">Mi Pedido</a>
					<a class="dropdown-item" href="#">Envío y Entrega</a>
					<a class="dropdown-item" href="#">Devolciones</a>
					<a class="dropdown-item" href="#">Opciones de Pago</a>
					<a class="dropdown-item" href="http://www.ferremayoristas.com.mx/#contacto">Comunícate con nosotros</a>
				</div>
			</li>
			<li class="nav-item active">
				<a class="nav-link marcas lead" href="login/index.php">Iniciar Sesión <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link marcas lead" href="#"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
			</li>
			<li class="nav-item">
				<a class="nav-link marcas lead" href="#"><img class="bandera" src="../assets/img/mx_png-min.png" alt="México" width="18"></a>
			</li>
		</ul>
	</div>
</nav>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
	<img class="mx-auto d-block" src="../assets/img/logo-min.png" width="200" alt="">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="col-8 collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto justify-content-center ajustar-nav">
				<li class="nav-item active">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/">Inicio<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/nosotros">Nosotros</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/tienda/index.php">Tienda</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/marcas">Marcas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/#promociones">Promociones</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/#cobertura">Cobertura</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/#contacto">Contacto</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/#ubicacion">Ubicación</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://www.ferremayoristas.com.mx/oportunidad">Empleo</a>
				</li>
			</ul>
	</div>
	<div class="col-2 objeto-centro text-center" id="buscador">
		<form class="form-inline" role="search">
			<div class="form-group">
				<input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar" id="findPublicProductBy">
			</div>
			<?php
				// send JSON to get on js and process it request
				$params = array("location"=>"findPublicProduct",
								"url"=>"php/product/product.php",
								"booleanResponse"=>false,
								"divResultID"=>"information",
								"msgSuccess"=>"Ok!",
								"msgError"=>"Error al intentar buscar producto");
				$paramsSend = json_encode($params);
			?>
			<div class="form-group">
				<p style="font-size: 12px;">Buscar por nombre, clave o código</p>
			</div>
		</form>
	</div>
</nav>
<div id='espere'><p>Un momento por favor, estamos procesando su solicitud.<img src='img/img_pro/loading.gif' width='100'/></p></div>
<div id="myLargeModalLabel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body text-center">
				<img class="img-fluid" src="img/img_pro/construccion.jpg" alt="">
				<p class="lead">Toda la información arrojada en este momento es de pruebas, es por ello que no tiene ningun valor comercial por el memento.</p>
				<p class="lead">Para tener mejor experiencia en nuestra tienda, le recomendamos usar desde una PC.</p>
				<!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button> -->
			</div>
		</div>
	</div>
</div>
<?php
	}
?>
