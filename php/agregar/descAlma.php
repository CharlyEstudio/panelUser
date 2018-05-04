<?php
$mysqliCon      = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);

$codigo = $_POST["codigo"];
$vendedor = $_POST["vendedor"];
$cliente = $_POST["cliente"];
$cantidad = $_POST["cantidad"];
$almacen = $_POST["almacen"];

$buscarProd = "SELECT *
                FROM controlinv
                WHERE codigo = $codigo";
$prodEnc = mysqli_query($mysqliCon, $buscarProd);
$rowEnc = mysqli_fetch_array($prodEnc);

$modificar      = "UPDATE controlinv ";

if($almacen == 2){
    $tx = $rowEnc["tequis"];
    $nuevoInv = $tx - $cantidad;
    if($nuevoInv < 0){
        $mensaje = "Productos Insuficientes, tenemos: ".$tx.", y se requieren: ".$cantidad.". No se procederá el apartado.";
    }else{
        $modificar .=   "SET tequis = $nuevoInv
                        WHERE codigo = $codigo";
        if($mysqliCon->query($modificar) === TRUE){
            $fecha = date("Y-m-d");
            $mensaje = "El producto se ha modificado correctamente. El código ".$codigo." está en: ".$nuevoInv.".";
            $insertar       = "INSERT INTO addsolinv(fecha, vendedor, cliente, producto, cantidad, almacen)
                                VALUES ('$fecha','$vendedor', '$cliente', '$codigo', $cantidad, $almacen)";
            $mysqliCon->query($insertar);
        } else {
            $mensaje = "No se pudo modificar por el siguiente error: " . $mysqliCon->error."";
        }
    }
}elseif($almacen == 1){
    $qro = $rowEnc["qro"];
    $nuevoInv = $qro - $cantidad;
    if($nuevoInv < 0){
        $mensaje = "Productos Insuficientes, tenemos: ".$qro.", y se requieren: ".$cantidad.". No se procederá el apartado.";
    }else{
        $modificar .=   "SET qro = $nuevoInv
                        WHERE codigo = $codigo";
        if($mysqliCon->query($modificar) === TRUE){
            $fecha = date("Y-m-d");
            $mensaje = "El producto se ha modificado correctamente. El código ".$codigo." está en: ".$nuevoInv.".";
            $insertar       = "INSERT INTO addsolinv(fecha, vendedor, cliente, producto, cantidad, almacen)
                                VALUES ('$fecha','$vendedor', '$cliente', '$codigo', $cantidad, $almacen)";
            $mysqliCon->query($insertar);
        } else {
            $mensaje = "No se pudo modificar por el siguiente error: " . $mysqliCon->error."";
        }
    }
}

echo $mensaje;

$mysqliCon->close();
?>