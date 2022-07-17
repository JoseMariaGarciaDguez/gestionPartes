<!DOCTYPE html>
<html>
<?php
include('includes/head.php');
$editarNombre = $editarEmail = "";
if(!$empleado->rol->tieneRol('empleado_editar_nombre',$empleado)) $editarNombre = 'readonly disabled';
if(!$empleado->rol->tieneRol('empleado_editar_email',$empleado)) $editarEmail = 'readonly disabled';

?>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

	<?php include('includes/header.php'); ?>
	<?php include('includes/sidebar.php'); ?>

	<div class="content-wrapper">
		<section class="content-header">
			<h1>Perfíl</h1>
		</section>
		<section class="content">
			<div class="row">
				<section class="col-lg-7">
				<div class="box box-primary">
				  <div class="box-header with-border">
					<h3 class="box-title">Ajustes del perfíl</h3>
				  </div>
					<div class="box-body">

						<form data-toggle="validator" accept-charset="UTF-8" role="form" id="formPerfil">
							<fieldset>
							
								<div class="form-group has-feedback">
									<label for="email">Correo electrónico</label>
									<input <?=$editarEmail?> class="form-control" placeholder="Correo electrónico" maxlength="64" name="email" value="<?=$empleado->email?>" type="email"
											data-error="Correo electrónico inválido."
											data-required-error="Este campo es obligatorio."
											data-incorrect="Este correo electrónico no existe."
									required>
								<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group has-feedback">
									<label for="nombre">Nombre *</label>
									<input <?=$editarNombre?> class="form-control" placeholder="Nombre" maxlength="32" name="nombre" value="<?=$empleado->nombre?>" type="text"
											data-required-error="Este campo es obligatorio."
									required>
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group">
									<label for="apellidos">Apellidos</label>
									<input <?=$editarNombre?> class="form-control" placeholder="Apellidos" maxlength="64" name="apellidos" value="<?=$empleado->apellidos?>" type="text">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group">
									<section class="col-lg-4">
										<img src="<?php echo base_url(); ?>/assets/images/empleado/<?=$empleado->avatar?>" data-name="<?=$empleado->avatar?>" id="imgAvatar"/>
									</section>
									<section class="col-lg-8">
										<label for="avatar">Avatar</label>
										<input class="form-control" id="inputAvatar"  data-width="256" data-height="256" name="avatar" type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg">
										<div class="help-block with-errors"></div>
									</section>
								</div>

							</fieldset>
						</form>

					</div>
				  <div class="box-footer">
					<button class="btn btn-lg btn-success btn-block" id="btn_actualizarPerfil">Actualizar perfíl</button>
					</div>
				</div>

				</section>
				<section class="col-lg-5">
				<div class="box box-primary">
				  <div class="box-header with-border">
					<h3 class="box-title">Cambio de contraseña</h3>
				  </div>
					<div class="box-body">

						<form data-toggle="validator" accept-charset="UTF-8" role="form" id="formClave">
							<fieldset>

								<div class="form-group">
									<label for="password">Contraseña actual</label>
									<input class="form-control" placeholder="Contraseña actual" name="oldpassword" type="password" 
											data-required-error="Este campo es obligatorio."
									required>
									<div class="help-block with-errors"></div>
								</div>

								<div class="form-group">
									<label for="password">Contraseña nueva</label>
									<input class="form-control" placeholder="Contraseña nueva" name="newpassword" id="newpassword" type="password"
											data-required-error="Este campo es obligatorio."
											data-incorrect="La contraseña es incorrecta."
									required>
									<div class="help-block with-errors"></div>
								</div>

								<div class="form-group">
									<label for="password">Repetir contraseña nueva</label>
									<input class="form-control" placeholder="Repetir contraseña nueva" name="repassword" type="password" data-match="#newpassword"
											data-required-error="Este campo es obligatorio."
											data-incorrect="La contraseña es incorrecta."
											data-match-error="Las contraseñas no coinciden."
									required>
									<div class="help-block with-errors"></div>
								</div>

							</fieldset>
						</form>

					</div>
				  <div class="box-footer">
					<button class="btn btn-lg btn-success btn-block" id="btn_cambiarClave">Cambiar contraseña</button>
					</div>
				</div>
					
				</section>
				
			</div>

		</section>
	</div>


	<?php include('includes/footer.php');?>
	<?php include('includes/sidebar_config.php');?>
	
</div>
<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/profile.js"></script>
</body>
</html>
