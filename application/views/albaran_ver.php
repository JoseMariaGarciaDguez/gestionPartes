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
                Ver Albarán
            </h1>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">
            <section class="invoice">
                <!-- title row -->
                <div id="toprint">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> 4PlusINT
                            </h2>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <!-- /.col -->
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <p class="hAlbaran">Información del Albarán</p>
                            <b>Referencia:</b> <span id="albaranID">ALB<?php echo $albaran->id; ?></span><br>
                            <b>Nombre del Albarán:</b> <?php echo $albaran->nombreAlbaran; ?><br>
                            <b>Fecha de Creación:</b> <?php echo $albaran->fechaCreacion; ?><br>
                            <b>Creado por:</b> <a
                                    href="<?=base_url()?>empleado/ver/<?php echo $albaran->byEmpleadoID; ?>"><?php $empleado = new Empleado_model($albaran->byEmpleadoID);
                                echo $empleado->nombre . ' ' . $empleado->apellidos; ?></a>
                        </div>
                        <div class="col-sm-8 invoice-col">
                            <address>
                                <?php if ($cliente->direccion != null || $albaran->nombre != null || $albaran->apellidos != null) { ?>

                                    <?php if ($cliente->nombre != null || $cliente->apellidos != null) { ?>
                                        <p class="hAlbaran">Información del cliente
                                            (<?php echo $cliente->nombre . ' ' . $cliente->apellidos; ?>)</p>
                                        <p><strong><?php echo $cliente->nombre . ' ' . $cliente->apellidos; ?></strong>
                                        </p>
                                    <?php } elseif ($albaran->nombre != null || $albaran->apellidos != null) { ?>
                                        <p class="hAlbaran">Información del cliente
                                            (<?php echo $albaran->nombre . ' ' . $albaran->apellidos; ?>)</p>
                                        <p><strong><?php echo $albaran->nombre . ' ' . $albaran->apellidos; ?></strong>
                                        </p>
                                    <?php } ?>

                                    <?php if ($cliente->direccion != null) { ?>
                                        <p><b>Dirección</b><br><?php echo $cliente->direccion; ?><br>
                                            <?php echo $cliente->localidad; ?>, <?php echo $cliente->provincia; ?>,
                                            CP <?php echo $cliente->codigoPostal; ?>, <?php echo $cliente->pais; ?></p>
                                        <p><b>Correo electrónico </b><br><?php echo $cliente->email; ?></p>
                                    <?php } ?>
                                <?php } ?>
                            </address>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <hr>
                    <!-- Table row -->
                    <div class="row topMargin">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad/Horas</th>
                                    <th>Precio</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $subtotal = 0.00;
                                if ($albaran->articulos != null) {
                                    foreach (json_decode($albaran->articulos) as $articulo) { ?>
                                        <?php
                                        $subtotal = floatval($subtotal) + floatval($articulo[2] * $articulo[1]);
                                        ?>
                                        <tr>
                                            <td class="articuloTablaPrimero"><?php echo $articulo[0][2]; ?></td>
                                            <td><?php echo $articulo[1]; ?></td>
                                            <td><?php echo $articulo[2]; ?>€</td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- /.col -->
                        <div class="col-xs-6" style="float:right;">
                            <p class="lead"></p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td><?php echo $subtotal; ?>€</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td><?php echo $subtotal; ?>€</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.row -->

                <!-- this row will not appear when printing -->
                <div class="row no-print">
                    <div class="col-xs-12">
                        <button id="descargarpdf" albaranID="<?php echo $albaran->id;?>" empleadoID="<?php echo $empleado->id;?>" class="btn btn-primary pull-right">
                            <i class="fa fa-download"></i> Generar PDF
                        </button>
                        <a  class="btn btn-primary" href="<?=base_url()?>albaran/editar/<?=$albaran->id?>"><i class="fa fa-pencil-alt"></i>Editar</a>
                        <button class="btn btn-danger btn_abrirModalBorrarAlbaran" data-id="<?=$albaran->id?>" data-toggle="modal" data-target="#modalBorrarAlbaran"><i class="fa fa-trash"></i>Borrar</button>
                    </div>
                </div>
            </section>
        </section>

    </div>
    <!-- /.content-wrapper -->
    <?php include('modals/borrarAlbaran.php');?>
    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
</div>
<!-- ./wrapper -->

</body>
<div id="editor"></div>
<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/albaran.js"></script>
<script src="<?=base_url()?>assets/js/printThis.js"></script>
<script src="<?=base_url()?>assets/js/jspdf.min.js"></script>
<script src="<?=base_url()?>assets/js/html2canvas.min.js"></script>
</html>

