<!DOCTYPE html>
<html>
<?php include 'includes/head.php';?>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

	<?php include 'includes/header.php';?>

  <!-- Left side column. contains the logo and sidebar -->
	<?php include 'includes/sidebar.php';?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Ver Obras
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">

      <!-- Main row -->
      <div class="row">

        <section class="col-lg-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información de la obra</h3>
			  </div>

			  <div class="box-body">
				<div class="col-md-12">
					<?php $cliente = new Cliente_model($obra->clienteID);?>
                    <?php if ($obra->avatar != null) {?>
						<div class="col-md-12" style="text-align:left;">
							<img class="img-circle" src="<?php echo base_url(); ?>/assets/images/obras/<?php echo $obra->avatar; ?>" data-name="<?php echo $obra->avatar; ?>" id="imgAvatar"/>
						</div>
					<?php }?>
					<p><b>Referencia</b><br> OBR<?php echo $obra->id; ?></p>
					<p><b>Nombre</b><br> <?php echo $obra->nombre; ?></p>
					<p><b>Estado</b><br> <span class="label label-<?php echo $this->obra->traducirEstadoColor($obra->estado); ?>"><?php echo $this->obra->traducirEstado($obra->estado); ?></span></p>
					<?php if (!empty($cliente->nombre)) {?><p><b>Cliente asignado</b><br> <a href="'.base_url().'cliente/ver/<?php echo $cliente->id; ?>"><?php echo $cliente->nombre . ' (x' . $cliente->contarObrasDeCliente() . ')'; ?></p><?php }?>
				</div>
                <a  class="btn btn-primary" href="<?=base_url()?>obra/editar/<?=$obra->id?>"><i class="fa fa-pencil-alt"></i>Editar</a>
                <button class="btn btn-danger btn_abrirModalBorrarObra" data-id="<?=$obra->id?>" data-toggle="modal" data-target="#modalBorrarObra"><i class="fa fa-trash"></i>Borrar</button>
			  </div>
			</div>

        </section>

        <section class="col-lg-6">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Descripción de la obra</h3>
			  </div>

			  <div class="box-body">
				<div class="col-md-12">
					<p><?php echo $obra->descripcion; ?></p>
				</div>
			  </div>
			</div>

        </section>

      </div>

    </section>

  </div>
  <!-- /.content-wrapper -->
  <?php include 'modals/borrarObra.php';?>
  <?php include 'includes/footer.php';?>
  <?php include 'includes/sidebar_config.php';?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/obra.js"></script>
</html>
