<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
// $rs = mysqli_query("SELECT MAX(clienteid) AS id FROM nuevoscli");
// if ($row = mysqli_fetch_row($rs)) {
//     $id = trim($row[0]);
//     $id++;
// } else {
//     $id = 1;
// }

$dir_subida     = 'docs/';

// Doc's obligatorios para Contado
$fichero_Solicitud = $dir_subida . basename($_FILES['imgSolicitud']['name']);
move_uploaded_file($_FILES['imgSolicitud']['tmp_name'], $fichero_Solicitud);

$fichero_Politica = $dir_subida . basename($_FILES['imgPolitica']['name']);
move_uploaded_file($_FILES['imgPolitica']['tmp_name'], $fichero_Politica);

$fichero_Fachada = $dir_subida . basename($_FILES['imgFachada']['name']);
move_uploaded_file($_FILES['imgFachada']['tmp_name'], $fichero_Fachada);

$fichero_Domicilio = $dir_subida . basename($_FILES['imgDomicilio']['name']);
move_uploaded_file($_FILES['imgDomicilio']['tmp_name'], $fichero_Domicilio);

//Doc's obligados para Crédito
$fichero_INEFre = $dir_subida . basename($_FILES['imgINEFre']['name']);
move_uploaded_file($_FILES['imgINEFre']['tmp_name'], $fichero_INEFre);

$fichero_INERev = $dir_subida . basename($_FILES['imgINERev']['name']);
move_uploaded_file($_FILES['imgINERev']['tmp_name'], $fichero_INERev);

$fichero_Cedula = $dir_subida . basename($_FILES['imgCedula']['name']);
move_uploaded_file($_FILES['imgCedula']['tmp_name'], $fichero_Hacienda);

$fichero_Hacienda = $dir_subida . basename($_FILES['imgHacienda']['name']);
move_uploaded_file($_FILES['imgHacienda']['tmp_name'], $fichero_Hacienda);

//Doc's obligados para Persona Moral
$fichero_Moral = $dir_subida . basename($_FILES['imgActaMoral']['name']);
move_uploaded_file($_FILES['imgActaMoral']['tmp_name'], $fichero_Moral);

$fichero_Repre = $dir_subida . basename($_FILES['imgDomRepLeg']['name']);
move_uploaded_file($_FILES['imgDomRepLeg']['tmp_name'], $fichero_Repre);

$fichero_INERepF = $dir_subida . basename($_FILES['imgINEFreLegal']['name']);
move_uploaded_file($_FILES['imgINEFreLegal']['tmp_name'], $fichero_INERepF);

$fichero_INERepR = $dir_subida . basename($_FILES['imgINERevLegal']['name']);
move_uploaded_file($_FILES['imgINERevLegal']['tmp_name'], $fichero_INERepR);

// var_dump($_FILES);

// var_dump($_POST);

$vendedorid     = $_POST["inputVendedor"];
$nombre         = utf8_decode($_POST["inputRazonSocial"]);
$comercial      = utf8_decode($_POST["inputNombreComercial"]);

if($_POST["inputAltaHaciendaSi"] === ""){
    $rfc        = utf8_decode($_POST["inputFRCNo"]);
    $fecalta    = date("Y-01-01");
} else {
    $rfc        = utf8_decode($_POST["inputFRCSi"]);
    $fecalta    = $_POST["inputAltaHaciendaSi"];
}

if($_POST["inputEmailSi"] === ""){
    $email      = utf8_decode($_POST["inputEmailNo"]);
} else {
    $email      = utf8_decode($_POST["inputEmailSi"]);
}

$direccion      = utf8_decode($_POST["inputDireccion"]);
$cp             = $_POST["inputCP"];
$colonia        = utf8_decode($_POST["inputColonia"]);
$ciudad         = utf8_decode($_POST["inputCiudad"]);
$tel            = $_POST["inputTelFijo"];
$cel            = $_POST["inputCel"];
$credito        = $_POST["inputCantidadCredito"];
$diascred       = $_POST["selectDiasCredito"];
$metpag         = utf8_decode($_POST["inputMetPago"]);
$hacerped       = utf8_decode($_POST["inputResponsableHacerPedidos"]);
$recibirped     = utf8_decode($_POST["inputResponsableRecibirPedidos"]);
$mlocal         = $_POST["inputM2"];
$tlocal         = utf8_decode($_POST["selectPropiedad"]);
$ladode         = utf8_decode($_POST["textareaALado"]);
$frentede       = utf8_decode($_POST["textareaFrente"]);
$notas          = utf8_decode($_POST["textareaNota"]);

$activo         = 'N';

$insertar       = "INSERT INTO nuevoscli(NOMBRE, COMERCIAL, RFC, FECALTA, DIRECCION, CP, COLONIA, CIUDAD, TEL, CEL, EMAIL,
                    CREDITO, DIASCRED, METPAG, HACERPED, RECIBIRPED, MLOCAL, TLOCAL, LADODE, FRENTEDE, VENDEDORID, ACTIVO,
                    IMGSOLICITUD, IMGPOLITICA, IMGFACHADA, IMGDOM, IMGINEFRE, IMGINEREV, IMGCEDULA, IMGHACIENDA, MORAL, REPRESENTANTE,
                    INEREPF, INEREPR, NOTAS)
                        VALUES ('$nombre','$comercial','$rfc',$fecalta,'$direccion',$cp,'$colonia',
                        '$ciudad','$tel','$cel','$email',$credito,$diascred,'$metpag','$hacerped',
                        '$recibirped',$mlocal,'$tlocal','$ladode','$frentede',$vendedorid, '$activo','$fichero_Solicitud',
                        '$fichero_Politica', '$fichero_Fachada', '$fichero_Domicilio','$fichero_INEFre',
                        '$fichero_INERev','$fichero_Cedula','$fichero_Hacienda','$fichero_Moral',
                        '$fichero_Repre','$fichero_INERepF','$fichero_INERepR', '$notas')";
if($mysqliCon->query($insertar) === TRUE){
    header("location: ../../intranet/index.php");
} else {
    echo "Error: " . $insertar . "<br>" . $mysqliCon->error;
    var_dump($_POST);
}
$_FILES = [];
$mysqliCon->close();
?>