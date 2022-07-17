var clientesTabla;

$(document).ready(function() {
	$('#formCrearCliente,#formEditarCliente,#formEditarClientes').validator('validate');
	setClientesTabla();
	comprobarColumnasVerCliente();
});

function comprobarColumnasVerCliente(){
	
	if($('.direccion').children().length ==0){
		$('.direccionPanel').hide();
	}
	if($('.contacto').children().length ==0){
		$('.contactoPanel').hide();
	}

}

function setClientesTabla(){
	if(clientesTabla!=undefined) clientesTabla.destroy();
	clientesTabla = $('#clientesTabla').DataTable({

        "ajax": {
			data: {tablaSoloCreados:$('#tablaSoloCreados').text()},
			dataType: "json",
            url : BASE_URL+"/cliente/obtenerClientesTabla",
            type : 'POST'
        },
		"stateSave": true,
		"pageLength": 50,
		"order": [[ 0, "desc" ]],
		language: {
			"url": BASE_URL+"/assets/components/datatables/Spanish.json"
		}
    });
}

//==============================
//Borrado
//==============================

//Cuando se hace click en "Si" del modal de borrar
$(document).on('click','#btn_borrarCliente',function(e){
	var id = $(this).attr('data-id');
	borrarCliente(id);
	
	e.preventDefault();
});

//Cuando se hace click sobre el elemento de la tabla
$(document).on('click','.btn_abrirModalBorrarCliente',function(e){
	$('#btn_borrarCliente').attr('data-id',$(this).attr('data-id'));
});

//Cuando se hace click sobre el elmento de borrar todos
$('#btn_borrarClientes').click( function () {
	clientesTabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			borrarCliente(selected.attr('data-id'));
		}
	});
	
	comprobarBtnAccionesMultiples();
} );

//Ejecuta un borrado por id
function borrarCliente(id){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/cliente/BorrarClienteModalSi',
		data: {id:id},
		dataType: "json",
		success: function(data){
			mostrarMensaje(data.titulo,data.mensaje,'success');
			clientesTabla.row($('i[data-id="'+id+'"]').closest('tr')).remove().draw();
			comprobarBtnAccionesMultiples();

			comprobarBorradoURL();
		}
	});
}

//==============================
//Creacion del cliente
//==============================

//Cuando se hace click sobre el elemento de agregar un telefono
$(document).on('click','.agregarTelefono',function(e){
	var html = `
		<div class="grupoTelefono">
			<div class="form-group has-feedback inline col-md-6 noPad">
				<input class="form-control" placeholder="Nombre" maxlength="32" name="telefonosNombre[]" type="text" 
					required 
					data-required-error="Este campo es obligatorio.">
				<span class="glyphicon form-control-feedback"></span>
				<div class="help-block with-errors"></div>
			</div>
			<div class="form-group has-feedback inline col-md-5 leftMargin noPad">
				<input class="form-control" placeholder="123456789"  minlength="9" maxlength="9" name="telefonos[]" type="text" pattern="^[0-9]{1,}$" type="text" 
					required 
					data-required-error="Este campo es obligatorio." 
					data-pattern-error="Debes escribir un número.">
				<span class="glyphicon form-control-feedback"></span>
				<div class="help-block with-errors"></div>
			</div>
			<div class="inline col-md-1 borrarTelefonoInline">
				<i class="fa fa-minus pull-right pointer borrarTelefono"></i>
			</div>
		</div>
	`;
	
	$('#listaTelefonos').append(html);
	$('#formCrearCliente').validator('validate');
	$('#formCrearCliente').validator('update');
	$('#formCrearCliente').validator('validate');
	$('#formCrearCliente').validator('update');
	
	$('#formModificarCliente').validator('validate');
	$('#formModificarCliente').validator('update');
	$('#formModificarCliente').validator('validate');
	$('#formModificarCliente').validator('update');
});

//Cuando se hace click sobre el elemento de borrar un telefono
$(document).on('click','.borrarTelefono',function(e){
	$(this).closest('.grupoTelefono').remove();
	$('#formCrearCliente').validator('update');
	$('#formModificarCliente').validator('update');
});

//Cuando se hace click en el boton de crear cliente
$('#formCrearCliente').validator().on('submit',document,function(e){
	if (!e.isDefaultPrevented()) {
		$.ajax({
			type: "POST",
			url: BASE_URL+'/cliente/crearCliente',
			data: $("#formCrearCliente").serialize()+'&avatarName='+$("#imgAvatar").attr('data-name')/*+'&firma='+JSON.stringify(signaturePad.toData())*/,
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/cliente";
				}
			}
		});
		e.preventDefault();
	}
});

//==============================
//Modificacion del cliente
//==============================

//Cuando se hace click en el boton de modificar cliente
$('#formModificarCliente').validator().on('submit',document,function(e){
	if (!e.isDefaultPrevented()) {
		$.ajax({
			type: "POST",
			url: BASE_URL+'/cliente/editarCliente',
			data: $("#formModificarCliente").serialize()+'&id='+$('#btn_modificarCliente').attr('data-id')+'&avatarName='+$("#imgAvatar").attr('data-name'),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/cliente";
				}
			}
		});
		e.preventDefault();
	}
});


