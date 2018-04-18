<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="favicon.ico">

	<title>Ferremayoristas Olvera en línea - Acceso</title>

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">

	<!-- Custom styles for this template -->
	<link href="css/cover.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.css">
	<link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">
</head>
<body>
	<div class="site-wrapper">
		<div class="site-wrapper-inner">
			<div class="cover-container">
				<div class="content">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12" style="border-radius: 10px; width: 450px;margin:auto;">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12 objeto-centro">
								<form class="form-signin" name="frmLogin" action="ruta/init.php" method="POST" enctype="multipart/form-data">
									<div class="form-group">
										<img class="img-fluid" style="padding: 20px" src="img/logo2.png" alt="Ferremayoristas Olvera">
									</div>
									<div class="form-group">
										<label for="user" class="sr-only">Número de Usuario</label>
										<input type="text" name="user" id="user" class="form-control text-center" placeholder="Usuario Eje.: 01234" autocomplete="off" required autofocus>
									</div>
									<div class="form-group">
										<label for="pass" class="sr-only">Password</label>
										<input type="password" name="pass" id="pass" class="form-control text-center" placeholder="Password" required>
									</div>
									<div class="form-group">
										<button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-6">
												<a class="btn btn-md btn-enl btn-block" href="http://www.ferremayoristas.com.mx/">Inicio</a>
											</div>
											<div class="col-6">
												<a class="btn btn-md btn-enl btn-block" href="http://www.ferremayoristas.com.mx/tienda/index.php">Tienda</a>
											</div>
											<?php
											if(isset($_SESSION['message'])){
												echo '<div class="col-md-12 alert alert-danger" style="margin: 10px 0 10px 0" role="alert">';
												echo  "<strong>".$_SESSION['message']."</strong>";
												echo "</div>";
												unset($_SESSION['message']);
											}
											?>
										</div>
									</div>
									<div class="col-12">
										<a class="btn btn-md btn-enl btn-block" href="javascript:location.reload(true)">Borrar Historial</a>
									</div>
								</form>
							</div>
							<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12  objeto-centro">
									<p class="lead" style="font-size: .8em;font-weight: bold;"> Derechos Reservados © Ferremayoristas Olvera S.A. de C.V.<br>Prohibida la reproduccion total o parcial del contenido de esta página.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</body>
</html>
