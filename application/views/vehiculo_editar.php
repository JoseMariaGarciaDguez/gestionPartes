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
        Modificar vehiculo
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
	  <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formModificar">
		
		<section class="col-lg-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información del vehiculo</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>		
					
						<div class="form-group has-feedback">
							<label for="nombre">Nombre</label>
							<input class="form-control" placeholder="Nombre" maxlength="32" value="<?php echo $vehiculo->nombre;?>" name="nombre" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="matricula">Matricula *</label>
							<input class="form-control" maxlength="32" value="<?php echo $vehiculo->matricula;?>" name="matricula" type="text" required 
									data-required-error="Este campo es obligatorio.">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="plazas">Plazas</label>
							<input class="form-control" value="<?php echo $vehiculo->plazas;?>" name="plazas" type="numeric">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="modelo">Modelo</label>
							<input class="form-control"  maxlength="32" value="<?php echo $vehiculo->modelo;?>" name="modelo" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
			    		<div class="form-group">
							<section class="col-lg-4">
								<img src="<?php echo base_url(); ?>/assets/images/vehiculo/<?php echo $vehiculo->avatar; ?>" data-name="<?php echo $vehiculo->avatar; ?>" id="imgAvatar"/>
							</section>
							<section class="col-lg-8">
								<label for="avatar">Avatar</label>
								<input class="form-control" data-width="12000" data-height="12000" id="inputAvatar" name="avatar" type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg">
							</section>
			    		</div>
						
			    	</fieldset>
				
			  </div>

			</div>
		
		</section>
		
		<section class="col-lg-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Otra información</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>	
						
						<div class="form-group has-feedback">
							<label for="estado">Estado *</label>
							<select id="estado" name="estado" class="form-control selectpicker" data-live-search="true"  
							data-required-error="Este campo es obligatorio." required title="Selecciona un estado...">
									<option <?php if($vehiculo->estado=="LI") echo "selected"; ?> value="LI">Libre</option>
									<option  <?php if($vehiculo->estado=="OC") echo "selected"; ?> value="OC">Ocupado</option>
									<option <?php if($vehiculo->estado=="RE") echo "selected"; ?> value="RE">Reparación</option>
							</select>

							<div class="help-block with-errors"></div>
						</div>		
						
						<div class="form-group">
							<label for="observaciones">Observaciones</label>
							<textarea class="form-control" rows="5" maxlength="60000" name="observaciones"> <?php echo $vehiculo->observaciones;?> </textarea>
							<div class="help-block with-errors"></div>
						</div>
						
			    	</fieldset>
				
			  </div>

			  <div class="box-footer">
					<button class="btn btn-lg btn-success btn-block" type="submit" data-id="<?php echo $vehiculo->id;?>" id="btn_editarVehiculo">Modificar vehiculo</button>				
			  </div>

			</div>
		
		</section>

	</form>

    </section>
  
  </div>
  <!-- /.content-wrapper -->

  <?php include('includes/footer.php');?>
  <?php include('includes/sidebar_config.php');?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/vehiculo.js"></script>

</html>
