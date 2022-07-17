//Cuando se hace click en el boton de iniciar sesión
$(document).on('click','#btn_cambiarClave',function(e){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/perfil/CambiarClaveFormulario',
		data: $("#formClave").serialize(),
		dataType: "json",
		success: function(data){
			if(data.enviar == false)
				mostrarMensaje(data.titulo,data.mensaje,'danger');
			else
				mostrarMensaje(data.titulo,data.mensaje,'success');
		}
	});
	
	e.preventDefault();
});

//Cuando se hace click en el boton de actualizar perfil
$(document).on('click','#btn_actualizarPerfil',function(e){
	$.ajax({
		type: "POST",
		url: BASE_URL+'/perfil/CambiarDatosPerfilFormulario',
		data: $("#formPerfil").serialize()+'&avatarName='+$("#imgAvatar").attr('data-name'),
		dataType: "json",
		success: function(data){
			if(data.titulo == "RECARGAR_WEB"){
				location.reload();
			}else{
				if(data.enviar == false)
					mostrarMensaje(data.titulo,data.mensaje,'danger');
				else{
					mostrarMensaje(data.titulo,data.mensaje,'success');
					$('.nombreEmpleado').text($('input[name="nombre"]').val());
					$('.nombreEmpleadoNavbar').text($('input[name="nombre"]').val()+' '+$('input[name="apellidos"]').val());
					$('.imgAvatarSidebar,.imgAvatarNavbar').attr('src',$('#imgAvatar').attr('src'));
				}
			}
		}
	});
	
	e.preventDefault();
});