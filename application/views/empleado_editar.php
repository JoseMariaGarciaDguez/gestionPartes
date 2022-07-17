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
                Modificar empleado
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
                            <h3 class="box-title">Modificar empleado</h3>
                        </div>
                        <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formEditarEmpleado"
                              data-id="<?php echo $empleadoEditar->id; ?>">
                            <div class="box-body">


                                <fieldset>
                                    <div class="form-group has-feedback">
                                        <label for="email">Correo electrónico *</label>
                                        <input class="form-control" placeholder="Correo electrónico" maxlength="64"
                                               value="<?php echo $empleadoEditar->email; ?>" name="email" type="email"
                                               data-error="Correo electrónico inválido."
                                               data-required-error="Este campo es obligatorio."
                                               required>
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="nombre">Nombre *</label>
                                        <input class="form-control" placeholder="Nombre" maxlength="32"
                                               value="<?php echo $empleadoEditar->nombre; ?>" name="nombre" type="text"
                                               required data-required-error="Este campo es obligatorio."
                                        >
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellidos">Apellidos</label>
                                        <input class="form-control" placeholder="Apellidos" maxlength="64"
                                               value="<?php echo $empleadoEditar->apellidos; ?>" name="apellidos"
                                               type="text">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="password">Contraseña *</label>
                                        <input class="form-control" placeholder="Contraseña" name="password"
                                               type="text">
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="password">Teléfono</label>
                                        <input class="form-control" placeholder="123456789" maxlength="9"
                                               value="<?= $empleadoEditar->telefono ?>" name="telefono" type="text"
                                               pattern="^[0-9]{1,}$" type="text"
                                               data-pattern-error="Debes escribir un número.">
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="rolID">Rol</label>
                                        <select id="rolID" name="rolID" class="form-control selectpicker"
                                                data-live-search="true" data-required-error="Este campo es obligatorio."
                                                required title="Selecciona un rol...">
                                            <?php $query = $empleado->obtenerRolesTabla('id,nombre')->result_array(); ?>
                                            <option selected value="null">Ninguno</option>
                                            <?php foreach ($query as $row) { ?>
                                                <?php if ($row['id'] == -1 && $empleado->rolID != -1) continue; ?>
                                                <option <?php if ($empleadoEditar->rolID == $row['id']) echo "selected"; ?>
                                                        value="<?= $row['id']; ?>"><?= $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <?php $estadoShow = ''; if(!$empleado->rol->tieneRol('empleado_editar_estado',$empleado)) $estadoShow = 'style="display:none"'; ?>
                                    <div <?=$estadoShow?> class="form-group has-feedback">
                                        <label for="estado">Estado *</label>
                                        <select name="estado" class="form-control selectpicker"
                                                data-required-error="Este campo es obligatorio." required>
                                            <option <?php if ($empleadoEditar->estado == 'B') echo "selected"; ?>
                                                    value="B">
                                                Baja
                                            </option>
                                            <option <?php if ($empleadoEditar->estado == 'A') echo "selected"; ?>
                                                    value="A">
                                                Activo
                                            </option>
                                        </select>

                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <section class="col-lg-4">
                                            <img src="<?php echo base_url(); ?>/assets/images/empleado/<?php echo $empleadoEditar->avatar; ?>"
                                                 data-name="<?php echo $empleadoEditar->avatar; ?>" id="imgAvatar"/>
                                        </section>
                                        <section class="col-lg-8">
                                            <label for="avatar">Avatar</label>
                                            <input class="form-control" data-width="256" data-height="256"
                                                   id="inputAvatar" name="avatar" type="file"
                                                   accept="image/x-png,image/gif,image/jpeg,image/jpg">
                                        </section>
                                    </div>
                                </fieldset>


                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-lg btn-success btn-block" id="btn_editarEmpleado"
                                        data-id="<?php echo $empleadoEditar->id; ?>">Editar empleado
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
</div>
<!-- ./wrapper -->

</body>

<script src="<?= base_url() ?>assets/js/validator.js"></script>
<script src="<?= base_url() ?>assets/js/empleado.js"></script>
</html>
