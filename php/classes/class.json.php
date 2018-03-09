<?php
require_once("../class.database.php");
require_once("../functions/util.php");
require_once("../functions/dml.php");
$paramDb 			= new Database();
$getConnection 		= $paramDb->GetLink();
$getAllProducts 	= "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento, a.existencia
						FROM inv i
							JOIN precios pre ON pre.unidadid = i.unibasid
							JOIN alm a ON a.articuloid = i.articuloid
						WHERE i.clvprov > 0
							AND a.existencia > 0
						ORDER BY i.clvprov";
$exeQuGetAll 		= $paramDb->Query($getAllProducts);
$rows 				= $paramDb->Rows();
$numRow				= $paramDb->NumRows();
$array				= array();
foreach($rows as $row)
{
	$array[] 		= $row;
}
$json 				= json_encode($array);
$result 			= json_decode($json);
?>
<!-- <!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>DATOS EN EL JASON A PHP</title>
		<link rel="stylesheet" href="">
	</head>
	<body>
		<table>
			<thead>
				<tr>
					<th>#</th>
					<th>ID</th>
					<th>Clave</th>
					<th>Código</th>
					<th>Descripción</th>
					<th>ID Precio</th>
					<th>Precio</th>
					<th>I.V.A.</th>
					<th>Alta</th>
					<th>Desc. %</th>
				</tr>
			</thead>
			<tbody> -->
<?php
var_dump($result);
//para reemplazar los caracteres especiales
/*$replace = array(
	'¥' => 'Ñ',
	'à' => 'Ó',
	'é' => '',
	'µ' => 'Á'
);
for($i=0; $i<count($result); $i++)
{
	$articuloid 	= $result[$i]->articuloid;
	$clave			= $result[$i]->clave;
	$clvprov		= $result[$i]->clvprov;
	$descripcio		= $result[$i]->descripcio;
	$unibasid		= $result[$i]->unibasid;
	$nprecio		= $result[$i]->precio;
	$impuesto		= $result[$i]->pimpuesto;
	$fecaltart		= $result[$i]->fecaltart;
	$invdescuento	= $result[$i]->invdescuento;

	echo 		"<tr>
					<td>".$i."</td>
					<td>".utf8_encode($articuloid)."</td>
					<td>".utf8_encode($clave)."</td>
					<td>".utf8_encode($clvprov)."</td>
					//la forma de aplicar el reemplazo
					<td>".strReplaceAssoc($replace,$descripcio)."</td>
					<td>".utf8_encode($unibasid)."</td>
					<td>".utf8_encode($nprecio)."</td>
					<td>".utf8_encode($impuesto)."</td>
					<td>".utf8_encode($fecaltart)."</td>
					<td>".utf8_encode($invdescuento)."</td>
				</tr>";
}

//funcion para reemplazar
function strReplaceAssoc(array $replace, $subject) { 
   return str_replace(array_keys($replace), array_values($replace), $subject);    
}*/
?>
			</tbody>
		</table>
	</body>
</html>
