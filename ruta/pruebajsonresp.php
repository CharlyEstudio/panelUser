<?php
require_once("../php/class.database.php");
require_once("../php/functions/util.php");
session_start();
$message = "Inicio de sesión Incorrecto";
if(isset($_POST['user']) && isset($_POST['pass'])) {
	$db 					= new Database();
	$functions 				= new Util();
	$getConnection 			= $db->GetLink();
	// sanitize variables from form
	$getParamUser 			= $functions->sanitize($_POST['user']);
	$getParamUser 			= $functions->specialChars($getParamUser);
	$getParamUser 			= $db->SecureInput($getParamUser);
	$getParamPassword 		= $functions->sanitize($_POST['pass']);
	$getParamPassword 		= $functions->specialChars($getParamPassword);
	$getParamPassword 		= $db->SecureInput($getParamPassword);
	$ip 					= $_SERVER['REMOTE_ADDR'];
	$date 					= new DateTime();
	$fecha 					= $date->format('Y-m-d g:i:s A');

	$checkInfo 				= "SELECT * FROM cli";
	$executeQuery 			= $db->Query($checkInfo);
	$encRow 				= $db->NumRows();

	if($encRow){
		if(is_numeric($getParamUser)){
			$checkUser 			= "SELECT def.dpnombre
									FROM cli c
										JOIN defpre def ON def.dpid = c.lista
										JOIN usuarios usu ON usu.clienteID = c.clienteid
									WHERE usu.username = $getParamUser
										AND";
			if(is_numeric($getParamPassword)){
				$checkUser		.= " (
										usu.password = $getParamPassword
										OR usu.passant = $getParamPassword
									)";
			} else {
				$checkUser		.= " (
										usu.password = '$getParamPassword'
										OR usu.passant = '$getParamPassword'
									)";
			}

			$executeQuery 			= $db->Query($checkUser);
			try {
				$numRow 			= $db->NumRows();
				if($numRow) {
					$checkUser 		= "SELECT c.numero, c.clienteid as usuarioID, c.saldo, c.limite, c.vendedorid, p.nombre as vendedor, c.diacredito, c.diavis, d.domid, d.nombre, d.rfc, c.lista, def.dpnombre, c.ignorasuspender, c.activo, co.correo, usu.password as pas2, usu.passant as pas1
									FROM cli c
										JOIN dom d ON d.clienteid = c.clienteid
										JOIN per p ON p.perid = c.vendedorid
										JOIN correos co ON co.clienteid = c.clienteid
										JOIN defpre def ON def.dpid = c.lista
										JOIN usuarios usu ON usu.clienteID = c.clienteid
									WHERE usu.username = $getParamUser";
					$executeQuery 	= $db->Query($checkUser);
					$numRow 		= $db->NumRows();
					$rows 			= $db->Rows();
					$array 			= array();
					foreach($rows as $row)
					{
						$array[] 	= $row;
					}
					$json 				= json_encode($array);

					$_SESSION["data"] = json_decode($json);
					$functions->redirect('../intranet/index.php');
						/*$functions->redirect('../login/prueba.php');*/
					$password 		= $rows[0]["pas1"];
					$pas2 			= $rows[0]["pas2"];
					$completeName 	= $rows[0]["nombre"];
					$inactivo 		= $rows[0]["vendedorid"];
					$clienteID 		= $rows[0]["usuarioID"];
					$feccap 		= date("Y-01-01");

					$ultCompra 	= "SELECT docid, feccap, feccan, vence, total, totalpagado
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
					$resUltCom 	= mysqli_query($getConnection,$ultCompra);
					$row2 		= mysqli_fetch_array($resUltCom);
					$ultimaCompra = $row2[1];

					$recienteCompra = "SELECT MAX(fecha) AS fecha FROM doc WHERE clienteid = $clienteID AND subtotal2 > 0";
					$resRecComp = mysqli_query($getConnection,$recienteCompra);
					$rowRec 	= mysqli_fetch_array($resRecComp);
					$compraReciente = $rowRec[0];

					$getTel 	= "SELECT tel FROM tel WHERE clienteid = ".$clienteID."";
					$resTel 	= mysqli_query($getConnection,$getTel);
					$row3 		= mysqli_fetch_array($resTel);
					$tel 		= $row3[0];

					$data 		= array(
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
									"pasAnt" => $password);

					if($getParamUser == '00001' || $getParamUser == '00080' || $getParamUser == '00051' || $getParamUser == '00491' || $getParamUser == '02980' || $getParamUser == '01739' || $getParamUser == '01789'){
						$status	= "Correcto SuperUser";
					} else {
						$status	= "Correcto User";
					}
					$_SESSION["data"] = $data;
					$functions->redirect('../intranet/index.php');
				} else {
					$_SESSION["message"] = $message;
					$functions->redirect('../login/index.php');
					$status		= "Incorrecto";
				}
				mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$getParamUser', '$fecha', '$ip', '$status')");
			} catch (Exception $e) {
				$_SESSION["message"] = $message;
				$functions->redirect('../login/index.php');
			}
		} else {
			$checkUser 			= "SELECT usu.clienteID, cli.nombreCompleto, cli.roleID, rol.rol, usu.username, usu.password, usu.clienteID, usu.passant, usu.password
									FROM clientes cli
										JOIN usuarios usu ON usu.clienteID = cli.id
										JOIN roles rol ON rol.id = cli.roleID
									WHERE usu.username = '$getParamUser'
										AND";

			if(is_numeric($getParamPassword)){
				$checkUser		.= " (
										usu.password = $getParamPassword
										OR usu.passant = $getParamPassword
									)";
			} else {
				$checkUser		.= " (
										usu.password = '$getParamPassword'
										OR usu.passant = '$getParamPassword'
									)";
			}

			$executeQuery 		= $db->Query($checkUser);
			try {
				$numRow 		= $db->NumRows();
				$rows 			= $db->Rows();
				$array 			= array();
				foreach($rows as $row)
				{
					$array[] 	= $row;
				}
				$json 				= json_encode($array);
				if($numRow) {
					$status	= "Correcto ".$getParamUser."";
					$_SESSION["data"] = json_decode($json);
					$functions->redirect('../intranet/index.php');
					/*$functions->redirect('../login/prueba.php');*/
				} else {
					$_SESSION["message"] = $message;
					$functions->redirect('../login/index.php');
					$status	= "Incorrecto ".$getParamUser."";
				}
				mysqli_query($getConnection, "INSERT INTO `accesos`(`user`, `datetimes`, `ip`, `status`) VALUES ('$getParamUser', '$fecha', '$ip', '$status')");
			} catch (Exception $e) {
				$_SESSION["message"] = $message;
				$functions->redirect('../login/index.php');
			}
		}
	} else {
		$_SESSION["message"] = "La base de datos esta en mantenimiento, en un momento estará disponible. Gracias por su comprensión.";
		$functions->redirect('../login/index.php');
	}
}
?>