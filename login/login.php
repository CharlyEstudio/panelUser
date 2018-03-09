<div class="modal fade" id="login-modal" tabindex="-1" role="dialog"
 aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header cabecera-modal-login">
        <h3>Iniciar Sesión</h3><br>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-6">
            <div class="well">
              <form name="frmLogin" action="login/process.php" method="POST"
         enctype="multipart/form-data">
                <div class="form-group">
                  <label for="username" class="control-label">Usuario</label>
                  <input class="form-control" type="text" name="user" id="user" placeholder="Nombre de usuario">
                  <span class="help-block"></span>
                </div>
                <div class="form-group">
                  <label for="password" class="control-label">Password</label>
                  <input type="password" class="form-control" name="pass" id="pass" placeholder="Contraseña">
                  <span class="help-block"></span>
                </div>
                <div id="loginErrorMsg" class="alert alert-error hide">Contraseña incorrecta!</div>
                <button type="submit" class="btn btn-success btn-block" name="login" value="Ingresar">Ingresar</button>
              </form>
            </div>
          </div>
          <div class="col-xs-6">
            <p class="lead">Beneficios de ser <span class="text-success">Socio/Distribuidor</span></p>
            <ul class="list-unstyled" style="line-height: 2">
              <li><span class="fa fa-check text-success"></span> Precios Espciales</li>
              <li><span class="fa fa-check text-success"></span> Boletín Mensual de Descuentos</li>
              <li><span class="fa fa-check text-success"></span> Asesoría para tu ferretería</li>
              <!-- <li><span class="fa fa-check text-success"></span> Get a gift <small>(only new customers)</small></li> -->
            </ul>
            <p><a href="../index.php#contacto" class="btn btn-info btn-block">Regístrate ahora!</a></p>
          </div>
        </div>
        <!-- <div class="login-help">
          <a href="#">Registrar</a> - <a href="#">Reestablecer contraseña</a>
        </div> -->
      </div>
    </div>
  </div>
</div>
