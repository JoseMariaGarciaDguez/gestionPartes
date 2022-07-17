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
                Ver rol
            </h1>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formEditar">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Información del rol</h3>
                    </div>

                    <div class="box-body">
                        <div class="col-md-12 espacioArriba">
                            <p><b>Referencia</b><br> ROL<?php echo $rol->id; ?></p>
                            <p><b>Nombre</b><br> <?php echo $rol->nombre; ?></p>
                        </div>

                    </div>

                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Permisos</h3>
                    </div>


                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-user "></i> Permisos de Clientes</h4>
                                <?php echo obtenerPermisos('cliente', $rol, false); ?>
                            </div>

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-user-tie "></i> Permisos de Empleados</h4>
                                <?php echo obtenerPermisos('empleado', $rol, false); ?>
                            </div>

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-file-invoice-dollar "></i> Permisos de Albaranes</h4>
                                <?php echo obtenerPermisos('albaran', $rol, false); ?>
                            </div>

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-file-contract "></i> Permisos de Obras</h4>
                                <?php echo obtenerPermisos('obra', $rol, false); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-truck "></i> Permisos de Vehiculos</h4>
                                <?php echo obtenerPermisos('vehiculo', $rol, false); ?>
                            </div>

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-calendar "></i> Permisos de Partes</h4>
                                <?php echo obtenerPermisos('parte', $rol, false); ?>
                            </div>

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-receipt "></i> Permisos de Informes</h4>
                                <?php echo obtenerPermisos('informes', $rol, false); ?>
                            </div>

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-hand-paper"></i> Permisos de Asignador</h4>
                                <?php echo obtenerPermisos('asignador', $rol, false); ?>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-lg-3">
                                <h4 class="box-title"><i class="fa fa-user-circle "></i> Permisos de Roles</h4>
                                <?php echo obtenerPermisos('rol', $rol, false); ?>
                            </div>
                        </div>


                    </div>

                </div>


            </form>

        </section>

    </div>
    <!-- /.content-wrapper -->

    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/rol.js"></script>

</html>
