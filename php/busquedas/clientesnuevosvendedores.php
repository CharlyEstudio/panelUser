<?php
    include('../class.database.php');
    require_once("../functions/util.php");
    date_default_timezone_set('America/Mexico_City');
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $month = date('m');
    $year = date('Y');
    $dayVtaTotMes = date("d", mktime(0,0,0, $month+1, 0, $year));
    $primerDiaMes = date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    $ultimoDiaMes = date('Y-m-d', mktime(0,0,0, $month, $dayVtaTotMes, $year));

    $queryVendedores = "SELECT perid, nombre FROM per WHERE grupo = 'MV' AND categoria NOT LIKE '00' AND sermov > 0";
    $vendedoresEncontrados = mysqli_query($getConnection,$queryVendedores);
    $numRow = mysqli_num_rows($vendedoresEncontrados);
    $i = 1;
?>
                <div id="tablaClientesNuevos" class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">VENDEDOR</th>
                                <th scope="col">CLIENTES NUEVOS</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
    while($row= mysqli_fetch_array($vendedoresEncontrados)){
        $vendedor = $row["nombre"];
        $perid = $row["perid"];
    
        $queryBuscarCli = "SELECT COUNT(c.clienteid) as cantidad
                            FROM cli c
                            WHERE c.vendedorid = $perid
                                AND (
                                    c.fecaltcli < '".$ultimoDiaMes."'
                                    AND c.fecaltcli > '".$primerDiaMes."'
                                    )
                                AND c.catalogo NOT LIKE 'W'";
        $clientesEncontrados = mysqli_query($getConnection, $queryBuscarCli);
        $rowC = mysqli_fetch_array($clientesEncontrados);
        $numCl = $rowC["cantidad"];
?>
                            <tr class="text-center">
                                <th scope="row"><?php echo $i ?></th>
                                <td><?php echo $vendedor ?></td>
                                <td class="text-tomato"><?php echo $numCl ?></td>
                            </tr>
<?php
      $i++;
    }
?>
                        </tbody>
                    </table>
                </div>