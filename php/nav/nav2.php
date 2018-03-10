<?php
	if(!isset($_SESSION["data"])) {
	header("Location: ../../index.php");
} else {
	require_once("../php/class.database.php");
	require_once("../php/functions/util.php");

	$paramDb = new Database();
	$getConnection = $paramDb->GetLink();

	$paramFunctions = new Util();
	$rol = $_SESSION["data"]["rol"];
	$username = $_SESSION["data"]["name"];
	$user = $_SESSION["data"]["username"];
	$id = $_SESSION["data"]["id"];
	$rfc = $_SESSION["data"]["rfc"];
	$saldo = $_SESSION["data"]["saldo"];
	$formatoSaldo = number_format($saldo);
	$limite = $_SESSION["data"]["limite"];
	$formatoLimite = number_format($limite);
	$dispo = number_format($limite - $saldo);
	$disponible = $limite - $saldo;
	$vendedor = $_SESSION["data"]["vendedor"];
	$vendedorID = $_SESSION["data"]["vendedorid"];
	$diascredito = $_SESSION["data"]["diacredito"];
	$diasvisita = $_SESSION["data"]["diavis"];
	$ultimacompra = $_SESSION["data"]["ucompra"];
	$compraReciente = $_SESSION["data"]["compraReciente"];
	$ignorasuspender = $_SESSION["data"]["ignorasuspender"];
	$activo = $_SESSION["data"]["activo"];
	$correo = $_SESSION["data"]["correo"];
	$tel = $_SESSION["data"]["tel"];
	$pas2 = $_SESSION["data"]["pas2"];
	$pasAnt = $_SESSION["data"]["pasAnt"];

	$getDashboard = "SELECT * FROM dashboard WHERE rol ='$rol'";
	$result = mysqli_query($getConnection,$getDashboard);
?>
<style type="text/css">
	body{
		background-color: #f5f5f5 !important;
	}
</style>
<div id="wrapper" class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
	<header class="main-header">
		<!-- Logo -->
		<a class="logo navbar-fixed-top">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><b>F</b>MO</span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><b>FMO</b>Store &beta;eta</span>
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-fixed-top">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" id="open" onclick="ocultar()" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<a style="display:none;" href="#" class="sidebar-toggle" id="close" onclick="mostrar()" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<div class="navbar-custom-menu">
		<?php
			if($rol == "administrador" || $rol == 'SZ-01' || $rol == 'SZ-02' || $rol == 'direccion') {?>
				<ul class="nav navbar-nav">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<!-- User Account: style can be found in dropdown.less -->
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<?php
					echo	'<i class="fas fa-user-circle fa-lg" aria-hidden="true"></i>
							<span class="hidden-xs">'.$rol.'</span>';
					?>
						</a>
					</li>
					<li class="dropdown user user-menu">
						<a class="dropdown-toggle" href="../login/logout.php" data-toggle='tooltip' title='Salir' class="btn btn-default btn-flat">Salir</a>
					</li>
					<!-- Control Sidebar Toggle Button -->
					<li>
						<a href="#" data-toggle="control-sidebar"><i class="fas fa-cogs"></i></a>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header">ADMINISTRACIÓN</li>
				<?php
				$disponible = 0;
				$saldo = 0;
				$diasRestantesNewMes = 0;
				$vendedor = 0;
				$ignorasuspender = 0;
				$activo = 0;
				$nav = $paramFunctions->drawNavMenu($result, $disponible, $saldo, $diasRestantesNewMes, $vendedor, $ignorasuspender, $activo, $user);
				echo $nav;
				?>
			</ul>
		</section>
		<!-- /.sidebar -->
	</aside>
	<!-- NOTE All response that will be show here -->
	<div id="page-wrapper"></div>
	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- Create the tabs -->
		<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
			<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
		</ul>
	</aside>
	<!-- /.control-sidebar -->
	<!-- Add the sidebar's background. This div must be placed
		immediately after the control sidebar -->
	<div class="control-sidebar-bg"></div>
<?php
			} else {?>
				<ul class="nav navbar-nav">
					<!-- mini logo for sidebar mini 50x50 pixels -->
					<!-- Messages: style can be found in dropdown.less-->
					<li class="dropdown messages-menu">
				<?php
				$getMensaje = "SELECT mensaje, tipoUser FROM avisos WHERE user = '$user' OR user = 'alluser' LIMIT 0,5";
				$MensajeLoc = mysqli_query($getConnection,$getMensaje);
				$numeroLine = $MensajeLoc->num_rows;

					echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="far fa-envelope"></i>
							<b style="font-family: Arial;font-size: .7em; border: 5px solid red;background-color: red; color: white; border-radius: 50%;">'.$numeroLine.'</b>
							<span class="label label-success"></span>
						</a>
						<ul class="dropdown-menu">
							<li class="header">Mensajes</li>
							<li>
								<ul class="menu">';

				if($numeroLine > 0){
					while($rw = mysqli_fetch_row($MensajeLoc)){
					$usarioMes = $rw[0];
					$tipoUser = $rw[1];
							echo	"<li>
										<a>
											<div class='pull-left'>
												<img src='../img/img_pro/logo-gif.png' class='img-circle' alt='".utf8_encode($tipoUser)."'>
											</div>
											<h4>
												".utf8_encode($tipoUser)."
												<!--<small><i class='fa fa-clock-o'></i> 5 mins</small>-->
											</h4>
											<p style='height:100%;white-space:pre-wrap !important; margin: 0 !important;'>
												".$usarioMes."
											</p>
										</a>
									</li>";
					}
				} else {
					echo			'<li>
										<a href="#">
											<div class="pull-left">
												<img src="../img/img_pro/logo-gif.png" class="img-circle" alt="FMOTeamSupport">
											</div>
											<h4>
												Equipo de Soporte FMO
												<!--<small><i class="fa fa-clock-o"></i> 5 mins</small>-->
											</h4>
											<p>
												Sin Mensajes.
											</p>
										</a>
									</li>';
				}
				?>
								</ul>
							</li>
							<!-- <li class="footer"><a href="#">See All Messages</a></li> -->
						</ul>
					</li>
					<li class="dropdown messages-menu">
						<a class="dropdown-toggle" data-toggle="dropdown" alt="Artículos en el Carrito">
							<i class="fas fa-cart-arrow-down"></i>
						<?php
						if(isset($_SESSION["shoppingCarPartner"]) > 0){
						$shopping2 = $_SESSION["shoppingCarPartner"];
						$length2 = count($shopping2);
						echo	'<b id="carritoNav" style="font-family: Arial;font-size: .7em; border: 5px solid red;background-color: red; color: white; border-radius: 50%;">'.$length2.'</b>';
						} else {
						echo	'<b id="carritoNav" style="font-family: Arial;font-size: .7em; border: 5px solid red;background-color: red; color: white; border-radius: 50%;">0</b>';
						}
						?>
							<span class="label label-success"></span>
						</a>
					</li>
					<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
					<!-- User Account: style can be found in dropdown.less -->
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<?php
					echo	'<i class="fas fa-user-circle fa-lg" aria-hidden="true"></i>
							<span class="hidden-xs">'.$username.'</span>';
					?>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
						<?php
						echo	'<img src="../img/img_pro/logo-gif.png" class="img-circle" alt="'.$username.'">
								<p>
									'.$username.'
									<small></small>
								</p>';
						?>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="text-center">
									<p>R.F.C.: <?php echo "<strong>".$rfc."</strong>"; ?></p>
									<p>Email: <?php echo "<strong>".$correo."</strong>"; ?></p>
								</div>
								<div class="row">
									<div class="col-md-4">
										<p>Saldo: <?php echo "<strong><br>$ ".$formatoSaldo."</strong>"; ?></p>
									</div>
									<div class="col-md-4">
										<p>Limite: <?php echo "<strong><br>$ ".$formatoLimite."</strong>"; ?></p>
									</div>
									<div class="col-md-4">
								<?php
								if ($disponible >= 0) {
									echo "<p>Disponible: <strong><br>$ ".$dispo."</strong></p>";
								} else {
									echo "<p>Disponible: <strong style='color:red'><br>$ ".$dispo."</strong></p>";
								}
								?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<p>ID Vendedor: <?php echo "<strong>".$vendedor."</strong>"; ?></p>
									</div>
									<div class="col-md-6">
										<p>Días de Crédito: <?php echo "<strong>".$diascredito."</strong>"; ?></p>
									</div>
								</div>
							<?php
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
							$sumaDeDias = $diasUltimaCompra + $diascredito;
							if($ultimacompra > 0){
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
									$nextMes = $mesUltimaCOmpra + 1;
									$fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");//39
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
							?>
								<div class="row">
									<div class="col-md-6">
										<p>Visitas: <?php echo "<strong>".$dia."</strong>"; ?></p>
										<!-- <p><?php echo $ignorasuspender; ?></p> -->
									</div>
									<div class="col-md-6">
										<p>Última Compra: <?php echo "<strong>".$compraReciente."</strong>"; ?></p>
										<!--<p>diasUltimaCompra: <?php echo "<strong>".$diasUltimaCompra."</strong>"; ?></p>
										<p>sumaDeDias: <?php echo "<strong>".$sumaDeDias."</strong>"; ?></p>
										<p>diasTotalMesUltimaCompra: <?php echo "<strong>".$diasTotalMesUltimaCompra."</strong>"; ?></p>
										<p>diasRestantesNewMes: <?php echo "<strong>".$diasRestantesNewMes."</strong>"; ?></p>
										<p>nextMes: <?php echo "<strong>".$nextMes."</strong>"; ?></p>
										<p>nextMes: <?php echo "<strong>".$nextMes."</strong>"; ?></p> -->
							<?php
							if($fechaLimite == 'Factura(s) Vencida(s)'){
							echo 		"<p>Límite de Pago: <strong style='color:red;'>".$fechaLimite."-".$ultimacompra."</strong></p>";
							} else {
							echo 		"<p>Límite de Pago: <strong>".$fechaLimite."</strong></p>";
							}
							?>
									</div>
								</div>
							</li>
						</ul>
					</li>
					<li class="dropdown user user-menu">
						<a class="dropdown-toggle" href="../login/logout.php" data-toggle='tooltip' title='Salir' class="btn btn-default btn-flat">Salir</a>
					</li>
					<!-- Control Sidebar Toggle Button -->
					<li>
						<a href="#" data-toggle="control-sidebar"><i class="fas fa-cogs"></i></a>
					</li>
				</ul>

			</div>
		</nav>
	</header>
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar user panel -->
			<div class="user-panel">
				<div class="pull-left image">
			<?php
			echo	'<img src="../img/img_pro/logo-gif.png" class="img-circle" alt="User Image">';
			?>
				</div>
				<div class="pull-left info">
				<?php
				echo "<p>".$username."</p>";
				echo "<div class='col-md-12' style='padding:0 !important;margin: 0 0 5px 0 !important;'>";
				if($user == '00001' || $user == '00080' || $user == '00051' || $user == '00491' || $user == '02980' || $user == '01739' || $user == '01789'){
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-success'></i> Activo</a>";
				} elseif($disponible > 0) {
					if ($vendedor == 'OF') {
						if ($saldo > 0) {
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-warning'></i> AS-(Liquidar saldo pendiente)</a>";
						} else {
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-success'></i> Activo</a>";
						}
					} elseif ($diasRestantesNewMes >=0) {
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-success'></i> Activo</a>";
					} elseif ($saldo == 0) {
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-success'></i> Activo</a>";
					} else {
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-warning'></i> AS-(Facturas SIN PAGO)</a>";
					}
				} else {
					echo "<a style='font-size:0.8em;'><i class='fa fa-circle text-warning'></i> AS-(Límite de crédito excedido)</a>";
				}
				echo "</div>";
			echo "</div>";
				$getDatos = "SELECT d.direccion, d.numero, d.interior, d.colonia, d.cp, d.ciudad, d.estado, cl.vendedorid
								FROM dom d
								JOIN cli cl ON cl.clienteid = d.clienteid
								WHERE d.clienteid = $id";
				$datosDev = mysqli_query($getConnection,$getDatos);
				$filaDatos = mysqli_fetch_array($datosDev);

			echo "<div class='info' style='position: initial !important;'>";
				echo "<div class='col-md-12' style='padding:0 !important;margin: 10px 0 0 0 !important;'>";
				echo 	"<p style='font-size:0.8em;'>TEL.: ".$tel."</p>";
				echo "</div>";
				echo "<div class='col-md-12' style='padding:0 !important;'>";
				echo 	"<p style='font-size:0.8em;'>".$correo."</p>";
				echo "</div>";
				echo "<div class='col-md-12' style='padding:0 !important;'>";
				echo 	"<p style='font-size:0.8em;'>DIRECCION: $filaDatos[0] $filaDatos[1] $filaDatos[2] COL. $filaDatos[3] C.P. $filaDatos[4]</p>";
				echo "</div>";
				echo "<div class='col-md-12' style='padding:0 !important;'>";
				echo 	"<p style='font-size:0.8em;'>LOCALIDAD: $filaDatos[5], $filaDatos[6]</p>";
				echo "</div>";
				?>
				</div>
			</div>
			<!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header">CENTRO DE INFORMACIÓN</li>
		<?php
		if($disponible > 0) {
			// at this point, call function that draw vertical nav from database for each kind of user
			if($result) {
				$nav = $paramFunctions->drawNavMenu($result, $disponible, $saldo, $diasRestantesNewMes, $vendedor, $ignorasuspender, $activo, $user);
				echo $nav;
			} // TODO do something when not find data
		} else {
		// at this point, call function that draw vertical nav from database for each kind of user
			if($result) {
				$nav = $paramFunctions->drawNavMenu($result, $disponible, $saldo, $diasRestantesNewMes, $vendedor, $ignorasuspender, $activo, $user);
				echo $nav;
			} // TODO do something when not find data
		}
		?>
			</ul>
		<?php
		//Aqui quite una lla }
		if($filaDatos[7] == 16){
			$email = 0;
		echo "<div id='vendedor' style='color:white;margin: 10px 0 0 0 !important;'>";
		echo 	"<div class='row text-center'>";
		echo 		"<div class='col-md-12'>";
		echo 			'<img src="../img/img_pro/logo-gif.png" class="img-circle" alt="User Image" width="100">';
		echo 		"</div>";
		echo 		"<div class='col-md-12'>";
		echo 			"<div class='col-md-12' style='margin: 10px 0 0 0 !important;'>";
		echo 				"<p style='font-size:0.8em;'>NO HAY VENDEDOR</p>";
		echo 			"</div>";
		echo 			"<div class='col-md-12'";
		echo 				"<p style='font-size:0.8em;'>USUARIO EMPLEADO</p>";
		echo 			"</div>";
		} else {
		$getFotoVen = "SELECT v.nombre, v.tel, v.foto
							FROM vendedores v
							JOIN cli c ON c.vendedorid = v.vendedorid
							WHERE c.clienteid = ".$id."";
		$FotoVenEnc = mysqli_query($getConnection,$getFotoVen);
		$filaFoto = mysqli_fetch_row($FotoVenEnc);
		$email = 0;
		echo 			"<div id='vendedor' style='color:white;margin:10px 0 0 0;'>";//aqui es el vendedor
		echo 				"<h4 class='text-center'>Vendedor Asignado</h4>";
		echo 				"<div class='row text-center'>";
		echo 					"<div class='col-md-12 pull-left'>";
		echo 						'<img src="../img/img_pro/vendedores/'.$filaFoto[2].'" class="img-circle" alt="'.$filaFoto[0].'" width="100">';
		echo 					"</div>";
		echo 					"<div class='col-md-12'>";
		echo 						"<div class='col-md-12' style='margin: 10px 0 0 0 !important;'>";
		echo 							"<p style='font-size:1em;'>".$filaFoto[0]."</p>";
		echo 						"</div>";
		echo 						"<div class='col-md-12' style='margin: 10px 0 0 0 !important;'>";
		echo 							"<p style='font-size:1em;'>TEL.: ".$filaFoto[1]."</p>";
		echo 						"</div>";
		if ($email == 0 || $email == ""){
		echo 						"<div class='col-md-12'>";
		echo 							"<p style='font-size:0.8em;'>EMAIL: SIN EMAIL</p>";
		echo 						"</div>";
		} else {
		echo 						"<div class='col-md-12'>";
		echo 							"<p style='font-size:1em;'>EMAIL: ".$email."</p>";
		echo 						"</div>";
		}
		echo 					"</div>";
		echo 				"</div>";
		echo 			"</div>";
		}
		?>
		</section>
	<!-- /.sidebar -->
	</aside>
	<!-- NOTE All response that will be show here -->
	<div id="page-wrapper"></div>
		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Create the tabs -->
			<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
				<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<!-- Home tab content -->
				<div class="tab-pane" id="control-sidebar-home-tab">
					<h3 class="control-sidebar-heading">Actividades Recientes</h3>
					<ul class="control-sidebar-menu">
				<?php
				echo	'<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-birthday-cake bg-red"></i>
								<div class="menu-info">
									<h4 class="control-sidebar-subheading">'.$username.'</h4>
									<p>Una recomendación.</p>
								</div>
							</a>
						</li>';
				?>
					</ul>
					<!-- /.control-sidebar-menu -->
				</div>
				<!-- /.tab-pane -->
			</div>
		</aside>
		<!-- /.control-sidebar -->
		<!-- Add the sidebar's background. This div must be placed
			immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>
	</div>
<?php
}
}
?>
</div>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>