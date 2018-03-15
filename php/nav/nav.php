<?php
	if(!isset($_SESSION["data"])) {
	header("Location: ../../index.php");
} else {
	require_once("../php/class.database.php");
	require_once("../php/functions/util.php");

	$paramDb = new Database();
	$getConnection = $paramDb->GetLink();
    $paramFunctions = new Util();

	$rol = $_SESSION["data"]["rol"];
	$username = $_SESSION["data"]["name"];
	$user = $_SESSION["data"]["username"];
	$id = $_SESSION["data"]["id"];
	$rfc = $_SESSION["data"]["rfc"];
	$saldo = $_SESSION["data"]["saldo"];
	$formatoSaldo = number_format($saldo);
	$limite = $_SESSION["data"]["limite"];
	$formatoLimite = number_format($limite);
	$dispo = number_format($limite - $saldo);
	$disponible = $limite - $saldo;
	$vendedor = $_SESSION["data"]["vendedor"];
	$vendedorID = $_SESSION["data"]["vendedorid"];
	$diascredito = $_SESSION["data"]["diacredito"];
	$diasvisita = $_SESSION["data"]["diavis"];
	$ultimacompra = $_SESSION["data"]["ucompra"];
	$compraReciente = $_SESSION["data"]["compraReciente"];
	$ignorasuspender = $_SESSION["data"]["ignorasuspender"];
	$activo = $_SESSION["data"]["activo"];
	$correo = $_SESSION["data"]["correo"];
	$tel = $_SESSION["data"]["tel"];
	$pas2 = $_SESSION["data"]["pas2"];
    $pasAnt = $_SESSION["data"]["pasAnt"];
    
    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
	$getDashboard = "SELECT * FROM dashboard WHERE rol ='$rol'";
    $result = mysqli_query($mysqliCon,$getDashboard);
    
    $fechaActualDia = date('d');
    $fechaActualMes = date('m');
    $UltimaComp = strtotime($ultimacompra);
    $diasUltimaCompra = idate('d' ,$UltimaComp);
    $mesUltimaCOmpra = idate('m',$UltimaComp);
    switch ($mesUltimaCOmpra) {
        case '1':
        $diasTotalMesUltimaCompra = 31;
        break;
        case '2':
        $diasTotalMesUltimaCompra = 28;
        break;
        case '3':
        $diasTotalMesUltimaCompra = 31;
        break;
        case '4':
        $diasTotalMesUltimaCompra = 30;
        break;
        case '5':
        $diasTotalMesUltimaCompra = 31;
        break;
        case '6':
        $diasTotalMesUltimaCompra = 30;
        break;
        case '7':
        $diasTotalMesUltimaCompra = 31;
        break;
        case '8':
        $diasTotalMesUltimaCompra = 31;
        break;
        case '9':
        $diasTotalMesUltimaCompra = 30;
        break;
        case '10':
        $diasTotalMesUltimaCompra = 31;
        break;
        case '11':
        $diasTotalMesUltimaCompra = 30;
        break;
        case '12':
        $diasTotalMesUltimaCompra = 31;
        break;
    }
    $sumaDeDias = $diasUltimaCompra + $diascredito;
    if($ultimacompra > 0){
        if($fechaActualMes == $mesUltimaCOmpra){
            if($sumaDeDias >= $diasTotalMesUltimaCompra){
                $diasRestantesNewMes = $sumaDeDias - $diasTotalMesUltimaCompra;
                $nextMes = $mesUltimaCOmpra + 1;
                $fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");
            } else {
                $fechaLimite = date("Y-$mesUltimaCOmpra-$sumaDeDias");//39
                $diasRestantesNewMes = $sumaDeDias;
            }
        } elseif($sumaDeDias >= $diasTotalMesUltimaCompra){
            $diasRestantesNewMes = $diasTotalMesUltimaCompra - $sumaDeDias;
            $nextMes = $mesUltimaCOmpra + 1;
            $fechaLimite = date("Y-$nextMes-$diasRestantesNewMes");//39
        } else {
            $diasRestantesNewMes = ($diasTotalMesUltimaCompra - $sumaDeDias) - $fechaActualDia;
            $fechaLimite = 'Factura(s) Vencida(s)';
            $nextMes = $mesUltimaCOmpra;
        }
    } else {
        $diasRestantesNewMes = 0;
        $fechaLimite = 'Sin Vencimiento';
        $nextMes = 0;
    }
?>
<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 panel">
    <nav class="navbar navbar-expand-lg">
        <a class="nabvar-brand">
            <!-- <img class="d-inline-block align-top" src="../img/logo2.png" width="30" height="30" /> -->
            FMO<span style="color: tomato!important;">Store</span> &beta;
        </a>
        <div class="menu">
            <ul class="nav">
            <?php
                if($disponible > 0) {
                    // at this point, call function that draw vertical nav from database for each kind of user
                    if($result) {
                        $nav = $paramFunctions->drawNavMenu($result, $disponible, $saldo, $diasRestantesNewMes, $vendedor, $ignorasuspender, $activo, $user);
                        echo $nav;
                    } // TODO do something when not find data
                } else {
                // at this point, call function that draw vertical nav from database for each kind of user
                    if($result) {
                        $nav = $paramFunctions->drawNavMenu($result, $disponible, $saldo, $diasRestantesNewMes, $vendedor, $ignorasuspender, $activo, $user);
                        echo $nav;
                    } // TODO do something when not find data
                }
            ?>
                <li class="nav-item">
					<a href="#" class="nav-link">
			<?php
				echo	'<i class="fas fa-user-circle fa-lg" aria-hidden="true"></i>
							<span>'.$username.'</span>';
			?>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../ruta/logout.php" data-toggle='tooltip' title='Salir' class="btn btn-default btn-flat">Salir</a>
				</li>
            </ul>
        </div>
    </nav>
    <div id="page-wrapper" class="body"></div>
</div>
<?php
}
?>