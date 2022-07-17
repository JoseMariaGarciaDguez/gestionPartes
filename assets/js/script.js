const BASE_URL = "";
const SEGMENTO_ASIGNADOR = 4;

//Cuando se cierra el mensaje de error
$(document).on('close.bs.alert', '#msg_alert', function (e) {
    $('#msg').hide();
    e.preventDefault();
    e.stopPropagation();

});

function mostrarMensaje(titulo, mensaje, tipo) {
    (mensaje);
    $('#msg_title').html(titulo);
    $('#msg_text').html(mensaje);
    $('#msg div').removeClass('alert-warning alert-success alert-danger');
    $('#msg div').addClass('alert-' + tipo);
    $('#msg').fadeIn();

    setTimeout(function () {
        $('#msg').fadeOut();
    }, 3000);

}


//Cuando se sube una imagen
$(document).on('change', '#inputAvatar', function (e) {

    var file_data = $(this).prop('files')[0];
    var form_data = new FormData();
    form_data.append('avatar', file_data);
    form_data.append('height', $('#inputAvatar').attr('data-height'));
    form_data.append('width', $('#inputAvatar').attr('data-width'));

    $.ajax({
        type: "POST",
        url: BASE_URL + '/empleado/subirTemporalmenteAvatar',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (data) {
            if (data.titulo == "PONER_AVATAR") {
                $("#imgAvatar").attr('src', data.url);
                $("#imgAvatar").attr('data-name', data.url_name);
            } else {
                mostrarMensaje(data.titulo, data.mensaje, 'danger');
            }
        }
    });

    e.preventDefault();
});

//Botones tabla

$(document).on('click', '.checkboxTabla', function (e) {
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
    } else {
        $(this).addClass('selected');
    }

    if ($('.tab-pane:visible .seleccionTabla tr').length > 1)
        comprobarSeleccionarTodosEnTAB('.tab-pane:visible');
    else comprobarSeleccionarTodos();

    e.preventDefault();
});

//Boton seleccionar todos
$(document).on('click', '.seleccionarTodos', function (e) {
    if ($('.tab-pane:visible .seleccionTabla tr').length > 1) {
        if ($(this).hasClass('selected')) {
            $('.tab-pane:visible .checkboxTabla').removeClass('selected');
        } else {
            $('.tab-pane:visible .checkboxTabla').addClass('selected');
        }
        comprobarSeleccionarTodosEnTAB('.tab-pane:visible');
    } else if ($('.seleccionTabla tr').length > 1) {
        if ($(this).hasClass('selected')) {
            $('.checkboxTabla').removeClass('selected');
        } else {
            $('.checkboxTabla').addClass('selected');
        }
        comprobarSeleccionarTodos();
    }
});

//Comprueba si debe mostrar/ocultar el boton de seleccionar todos (despues comprueba edicion multiple)
function comprobarSeleccionarTodos() {
    if ($('.seleccionarTodos').hasClass('selected') || $('.seleccionTabla tr').length - 1 != $('.checkboxTabla.selected').length) {
        $('.seleccionarTodos').removeClass('selected');
    } else {
        $('.seleccionarTodos').addClass('selected');
    }
    comprobarBtnAccionesMultiples();

}

//Comprueba si debe mostrar/ocultar el boton de seleccionar todos (despues comprueba edicion multiple) en tablas dentro de tab-pane
function comprobarSeleccionarTodosEnTAB(tab) {
    if ($(tab + ' .seleccionarTodos').hasClass('selected') || $(tab + ' .seleccionTabla tr').length - 1 != $(tab + ' .checkboxTabla.selected').length) {
        $(tab + ' .seleccionarTodos').removeClass('selected');
    } else {
        $(tab + ' .seleccionarTodos').addClass('selected');
    }
    comprobarBtnAccionesMultiplesEnTAB(tab);

}

//Comprueba si debe mosrtar/ocultar los botones de edicion multiple
function comprobarBtnAccionesMultiples() {

    if ($('.checkboxTabla.selected').length > 0) {
        $('.accionSeleccion').show();
    } else {
        $('.accionSeleccion').hide();
    }
}

//Comprueba si debe mosrtar/ocultar los botones de edicion multiple en tablas dentro de tab-pane
function comprobarBtnAccionesMultiplesEnTAB(tab) {

    if ($(tab + ' .checkboxTabla.selected').length > 0) {
        $(tab + ' .accionSeleccion').show();
    } else {
        $(tab + ' .accionSeleccion').hide();
    }
}

//Comprueba los selectores
$('.selectpicker').on('hide.bs.select', function () {
    $(this).trigger("focusout");
});

function dataURLtoFile(dataurl, filename) {
    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, {type: mime});
}


//Cuando se hace click sobre borrar en el modal de previsualización de la imagen (SCAR A LIBRERIA)
$(document).on('click', '.btn_borrarImagen', function (e) {
    var posicion = $(this).attr('position');

    $('#modalVerImagen').modal('hide');
    $('#modalVerImagen .modal-body img').attr('src', "");
    $('.image-card[position=' + posicion + ']').remove();
});

//Cuando se hace click sobre la imagen (SACAR A LIBRERIA)
$(document).on('click', '.verImagen', function (e) {
    var posicion = $(this).parent().attr('position');
    var imagen = $(this).attr('src');
    $('#descargarImagen').attr('href', imagen);

    $('.btn_borrarImagen').attr('position', posicion);
    $('#modalVerImagen .modal-body img').attr('src', imagen);
    $('#modalVerImagen').modal('show');
});
//Cuando se hace click sobre la imagen (sin que aparezca el boton borrar)
$(document).on('click', '.verImagenSinBorrar', function (e) {
    var imagen = $(this).attr('src');
    $('#descargarImagen').attr('href', imagen);

    $('.btn_borrarImagen').remove();
    $('#modalVerImagen .modal-body img').attr('src', imagen);
    $('#modalVerImagen').modal('show');
});
//Cuando se hace click en el boton de agregar imagenes
$(document).on('click', '#agregarImagenes', function (e) {
    $('#agregarImagenesForm').click();
});

function setBase64Image(file) {
    lastnum = $(document).find('.image-card').last().attr('position');
    if (lastnum == "NaN" || lastnum == undefined) lastnum = 0;
    else lastnum = parseInt(lastnum) + 1;

    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        var output = $("#contendor-imagenes");
        var html = ` 
            <div class="image-card" position="` + lastnum + `">
                <img class="img-fluid verImagen" src="` + reader.result + `"/>
             </div>
        `;
        output.append(html);
        lastnum = parseInt(lastnum) + 1;
    };
    reader.onerror = function (error) {
        console.log('Error: ', error);
    };
}

var lastnum;
$(document).ready(function () {

    lastnum = $(document).find('.image-card').last().attr('position');

    if (location.href.split('/')[3] != "parte" && location.href.split('/')[3] != "informes") $(".se-pre-con").fadeOut("slow");

    $('input[data-provide=datepicker]').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        orientation: 'auto'
    });

    $('.datepicker').datepicker({
        language: 'es',
        format: 'dd/mm/yyyy',
        orientation: 'bottom'
    });

    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        if (sessionStorage.getItem("sidebar-toggle-collapsed") == "1") $("body").addClass('sidebar-collapse');
    }
    $(function () {
        $('#agregarImagenesForm').fileupload({
            dataType: 'json',
            add: function (e, data) {
                setBase64Image(data.files[0]);
            },
            done: function (e, data) {
                console.log("COMPLETADO");
            }
        });
    });

});

$(document).on('click', '.sidebar-toggle', function (event) {
    if (Boolean(sessionStorage.getItem("sidebar-toggle-collapsed"))) {
        sessionStorage.setItem("sidebar-toggle-collapsed", "");
    } else {
        sessionStorage.setItem("sidebar-toggle-collapsed", "1");
    }
    event.preventDefault();
});

function comprobarBorradoURL() {
    if (location.href.split('/')[5] == "ver" && location.href.split('/')[5] != undefined) window.history.back();
}
