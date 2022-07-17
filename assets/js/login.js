//Cuando se hace click en el boton de iniciar sesión
$('#formLogin').validator().on('submit',document,function(e){
	if (!e.isDefaultPrevented()) {
		$.ajax({
			type: "POST",
			url: BASE_URL+'/login/revisarFormulario',
			data: $("#formLogin").serialize(),
			dataType: "json",
			success: function(data){
				if(data.enviar == false)
					mostrarMensaje(data.titulo,data.mensaje,'danger');
				else
					location.reload();
			}
		});
		
		e.preventDefault();
	}
});