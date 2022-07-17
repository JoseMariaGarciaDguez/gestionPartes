var obrasTabla;

$(document).ready(function() {
	$('#formCrearObra,#formEditarObra,#formEditarObras').validator('validate');
	setObrasTabla();
});


function setObrasTabla(){
	if(obrasTabla!=undefined) obrasTabla.destroy();
	
	obrasTabla = $('#obrasTabla').DataTable({
        "ajax": {
			data: {tablaSoloCreados:$('#tablaSoloCreados').text()},
			dataType: "json",
            url : BASE_URL+"/obra/obtenerObrasTabla",
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

//Cuando se hace click en el boton de editar obra
$(document).on('click','#btn_editarObra',function(e){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/obra/EditarObraFormulario',
		data: $("#formEditarObra").serialize()+'&id='+$(this).attr('data-obra')+'&avatarName='+$("#imgAvatar").attr('data-name'),
		dataType: "json",
		success: function(data){
			if(data.titulo == "RECARGAR_WEB"){
				location.reload();
			}else{
				if(data.enviar == false)
					mostrarMensaje(data.titulo,data.mensaje,'danger');
				else
					location.href=BASE_URL+"/obra";
			}
		}
	});
	
	e.preventDefault();
});

//Cuando se hace click en el boton de editar obras (modal) (seleccion)
$(document).on('click','#btn_editarObras',function(e){
	var listaIds = [];
	
	obrasTabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			listaIds.push(selected.attr('data-obra'));
		}
	});
	
	$.ajax({
		type: "POST",
		url: BASE_URL+'/obra/EditarObrasFormulario',
		data: $("#formEditarObras").serialize()+'&ids='+JSON.stringify(listaIds),
		dataType: "json",
		success: function(data){
			if(data.enviar == false)
				mostrarMensaje(data.titulo,data.mensaje,'danger');

			refrescarContadorTiposHTML();
		}
	});
	
	var lastSearch = obrasTabla.search();
	
	setObrasTabla();
	
	obrasTabla.search(lastSearch).draw();
	e.preventDefault();
});

//Cuando se hace click en el boton de crear obra
$(document).on('click','#btn_crearObra',function(e){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/obra/CrearObraFormulario',
		data: $("#formCrearObra").serialize()+'&avatarName='+$("#imgAvatar").attr('data-name'),
		dataType: "json",
		success: function(data){
			if(data.titulo == "RECARGAR_WEB"){
				location.reload();
			}else{
				if(data.enviar == false)
					mostrarMensaje(data.titulo,data.mensaje,'danger');
				else
					location.href=BASE_URL+"/obra";
			}
		}
	});
	
	e.preventDefault();
});


//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click','.estadoTodos,.estadoA,.estadoP,.estadoC,.estadoD',function(e){
	refrescarContadorTiposHTML();
	$('.checkboxTabla.selected').removeClass('selected');
	$('.checkboxTabla.selected').toggleClass( 'fa-check-square','fa-square');
	obrasTabla.search($(this).attr('data-search')).draw();
	
});
//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click','.estadoTodos',function(e){
	obrasTabla.search('').draw();
});

//Cuando se hace click en el boton de borrar empleado (dentro del modla)
$(document).on('click','#btn_borrarObra',function(e){
	var id = $(this).attr('data-id');
	borrarObra(id);
	
	refrescarContadorTiposHTML();
	e.preventDefault();
});


//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click','#btn_abrirModalBorrarEmpleado',function(e){
	$('#btn_borrarEmpleado').attr('data-empleado',$(this).attr('data-empleado'));
	
});





//Botones de la tabla

$(document).on( 'click','.btn_abrirModalBorrarObra', function (e) {
	$('#btn_borrarObra').attr('data-id',$(this).attr('data-id'));
});

$('#btn_borrarObras').click( function () {
	obrasTabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			borrarObra(selected.attr('data-id'));
		}
	});
	
	refrescarContadorTiposHTML();
} );

//Botones de la tabla
function borrarObra(id){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/obra/BorrarObraModalSi',
		data: {id:id},
		dataType: "json",
		success: function(data){
			mostrarMensaje(data.titulo,data.mensaje,'success');
			obrasTabla.row($('i[data-id="'+id+'"]').closest('tr')).remove().draw();
			comprobarBtnAccionesMultiples();
			comprobarBorradoURL();
		}
	});
}

function refrescarContadorTiposHTML(){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/obra/contadorDeTiposHTML',
		dataType: "html",
		success: function(data){
			$('#refreshContadorTipos').html(data);
		}
	});
	comprobarBtnAccionesMultiples();
	comprobarSeleccionarTodos();
}