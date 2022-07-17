var tabla;

$(document).ready(function() {
	$('#formCrear,#formEditar').validator('validate');
	$('#formCrear,#formEditar').validator('update');
	setTabla();
	comprobarColumnasVerPermisos();
});


function comprobarColumnasVerPermisos(){
	$.each($('.panelPermiso'),function(){
		var panel = $(this);
		if($(this).find('.box-body').find('li').length ==0){
			  ($(this));
			panel.hide();
		}
	});

}



function setTabla(){
	if(tabla!=undefined) tabla.destroy();
	
	tabla = $('#tabla').DataTable({
        "ajax": {
            url : BASE_URL+"/rol/obtenerTabla",
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
$(document).on('click','#btn_borrarRol',function(e){
	var id = $(this).attr('data-id');
	borrarRol(id);
	
	e.preventDefault();
});

//Cuando se hace click sobre el elemento de la tabla
$(document).on('click','.btn_abrirModalBorrarRol',function(e){
	$('#btn_borrarRol').attr('data-id',$(this).attr('data-id'));
});

//Cuando se hace click sobre el elmento de borrar todos
$('#btn_borrarRoles').click( function () {
	tabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			borrarRol(selected.attr('data-id'));
		}
	});
	
	comprobarBtnAccionesMultiples();
} );

//Ejecuta un borrado por id
function borrarRol(id){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/rol/BorrarRolModalSi',
		data: {id:id},
		dataType: "json",
		success: function(data){
			mostrarMensaje(data.titulo,data.mensaje,'success');
			tabla.row($('i[data-id="'+id+'"]').closest('tr')).remove().draw();
			comprobarBtnAccionesMultiples();

			comprobarBorradoURL();
		}
	});
}

//==============================
//Creacion
//==============================

//Cuando se hace click en el boton de crear rol
$('#formCrear').validator().on('submit',document,function(e){
	
	if (!e.isDefaultPrevented()) {
		
		var listaPermisos = [];
		$('.permInput').each(function(){
			if($(this).hasClass('selected')) listaPermisos.push($(this).attr('data-permiso'));
		});
		
		$.ajax({
			type: "POST",
			url: BASE_URL+'/rol/crearRol',
			data: $("#formCrear").serialize()+'&permisos='+JSON.stringify(listaPermisos),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/rol";
				}
			}
		});
		e.preventDefault();
	}
});

//==============================
//Modificacion
//==============================

//Cuando se hace click en el boton de modificar rol
$('#formEditar').validator().on('submit',document,function(e){
	$('#formEditar').validator('update');
	if (!e.isDefaultPrevented()) {
		
		var listaPermisos = [];
		$('.permInput').each(function(){
			if($(this).hasClass('selected')) listaPermisos.push($(this).attr('data-permiso'));
		});
		$.ajax({
			type: "POST",
			url: BASE_URL+'/rol/editarRol',
			data: $("#formEditar").serialize()+'&id='+$('#btn_editarRol').attr('data-id')+'&permisos='+JSON.stringify(listaPermisos),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/rol";
				}
			}
		});
		e.preventDefault();
	}
});