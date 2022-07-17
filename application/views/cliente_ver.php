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
        Ver Cliente
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
      <div class="row">
	  
        <section class="col-lg-4">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información del cliente</h3>
			  </div>

			  <div class="box-body">
				<div class="col-md-12" style="text-align:center;">
					<img class="img-circle" src="<?php echo base_url(); ?>/assets/images/cliente/<?php echo $cliente->avatarName; ?>" data-name="<?php echo $cliente->avatarName; ?>" id="imgAvatar"/>
				</div>
				
				<div class="col-md-12 espacioArriba">
					<p><b>Referencia</b><br> CLI<?php echo $cliente->id; ?></p>
					<p><b>Nombre</b><br> <?php echo $cliente->nombre; ?></p>
					<p><b>Correo electrónico</b><br> <?php echo $cliente->email; ?></p>
					<?php if($cliente->nombreJuridico != null){ ?>
						<p><b>Nombre Jurídico</b><br> <?php echo $cliente->nombreJuridico; ?></p>
					<?php } ?>
					<?php if($cliente->nif != null){ ?>
						<p><b>NIF/CIF</b><br> <?php echo $cliente->nif; ?></p>
					<?php } ?>
					<p><b>Fecha de Creación</b><br> <?php echo $cliente->fechaCreacion; ?></p>
					<?php if($cliente->IBAN != null){ ?>
						<p><b>IBAN</b><br> <?php echo $cliente->IBAN; ?></p>
					<?php } ?>
                    <a  class="btn btn-primary" href="<?=base_url()?>cliente/editar/<?=$cliente->id?>"><i class="fa fa-pencil-alt"></i>Editar</a>
                    <button class="btn btn-danger btn_abrirModalBorrarCliente" data-id="<?=$cliente->id?>" data-toggle="modal" data-target="#modalBorrarCliente"><i class="fa fa-trash"></i>Borrar</button>
				</div>
			  </div>
			</div>
		
        </section>
	  
        <section class="col-lg-4 direccionPanel">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Dirección del cliente</h3>
			  </div>

			  <div class="box-body">
				<div class="col-md-12 direccion">
					<?php if($cliente->direccion != null){ ?>
						<p><b>Calle</b><br> <?php echo $cliente->direccion; ?></p>
					<?php } ?>
					<?php if($cliente->codigoPostal != null){ ?>
						<p><b>Código postal</b><br> <?php echo $cliente->codigoPostal; ?></p>
					<?php } ?>
					<?php if($cliente->localidad != null){ ?>
						<p><b>Localidad</b><br> <?php echo $cliente->localidad; ?></p>
					<?php } ?>
					<?php if($cliente->provincia != null){ ?>
						<p><b>Provincia</b><br> <?php echo $cliente->provincia; ?></p>
					<?php } ?>
					<?php if($cliente->pais != null){ ?>
						<p><b>País</b><br> <?php echo $cliente->pais; ?></p>
					<?php } ?>
				</div>
			  </div>
			</div>
		
        </section>
	  
        <section class="col-lg-4 contactoPanel">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información de contacto</h3>
			  </div>

			  <div class="box-body">
				<div class="col-md-12 contacto">
					<?php if($cliente->web != null){ ?>
						<p><b>Página web</b><br> <?php echo $cliente->web; ?></p>
					<?php } ?>
					<?php if($cliente->fax != null){ ?>
						<p><b>Fax</b><br> <?php echo $cliente->fax; ?></p>
					<?php } ?>
					<?php if(!empty($cliente->telefonos) && $cliente->telefonos[0] != null){ ?>
					<p><b>Teléfonos</b><br>
						<ul id="listaTelefonos">
							<?php for($x = 0; $x < count($cliente->telefonos[0]); $x++){ ?>
								<li><?=$cliente->telefonos[0][$x];?> (<?=$cliente->telefonos[1][$x];?>)</li>
							<?php } ?>
						</ul>
					</p>
					<?php } ?>
				</div>
			  </div>
			</div>
		
        </section>
		
      </div>
    </section>
  
  </div>
  <!-- /.content-wrapper -->

<?php include('modals/borrarCliente.php');?>
<?php include('includes/footer.php');?>
<?php include('includes/sidebar_config.php');?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/validator.js"></script>
<script src="<?=base_url()?>assets/js/cliente.js"></script>
</html>
