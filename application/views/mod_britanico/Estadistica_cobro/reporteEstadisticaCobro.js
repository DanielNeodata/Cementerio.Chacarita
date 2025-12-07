function VerPorAvisos() {
    
    let anio =$('#anio').val();
    let mes=$('#mes').val();

    //alert(anio + " " + mes);

    let m = "00";
    m+=mes;
    m=m.substring(m.length-2, m.length);
    parametros = {anio: anio, mes: m};
    _FUNCTIONS.OnGetReporteEstadisticaCobroPorAvisos(null, parametros)
}

function VerPorCobranza(anio, mes, listaCodigos) {
    let m = "00";
    m+=mes;
    m=m.substring(m.length-2, m.length);
    parametros = {anio: anio, mes: m, listaCodigos: listaCodigos};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
}
function VerPorCobranza2(anio, mes) {
    let m = "00";
    m+=mes;
    m=m.substring(m.length-2, m.length);

    let html = "";
    html+="<p><h3>Agrupamiento contable</h3></p>";
    html+="<table cellpadding='3' cellspacing='0'>";
    html+="   <tr>";
    html+="      <td><b>Agrupamiento</b></td>";
    html+="      <td><b>Cobranza</b></td>";
    html+="   </tr>";
    $('#REPORT-CONTAINER').html('');
    $('#REPORT-CONTAINER').append(html);
    
    //VerPorCobranza(anio, mes, "1010,1020,1030,1040,1050,1060,1070,1080,1090,1100,1110");
    let parametros = {anio: anio, mes: m, listaCodigos: "1010,1020,1030,1040,1050,1060,1070,1080,1090,1100,1110", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "1120,1130,1140");
    parametros = {anio: anio, mes: m, listaCodigos: "1120,1130,1140"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "1150,1160,1170,1180,1190,1200,1210,1220,1440,1230");
    parametros = {anio: anio, mes: m, listaCodigos: "1150,1160,1170,1180,1190,1200,1210,1220,1440,1230", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "2010,2020,2030,2040,2050,2060,2070,2080,2090,2100");
    parametros = {anio: anio, mes: m, listaCodigos: "2010,2020,2030,2040,2050,2060,2070,2080,2090,2100", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "2110,2120,2130,2140,2150,2160,2170,2180,2190");
    parametros = {anio: anio, mes: m, listaCodigos: "2110,2120,2130,2140,2150,2160,2170,2180,2190", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "2200,2210,2220,2230,2240,2250,2260");
    parametros = {anio: anio, mes: m, listaCodigos: "2200,2210,2220,2230,2240,2250,2260", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "2270,2280,2290,2300");
    parametros = {anio: anio, mes: m, listaCodigos: "2270,2280,2290,2300", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "3010,3020,3030,3040,5010,5020,5040,5050,5060,5070,5110,5120");
    parametros = {anio: anio, mes: m, listaCodigos: "3010,3020,3030,3040,5010,5020,5040,5050,5060,5070,5110,5120", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "3050,3060,3070,3080,3090,3100,3110,3120,3130,3140,4030,5030");
    parametros = {anio: anio, mes: m, listaCodigos: "3050,3060,3070,3080,3090,3100,3110,3120,3130,3140,4030,5030"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "4010,4020,4050,4060,4070,4080,4090,4100,4110");
    parametros = {anio: anio, mes: m, listaCodigos: "4010,4020,4050,4060,4070,4080,4090,4100,4110", totalizar:"N"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    //VerPorCobranza(anio, mes, "5090, 5100");
    parametros = {anio: anio, mes: m, listaCodigos: "5090, 5100", totalizar:"S"};
    _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);

    html="   <tr><td colspan='3'><hr/></td></tr>";
    html+="   <tr><td colspan='3'><hr/></td></tr>";
    html+="   <tr>";
    html+="      <td></td>";
    html+="      <td>TOTAL</td>";
    html+="      <td align='right' class='totalizador'>$ " + "XXXXX" + "</td>";
    html+="   </tr>";
    html+="</table>";
    $('#REPORT-CONTAINER').append(html);
}

function VerPorCobranzaAsync() {
    // Este es el que se usa
    //https://softwareengineering.stackexchange.com/questions/433640/in-javascript-how-is-awaiting-the-result-of-an-async-different-than-sync-calls

    let anio = $('#anio').val();
    let mes = $('#mes').val();

    let m = "00";
    m += mes;
    m = m.substring(m.length - 2, m.length);

    //alert(anio + " " + mes);
    const task1 = function (anio, mes) {
        let html = "<div class='container'>";
        html += "<div class='row'><h3>Agrupamiento contable</h3></div></div>";
        html += "<table cellpadding='3' cellspacing='0'>";
        html += "   <tr>";
        html += "      <td><b>Agrupamiento</b></td>";
        html += "      <td><b>Cobranza</b></td>";
        html += "   </tr>";
        $('#REPORT-CONTAINER').html('');
        $('#REPORT-CONTAINER').append(html);
    }

    const task2 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "1010,1020,1030,1040,1050,1060,1070,1080,1090,1100,1110");
        let parametros = { anio: anio, mes: m, listaCodigos: "1010,1020,1030,1040,1050,1060,1070,1080,1090,1100,1110", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task3 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "1120,1130,1140");
        parametros = { anio: anio, mes: m, listaCodigos: "1120,1130,1140" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task4 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "1150,1160,1170,1180,1190,1200,1210,1220,1440,1230");
        parametros = { anio: anio, mes: m, listaCodigos: "1150,1160,1170,1180,1190,1200,1210,1220,1440,1230", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task5 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "2010,2020,2030,2040,2050,2060,2070,2080,2090,2100");
        parametros = { anio: anio, mes: m, listaCodigos: "2010,2020,2030,2040,2050,2060,2070,2080,2090,2100", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task6 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "2110,2120,2130,2140,2150,2160,2170,2180,2190");
        parametros = { anio: anio, mes: m, listaCodigos: "2110,2120,2130,2140,2150,2160,2170,2180,2190", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task7 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "2200,2210,2220,2230,2240,2250,2260");
        parametros = { anio: anio, mes: m, listaCodigos: "2200,2210,2220,2230,2240,2250,2260", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task8 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "2270,2280,2290,2300");
        parametros = { anio: anio, mes: m, listaCodigos: "2270,2280,2290,2300", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task9 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "3010,3020,3030,3040,5010,5020,5040,5050,5060,5070,5110,5120");
        parametros = { anio: anio, mes: m, listaCodigos: "3010,3020,3030,3040,5010,5020,5040,5050,5060,5070,5110,5120", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task10 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "3050,3060,3070,3080,3090,3100,3110,3120,3130,3140,4030,5030");
        parametros = { anio: anio, mes: m, listaCodigos: "3050,3060,3070,3080,3090,3100,3110,3120,3130,3140,4030,5030" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task11 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "4010,4020,4050,4060,4070,4080,4090,4100,4110");
        parametros = { anio: anio, mes: m, listaCodigos: "4010,4020,4050,4060,4070,4080,4090,4100,4110", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }

    const task12 = function (anio, mes) {
        //VerPorCobranza(anio, mes, "5090, 5100");
        parametros = { anio: anio, mes: m, listaCodigos: "5090, 5100", totalizar: "N" };
        _FUNCTIONS.OnGetReporteEstadisticaCobroDatosAgrupados(null, parametros);
    }
    $('.totalizador').text(0.0);
    $.when(task1(anio, mes)).then(function () {
        $.when(task2(anio, mes)).then(function () {
            $.when(task3(anio, mes)).then(function () {
                $.when(task4(anio, mes)).then(function () {
                    $.when(task5(anio, mes)).then(function () {
                        $.when(task6(anio, mes)).then(function () {
                            $.when(task7(anio, mes)).then(function () {
                                $.when(task8(anio, mes)).then(function () {
                                    $.when(task9(anio, mes)).then(function () {
                                        $.when(task10(anio, mes)).then(function () {
                                            $.when(task11(anio, mes)).then(function () {
                                                $.when(task12(anio, mes)).then(function () {
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            });
        });
    });
}
$(document).ready(
    acomodar()
);

function acomodar(){
    var d = new Date();
    var anio = d.getFullYear();
    var mes = d.getMonth()+1;
    var selmes = 'select#mes option[value="'+mes+'"]';
    $(selmes).attr("selected",true);
    var selanio = 'select#anio option[value="'+anio+'"]';
    $(selanio).attr("selected",true);    
}
