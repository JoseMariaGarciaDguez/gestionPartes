<?php ?>

<div class="modal modal-info fade" id="modalEditarVehiculos">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span></button>
		<h4 class="modal-title">¡Editar vehiculos!</h4>
	  </div>
	  <div class="modal-body">

		<p>¿Estas seguro de que quieres editar los vehiculos seleccionados?</p>
	  
	  
		<form accept-charset="UTF-8" role="form" id="formEditarVehiculos">
			<fieldset>	
				<div class="form-group ">
					<label for="estado">Estado</label>
					<select name="estado" class="form-control  has-feedback" data-required-error="Este campo es obligatorio." required>
						<option <?php if($vehiculo->estado == 'LI') echo "selected"; ?> value="LI">Libre</option>
						<option <?php if($vehiculo->estado == 'OC') echo "selected"; ?>  value="OC">Ocupado</option>
						<option <?php if($vehiculo->estado == 'RE') echo "selected"; ?> value="RE">Reparación</option>
					</select>
				</div>
			</fieldset>
		</form>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="btn_editarVehiculos">Si</button>
		<button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>