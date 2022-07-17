var empleadosTabla;
var empleadosTablaB;



function setEmpleadosTabla(estado) {
	if(estado == "A") estado = "";

	return $('#empleadosTabla' + estado).DataTable({
		"ajax": {
			dataType: "json",
			data: {estado: estado,tablaSoloCreados:$('#tablaSoloCreados').text()},
			url: BASE_URL+"/empleado/obtenerEmpleadosTabla",
			type: 'POST'
		},
		"stateSave": true,
		"pageLength": 50,
		"order": [[0, "desc"]],
		language: {
			"url": BASE_URL+"/assets/components/datatables/Spanish.json"
		}
	});
}

$(document).ready(function() {
    empleadosTabla = setEmpleadosTabla('A');
    empleadosTablaB = setEmpleadosTabla('B');
});

//Cuando se hace click en el boton de borrar empleado (dentro del modla)
$(document).on('click','#btn_borrarEmpleado',function(e){
	var id = $(this).attr('data-id');
	borrarEmpleado(id);
	
	e.preventDefault();
});

//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click','.btn_abrirModalBorrarEmpleado',function(e){
	$('#btn_borrarEmpleado').attr('data-id',$(this).attr('data-id'));
});

//Cuando se hace click en el boton de editar empleado
$('#formEditarEmpleado').validator().on('submit',document,function(e){
	console.log("send");
	if (!e.isDefaultPrevented()) {
		$.ajax({
			type: "POST",
			url: BASE_URL+'/empleado/EditarEmpleadoFormulario',
			data: $("#formEditarEmpleado").serialize()+'&avatarName='+$("#imgAvatar").attr('data-name')+'&id='+$(this).attr('data-id'),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/empleado";
				}
			}
		});

		e.preventDefault();
	}
});



//Cuando se hace click en el boton de editar empleado
$('#formCrearEmpleado').validator().on('submit',document,function(e){
	if (!e.isDefaultPrevented()) {
		$.ajax({
			type: "POST",
			url: BASE_URL+'/empleado/CrearEmpleadoFormulario',
			data: $("#formCrearEmpleado").serialize()+'&avatarName='+$("#imgAvatar").attr('data-name'),
			dataType: "json",
			success: function(data){
				if(data.titulo == "RECARGAR_WEB"){
					location.reload();
				}else{
					if(data.enviar == false)
						mostrarMensaje(data.titulo,data.mensaje,'danger');
					else
						location.href=BASE_URL+"/empleado";
				}
			}
		});

		e.preventDefault();
	}
});





function borrarEmpleado(id){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/empleado/BorrarEmpleadoModalSi',
		data: {id:id},
		dataType: "json",
		success: function(data){
			mostrarMensaje(data.titulo,data.mensaje,'success');
			empleadosTabla.row($('i[data-id="'+id+'"]').closest('tr')).remove().draw();
			comprobarBtnAccionesMultiples();

			comprobarBorradoURL();
		}
	});
}




//Botones de la tabla

$('#btn_borrarEmpleados').click( function () {
	empleadosTabla.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		var selected = $(this.node()).find('.selected');
		if(selected.length > 0){
			borrarEmpleado(selected.attr('data-id'));
		}
	});
} );

//Botones de la tabla

//Cuando se hace click en el boton de editar empleados (modal) (seleccion)
$(document).on('click', '#btn_editarEmpleados', function (e) {
	var listaIds = [];
	var tipo = "P";

	if ($('.tab-pane:visible #empleadosTabla').length > 0) tipo = "A";

	if (tipo == 'A')
		tempTable = empleadosTabla;
	else
		tempTable = empleadosTablaB;


	tempTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
		var selected = $(this.node()).find('.selected');
		if (selected.length > 0) {
			listaIds.push(selected.attr('data-id'));
		}
	});

	$.ajax({
		type: "POST",
		url: BASE_URL+'/empleado/EditarEmpleadosFormulario',
		data: $("#formEditarEmpleados").serialize() + '&ids=' + JSON.stringify(listaIds),
		dataType: "json",
		success: function (data) {
			if (data.enviar == false)
				mostrarMensaje(data.titulo, data.mensaje, 'danger');
		}
	});


	var lastSearch = empleadosTabla.search();
	empleadosTabla.destroy();
	empleadosTabla = setEmpleadosTabla('A');
	empleadosTabla.search(lastSearch).draw();

	lastSearch = empleadosTablaB.search();
	empleadosTablaB.destroy();
	empleadosTablaB = setEmpleadosTabla('B');
	empleadosTablaB.search(lastSearch).draw();

	e.preventDefault();
});

