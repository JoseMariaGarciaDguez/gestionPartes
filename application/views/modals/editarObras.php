<?php ?>

<div class="modal modal-info fade" id="modalEditarObras">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">×</span></button>
		<h4 class="modal-title">¡Editar obras!</h4>
	  </div>
	  <div class="modal-body">

		<p>¿Estas seguro de que quieres editar las obras seleccionados?</p>
	  
	  
		<form accept-charset="UTF-8" role="form" id="formEditarObras">
			<fieldset>	
				<div class="form-group ">
					<label for="nombre">Estado</label>
					<select name="estado" class="form-control  has-feedback" data-required-error="Este campo es obligatorio." required>
						<option <?php if($obra->estado == 'A') echo "selected"; ?> value="A">Activo</option>
						<option <?php if($obra->estado == 'P') echo "selected"; ?>  value="P">Pendiente</option>
						<option <?php if($obra->estado == 'C') echo "selected"; ?> value="C">Cancelado</option>
						<option <?php if($obra->estado == 'F') echo "selected"; ?> value="F">Finalizado</option>
					</select>
				</div>
			</fieldset>
		</form>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="btn_editarObras">Si</button>
		<button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>