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
        Editar Albarán
      </h1>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	  
      <!-- Main row -->
	  <form data-toggle="validator" accept-charset="UTF-8" role="form" id="formModificar">
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
									<option <?php if($cliente->id == $row['id'] || $albaran->clienteID == $row['id']) echo "selected"; ?> value="<?=$row['id'];?>"><?=$row['nombre'];?></option>
								<?php } ?>
							</select>

							<div class="help-block with-errors"></div>
						</div>
					
						<div class="form-group has-feedback">
							<label for="nombre">Nombre</label>
							<input class="form-control" value="<?php echo $albaran->nombre; ?>" placeholder="Nombre" maxlength="32" name="nombre" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>	
						<div class="form-group has-feedback">
							<label for="email">Correo electrónico</label>
							<input class="form-control" value="<?php echo $albaran->email; ?>"  placeholder="Correo electrónico" maxlength="64" name="email" type="email">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>	
						<div class="form-group has-feedback">
							<label for="nombreJuridico">Nombre Jurídico</label>
							<input class="form-control"  value="<?php echo $albaran->nombreJuridico; ?>" placeholder="Nombre Jurídico" maxlength="32" name="nombreJuridico" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="apellidos">Apellidos</label>
							<input class="form-control" placeholder="Apellidos"  value="<?php echo $albaran->apellidos; ?>"maxlength="64" name="apellidos" type="text">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
						<div class="form-group has-feedback">
							<label for="nif">NIF/CIF</label>
							<input class="form-control" placeholder="12345678A"  value="<?php echo $albaran->nif; ?>" maxlength="9" name="nif" type="text">
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
				<h3 class="box-title">Información del Albarán</h3>
			  </div>
			
			  <div class="box-body">

                    <fieldset>			
						
					
						<div class="form-group has-feedback">
							<label for="nombreAlbaran">Nombre del Albarán * </label>
							<input class="form-control" placeholder="Nombre del Albarán" value="<?php echo $albaran->nombreAlbaran;?>" required maxlength="32" name="nombreAlbaran" type="text"
							data-required-error="Este campo es obligatorio.">
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>
					
						<div class="form-group has-feedback">
							<label for="enviado">Enviada</label> <i style="padding-left: 15px;" id="enviado" class="checkboxTabla fa fa-square <?php if($albaran->enviado==1) echo "selected";?>"></i>
						</div>	
						
						<div class="form-group has-feedback">
							<label for="estado">Estado *</label>
							<select id="estado" name="estado" class="form-control selectpicker" data-live-search="true"  
							data-required-error="Este campo es obligatorio." required title="Selecciona un estado...">
									<option  <?php if($albaran->estado=="PE") echo "selected"; ?> value="PE">Pendiente</option>
									<option <?php if($albaran->estado=="PA") echo "selected"; ?> value="PA">Pagada</option>
									<option <?php if($albaran->estado=="CA") echo "selected"; ?> value="CA">Cancelada</option>
							</select>

							<div class="help-block with-errors"></div>
						</div>
						
						<!--<div class="form-group has-feedback">
							<label for="ref">Referencia *</label>
							<input class="form-control" maxlength="32" name="ref" value="<?php echo $albaran->ref; ?>" type="text" 
									data-required-error="Este campo es obligatorio."
							required>
							<span class="glyphicon form-control-feedback"></span>
							<div class="help-block with-errors"></div>
						</div>-->
						<div class="form-group has-feedback">
							<label for="fechaCreacion">Fecha de Creación *</label>
							<input class="form-control" data-date-format="dd/mm/yyyy" data-language="es" data-provide="datepicker" name="fechaCreacion" value="<?php echo $albaran->fechaCreacion; ?>" type="datetime" 
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
								<select id="tipoAlbaran" class="form-control selectpicker p010" data-live-search="true"  data-required-error="Este campo es obligatorio." required title="Selecciona un elemento...">
									<?php	$query = $obra->obtenerTabla('id,nombre')->result_array(); ?>
										<option data-type="null" selected value="null">Texto</option>
									<?php	foreach ($query as $row){ ?>
										<option data-type="O" value="<?=$row['id'];?>">Obra: <?=$row['nombre'];?></option>
									<?php } ?>
									
									<?php	$query = $parte->obtenerTabla('id')->result_array(); ?>
									<?php	foreach ($query as $row){ ?>
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
											<?php $total= 0.00; if($albaran->articulos != null){ foreach(json_decode($albaran->articulos) as $articulo){ ?>
											<?php
													$articulo_readonly = '';
													$tipo = $articulo[0][0];
													if($tipo == "O"){
														$articulo_readonly = 'readonly';
													}
													elseif($tipo == "P"){
														$articulo_readonly = 'readonly';
													}
													
													$total = floatval($total)+floatval($articulo[2]*$articulo[1]);
														
													
											?>
													<tr>
														  <td class="articuloTablaPrimero">
															<div class="form-group has-feedback">
																<input class="form-control" maxlength="250" <?php echo $articulo_readonly;?> data-type="<?php echo $articulo[0][0]; ?>" value="<?php echo $articulo[0][2];?> " data-id="<?php echo $articulo[0][1];?>" name="articulos[]" type="text" required
																	data-required-error="Este campo es obligatorio.">
																<div class="help-block "></div>
															</div>
														  </td>
														  <td>
															<div class="form-group has-feedback">
															<input class="form-control" min="1" name="cantidades[]" value="<?php echo $articulo[1];?>" type="number" required
																data-required-error="Este campo es obligatorio.">
															<div class="help-block "></div>
															</div>
														  </td>
														  <td>
															<div class="form-group has-feedback">
																<input type="number"  name="precios[]" value="<?php echo $articulo[2];?>" min="0" step="0.01" lang="es" 
																	data-number-to-fixed="2" 
																	data-number-stepfactor="100" class="form-control currency" required
																	data-required-error="Este campo es obligatorio." />
													
																<div class="help-block "></div>
															</div>
														  </td>
														  <td>
															<i class="fa fa-minus pull-right pointer borrarArticulo"></i>
														  </td>
													</tr>
											
											<?php }} ?>
										</tbody>
									</table>
									<span class="albaranTotal col-md-12"><b>Total: </b><span id="albaranTotal"><span class="albaranTotalFix"><?php echo $total; ?></span>€</span></span>
								</div>
							</div>
						</div>
						
			    	</fieldset>

					  <div class="box-footer">
							<input type="hidden" name="empleadoID" value="<?php echo $empleado->id;?>"/>
							<button class="btn btn-lg btn-success btn-block" type="submit" data-id="<?php echo $albaran->id; ?>" id="btn_editarAlbaran">Editar Albarán</button>				
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
