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
                Ver todos los clientes
            </h1>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <div class="row">

                <section class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Listado de clientes</h3>
                        </div>

                        <div class="box-body">
                            <div class="col-sm-12 espacioAbajo">
                                <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) { ?>
                                    <a type="button" href="<?=base_url()?>cliente/crear" class="btn btn-success"><i
                                                class="fa fa-plus"></i></a>
                                <?php } ?>
                                <button type="button" class="btn btn-info seleccionarTodos"><i
                                            class="fa fa-check-double"></i></button>
                                <div class="btn-group accionSeleccion" style="display:none;">
                                    <?php if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) { ?>
                                        <button type="button" data-target="#modalBorrarClientes" data-toggle="modal"
                                                class="btn btn-danger" id="borrarSeleccionTabla"><i
                                                    class="fa fa-trash"></i></button>
                                    <?php } ?>
                                </div>
                            </div>

                            <table id="clientesTabla"
                                   class="table table-bordered table-striped table-hover seleccionTabla">
                                <thead>
                                <tr>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                        </div>

                        <div class="box-footer">

                        </div>
                    </div>

                </section>

            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->

    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
    <?php include('modals/borrarCliente.php'); ?>
    <?php include('modals/borrarClientes.php'); ?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/cliente.js"></script>

</html>
