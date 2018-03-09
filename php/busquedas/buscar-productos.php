<?php
require_once("../class.database.php");
require_once("../functions/util.php");

$paramDb = new Database();
$paramFunctions = new Util();
$getConnection = $paramDb->GetLink();

//Variable de búsqueda
$consultaBusqueda = $_POST['valorBusqueda'];

//Variable vacía (para evitar los E_NOTICE)
$mensaje = "";

//Comprueba si $consultaBusqueda está seteado
if (isset($consultaBusqueda)) {
	//Selecciona todo de la tabla categoria 
	//donde la categoria sea igual a $consultaBusqueda,
	/*$consulta = mysqli_query($getConnection,"SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, img.imagen
						FROM inv i
							JOIN imagenes img ON img.clvprov = i.clvprov
						WHERE i.descripcio LIKE %".$consultaBusqueda."%'");*/
	$consulta = mysqli_query($getConnection, "SELECT articuloid, clave, clvprov, descripcio FROM inv
												WHERE
												(
													descripcio LIKE '%$consultaBusqueda%'
													OR clvprov LIKE '$consultaBusqueda%'
												)
												OR
													clave LIKE '%$consultaBusqueda%'
												ORDER BY CASE WHEN clave LIKE '$consultaBusqueda%'
													THEN 1
													ELSE 2
												END, clave");

	//Obtiene la cantidad de filas que hay en la consulta
	$filas = mysqli_num_rows($consulta);

	$headers = ["Imagen", "Clave", "Código", "Producto", "Agregar"];
	$classPerColumn = ["text-center", "text-center", "text-center", "text-center", "text-center"];
	$mensaje .= $paramFunctions->drawTableHeader($headers, $classPerColumn);

	//Si no existe ninguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
	if ($filas == NULL) {
		$mensaje .= "<p>No existe este producto</p>";
	} else {
		//Si existe alguna fila que sea igual a $consultaBusqueda, entonces mostramos el siguiente mensaje
		echo '<div style="padding:5px;text-align:center;">Estas buscando <b>'.$consultaBusqueda.'</b> y se encontraron <b>'.$filas.'</b> resultados.</div>';
		echo "<hr>";
		//La variable $resultado contiene el array que se genera en la consulta, así que obtenemos los datos y los mostramos en un bucle
		while($resultados = mysqli_fetch_array($consulta)) {

			$id = $resultados["articuloid"];
			$clave = $resultados["clave"];
			$codigo = $resultados["clvprov"];
			$producto = $resultados["descripcio"];

			$getImagen = "SELECT imagen
						FROM imagenes
						WHERE codigo = $codigo";
			$resultImg = mysqli_query($getConnection,$getImagen);
			$filaImg = mysqli_fetch_array($resultImg);
			$imagen = $filaImg[0];

			$params = array("productoID"=>$codigo,
								"location"=>"addProduct-to-shoppingcart-partner",
								"url"=>"../php/shopping/shopping.php",
								"booleanResponse"=>true,
								"divResultID"=>"content-shoppingCar-partner",
								"msgSuccess"=>"Producto agregado correctamente",
								"msgError"=>"Error al agregar producto al carrito");
			$recibirParametro = json_encode($params);

			// JSON to show modal when user will see description and images about product
			/*$paramsModal = array("productID"=>$id,
								"location"=>"showModalProductRegisteredUser",
								"section"=>"productRegisteredUser",
								"url"=>"../php/product/product.php",
								"booleanResponse"=>false,
								"divResultID"=>"resultModalProduct",
								"msgSuccess"=>"Ok!",
								"msgError"=>"Error mostrar informacion del producto");
			$recibirParametro = json_encode($paramsModal);*/

			//Output
			/*$mensaje .= "<a class='dropdown-item' href='#' id='clickProductCategoryBy' onclick='clickProductCategoryBy(".$recibirParametro.")'>".$categoria."</a>";*/
			$mensaje .= "<tr>";
			$mensaje .=   "<td class='text-center'>";
			$mensaje .=     "<img class='img-fluid' src='../img/img_pro/img/".$imagen."' alt='$producto' width='100'/>";
			$mensaje .=    "</td>";

			$mensaje .=   "<td width='20%' class='text-center' style='vertical-align:middle; font-weight:bold;'>$clave</td>";
			$mensaje .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$codigo</td>";
			$mensaje .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>$producto</td>";
			$mensaje .=   "<td class='text-center' style='vertical-align:middle; font-weight:bold;'>";
			$mensaje .=     "<button type='button' class='btn btn-success btnOk' onclick='generalFunctionToRequest($recibirParametro)'><i class='fa fa-plus' aria-hidden='true'></i></button>";
			$mensaje .=   "</td>";
			$mensaje .= "</tr>";
		};//Fin while $resultados
	}; //Fin else $filas
};//Fin isset $consultaBusqueda
//Devolvemos el mensaje que tomará jQuery
$mensaje .= "</table>";
echo $mensaje;
echo "<hr>";
$getConnection->close();
?>