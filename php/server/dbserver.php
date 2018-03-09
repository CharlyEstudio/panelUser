<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Consulta de FMO</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="col-md-12">
		<form method="post">
			<div class="form-group">
				<h4>Ingresar Código del Artículo</h4>
				<input type="number" id="codigo" name="codigo" value="" placeholder="">
			</div>
		</form>
	</div>
	<?php
	if (isset($_POST['codigo']) && is_numeric($_POST['codigo'])) {
		$codigo = $_POST['codigo'];
	} else {
		$codigo = 0;
	}

	$fmoServer = new mysqli('192.168.1.250', 'web', 'webfmolvera17', 'datosa');

	if($fmoServer->connect_errno) {
		echo "Error: Fallo al conectarse a MySQL de a: \n";
		echo "Errno: " . $fmoServer->connect_errno . "\n";
		echo "Error: " . $fmoServer->connect_error . "\n";

		exit;
	} else {
		/*echo "Servidor Conectado";*/
	}

	$invFmo = "SELECT articuloid, clave, clvprov, descripcio, codbar, costo, unibasid, invdescuento, dibujo FROM inv WHERE clvprov = $codigo ORDER BY articuloid";

	$result = mysqli_query($fmoServer,$invFmo);
	?>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>TABLA DE INVENTARIO</h4>
			</div>
			<div class="panel-body">
				<table class='table table-striped'>
					<thead>
						<tr>
							<th class='text-center'><strong>ARTICULO ID</strong></th>
							<th class='text-center'><strong>CLAVE</strong></th>
							<th class='text-center'><strong>CODIGO</strong></th>
							<th class='text-center'><strong>DESCRIPCION</strong></th>
							<th class='text-center'><strong>CODBAR</strong></th>
							<th class='text-center'><strong>COSTO</strong></th>
							<th class='text-center'><strong>ID PRECIO - UNIBASID</strong></th>
							<th class='text-center'><strong>DESCUENTO</strong></th>
							<th class='text-center'><strong>DIBUJO</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($row = mysqli_fetch_array($result)){
								$articuloID = $row[0];
								$claveArt = $row[1];
								$codigoArt = $row[2];
								$descripcion = $row[3];
								$codbar = $row[4];
								$costo = $row[5];
								$precioid = $row[6];
								$desc = $row[7];
								$dibujo = $row[8];
								echo 	"<tr>
											<td class='text-center'>".$articuloID."</td>
											<td class='text-center'>".$claveArt."</td>
											<td class='text-center'>".$codigoArt."</td>
											<td>".$descripcion."</td>
											<td class='text-center'>".$codbar."</td>
											<td class='text-center'>".$costo."</td>
											<td class='text-center'>".$precioid."</td>
											<td class='text-center' style='font-weight:bold; color: red;'>".$desc." %</td>
											<td class='text-center'>".$dibujo."</td>
										</tr>";
							}

							$preFmo = "SELECT * FROM precios WHERE unidadid = $articuloID ORDER BY articuloid";
							$result2 = mysqli_query($fmoServer,$preFmo);
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>TABLA DE PRECIOS</h4>
			</div>
			<div class="panel-body">
				<table class='table table-striped'>
					<thead>
						<tr>
							<th><strong>ARTICULO-ID</strong></th>
							<th><strong>UNIDAD-ID</strong></th>
							<th><strong>N-PRECIO</strong></th>
							<th><strong>PRECIO</strong></th>
							<th><strong>P-IMPUESTO</strong></th>
							<th><strong>P-MINIMO</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php
							while($row2 = mysqli_fetch_array($result2)){
								$articuloID2 = $row2[0];
								$unidadid = $row2[1];
								$nprecio = $row2[2];
								$precio = $row2[3];
								$pimpuesto = $row2[4];
								$pminimo = $row2[5];
								echo 	"<tr>
											<td>".$articuloID2."</td>
											<td>".$unidadid."</td>
											<td>".$nprecio."</td>
											<td>".$precio."</td>
											<td>".$pimpuesto."</td>
											<td>".$pminimo."</td>
										</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>TABLA DE UNIDADES</h4>
			</div>
			<div class="panel-body">
				<table class='table table-striped'>
					<thead>
						<tr>
							<th><strong>UNIDAD-ID</strong></th>
							<th><strong>ARTICULO-ID</strong></th>
							<th><strong>N-UNIDAD</strong></th>
							<th><strong>UNIDAD</strong></th>
							<th><strong>U-ACTIVA</strong></th>
							<th><strong>U-EQUIVALE</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$uniFmo = "SELECT * FROM unidades WHERE unidadid = $articuloID ORDER BY articuloid";
							$result3 = mysqli_query($fmoServer,$uniFmo);
							while($row3 = mysqli_fetch_array($result3)){
								$unidadid = $row3[0];
								$articuloID3 = $row3[1];
								$nunidad = $row3[2];
								$unidad = $row3[3];
								$uactiva = $row3[4];
								$uequivale = $row3[5];
								echo 	"<tr>
											<td>".$unidadid."</td>
											<td>".$articuloID3."</td>
											<td>".$nunidad."</td>
											<td>".$unidad."</td>
											<td>".$uactiva."</td>
											<td>".$uequivale."</td>
										</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
<?php
/*$articuloID = $resultado["articuloid"];
$clave = $resultado["clave"];
$descripcion = $resultado["descripcion"];
$codbar = $resultado["codbar"];
$similarid = $resultado["similarid"];
$familiaid = $resultado["familiaid"];
$agruparid = $resultado["agruparid"];
$catalogo = $resultado["catalogo"];
$dibujo = $resultado["dibujo"];
$costo = $resultado["costo"];
$descuentos = $resultado["descuentos"];*/

$fmoServer->close();
?>