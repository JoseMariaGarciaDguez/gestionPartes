<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<?php
$id = null;
if (!is_null($this->uri->segment(3))) $id = $this->uri->segment(3);
?>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <?php include 'includes/header.php'; ?>

    <!-- Left side column. contains the logo and sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?= $asignador->getTitleHTML('Asignador ' . $asignador->obtenerFecha($id, true)); ?>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_Empleados" data-toggle="tab">Empleados</a></li>
                            <li><a href="#tab_Vehiculos" data-toggle="tab">Vehiculos</a></li>
                            <li><a href="#tab_Obras" data-toggle="tab">Obras</a></li>

                        </ul>


                        <div class="tab-content contenidoScroll ">

                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label for="search">Buscar </label>
                                        <input id="buscarTexto" class="form-control" placeholder="Buscar" maxlength="64" name="search" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane active " id="tab_Empleados">
                                <?php echo $HTMLEmpleados; ?>
                            </div>

                            <div class="tab-pane " id="tab_Vehiculos">
                                <?php echo $HTMLVehiculos; ?>
                            </div>

                            <div class="tab-pane " id="tab_Obras">
                                <?php echo $HTMLObras; ?>
                            </div>

                            <div class="row">
                                <div class=" col-md-10">
                                    <?php
                                    $fecha = date("d/m/Y");
                                    if (!is_null($this->uri->segment(3)))
                                        $fecha = str_replace('-', '/', $this->uri->segment(3));

                                    ?>
                                    <div class="form-group">
                                        <input class="form-control datepicker" name="fecha"
                                               value="<?= $fecha ?>"
                                               type="datetime"
                                               data-required-error="Este campo es obligatorio."
                                               required />
                                    </div>
                                </div>
                                <div class=" col-md-2">
                                    <button class="btn btn-success" id="guardarDatos">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row asignadorCards">
                <?php echo $asignador->obtenerVisorHTML($id); ?>

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/sidebar_config.php'; ?>
</div>

</body>

<script src="<?= base_url() ?>assets/js/asignador.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.mousewheel.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.mCustomScrollbar.min.js"></script>
</html>
