<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>
<?php
$disabled = "";
if (!tieneRol('parte', 'crear', 'fecha', $empleado)) $disabled = "disabled";
header("Access-Control-Allow-Origin: *");
?>

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
                Crear parte
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
                            <h3 class="box-title">Crear parte</h3>
                        </div>
                        <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formCrearParte">
                            <div class="box-body">


                                <fieldset>
                                    <div class="form-group has-feedback">
                                        <label for="estado">Estado *</label>
                                        <select name="estado" class="form-control selectpicker"
                                                data-required-error="Este campo es obligatorio." required>
                                            <option selected value="P">Pendiente</option>
                                            <option value="C">Cerrado</option>
                                        </select>

                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback" id="obraGrupo">
                                        <label for="obra">Obra</label>
                                        <input class="form-control" placeholder="Obra" maxlength="32"
                                               style="display:none;" id="textObra" name="obra" type="text">
                                        <select name="obra" id="selectObra" class="form-control selectpicker"
                                                title="Selecciona una obra..." data-live-search="true">
                                            <?php $query = $obra->obtenerObrasActivasTabla()->result_array(); ?>
                                            <option selected value="NULL">Sin obra</option>
                                            <?php foreach ($query as $row) { ?>
                                                <option value="<?= $row['id']; ?>"><?= $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>


                                    <div class="form-group has-feedback">
                                        <label for="fechaCreacion">Fecha de Creación *</label>
                                        <input <?php echo $disabled; ?> class="form-control"
                                                                        data-date-format="dd/mm/yyyy" data-language="es"
                                                                        data-provide="datepicker" name="fechaCreacion"
                                                                        value="<?php echo date('d/m/Y'); ?>"
                                                                        type="datetime"
                                                                        data-required-error="Este campo es obligatorio."
                                                                        required>
                                        <div class="help-block with-errors"></div>
                                        <span class="glyphicon form-control-feedback"></span>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="tipo">Tipo *</label>
                                        <select name="tipo" class="form-control selectpicker"
                                                title="Selecciona un tipo..."
                                                data-required-error="Este campo es obligatorio." required>
                                            <option value="T">Trabajo</option>
                                            <option value="A">Avería</option>
                                        </select>

                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="empleadosID">Empleados *</label>
                                        <select name="empleadosID[]" class="form-control selectpicker"
                                                data-live-search="true" data-required-error="Este campo es obligatorio."
                                                required multiple title="Selecciona un empleado...">
                                            <?php $query = $empleado->obtenerEmpleadosTabla('A')->result_array(); ?>
                                            <?php foreach ($query as $row) { ?>
                                                <?php
                                                $tempEmpleado = new Empleado_model($row['id']);
                                                if ($empleado->rol->tieneRol("empleado_poder_participar", $tempEmpleado) == false) continue;
                                                else unset($tempEmpleado);
                                                ?>
                                                <option <?php if ($empleado->id == $row['id']) echo "selected"; ?>
                                                        value="<?= $row['id']; ?>"><?= $row['nombre']; ?> <?= $row['apellidos']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="horas">Horas *</label>
                                        <input class="form-control" placeholder="Horas" name="horas"
                                               pattern="^[0-9]{1,}$" type="text"
                                               data-required-error="Este campo es obligatorio."
                                               data-pattern-error="Debes escribir un número."
                                               required>
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="material">Material</label>
                                        <textarea class="form-control" rows="5" maxlength="60000"
                                                  name="material"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="material">Trabajo realizado</label>
                                        <textarea class="form-control" rows="5" maxlength="60000"
                                                  name="observaciones"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="material">Observaciones extra</label>
                                        <textarea class="form-control" rows="3" maxlength="60000" name="observacionesExtra"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="btn-group">
                                        <span id="btn_siFirma" class="label bg-red"><i
                                                    class="fa fa-clipboard-check "></i> Activar firma</span>
                                    </div>

                                    <div id="grupo-firma" style="display:none;">
                                        <div class=" has-feedback col-sm-4">
                                            <label for="dni">DNI</label>
                                            <input class="form-control" placeholder="12345678A" maxlength="9" name="dni"
                                                   type="text"
                                                   data-required-error="Este campo es obligatorio."
                                            >
                                            <span class="glyphicon form-control-feedback"></span>
                                            <div class="help-block with-errors"></div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-app" id="btn_reiniciarFirma"><i
                                                            class="fa fa-redo"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <label for="firma">Firma</label>
                                            <br>
                                            <canvas style="touch-action: none;" id="firma"></canvas>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <hr>
                                        <label for="material">Imágenes</label>
                                        <div class="col-md-12" id="contendor-imagenes">
                                            <input id="agregarImagenesForm" class="image-card" accept="image/*"
                                                   type="file" multiple>
                                            <div type="file" class="image-card" id="agregarImagenes">
                                                <i class="fa fa-plus-square"></i>
                                            </div>
                                        </div>
                                    </div>


                                </fieldset>


                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-lg btn-success btn-block" id="btn_crearParte">Crear
                                    parte
                                </button>
                            </div>
                        </form>
                    </div>

                </section>

            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->

    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
    <?php include('modals/verImagen.php'); ?>
</div>
<!-- ./wrapper -->

</body>
<script src="<?= base_url() ?>assets/components/signature-pad/js/signature_pad.umd.js"></script>
<script src="<?= base_url() ?>assets/js/parte.js"></script>
</html>
