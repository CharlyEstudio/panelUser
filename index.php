<?php
/*include('php/***.php');*/
header('Content-Type: text/html; charset=UTF-8');
$ip = $_SERVER['REMOTE_ADDR'];
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="es class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="es" class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="es" class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="es" class="ie9"> <![endif]-->
<!--[if IE]><script src="js/matchMedia.min.js"></script>< ![endif]-->
<html class="no-js es_la" xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Sitio web oficial de Ferremayoristas Olvera. Ferremayoristas.com.mx (MX)</title>
	<meta name="robots" content="index,follow" />
	<meta http-equiv="content-language" content="es">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="title" content="Sitio web oficial Ferremayoristas Olvera. Ferremayoristas.com.mx (MX)" />
	<meta property="og:locale" content="es_LA" />
	<meta name="description" content="Ferremayoristas ofrece ideas y soluciones para el hogar y los negocios." />
	<meta name="displayphonenav" content="true" />
	<meta property="og:title" content="Sitio web oficial de Ferremayoristas Olvera" />
	<meta property="og:url" content="http://www.ferremayoristas.com.mx/" />
	<meta property="og:description" content="Compra productos de ferretería para tu negocio, hogar o proyectos." />
	<!-- Favicon -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/img/favicon2.png">
	<link rel="icon" type="image/png" href="assets/img/favicon2.png">
	<!-- CSS's -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../css/style.min.css">
	<!-- Font's -->
	<link href="https://fonts.googleapis.com/css?family=Roboto|Oswald" rel="stylesheet">
	<!-- JS -->
	<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
	<script src="js/vendor/toastr.min.js"></script>
	<script src="js/vendor/lodash-4-15-0.min.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/validations/validation.js"></script>
	<script src="js/main.js"></script>
	<script type="text/javascript">
		var ip = document.getElementById('ip');

		$(document).ready(function(){
			$("#myLargeModalLabel").modal("show");
		});
		setTimeout(()=>{
			$("#myLargeModalLabel").modal("hide");
		}, 5000);
	</script>
	<script>
		$(document).ready(function(){
			$("#modalPicture").modal("hide");
		});
	</script>
	<script>
		$(document).ready(function() {
			$("#resultadoBusqueda").html('<hr><div style="padding:5px;text-align:center;">Sin busqueda</div><hr>');
		});
		function buscar() {
			var textoBusqueda = $("input#buscar-categoria").val();

			if (textoBusqueda != "") {
				$.post("php/busquedas/buscar-categoria.php", {valorBusqueda: textoBusqueda}, function(mensaje){
					$("#resultadoBusqueda").html(mensaje);
				});
			};
		};
	</script>
</head>
<body onload="showInformation('product')">
	<div id="ip" style="display: none"><?php echo $ip; ?></div>
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<?php
		session_start();
		$page = true;
		require_once('php/nav/web.php');
		echo  "<div class='container-fluid col-md-12' id='information' style='padding:0;'></div>";

		if(isset($_GET["op"])) {
			$option = $_GET["op"];
			if($option == "message") {
				// save message, because otherwise i can't show message from other page
				if(isset($_SESSION["data"]["message"])) {
					$data = $_SESSION["data"];
					echo '<script type="text/javascript">
					showMessage("'.$data["message"]["type"].'","'.$data["message"]["msg"].'");
					</script>';
					switch($_SESSION["data"]["message"]["section"]) {
						case "login":
							echo '<script type="text/javascript">
							$("#login-modal").modal("show");
							$("#user").focus();
							</script>';
						break;
					}
					// if message has been displayed, remove from array session; to save other message from other page
					unset($_SESSION["data"]["message"]);
				}
			}
		}
	?>
	<footer class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
		<div class="row">
			<div class="col-sm-12 col-md-3 col-lg-3 col-xs-3 col-12">
				<a class="enlaces-footer" href="http://www.ferremayoristas.com.mx/marcas" title=""><h5>SOCIOS COMERCIALES</h5></a>
				<a class="enlaces-footer" href="#" title="" data-toggle="modal" data-target="#modal-comentarios"><p class="lead">Comentarios sobre el sitio</p></a>
				<div id="modal-comentarios" class="modal fade" role="dialog">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header comentarios">
								<h5>COMENTA TU EXPERIENCIA EN FERREMAYORISTAS.COM.MX</h5>
							</div>
							<div class="modal-body">
								<form action="../php/coment.php" method="POST">
									<div class="form-group">
										<div class="form-group clasificacion">
											<input id="radio1" type="radio" name="estrellas" value="5"><label class="radio1" for="radio1">&#9733;</label>
											<input id="radio2" type="radio" name="estrellas" value="4"><label class="radio2" for="radio2">&#9733;</label>
											<input id="radio3" type="radio" name="estrellas" value="3"><label class="radio3" for="radio3">&#9733;</label>
											<input id="radio4" type="radio" name="estrellas" value="2"><label class="radio4" for="radio4">&#9733;</label>
											<input id="radio5" type="radio" name="estrellas" value="1"><label class="radio5" for="radio5">&#9733;</label>
										</div>
										<div class="form-group">
											<textarea class="form-control" name="comentario" placeholder="Comentario sobre el sitio..." rows="3"></textarea>
										</div>
										<div class="form-group btn-comentario">
											<button type="submit" class="btn btn-danger">Enviar</button>
										</div>
									</div>
									<p class="p-boletin">Gracias por tu comentario</p>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-3 col-lg-3 col-xs-3 col-12">
				<h5>OBTENER AYUDA</h5>
				<a class="enlaces-footer" href="#" onclick="showInformation('publicorder');"><p class="lead">Mi Pedido</p></a>
				<a class="enlaces-footer" href="#" title=""><p class="lead">Estado del Pedido</p></a>
				<a class="enlaces-footer" href="#" title=""><p class="lead">Envío y entrega</p></a>
				<a class="enlaces-footer" href="#" title=""><p class="lead">Devoluciones</p></a>
				<a class="enlaces-footer" href="#" title=""><p class="lead">Opciones de pago</p></a>
				<a class="enlaces-footer" href="http://www.ferremayoristas.com.mx/terminos" title=""><p class="lead">Terminos y Condiciones</p></a>
				<a class="enlaces-footer" href="http://www.ferremayoristas.com.mx/#contacto" title=""><p class="lead">Comunícate con nosotros</p></a>
			</div>
			<div class="col-sm-12 col-md-3 col-lg-3 col-xs-3 col-12">
				<a class="enlaces-footer" href="http://www.ferremayoristas.com.mx/ferroteca" title="">
					<h5>NOVEDADES</h5>
				</a>
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 col-12">
						<a class="enlaces-footer" href="http://www.ferremayoristas.com.mx/nosotros" title="">
							<p class="lead">Sobre <strong>Ferremayoristas Olvera</strong></p>
						</a>
					</div>
				</div>
				<a class="enlaces-footer" href="http://www.ferremayoristas.com.mx/oportunidad" title="">
					<h5>OPORTUNIDAD DE EMPLEO</h5>
				</a>
			</div>
			<div class="col-sm-12 col-md-3 col-lg-3 col-xs-3 col-12">
				<h5>SOCIAL</h5>
				<div class="row text-center">
					<div class="col">
						<a class="redes-footer" href="https://twitter.com/fmomx" title="" target="_blank"><p><i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i></p></a>
					</div>
					<div class="col">
						<a class="redes-footer" href="https://www.facebook.com/FerremayoristasOlvera/" title="" target="_blank"><p><i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i></p></a>
					</div>
					<div class="col"></div>
					<div class="col"></div>
				</div>
				<h5># VISITAS <?php include('../php/contador.php'); ?></h5>
			</div>
		</div>
	</footer>
</body>
</html>
<!-- Favicon -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/img/favicon2.png">
<link rel="icon" type="image/png" href="assets/img/favicon2.png">
<!-- CSS's -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../css/style.min.css">
<!-- Font's -->
<link href="https://fonts.googleapis.com/css?family=Roboto|Oswald" rel="stylesheet">