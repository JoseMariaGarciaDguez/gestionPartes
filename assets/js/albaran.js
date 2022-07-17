var albaranesTabla;

$(document).ready(function () {
    $('#formCrear,#formModificar').validator('validate');
    $('#formCrear,#formModificar').validator('update');
    setTabla();
});


$('#descargarpdf').click(function () {
    var idAlbaran = $(this).attr('albaranID');
    var idEmpleado = $(this).attr('empleadoID');

    $.ajax({
        type: "POST",
        url: BASE_URL+'/albaran/plantilla/' + idAlbaran,
        data: {empleadoID: idEmpleado},
        success: function (data) {
            newlink = document.createElement('a');
            newlink.setAttribute('href',  BASE_URL+'/albaran/pdf/' + data);
            newlink.setAttribute('target', '_blank');
            newlink.click();
        }
    });
});


function setTabla() {
    if (albaranesTabla != undefined) albaranesTabla.destroy();

    albaranesTabla = $('#albaranesTabla').DataTable({
        "ajax": {
            data: {tablaSoloCreados: $('#tablaSoloCreados').text()},
            dataType: "json",
            url: BASE_URL+"/albaran/obtenerTabla",
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

$(document).on('change', '#clientesID', function (e) {
    actualizarRequisitosFormulario();
});

function actualizarRequisitosFormulario() {

    var value = $('#clientesID').val();
    if (value == "CREAR") {
        $('input[name="nombre"]').attr('required', true);
        $('input[name="email"]').attr('required', true);
    } else {
        $('input[name="nombre"]').attr('required', false);
        $('input[name="email"]').attr('required', false);
    }
    $('#formCrear,#formModificar').validator('validate');
    $('#formCrear,#formModificar').validator('update');
    $('#formCrear,#formModificar').validator('validate');
    $('#formCrear,#formModificar').validator('update');
}

//==============================
//Borrado
//==============================

//Cuando se hace click en "Si" del modal de borrar
$(document).on('click', '#btn_borrarAlbaran', function (e) {
    var id = $(this).attr('data-id');
    borrarAlbaran(id);

    e.preventDefault();
});

//Cuando se hace click sobre el elemento de la tabla
$(document).on('click', '.btn_abrirModalBorrarAlbaran', function (e) {
    $('#btn_borrarAlbaran').attr('data-id', $(this).attr('data-id'));
});

//Cuando se hace click sobre el elmento de borrar todos
$('#btn_borrarAlbaranes').click(function () {
    albaranesTabla.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var selected = $(this.node()).find('.selected');
        if (selected.length > 0) {
            borrarAlbaran(selected.attr('data-id'));
        }
    });

    comprobarBtnAccionesMultiples();
});

//Ejecuta un borrado por id
function borrarAlbaran(id) {
    $.ajax({
        type: "POST",
        url: BASE_URL+'/albaran/BorrarAlbaranModalSi',
        data: {id: id},
        dataType: "json",
        success: function (data) {
            mostrarMensaje(data.titulo, data.mensaje, 'success');
            albaranesTabla.row($('i[data-id="' + id + '"]').closest('tr')).remove().draw();
            comprobarBtnAccionesMultiples();

            comprobarBorradoURL();
        }
    });
}

//==============================
//Creacion
//==============================


/*webshims.setOptions('forms-ext', {
	replaceUI: 'auto',
	types: 'number',
	activeLang: 'es-ES'
});

webshims.polyfill('forms forms-ext');*/

function validarDinero(elm) {
    elm.value.replace(/,/g, ".");
}

//Cuando se hace click sobre el elemento de agregar un artículo
$(document).on('click', '.agregarArticulo', function (e) {

    var articulo = '';
    var articulo_readonly = '';

    var id = 'null';
    var tipo = 'null';

    if ($('#tipoAlbaran').val() != 'null') {
        id = $('#tipoAlbaran').val();
        tipo = $('#tipoAlbaran option:selected').attr('data-type');

        articulo = $('#tipoAlbaran option:selected').text();
        articulo_readonly = 'readonly';
    }

    if (tipo == "P"||tipo == "O") {
        $.ajax({
            type: "POST",
            url: BASE_URL+'/albaran/obtenerHoras',
            data: {id: id,tipo:tipo},
            dataType: "text",
            success: function (data) {
                var html = `
                    <tr>
                      <td class="articuloTablaPrimero">
                        <div class="form-group has-feedback">
                            <input class="form-control" maxlength="250" ` + articulo_readonly + ` data-type="` + tipo + `" value="` + articulo + `" data-id="` + id + `" name="articulos[]" type="text" required
                                data-required-error="Este campo es obligatorio.">
                            <div class="help-block "></div>
                        </div>
                      </td>
                      <td>
                        <div class="form-group has-feedback">
                        <input class="form-control" min="1" name="cantidades[]" value="`+data+`" type="number" required
                            data-required-error="Este campo es obligatorio.">
                        <div class="help-block "></div>
                        </div>
                      </td>
                      <td>
                        <div class="form-group has-feedback">
                            <input type="number"  name="precios[]" value="0" min="0" step="0.01" lang="es" 
                                data-number-to-fixed="2" 
                                data-number-stepfactor="100" class="form-control currency" required
                                data-required-error="Este campo es obligatorio." />
                
                            <div class="help-block "></div>
                        </div>
                      </td>
                      <td>
                        <i class="fa fa-minus pull-right pointer borrarArticulo"></i>
                      </td>
                    </tr>
                `;
                $('#listaArticulos tbody').append(html);
                $('#formCrear').validator('update');
                $('formModificar').validator('update');
            }
        });
    }else{
        var html = `
                    <tr>
                      <td class="articuloTablaPrimero">
                        <div class="form-group has-feedback">
                            <input class="form-control" maxlength="250" ` + articulo_readonly + ` data-type="` + tipo + `" value="` + articulo + `" data-id="` + id + `" name="articulos[]" type="text" required
                                data-required-error="Este campo es obligatorio.">
                            <div class="help-block "></div>
                        </div>
                      </td>
                      <td>
                        <div class="form-group has-feedback">
                        <input class="form-control" min="1" name="cantidades[]" value="1" type="number" required
                            data-required-error="Este campo es obligatorio.">
                        <div class="help-block "></div>
                        </div>
                      </td>
                      <td>
                        <div class="form-group has-feedback">
                            <input type="number"  name="precios[]" value="0" min="0" step="0.01" lang="es" 
                                data-number-to-fixed="2" 
                                data-number-stepfactor="100" class="form-control currency" required
                                data-required-error="Este campo es obligatorio." />
                
                            <div class="help-block "></div>
                        </div>
                      </td>
                      <td>
                        <i class="fa fa-minus pull-right pointer borrarArticulo"></i>
                      </td>
                    </tr>
                `;
        $('#listaArticulos tbody').append(html);
        $('#formCrear').validator('update');
        $('formModificar').validator('update');
    }


});

//Cuando se hace click sobre el elemento de borrar un artículo
$(document).on('click', '.borrarArticulo', function (e) {
    $(this).closest('tr').remove();
    $('#formCrear').validator('update');
    $('#formModificar').validator('update');
});

//Cuando se hace click sobre el elemento de borrar un artículo
$(document).on('click', '.checkboxEnviado', function (e) {
    var id = $(this).attr('data-id');
    var enviado = 0;

    if ($(this).hasClass("selected")) {
        $(this).removeClass("selected");
        enviado = 0;
    } else {
        $(this).addClass("selected");
        enviado = 1;
    }

    $.ajax({
        type: "POST",
        url: BASE_URL+'/albaran/enviarAlbaran',
        data: {id: id, enviado: enviado},
        dataType: "json",
        success: function (data) {

        }
    });
});

//Cuando se cambia el precio
$(document).on('change', 'input[name="cantidades[]"],input[name="articulos[]"],input[name="precios[]"]', function (e) {
    (obtenerListaArticulos());

});

function obtenerListaArticulos() {
    var articulos = [];
    var total = 0.00;
    $('.grupoArticulo tbody tr').each(function (x, item) {
        var articulo = $(this).find('input[name="articulos[]"]');
        var cantidad = $(this).find('input[name="cantidades[]"]');
        var precio = $(this).find('input[name="precios[]"]');
        var subArtiuclos = [articulo.attr('data-type'), articulo.attr('data-id'), articulo.val()];
        articulos.push([subArtiuclos, cantidad.val(), precio.val()]);
        total += parseFloat(precio.val()) * parseFloat(cantidad.val());
    });

    $('.albaranTotalFix').html(parseFloat(total).toFixed(2));
    return articulos;
}

//Cuando se hace click en el boton de crear albaran
$('#formCrear').validator().on('submit', document, function (e) {

    if (!e.isDefaultPrevented()) {
        var enviado = 0;

        if ($('#enviado').hasClass('selected')) enviado = 1;
        var total = parseFloat($('.albaranTotalFix').text()).toFixed(2);

        $.ajax({
            type: "POST",
            url: BASE_URL+'/albaran/crearAlbaran',
            data: $("#formCrear").serialize() + '&listaArticulos=' + JSON.stringify(obtenerListaArticulos()) + '&clientesID=' + $('#clientesID').val() + '&enviado=' + enviado + '&total=' + total,
            dataType: "json",
            success: function (data) {
                if (data.titulo == "RECARGAR_WEB") {
                    location.reload();
                } else {
                    if (data.enviar == false)
                        mostrarMensaje(data.titulo, data.mensaje, 'danger');
                    else
                        location.href=BASE_URL+"/albaran";
                }
            }
        });
        e.preventDefault();
    }
});

//Cuando se cambia el campo de albaran
$(document).on('change', '#clientesID', function (e) {

    var clientesID = $(this).val();
    $.ajax({
        type: "POST",
        url: BASE_URL+'/albaran/obtenerDatosCliente',
        data: {clientesID: clientesID},
        dataType: "json",
        success: function (data) {
            if ($.isNumeric(clientesID) && data.length != 0) {
                $('input[name=nombre]').val(data.nombre);
                $('input[name=nombreJuridico]').val(data.nombreJuridico);
                $('input[name=apellidos]').val(data.apellidos);
                $('input[name=nif]').val(data.nif);
            } else {
                $('input[name=nombre]').val('');
                $('input[name=nombreJuridico]').val('');
                $('input[name=apellidos]').val('');
                $('input[name=nif]').val('');
                $('input[name=articulos]').val('');
            }
        }
    });

    $('#formCrear').focus();
    $('#formCrear').validator('update');
    $('#formModificar').validator('update');
    $('#formCrear').blur();
});

//==============================
//Modificacion
//==============================

//Cuando se hace click en el boton de modificar albaran
$('#formModificar').validator().on('submit', document, function (e) {
    $('#formModificar').validator('update');
    if (!e.isDefaultPrevented()) {
        var enviado = 0;
        if ($('#enviado').hasClass("selected")) enviado = 1;
        var total = parseFloat($('.albaranTotalFix').text()).toFixed(2);
        $.ajax({
            type: "POST",
            url: BASE_URL+'/albaran/editarAlbaran',
            data: $("#formModificar").serialize() + '&id=' + $('#btn_editarAlbaran').attr('data-id') + '&listaArticulos=' + JSON.stringify(obtenerListaArticulos()) + '&clientesID=' + $('#clientesID').val() + '&enviado=' + enviado + '&total=' + total,
            dataType: "json",
            success: function (data) {
                if (data.titulo == "RECARGAR_WEB") {
                    location.reload();
                } else {
                    if (data.enviar == false)
                        mostrarMensaje(data.titulo, data.mensaje, 'danger');
                    else
                        location.href=BASE_URL+"/albaran";
                }
            }
        });
        e.preventDefault();
    }
});

//Cuando se hace click en el boton de editar albaranes (modal) (seleccion)
$(document).on('click', '#btn_editarAlbaranes', function (e) {
    var listaIds = [];

    albaranesTabla.rows().every(function (rowIdx, tableLoop, rowLoop) {
        var selected = $(this.node()).find('.selected');
        if (selected.length > 0) {
            listaIds.push(selected.attr('data-id'));
        }
    });

    $.ajax({
        type: "POST",
        url: BASE_URL+'/albaran/EditarAlbaranesFormulario',
        data: $("#formEditarAlbaranes").serialize() + '&ids=' + JSON.stringify(listaIds),
        dataType: "json",
        success: function (data) {
            if (data.enviar == false)
                mostrarMensaje(data.titulo, data.mensaje, 'danger');

            refrescarContadorTiposHTML();
        }
    });

    var lastSearch = albaranesTabla.search();

    setTabla();

    albaranesTabla.search(lastSearch).draw();
    e.preventDefault();
});


//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click', '.estadoTodos,.estado', function (e) {
    refrescarContadorTiposHTML();
    $('.checkboxTabla.selected').removeClass('selected');
    $('.checkboxTabla.selected').toggleClass('fa-check-square', 'fa-square');
    albaranesTabla.search($(this).attr('data-search')).draw();

});
//Cuando se hace click en el boton de borrar empleado (se abre el modal)
$(document).on('click', '.estadoTodos', function (e) {
    albaranesTabla.search('').draw();
});

function refrescarContadorTiposHTML() {
    $.ajax({
        type: "POST",
        url: BASE_URL+'/albaran/contadorDeTiposHTML',
        dataType: "html",
        success: function (data) {
            $('#refreshContadorTipos').html(data);
        }
    });
    comprobarBtnAccionesMultiples();
    comprobarSeleccionarTodos();
}
