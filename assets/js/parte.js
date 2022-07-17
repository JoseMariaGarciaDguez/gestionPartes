var partesTablaPP;
var partesTablaPC;
var signaturePad;

function setPartesTabla(estado) {
    return $('#partesTabla' + estado).DataTable({
        "ajax": {
            dataType: "json",
            data: {estado: estado, tablaSoloCreados: $('#tablaSoloCreados').text()},
            url: BASE_URL + "/parte/obtenerPartesTabla",
            type: 'POST'
        },
		"bSort" : false,
        "stateSave": true,
        "pageLength": 50,
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL + "/assets/components/datatables/Spanish.json"
        }
    });
}


$('#descargarpdf').click(function () {
    var idParte = $(this).attr('parteID');
    var idEmpleado = $(this).attr('empleadoID');
    var canvas = '';
    if (signaturePad != undefined)
        canvas = signaturePad.toDataURL();

    var ajaxRequest = $.ajax({
        type: "POST",
        url: BASE_URL + '/parte/plantilla/' + idParte,
        data: {canvas: canvas, empleadoID: idEmpleado},
        success: function (data) {
            newlink = document.createElement('a');
            newlink.setAttribute('href', BASE_URL + '/parte/pdf/' + data);
            newlink.setAttribute('target', '_blank');
            newlink.click();
        }
    });
});

$(document).ready(function () {

    $('#formCrearParte,#formEditarParte').validator('validate');

    partesTablaPP = setPartesTabla('P');
    partesTablaPC = setPartesTabla('C');

    canvas = document.querySelector("canvas");
    if (canvas != undefined) {
        signaturePad = new SignaturePad(canvas, {
            penColor: "rgb(66, 133, 244)",
            backgroundColor: "#ecf0f5"
        });
    }

    if ($('#firmaDibujo') != undefined && signaturePad != undefined) {
        try {
            signaturePad.fromData($.parseJSON($('#firmaDibujo').text()));
            signaturePad.on();

            if ($('#firma').attr('data-disable') == 'true') signaturePad.off();
        } catch (e) {
        }
    }

    setTimeout(function () {
        actualizarAveria();
        $('#formCrearParte,#formEditarParte').validator('validate');
        $('#formCrearParte,#formEditarParte').validator('update');
    }, 500);

    $(".se-pre-con").fadeOut("slow");
});


//Cuando se hace click en el boton de editar parte
$('#formEditarParte').validator().on('submit', document, function (e) {
    if (!e.isDefaultPrevented()) {
        var tipo = $("#formEditarParte").find('select[name="tipo"]').val();
        var obra = "NULL";

        if (tipo == "A") {
            obra = $('#textObra').val();
        } else {
            obra = $('#selectObra').val();
        }

        if (obra == "") obra = "NULL";


        var listImageCards = $('.image-card img');
        var galeriaImagenes = [];

        listImageCards.each(x => {
            galeriaImagenes.push(listImageCards[x].src);
        });

        $('.se-pre-con').show();
        $.ajax({
            type: "POST",
            url: BASE_URL + '/parte/EditarParteFormulario',
            data: $("#formEditarParte").serialize() + '&id=' + $('#btn_editarParte').attr('data-id') + '&galeriaImagenes=' + JSON.stringify(galeriaImagenes) + '&firma=' + JSON.stringify(signaturePad.toData()) + '&avatarName=' + $("#imgAvatar").attr('data-name') + '&obraF=' + obra,
            dataType: "json",
            success: function (data) {
                if (data.titulo == "RECARGAR_WEB") {
                    location.reload();
                } else {
                    if (data.enviar == false)
                        mostrarMensaje(data.titulo, data.mensaje, 'danger');
                    else
                        location.href = BASE_URL + "/parte";
                }

                $('.se-pre-con').hide();
            }
        });
    }

    e.preventDefault();

});

//Cuando se hace click en el boton de crear parte
$('#formCrearParte').validator().on('submit', document, function (e) {
    if (!e.isDefaultPrevented()) {
        var tipo = $("#formCrearParte").find('select[name="tipo"]').val();
        var obra = "NULL";


        if (tipo == "A") {
            obra = $('#textObra').val();
        } else {
            obra = $('#selectObra').val();
        }
        if (obra == null) obra = "NULL";

        var listImageCards = $('.image-card img');
        var galeriaImagenes = [];

        if (listImageCards.length > 0) {
            var fd = new FormData();
            listImageCards.each(x => {
                galeriaImagenes.push(listImageCards[x].src);
            });
        }

        $('.se-pre-con').show();

        $.ajax({
            type: "POST",
            url: BASE_URL + '/parte/CrearParteFormulario',
            data: $("#formCrearParte").serialize() + '&firma=' + JSON.stringify(signaturePad.toData()) + '&galeriaImagenes=' + JSON.stringify(galeriaImagenes) + '&avatarName=' + $("#imgAvatar").attr('data-name') + '&obraF=' + obra,
            dataType: "json",
            success: function (data) {
                if (data.titulo == "RECARGAR_WEB") {
                    location.reload();
                } else {
                    if (data.enviar == false)
                        mostrarMensaje(data.titulo, data.mensaje, 'danger');
                    else
                        location.href = BASE_URL + "/parte";
                }

                $('.se-pre-con').hide();
            }
        });
        e.preventDefault();
    }
});

$(document).on('change', 'select[name="tipo"]', function (e) {
    actualizarAveria();
});

function actualizarAveria() {

    var value = $('select[name="tipo"]').val();
    if (value == "A") {
        $('#textObra').show();
        $('#selectObra').parent('.dropdown').hide();
    } else {
        $('#textObra').hide();
        $('#selectObra').parent('.dropdown').show();
    }
}

//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click', '#btn_reiniciarFirma', function (e) {
    signaturePad.clear();
});


//Cuando se hace click en el boton de descargar todas las imagenes
$(document).on('click', '#descargarTodasImagenes', function (e) {
    ($('#parteID').text().replace('PAR', ''));
    $.ajax({
        type: "POST",
        data: {parteID: $('#parteID').text().replace('PAR', '')},
        url: BASE_URL + "/parte/descargarImagenes",
        dataType: "JSON",
        success: function (result) {
            var a = document.createElement('a');
            a.href = result.url;
            a.download = result.nombre;
            a.click();
        }
    });
});


$(document).on('click', '#btn_siFirma', function (e) {
    if ($(this).hasClass('bg-red')) {
        $(this).removeClass("bg-red");
        $(this).addClass("bg-green");
    } else {
        $(this).removeClass("bg-green");
        $(this).addClass("bg-red");
    }

    if ($('#grupo-firma').is(':visible')) {
        $('#grupo-firma').hide();
        $('input[name="dni"]').val('');
        signaturePad.clear();
    } else {
        $('#grupo-firma').show();
    }
});

//Cuando se hace click en el boton de borrar empleado (dentro del modla)
$(document).on('click', '#btn_borrarParte', function (e) {
    var id = $(this).attr('data-id');
    borrarParte(id);

    refrescarContadorTiposHTML($(this).attr('data-tipo'));
    e.preventDefault();
});

//Cuando se hace click en el boton de borrar empleado (dentro del modla)
$(document).on('click', '.btn_abrirModalBorrarParte', function (e) {
    var id = $(this).attr('data-id');
    var tipo = $(this).attr('data-tipo');
    $('#btn_borrarParte').attr('data-id', id);
    $('#btn_borrarParte').attr('data-tipo', tipo);
});


//Botones de la tabla

$('#borrarSeleccionTablaPP').click(function () {
    $('#btn_borrarPartes').attr('data-tipo', 'P');
});

$('#borrarSeleccionTablaPC').click(function () {
    $('#btn_borrarPartes').attr('data-tipo', 'C');
});


$('#btn_borrarPartes').click(function () {
    if ($(this).attr('data-tipo') == 'P') {
        partesTablaPP.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var selected = $(this.node()).find('.selected');
            if (selected.length > 0) {
                borrarParte(selected.attr('data-id'));
            }
        });
        refrescarContadorTiposHTML('P');
    } else {
        partesTablaPC.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var selected = $(this.node()).find('.selected');
            if (selected.length > 0) {
                borrarParte(selected.attr('data-id'));
            }
        });
        refrescarContadorTiposHTML('C');
    }

});

function borrarParte(id) {

    $.ajax({
        type: "POST",
        url: BASE_URL + '/parte/BorrarParteModalSi',
        data: {id: id},
        dataType: "json",
        success: function (data) {
            mostrarMensaje(data.titulo, data.mensaje, 'success');
            partesTablaPP.row($('i[data-id="' + id + '"]').closest('tr')).remove().draw();
            partesTablaPC.row($('i[data-id="' + id + '"]').closest('tr')).remove().draw();

            comprobarBorradoURL();
        }
    });
}

//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click', '.estadoPTodos,.estadoPA,.estadoPT', function (e) {
    $('.tab-pane:visible .checkboxTabla.selected').removeClass('selected');
    $('.tab-pane:visible .checkboxTabla.selected').toggleClass('fa-check-square', 'fa-square');
    refrescarContadorTiposHTML('P');
    refrescarContadorTiposHTML('C');
    partesTablaPP.search($(this).attr('data-search')).draw();

});
$(document).on('click', '.estadoCTodos,.estadoCA,.estadoCT', function (e) {
    $('.tab-pane:visible .checkboxTabla.selected').removeClass('selected');
    $('.tab-pane:visible .checkboxTabla.selected').toggleClass('fa-check-square', 'fa-square');
    refrescarContadorTiposHTML('C');
    refrescarContadorTiposHTML('P');
    partesTablaPC.search($(this).attr('data-search')).draw();

});

$(document).on('click', '.nav .nav-tabs li', function (e) {
    comprobarSeleccionarTodosEnTAB('.tab-pane:visible');
});

function refrescarContadorTiposHTML(tipo) {
    $.ajax({
        type: "POST",
        data: {tipo: tipo},
        url: BASE_URL + '/parte/contadorDeTiposHTML',
        dataType: "html",
        success: function (data) {
            $('#tab_' + tipo + ' .refreshContadorTipos').html(data);
        }
    });

    comprobarSeleccionarTodosEnTAB('#tab_' + tipo);
}

//Cuando se hace click en el boton de editar partes (modal) (seleccion)
$(document).on('click', '#btn_editarPartes', function (e) {
    var listaIds = [];
    var tipo = "P";

    if ($('.tab-pane:visible #partesTablaC').length > 0) tipo = "C";

    if (tipo == 'C')
        tempTable = partesTablaPC;
    else
        tempTable = partesTablaPP;


    tempTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var selected = $(this.node()).find('.selected');
        if (selected.length > 0) {
            listaIds.push(selected.attr('data-id'));
        }
    });

    $.ajax({
        type: "POST",
        url: BASE_URL + '/parte/EditarPartesFormulario',
        data: $("#formEditarPartes").serialize() + '&ids=' + JSON.stringify(listaIds),
        dataType: "json",
        success: function (data) {
            if (data.enviar == false)
                mostrarMensaje(data.titulo, data.mensaje, 'danger');
        }
    });


    var lastSearch = partesTablaPC.search();
    partesTablaPC.destroy();
    partesTablaPC = setPartesTabla('C');
    partesTablaPC.search(lastSearch).draw();
    refrescarContadorTiposHTML('C');

    lastSearch = partesTablaPP.search();
    partesTablaPP.destroy();
    partesTablaPP = setPartesTabla('P');
    partesTablaPP.search(lastSearch).draw();
    refrescarContadorTiposHTML('P');

    e.preventDefault();
});
