<?php
class Util {
  public function constructSql($params, $elementsID) {
    $lengthParams = count($params);
    $values = "";
    for($i = 0; $i < $lengthParams; $i++) {
      // first, i have array with elements depends how many fields in db
      // then i check length for loop it, inside loop; i get the name of index.
      // it's the same as name field on db and construct the sql query
      $param = $params[$i];
      $lengthParam = count($param);
      $value = "(";
      for($j = 0; $j < $lengthParam; $j++) {
        $paramElement = $param[$j];
        $element = $elementsID[$j]; // contais names of fields in db
        // check if variable("boolean") if it's string, enclosed in single quotes
        $fieldDatabase = $paramElement[$element];
        $typeOfVariable = $paramElement["string"];
        if($typeOfVariable == "true") {
          $fieldDatabase = "'$fieldDatabase'";
        }
        // if it's the first element, don't add colon
        if($j == 0) {
          $value .= $fieldDatabase;
        } else {
          $value .= ", " . $fieldDatabase;
        }
      }
      $value .= "),"; // the next value, separate by colon
      $values .= $value;
    }
    $values = substr(trim($values), 0, -1); // remove the last colon
    return $values;
  }

  public function comparepasswordCrypt($params) {
    $passDb = $params["passDb"];
    $passForm = $params["passForm"];
    $response = "";
    if (hash_equals($passDb, crypt($passForm, $passDb))) {
      $response = "success";
    } else {
      $response = "fail";
    }
    return $response;
  }

  public function deleteElementArray($array, $element) {
    $length = count($array);
    $element = $element + "";
    for ($i=0; $i < $length; $i++) {
      if($array[$i]["productID"] == $element) {
        unset($array[$i]);
      }
    }
    return $array;
  }

  // print nav, depend what kind of user has been logged show menu them
  public function drawNavMenu($rol, $disponible, $saldo, $diasRestantesNewMes, $vendedor, $ignorasuspender, $activo, $user) {

    $mysqliCon = new mysqli("67.227.237.109", "zizaram1_datosaF", "dwzyGskl@@.W", "zizaram1_datosa", 3306);
    $getDashboard = "SELECT * FROM dashboard WHERE rol ='$rol'";
    $buscarRolNav = mysqli_query($mysqliCon,$getDashboard);

    $nav = "";
    $fechaActual = date('Y-m-d');
    // Mes
    $mesNum = date('m');
    switch ($mesNum) {
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

    while ($row = mysqli_fetch_array($buscarRolNav)) {
      $seccion = $row["seccion"];
      $funcion = $row["funcion"];
      $parametro = $row["parametro"];
      $clase = $row["clase"];
      $linkFunction = $funcion . "('$parametro')";

      if($user == 'admin' || $user == 'supervisor1' || $user == 'supervisor2' || $user == 'pedidos' || $user == 'direccion' || $user == 'cartera'){
          $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
          $nav .=    '<i class="'.$clase.'"></i>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
      } elseif($user == '00001' || $user == '00080' || $user == '00051' || $user == '00491' || $user == '02980' || $user == '01739' || $user == '01789'){
        if($seccion == "PROMOTRUPER"){
          $nav .='<li class="nav-item" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="../../assets/pdf/promotruperOficial.pdf" target="_blank">';
          $nav .=    '<i class="'.$clase.'"></i>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
        } else {
          $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
          $nav .=    '<i class="'.$clase.'"></i>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
        }
      } elseif($disponible > 0) {
        var_dump($disponible);
        if($activo == "S" || $ignorasuspender >= $fechaActual){
          if($seccion == "PROMOTRUPER"){
            $nav .='<li class="nav-item" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
            $nav .=  '<a class="nav-link navMenu" href="../../assets/pdf/promotruperOficial.pdf" target="_blank">';
            $nav .=    '<i class="'.$clase.'"></i>';
            $nav .=    '<span class="pull-right-container" style="display:none;">
                          <i class="'.$clase.' pull-right"></i>
                        </span>
                      </a>
                    </li>';
          } elseif($parametro == "edocta" || $parametro == "facturas" || $parametro == "notcred" || $parametro == "order" || $parametro == "dashboard"){
            $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
            $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
            $nav .=    '<i class="'.$clase.'"></i>';
            $nav .=    '<span class="pull-right-container" style="display:none;">
                          <i class="'.$clase.' pull-right"></i>
                        </span>
                      </a>
                    </li>';
          } elseif($saldo > 0){
            if($diasRestantesNewMes < 0){
              $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
              $nav .=  '<a class="nav-link navMenu" href="#">';
              $nav .=    '<i class="'.$clase.'"></i>';
              $nav .=    '<span class="pull-right-container" style="display:none;">
                            <i class="'.$clase.' pull-right"></i>
                          </span>
                        </a>
                      </li>';
            } else {
              $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
              $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
              $nav .=    '<i class="'.$clase.'"></i>';
              $nav .=    '<span class="pull-right-container" style="display:none;">
                            <i class="'.$clase.' pull-right"></i>
                          </span>
                        </a>
                      </li>';
            }
          } else {
            $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
            $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
            $nav .=    '<i class="'.$clase.'"></i>';
            $nav .=    '<span class="pull-right-container" style="display:none;">
                          <i class="'.$clase.' pull-right"></i>
                        </span>
                      </a>
                    </li>';
          }
        } else {
          if($seccion == "PROMOTRUPER"){
              $nav .='<li class="nav-item" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
              $nav .=  '<a class="nav-link navMenu" href="../../assets/pdf/promotruperOficial.pdf" target="_blank">';
              $nav .=    '<i class="'.$clase.'"></i>';
              $nav .=    '<span class="pull-right-container" style="display:none;">
                            <i class="'.$clase.' pull-right"></i>
                          </span>
                        </a>
                      </li>';
          } elseif($parametro == "edocta" || $parametro == "facturas" || $parametro == "notcred" || $parametro == "order" || $parametro == "dashboard"){
            $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
            $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
            $nav .=    '<i class="'.$clase.'"></i>';
            $nav .=    '<span class="pull-right-container" style="display:none;">
                          <i class="'.$clase.' pull-right"></i>
                        </span>
                      </a>
                    </li>';
          } else {
            $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
            $nav .=  '<a class="nav-link navMenu" href="#">';
            $nav .=    '<i class="'.$clase.'"></i>';
            $nav .=    '<span class="pull-right-container" style="display:none;">
                          <i class="'.$clase.' pull-right"></i>
                        </span>
                      </a>
                    </li>';
          }
        }
      } else {
        if($parametro == "promotruper"){
          $nav .='<li class="nav-item" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="../../assets/pdf/promotruperOficial.pdf" target="_blank">';
          $nav .=    '<i class="'.$clase.'"></i>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
        } elseif($parametro == "edocta" || $parametro == "facturas" || $parametro == "notcred" || $parametro == "order" || $parametro == "dashboard"){
          $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
          $nav .=    '<i class="'.$clase.'"></i>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
        } elseif($diasRestantesNewMes < 0) {
          $nav .='<li class="nav-item" role="'.$seccion.'" data-toggle="tooltip" data-placement="top" title="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="#">';
          $nav .=    '<i class="'.$clase.'"></i>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
        } /*else {
          $nav .='<li class="nav-item" role="'.$seccion.'">';
          $nav .=  '<a class="nav-link navMenu" href="#" onclick="'.$linkFunction.'">';
          $nav .=    '<i class="'.$clase.'"></i> <span>'.$seccion.'</span>';
          $nav .=    '<span class="pull-right-container" style="display:none;">
                        <i class="'.$clase.' pull-right"></i>
                      </span>
                    </a>
                  </li>';
        }*/
      }
    }
    return $nav;
  }

  public function drawRowProduct($descriptionRow, $row) {
    $result = false;
    if($row != "null") {
      $result =
              "<p class='text-justify margin-top-3'>
                <label>$descriptionRow: </label>
                $row
              </p>";
    }
    return $result;
  }

  public function drawTableHeader($headers, $classPerColumn) {
    // NOTE only print header, the body it's on loop
    $table =    "<table class='table table-striped table-dark'>";
    $table .=     '<tr>';
    $length = count($headers);

    for($i= 0; $i < $length; $i++) {
      $table .=     "<th class='$classPerColumn[$i]'>$headers[$i]</th>";
    }
    $table .=     '</tr>';
    return $table;
  }

  public function encryptPassword($string) {
    $salt = '$2a$07$usesomadasdsadsadsadasdasdasdsadesillystringfors';
    $hashed_password = crypt($string, $salt); // set hash
    return $hashed_password;
  }

  public function findElementOnArray($array, $productCode) {
    $length = count($array);
    $exist = false;
     for ($i=0; $i < $length; $i++) {
       if(in_array($productCode, $array[$i])) {
         $exist = true;
       }
     }

     return $exist;
  }

  public function generateRandomString($length, $ID) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $ID . $randomString;
  }

  public function identifyEmailOrder($row) {
    $emailOrder = $row["correo"];
    $emailClient = $row["correo"];

    if(strlen($emailOrder) > 0) {
      $email = $emailOrder;
    } else {
      $email = $emailClient;
    }
    return $email;
  }

  public function loadScript($url) {
    echo"<script src=".$url."> </script>";
  }

  public function loadCss($url) {
    echo"<link rel ='stylesheet' href=".$url.">";
  }

  public function redirect($url) {
    header('Location: ' .  $url);
  }

  public function sanitize($string) {
    $string = strip_tags(trim($string));
    $string = htmlentities($string, ENT_QUOTES, "UTF-8");
    return stripslashes($string);
  }

  public function specialChars($string) {
    $arrayHtmlCode = array("&aacute;","&eacute;","&iacute;",
                            "&oacute;","&uacute;","&ntilde;",
                            "&Aacute;","&Eacute;","&Iacute;",
                            "&Oacute;","&Uacute;","&Ntilde;",
                            "&#039;");
    $arrayUTF8 = array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ", "'");
    return str_replace($arrayHtmlCode, $arrayUTF8 ,$string);
  }

  public function selectDefaultOption($optionDefault, $contentValues) {
    $print = "";
    foreach($contentValues as $valueOption) {
      if($optionDefault == $valueOption) {
        $print .= "<option value='$valueOption' selected>$valueOption</option>";
      } else {
        $print .= "<option value='$valueOption'>$valueOption</option>";
      }
    }
    return $print;
  }

  public function showDivMessage($message) {
    $resultMessage = "<div class='row'>";
    $resultMessage .=   "<div class='col-md-12'>";
    $resultMessage .=     "<h4>$mensaje</h4>";
    $resultMessage .=   "</div>";
    $resultMessage .= "</div>";

    echo $resultMessage;
  }

  public function updateElementArray($array, $element, $quantity) {
    $length = count($array);
    for ($i=0; $i < $length; $i++) {
      if($array[$i]["productID"] == $element) {
        $_SESSION["shoppingCarPartner"][$i]["quantity"] = $quantity;
      }
    }
  }
} // End class

?>
