<?php
  if(!isset($_SESSION["data"])) {
    header("Location: ../../index.php");
  } else {
?>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Sistema de Administración</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- <a class="navbar-brand" href="index.html"><img src="../img/logo.png" width="80" height="30"></a> -->
            <h4>Ferremayoristas</h4>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                <ul class="dropdown-menu message-dropdown">
                    <li class="message-preview">
                        <a href="#">
                            <div class="media">
                                <span class="pull-left">
                                    <img class="media-object" src="http://placehold.it/50x50" alt="">
                                </span>
                                <div class="media-body">
                                    <h5 class="media-heading"><strong>Informe de Usuario</strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i> Hora de Informe</p>
                                    <p>Aqui va la información.</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="message-preview">
                        <a href="#">
                            <div class="media">
                                <span class="pull-left">
                                    <img class="media-object" src="http://placehold.it/50x50" alt="">
                                </span>
                                <div class="media-body">
                                    <h5 class="media-heading"><strong>Informe de Usuario</strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i> Hora de Informe</p>
                                    <p>Aqui va la información.</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="message-preview">
                        <a href="#">
                            <div class="media">
                                <span class="pull-left">
                                    <img class="media-object" src="http://placehold.it/50x50" alt="">
                                </span>
                                <div class="media-body">
                                    <h5 class="media-heading"><strong>Informe de Usuario</strong>
                                    </h5>
                                    <p class="small text-muted"><i class="fa fa-clock-o"></i> Hora de Informe</p>
                                    <p>Aqui va la información.</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="message-footer">
                        <a href="#">Leer todos los Mensajes</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                <ul class="dropdown-menu alert-dropdown">
                    <li>
                        <a href="#">Tipor de Alerta <span class="label label-default">Tipor de Alerta</span></a>
                    </li>
                    <li>
                        <a href="#">Tipor de Alerta <span class="label label-primary">Tipor de Alerta</span></a>
                    </li>
                    <li>
                        <a href="#">Tipor de Alerta <span class="label label-success">Tipor de Alerta</span></a>
                    </li>
                    <li>
                        <a href="#">Tipor de Alerta <span class="label label-info">Tipor de Alerta</span></a>
                    </li>
                    <li>
                        <a href="#">Tipor de Alerta <span class="label label-warning">Tipor de Alerta</span></a>
                    </li>
                    <li>
                        <a href="#">Tipor de Alerta <span class="label label-danger">Tipor de Alerta</span></a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#">Ver todos</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Usuario <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#"><i class="fa fa-fw fa-user"></i> Información</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-envelope"></i> Mensajería</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-fw fa-gear"></i> Opciones</a>
                    </li>
                    <li class="divider"></li>
                    <li role="CerrarSesion">
                      <a href="../login/logout.php" data-toggle='tooltip' title='Cerrar sesión!'>
                        Cerrar sesión <i class="fa fa-power-off"></i>
                      </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li role="Usuarios">
                  <a href="#">
                    <i class="fa fa-user"></i> Usuarios
                  </a>
                </li>
                <li role="Pedidos">
                  <a href="#" onclick="showInformation('order');">
                    <i class="fa fa-bar-chart-o"></i> Pedidos
                  </a>
                </li>
                <li role="Ventas">
                  <a href="#">
                    <i class="fa fa-fw fa-table"></i> Ventas
                  </a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-edit"></i> Facturas</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-credit-card"></i> Pagos</a>
                </li>
                <li role="Reportes">
                  <a href="#" onclick="showInformation('report');">
                    <i class="fa fa-pie-chart"></i> Reportes
                  </a>
                </li>
                <li>
                    <p>Sistema de Administración | By KODE-B &copy</p>
                </li>
                <!-- <li>
                    <a href="#" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Dropdown <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="demo" class="collapse">
                        <li>
                            <a href="#">Dropdown Item</a>
                        </li>
                        <li>
                            <a href="#">Dropdown Item</a>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </div>
    </nav>
    <div id="page-wrapper">
    </div>
</div>

<?php
  }
?>
