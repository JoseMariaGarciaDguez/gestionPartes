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
        Ver todos los partes
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
      <div class="row">
	  
        <section class="col-lg-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_P" data-toggle="tab">Partes pendientes</a></li>
				  <li><a href="#tab_C" data-toggle="tab">Partes cerrados</a></li>
				  
				</ul>
				
				
				<div class="tab-content">
				  <div class="tab-pane active" id="tab_P">
					<div class="col-sm-3">
						<a type="button" href="<?=base_url()?>parte/crear" class="btn btn-success" ><i class="fa fa-plus"></i></a>
						<button type="button" class="btn btn-info seleccionarTodos"><i class="fa fa-check-double"></i></button> 
						<div class="btn-group accionSeleccion" style="display:none;">
                            <?php if($empleado->rol->tieneRol($this->uri->segment(1)."_borrar_todos",$empleado)){ ?>
                                <button type="button" data-target="#modalBorrarPartes" data-tipo="P" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTablaPP"><i class="fa fa-trash"></i></button>
                            <?php } ?>
                            <?php if($empleado->rol->tieneRol($this->uri->segment(1)."_editar_todos",$empleado)){ ?>
                                <button type="button" data-target="#modalEditarPartes" data-toggle="modal" class="btn btn-info" id="editarSeleccionTablaPP"><i class="fa fa-edit"></i></button>
                            <?php } ?>
						</div>
					</div> 
					
					<div class="col-sm-9">
						<div class="refreshContadorTipos espacioAbajo"><?php echo $parte->contadorDeTiposHTML('P',$empleado); ?></div>
					</div>
					
					<table id="partesTablaP" class="table table-bordered table-striped seleccionTabla">
						<thead>
							<tr>
								<td></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				  </div>
				  
				  <div class="tab-pane" id="tab_C">  
					<div class="col-sm-3">
                        <?php if($empleado->rol->tieneRol($this->uri->segment(1)."_crear",$empleado)){ ?>
						    <a type="button" href="<?=base_url()?>parte/crear" class="btn btn-success" ><i class="fa fa-plus"></i></a>
                        <?php } ?>
						<button type="button" class="btn btn-info seleccionarTodos" ><i class="fa fa-check-double"></i></button> 
						<div class="btn-group accionSeleccion" style="display:none;">
                            <?php if($empleado->rol->tieneRol($this->uri->segment(1)."_borrar_todos",$empleado)){ ?>
                                <button type="button" data-target="#modalBorrarPartes" data-tipo="C" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTablaPC"><i class="fa fa-trash"></i></button>
                            <?php } ?>
                            <?php if($empleado->rol->tieneRol($this->uri->segment(1)."_editar_todos",$empleado)){ ?>
                                <button type="button" data-target="#modalEditarPartes" data-toggle="modal" class="btn btn-info" id="editarSeleccionTablaPC"><i class="fa fa-edit"></i></button>
                            <?php } ?>
						</div>
					</div> 
					
					<div class="col-sm-9">
						<div class="refreshContadorTipos espacioAbajo"><?php echo $parte->contadorDeTiposHTML('C',$empleado); ?></div>
					</div>
					
					<table id="partesTablaC" class="table table-bordered table-striped seleccionTabla">
						<thead>
							<tr>
								<td></td>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				  </div>
				</div>
			  </div>
		
        </section>
		
      </div>

    </section>
  
  </div>
  <!-- /.content-wrapper -->

  <?php include('includes/footer.php');?>
  <?php include('includes/sidebar_config.php');?>
  <?php include('modals/borrarParte.php');?>
  <?php include('modals/borrarPartes.php');?>
  <?php include('modals/editarPartes.php');?>
</div>
<!-- ./wrapper -->

</body>

<script src="<?=base_url()?>assets/js/parte.js"></script>

</html>
