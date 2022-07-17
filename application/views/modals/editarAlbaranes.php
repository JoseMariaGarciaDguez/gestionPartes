<?php ?>

<div class="modal modal-info fade" id="modalEditarAlbaranes">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span></button>
		<h4 class="modal-title">¡Editar albaranes!</h4>
	  </div>
	  <div class="modal-body">

		<p>¿Estas seguro de que quieres editar las albaranes seleccionados?</p>
	  
	  
		<form accept-charset="UTF-8" role="form" id="formEditarAlbaranes">
			<fieldset>	
				<div class="form-group ">
					<label for="estado">Estado</label>
					<select name="estado" class="form-control  has-feedback" data-required-error="Este campo es obligatorio." required>
						<option <?php if($albaran->estado == 'PA') echo "selected"; ?> value="PA">Pagado</option>
						<option <?php if($albaran->estado == 'PE') echo "selected"; ?>  value="PE">Pendiente</option>
						<option <?php if($albaran->estado == 'CA') echo "selected"; ?> value="CA">Cancelado</option>
					</select>
				</div>
			</fieldset>
		</form>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="btn_editarAlbaranes">Si</button>
		<button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>