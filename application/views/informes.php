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
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Informes
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Filtros del Informe</h3>
                        </div>

                        <div class="box-body">


                            <?php
                            $fechaFinal = date('d/m/Y');
                            $fechaInicio = date('d/m/Y', time() - (7 * 24 * 60 * 60));
                            ?>


                            <div class="col-sm-3">
                                <label for="fechaInicio">Fecha de Inicio</label>
                                <input class="form-control" data-date-format="dd/mm/yyyy" data-language="es"
                                       data-provide="datepicker" id="fechaInicio"
                                       value="<?php if (!isset($informe->fechaInicio)) {
                                           echo $fechaInicio;
                                       } else {
                                           echo $informe->fechaInicio;
                                       }
                                       ?>"
                                       type="datetime">
                            </div>
                            <div class="col-sm-3">
                                <label for="fechaFinal">Fecha de Final</label>
                                <input class="form-control" data-date-format="dd/mm/yyyy" data-language="es"
                                       data-provide="datepicker" id="fechaFinal"
                                       value="<?php if (!isset($informe->fechaFinal)) {
                                           echo $fechaFinal;
                                       } else {
                                           echo $informe->fechaFinal;
                                       }
                                       ?>"
                                       type="datetime">
                            </div>
                            <div class="col-sm-3">
                                <label for="tablas">Tablas a mostrar</label>
                                <select id="tablas" class="form-control selectpicker" data-live-search="true"
                                        multiple required title="Selecciona...">
                                    <option value="0" <?php echo $informe->seleccionTablas(0); ?> >Gasto por Cliente
                                    </option>
                                    <option value="1" <?php echo $informe->seleccionTablas(1); ?> >Obras por Cliente
                                    </option>
                                    <option value="2" <?php echo $informe->seleccionTablas(2); ?> >Obras por Día
                                    </option>
                                    <option value="3" <?php echo $informe->seleccionTablas(3); ?> >Partes por Día
                                    </option>
                                    <option value="4" <?php echo $informe->seleccionTablas(4); ?> >Horas por Empleado
                                    </option>
                                    <option value="5" <?php echo $informe->seleccionTablas(5); ?> >Partes por Obra
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label for="fechaCreacion">Gráficos a mostrar</label>
                                <select id="graficos" class="form-control selectpicker"
                                        data-live-search="true" multiple required title="Selecciona...">
                                    <option value="0" <?php echo $informe->seleccionGraficos(0); ?> >Gasto por Cliente
                                    </option>
                                    <option value="1" <?php echo $informe->seleccionGraficos(1); ?> >Obras por Cliente
                                    </option>
                                    <option value="2" <?php echo $informe->seleccionGraficos(2); ?> >Obras y Partes por
                                        Día
                                    </option>
                                    <option value="3" <?php echo $informe->seleccionGraficos(3); ?> >Horas por
                                        Empleado
                                    </option>
                                    <option value="4" <?php echo $informe->seleccionGraficos(4); ?> >Partes por Obra
                                    </option>
                                </select>
                            </div>

                            <div class="col-sm-12">
                                <button class="btn btn-success" id="exportarPDF">Exportar a PDF</button>

                            </div>

                        </div>

                        <div class="box-footer">

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Listado de informes</h3>
                        </div>

                        <div class="box-body" id="listadoInformes">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div id="gastosPorCliente" class="grafico" data-numero="0"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="tabla" data-numero="0">
                                        <table id="gastosPorClienteTabla"
                                               class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td>Cliente</td>
                                                <td>Gasto total</td>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-5">
                                    <div id="obrasPorCliente" class="grafico" data-numero="1"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="tabla" data-numero="1">
                                        <table id="obrasPorClienteTabla"
                                               class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td>Cliente</td>
                                                <td>Obras Totales</td>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-5">
                                    <div id="obrasPartes" class="grafico" data-numero="2"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="tabla" data-numero="2">
                                        <table id="obrasTabla" class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td>Fecha</td>
                                                <td>Obras</td>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="tabla1" data-numero="3">
                                        <table id="partesTabla" class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td>Fecha</td>
                                                <td>Partes</td>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-5">
                                    <div id="horasPorEmpleado" class="grafico" data-numero="3"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="tabla" data-numero="4">
                                        <table id="horasPorEmpleadoTabla"
                                               class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td>Empleado</td>
                                                <td>Horas</td>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-5">
                                    <div id="partesPorObra" class="grafico" data-numero="4"></div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="tabla" data-numero="5">
                                        <table id="partesPorObraTabla"
                                               class="table table-bordered table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td>Obra</td>
                                                <td>Partes</td>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="box-footer">

                        </div>
                    </div>
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

<script src="<?=base_url()?>assets/js/html2canvas.min.js"></script>
<script src="<?=base_url()?>assets/js/jspdf.min.js"></script>
<script src="<?=base_url()?>assets/js/informes.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
</html>
