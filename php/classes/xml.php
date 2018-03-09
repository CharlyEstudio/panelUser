<?php
	require_once("../class.database.php");
	$id			= $_GET['f'];
	$host 		= "67.227.237.109";
	$username 	= "zizaram1_datosaF";
	$password 	= "dwzyGskl@@.W";
	$database 	= "zizaram1_datosa";
	$mysqli 	= new mysqli($host, $username, $password, $database);
	$getDatFac	= "SELECT xml, folio  FROM cfd where docid = $id";
	$getFact	= mysqli_query($mysqli, $getDatFac);
	$fact 		= mysqli_fetch_row($getFact);
	$xml 		= $fact[0];
	$folio		= $fact[1];
	$file		= fopen("XML-".$folio.".xml","w+");
	fwrite ($file,$xml);
	fclose($file);

	header("Content-disposition: attachment; filename=XML-".$folio.".xml");
	header("Content-type: MIME");
	readfile("XML-".$folio.".xml");

	$mysqli->close();
?>