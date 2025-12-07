$("#contenidoGenerico").ready(function (e) {});
$(window).on("load", function (e) {});
$("body").off('click', '#agregar-importe').on('click', '#agregar-importe', function (e) {
    let _importe = $("#importe-contable").val();
    let _cuenta_id = $("#aCuenta :selected").val();
    let _cuenta_descripcion = $("#aCuenta :selected").text();
    let _cuentasContablesConcatenadas = _cuenta_id + "^" + _importe;
    let _row="";
    _row += "<tr>";
    _row += "    <td><input type='hidden' id='linea_cc' name='linea_cc' value='" + _cuentasContablesConcatenadas + "'></td>";
    _row += "    <td><b>" + _cuenta_descripcion + "</b></td>";
    _row += "    <td align='right'>$" + _importe + "</td>";
    _row += "    <td><input class='valor_importe_contable' type='hidden' id='importe_movimiento_caja' value='" + _importe + "'></td>";
    _row += "    <td><input type='hidden' id='cuenta_movimiento_caja' value='" + _cuenta_id + "'></td>";
    _row += "    <td style='cursor:hand;' onclick='javascript:eliminaItem_CEM($(this));'>";
    _row += "        <img src='./assets/img/media/imagenes/delete_small.png'>";
    _row += "    </td>";
    _row += "</tr>";
    $('table#tbl_lista_importes > tbody:last-child').append(_row); // agrega ultima fila aunque no tenga filas 
    let _ccPagadadasAnterior="";
    if ( !($('#cuentasContablesPagadas').val() == null || $('#cuentasContablesPagadas').val() == 'undefined') ) {_ccPagadadasAnterior = $('#cuentasContablesPagadas').val();}
    $('#cuentasContablesPagadas').val(_ccPagadadasAnterior+"~"+_cuentasContablesConcatenadas);
    $("input[name='itemsPagadosCC']").val(_ccPagadadasAnterior+"~"+_cuentasContablesConcatenadas);
    TotalizarValoresMovCaja_CEM();
});
function eliminaItem_CEM(_item) {
    if(confirm('Confirma?')){
        let id = _item[0].id; 
        let tag = _item[0].tagName;
        let _tr = _item.parent('tr:first');
        _tr.remove();
        TotalizarValoresMovCaja_CEM(); 
        let _x = "";
        $("table#tbl_lista_importes > tbody input[name='linea_cc']").each((idx, elem)=>{_x+="~"+elem.val();});
        if (_x.substring(0,1)=="~") {_x=_x.substring(1);}
        $('#cuentasContablesPagadas').val(_x);
        $("input[name='itemsPagadosCC']").val(_x);
    }
}
function CabeceraOP_CEM(datos) {
    // Ya una parte de los datos los tengo en el abm.php
    
    //alert("Pasando por CabeceraOP_CEM");
    let _temp=""; 
    _temp += "<div id='divTitle'>";
    _temp += "<p><b>Cuentas e importes a registrar</b></p>";
    _temp += "</div>";
    _temp += "<div id='div_cuenta_contable'>";
    _temp += "<table id='tbl_lista_importes' cellpadding='1' class='table table-condensed'>";
    _temp += "  <input type='hidden' id='cuentasContablesPagadas' name='cuentasContablesPagadas' value=''>";
    _temp += "  <thead>";
    _temp += "      <tr bgcolor='silver'>";
    _temp += "          <td></td>";
    _temp += "          <td><b>Cuenta contable</b></td>";
    _temp += "          <td align='right'><b>Importe</b></td>";
    _temp += "          <td></td>";
    _temp += "          <td></td>";
    _temp += "          <td></td>";    
    _temp += "      </tr>";
    _temp += "  </thead>";
    _temp += "  <tbody>";
    _temp += "  </tbody>";
    _temp += "</table>";
    _temp += "</div>";
    _temp += "<div id='div_cuenta_corriente'></div>";
    $('#detalleDeValores').append(_temp); 
}
function botonGuardarOP() {
    return; // me voy para que siga el otro evento....deberia sacar este...

    ///////////////////
    // NO ESTA TERMINADO DE IMPLEMENTAR; PERO SE USA EL EVENTO CLICK MAS QUE ESTE METODO
    ///////////////////

    

    // Armo los valores recibidos
    var valores_recibidos="";
    $("input[name='linea_valor']").each((idx, elem)=>{
        let item = elem.value;
        let item2 = $(elem).val();
        valores_recibidos += "~"+$(elem).val();
     });

     if (valores_recibidos.substring(0,1)=="~") {
        valores_recibidos = valores_recibidos.substring(1); // quito el ~ inicial
     }
     $('#valores_recibidos').val(valores_recibidos);

     // Cargo variables
     var ID_EmpresaSucursal = $('#ID_EmpresaSucursal').val();
     var id_cliente = $('#id_cliente').val();
     var Nro_Recibo_Provisorio = $('#Nro_Recibo').val();
     var Fecha_Caja = $('#Fecha_Caja').val();
     var Fecha_Emision = $('#Fecha_Emision').val();
     var Fecha_Contable = $('#Fecha_Contable').val();
     var ImporteTotalRecibo = $('#valores_total').val();
     var Conciliacion = $('#Conciliacion').val(); 
     var Observaciones = $('#Observaciones').val();
     // Estado ??
     var ID_Caja_Tesoreria = $('#ID_Caja_Tesoreria').val();
     //var comprobantes_pagados = $('#comprobantes_pagados').val();
     var comprobantes_pagados = $('#itemsPagadosCC').val(); // Items de la CC
     var valores_recibidos = $('#valores_recibidos').val(); // Valores Recibidos // div id=#div_lista_comprobantes -> array name=linea_valor
     var ImporteACuenta = $('#importe_a_cuenta').val();
     var id_pagador = $('#id_pagador').val();
     var username = $('#username').val(); ///////////
     var idRecibo = $('#idRecibo').val(); /// Este se va a generar

     if (parseInt(ImporteACuenta) != 0) {
         alert("¡No pueden generarse órdenes de pago con importe a cuenta!");
         return false;
     }

     param={ID_EmpresaSucursal: ID_EmpresaSucursal, id_cliente: id_cliente, Nro_Recibo_Provisorio: Nro_Recibo_Provisorio, 
            Fecha_Caja: Fecha_Caja, Fecha_Emision: Fecha_Emision, Fecha_Contable: Fecha_Contable, ImporteTotalRecibo: ImporteTotalRecibo, 
            Conciliacion: Conciliacion, Observaciones: Observaciones, ID_Caja_Tesoreria: ID_Caja_Tesoreria, 
            comprobantes_pagados: comprobantes_pagados, valores_recibidos: valores_recibidos, ImporteACuenta: ImporteACuenta,
            id_pagador: id_pagador, username: username, idRecibo: idRecibo};
     //_FUNCTIONS.OnGenerarOrdenDePago(null, param); // Implementar
}
$("body").off("click", ".apretarBotonOP").on("click", ".apretarBotonOP", function (e) {
    var valores_entregados="";
    $("input[name='linea_valor']").each((idx, elem)=>{
        let item = elem.value;
        let item2 = $(elem).val();
        valores_entregados += "~"+$(elem).val();
    });
    if (valores_entregados.substring(0, 1) == "~") { valores_entregados = valores_entregados.substring(1); }
    $('#valores_entregados').val(valores_entregados); // EF:  TC:
    let inp = $("input[name='itemsPagadosCC']");
    var itemsPagadosCC="";
    $("input[name='itemsPagadosCC']").each((idx, elem)=>{
        let item = $(elem).val();
        if (item.substring(0, 1) == "~") { item = item.substring(1); }
        itemsPagadosCC += "~"+item;
    });
    if (itemsPagadosCC.substring(0, 1) == "~") { itemsPagadosCC = itemsPagadosCC.substring(1); }
    $('#itemsPagadosCC').val(itemsPagadosCC); // EF:  TC:
    var ID_EmpresaSucursal = $('#aEmpresa').val();
    var ID_Caja_Tesoreria = $('#aCaja').val();
    var id_Proveedor = null; //$('#id_cliente').val();
    var Fecha_Pago = $('#TB-aFechaPago').val();
    var Fecha_Emision = $('#TB-aFechaEmision').val();
    var ImporteTotalOP = $('#valores_total').val();
    var Observaciones = $('#TB-aComentario').val();
    var cuentas_pagadas = $('#itemsPagadosCC').val(); // Items de la CC
    var valores_entregados = $('#valores_entregados').val(); // Valores Entregados EF TC etc  // div id=#div_lista_comprobantes -> array name=linea_valor
    if (TotalizarACuenta() != 0) { alert("La OP no puede grabarse con monto a cuenta, debe balancear!");return false; }
    if (id_Proveedor==0 || id_Proveedor=="" || id_Proveedor == undefined || id_Proveedor == 'undefined') {
        id_Proveedor = null;
    }
    param={ID_EmpresaSucursal: ID_EmpresaSucursal, ID_Caja_Tesoreria: ID_Caja_Tesoreria, id_Proveedor: id_Proveedor, 
        Fecha_Pago: Fecha_Pago, Fecha_Emision: Fecha_Emision, ImporteTotalOP: ImporteTotalOP, 
        Observaciones: Observaciones, cuentas_pagadas: cuentas_pagadas, valores_entregados: valores_entregados, 
        idOP: null};
    _FUNCTIONS.OnGenerarOrdenDePago(null, param);
});
function TotalesOP_CEM() {
    let _temp;
    _temp = "<h2>TOTALES</h2><div id='totales' style='border:solid 3px navy;'>";
    _temp += "<table cellpadding='3' id='tbl_totales'>";
    _temp += "<tr valign='top'>";
    _temp += "<td valign='top'><b>Deuda a cobrar</b><input type='hidden' id='deuda_total' name='deuda_total' value='0'></td><td id='td_deuda_total' align='right' valign='top'><h2>$ 0</h2></td>";
    _temp += "<td valign='top'><b>Valores cargados</b><input type='hidden' id='valores_total' name='valores_total' value='0'></td><td id='td_valores_total' align='right' valign='top'><h2>$ 0</h2></td>";
    _temp += "<td valign='top'><b>Importe a cuenta</b><input type='hidden' id='importe_a_cuenta' name='importe_a_cuenta' value='0'></td><td id='td_importe_a_cuenta' align='right' valign='top'><h2>$ 0</h2></td>";
    _temp += "</tr>";
    _temp += "</table>";
    _temp += "<input type='hidden' id='itemsPagadosCC' name='itemsPagadosCC' value=''>";
    _temp += "<input type='hidden' id='valores_entregados' name='valores_entregados' value=''>";
    $('#detalleDeValores').append(_temp);
}
