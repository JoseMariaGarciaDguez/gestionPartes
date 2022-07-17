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
                Ver Parte
            </h1>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <div id="toprint">
                <div class="row">
                    <section class="col-lg-4">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Información del parte</h3>
                            </div>

                            <div class="box-body">
                                <div class="col-md-12">
                                    <p><b>Referencia</b><br> <span id="parteID">PAR<?php echo $parte->id; ?></span></p>
                                    <p><b>Estado</b><br> <span
                                                class="label label-<?php echo $parte->traducirEstadoColor($parte->estado); ?>"><?php echo $parte->traducirEstado($parte->estado); ?></span>
                                    </p>
                                    <p><b>Fecha de Creación</b><br> <?php echo $parte->fechaCreacion; ?></p>
                                    <p><b>Creado por</b><br> <a
                                                href="'.base_url().'empleado/ver/<?php echo $parte->byEmpleadoID; ?>"><?php echo $empleado->traducirEmpleado($parte->byEmpleadoID); ?></a>
                                    </p>

                                    <?php if (is_numeric($parte->obraID)) { ?>
                                        <p><b>Obra asignada: </b> <a
                                                    href="<?= base_url() ?>obra/ver/<?= $parte->obraID ?>"><?= $this->obra->traducirObra($parte->obraID) ?></a></br>
                                        </p>
                                    <?php } else { ?>
                                        <p><b>Obra: </b><?= $parte->obraID ?></br></p>
                                    <?php } ?>
                                    <p><b>Tipo de parte</b><br> <span
                                                class="label label-<?php echo $parte->traducirTipoColor($parte->tipo); ?>"><?php echo $parte->traducirTipo($parte->tipo); ?></span>
                                    </p>
                                    <?php if ($parte->dni != null) { ?>
                                        <p><b>DNI</b><br> <?php echo $parte->dni; ?></p>
                                    <?php } ?>

                                    <?php if ($parte->avatar != null) { ?>
                                        <b>Imágenes</b> <span class="label label-success" id="descargarTodasImagenes">(Descargar imagenes)</span>
                                        <br>
                                        <?php for ($x = 0; $x < sizeof($parte->avatar); $x++) { ?>
                                            <div class="image-card" position="<?php echo $x; ?>">
                                                <img class="img-fluid verImagenSinBorrar"
                                                     src="<?php echo base_url(); ?>/assets/images/parte/<?php echo $parte->avatar[$x]; ?>"/>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ($parte->firma != null) { ?>
                                        <br>
                                        <b>Firma:</b>
                                        <br>
                                        <canvas data-disable="true"
                                                id="firma"><?= json_encode($parte->firma); ?></canvas>
                                        <div style="display:none;"
                                             id="firmaDibujo"><?= json_encode($parte->firma); ?></div>
                                    <?php } ?>
                                </div>
                                <a class="btn btn-primary" href="<?= base_url() ?>parte/editar/<?= $parte->id ?>"><i
                                            class="fa fa-pencil-alt"></i>Editar</a>
                                <button class="btn btn-danger btn_abrirModalBorrarParte" data-id="<?= $parte->id ?>"
                                        data-toggle="modal" data-target="#modalBorrarParte"><i class="fa fa-trash"></i>Borrar
                                </button>
                            </div>
                        </div>

                    </section>


                    <section class="col-lg-8">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Detalles del parte</h3>
                            </div>
                            <div class="box-body">
                                <div class="col-md-12">
                                    <p><b>Duración:</b> <?php echo $parte->horas; ?> horas</p>
                                    <?php if ($parte->material != null) { ?>
                                        <p><b>Material</b><br> <?php echo $parte->material; ?></p>
                                    <?php } ?>
                                    <?php if ($parte->observaciones != null) { ?>
                                        <p><b>Trabajo realizado</b><br> <?php echo $parte->observaciones; ?></p>
                                    <?php } ?>
                                    <?php if ($parte->observacionesExtra != null) { ?>
                                        <p><b>Observaciones extra</b><br> <?php echo $parte->observacionesExtra; ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </section>
                </div>
                <div class="row">

                    <section class="col-lg-4">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Empleados asignados</h3>
                            </div>

                            <div class="box-body">
                                <div class="col-md-12">
                                    <ul class="list-group">
                                        <?php $query = $empleado->obtenerEmpleadosTabla()->result_array(); ?>
                                        <?php foreach ($query as $row) { ?>
                                            <?php if (in_array($row['id'], $parte->empleadosID)) { ?>
                                                <li class="list-group-item"><img class="img-25-redonda"
                                                                                 src="<?= base_url() ?>assets/images/empleado/<?php echo $row['avatar']; ?>"/>
                                                    <a href="<?= base_url() ?>empleado/ver/<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?>
                                                        &nbsp;<?php echo $row['apellidos']; ?></a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </section>

                    <section class="col-lg-8">
                        <?php if (!empty($obra->id)) { ?>
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Datos de la obra asignada</h3>
                                </div>

                                <div class="box-body">
                                    <p><b>Estado</b><br> <span
                                                class="label label-<?php echo $this->obra->traducirEstadoColor($obra->estado); ?>"><?php echo $this->obra->traducirEstado($obra->estado); ?></span>
                                    </p>
                                    <p><b>Nombre</b><br> <?php echo $obra->nombre; ?></p>
                                    <p><b>Descripción</b><br> <?php echo $obra->descripcion; ?></p>

                                </div>
                            </div>
                        <?php } ?>
                    </section>
                </div>

            </div>
            <div class="row no-print">
                <div class="col-xs-12">
                    <button id="descargarpdf" parteID="<?php echo $parte->id; ?>"
                            empleadoID="<?php echo $empleado->id; ?>" class="btn btn-primary pull-right">
                        <i class="fa fa-download"></i> Generar PDF
                    </button>
                </div>
            </div>
        </section>
    </div>


    <!-- /.content-wrapper -->
    <?php include('modals/borrarParte.php'); ?>
    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
    <?php include('modals/verImagen.php'); ?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?= base_url() ?>assets/components/signature-pad/js/signature_pad.umd.js"></script>
<script src="<?= base_url() ?>assets/js/parte.js"></script>
<script src="<?= base_url() ?>assets/js/printThis.js"></script>
<script src="<?= base_url() ?>assets/js/jspdf.min.js"></script>
<script src="<?= base_url() ?>assets/js/html2canvas.min.js"></script>
</html>
