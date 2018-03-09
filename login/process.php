<?php
	session_start();
	$message["message"] = array(
		"type" => "error",
		"msg" => "Usuario ó Contraseña No validos.",
		"section" => "login"
	);

	$message = "Usuario ó Contraseña No validos";

	if(isset($_POST['user']) && isset($_POST['pass'])) {
		if(strlen($_POST['user']) > 0 && strlen($_POST['pass']) > 0 ) {
			require_once("../php/class.database.php");
			require_once("../php/functions/util.php");
			$db = new Database();
			$functions = new Util();
			$getConnection = $db->GetLink();

			// sanitize variables from form
			$user = $functions->sanitize($_POST['user']);
			$user = $functions->specialChars($user);
			$user = $db->SecureInput($user);

			$getParamPassword = $functions->sanitize($_POST['pass']);
			$getParamPassword = $functions->specialChars($getParamPassword);
			$ip = $_SERVER['REMOTE_ADDR'];
			$date = new DateTime();
    		$fecha = $date->format('Y-m-d g:i:s A');

    		//Sesion USUARIO Admin
			$checkUser = "SELECT usu.clienteID, cli.nombreCompleto, cli.roleID, rol.rol, usu.username, usu.password, usu.clienteID
								FROM clientes cli
									JOIN usuarios usu ON usu.clienteID = cli.id
									JOIN roles rol ON rol.id = cli.roleID
								WHERE usu.username = '$user'";

			$executeQuery = $db->Query($checkUser);
			try {
				$numRow = $db->NumRows();
				$row = $db->Rows();

				if($user == 'admin' || $user == 'supervisor1' || $user == 'supervisor2' || $user == 'pedidos'){
					// user exist on database
					if($numRow == 1) {
						// count($row);
						$password = $row[0]["password"];
						// then compare user input password send and store on database hashed
						if ($getParamPassword == $password) {
							$data = array(
								"id" => $row[0]["clienteID"],
								"username" => $row[0]["username"],
								"name" => $row[0]["nombreCompleto"],
								"rol" => $row[0]["rol"],
								"rfc" => 0,
								"saldo" => 0,
								"limite" => 0,
								"vendedorid" => 0,
								"diacredito" => 0,
								"diavis" => 0,
								"ucompra" => 0,
								"compraReciente" => 0,
								"vendedor" => 0,
								"ignorasuspender" => 0,
								"activo" => 0,
								"correo" => 0,
								"tel" => 0,
								"pas2" => 'Correcto',
								"pasAnt" => 'Correcto'
							);
							$_SESSION["data"] = $data;
							$functions->redirect('../intranet/index.php');
							mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Correcto')");
						} else {
							$_SESSION["data"] = $message;
							$functions->redirect('../login/index.php?op=message');
							mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Password Incorrecto')");
						}
					} else { // user doesn't exist, show message and redirect
						$_SESSION["data"] = $message;
						$functions->redirect('../login/index.php?op=message');
						mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Ingreso de Usuario inexistente')");
					}
				} else { // user doesn't exist, show message and redirect
					//Sesion USUARIO Normal
					$checkUser = "SELECT c.numero, c.clienteid as usuarioID, c.saldo, c.limite, c.vendedorid, p.nombre as vendedor, c.diacredito, c.diavis, d.domid, d.nombre, d.rfc, c.lista, def.dpnombre, c.ignorasuspender, c.activo, co.correo, usu.password as pas2, usu.passant as pas1
						FROM cli c
							JOIN dom d ON d.clienteid = c.clienteid
							JOIN per p ON p.perid = c.vendedorid
							JOIN correos co ON co.clienteid = c.clienteid
							JOIN defpre def ON def.dpid = c.lista
							JOIN usuarios usu ON usu.clienteID = c.clienteid
						WHERE usu.username = $user";

					$executeQuery = $db->Query($checkUser);
					try {
						$numRow = $db->NumRows();
						$row = $db->Rows();

						// user exist on database
						if($numRow) {
							// count($row);
							$password = $row[0]["pas1"];
							$pas2 = $row[0]["pas2"];
							$inactivo = $row[0]["vendedorid"];
							$clienteID = $row[0]["usuarioID"];

							$feccap = date("Y-01-01");

							$ultCompra = "SELECT docid, feccap, feccan, vence, total, totalpagado
												FROM doc
												WHERE clienteid = $clienteID
													AND (
														totalpagado > 0
														AND totalpagado < total
													)
													AND feccap >= '$feccap'
													AND feccan = 0
												ORDER BY feccap ASC
												LIMIT 0,1";
							$resUltCom = mysqli_query($getConnection,$ultCompra);
							$row2 = mysqli_fetch_array($resUltCom);
							$ultimaCompra = $row2[1];

							$recienteCompra = "SELECT MAX(fecha) AS fecha FROM doc WHERE clienteid = $clienteID AND subtotal2 > 0";
							$resRecComp = mysqli_query($getConnection,$recienteCompra);
							$rowRec = mysqli_fetch_array($resRecComp);
							$compraReciente = $rowRec[0];

							$getTel = "SELECT tel FROM tel WHERE clienteid = ".$clienteID."";
							$resTel = mysqli_query($getConnection,$getTel);
							$row3 = mysqli_fetch_array($resTel);
							$tel = $row3[0];

							if($user == '00001' || $user == '00080' || $user == '00051' || $user == '00491' || $user == '02980' || $user == '01739' || $user == '01789'){
								if ($getParamPassword == $pas2) {
									$completeName = $row[0]["nombre"];
									$data = array(
										"id" => $clienteID,
										"username" => $row[0]["numero"],
										"name" => $completeName,
										"rol" => $row[0]["dpnombre"],
										"rfc" => $row[0]["rfc"],
										"saldo" => $row[0]["saldo"],
										"limite" => $row[0]["limite"],
										"vendedorid" => $row[0]["vendedorid"],
										"diacredito" => $row[0]["diacredito"],
										"diavis" => $row[0]["diavis"],
										"ucompra" => $ultimaCompra,
										"compraReciente" => $compraReciente,
										"vendedor" => $row[0]["vendedor"],
										"ignorasuspender" => $row[0]["ignorasuspender"],
										"activo" => $row[0]["activo"],
										"correo" => $row[0]["correo"],
										"tel" => $tel,
										"pas2" => $pas2,
										"pasAnt" => $password
									);
									$_SESSION["data"] = $data;
									$functions->redirect('../intranet/index.php');
									mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Correcto SuperUser')");
								} elseif ($getParamPassword == $password) {
									$completeName = $row[0]["nombre"];
									$data = array(
										"id" => $clienteID,
										"username" => $row[0]["numero"],
										"name" => $completeName,
										"rol" => $row[0]["dpnombre"],
										"rfc" => $row[0]["rfc"],
										"saldo" => $row[0]["saldo"],
										"limite" => $row[0]["limite"],
										"vendedorid" => $row[0]["vendedorid"],
										"diacredito" => $row[0]["diacredito"],
										"diavis" => $row[0]["diavis"],
										"ucompra" => $ultimaCompra,
										"compraReciente" => $compraReciente,
										"vendedor" => $row[0]["vendedor"],
										"ignorasuspender" => $row[0]["ignorasuspender"],
										"activo" => $row[0]["activo"],
										"correo" => $row[0]["correo"],
										"tel" => $tel,
										"pas2" => $pas2,
										"pasAnt" => $password
									);
									$_SESSION["data"] = $data;
									$functions->redirect('../intranet/index.php');
									mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Correcto SuperUser')");
								} else {
									$_SESSION["data"] = $message;
									//$functions->redirect('../login/index.php?op=message');
									$functions->redirect('../login/indexerror.php');
									mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Password Incorrecto SuperUser')");
								}
							} elseif($inactivo == 17){
								//TODO decir que la cuenta esta inactiva
								$_SESSION["data"] = $message;
								//$functions->redirect('../login/index.php?op=message');
								$functions->redirect('../login/indexerror.php');
								mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Usuario Suspendido')");
							} else {
								if ($getParamPassword == $pas2) {
									$completeName = $row[0]["nombre"];
									$data = array(
										"id" => $clienteID,
										"username" => $row[0]["numero"],
										"name" => $completeName,
										"rol" => $row[0]["dpnombre"],
										"rfc" => $row[0]["rfc"],
										"saldo" => $row[0]["saldo"],
										"limite" => $row[0]["limite"],
										"vendedorid" => $row[0]["vendedorid"],
										"diacredito" => $row[0]["diacredito"],
										"diavis" => $row[0]["diavis"],
										"ucompra" => $ultimaCompra,
										"compraReciente" => $compraReciente,
										"vendedor" => $row[0]["vendedor"],
										"ignorasuspender" => $row[0]["ignorasuspender"],
										"activo" => $row[0]["activo"],
										"correo" => $row[0]["correo"],
										"tel" => $tel,
										"pas2" => $pas2,
										"pasAnt" => $password
									);
									$_SESSION["data"] = $data;
									$functions->redirect('../intranet/index.php');
									mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Correcto User')");
								} elseif ($getParamPassword == $password) {// then compare user input password send and store on database hashed
									$completeName = $row[0]["nombre"];
									$data = array(
										"id" => $clienteID,
										"username" => $row[0]["numero"],
										"name" => $completeName,
										"rol" => $row[0]["dpnombre"],
										"rfc" => $row[0]["rfc"],
										"saldo" => $row[0]["saldo"],
										"limite" => $row[0]["limite"],
										"vendedorid" => $row[0]["vendedorid"],
										"diacredito" => $row[0]["diacredito"],
										"diavis" => $row[0]["diavis"],
										"ucompra" => $ultimaCompra,
										"compraReciente" => $compraReciente,
										"vendedor" => $row[0]["vendedor"],
										"ignorasuspender" => $row[0]["ignorasuspender"],
										"activo" => $row[0]["activo"],
										"correo" => $row[0]["correo"],
										"tel" => $tel,
										"pas2" => $pas2,
										"pasAnt" => $password
									);
									$_SESSION["data"] = $data;
									$functions->redirect('../intranet/index.php');
									mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Correcto User')");
								} else {
									$_SESSION["data"] = $message;
									//$functions->redirect('../login/index.php?op=message');
									$functions->redirect('../login/indexerror.php');
									mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Password Incorrecto')");
								}
							}
						} else { // user doesn't exist, show message and redirect
							/*$_SESSION["data"] = $message;
							//$functions->redirect('../login/index.php?op=message');*/
							$functions->redirect('../login/indexerror.php');
							mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$user', '$fecha', '$ip', 'Ingreso de Usuario inexistente')");
						}
					} catch (Exception $e) {
						$message["message"] = array(
							"type" => "error",
							"msg" => "Ha ocurrido un error al autenticarse: " . $e->getMessage(),
							"section" => "login"
						);
						$_SESSION["data"] = $message;
						//$functions->redirect('../login/index.php?op=message');
						$functions->redirect('../login/indexerror.php');
					}
				}
			} catch (Exception $e) {
				$message["message"] = array(
					"type" => "error",
					"msg" => "Ha ocurrido un error al autenticarse: " . $e->getMessage(),
					"section" => "login"
				);
				$_SESSION["data"] = $message;
				//$functions->redirect('../login/index.php?op=message');
				$functions->redirect('../login/indexerror.php');
			}
		} else {
			$_SESSION["data"] = $message;
			//header('Location: ../login/index.php?op=message');
			$functions->redirect('../login/indexerror.php');
		}
	} else {
		//$_SESSION["data"] = $message;
		header('Location: ../login/index.php?op=message');
		$functions->redirect('../login/indexerror.php');
	}
	$getConnection->close();
?>
