<?php
session_start();
if(isset($_SESSION["data"])) {
	$json 			= array();
	$json 			= $_SESSION["data"];
	$session 		= json_decode($json);
	var_dump($session);
} else {
	echo "No hay datos";
}
?>