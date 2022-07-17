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
        Crear Albarán
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
	  <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formCrear">
      <div class="row">
		<section class="col-lg-4">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información del cliente</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>		
						
						<div class="form-group has-feedback">
							<label for="clientesID">Cliente *</label>
							<select id="clientesID" name="clientesID" class="form-control selectpicker" data-live-search="true"  data-required-error="Este campo es obligatorio." required title="Selecciona un cliente...">
								<?php	$query = $cliente->obtenerTabla('id,nombre')->result_array(); ?>
									<option selected value="null">Sin cliente</option>
									<option value="CREAR">Crear cliente</option>
								<?php	foreach ($query as $row){ ?>
									<option value="<?=$row['id'];?>"><?=$row['nombre'];?></option>
								<?php } ?>
							</select>

							<div class="help-block with-errors"></div>
						</div>
					
						<div class="form-group has-feedback">
							<label for="nombre">Nombre</label>
							<input class="form-control" placeholder="Nombre" maxlength="32" name="nombre" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="email">Correo electrónico</label>
							<input class="form-control" placeholder="Correo electrónico" maxlength="64" name="email" type="email">
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
							<input class="form-control" placeholder="12345678A" maxlength="9" name="nif" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						
			    	</fieldset>
				
			  </div>

			</div>
		
		</section>
		<section class="col-lg-8">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Información de la albaran</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>		
						
					
						<div class="form-group has-feedback">
							<label for="nombreAlbaran">Nombre del Albarán * </label>
							<input class="form-control" placeholder="Nombre del Albarán" required maxlength="250" name="nombreAlbaran" type="text"
							data-required-error="Este campo es obligatorio.">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
					
						<div class="form-group has-feedback">
							<label for="enviado">Enviada</label> <i style="padding-left: 15px;" id="enviado" class="checkboxTabla fa fa-square"></i>
						</div>
						
						<div class="form-group has-feedback">
							<label for="estado">Estado *</label>
							<select id="estado" name="estado" class="form-control selectpicker" data-live-search="true"  
							data-required-error="Este campo es obligatorio." required title="Selecciona un estado...">
								<?php	$query = $cliente->obtenerTabla('id,nombre')->result_array(); ?>
									<option selected value="PE">Pendiente</option>
									<option value="PA">Pagada</option>
									<option value="CA">Cancelada</option>
							</select>

							<div class="help-block with-errors"></div>
						</div>
						
						<!--<div class="form-group has-feedback">
							<label for="ref">Referencia *</label>
							<input class="form-control" maxlength="32" name="ref" value="REF<?php echo date("dmYHis"); ?>" type="text" 
									data-required-error="Este campo es obligatorio."
							required>
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>-->
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

			</div>
		
		</section>
		</div>
		<div class="row">
		<section class="col-lg-12">
			<div class="box box-primary">
			  <div class="box-header with-border">
				<h3 class="box-title">Artículos a incluir en Albarán</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>
						<div class="form-group">
							<div class="form-group">
								<label for="articulos[]">Agregar un parte u obra al Albarán</label>
								<select id="tipoAlbaran" class="form-control selectpicker p010" data-live-search="true"  data-required-error="Este campo es obligatorio." required title="Selecciona un cliente...">
									<?php	$query = $obra->obtenerTabla('id,nombre')->result_array(); ?>
										<option data-type="null" selected value="null">Texto</option>
									<?php	foreach ($query as $row){ ?>
										<option data-type="O" value="<?=$row['id'];?>">Obra: <?=$row['nombre'];?></option>
									<?php } ?>
									
									<?php	$query = $parte->obtenerTabla('id','C')->result_array(); ?>
									<?php	foreach ($query as $row){ ?>
									<?php	if($row['']) ?>
										<option data-type="P" value="<?=$row['id'];?>">Parte: <?=$row['id'];?></option>
									<?php } ?>
								</select>
								<i class="fa fa-plus leftPad pointer agregarArticulo"></i>
							</div>
							<div id="listaArticulos">
								<div class="grupoArticulo">
									<table class="table table-striped">
										<thead>
											<tr>
											  <th class="tabcol80">Concepto</th>
											  <th class="tabcol10">Cantidad/Horas</th>
											  <th class="tabcol10">Precio</th>
											  <th></th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
									<span class="albaranTotal col-md-12"><b>Total: </b><span id="albaranTotal"><span class="albaranTotalFix">0.00</span>€</span></span>
								</div>
							</div>
						</div>
						
			    	</fieldset>

					  <div class="box-footer">
							<input type="hidden" name="empleadoID" value="<?php echo $empleado->id;?>"/>
							<button class="btn btn-lg btn-success btn-block" type="submit" id="btn_crearAlbaran">Crear Albarán</button>				
					  </div>
						
				
			  </div>

			</div>
		
		</section>
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

<script src="<?=base_url()?>assets/js/albaran.js"></script>

</html>
