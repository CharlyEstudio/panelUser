<?php
require_once("../class.database.php");
require_once("../functions/util.php");
require_once("../functions/dml.php");

class Product {
  public function getterFindPublicProductBy($params) {
    $this->findPublicProductBy($params);
  }
  public function getterClickProductCategoryBy($params) {
    $this->clickProductCategoryBy($params);
  }
  public function getterClickProductMarcaBy($params) {
    $this->clickProductMarcaBy($params);
  }
  public function getterClickProductInterestBy($params) {
    $this->clickProductInterestBy($params);
  }
  public function getterFindCrudProductBy($params) {
    $this->findCrudProductBy($params);
  }
  public function getterGetProducts($params) {
    $this->getProducts($params);
  }

  public function getterGetProductsCrud() {
    $this->getProductsCrud();
  }

  public function getterGetProductToUpdateCrud($params) {
    $this->getProductToUpdateCrud($params);
  }

  public function getterNewProductCrud() {
    $this->newProductCrud();
  }

  public function getterSaveNewProductCrud($params) {
    $this->saveNewProductCrud($params);
  }

  public function getterSetProductToupdateCrud($params) {
    $this->setProductToupdateCrud($params);
  }


  private function findPublicProductBy($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $findPublicProductBy = $paramDb->SecureInput($params["findPublicProductBy"]);

    $getproducts = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.codbar, i.unibasid, i.invdescuento, pre.nprecio, pre.precio, pre.pimpuesto, uni.nunidad, i.fecaltart, img.imagen
                    FROM inv i
                      JOIN precios pre ON pre.unidadid = i.unibasid
                      JOIN unidades uni ON uni.unidadid = i.unibasid
                      JOIN imagenes img ON img.codigo = i.clvprov
                      WHERE
                        (
                          i.clave LIKE '$findPublicProductBy%'
                          OR i.clvprov LIKE '$findPublicProductBy%'
                          OR i.descripcio LIKE '%$findPublicProductBy%'
                        )
                      AND pre.nprecio = 3
                    ORDER BY i.clvprov";

    $exeQuGetPagination = $paramDb->Query($getproducts);
    if($exeQuGetPagination === false) {
      echo "error-query";
      return false;
    }

    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();

    $result = $getConnection->query($getproducts);
    $numero = $result->num_rows;
    $numero = number_format($numero, 0, '', ',');

    if($numero){
      echo "<div class='seguimiento-enlaces col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'><a class='nav-link' href='index.php'>Ir al Inicio ></a><p>Su busqueda de <strong>\"".$findPublicProductBy."\"</strong> obtuvo <strong>".$numero."</strong> resultados</p></div>";
      echo "<div class='flex-container productos'>";
      $print = "<div class='flex-container fixed-panel4 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
      $print .= "<div class='row'>";

      foreach($rows as $row) {
        $id = $row["clvprov"];
        $titulo = $row["descripcio"];
        $clave = $row["clave"];
        $alta = $row["fecaltart"];
        $precio = $row["precio"];
        $impuesto = $row["pimpuesto"];
        $impuesto = $impuesto / 100;
        $sacariva = $precio * $impuesto;
        $resultado = $precio + $sacariva;
        $total = number_format($resultado, 2);
        $articuloid = $row["articuloid"];
        $des = $row["invdescuento"];

        $codigo = $row["clvprov"];
        $imagen = $row["imagen"];

        $location = "'listProducts'";

        $paramsModal = array("productID"=>$id,
                        "alta"=>$alta,
                        "location"=>"showModalFindPublicProduct",
                        "section"=>"findPublicProduct",
                        "url"=>"php/product/product.php",
                        "booleanResponse"=>false,
                        "divResultID"=>"resultModalProduct",
                        "msgSuccess"=>"Ok!",
                        "msgError"=>"Error al mostrar informacion del producto");
        $paramsModalSend = json_encode($paramsModal);

        if($numRow == 1){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
        } elseif($numRow == 2){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-6 col-lg-6 col-xs-6'>";
        } elseif($numRow == 3){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-4 col-lg-4 col-xs-4'>";
        } elseif($numRow >= 4){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-3 col-lg-3 col-xs-3'>";
        }

        $print .=   "<div class='product-image'>";

        if($des > 0) {
          $icono = number_format($des);
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' style='position:relative !important;'/></a>";
          $print .=     "<img src='img/iconos/".$icono."porciento2000x763.png' alt='...' style='width:100px !important;position: absolute !important;margin-left:-40px;'/>";
        } else {
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' /></a>";
        }

        $print .=    "</div>"; // image
        $print .=    "<div class='product-info'>";
        $print .=     "<label class='margin-top-3 lead' id='titulo$id'>$titulo</label>";
        $print .=     "<label class='margin-top-3 color lead' id ='clave$id'>Clave: $clave</label>";
        $print .=     "<label class='margin-top-3 color lead'>$ $total MXN</label>";
        $print .=    "</div>"; // product-info
        $print .=   "</div>"; // flex-item
      }
      $print .=      "<div id='resultModalProduct'></div>";
      $print .=    "</div>"; //row
      $print .= "</div>"; // flex-container
      echo $print;
      echo "<div class='seguimiento-enlaces text-center'><span>Si no encuentras el producto deseado, contáctanos y con gusto te atenderemos!</span></div>";
    } else {
      echo  "<div class='row'>
            <div class='alert alert-warning' role='alert' style='margin-bottom:0 !important; height: 100vh; width: 100%; text-align:center; display: flex; justify-content: center; align-items: center; flex-direction: column;'>
              <h4 class='alert-heading' style='font-size: 3em;'>En mantenimiento.</h4>
              <p style='font-size: 1.5em;'>En estos momentos nuestro servidor está en mantenimiento y no está disponible los productos en nuestra tienda. Agradecemos su paciencia y su comprensión. Estará disponible lo más pronto posible.</p>
              <p class='mb-0' style='font-size: 1.5em;'>Si necesita un producto en especifico le solicitamos que envíe un correo a <strong>contacto@ferremayoristas.com.mx</strong>, con los datos del/los producto(s).</p>
            </div>
          </div>";
    }
    $getConnection->close();
  }

  private function clickProductCategoryBy($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $clickProductCategoryBy = $paramDb->SecureInput($params['clickProductCategoryBy']);

    $clickcategory = "SELECT i.ARTICULOID, i.clvprov as productoID, i.descripcio, i.clave, i.fecaltart, i.unibasid, i.invdescuento, pre.precio, pre.pimpuesto
    FROM inv i
          JOIN precios pre ON pre.articuloid = i.articuloid
            JOIN enl e ON e.codigo = i.clvprov
            JOIN categoria cat ON cat.id = e.categoriaID
        WHERE cat.id = $clickProductCategoryBy
          AND pre.unidadid = i.unibasid
          AND pre.nprecio = 3
        ORDER BY i.clvprov";

    $executeQuery = $paramDb->Query($clickcategory);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }

    $consulta = "SELECT categoria FROM categoria WHERE id = ".$clickProductCategoryBy."";
    $result = mysqli_query($getConnection,$consulta);
    $row2 = mysqli_fetch_array($result);
    $categoria = $row2[0];

    $numRow = $paramDb->NumRows();
    $resultados = number_format($numRow, 0, '',',');
    $rows = $paramDb->Rows();

    if($numRow){
      echo "<div class='seguimiento-enlaces col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'><a class='nav-link' href='index.php'>Ir al Inicio ></a><p>La categoria <strong>".$categoria."</strong> obtuvo <strong>".$resultados."</strong> resultados</p></div>";
      echo "<div class='flex-container productos'>";
      $print = "<div class='flex-container fixed-panel4 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
      $print .= "<div class='row'>";

      foreach($rows as $row) {
        $id = $row["productoID"];
        $titulo = $row["descripcio"];
        $clave = $row["clave"];
        $pre = $row["precio"];
        $alta = $row["fecaltart"];
        $desc = $row["invdescuento"];
        $impuesto = $row["pimpuesto"];
        $impuesto = $impuesto / 100;
        $iva = $pre * $impuesto;
        $importe = $pre + $iva;
        $pf = number_format($importe,2);
        $location = "'listProducts'";

        $getanexo = "SELECT im.imagen, promo.icono, promo.id as promotruperID
                FROM imagenes im
                  JOIN enl e ON e.codigo = im.codigo
                  JOIN promotruper promo ON promo.id = e.promotruperid
                WHERE im.codigo = $id";

        $consultanexo = mysqli_query($getConnection,$getanexo);
        $fila = mysqli_fetch_array($consultanexo);
        $imagen = $fila[0];
        $icono = $fila[1];
        $promoID = $fila[2];

        $paramsModal = array("productID"=>$id,
                        "alta"=>$alta,
                        "location"=>"showModalFindPublicProduct",
                        "section"=>"clickProductCategory",
                        "url"=>"php/product/product.php",
                        "booleanResponse"=>false,
                        "divResultID"=>"resultModalProduct",
                        "msgSuccess"=>"Ok!",
                        "msgError"=>"Error al mostrar informacion del producto");
        $paramsModalSend = json_encode($paramsModal);

        if($numRow == 1){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
        } elseif($numRow == 2){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-6 col-lg-6 col-xs-6'>";
        } elseif($numRow == 3){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-4 col-lg-4 col-xs-4'>";
        } elseif($numRow >= 4){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-3 col-lg-3 col-xs-3'>";
        }

        $print .=   "<div class='product-image'>";

        $des = number_format($desc);
        if($des > 0) {
          $icono = number_format($des);
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' style='position:relative !important;'/></a>";
          $print .=     "<img src='img/iconos/".$icono."porciento2000x763.png' alt='...' style='width:100px !important;position: absolute !important;margin-left:-40px;'/>";
        } else {
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' /></a>";
        }

        $print .=    "</div>"; // image
        $print .=    "<div class='product-info'>";
        $print .=     "<label class='margin-top-3 lead' id='titulo$id'>$titulo</label>";
        $print .=     "<label class='margin-top-3 color lead' id ='clave$id'>Clave: $clave</label>";
        $print .=     "<label class='margin-top-3 color lead'>$ $pf MXN</label>";
        $print .=     "</div>"; // product-info
        $print .=   "</div>"; // flex-item
      }
      $print .=      "<div id='resultModalProduct'></div>";
      $print .=   "</div>";
      $print .= "</div>"; // flex-container
      echo $print;
    } else {
      echo  "<div class='row'>
            <div class='alert alert-warning' role='alert' style='margin-bottom:0 !important; height: 100vh; width: 100%; text-align:center; display: flex; justify-content: center; align-items: center; flex-direction: column;'>
              <h4 class='alert-heading' style='font-size: 3em;'>En mantenimiento.</h4>
              <p style='font-size: 1.5em;'>En estos momentos nuestro servidor está en mantenimiento y no está disponible los productos en nuestra tienda. Agradecemos su paciencia y su comprensión. Estará disponible lo más pronto posible.</p>
              <p class='mb-0' style='font-size: 1.5em;'>Si necesita un producto en especifico le solicitamos que envíe un correo a <strong>contacto@ferremayoristas.com.mx</strong>, con los datos del/los producto(s).</p>
            </div>
          </div>";
    }
    $getConnection->close();
  }

  private function clickProductMarcaBy($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $clickProductMarcaBy = $paramDb->SecureInput($params['clickProductMarcaBy']);

    $clickcategory = "SELECT i.clvprov as productoID, i.descripcio, i.clave, pre.precio, i.fecaltart, i.invdescuento, pre.pimpuesto
        FROM inv i
            JOIN precios pre ON pre.articuloid = i.articuloid
            JOIN enl e ON e.codigo = i.clvprov
            JOIN marca mar ON mar.id = e.marcaID
        WHERE mar.id = $clickProductMarcaBy
          AND pre.unidadid = i.unibasid
          AND pre.nprecio =  3 
        ORDER BY i.clvprov";

    $executeQuery = $paramDb->Query($clickcategory);
    if($executeQuery === false) {
      echo "error-query";
      return false;
    }

    $consulta = "SELECT nombre FROM marca WHERE id = ".$clickProductMarcaBy."";
    $result = mysqli_query($getConnection,$consulta);
    $row2 = mysqli_fetch_array($result);
    $marca = $row2[0];

    $numRow = $paramDb->NumRows();
    $resultados = number_format($numRow, 0, '',',');
    $rows = $paramDb->Rows();

    if($numRow){
      echo "<div class='seguimiento-enlaces col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'><a class='nav-link' href='index.php'>Ir al Inicio ></a><p>La marca <strong>".$marca."</strong> obtuvo <strong>".$resultados."</strong> resultados</p></div>";
      echo "<div class='flex-container productos'>";
      $print = "<div class='flex-container fixed-panel4 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
      $print .= "<div class='row'>";

      foreach($rows as $row) {
        $id = $row["productoID"];
        $titulo = $row["descripcio"];
        $clave = $row["clave"];
        $pre = $row["precio"];
        $alta = $row["fecaltart"];
        $impuesto = $row["pimpuesto"];
        $impuesto = $impuesto / 100;
        $des = $row["invdescuento"];

        $consulta = "SELECT imagen FROM imagenes WHERE codigo = ".$id."";
        $result = mysqli_query($getConnection,$consulta);
        $row2 = mysqli_fetch_array($result);
        $imagen = $row2[0];

        $sacariva = $pre * $impuesto;
        $iva = number_format($sacariva,2);
        $importe = $pre + $sacariva;
        $pf = number_format($importe,2);
        $location = "'listProducts'";

        $paramsModal = array("productID"=>$id,
                        "alta"=>$alta,
                        "location"=>"showModalFindPublicProduct",
                        "section"=>"clickProductMarca",
                        "url"=>"php/product/product.php",
                        "booleanResponse"=>false,
                        "divResultID"=>"resultModalProduct",
                        "msgSuccess"=>"Ok!",
                        "msgError"=>"Error al mostrar informacion del producto");
        $paramsModalSend = json_encode($paramsModal);

        if($numRow == 1){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
        } elseif($numRow == 2){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-6 col-lg-6 col-xs-6'>";
        } elseif($numRow == 3){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-4 col-lg-4 col-xs-4'>";
        } elseif($numRow >= 4){
          $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-3 col-lg-3 col-xs-3'>";
        }

        $print .=   "<div class='product-image'>";

        $desc= number_format($des);
        if($desc > 0) {
          $icono = $desc;
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' style='position:relative !important;'/></a>";
          $print .=     "<img src='img/iconos/".$icono."porciento2000x763.png' alt='...' style='width:100px !important;position: absolute !important;margin-left:-40px;'/>";
        } else {
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' /></a>";
        }

        $print .=    "</div>"; // image
        $print .=    "<div class='product-info'>";
        $print .=     "<label class='margin-top-3 lead' id='titulo$id'>$titulo</label>";
        $print .=     "<label class='margin-top-3 color lead' id ='clave$id'>Clave: $clave</label>";
        $print .=     "<label class='margin-top-3 color lead'>$ $pf MXN</label>";
        $print .=     "</div>"; // product-info
        $print .=   "</div>"; // flex-item
      }
      $print .=      "<div id='resultModalProduct'></div>";
      $print .=   "</div>";
      $print .= "</div>"; // flex-container
      echo $print;
    } else {
      echo  "<div class='row'>
            <div class='alert alert-warning' role='alert' style='margin-bottom:0 !important; height: 100vh; width: 100%; text-align:center; display: flex; justify-content: center; align-items: center; flex-direction: column;'>
              <h4 class='alert-heading' style='font-size: 3em;'>En mantenimiento.</h4>
              <p style='font-size: 1.5em;'>En estos momentos nuestro servidor está en mantenimiento y no está disponible los productos en nuestra tienda. Agradecemos su paciencia y su comprensión. Estará disponible lo más pronto posible.</p>
              <p class='mb-0' style='font-size: 1.5em;'>Si necesita un producto en especifico le solicitamos que envíe un correo a <strong>contacto@ferremayoristas.com.mx</strong>, con los datos del/los producto(s).</p>
            </div>
          </div>";
    }
    $getConnection->close();    
  }

  private function clickProductInterestBy($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();
    $clickProductInterestBy = $paramDb->SecureInput($params['clickProductInterestBy']);

    $getinteres = "SELECT i.clvprov as productoID, i.descripcio, i.clave, pre.precio, i.fecaltart, i.invdescuento, pre.pimpuesto
        FROM inv i
            JOIN precios pre ON pre.articuloid = i.articuloid
        WHERE i.clvprov = $clickProductInterestBy
          AND pre.unidadid = i.unibasid
          AND pre.nprecio =  3 
        ORDER BY i.clvprov";

    $listint = mysqli_query($getConnection,$getinteres);
    $filaint = mysqli_fetch_array($listint);

    if($filaint){
      $getnomarca = "SELECT mar.nombre FROM marca mar
                        JOIN enl e ON e.marcaID = mar.id
                      WHERE e.codigo = $clickProductInterestBy";

      $nomarca = mysqli_query($getConnection,$getnomarca);
      $filanom = mysqli_fetch_array($nomarca);

      if($filanom > 0){
        $marca = $filanom[0];
        echo "<div class='seguimiento-enlaces col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'><a class='nav-link' href='index.php'>Ir al Inicio ></a><p>Este producto es de la marca <strong>".$marca."</strong></p></div>";
      } else {
        echo "<div class='seguimiento-enlaces col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'><a class='nav-link' href='index.php'>Ir al Inicio ></a><p>Este producto es <strong>Nuevo</strong></p></div>";
      }
      echo "<div class='flex-container productos'>";
      $print = "<div class='flex-container fixed-panel4 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
      $print .= "<div class='row'>";

      $id = $filaint[0];
      $titulo = $filaint[1];
      $clave = $filaint[2];
      $pre = $filaint[3];
      $alta = $filaint[4];
      $des = $filaint[5];
      $desc = number_format($des);
      $impuesto = $filaint[6];
      $impuesto = $impuesto / 100;

      $getimg = "SELECT imagen FROM imagenes WHERE codigo = ".$id."";
      $imgenc = mysqli_query($getConnection,$getimg);
      $filaimg = mysqli_fetch_array($imgenc);

      if($filaimg > 0){
        $imagen = $filaimg[0];
      } else {
        $imagen = "newpro.gif";
      }

      $sacariva = $pre * $impuesto;
      $iva = number_format($sacariva,2);
      $importe = $pre + $sacariva;
      $pf = number_format($importe,2);
      $location = "'listProducts'";

      $paramsModal = array("productID"=>$id,
                      "alta"=>$alta,
                      "location"=>"showModalFindPublicProduct",
                      "section"=>"clickProductMarca",
                      "url"=>"php/product/product.php",
                      "booleanResponse"=>false,
                      "divResultID"=>"resultModalProduct",
                      "msgSuccess"=>"Ok!",
                      "msgError"=>"Error al mostrar informacion del producto");
      $paramsModalSend = json_encode($paramsModal);

      $print .= "<div class='flex-item2 col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
      $print .=   "<div class='product-image'>";

      if($desc > 0) {
        $icono = $desc;
        $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' style='position:relative !important;'/></a>";
        $print .=     "<img src='img/iconos/".$icono."porciento2000x763.png' alt='...' style='width:100px !important;position: absolute !important;margin-left:-40px;'/>";
      } else {
        $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$imagen' alt='$titulo' /></a>";
      }

      $print .=    "</div>"; // image
      $print .=     "<div class='product-info'>";
      $print .=      "<a class='nav-link' href='#' onclick='generalFunctionToRequest($paramsModalSend)'><label class='margin-top-3 lead' id='titulo$id'>$titulo</label>";
      $print .=      "<label class='margin-top-3 color lead' id ='clave$id'>Clave: $clave</label>";
      $print .=      "<label class='margin-top-3 color lead'>$ $pf MXN</label></a>";
      $print .=     "</div>"; // product-info
      $print .=    "</div>"; // flex-item
      $print .=   "<div id='resultModalProduct'></div>";
      $print .=  "</div>";
      $print .= "</div>"; // flex-container
      echo $print;
    } else {
      echo  "<div class='row'>
            <div class='alert alert-warning' role='alert' style='margin-bottom:0 !important; height: 100vh; width: 100%; text-align:center; display: flex; justify-content: center; align-items: center; flex-direction: column;'>
              <h4 class='alert-heading' style='font-size: 3em;'>En mantenimiento.</h4>
              <p style='font-size: 1.5em;'>En estos momentos nuestro servidor está en mantenimiento y no está disponible los productos en nuestra tienda. Agradecemos su paciencia y su comprensión. Estará disponible lo más pronto posible.</p>
              <p class='mb-0' style='font-size: 1.5em;'>Si necesita un producto en especifico le solicitamos que envíe un correo a <strong>contacto@ferremayoristas.com.mx</strong>, con los datos del/los producto(s).</p>
            </div>
          </div>";
    }
    $getConnection->close();
  }

  // TODO check bug, not display all records pef row $itemsPerPage = 7 and $baseLimitPage= 10
  // TODO verificar BUG si se deja JOIN imagenes img ON p.id = img.valorRelacion
  private function getProducts($params) {
    $paramDb = new Database();
    $getConnection = $paramDb->GetLink();

    $getAllProducts = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento
                      FROM inv i
                        JOIN precios pre ON pre.unidadid = i.unibasid
                      WHERE i.invdescuento > 0
                        AND pre.nprecio = 3
                      ORDER BY i.clvprov";

    //EVENTO ESPECIAL
    /*$getAllProducts = "SELECT i.clave
                      FROM inv i
                        JOIN precios pre ON pre.unidadid = i.unibasid
                      WHERE (
                              i.clvprov = 14292
                              OR i.clvprov = 14250
                              OR i.clvprov = 18646
                              OR i.clvprov = 24088
                              OR i.clvprov = 24089
                              OR i.clvprov = 24090
                              OR i.clvprov = 11740
                              OR i.clvprov = 21830
                              OR i.clvprov = 14412
                              OR i.clvprov = 17160
                              OR i.clvprov = 17256
                              OR i.clvprov = 18631
                              OR i.clvprov = 22502
                              OR i.clvprov = 22511
                              OR i.clvprov = 14412
                              OR i.clvprov = 17160
                              OR i.clvprov = 17256
                              OR i.clvprov = 18631
                              OR i.clvprov = 22502
                              OR i.clvprov = 22511
                              OR i.clvprov = 24053
                            )
                        AND pre.nprecio = 1
                      ORDER BY i.clvprov";*/

    $exeQuGetAll = $paramDb->Query($getAllProducts);
    if($exeQuGetAll === false) {
      echo "error-query";
      return false;
    }

    $totalNumberRows = $paramDb->NumRows();

    if($totalNumberRows > 0) {
      $itemsPerPage = 8;  // items will be display per page
      $baseLimitPage = 10; // record number to will be display on pagination bar
      $totalPages = ceil($totalNumberRows / $itemsPerPage); // total of pages, for segments of $itemsPerPage; not confuse with count(*) productos

      if(isset($params["parametersRequest"]["currentPage"])) {
        $currentPage = $paramDb->SecureInput($params["parametersRequest"]["currentPage"]);
        $limitPages = $paramDb->SecureInput($params["parametersRequest"]["limitPages"]);
        // depend on what page the user is, is the total record, for example if the user
        // is on main page start = 0, if user is on second page start = $itemsPerPage + $itemsPerPage
        // this way constructing query LIMIT 15, 0 or LIMIT 30, 15; depends which pages the user is and value of $itemsPerPage, in this example = 15.
        $start = ($currentPage - 1) * $itemsPerPage;
      } else {
        // on first page doesn't exist variables, assign init value
        $start = 0;
        $currentPage = 1;
        $limitPages = $baseLimitPage;
      }

      $getproducts = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento
                      FROM inv i
                        JOIN precios pre ON pre.unidadid = i.unibasid
                      WHERE i.invdescuento > 0
                        AND pre.nprecio = 3
                      ORDER BY i.clvprov
                      LIMIT $start, $itemsPerPage";

      //EVENTO ESPECIAL
      /*$getproducts = "SELECT i.articuloid, i.clave, i.clvprov, i.descripcio, i.unibasid, pre.nprecio, pre.precio, pre.pimpuesto, i.fecaltart, i.invdescuento
                      FROM inv i
                        JOIN precios pre ON pre.unidadid = i.unibasid
                      WHERE (
                              i.clvprov = 14292
                              OR i.clvprov = 14250
                              OR i.clvprov = 18646
                              OR i.clvprov = 24088
                              OR i.clvprov = 24089
                              OR i.clvprov = 24090
                              OR i.clvprov = 11740
                              OR i.clvprov = 21830
                              OR i.clvprov = 14412
                              OR i.clvprov = 17160
                              OR i.clvprov = 17256
                              OR i.clvprov = 18631
                              OR i.clvprov = 22502
                              OR i.clvprov = 22511
                              OR i.clvprov = 14412
                              OR i.clvprov = 17160
                              OR i.clvprov = 17256
                              OR i.clvprov = 18631
                              OR i.clvprov = 22502
                              OR i.clvprov = 22511
                              OR i.clvprov = 24053
                            )
                        AND pre.nprecio = 1
                      ORDER BY i.clvprov
                      LIMIT $start, $itemsPerPage";*/

      $exeQuGetPagination = $paramDb->Query($getproducts);
      if($exeQuGetPagination === false) {
        echo "error-query";
        return false;
      }

      $numRow = $paramDb->NumRows();
      $rows = $paramDb->Rows();

      if($currentPage < $baseLimitPage) {
        $initLimitPages = 1;
        $limitPages = $baseLimitPage;
        if($limitPages > $totalPages) {
          $limitPages = $totalPages;
        }
      }
      else if($currentPage < $limitPages) {
        $initLimitPages = $limitPages - $baseLimitPage;
      }
      else if($currentPage == $limitPages) {
        $initLimitPages = $currentPage;
        $limitPages = $currentPage + $baseLimitPage;
        // when limit pages it's major to totalPages, assign value totalPages; because
        // doesn't exist major number pages than total pages
        if($limitPages > $totalPages) {
          $initLimitPages = $totalPages - $baseLimitPage;
          // to fixed bug when baseLimitPage it's equal to total pages
          if($initLimitPages == 0) {
            $initLimitPages = 1;
          }
          $limitPages = $totalPages;
        }
      }

      if($currentPage < $initLimitPages) {
        $initLimitPages = $initLimitPages - $baseLimitPage;
        $limitPages = $limitPages - $baseLimitPage;
      }

      $paramsPagination = array("url" => "php/product/product.php",
                      "booleanResponse" => false,
                      "location" => "getproducts",
                      "divResultID" => "information",
                      "msgSuccess" => "Ok!",
                      "msgError" => "Error en paginacion de productos");
      // Mes y A単o
      $date=new DateTime();
      $result = $date->format('m');
      $year=new DateTime();
      $result2 = $year->format('Y');
      switch ($result) {
        case 1:
          $mes='Enero';
        break;
        case 2:
          $mes='Febrero';
        break;
        case 3:
          $mes='Marzo';
        break;
        case 4:
          $mes='Abril';
        break;
        case 5:
          $mes='Mayo';
        break;
        case 6:
          $mes='Junio';
        break;
        case 7:
          $mes='Julio';
        break;
        case 8:
          $mes='Agosto';
        break;
        case 9:
          $mes='Septiembre';
        break;
        case 10:
          $mes='Octubre';
        break;
        case 11:
          $mes='Noviembre';
        break;
        case 12:
          $mes='Diciembre';
        break;
      }
      echo "<div class='seguimiento-enlaces'><span><strong>PROMOTRUPER</strong> del mes de <strong>".$mes."</strong> <strong>".$result2."</strong> /<span></div>";
     /* echo "<div class='seguimiento-enlaces'><span><strong>PRECIO ESPECIAL #MEXICODEPIE</strong> /<span></div>";*/
      // NOTE encoding json, it's apply in structure.php
      echo "<div class='flex-container productos scroll-pro col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
      echo "<div class='row'>";

      foreach($rows as $row) {
        $id = $row["unibasid"];
        $titulo = $row["descripcio"];
        $clave = $row["clave"];
        $codigo = $row["clvprov"];
        $alta = $row["fecaltart"];
        $des = $row["invdescuento"];
        $impuesto = $row["pimpuesto"];
        
        $location = "'listProducts'";

        $paramsModal = array("productID"=>$codigo,
                        "alta"=>$alta,
                        "location"=>"showModalPublicProduct",
                        "section"=>"publicProduct",
                        "url"=>"php/product/product.php",
                        "booleanResponse"=>false,
                        "divResultID"=>"resultModalProduct",
                        "msgSuccess"=>"Ok!",
                        "msgError"=>"Error al mostrar informacion del producto");
        $paramsModalSend = json_encode($paramsModal);

        if($des){
          $getimagen = "SELECT imagen
                      FROM imagenes
                      WHERE codigo = $codigo";

          $getresimg = mysqli_query($getConnection,$getimagen);
          $imgenc = mysqli_fetch_array($getresimg);
          //var_dump($imgenc);

          if($imgenc){
            $img = $imgenc[0];
          } else {
            $img = 'newpro.gif';
          }

          $icono = number_format($des);

          $desc = $des / 100;
          $precio = $row["precio"];
          $precio = $precio - ($precio * $desc);
          $impuesto = $impuesto / 100;
          $sacariva = $precio * $impuesto;
          $iva = number_format($sacariva,2);
          $importe = $precio + $sacariva;
          $pf = number_format($importe,2);
        } else {
          $des = 0;
          $img = 'new.gif';
          $icono = 'productonuevo2000x763.png';
        }

        if($numRow == 1){
          $print = "<div class='flex-item col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
        } elseif($numRow == 2){
          $print = "<div class='flex-item col-12 col-sm-12 col-md-6 col-lg-6 col-xs-6'>";
        } elseif($numRow == 3){
          $print = "<div class='flex-item col-12 col-sm-12 col-md-4 col-lg-4 col-xs-4'>";
        } elseif($numRow >= 4){
          $print = "<div class='flex-item col-12 col-sm-12 col-md-3 col-lg-3 col-xs-3'>";
        }

        $print .=   "<div class='product-image'>";

        if($des > 0) {
          $icono = number_format($des);
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$img' alt='$titulo' style='position:relative !important;'/></a>";
          $print .=     "<img src='img/iconos/".$icono."porciento2000x763.png' alt='...' style='width:100px !important;position: absolute !important;margin-left:-40px;'/>";
        } else {
          $print .=     "<a href='#' onclick='generalFunctionToRequest($paramsModalSend)'><img class='tamaño img-fluid' src='img/img_pro/img/$img' alt='$titulo' /></a>";
        }
        //EVENTO ESPECIAL
        /*$print .=     "<a><img class='tamaño img-fluid' src='img/img_pro/img/$img' alt='$titulo' style='position:relative !important;'/></a>";
        $print .=     "<img src='img/iconos/precioespecialmexicodepie.png' alt='...' style='width:100px !important;position: absolute !important;margin-left:-40px;'/>";*/

        $print .=   "</div>"; // image

        //$print .=    "<div class='product-info'>";
        $print .=    "<div class='product-info col-12 col-sm-12 col-md-12 col-lg-12 col-xs-12'>";
        $print .=     "<a class='nav-link' href='#' onclick='generalFunctionToRequest($paramsModalSend)'><label class='margin-top-3 lead' id='titulo$id'>".$titulo."</label>";
        $print .=     "<label class='margin-top-3 color lead' id ='clave$id'>Clave: ".$clave."</label>";
        $print .=     "<label class='margin-top-3 color lead'>$ ".$pf." MXN</label></a>";
        $print .=    "</div>"; // product-info
        $print .=  "</div>"; // flex-item
        echo $print;
      }
      echo    "<div id='resultModalProduct'></div>";
      //require_once('../pagination/structure.php');
      echo  "</div>";
      echo "</div>"; // flex-container
      // include pagination
      require_once('../pagination/structure.php');
    } else {
      echo  "<div class='row'>
            <div class='alert alert-warning' role='alert' style='margin-bottom:0 !important; height: 100vh; width: 100%; text-align:center; display: flex; justify-content: center; align-items: center; flex-direction: column;'>
              <h4 class='alert-heading' style='font-size: 3em;'>En mantenimiento.</h4>
              <p style='font-size: 1.5em;'>En estos momentos nuestro servidor está en mantenimiento y no está disponible los productos en nuestra tienda. Agradecemos su paciencia y su comprensión. Estará disponible lo más pronto posible.</p>
              <p class='mb-0' style='font-size: 1.5em;'>Si necesita un producto en especifico le solicitamos que envíe un correo a <strong>contacto@ferremayoristas.com.mx</strong>, con los datos del/los producto(s).</p>
            </div>
          </div>";
    }// end validation num row > 0, do something if doesn't exist product
    $getConnection->close();
  } // end function

  private function getProductsCrud() {
    $params = array("location"=>"newProduct-crud",
                    "url"=>"../php/product/product.php",
                    "booleanResponse"=>false,
                    "divResultID"=>"result-find-productBy-crud",
                    "msgSuccess"=>"Ok!",
                    "msgError"=>"Error al intentar editar producto");
    $paramsSend = json_encode($params);

    $print = "<div class='row' style='margin: 40px 15px 10px 250px;'>";
    $print .=  "<div class='form-group col-md-12'>";
    $print .=     "<div class='form-group col-md-4'>";
    $print .=       "<label>(Código, clave, descripción) del producto</label>";
    $print .=       "<div class='input-group'>";
    $print .=         "<input type='text' name='findCrudProductBy' id='findCrudProductBy'
                      class='form-control' placeholder='Buscar'/>";

    $print .=         "<span class='input-group-btn'>";
    $print .=           "<button class='btn btn-default' onclick='generalFunctionToRequest($paramsSend)'>";
    $print .=             "<i class='glyphicon glyphicon-plus'></i>";
    $print .=            "</button>";
    $print .=          "</span>";
    $print .=        "</div>"; // input-group
    $print .=     "</div>"; // col-md-4
    $print .=   "</div>";
    $print .=  "</div>";

    // results query to find product
    $print .=  "<div class='row' id='result-find-productBy-crud'>";
    $print .= "</div>";
    echo $print;
  }

  private function getProductToUpdateCrud($params) {
    // TODO display images to update. depend which section want to update.
    $paramDb = new Database();
    $paramDml = new Dml();
    $getConnection = $paramDb->GetLink();

    $productoID = $params["productoID"];
    $productoID = $paramDb->SecureInput($productoID);

    $getProduct = "SELECT pro.id as productoID, pro.*, pre.*
                    FROM productos pro
                      JOIN precios pre ON pre.id = pro.id
                      WHERE pro.id = $productoID";

    $executeQuery = $paramDb->Query($getProduct);

    if($executeQuery === false) {
      echo "error-query";
      return false;
    }
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    $getConnection->close();

    $parameters = array("rows"=>$rows);
    // draw all inputs by function, and send parameters to be will be process it on js
    $print = $paramDml->updateProduct($parameters);
    echo $print;
  }

  private function newProductCrud() {
    $paramDml = new Dml();
    // draw all inputs by function, and send parameters to be will be process it on js
    $print = $paramDml->addProduct();
    echo $print;

  }

  private function saveNewProductCrud($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    foreach ($params["registrosFormulario"] as $key => $value) {
      $params["registrosFormulario"][$key] = $paramFunctions->sanitize($params["registrosFormulario"][$key]);
      if($key == "titulo") {
        $params["registrosFormulario"][$key] = $paramFunctions->specialChars($params["registrosFormulario"][$key]);
      }
      $params["registrosFormulario"][$key] = $paramDb->SecureInput($params["registrosFormulario"][$key]);
    }

    $clave = $params["registrosFormulario"]["clave"];
    $codigo = $params["registrosFormulario"]["codigo"];
    $titulo = $params["registrosFormulario"]["titulo"];
    $mayorista = $params["registrosFormulario"]["mayorista"];
    $distribuidor = $params["registrosFormulario"]["distribuidor"];
    $subdistribuidor = $params["registrosFormulario"]["subdistribuidor"];

    $insertProduct = "INSERT INTO productos (clave, codigo, titulo) VALUES ('$clave', $codigo, '$titulo')";
    $exeQuInsertProduct = $paramDb->UpdateDb($insertProduct);
    if($exeQuInsertProduct === false) {
      echo "error-query";
      return false;
    }

    $getMaxID = "SELECT LAST_INSERT_ID() as maxProductID FROM productos";
    $exeQuGetLastID = $paramDb->Query($getMaxID);
    if($exeQuGetLastID === false) {
      echo "error-query";
      return false;
    }

    $rows = $paramDb->Rows();
    $productID = $rows[0]["maxProductID"];

    // TODO Checar el flujo de actualizacion, ya que se elimino el campo precioID de la tabla
    $updateProduct = "UPDATE productos SET precioID = $productID WHERE id = $productID";
    $exeQuUpdateProduct = $paramDb->Query($updateProduct);
    if($exeQuUpdateProduct === false) {
      echo "error-query";
      return false;
    }

    $insertPrice = "INSERT INTO precio (id, mayorista, subdistribuidor, distribuidor) VALUES ($productID, $mayorista, $constructor, $socio)";
    $exeQuInsertPrice = $paramDb->Query($insertPrice);
    if($exeQuInsertPrice === false) {
      echo "error-query";
      return false;
    }

    echo "success-query";
    $getConnection->close();
  }

  private function setProductToupdateCrud($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $productoID = $params["productoID"];
    $dataForm = $params["registrosFormulario"]; // data that user set
    $dataDB = $params["registrosDB"]; // data from database
    $updateQuery = "UPDATE ";
    $fieldsToupdate = "";
    $boolean = ["productos"=> false, "precios" => false, "shouldUpdate" => false];
    $counter = 0;
    //  check if data if diferent from data from database, you should be update on database
    // value key, is the same name of field on table.
    foreach ($dataForm as $keyForm => $valueForm) {
      if($dataDB[$keyForm] != $valueForm) {
        $boolean["shouldUpdate"] = true;
        $elementDB = $dataDB[$keyForm];
        // echo "key $keyForm with value: $elementDB is not equal to $valueForm </br>";
        $valueForm = $paramDb->SecureInput($valueForm);
        switch($keyForm) {
          case "clave":
            $valueForm = $paramFunctions->sanitize($valueForm);
            $fieldsToupdate .= ($counter == 0) ? "pro.$keyForm = '$valueForm'" : ", pro.$keyForm = '$valueForm'";
            $boolean["productos"] = true;
            break;
          case "codigo":
            $valueForm = $paramFunctions->sanitize($valueForm);
            $fieldsToupdate .= ($counter == 0) ? "pro.$keyForm = '$valueForm'" : ", pro.$keyForm = '$valueForm'";
            $boolean["productos"] = true;
            break;
          case "titulo":
            $valueForm = $paramFunctions->sanitize($valueForm);
            $valueForm = $paramFunctions->specialChars($valueForm);
            $fieldsToupdate .= ($counter == 0) ? "pro.$keyForm = '$valueForm'" : ", pro.$keyForm = '$valueForm'";
            $boolean["productos"] = true;
            break;
          default:
            $valueForm = $paramFunctions->sanitize($valueForm);
            $fieldsToupdate .= ($counter == 0) ? "pre.$keyForm = '$valueForm'" : ", pre.$keyForm = '$valueForm'";
            $boolean["precios"] = true;
        }
        $counter++;
      }
      // else nothing to update, to test print something
    }

    if($boolean["productos"]) {
      if($boolean["productos"] && $boolean["precios"]) {
        $updateQuery .= "productos pro, precios pre SET " . $fieldsToupdate . " WHERE pro.id = pre.id AND pro.id = $productoID";
      } else {
        $updateQuery .= "productos pro SET " . $fieldsToupdate . " WHERE pro.id = $productoID";
      }
    } else if($boolean["precios"]) {
      $updateQuery .= "precios pre SET " . $fieldsToupdate . " WHERE pre.id = $productoID";
    }

    // validate if i should be update row
    if($boolean["shouldUpdate"]) {
      // NOTE depend which table the user modify, the complete sentence look like:
      // UPDATE productos pro, precios pre SET pro.clave = 'M-20', pro.codigo = '1001', pro.titulo = 'ABRAZADERA DE 19-44MM INOXIDABL',
      // pre.publico = '15.9', pre.socio = '11.22' WHERE pro.precioID = pre.id AND pro.id = 1
      $executeQuery = $paramDb->UpdateDb($updateQuery);

      if($executeQuery === false) {
        echo 'error-query'; // there is an error or something like that
      } else {
        $getConnection->close();
        echo 'success-query';
      }
    } else {
      // send message because anything value update
      echo "nothing-to-update";
    }
  }


}

?>
