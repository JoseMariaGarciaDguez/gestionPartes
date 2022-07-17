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
        Ver Vehiculo
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
				<h3 class="box-title">Información del vehiculo</h3>
			  </div>

			  <div class="box-body">
				<div class="col-md-12 espacioArriba">
                <?php if($vehiculo->avatar != null){ ?>
					<div class="col-md-12" style="text-align:left;">
						<img class="img-circle" src="<?php echo base_url(); ?>/assets/images/vehiculo/<?php echo $vehiculo->avatar; ?>" data-name="<?php echo $vehiculo->avatar; ?>" id="imgAvatar"/>
					</div>
					<?php } ?>
					<p><b>Matrícula</b><br> <?php echo $vehiculo->matricula; ?></p>
					<?php if($vehiculo->nombre != null){ ?>
						<p><b>Nombre</b><br> <?php echo $vehiculo->nombre; ?></p>
					<?php } ?>
					<?php if($vehiculo->plazas != 0){ ?>
						<p><b>Plazas</b><br> <?php echo $vehiculo->plazas; ?></p>
					<?php } ?>
					<?php if($vehiculo->modelo != null){ ?>
						<p><b>Modelo</b><br> <?php echo $vehiculo->modelo; ?></p>
					<?php } ?>
					<p><b>Estado</b><br> <span class="label label-<?php echo $vehiculo->traducirEstadoColor($vehiculo->estado);?>"><?php echo $vehiculo->traducirEstado($vehiculo->estado);?></span></p>
                    <p><b>Referencia</b><br>VEH<?php echo $vehiculo->id; ?></p>
					<p><b>Número de plazas:</b><br> <?php echo $vehiculo->plazas; ?></p>
					<?php if($vehiculo->observaciones != null){ ?>
						<p><b>Observaciones</b><br> <?php echo $vehiculo->observaciones; ?></p>
					<?php } ?>
                    <a  class="btn btn-primary" href="<?=base_url()?>vehiculo/editar/<?=$vehiculo->id?>"><i class="fa fa-pencil-alt"></i>Editar</a>
                    <button class="btn btn-danger btn_abrirModalBorrarVehiculo" data-id="<?=$vehiculo->id?>" data-toggle="modal" data-target="#modalBorrarVehiculo"><i class="fa fa-trash"></i>Borrar</button>

				</div>
			  </div>
			</div>
		
        </section>
	  
		
      </div>
    </section>
  
  </div>
  <!-- /.content-wrapper -->
  <?php include('modals/borrarVehiculo.php');?>
  <?php include('includes/footer.php');?>
  <?php include('includes/sidebar_config.php');?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/vehiculo.js"></script>
</html>
