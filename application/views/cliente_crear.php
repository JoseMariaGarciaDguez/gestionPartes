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
        Crear cliente
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
      <div class="row">
	  <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formCrearCliente">
		<section class="col-lg-4">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información del cliente</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>	
			    	  	<div class="form-group has-feedback">
							<label for="email">Correo electrónico *</label>
			    		    <input class="form-control" placeholder="Correo electrónico" maxlength="64" name="email" type="email" 
									data-error="Correo electrónico inválido."
									data-required-error="Este campo es obligatorio."
							required>
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
			    		</div>	
						<div class="form-group has-feedback">
							<label for="nombre">Nombre *</label>
							<input class="form-control" placeholder="Nombre" maxlength="32" name="nombre" type="text" 
									data-required-error="Este campo es obligatorio."
							required>
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>	
						<div class="form-group has-feedback">
							<label for="nombreJuridico">Nombre Jurídico</label>
							<input class="form-control" placeholder="Nombre Jurídico" maxlength="32" name="nombreJuridico" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="apellidos">Apellidos</label>
							<input class="form-control" placeholder="Apellidos" maxlength="64" name="apellidos" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="nif">NIF/CIF</label>
							<input class="form-control" placeholder="12345678A"  minlength="9" maxlength="9" name="nif" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
			    		<div class="form-group">
							<section class="col-lg-4 noPad">
								<img width="128px" src="<?php echo base_url(); ?>assets/images/cliente/default.png" data-name="default.png" id="imgAvatar"/>
							</section>
							<section class="col-lg-8 noPad leftPad">
								<label for="avatar">Avatar</label>
								<input class="form-control" id="inputAvatar" name="avatar" type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg">
							</section>
			    		</div>
						
						
			    	</fieldset>
				
			  </div>

			</div>
		
		</section>
		<section class="col-lg-8">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Dirección del cliente</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>
						<div>
							<div class="col-sm-9 noPad">
								<div class="form-group has-feedback">
									<label for="direccion">Dirección</label>
									<input class="form-control" placeholder="Dirección" maxlength="128" name="direccion" type="text">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-sm-3 noPad">
								<div class="form-group has-feedback leftPad">
									<label for="codigoPostal">Codigo postal</label>
									<input class="form-control" placeholder="112233" maxlength="6" name="codigoPostal" pattern="^[0-9]{1,}$" type="text" 
										data-pattern-error="Debes escribir un número.">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						
						<div>
							<div class="col-sm-4 noPad">
								<div class="form-group has-feedback">
									<label for="localidad">Localidad</label>
									<input class="form-control" placeholder="Localidad" maxlength="32" name="localidad" type="text">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-sm-4 noPad">
								<div class="form-group has-feedback leftPad">
									<label for="provincia">Provincia</label>
									<input class="form-control" placeholder="Provincia" maxlength="32" name="provincia" type="text">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-sm-4 noPad">
								<div class="form-group has-feedback leftPad">
									<label for="pais">País</label>
									<input class="form-control" placeholder="País" maxlength="32" name="pais" type="text">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
						
						
			    	</fieldset>
				
			  </div>

			</div>
		
		</section>
		
		<section class="col-lg-8">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información de contacto</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>
						
							<div class="col-md-9 noPad">
								<div class="form-group has-feedback">
									<label for="web">Página Web</label>
									<input class="form-control" placeholder="http://www.miempresa.com" maxlength="128" name="web" type="text">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-3 noPad">
								<div class="form-group has-feedback leftPad">
									<label for="fax">Fax</label>
									<input class="form-control" placeholder="123456789" minlength="9"   maxlength="9" name="fax" type="text">
									<span class="glyphicon form-control-feedback"></span>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						
						
						
							<div class="col-md-12 noPad">
								<div class="form-group">
									<label for="telefonos">Teléfonos</label>
									<i class="fa fa-plus leftPad pointer agregarTelefono"></i>
									<div id="listaTelefonos">
									</div>
								</div>
							</div>
						
						
						
			    	</fieldset>
				
			  </div>

			</div>
		
		</section>
		
        <section class="col-lg-12">
		
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Otros datos</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>
						<div class="form-group has-feedback">
							<label for="IBAN">IBAN</label>
							<input class="form-control" placeholder="ES00123412341234" maxlength="128" name="IBAN" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>	
						
						<div class="form-group has-feedback">
							<label for="fechaCreacion">Fecha de Creación *</label>
							<input class="form-control" data-date-format="dd/mm/yyyy" data-language="es" data-provide="datepicker" name="fechaCreacion" value="<?php echo date('d/m/Y'); ?>" type="datetime" 
									data-required-error="Este campo es obligatorio."
							required>
							<div class="help-block with-errors"></div>
							<span class="glyphicon glyphicon-th form-control-feedback"></span>
						</div>	

						
						
			    	</fieldset>
				
			  </div>

			  <div class="box-footer">
					<button class="btn btn-lg btn-success btn-block" type="submit" id="btn_editarCliente" data-cliente="<?=$cliente->id;?>">Crear cliente</button>				
			  </div>

			</div>
		
        </section>
		</form>
      </div>

    </section>
  
  </div>
  <!-- /.content-wrapper -->

  <?php include('includes/footer.php');?>
  <?php include('includes/sidebar_config.php');?>
</div>
<!-- ./wrapper -->

</body>
<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/cliente.js"></script>

</html>
