<header class="main-header">
    <!-- Logo -->
    <a href="<?= base_url() ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini img-circle"><img src="<?= base_url() ?>assets/images/logoNavBarMin.png"/></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Gestión A4Plus</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= base_url() ?>assets/images/empleado/<?= $empleado->avatar ?>"
                             class="user-image imgAvatarNavbar" alt="User Image">
                        <span class="hidden-xs nombreEmpleadoNavbar"><?= $empleado->nombre ?> <?= $empleado->apellidos ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= base_url() ?>assets/images/empleado/<?= $empleado->avatar ?>"
                                 class="img-circle imgAvatarNavbar" alt="User Image">
                            <p>
                                <span class="label bg-blue nombreEmpleadoNavbar"><?= $empleado->nombre ?> <?= $empleado->apellidos ?></span>
                                <span class="label bg-blue"><?= $empleado->rol->nombre ?></span>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?= base_url() ?>perfil" class="btn btn-info btn-flat">Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?= base_url() ?>logout" class="btn btn-danger btn-flat">Cerrar sesión</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <!-- <li>
                  <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>-->
            </ul>
        </div>
    </nav>
</header>
  
  