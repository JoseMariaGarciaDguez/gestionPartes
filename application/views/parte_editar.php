<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>
<?php
$disabled = "";
if (!tieneRol('parte', 'editar', 'fecha', $empleado)) $disabled = "disabled";

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
                Modificar parte
            </h1>
            <span hidden id="parteID"><?php echo $parte->id; ?></span>
        </section>
        <!-- Main content -->
        <!-- Main content -->
        <section class="content">

            <!-- Main row -->
            <div class="row">

                <section class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Modificar parte</h3>
                        </div>
                        <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formEditarParte">
                            <div class="box-body">

                                <fieldset>

                                    <div class="form-group has-feedback">
                                        <label for="estado">Estado *</label>
                                        <select name="estado" class="form-control selectpicker"
                                                data-required-error="Este campo es obligatorio." required>
                                            <option <?php if ($parte->estado == 'P') echo "selected"; ?> value="P">
                                                Pendiente
                                            </option>
                                            <option <?php if ($parte->estado == 'C') echo "selected"; ?> value="C">
                                                Cerrado
                                            </option>
                                        </select>

                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback" id="obraGrupo">
                                        <label for="obra">Obra</label>
                                        <input class="form-control" <?php if (is_numeric($parte->obraID)) echo 'style="display:none;"'; else echo 'value="' . $parte->obraID . '"'; ?>
                                               placeholder="Obra" maxlength="32" id="textObra" name="obra" type="text">
                                        <select name="obra"
                                                id="selectObra" <?php if (!is_numeric($parte->obraID)) echo 'style="display:none;"'; ?>
                                                class="form-control selectpicker" data-live-search="true">
                                            <option value="NULL">Sin obra</option>
                                            <?php $query = $obra->obtenerObrasTabla('A')->result_array(); ?>
                                            <?php foreach ($query as $row) { ?>
                                                <option <?php if ($parte->obraID == $row['id']) echo "selected"; ?>
                                                        value="<?= $row['id']; ?>"><?= $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="fechaCreacion">Fecha de Creación *</label>
                                        <input <?php echo $disabled; ?> class="form-control"
                                                                        data-date-format="dd/mm/yyyy" data-language="es"
                                                                        data-provide="datepicker" name="fechaCreacion"
                                                                        value="<?= $parte->fechaCreacion; ?>"
                                                                        type="datetime"
                                                                        data-required-error="Este campo es obligatorio."
                                                                        required>

                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="tipo">Tipo *</label>
                                        <select name="tipo" class="form-control selectpicker"
                                                data-required-error="Este campo es obligatorio." required>
                                            <option <?php if ($parte->tipo == 'T') echo "selected"; ?> value="T">
                                                Trabajo
                                            </option>
                                            <option <?php if ($parte->tipo == 'A') echo "selected"; ?> value="A">
                                                Avería
                                            </option>
                                        </select>

                                        <span class="glyphicon form-control-feedback"></span>
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
                                                <option <?php if (in_array($row['id'], $parte->empleadosID)) echo "selected"; ?>
                                                        value="<?= $row['id']; ?>"><?= $row['nombre']; ?> <?= $row['apellidos']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="horas">Horas *</label>
                                        <input class="form-control" value="<?= $parte->horas; ?>" placeholder="Horas"
                                               name="horas" pattern="^[0-9]{1,}$" type="text"
                                               data-required-error="Este campo es obligatorio."
                                               data-pattern-error="Debes escribir un número."
                                               required>
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="material">Material</label>
                                        <textarea class="form-control" rows="5" maxlength="60000"
                                                  name="material"><?= $parte->material; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="material">Trabajo realizado</label>
                                        <textarea class="form-control" rows="5" maxlength="60000"
                                                  name="observaciones"><?= $parte->observaciones; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="material">Observaciones extra</label>
                                        <textarea class="form-control" rows="3" maxlength="60000"
                                                  name="observacionesExtra"><?= $parte->observacionesExtra; ?></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="btn-group">
                                        <span id="btn_siFirma"
                                              class="label <?php if (empty($parte->dni) && empty($parte->firma)) echo 'bg-red'; else echo 'bg-green'; ?>"><i
                                                    class="fa fa-clipboard-check "></i> Activar firma</span>
                                    </div>

                                    <div id="grupo-firma"
                                         style="<?php if (empty($parte->dni) && empty($parte->firma)) echo 'display:none;'; ?>">
                                        <div class=" has-feedback col-sm-4">
                                            <label for="dni">DNI</label>
                                            <input class="form-control" placeholder="12345678A" maxlength="9" name="dni"
                                                   value="<?= $parte->dni; ?>" type="text"
                                                   data-required-error="Este campo es obligatorio."
                                            >
                                            <span class="glyphicon form-control-feedback"></span>
                                            <div class="help-block with-errors"></div>
                                            <div class="col-sm-4">
                                                <button type="button" class="btn btn-app" id="btn_reiniciarFirma"><i
                                                            class="fa fa-redo"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <label for="firma">Firma</label>
                                            <br>
                                            <canvas style="touch-action: auto;" id="firma"></canvas>
                                        </div>

                                    </div>

                                    <hr>
                                    <label for="material">Imágenes</label> <span class="label label-success"
                                                                                 id="descargarTodasImagenes">(Descargar imagenes)</span>
                                    <div class="col-md-12" id="contendor-imagenes">
                                        <input id="agregarImagenesForm" class="image-card" accept="image/*" type="file"
                                               multiple>
                                        <div type="file" class="image-card" id="agregarImagenes">
                                            <i class="fa fa-plus-square"></i>
                                        </div>
                                        <?php if ($parte->avatar != null) { ?>
                                            <?php for ($x = 0; $x < count($parte->avatar); $x++) { ?>
                                                <?php
                                                $path = 'assets/images/parte/' . $parte->avatar[$x];
                                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                                $data = file_get_contents($path);
                                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                                                ?>
                                                <div class="image-card" position="<?php echo $x; ?>">
                                                    <img class="img-fluid verImagen"
                                                         src="<?php echo $base64; ?>"/>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>


                                </fieldset>

                            </div>

                            <div class="box-footer">
                                <button class="btn btn-lg btn-success btn-block" type="submit" id="btn_editarParte"
                                        data-id="<?= $parte->id; ?>">Modificar parte
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
<script src="<?= base_url() ?>assets/js/validator.js"></script>
<script src="<?= base_url() ?>assets/components/signature-pad/js/signature_pad.umd.js"></script>
<script src="<?= base_url() ?>assets/js/parte.js"></script>
<div style="display:none;" id="firmaDibujo"><?= json_encode($parte->firma); ?></div>
<script>
    $('.selectpicker').on('hide.bs.select', function () {
        $(this).trigger("focusout");
    });
</script>

</html>
