<?php ?>

<div class="modal modal-info fade" id="modalEditarEmpleados">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">¡Editar empleados!</h4>
            </div>
            <div class="modal-body">

                <p>¿Estas seguro de que quieres editar los empleados seleccionados?</p>


                <form accept-charset="UTF-8" role="form" id="formEditarEmpleados">
                    <fieldset>
                        <div class="form-group ">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-control  has-feedback"
                                    data-required-error="Este campo es obligatorio." required>
                                <option <?php if ($empleado->estado == 'A') echo "selected"; ?> value="A">Activo
                                </option>
                                <option <?php if ($empleado->estado == 'B') echo "selected"; ?> value="B">Baja</option>
                            </select>
                        </div>
                    </fieldset>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal" id="btn_editarEmpleados">
                    Si
                </button>
                <button type="button" class="btn btn-outline" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>