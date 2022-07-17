<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <?php include('includes/header.php'); ?>

    <!-- Left side column. contains the logo and sidebar -->
    <?php include('includes/sidebar.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Ver empleado
            </h1>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <div class="row">
                <?php
                $rol = $ultimaConexion = "";

                if (!is_null($empleadoEditar->rolID)) {
                    $rolModel = new Rol_model($empleadoEditar->rolID);
                    if ($empleadoEditar->rolID == -1) $rolModel->nombre = "Super Administrador";
                    $rol = '<p><b>Rol: </b><br>' . $rolModel->nombre . '</p>';
                }

                if (!is_null($empleadoEditar->ultimaConexion)) {
                    $ultimaConexion = '<p><b>Última conexión: </b><br>' . $empleadoEditar->ultimaConexion . '</p>';
                }

                ?>
                <section class="col-lg-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Información del empleado</h3>
                        </div>

                        <div class="box-body">
                            <div class="col-md-12" style="text-align:center;">
                                <img class="img-circle"
                                     src="<?php echo base_url(); ?>/assets/images/empleado/<?php echo $empleadoEditar->avatar; ?>"
                                     data-name="<?php echo $empleadoEditar->avatar; ?>" id="imgAvatar"/>
                            </div>
                            <div class="col-md-12 espacioArriba">
                                <p><b>Correo electrónico:</b><br> <?php echo $empleadoEditar->email; ?></p>
                                <p>
                                    <b>Nombre:</b><br> <?php echo $empleadoEditar->nombre; ?> <?php echo $empleadoEditar->apellidos; ?>
                                </p>
                                <?php echo $rol; ?>
                                <p><b>Estado</b><br> <span
                                            class="label label-<?php echo $empleado->traducirEstadoColor($empleadoEditar->estado); ?>"><?php echo $empleado->traducirEstado($empleadoEditar->estado); ?></span>
                                </p>
                                <a class="btn btn-primary"
                                   href="<?= base_url() ?>empleado/editar/<?= $empleadoEditar->id ?>"><i
                                            class="fa fa-pencil-alt"></i>Editar</a>
                                <button class="btn btn-danger btn_abrirModalBorrarCliente"
                                        data-id="<?= $empleadoEditar->id ?>" data-toggle="modal"
                                        data-target="#modalBorrarEmpleado"><i class="fa fa-trash"></i>Borrar
                                </button>
                                <?php echo $ultimaConexion; ?>
                            </div>
                        </div>
                    </div>

                </section>

            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->
    <?php include('modals/borrarEmpleado.php'); ?>
    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?= base_url() ?>assets/js/validator.js"></script>
<script src="<?= base_url() ?>assets/js/empleado.js"></script>
</html>
