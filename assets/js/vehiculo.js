var tabla;

$(document).ready(function() {
	$('#formCrear,#formModificar').validator('validate');
	$('#formCrear,#formModificar').validator('update');
	setTabla();
});

function setTabla(){
	if(tabla!=undefined) tabla.destroy();
	
	tabla = $('#tabla').DataTable({
        "ajax": {
			data: {tablaSoloCreados:$('#tablaSoloCreados').text()},
			dataType: "json",
            url : BASE_URL+"/vehiculo/obtenerTabla",
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
$(document).on('click','#btn_borrarVehiculo',function(e){
	var id = $(this).attr('data-id');
	borrarVehiculo(id);
	
	e.preventDefault();
});

//Cuando se hace click sobre el elemento de la tabla
$(document).on('click','.btn_abrirModalBorrarVehiculo',function(e){
	$('#btn_borrarVehiculo').attr('data-id',$(this).attr('data-id'));
});

//Cuando se hace click sobre el elmento de borrar todos
$('#btn_borrarVehiculos').click( function () {
	tabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			borrarVehiculo(selected.attr('data-id'));
		}
	});
	
	comprobarBtnAccionesMultiples();
} );

//Ejecuta un borrado por id
function borrarVehiculo(id){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/vehiculo/BorrarVehiculoModalSi',
		data: {id:id},
		dataType: "json",
		success: function(data){
			mostrarMensaje(data.titulo,data.mensaje,'success');
			tabla.row($('i[data-id="'+id+'"]').closest('tr')).remove().draw();
			comprobarBtnAccionesMultiples();

			comprobarBorradoURL();
			refrescarContadorTiposHTML();
		}
	});
}

//==============================
//Creacion
//==============================

function validarDinero(elm){
	elm.value.replace(/,/g, ".");
}

//Cuando se hace click en el boton de crear vehiculo
$('#formCrear').validator().on('submit',document,function(e){
	
	if (!e.isDefaultPrevented()) {
		
		$.ajax({
			type: "POST",
			url: BASE_URL+'/vehiculo/crearVehiculo',
			data: $("#formCrear").serialize()+'&avatarName='+$("#imgAvatar").attr('data-name'),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/vehiculo";
				}
			}
		});
		e.preventDefault();
	}
});

//==============================
//Modificacion
//==============================

//Cuando se hace click en el boton de modificar vehiculo
$('#formModificar').validator().on('submit',document,function(e){
	$('#formModificar').validator('update');
	if (!e.isDefaultPrevented()) {
		$.ajax({
			type: "POST",
			url: BASE_URL+'/vehiculo/editarVehiculo',
			data: $("#formModificar").serialize()+'&id='+$('#btn_editarVehiculo').attr('data-id')+'&avatarName='+$("#imgAvatar").attr('data-name'),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/vehiculo";
				}
			}
		});
		e.preventDefault();
	}
});

//Cuando se hace click en el boton de editar vehiculos (modal) (seleccion)
$(document).on('click','#btn_editarVehiculos',function(e){
	var listaIds = [];
	
	tabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			listaIds.push(selected.attr('data-id'));
		}
	});

	$.ajax({
		type: "POST",
		url: BASE_URL+'/vehiculo/EditarVehiculosFormulario',
		data: $("#formEditarVehiculos").serialize()+'&ids='+JSON.stringify(listaIds),
		dataType: "json",
		success: function(data){
			if(data.enviar == false)
				mostrarMensaje(data.titulo,data.mensaje,'danger');

			refrescarContadorTiposHTML();
		}
	});
	
	var lastSearch = tabla.search();
	
	setTabla();
	
	tabla.search(lastSearch).draw();
	e.preventDefault();
});

function refrescarContadorTiposHTML(){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/vehiculo/contadorDeTiposHTML',
		dataType: "html",
		success: function(data){
			$('#refreshContadorTipos').html(data);
		}
	});
	comprobarBtnAccionesMultiples();
	comprobarSeleccionarTodos();
}

//Cuando se hace click en un estado
$(document).on('click','.estado,.estadoTodos',function(e){
	refrescarContadorTiposHTML();
	$('.checkboxTabla.selected').removeClass('selected');
	$('.checkboxTabla.selected').toggleClass( 'fa-check-square','fa-square');
	tabla.search($(this).attr('data-search')).draw();
});
