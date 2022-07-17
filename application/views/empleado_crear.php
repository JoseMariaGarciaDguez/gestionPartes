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
                Crear un nuevo empleado
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
                            <h3 class="box-title">Listado de empleados</h3>
                        </div>
                        <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formCrearEmpleado">
                            <div class="box-body">


                                <fieldset>
                                    <div class="form-group has-feedback">
                                        <label for="email">Correo electrónico *</label>
                                        <input class="form-control" placeholder="Correo electrónico" maxlength="64"
                                               name="email" type="email"
                                               data-error="Correo electrónico inválido."
                                               data-required-error="Este campo es obligatorio."
                                               required>
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="nombre">Nombre *</label>
                                        <input class="form-control" placeholder="Nombre" maxlength="32" name="nombre"
                                               type="text"
                                               data-required-error="Este campo es obligatorio."
                                               required>
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="apellidos">Apellidos</label>
                                        <input class="form-control" placeholder="Apellidos" maxlength="64"
                                               name="apellidos" type="text">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label for="password">Contraseña *</label>
                                        <input class="form-control" placeholder="Contraseña" name="password"
                                               type="password"
                                               data-required-error="Este campo es obligatorio."
                                               required>
                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group has-feedback">
                                        <label for="password">Teléfono</label>
                                        <input class="form-control" placeholder="123456789" minlength="9" maxlength="9"
                                               name="telefono" type="text" pattern="^[0-9]{1,}$" type="text"
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
                                                <option value="<?= $row['id']; ?>"><?= $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <?php $estadoShow = ''; if(!$empleado->rol->tieneRol('empleado_editar_estado',$empleado)) $estadoShow = 'style="display:none"'; ?>
                                    <div <?=$estadoShow?> class="form-group has-feedback">
                                        <label for="estado">Estado *</label>
                                        <select name="estado" class="form-control selectpicker"
                                                data-required-error="Este campo es obligatorio." required>
                                            <option selected value="A">Activo</option>
                                            <option value="B">Baja</option>
                                        </select>

                                        <span class="glyphicon form-control-feedback"></span>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="form-group">
                                        <section class="col-lg-4">
                                            <img src="<?php echo base_url(); ?>/assets/images/empleado/default.png"
                                                 data-name="default.png" id="imgAvatar"/>
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
                                <button type="submit" class="btn btn-lg btn-success btn-block" id="btn_crearEmpleado">
                                    Crear empleado
                                </button>
                            </div>
                        </form>
                    </div>

                </section>

            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->
    <?php include('modals/borrarCliente.php'); ?>
    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?= base_url() ?>assets/js/validator.js"></script>
<script src="<?= base_url() ?>assets/js/empleado.js"></script>
</html>
