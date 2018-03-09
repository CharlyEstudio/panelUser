<?php

require_once("../class.database.php");

$paramDb = new Database();
$getConnection = $paramDb->GetLink();

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda)) {

	//Selecciona todo de la tabla categoria 
	//donde la categoria sea igual a $consultaBusqueda, 
	$consulta = mysqli_query($getConnection, "SELECT * FROM categoria WHERE categoria LIKE '%".$consultaBusqueda."%'");

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas = 0) {
		$mensaje = "<p>No existe esta categoria</p>";
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		echo '<div style="padding:5px;text-align:center;">'.$consultaBusqueda.'</div>';
		echo "<hr>";
		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($resultados = mysqli_fetch_array($consulta)) {

			$categoria = $resultados['categoria'];
			$idcategoria = $resultados['id'];

				$datosCategoria = array("categoriaID" => $idcategoria,
								"nombreCategoria" => $categoria);
				$recibirParametro = json_encode($datosCategoria);
			//Output
			$mensaje .= "<a class='dropdown-item' href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy(".$recibirParametro.")'>".$categoria."</a>";

		};//Fin while $resultados

	}; //Fin else $filas

};//Fin isset $consultaBusqueda

//Devolvemos el mensaje que tomará jQuery
echo $mensaje;
echo "<hr>";
?>