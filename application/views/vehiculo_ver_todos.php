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
        Ver todos los vehículos
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
				<h3 class="box-title">Listado de vehiculos</h3>
			  </div>

			  <div class="box-body">
				
				<div id="refreshContadorTipos" class="espacioAbajo alturaContador"><?php echo $vehiculo->contadorDeTiposHTML($empleado); ?></div>

				<table id="tabla" class="table table-bordered table-striped table-hover seleccionTabla">
					<thead>
						<tr>
						<td></td>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				
			  </div>

			  <div class="box-footer">
					
			  </div>
			</div>
		
        </section>
		
      </div>

    </section>
  
  </div>
  <!-- /.content-wrapper -->

  <?php include('includes/footer.php');?>
  <?php include('includes/sidebar_config.php');?>
  <?php include('modals/borrarVehiculo.php');?>
  <?php include('modals/borrarVehiculos.php');?>
  <?php include('modals/editarVehiculos.php');?>
</div>
<!-- ./wrapper -->

</body>


<script src="<?=base_url()?>assets/js/vehiculo.js"></script>

</html>
