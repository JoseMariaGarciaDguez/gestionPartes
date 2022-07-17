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
                Ver todos los empleados
            </h1>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <div class="row">

                <section class="col-lg-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_A" data-toggle="tab">Empleados activos</a></li>
                            <li><a href="#tab_B" data-toggle="tab">Empleados de baja</a></li>

                        </ul>


                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_A">
                                <div class="col-sm-12 espacioAbajo">
                                    <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) { ?>
                                        <a type="button" href="<?= base_url() ?>empleado/crear" class="btn btn-success"><i
                                                    class="fa fa-plus"></i></a>
                                    <?php } ?>
                                    <button type="button" class="btn btn-info seleccionarTodos"><i
                                                class="fa fa-check-double"></i></button>
                                    <div class="btn-group accionSeleccion" style="display:none;">
                                        <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) { ?>
                                            <button type="button" data-target="#modalBorrarEmpleados"
                                                    data-toggle="modal"
                                                    class="btn btn-danger" id="borrarSeleccionTabla"><i
                                                        class="fa fa-trash"></i></button>
                                        <?php } ?>
                                        <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_editar_todos", $empleado)) { ?>
                                            <button type="button" data-target="#modalEditarEmpleados"
                                                    data-toggle="modal" class="btn btn-info" id="editarSeleccionTabla">
                                                <i class="fa fa-edit"></i></button>
                                        <?php } ?>

                                    </div>
                                </div>

                                <table id="empleadosTabla"
                                       class="table table-bordered table-striped table-hover seleccionTabla">
                                    <thead>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="tab-pane" id="tab_B">
                                <div class="col-sm-12 espacioAbajo">
                                    <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) { ?>
                                        <a type="button" href="<?= base_url() ?>empleado/crear" class="btn btn-success"><i
                                                    class="fa fa-plus"></i></a>
                                    <?php } ?>
                                    <button type="button" class="btn btn-info seleccionarTodos"><i
                                                class="fa fa-check-double"></i></button>
                                    <div class="btn-group accionSeleccion" style="display:none;">
                                        <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) { ?>
                                            <button type="button" data-target="#modalBorrarEmpleados"
                                                    data-toggle="modal"
                                                    class="btn btn-danger" id="borrarSeleccionTabla"><i
                                                        class="fa fa-trash"></i></button>
                                        <?php } ?>
                                        <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_editar_todos", $empleado)) { ?>
                                            <button type="button" data-target="#modalEditarEmpleados"
                                                    data-toggle="modal" class="btn btn-info" id="editarSeleccionTablaB">
                                                <i class="fa fa-edit"></i></button>
                                        <?php } ?>

                                    </div>
                                </div>

                                <table id="empleadosTablaB"
                                       class="table table-bordered table-striped table-hover seleccionTabla">
                                    <thead>
                                    <tr>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </section>

            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->

    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
    <?php include('modals/borrarEmpleado.php'); ?>
    <?php include('modals/borrarEmpleados.php'); ?>
    <?php include('modals/editarEmpleados.php'); ?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?= base_url() ?>assets/js/empleado.js"></script>

</html>