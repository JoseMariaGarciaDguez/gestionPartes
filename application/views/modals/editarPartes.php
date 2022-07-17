<?php ?>

<div class="modal modal-info fade" id="modalEditarPartes">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span></button>
		<h4 class="modal-title">¡Editar partes!</h4>
	  </div>
	  <div class="modal-body">

		<p>¿Estas seguro de que quieres editar los partes seleccionados?</p>
	  
	  
		<form accept-charset="UTF-8" role="form" id="formEditarPartes">
			<fieldset>	
				<div class="form-group ">
					<label for="estado">Estado</label>
					<select name="estado" class="form-control  has-feedback" data-required-error="Este campo es obligatorio." required>
						<option <?php if($parte->estado == 'P') echo "selected"; ?>  value="P">Pendiente</option>
						<option <?php if($parte->estado == 'C') echo "selected"; ?> value="C">Cerrado</option>
					</select>
				</div>	
				<div class="form-group ">
					<label for="tipo">Tipo</label>
					<select name="tipo" class="form-control  has-feedback" data-required-error="Este campo es obligatorio." required>
						<option selected  value="null">No cambiar</option>
						<option value="T">Trabajo</option>
						<option value="A">Avería</option>
					</select>
				</div>
			</fieldset>
		</form>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="btn_editarPartes">Si</button>
		<button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>