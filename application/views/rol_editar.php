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
        Editar rol
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
	  <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formEditar">
		
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información del rol</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>		
					
						<div class="form-group has-feedback">
							<label for="nombre">Nombre *</label>
							<input class="form-control" placeholder="Nombre" maxlength="250" value="<?php echo $rol->nombre; ?>" name="nombre" type="text" required
									data-required-error="Este campo es obligatorio.">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						
			    	</fieldset>
				
			  </div>

			</div>
		
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Permisos</h3>
			  </div>
			
			  <div class="box-body">
                  <div class="row">
					<div class="col-lg-3">
						<h4 class="box-title"><i class="fa fa-user "></i> Permisos de Clientes</h4>
						<?php echo obtenerPermisos('cliente',$rol); ?>
					</div>	
					
					<div class="col-lg-3">
						<h4 class="box-title"><i class="fa fa-user-tie "></i> Permisos de Empleados</h4>
						<?php echo obtenerPermisos('empleado',$rol); ?>
					</div>
					
					<div class="col-lg-3">
						<h4 class="box-title"><i class="fa fa-file-invoice-dollar "></i> Permisos de Albaranes</h4>
						<?php echo obtenerPermisos('albaran',$rol); ?>
					</div>
					
					<div class="col-lg-3">
						<h4 class="box-title"><i class="fa fa-file-contract "></i> Permisos de Obras</h4>
						<?php echo obtenerPermisos('obra',$rol); ?>
					</div>
					</div>
      <div class="row">
					<div class="col-lg-3">
						<h4 class="box-title"><i class="fa fa-truck "></i> Permisos de Vehiculos</h4>
						<?php echo obtenerPermisos('vehiculo',$rol); ?>
					</div>
					
					<div class="col-lg-3">
						<h4 class="box-title"><i class="fa fa-calendar "></i> Permisos de Partes</h4>
						<?php echo obtenerPermisos('parte',$rol); ?>
					</div>

                  <div class="col-lg-3">
                      <h4 class="box-title"><i class="fa fa-receipt "></i> Permisos de Informes</h4>
                      <?php echo obtenerPermisos('informes',$rol); ?>
                  </div>

                  <div class="col-lg-3">
                      <h4 class="box-title"><i class="fa fa-hand-paper"></i> Permisos de Asignador</h4>
                      <?php echo obtenerPermisos('asignador',$rol); ?>
                  </div>
                  </div>
          <div class="row">
                  <div class="col-lg-3">
                      <h4 class="box-title"><i class="fa fa-user-circle "></i> Permisos de Roles</h4>
                      <?php echo obtenerPermisos('rol',$rol); ?>
                  </div>
                  </div>

				
			  </div>
			  <div class="box-footer">
				  <button class="btn btn-lg btn-success btn-block" data-id="<?php echo $rol->id;?>" type="submit" id="btn_editarRol"> Editar rol</button>
				</div>
			  

			</div>
		

	</form>

    </section>
  
  </div>
  <!-- /.content-wrapper -->

  <?php include('includes/footer.php');?>
  <?php include('includes/sidebar_config.php');?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/rol.js"></script>

</html>
