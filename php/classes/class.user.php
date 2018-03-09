<?php
require_once("../class.database.php");
require_once("../classes/class.queries.php");
require_once("../functions/util.php");
require_once("../functions/dml.php");

/* NOTE
  1.- If you want to update or save user, you should know that i get all values
    by array with names on class.dml.php then i process it on js and get value by arrayname.
    then it's very important that names on array iquals on form.
  2.-
*/

Class User {
  public function getterFindCrudUserBy($params) {
    $this->findCrudUserBy($params);
  }

  public function getterGetUsersCrud() {
    $this->getUsersCrud();
  }

  public function getterGetUserToUpdateCrud($params) {
    $this->getUserToUpdateCrud($params);
  }

  public function getterNewUserCrud() {
    $this->newUserCrud();
  }

  public function getterSaveNewUserCrud($params) {
    $this->saveNewUserCrud($params);
  }

  public function getterSetUserToupdateCrud($params) {
    $this->setUserToupdateCrud($params);
  }


  private function findCrudUserBy($params) {
    $paramDb = new Database();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $findUserBy = $params["findCrudUserBy"];
    $findUserBy = $paramDb->SecureInput($findUserBy);

    $getusers = "SELECT u.id as usuarioID, u.*, c.*, r.rol
                  FROM usuarios u
                    JOIN clientes c ON c.id = u.clienteID
                    JOIN roles r ON r.id = c.roleID
                      WHERE
                      (
                        u.username LIKE '$findUserBy%'
                        OR c.clave LIKE '$findUserBy%'
                        OR c.nombreCompleto LIKE '%$findUserBy%'
                      ) LIMIT 0,10";

    $executeQuery = $paramDb->Query($getusers);
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    $getConnection->close();

    if($numRow > 0) {
      $headers = ["Username", "Rol", "Nombre", "Clave", "Dirección", "RFC", "Teléfono", "Correo", "Actualizar"];
      $classPerColumn = ["text-left", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center", "text-center"];

      $print = "<div style='margin: 40px 15px 10px 250px;'>";
      $print .=   "<div class='form-group col-md-12'>";
      // NOTE draw table header, only print header, the body it's on loop
      $print .= $paramFunctions->drawTableHeader($headers, $classPerColumn);
      foreach($rows as $row) {
        $usuarioID = $row["usuarioID"];
        $username = $row["username"];
        $rol = $row["rol"];
        $nombreCompleto = $row["nombreCompleto"];
        $clave = $row["clave"];
        $direccion = $row["direccion"];
        $rfc = $row["rfc"];
        $telefono = $row["telefono"];
        $correo = $row["correo"];

        $paramsUser = array("userID"=>$usuarioID,
                        "location"=>"getUser-to-update-crud",
                        "url"=>"../php/user/user.php",
                        "booleanResponse"=>false,
                        "divResultID"=>"result-find-userBy-crud",
                        "msgSuccess"=>"Ok!",
                        "msgError"=>"Error al intentar editar usuario");
        $paramsSend = json_encode($paramsUser);


        $print .= "<tr>";
        $print .= "<td class='text-left'>$username</td>";
        $print .= "<td class='text-center'>$rol</td>";
        $print .= "<td class='text-center'>$nombreCompleto</td>";
        $print .= "<td class='text-center'>$clave</td>";
        $print .= "<td class='text-center'>$direccion</td>";
        $print .= "<td class='text-center'>$rfc</td>";
        $print .= "<td class='text-center'>$telefono</td>";
        $print .= "<td class='text-center'>$correo</td>";
        $print .= "<td class='text-center'>";
        $print .= "<button type='button' class='btn btn-primary' onclick='generalFunctionToRequest($paramsSend)'>";
        $print .=   "Actualizar";
        $print .= "</button>";
        $print .= "</td>";
        $print .= "</tr>";
      }
      $print .= "</table>";
      $print .= "</div>";
      $print .= "</div>"; // overflow-x:auto

      echo $print;

    } else {
      $message = "No se encontró usuario: " . $findUserBy;
      echo "<div class='col-md-12'><h4>$message</h4></div>";
    }


  }

  private function getUsersCrud() {
    $params = array("location"=>"newUser-crud",
                    "url"=>"../php/user/user.php",
                    "booleanResponse"=>false,
                    "divResultID"=>"result-find-userBy-crud",
                    "msgSuccess"=>"Ok!",
                    "msgError"=>"Error al intentar editar usuario");
    $paramsSend = json_encode($params);

    $print = "<div class='row' style='margin: 40px 15px 10px 250px;'>";
    $print .=  "<div class='form-group col-md-12'>";
    $print .=     "<div class='form-group col-md-4'>";
    $print .=       "<label>(Nombre, clave) del usuario</label>";
    $print .=       "<div class='input-group'>";
    $print .=         "<input type='text' name='findCrudUserBy' id='findCrudUserBy'
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

    // results query to find user
    $print .=  "<div class='row' id='result-find-userBy-crud'>";
    $print .= "</div>";
    echo $print;
  }

  private function getUserToUpdateCrud($params) {
    $paramDb = new Database();
    $paramDml = new Dml();
    $getConnection = $paramDb->GetLink();

    $usuarioID = $params["usuarioID"];
    $usuarioID = $paramDb->SecureInput($usuarioID);

    $getusers = "SELECT u.id as usuarioID, u.*, c.*, r.rol
                  FROM usuarios u
                    JOIN clientes c ON c.id = u.clienteID
                    JOIN roles r ON r.id = c.roleID
                    WHERE u.id = $usuarioID";

    $executeQuery = $paramDb->Query($getusers);
    $numRow = $paramDb->NumRows();
    $rows = $paramDb->Rows();
    $getConnection->close();

    $parameters = array("rows"=>$rows);
    // draw all inputs by function, and send parameters to be will be process it on js
    $print = $paramDml->updateUser($parameters);
    echo $print;
  }

  private function newUserCrud() {
    $paramDml = new Dml();
    // draw all inputs by function, and send parameters to be will be process it on js
    $print = $paramDml->addUser();
    echo $print;
  }

  // TODO validation for queries, testing
  private function saveNewUserCrud($params) {
    $paramDb = new Database();
    $queries = new Queries();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();
    $response = "";

    $username = $params["registrosFormulario"]["username"];
    $username = $paramFunctions->sanitize($username);
    $username = $paramFunctions->specialChars($username);

    $getuser = "SELECT username FROM usuarios WHERE username = '$username'";
    $executeQuery = $paramDb->Query($getuser);
    $numRow = $paramDb->NumRows();

    if($numRow == 0) {
      $rol = $params["registrosFormulario"]["rol"];
      // get id from table role where rol = ["mayorista", "distribuidor", "subdistribuidor", etc]
      $paramsSpecificValue = array("query" => "SELECT id FROM roles WHERE rol = '$rol'",
                                    "column" => "id");
      $roleID = $queries->getterGetSpecificValue($paramsSpecificValue);

      foreach ($params["registrosFormulario"] as $key => $value) {
        $params["registrosFormulario"][$key] = $paramFunctions->sanitize($params["registrosFormulario"][$key]);
        switch($key) {
          case "password":
            $params["registrosFormulario"][$key] = $paramFunctions->specialChars($params["registrosFormulario"][$key]);
            $params["registrosFormulario"][$key] = $paramFunctions->encryptPassword($params["registrosFormulario"][$key]);
            break;
          case "nombreCompleto":
          case "direccion":
          case "correo":
            $params["registrosFormulario"][$key] = $paramFunctions->specialChars($params["registrosFormulario"][$key]);
            break;
        }
        $params["registrosFormulario"][$key] = $paramDb->SecureInput($params["registrosFormulario"][$key]);
      }

      $password = $params["registrosFormulario"]["password"];
      $nombreCompleto = $params["registrosFormulario"]["nombreCompleto"];
      $clave = $params["registrosFormulario"]["clave"];
      $direccion = $params["registrosFormulario"]["direccion"];
      $rfc = $params["registrosFormulario"]["rfc"];
      $telefono = $params["registrosFormulario"]["telefono"];
      $correo = $params["registrosFormulario"]["correo"];

      $insertUser = "INSERT INTO usuarios (username, password) VALUES('$username', '$password')";

      $insertClient = "INSERT INTO clientes (nombreCompleto, clave, direccion, rfc, telefono, correo, roleID) ";
      $insertClient .= "VALUES('$nombreCompleto', '$clave', '$direccion', '$rfc', '$telefono', '$correo', '$roleID')";

      $getLastUserID = "SELECT LAST_INSERT_ID() as lastInsertID FROM usuarios";
      $getLastClientID = "SELECT LAST_INSERT_ID() as lastInsertID FROM clientes";

      $statements = array($insertUser, $getLastUserID, $insertClient, $getLastClientID);

      if ($getConnection->multi_query(implode(';', $statements))) {
        $i = 0;
        do {
            if ($result = $getConnection->store_result()) {
              while ($row = $result->fetch_assoc()) {
                switch($i) {
                  case 1:
                    $lastUserID = $row["lastInsertID"];
                    break;
                  case 3:
                    $lastClientID = $row["lastInsertID"];
                    break;
                }
              }
            }
            $i++;
        } while ($getConnection->next_result());
      }

      $updateUser = "UPDATE usuarios SET clienteID = $lastClientID WHERE id = $lastUserID";
      $executeQueryUC = $paramDb->UpdateDb($updateUser);
      $getConnection->close();
      $response =  "success";
    } else {
      $response =  "already exist";
    }

    echo $response;
  }

  private function setUserToupdateCrud($params) {
    $paramDb = new Database();
    $queries = new Queries();
    $paramDml = new Dml();
    $paramFunctions = new Util();
    $getConnection = $paramDb->GetLink();

    $usuarioID = $params["usuarioID"];
    $usuarioID = $paramDb->SecureInput($usuarioID);

    $dataDB = $params["registrosDB"]; // data from database
    $updateQuery = "UPDATE ";
    $fieldsToupdate = "";
    $boolean = ["usuarios"=> false, "clientes" => false, "shouldUpdate" => false];
    $counter = 0;

    foreach ($params["registrosFormulario"] as $key => $value) {
      $params["registrosFormulario"][$key] = $paramFunctions->sanitize($params["registrosFormulario"][$key]);
      switch($key) {
        case "nombreCompleto":
        case "direccion":
        case "correo":
          $params["registrosFormulario"][$key] = $paramFunctions->specialChars($params["registrosFormulario"][$key]);
          break;
      }
      $params["registrosFormulario"][$key] = $paramDb->SecureInput($params["registrosFormulario"][$key]);
    }
    $dataForm = $params["registrosFormulario"]; // data that user set

    foreach ($dataForm as $keyForm => $valueForm) {
      if($dataDB[$keyForm] != $valueForm) {
        $boolean["shouldUpdate"] = true;
        $elementDB = $dataDB[$keyForm];
        // echo "key $keyForm with value: $elementDB is not equal to $valueForm </br>";
        switch($keyForm) {
          case "username":
            $fieldsToupdate .= ($counter == 0) ? "usr.$keyForm = '$valueForm'" : ", usr.$keyForm = '$valueForm'";
            $boolean["usuarios"] = true;
            break;
          case "password":
            $paramsCompare = array("passDb"=>$dataDB[$keyForm],
                                    "passForm"=>$valueForm);
            $validatePassword = $paramFunctions->comparepasswordCrypt($paramsCompare);
            // validate password, i compare password from form and database
            // first i encript the password from form to will be compare, because is save encript it.
            if($validatePassword === "fail") {
              $valueForm = $paramFunctions->encryptPassword($valueForm);
              $fieldsToupdate .= ($counter == 0) ? "usr.$keyForm = '$valueForm'" : ", usr.$keyForm = '$valueForm'";
              $boolean["usuarios"] = true;
            } else {
              $boolean["shouldUpdate"] = false; // setter to false, because the passwords are equals
            }
            break;
          // NOTE it's the only value that change to save on database
          case "rol":
            // get id from role, and update table clientes set roleID = $roleID (result from database)
            $paramsSpecificValue = array("query" => "SELECT id FROM roles WHERE rol = '$valueForm'",
                                          "column" => "id");
            $roleID = $queries->getterGetSpecificValue($paramsSpecificValue);
            $fieldsToupdate .= ($counter == 0) ? "cli.roleID = '$roleID'" : ", cli.roleID = '$roleID'";
            $boolean["clientes"] = true;
            break;
          default:
            $fieldsToupdate .= ($counter == 0) ? "cli.$keyForm = '$valueForm'" : ", cli.$keyForm = '$valueForm'";
            $boolean["clientes"] = true;
        }
        $counter++;
      }
      // else nothing to update, to test print something
    }


    if($boolean["usuarios"]) {
      if($boolean["usuarios"] && $boolean["clientes"]) {
        $updateQuery .= "usuarios usr, clientes cli SET " . $fieldsToupdate . " WHERE usr.clienteID = cli.id AND usr.id = $usuarioID";
      } else {
        $updateQuery .= "usuarios usr SET " . $fieldsToupdate . " WHERE usr.id = $usuarioID";
      }
    } else if($boolean["clientes"]) {
      $updateQuery .= "clientes cli SET " . $fieldsToupdate . " WHERE cli.id = $usuarioID";
    }

    // validate if i should be update row
    if($boolean["shouldUpdate"]) {
      $executeQuery = $paramDb->UpdateDb($updateQuery);
      $numRow = $paramDb->NumRows();
      $getConnection->close();
      if($executeQuery) {
        echo 'true';
      } else {
        echo 'false'; // there is an error or something like that
      }
    } else {
      // send message because anything value update
    }

  } // end function setUserToupdateCrud


}
?>
