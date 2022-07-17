var gastosPorCliente;
var gastosPorClienteTabla;

var obrasPorCliente;
var obrasPorClienteTabla;

var horasPorEmpleado;
var horasPorEmpleadoTabla;

var partesPorObra;
var partesPorObraTabla;

var obrasPartes;
var partesTabla;
var obrasTabla;

$(document).ready(function () {
    $(".se-pre-con").show();
    setTimeout(function () {
        actualizarInformes();
        comprobarTablas();
        $(".se-pre-con").fadeOut("slow");
    }, 1000);
});

$(document).on('change', 'select,#fechaInicio,#fechaFinal', function (e) {
    $(".se-pre-con").show();
    actualizarFiltros();
    comprobarTablas();
    $(".se-pre-con").fadeOut("slow");
    e.preventDefault();
});

$(document).on('click', '#exportarPDF', function (e) {
    var doc = new jsPDF('landscape');
    html2canvas(document.querySelector("#listadoInformes")).then(canvas => {
        var width = doc.internal.pageSize.width-2;
        var height = doc.internal.pageSize.height-2;
        var imgData = canvas.toDataURL('image/jpeg');
        doc.addImage(imgData,'PNG',1,1, width, height);
        doc.save('Informes.pdf');
    });
    e.preventDefault();
});

function isEmpty(el) {
    return !$.trim(el.html())
}

function comprobarTablas() {
    var tablasSeleccionadas = $('#tablas').val();
    var graficosSeleccionadas = $('#graficos').val();
      ("tablas: "+tablasSeleccionadas);
      ("graficos: "+graficosSeleccionadas);

    $('#listadoInformes .row').each(function (x) {

        var grafico = $(this).find('.grafico');
        var tabla = $(this).find('.tabla');
        var tabla1 = $(this).find('.tabla1');
        if(~graficosSeleccionadas.indexOf(grafico.attr('data-numero'))) grafico.show(); else grafico.hide();
        if(~tablasSeleccionadas.indexOf(tabla.attr('data-numero'))) tabla.show(); else tabla.hide();

        if(tabla1.length > 0)
            if(~tablasSeleccionadas.indexOf(tabla1.attr('data-numero'))) tabla1.show(); else tabla1.hide();

        if (grafico.is(':hidden')){
            tabla.closest('.col-sm-7').removeClass('col-sm-7').addClass('col-sm-12');
            tabla1.closest('.col-sm-7').removeClass('col-sm-7').addClass('col-sm-12');
        }
        else {
            tabla.closest('.col-sm-12').removeClass('col-sm-12').addClass('col-sm-7');
            tabla1.closest('.col-sm-12').removeClass('col-sm-12').addClass('col-sm-7');
        }
        if(tabla1.length > 0) {
            if (tabla.is(':hidden') && tabla1.is(':hidden')) {
                grafico.closest('.col-sm-5').removeClass('col-sm-5').addClass('col-sm-12');
            } else {
                grafico.closest('.col-sm-12').removeClass('col-sm-12').addClass('col-sm-5');
            }
        }else{
            if (tabla.is(':hidden')){ grafico.closest('.col-sm-5').removeClass('col-sm-5').addClass('col-sm-12');}
            else {grafico.closest('.col-sm-12').removeClass('col-sm-12').addClass('col-sm-5');}
        }
    });
}

function actualizarInformes() {
    obtenerDatosGraficos();
    generarTablas();
}

function actualizarFiltros() {
    $.ajax({
        type: "POST",
        url: BASE_URL+'/informes/actualizarFiltros',
        data: {
            fechaInicio: $('#fechaInicio').val(),
            fechaFinal: $('#fechaFinal').val(),
            tablas: JSON.stringify($('#tablas').val()),
            graficos: JSON.stringify($('#graficos').val())
        },
        dataType: "json",
        success: function (datos) {
            actualizarInformes();
        }
    });
}

function obtenerDatosGraficos() {
    $.ajax({
        type: "POST",
        data: {
            fechaInicio: $('#fechaInicio').val(),
            fechaFinal: $('#fechaFinal').val()
        },
        url: BASE_URL+'/informes/obtenerDatosGraficos',
        dataType: "json",
        success: function (datos) {
            generarGraficos(datos);
        }
    });
}

function generarTablas() {

    //Gastos por cliente
    if (gastosPorClienteTabla != undefined) gastosPorClienteTabla.destroy();
    gastosPorClienteTabla = $('#gastosPorClienteTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerGastosPorClienteTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });
    //Partes por obra
    if (partesPorObraTabla != undefined) partesPorObraTabla.destroy();
    partesPorObraTabla = $('#partesPorObraTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerPartesPorObraTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });
    //Horas por empleado
    if (horasPorEmpleadoTabla != undefined) horasPorEmpleadoTabla.destroy();
    horasPorEmpleadoTabla = $('#horasPorEmpleadoTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerHorasPorEmpleadoTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });
    //Parte por obra
    /*if (horasPorEmpleadoTabla != undefined) horasPorEmpleadoTabla.destroy();
    horasPorEmpleadoTabla = $('#horasPorEmpleadoTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerHorasPorEmpleadoTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });*/

    //Obras por cliente
    if (obrasPorClienteTabla != undefined) obrasPorClienteTabla.destroy();
    obrasPorClienteTabla = $('#obrasPorClienteTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerObrasPorClienteTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });


    //Obras
    if (obrasTabla != undefined) obrasTabla.destroy();
    obrasTabla = $('#obrasTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerObrasTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });

    //Partes
    if (partesTabla != undefined) partesTabla.destroy();
    partesTabla = $('#partesTabla').DataTable({
        "ajax": {
            data: {
                fechaInicio: $('#fechaInicio').val(),
                fechaFinal: $('#fechaFinal').val()
            },
            dataType: "json",
            url: BASE_URL+"/informes/obtenerPartesTabla",
            type: 'POST'
        },
        "order": [[0, "desc"]],
        language: {
            "url": BASE_URL+"/assets/components/datatables/Spanish.json"
        }
    });

}

function generarGraficos(datos) {

    var datGastosPorCliente = datos.datGastosPorCliente;
    var datObrasPorCliente = datos.datObrasPorCliente;
    var datObras = datos.datObras;
    var datPartes = datos.datPartes;
    var datHorasPorEmpleado = datos.datHorasPorEmpleado;
    var datPartesPorObra = datos.datPartesPorObra;

    //Gastos por cliente
    if (gastosPorCliente != undefined) gastosPorCliente.destroy();
    gastosPorCliente = Highcharts.chart('gastosPorCliente', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Gastos por Cliente'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y:.2f}€ {point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y:.2f}€ ({point.percentage:.1f}%)',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Clientes',
            colorByPoint: true,
            data: datGastosPorCliente
        }],
        credits: {
            enabled: false
        }
    });

    //Partes por obra
    if (partesPorObra != undefined) partesPorObra.destroy();
    partesPorObra = Highcharts.chart('partesPorObra', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Partes por Obra'
        },
        tooltip: {
            pointFormat: '<b>{point.y} partes {point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y} partes ({point.percentage:.1f}%)',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Obras',
            colorByPoint: true,
            data: datPartesPorObra
        }],
        credits: {
            enabled: false
        }
    });

    //Horas por empleado
    if (horasPorEmpleado != undefined) horasPorEmpleado.destroy();
    horasPorEmpleado = Highcharts.chart('horasPorEmpleado', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Horas por empleado'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y} horas ({point.percentage:.1f}%)</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y} horas ({point.percentage:.1f}%)',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Empleados',
            colorByPoint: true,
            data: datHorasPorEmpleado
        }],
        credits: {
            enabled: false
        }
    });

    //Obras partes por dia
    if (obrasPartes != undefined) obrasPartes.destroy();
    obrasPartes = Highcharts.chart('obrasPartes', {
        title: {
            text: 'Obras y Partes por día'
        },
        tooltip: {
            pointFormat: '<b>{point.y} {series.name}</b>'
        },
        plotOptions: {
        },
        xAxis: {
            type: 'datetime',
            labels: {
                format: '{value:%d/%m/%Y}'
            }
        },
        yAxis: {
            type: 'int',
            title: {
                text: 'Cantidad'
            },
            labels: {
                format: '{value}'
            }
        },
        series: [{
            name: 'Obras',
            data: datObras
        },{
            name: 'Partes',
            data: datPartes
        }],
        credits: {
            enabled: false
        }
    });

    //Obras por cliente
    if (obrasPorCliente != undefined) obrasPorCliente.destroy();
    obrasPorCliente = Highcharts.chart('obrasPorCliente', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Obras por Cliente'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>x{point.y} {point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: x{point.y} ({point.percentage:.1f}%)',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Obras',
            colorByPoint: true,
            data: datObrasPorCliente
        }],
        credits: {
            enabled: false
        }
    });
}