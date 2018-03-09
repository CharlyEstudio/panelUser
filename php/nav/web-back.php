<?php
	header('Content-Type: text/html; charset=UTF-8');
	$conexion=new mysqli("67.227.237.109","zizaram1_fUser",",[uJz^WP6q;U")
		or die('No se pudo conectar: ' . mysql_error());
	$link = $conexion->select_db("zizaram1_ferre") or die('No se pudo seleccionar la base de datos');

	if(!isset($page)) {
		header("Location: ../../index.php");
	} else {
?>
<nav class="navbar navbar-default navbar-static-top">
	<div class="container">
		<ul class="nav navbar-nav">
			<li class="borde"><a href="#" title="">Truper</a>
				<ul class="submenu">
					<div>
						<?php
							$consulta = "SELECT * FROM marca ORDER BY importanciaID LIMIT 1,7";
							$result = mysqli_query($conexion,$consulta);

							while($row = mysqli_fetch_array($result)){

								$params = array("marcaID" => $row[0],
												"nombreMarca" => $row[2]);
								$paramsSend = json_encode($params);

								echo "<li><a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row[2])."</a></li>";
							}
						?>
					</div>
				</ul>
			</li>
			<li class="borde"><a href="#" title="">Ferremayoristas</a>
				<ul class="submenu" style="margin-left: 0">
					<?php
						$consulta = "SELECT * FROM marca ORDER BY importanciaID LIMIT 8,9";
						$result = mysqli_query($conexion,$consulta);

						while($row = mysqli_fetch_array($result)){

							$params = array("marcaID" => $row[0],
											"nombreMarca" => $row[2]);
							$paramsSend = json_encode($params);

							echo "<li><a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row[2])."</a></li>";
						}
					?>
				</ul>
				<ul class="submenu" style="margin-left: 100px">
					<?php
						$consulta = "SELECT * FROM marca ORDER BY importanciaID LIMIT 17,9";
						$result = mysqli_query($conexion,$consulta);

						while($row = mysqli_fetch_array($result)){

							$params = array("marcaID" => $row[0],
											"nombreMarca" => $row[2]);
							$paramsSend = json_encode($params);

							echo "<li><a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row[2])."</a></li>";
						}
					?>
				</ul>
				<ul class="submenu" style="margin-left: 220px">
					<?php
						$consulta = "SELECT * FROM marca ORDER BY importanciaID LIMIT 26,9";
						$result = mysqli_query($conexion,$consulta);

						while($row = mysqli_fetch_array($result)){

							$params = array("marcaID" => $row[0],
											"nombreMarca" => $row[2]);
							$paramsSend = json_encode($params);

							echo "<li><a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row[2])."</a></li>";
						}
					?>
				</ul>
				<ul class="submenu" style="margin-left: 340px; height: 227px">
					<?php
						$consulta = "SELECT * FROM marca ORDER BY importanciaID LIMIT 35,3";
						$result = mysqli_query($conexion,$consulta);

						while($row = mysqli_fetch_array($result)){

							$params = array("marcaID" => $row[0],
											"nombreMarca" => $row[2]);
							$paramsSend = json_encode($params);

							echo "<li><a href='#' id='clickProductMarcaBy' onclick='clickProductMarcaBy($paramsSend)'>".utf8_encode($row[2])."</a></li>";
						}
					?>
				</ul>
			</li>
			<li class="borde"><a href="../ferroteca.php" title="">Ferroteca</a></li>
			<li class="borde"><a href="#">Categorias</a>
				<ul class="submenu2">
					<div>
						<form method="POST" accept-charset="utf-8">
							<input id="buscar-categoria" class="form-control empty" type="text" name="buscarCategoria" placeholder="Buscar categoria" autocomplete="off" onkeyup="buscar()">
						</form>
					</div>
					<div id="resultadoBusqueda" style="margin-top: 10px;"></div>
					<div>
						<?php
							$consulta = "SELECT * FROM categoria ORDER BY categoria";
							$result = mysqli_query($conexion,$consulta);

							while($row = mysqli_fetch_array($result)){

								$params = array("categoriaID" => $row[0],
												"categoria" => $row[1]);
								$paramsSend = json_encode($params);

								echo "<li><a href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy($paramsSend)'>".utf8_encode($row[1])."</a></li>";
							}
						?>
					</div>
				</ul>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="login/sesion.php">Iniciar Sesión</a></li>
			<li><a href="#">Auyda</a>
				<ul class="submenu">
					<li><a href="#" onclick="showInformation('publicorder');">Mi Pedido</a></li>
					<li><a href="#">Envío y entrega</a></li>
					<li><a href="#">Devoluciones</a></li>
					<li><a href="#">Opciones de pago</a></li>
					<li><a href="#">Comunícate con nosotros</a></li>
				</ul>
			</li>
			<li><a href="#" onclick="showInformation('shopping')"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a></li>
			<li><a href="#"><img class="bandera" src="../..../../assets/img/mx_png.png" alt="México"></a></li>
		</ul>
	</div>
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="sr-only">Menú</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="http://www.ferremayoristas.com.mx/"><img class="logo-principal" src="../..../../assets/img/logo.png" alt="Ferremayoristas Olvera"></a>
	</div>
	<div class="collapse navbar-collapse navbar-ex1-collapse ancho" id="myNavbar">
		<ul class="nav navbar-nav mover">
			<li class="menu"><a href="../index.php">INICIO</a></li>
			<li class="menu"><a href="../nosotros.php">NOSOTROS</a></li>
			<li class="menu"><a href="index.php">TIENDA</a></li>
			<li class="menu"><a href="../marcas.php">MARCAS</a></li>
			<li class="menu"><a href="../index.php#promociones">PROMOCIONES</a></li>
			<li class="menu"><a href="../index.php#cobertura">COBERTURA</a></li>
			<li class="menu"><a href="../index.php#contacto">CONTACTO</a></li>
			<li class="menu"><a href="../index.php#ubicacion">UBICACION</a></li>
		</ul>
		<form id="frmFindPublicProduct" class="navbar-form navbar-right izquierda" role="search">
			<div class="form-group">
				<input type="text" name="findPublicProductBy" id="findPublicProductBy" class="form-control empty" placeholder="Buscar">
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
			<p class="text-center" style="font-size: 12px;">Buscar por nombre, clave o código</p>
		</form>
	</div>
</nav>
<?php
	// Mes y A単o
	$date=new DateTime();
	$result = $date->format('m');
	$year=new DateTime();
	$result2 = $year->format('Y');
	switch ($result) {
		case 1:
			$mes='ENERO';
			break;
		case 2:
			$mes='FEBRERO';
			break;
		case 3:
			$mes='MARZO';
			break;
		case 4:
			$mes='ABRIL';
			break;
		case 5:
			$mes='MAYO';
			break;
		case 6:
			$mes='JUNIO';
			break;
		case 7:
			$mes='JULIO';
			break;
		case 8:
			$mes='AGOSTO';
			break;
		case 9:
			$mes='SEPTIEMBRE';
			break;
		case 10:
			$mes='OCTUBRE';
			break;
		case 11:
			$mes='NOVIEMBRE';
			break;
		case 12:
			$mes='DICIEMBRE';
			break;
	}
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 aviso">
	<div class="container avisos">
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<div class="item active">
					<h1 style="color:red;">NUESTRA TIENDA ESTA EN CONSTRUCCIÓN</h1>
				</div>
				<!-- <div class="item">
					<?php echo "<h1>PROMOCIONES DEL MES DE ".$mes."</h1>" ?>
				</div>
				<div class="item">
					<h1>PROMOCION 1</h1>
				</div>
				<div class="item">
					<h1>PROMOCION 2</h1>
				</div>
				<div class="item">
					<h1>PROMOCION 3</h1>
				</div>
				<div class="item">
					<h1>PROMOCION 4</h1>
				</div> -->
			</div>
			<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
				<i class="fa fa-chevron-left" aria-hidden="true"></i>
			</a>
			<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
				<i class="fa fa-chevron-right" aria-hidden="true"></i>
			</a>
		</div>
	</div>
</div>
<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
	<div class="modal-dialog modal-aviso">
		<div class="modal-content modal-aviso-contenido">
			<div class="modal-body">
				<img src="img/img_pro/construccion-modal.gif" alt="">
			</div>
		</div>
	</div>
</div>
<!-- <div id="carga"></div> -->
<?php
	}
?>
