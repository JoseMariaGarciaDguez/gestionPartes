<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <?php include 'includes/header.php'; ?>

    <!-- Left side column. contains the logo and sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?= $asignador->getTitleHTML('Historial de asignaciones'); ?>

        <!-- Main content -->
        <section class="content">

            <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Listado de asignaciones</h3>
            </div>

            <div class="box-body">
                <div class="col-sm-12 espacioAbajo">
                    <a type="button" href="<?=base_url()?>asignador/editar" class="btn btn-success" ><i class="fa fa-plus"></i></a>
                    <button type="button" class="btn btn-info seleccionarTodos"><i class="fa fa-check-double"></i></button>
                    <div class="btn-group accionSeleccion" style="display:none;">
                        <?php if($empleado->rol->tieneRol($this->uri->segment(1)."_borrar_todos",$empleado)){ ?>
                            <button type="button" data-target="#modalBorrarAsignadores" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTabla"><i class="fa fa-trash"></i></button>
                        <?php } ?>

                    </div>
                </div>
                <table id="asignadorTabla" class="table table-bordered table-striped table-hover seleccionTabla">
                    <thead>
                    <tr>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
    </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'includes/footer.php'; ?>
<?php include 'includes/sidebar_config.php'; ?>
</div>

</body>

<script src="<?=base_url()?>assets/js/asignador.js"></script>
<?php include('modals/borrarAsignador.php');?>
<?php include('modals/borrarAsignadores.php');?>
</html>
