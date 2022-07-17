var asignadorTabla;
var ultimaPosicionScroll = 0;
var topeScroll = false;
var avance = $('.asignadorCards .col-md-3').width() + 15;

function desasignarElemento(elemento) {
    elemento = elemento.removeClass('col-md-12').addClass('col-md-3');
    if (~elemento.attr('id').indexOf('EMP')) {
        $('#tab_Empleados .row').append(elemento);
    } else if (~elemento.attr('id').indexOf('OBR')) {
        $('#tab_Obras .row').append(elemento);
    } else if (~elemento.attr('id').indexOf('VEH')) {
        $('#tab_Vehiculos .row').append(elemento);
    }

    comprobarCardsVacias();
}

function setAsignadorTabla() {
    if (asignadorTabla != undefined) asignadorTabla.destroy();

    asignadorTabla = $('#asignadorTabla').DataTable({
        "ajax": {
            dataType: "json",
            url: BASE_URL + "/asignador/obtenerAsignadorTabla",
            type: 'POST'
        },
        "stateSave": true,
        "pageLength": 50,
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL + "/assets/components/datatables/Spanish.json"
        }
    });
}

if (location.href.split('/')[SEGMENTO_ASIGNADOR] == 'historial') {
    $(document).ready(function () {
        setAsignadorTabla();
    });

} else if (location.href.split('/')[SEGMENTO_ASIGNADOR] == 'editar') {
    $(document).ready(function () {
        $(".draggable").draggable({
            revert: false,
            helper: 'clone',
            scroll: false,
            stop: function (event, ui) {
                comprobarCardsVacias();
                setTimeout(function () {
                    ;
                    guardarDatos();
                }, 500)
            }
        });

        $("#guardarDatos").on('click', function () {
            guardarDatos();
        });

        //Cuando sueltas el elemento en cualquier parte de la página
        $("body").droppable({
            accept: ".draggable",
            drop: function (event, ui) {
                var dondeSeSuelta = $(this);
                var elQueSuelta = ui.draggable;

                //Si lo estás soltando fuera de sus sitios correspondientes lo devuelve al sitio original
                if (!~elQueSuelta.attr('id').indexOf(dondeSeSuelta.attr('data-id'))) {
                    desasignarElemento(elQueSuelta);
                    elQueSuelta.find('.verIcno').show();
                }
            }
        });

        //Cuando sueltas el elemento sobre el area del asignador de cards
        $(".asignadorCards").droppable({
            accept: ".draggable",
            drop: function (event, ui) {

                var dondeSeSuelta = $(this);
                var elQueSuelta = ui.draggable;
                var d = $.datepicker.formatDate('ddmmyy', new Date());

                //HTML De la card que se añadirá
                var html = `
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <span class="fa fa-times cerrar"></span>
                            <span class="fa fa-minus minimizar"></span>
                            <h3 class="box-title">ASI` + d + `</h3>
                        </div>

                        <div class="box-body">

                            <b>Obras</b>
                            <div class="asignadoContenedor" data-id="OBR"></div>
                            
                            <b>Empleados</b>
                            <div class="asignadoContenedor" data-id="EMP"></div>

                            <b>Vehiculos</b>
                            <div class="asignadoContenedor" data-id="VEH"></div>

                        </div>
                    </div>
                </div>
            `;

                var card = $(html);

                //Cambiamos una clase del elemento que se está soltando
                elQueSuelta = elQueSuelta.removeClass('col-md-3').addClass('col-md-12');

                //Dependiendo del tipo de elemento que se está soltando, se añade a su sitio correspondiente
                if (~elQueSuelta.attr('id').indexOf('EMP')) {
                    card.find('.asignadoContenedor[data-id="EMP"]').append(elQueSuelta);
                } else if (~elQueSuelta.attr('id').indexOf('OBR')) {
                    card.find('.asignadoContenedor[data-id="OBR"]').append(elQueSuelta);
                } else if (~elQueSuelta.attr('id').indexOf('VEH')) {
                    card.find('.asignadoContenedor[data-id="VEH"]').append(elQueSuelta);
                }

                elQueSuelta.find('.verIcno').hide();

                //Finalmente agregamos la card al sitio dodne se suelta
                dondeSeSuelta.append(card);
            },
            over: function (event, ui) {
                //Asignamos todos los contenedores asignables
                asignarDroppable($('.asignadoContenedor'));
            },
            out: function (event, ui) {
                //Asignamos todos los contenedores asignables
                asignarDroppable($('.asignadoContenedor'));
            }
        });


        $('.asignadoContenedor').find('.verIcno').hide();

    });

} else {
    $('.verIcno').hide();


    setInterval(function () {
        $.ajax({
            type: "POST",
            data: {update: true, id: location.href.split('/')[SEGMENTO_ASIGNADOR + 1]},
            url: BASE_URL + '/asignador/actualizarDatos',
            dataType: "html",
            success: function (data) {
                $('.asignadorCards').html('');
                $('.asignadorCards').html(data);
                $('.verIcno').hide();
            }
        });
    }, 5000);

    setInterval(function () {
        var scroll = $('.asignadorCards');
        var posicion = scroll.scrollLeft();


        if (topeScroll) var aMover = posicion - avance;
        else var aMover = posicion + avance;

        if (posicion <= 0) topeScroll = false;

        $('.asignadorCards').scrollLeft(aMover);

        console.log(aMover);
        console.log(posicion);


        if (ultimaPosicionScroll == aMover) {
            ultimaPosicionScroll = 0;
            topeScroll = true;
        }
        ultimaPosicionScroll = aMover;


    }, 5000);
}

//Esta funcion hace un elemento asignable
function asignarDroppable(elemento) {
    elemento.droppable({
        accept: ".draggable",
        drop: function (event, ui) {
            var dondeSeSuelta = $(this);
            var elQueSuelta = ui.draggable;

            if (~elQueSuelta.attr('id').indexOf(dondeSeSuelta.attr('data-id'))) {
                dondeSeSuelta.append(elQueSuelta.removeClass('col-md-3').addClass('col-md-12'));
            }

            comprobarCardsVacias();
        }
    });
}

//Comprueba las cards vacias y las borra
function comprobarCardsVacias() {
    setTimeout(function () {
        $(document).find('.asignadorCards  .col-md-3').each(function () {
            var emp = $(this).find('.asignadoContenedor[data-id="EMP"]');
            var obr = $(this).find('.asignadoContenedor[data-id="OBR"]');
            var veh = $(this).find('.asignadoContenedor[data-id="VEH"]');
            if (emp.text().trim() == '' && obr.text().trim() == '' && veh.text().trim() == '') emp.closest('.col-md-3').remove();
        });
    }, 0);

}

function guardarDatos() {
    var asignaciones = [];

    $(document).find('.asignadorCards  .col-md-3').each(function () {
        var empleados = [];
        var vehiculos = [];
        var obras = [];
        try {
            $(this).find('.asignadoContenedor[data-id="EMP"] .draggable').each(function () {
                var id = $(this).attr('id').replace('EMP', '');
                empleados.push(id);
            });

            $(this).find('.asignadoContenedor[data-id="OBR"] .draggable').each(function () {
                var id = $(this).attr('id').replace('OBR', '');
                var nombre = $(this).attr('nombre');
                var fecha = $(this).attr('fecha');

                $(this).closest('.box').find('.box-title').text(nombre + ' ' + fecha);
                obras.push(id);
            });

            $(this).find('.asignadoContenedor[data-id="VEH"] .draggable').each(function () {
                var id = $(this).attr('id').replace('VEH', '');
                vehiculos.push(id);
            });

            asignaciones.push({empleadosID: empleados, obrasID: obras, vehiculosID: vehiculos});
        } catch (e) {

        }
    });

    $.ajax({
        type: "POST",
        url: BASE_URL + '/asignador/guardarDatos',
        data: {
            asignaciones: JSON.stringify(asignaciones),
            id: location.href.split('/')[5],
            fecha: $('.datepicker').val()
        },
        dataType: "json",
        success: function (data) {
            mostrarMensaje(data.titulo, data.mensaje, 'success');
        }
    });


}

function borrarAsignacion(id) {

    $.ajax({
        type: "POST",
        url: BASE_URL + '/asignador/borrarDato',
        data: {id: id},
        dataType: "json",
        success: function (data) {
            mostrarMensaje(data.titulo, data.mensaje, 'success');
            asignadorTabla.row($('i[data-id="' + id + '"]').closest('tr')).remove().draw();
        }
    });
}

$(document).on('click', '#btn_borrarAsignacion', function (e) {
    var id = $(this).attr('data-id');
    borrarAsignacion(id);

    e.preventDefault();
});

$(document).on('click', '#btn_borrarAsignadores', function (e) {
    asignadorTabla.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var selected = $(this.node()).find('.selected');
        if (selected.length > 0) {
            borrarAsignacion(selected.attr('data-id'));
        }
    });
});


$(document).on('click', '.btn_abrirModalBorrarAsignador', function (e) {
    $('#btn_borrarAsignacion').attr('data-id', $(this).attr('data-id'));
});

$(document).on('click', '.minimizar', function (e) {
    var icono = $(this);
    var body = icono.closest('.box').find('.box-body');

    if (body.is(':visible')) {
        body.fadeOut('fast');
        icono.toggleClass('fa-plus', 'fa-minus');
    } else {
        body.fadeIn('fast');
        icono.toggleClass('fa-plus', 'fa-minus');
    }
});

$(document).on('click', '.cerrar', function (e) {
    var icono = $(this);
    var body = icono.closest('.box').find('.box-body');

    body.find('.draggable').each(function () {
        var drag = $(this);
        desasignarElemento(drag);
    });

    comprobarCardsVacias();
    guardarDatos();
});

$(document).on('keyup', '#buscarTexto', function (e) {
    var texto = $(this).val();
    buscar(texto);
});

function buscar(texto) {
    $('.tab-pane .draggable ').each(function () {
        var textoPanel = $(this).text();
        if (texto != "") {
            if (~textoPanel.toLowerCase().indexOf(texto.toLowerCase())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        } else {
            $(this).show();
        }


    });
}





