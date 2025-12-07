// Apago todo
$('.btnA').hide(); // Desde iniciar hasta terminar por abandono
$('.btnB').hide(); // Devolver o Terminar por devolucion
$('.btnX').hide(); // Suspender

// prendo los default. Despues los datos acomodaran si corresponde
$('#A_1').show();
$('#B_1').show();
$('.btnX').hide();

// Obtengo los parametros y busco el estado del desenganche. Como resultado del Ajax prendo los botones
// que corresponden a la maquina de estados, las transiciones permitidas.
parametros = {id_parcela: $('.btn-abm-accept').attr('data-id') , id_cliente: $('#id_cliente').attr('data-id')};
_FUNCTIONS.OnGetEstadoDesenganche(null, parametros);

//	Estado Desenganche:
//	A1 Inicio Abandono/Devolucion   btnA
//	A2 Envio Carta                  btnA
//	A3 Recepcion Carta              btnA
//	A4 Acta Comite                  btnA
//	A5 Terminado por Devolucion     btnB
//	B1 Terminado por Abandono       btnA
//	B5 Devolucion Parcela           btnB
//	--> Suspender y Revertir proceso de Abandono btnX

//	Transiciones:
//	A1 -> A2
//	A2 -> A3
//	A3 -> A4
//	A4 -> A5
//	B1 -> B5
//	case Default    A1
//					B1
//					btnX

function RevertirDesenganche_CEM(_id_parcela,_id_cliente) {
    $('.btnA').hide();
    $('.btnB').hide();
    $('#A_1').show();
    $('#B_1').show();
    $('.btnX').hide();
    let param = {modo: "X" , id_parcela: _id_parcela, id_cliente: _id_cliente};
    _FUNCTIONS.OnSetearDesengancheParcela(null, param);
}
function PresentacionPersonal_CEM(_i,_id_parcela,_id_cliente) {
    $('.btnA').hide();
    $('.btnB').hide();
    $('.btnX').hide();
    let param = {modo: "B"+_i , id_parcela: _id_parcela, id_cliente: _id_cliente};
    _FUNCTIONS.OnSetearDesengancheParcela(null, param);

    switch(_i) {
        case 1:
            $('.btnX').show();
            $('#B_5').show();
            break;
        case 5:
            $('.btnX').show();
            $('#id_cliente').val('');
            $('#id_pagador').val('');
            $('#cliente_pagador').val('');
            $('input:radio[name=Disponible][value=S]').click();
            alert('Debe seleccionar un estado luego de finalizar el proceso de desenganche!');
            $('#ClienteCategoria_0').removeAttr('checked');
            $('#ClienteCategoria_1').removeAttr('checked');
            $('#ClienteCategoria_2').removeAttr('checked');
            $('.borrarcliente').click();
            break;
    }
}
function Abandono_CEM(_i, _id_parcela, _id_cliente) {
    $('.btnA').hide();
    $('.btnB').hide();
    $('.btnX').hide();
    let param = {modo: "A"+_i , id_parcela: _id_parcela, id_cliente: _id_cliente};
    _FUNCTIONS.OnSetearDesengancheParcela(null, param);

    switch(_i) {
        case 1:
            $('.btnX').show();
            $('#A_2').show();
            break;
        case 2:
            $('.btnX').show();
            $('#A_3').show();
            break;
        case 3:
            $('.btnX').show();
            $('#A_4').show();
            break;
        case 4:
            $('.btnX').show();
            $('#A_5').show();
            break;
        case 5:
            $('.btnX').show();
            //$('#msg_cliente_pagador').html('');
            $('#id_cliente').val('');
            $('#id_pagador').val('');
            $('#cliente_pagador').val('');
            $('input:radio[name=Disponible][value=S]').click();
            alert('Debe seleccionar un estado luego de finalizar el proceso de desenganche!');
            $('#ClienteCategoria_0').removeAttr('checked');
            $('#ClienteCategoria_1').removeAttr('checked');
            $('#ClienteCategoria_2').removeAttr('checked');
            //$('#btnOK').click();

            $('.borrarcliente').click();
             

            break;
    }
}
function seleccionClienteCategoria(){
    let seleccionado = $('input[type="radio"][name="SelClienteCategoria"]:checked').attr('id');
    let ClienteCategoria = "";
    ClienteCategoria = $('input[type="radio"][name="SelClienteCategoria"]:checked').val(); // N J C A

    if (!(ClienteCategoria == "N" || ClienteCategoria == "J" || ClienteCategoria == "C" || ClienteCategoria == "A") ) {
        alert("Debe seleccionar un estado para la parcela (N / J / C / A)");
    } else {
        $('input#ClienteCategoria').val(ClienteCategoria);
    }
}