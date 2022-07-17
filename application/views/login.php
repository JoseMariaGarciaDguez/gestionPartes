<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>


<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?=base_url()?>"><img src="<?=base_url()?>/assets/images/a4.png"/> Gestión <b>A4Plus</b></a>
  </div>
	<div class="box box-primary">
	<form data-toggle="validator" accept-charset="UTF-8" role="form" id="formLogin">
	  <div class="box-body">
		
			<fieldset>
				<div class="form-group has-feedback">
					<label for="email">Correo electrónico</label>
					<input class="form-control" placeholder="Correo electrónico" maxlength="64" name="email" type="email" 
							data-error="Correo electrónico inválido."
							data-required-error="Este campo es obligatorio."
					required>
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					<div class="help-block with-errors"></div>
				</div>
				<div class="form-group has-feedback">
					<label for="password">Contraseña</label>
					<input class="form-control" placeholder="Contraseña" name="password" type="password" 
							data-required-error="Este campo es obligatorio."
					required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					<div class="help-block with-errors"></div>
				</div>

			</fieldset>
		
		
		
	  </div>

	  <div class="box-footer">
			<button class="btn btn-lg btn-success btn-block" type="submit" id="btn_iniciarSesion">Iniciar sesión</button>
	  </div>
		</form>
	</div>
</div>


</body>

<?php include('includes/footer_login.php'); ?>

<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/login.js"></script>

</html>
