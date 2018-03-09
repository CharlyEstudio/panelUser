<?php

$usuario    = $_POST['usuario'];
$userInt    = (int) $usuario;
$userStr    = (string) $userInt;
$pas2       = $_POST['password'];
$email      = $_POST['email'];

$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890"; //posibles caracteres a usar
$numerodeletras=5; //numero de letras para generar el texto
$cadena = ""; //variable para almacenar la cadena generada
for($i=0;$i<$numerodeletras;$i++)
{
    $cadena .= substr($caracteres,rand(0,strlen($caracteres)),1); /*Extraemos 1 caracter de los caracteres 
entre el rango 0 a Numero de letras que tiene la cadena */
}

$pasAnt     = $cadena;

$newDat     = new DateTime();
$fecha      = $newDat->format('Y-m-d g:i:s A');

$db_host = "67.227.237.109";
$db_username = "zizaram1_datosaF";
$db_password = "dwzyGskl@@.W";
$db_database = "zizaram1_datosa";

/*echo '<script language="javascript">alert("'.$userStr.', '.$pas2.', '.$email.'");</script>';
*/
$db_connection = new mysqli($db_host,$db_username,$db_password,$db_database);
$sSQL="UPDATE usuarios SET `password`='".$pas2."', `passant`='".$pasAnt."', `datecambio`='".$fecha."' WHERE `username`='".$userStr."'";
$db_connection->query($sSQL);

// título
$título = "Cambio de Contrase&ntildea con &eacutexito";

$mensaje = utf8_decode("
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title></title>
    <style type='text/css'>
        body{
            font-family: Century Gothic;
        }
        .texto {
            font-size: 25px;
            color: #182659;
        }

        .texto-details {
            text-align: left;
            font-size: 18px;
            color: #182659;
        }

        .letras {
            font-size: 18px;
        }
    </style>
</head>
<body>
<table>
    <tbody>
    <tr>
        <td>
            <left>
                <img src='http://www.heilaguna.com/images/slides/slide8.jpg' width='1000'>
            </left>
        </td>
    </tr>
    <tr>
        <td>
            <p class='texto' align='center'>Tu contraseña se actualizo correctamente, el cual tiene una vigencia de 30 días a partir de este momento.</p>
        </td>
    </tr>
    <tr>
        <td>
            <p align='center' class='letras'>
                <b class='texto-details'>Usuario :</b> ".$usuario."
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p align='center' class='letras'>
                <b class='texto-details'>Fecha y Hora de la Solicitud: </b>".$fecha."</p>
        </td>
    </tr>
    <!--<tr>
        <td>
            <p align='center' class='letras'>
                <b class='texto-details'>El comentario es: </b></p>
        </td>
    </tr>-->
    <tr>
        <td>
            <left>
                <img src='http://cdn6.bigcommerce.com/s-i9htebe/product_images/theme_images/truper_tools_banner__99986.jpg?t=1467763773' width='1000'>
            </left>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
");

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras = 'FROM: Ferremayoristas Olvera S.A. de C.V.' . "\r\n";
$cabeceras .= 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Enviarlo
mail("direccion@codigototal.com.mx", $título, $mensaje, $cabeceras);
mail("vleal@ferremayoristas.com.mx", $título, $mensaje, $cabeceras);
mail($email, $título, $mensaje, $cabeceras);
if(!mail){
    session_start();
    session_unset();
    session_destroy();
    echo '<script language="javascript">alert("Hubo un problema en el cambio de contraseña, favor de ponerse en contacto con el Administrador.");document.location="../../login/index.php";</script>'; 
}else{
    session_start();
    session_unset();
    session_destroy();
    echo '<script language="javascript">alert("Su contraseña se cambio correctamente, debe de inciar sesión con los nuevos datos. Gracias!.");document.location="../../login/index.php";</script>';   
}
?>