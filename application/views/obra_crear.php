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
        Crear obra
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
				<h3 class="box-title">Crear obra</h3>
			  </div>

			  <div class="box-body">

				<form data-toggle="validator" accept-charset="UTF-8" role="form" id="formCrearObra">
                    <fieldset>	
						<div class="form-group has-feedback">
							<label for="nombre">Nombre *</label>
							<input class="form-control" placeholder="Nombre" maxlength="32" name="nombre" type="text" 
									data-required-error="Este campo es obligatorio."
							required>
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>	
						<div class="form-group  has-feedback">
							<label for="clienteID">Cliente</label>
							<select name="clienteID" class="form-control selectpicker" data-live-search="true"  title="Selecciona un cliente...">
								<?php	$query = $cliente->obtenerTabla('id,nombre')->result_array(); ?>
								<?php	foreach ($query as $row){ ?>
									<option <?php if($cliente->id == $row['id']) echo "selected"; ?> value="<?=$row['id'];?>"><?=$row['nombre'];?></option>
								<?php } ?>
							</select>
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>	
						<div class="form-group has-feedback">
							<label for="nombre">Estado *</label>
							<?php
								
							?>
							<select name="estado" class="form-control" data-required-error="Este campo es obligatorio." required>
								<option value="A">Activo</option>
								<option value="P">Pendiente</option>
								<option value="C">Cancelado</option>
								<option value="F">Finalizado</option>
							</select>

							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="descripcion">Descripcion</label>
							<textarea class="form-control" rows="5" placeholder="Descripcion" maxlength="250" name="descripcion"></textarea>
							<div class="help-block with-errors"></div>
						</div>
			    		<div class="form-group">
							<section class="col-lg-4">
								<img src="<?php echo base_url(); ?>/assets/images/obras/default.png" data-name="default.png" id="imgAvatar"/>
							</section>
							<section class="col-lg-8">
								<label for="avatar">Avatar</label>
								<input class="form-control" id="inputAvatar" data-width="12000" data-height="12000" name="avatar" type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg">
							</section>
			    		</div>
			    	</fieldset>
				</form>
				
			  </div>

			  <div class="box-footer">
					<button class="btn btn-lg btn-success btn-block" id="btn_crearObra">Crear obra</button>				
			  </div>
			</div>
		
        </section>
		
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
<script src="<?=base_url()?>assets/js/obra.js"></script>
</html>
