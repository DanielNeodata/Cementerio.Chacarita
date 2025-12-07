var timeoutId = 0;
$("body").off("keyup", "#entradaPagadorRecibo").on("keyup", "#entradaPagadorRecibo", function (e) {
    clearTimeout(timeoutId) // doesn't matter if it's 0
    timeoutId = setTimeout(onKeyUpEntradaPagadorRecibo, 800);
});

function botonGuardarRecibo() {
    var valores_recibidos="";
    $("input[name='linea_valor']").each((idx, elem)=>{
        let item = elem.value;
        let item2 = $(elem).val();
        valores_recibidos += "~"+$(elem).val();
     });
    if (valores_recibidos.substring(0, 1) == "~") { valores_recibidos = valores_recibidos.substring(1); }
     $('#valores_recibidos').val(valores_recibidos);
     var ID_EmpresaSucursal = $('#ID_EmpresaSucursal').val();
     var id_cliente = $('#id_cliente').val();
     var Nro_Recibo_Provisorio = $('#Nro_Recibo').val();
     var Fecha_Caja = $('#Fecha_Caja').val();
     var Fecha_Emision = $('#Fecha_Emision').val();
     var Fecha_Contable = $('#Fecha_Contable').val();
     var ImporteTotalRecibo = $('#valores_total').val();
     var Conciliacion = $('input[name="Conciliacion"]:checked').val(); 
     var Observaciones = $('#Observaciones').val();
     var ID_Caja_Tesoreria = $('#ID_Caja_Tesoreria').val();
     var comprobantes_pagados = $('#itemsPagadosCC').val(); // Items de la CC
     var valores_recibidos = $('#valores_recibidos').val(); // Valores Recibidos // div id=#div_lista_comprobantes -> array name=linea_valor
     var ImporteACuenta = $('#importe_a_cuenta').val();
     var id_pagador = $('#id_pagador').val();
     var username = $('#username').val(); ///////////
     var idRecibo = $('#idRecibo').val(); /// Este se va a generar
     if (ID_EmpresaSucursal == 'null' || ID_EmpresaSucursal == 'undefined' || ID_EmpresaSucursal == 0 ||
     ID_Caja_Tesoreria == 'null' || ID_Caja_Tesoreria == 'undefined' || ID_Caja_Tesoreria == 0  ) {
         alert("Revise los datos de Empresa o Caja/Tesoreria, no pueden estar vacios");
         return ;
     }
     if (id_cliente == 'null' || id_cliente == 'undefined' || id_cliente == 0 ||
     id_pagador == 'null' || id_pagador == 'undefined' || id_pagador == 0 ) {
         alert("Revise los datos de Cliente/Pagador, no puede estar vacio");
         return ;
     }
     if ( Fecha_Caja == 'null' || Fecha_Caja == 'undefined' || Fecha_Caja == '' ||
            Fecha_Emision == 'null' || Fecha_Emision == 'undefined' || Fecha_Emision == '' ||
            Fecha_Contable == 'null' || Fecha_Contable == 'undefined' || Fecha_Contable == '' ) {
         alert("Las fechas de Caja, Emisión y Contable no pueden estar vacías.");
         return ;
     } 
     if ( comprobantes_pagados == 'null' || comprobantes_pagados == 'undefined' || comprobantes_pagados == '' ) {
        alert("Debe seleccionar items a pagar");
        return ;
    } 
    if ( valores_recibidos == 'null' || valores_recibidos == 'undefined' || valores_recibidos == '' ) {
        alert("Debe indicar con que valores realiza el pago.");
        return ;
    } 
    if (Nro_Recibo_Provisorio == 'null' || Nro_Recibo_Provisorio == 'undefined' || Nro_Recibo_Provisorio == "" ) {Nro_Recibo_Provisorio=0;}
    if (Conciliacion == 'null' || Conciliacion == 'undefined' || Conciliacion == "" ) {Conciliacion='N';}
    var _d =($('#deuda_total').val()*1);
    var _v=($('#valores_total').val()*1);
    if (_d==0 || _d=='undefined'||_d=='null') {
        alert("Selecciones los items de deuda a saldar, la deuda no puede ser cero");
        return;
    }
    if (_v==0 || _v=='undefined'||_v=='null') {
        alert("Ingrese los valores con los que se paga la deuda");
        return;
    }
    var _a=(_v -_d );
    if (_a!=0 || _a=='undefined'||_a=='null') {
        alert("La deuda seleccionada debe ser igual a los valores con los que paga");
        return;
    }
     param={ID_EmpresaSucursal: ID_EmpresaSucursal, id_cliente: id_cliente, Nro_Recibo_Provisorio: Nro_Recibo_Provisorio, 
            Fecha_Caja: Fecha_Caja, Fecha_Emision: Fecha_Emision, Fecha_Contable: Fecha_Contable, ImporteTotalRecibo: ImporteTotalRecibo, 
            Conciliacion: Conciliacion, Observaciones: Observaciones, ID_Caja_Tesoreria: ID_Caja_Tesoreria, 
            comprobantes_pagados: comprobantes_pagados, valores_recibidos: valores_recibidos, ImporteACuenta: ImporteACuenta,
            id_pagador: id_pagador, username: username, idRecibo: idRecibo};
     _FUNCTIONS.OnGenerarRecibo(null, param);
}
$("body").off("click", ".apretarBoton").on("click", ".apretarBoton", function (e) {
    botonGuardarRecibo();
});

function cargarDatosPagadorRecibo(id, cli, pag){
    var dataid = $(this).attr('data-id');
    _id = '#cliente'+id;
    var detalle = $(_id).text();
    idcliente=cli;
    idpagador=pag;
    $('#entradaPagadorRecibo').val(detalle);
    $('#id_cliente').val(idcliente);
    $('#id_pagador').val(idpagador);
    $('#resultadoPagadorRecibo').html(''); // limpio para achicar el espacio de la pantalla
    _FUNCTIONS.onGetCuentaCorriente(null, "IMPAGA", idcliente, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'true', 'false');
}
$("body").off("click", "#resultadoPagadorRecibo div").on("click", "#resultadoPagadorRecibo div", function (e) {
    alert("yo");
    alert("click en entradaPagadorRecibo div");
    var dataid = $(this).attr('data-id');
    var detalle = $(this).text();
    var idcliente = dataid.split('|')[0];
    var idpagador = dataid.split('|')[1];
    $('#entradaPagadorRecibo').val(detalle);
    $('#id_cliente').val(idcliente);
    $('#id_pagador').val(idpagador);
    $('#resultadoPagadorRecibo').html(''); // limpio para achicar el espacio de la pantalla
    _FUNCTIONS.onGetCuentaCorriente( null, "COMPLETA", idcliente, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'false');
});
function VerDetallesDeuda_CEM(idcliente, titulo){
    alert("VerDetallesDeuda_CEM");
	$('#contenidoGenerico').attr('data-id', idcliente);
	$('#contenidoGenerico').attr('data-titulo', titulo);
    _FUNCTIONS.onGetCuentaCorriente( null, "COMPLETA", idcliente, 'CC desde ReC', '#div_cuenta_corriente');
}
function IsNumeric(sText)
  {
   var ValidChars="0123456789.-";
   var IsNumber=true;
   var Char;
   for (i=0;i<sText.length&&IsNumber==true;i++) 
      { 
         Char=sText.charAt(i); 
         if(ValidChars.indexOf(Char)==-1){IsNumber=false;}
      }
   return IsNumber;
  }
function onKeyUpEntradaPagadorRecibo (_this){
    let searchKey = $('#entradaPagadorRecibo').val();
    $('#resultadoPagadorRecibo').html('');	
    if(searchKey.length >= 2){
        data = {searchKey: searchKey};
        var myObj = data;
        _AJAX.UiGetClientePagadorParcela(myObj).then(function (datajson) {
            let vuelta = datajson.clientePagadorParcela;
            let res='';
            for(let i=0;i<vuelta.length;i++){
                res += '<div class="" data-id="'+vuelta[i].id+'" data-id-cliente="'+ (vuelta[i].id).split("|")[0] +'" data-id-pagador="'+ (vuelta[i].id).split("|")[1] +'" id="'+'cliente'+i+'" onClick="javascript:cargarDatosPagadorRecibo('+ i + ','+ (vuelta[i].id).split("|")[0] + ','+ (vuelta[i].id).split("|")[1]+')">'+vuelta[i].detalle+'</div>';
            }
            $('#resultadoPagadorRecibo').html(res);
            $('#resultadoPagadorRecibo').fadeIn(1000);
        });			
    }
};
function SetChildCombo(iVal,_combo)
{
    try
        {
        var oCbo=attach(_combo);
        oCbo.options.length=0;
        var opt = window.document.createElement('option');
        opt.value = 0;
        opt.text = '[Seleccione]';
        oCbo.options.add(opt);
        var x;
        var _array=eval('_array_' + _combo);
        for (i=0;i<_array.length;i++) 
            {
                var _array2=_array[i];
                if (_array2[2]*1==iVal*1)
                {
                    var opt2 = window.document.createElement('option');
                    opt2.value = _array2[0];
                    opt2.text = _array2[1];
                    oCbo.options.add(opt2);
                }   
            }
        }
    catch(err){}
}
function getCombo_Plain(datos, Combo_ID, Field_Value, Field_Text, Combo_Default_Value, bEmptyItem, bAllItem, Client_OnChange) {
    var _sb = "";
    var sSel = "";
    var sVal = "";
    var _onchange = "";
    var _s_zero = "";
    _onchange = (" onchange=" + Client_OnChange);
    _sb += "<SELECT class='form-control' id='" + Combo_ID + "' name='" + Combo_ID + "' " + _onchange + ">";
    if (bEmptyItem == 'true') { _sb += "<option selected value='0'>" + _s_zero + "</option>"; }
    if (bAllItem == 'true') { _sb += "<option selected value='empty'>" + GetString("strSelectComboAllOptions") + "</option>"; }
    $.each(datos, (index, item) => {
        sVal = item[Field_Value];
        if (Combo_Default_Value == sVal) {
            sSel = " selected ";
        } else {
            sSel = "";
        }
        _sb += "<option " + sSel + " value='" + sVal + "'>" + item[Field_Text] + "</option>";
    });
    _sb += "</SELECT>";
    return _sb;
}
function getCombo_Plain_Array (datos, Combo_ID, Field_Value, Field_Text, Combo_Default_Value, bEmptyItem, bAllItem, Client_OnChange, _ID_Empresa_Sucursal) {
    var _tmp="";
    _tmp+=getCombo_Plain(datos, Combo_ID, Field_Value, Field_Text, Combo_Default_Value, bEmptyItem, bAllItem, Client_OnChange);
    if (_ID_Empresa_Sucursal!='undefined'&&_ID_Empresa_Sucursal!='null'&&_ID_Empresa_Sucursal!=0) {
        _tmp+="<script>var _array_ID_Caja_Tesoreria=new Array(new Array('1','Caja y banco','"+ _ID_Empresa_Sucursal +"'),new Array('2','Fondo fijo','"+ _ID_Empresa_Sucursal +"'));</script>";
    } else {
        _tmp+="<script>var _array_ID_Caja_Tesoreria=new Array(new Array('1','Caja y banco','1'),new Array('2','Fondo fijo','1'));</script>";
    }
    return _tmp;
}
function AddTarjeta_Credito_CEM()
{
   var _linea='';
   var _importe=$('#tarjeta_credito_importe').val();
  _linea="<tr>";
  var _sVal="TC:"+_importe;
  _linea+="<td><input type='hidden' id='linea_valor' name='linea_valor' value='"+_sVal+"'></td>";
  _linea+="<td><b>Tarjeta de crédito</b></td>";
  _linea+="<td align='right'>$ " + _importe + "</td>";
  _linea+="<td><input class='valor_importe' class='form-control' type='hidden' id='comprobante_tarjeta_credito' value='" + _importe + "'></td>";
  _linea+="<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
  _linea+="</tr>";
  $('#tbl_lista_comprobantes').append(_linea);
  $('#tarjeta_credito_importe').val('');
  TotalizarValores_CEM();
}
function AddTarjeta_Debito_CEM()
{
   var _linea='';
   var _importe=$('#tarjeta_debito_importe').val();
  _linea="<tr>";
  var _sVal="TD:"+_importe;
  _linea+="<td><input type='hidden' id='linea_valor' name='linea_valor' value='"+_sVal+"'></td>";
  _linea+="<td><b>Tarjeta de débito</b></td>";
  _linea+="<td align='right'>$ " + _importe + "</td>";
  _linea+="<td><input class='valor_importe' type='hidden' id='comprobante_tarjeta_debito' value='" + _importe + "'></td>";
  _linea+="<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
  _linea+="</tr>";
  $('#tbl_lista_comprobantes').append(_linea);
  $('#tarjeta_debito_importe').val('');
  TotalizarValores_CEM();
}
function AddEfectivo_CEM()
{
   var _linea='';
   var _importe=$('#efectivo_importe').val();
   var _tipo=$("#div_efectivo input[type='radio']:checked").val();
   if ($('#tbl_lista_comprobantes').html().indexOf("Efectivo")==-1)
   {
      _linea="<tr>";
      var _sVal="EF:"+_importe+"^"+_tipo;
      _linea+="<td><input type='hidden' id='linea_valor' name='linea_valor' value='"+_sVal+"'></td>";
      _linea+="<td><b>Efectivo</b></td>";
      _linea+="<td align='right'>$ " + _importe + "</td>";
      _linea+="<td>";
      _linea+="<input class='valor_importe' type='hidden' id='comprobante_efectivo' value='" + _importe + "'>";
      _linea+="<input type='hidden' id='efectivo_tipo' value='" + _tipo + "'>";
      _linea+="</td>";
      _linea+="<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
      _linea+="</tr>";
      $('#tbl_lista_comprobantes').append(_linea);
      $('#efectivo_importe').val('');
      TotalizarValores_CEM();
   }
   else
   {
      alert('Ya existe un comprobante de efectivo para este recibo');
   }   
}
function AddCheque_CEM()
{
   var _linea='';
   var _importe=$('#cheque_importe').val()*1;
   var _banco=$('#id_banco_cheque').val()*1;
   var _numero=$('#cheque_numero').val();
   var _fecha_emision=$('#cheque_fecha_emision').val();
   var _fecha_vencimiento=$('#cheque_fecha_vencimiento').val();
   var _tipo=$("#div_cheque input[type='radio']:checked").val();
   var _orden="";
   var _html=$("#tbl_lista_comprobantes").html();
   if($('#cheque_orden').is(":checked")){_orden="S";}else{_orden="N";};
   if(_html.indexOf(_numero)!=-1)
   {
      alert('Ya se ha cargado este nº de cheque');
      return;
   }
   
   if (_importe==0 || _banco==0 || _numero=='' || _fecha_emision=='' || _fecha_vencimiento=='' || _tipo==undefined)
   {
      alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Importe\n - Banco\n - Nº de cheque\n - Fecha de emisión\n - Fecha de depósito\n - Tipo de cheque');
   }   
   else
   {
      _linea="<tr>";
       var _sVal = "CH:" + _importe + "^" + _banco + "^" + _numero + "^" + _TOOLS.reformatDate(_fecha_emision) + "^" + _TOOLS.reformatDate(_fecha_vencimiento) +"^"+_tipo+"^"+_orden;
      _linea+="<td><input type='hidden' id='linea_valor' name='linea_valor' value='"+_sVal+"'></td>";
      _linea+="<td><b>Cheque / " + _numero + " " + $("#id_banco_cheque option:selected").text() + "</b></td>";
      _linea+="<td align='right'>$ " + _importe + "</td>";
      _linea+="<td>";
      _linea+="<input class='valor_importe' type='hidden' id='comprobante_cheque_importe' value='" + _importe + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_banco' value='" + _banco + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_numero' value='" + _numero + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_fecha_emision' value='" + _fecha_emision + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_fecha_vencimiento' value='" + _fecha_vencimiento + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_tipo' value='" + _tipo + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_orden' value='" + _orden + "'>";
      _linea+="</td>";
      _linea+="<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
      _linea+="</tr>";
      $('#tbl_lista_comprobantes').append(_linea);
      $('#cheque_importe').val('');
      //$('#id_banco_cheque').val('0');
      $('#cheque_numero').val('');
      //$('#cheque_fecha_emision').val('');
      //$('#cheque_fecha_vencimiento').val('');
      TotalizarValores_CEM();
   }
}
function TotalizarChequeExtranjeroCEM() {
    var _i1 = $('#cheque_extranjero_importe').val();
    var _i2 = $('#cheque_extranjero_cotizacion').val();
    if (_i1 == '') { _i1 = 0; }
    if (_i2 == '') { _i2 = 0; }
    _i1 = (_i1 * 1);
    _i2 = (_i2 * 1);
    _total = (_i1 * _i2);
    _total = Math.round(_total * 100) / 100;
    $('#cheque_extranjero_total').val(_total);
    $('#td_cheque_extranjero_total').html(_total);
}
function TotalizarChequeExtranjeroCEM2() {
    var _i1 = $('#cheque_extranjero_importe2').val();
    var _i2 = $('#cheque_extranjero_cotizacion2').val();
    if (_i1 == '') { _i1 = 0; }
    if (_i2 == '') { _i2 = 0; }
    _i1 = (_i1 * 1);
    _i2 = (_i2 * 1);
    _total = (_i1 * _i2);
    _total = Math.round(_total * 100) / 100;
    $('#cheque_extranjero_total2').val(_total);
    $('#td_cheque_extranjero_total2').html(_total);
}
function TotalizarMonedaExtranjeraCEM() {
    var _i1 = $('#moneda_extranjera_importe').val();
    var _i2 = $('#moneda_extranjera_cotizacion').val();
    if (_i1 == '') { _i1 = 0; }
    if (_i2 == '') { _i2 = 0; }
    _i1 = (_i1 * 1);
    _i2 = (_i2 * 1);
    _total = (_i1 * _i2);
    _total = Math.round(_total * 100) / 100;
    $('#moneda_extranjera_total').val(_total);
    $('#td_moneda_extranjera_total').html(_total);
}
function TotalizarMonedaExtranjeraCEM2() {
    var _i1 = $('#moneda_extranjera_importe2').val();
    var _i2 = $('#moneda_extranjera_cotizacion2').val();
    if (_i1 == '') { _i1 = 0; }
    if (_i2 == '') { _i2 = 0; }
    _i1 = (_i1 * 1);
    _i2 = (_i2 * 1);
    _total = (_i1 * _i2);
    _total = Math.round(_total * 100) / 100;
    $('#moneda_extranjera_total2').val(_total);
    $('#td_moneda_extranjera_total2').html(_total);
}
function AddChequeExtranjero_CEM()
{
   var _linea='';
   var _importe=$('#cheque_extranjero_importe').val()*1;
   var _banco=$('#id_banco_cheque_extranjero').val()*1;
   var _numero=$('#cheque_extranjero_numero').val();
   var _fecha_emision=$('#cheque_extranjero_fecha_emision').val();
   var _fecha_vencimiento=$('#cheque_extranjero_fecha_vencimiento').val();
   var _cotizacion=$('#cheque_extranjero_cotizacion').val()*1;
   var _total=$('#cheque_extranjero_total').val()*1;
   var _tipo=$("#div_cheque_extranjero input[type='radio']:checked").val();
   var _orden="";
   if($('#cheque_extranjero_orden').is(":checked")){_orden="S";}else{_orden="N";};
    if (_importe == 0 || _banco == 0 || _fecha_emision=='' || _fecha_vencimiento=='' || _tipo==undefined  || _cotizacion==0)
   {
        alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Importe\n - Banco\n - Fecha de emisión\n - Fecha de depósito\n - Tipo de cheque\n - Cotización');
   }   
   else
   {
      _linea="<tr>";
       var _sVal = "CX:" + _importe + "^" + _banco + "^" + _numero + "^" + _TOOLS.reformatDate(_fecha_emision) + "^" + _TOOLS.reformatDate(_fecha_vencimiento) +"^"+_tipo+"^"+_orden+"^"+_cotizacion+"^"+_total;
      _linea+="<td><input type='hidden' id='linea_valor' name='linea_valor' value='"+_sVal+"'></td>";
      _linea+="<td><b>Cheque / " + _numero + " " + $("#id_banco_cheque_extranjero option:selected").text() + "</b></td>";
      _linea+="<td align='right'>$ " + _total + "</td>";
      _linea+="<td>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_importe' value='" + _importe + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_banco' value='" + _banco + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_numero' value='" + _numero + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_fecha_emision' value='" + _fecha_emision + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_fecha_vencimiento' value='" + _fecha_vencimiento + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_tipo' value='" + _tipo + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_orden' value='" + _orden + "'>";
      _linea+="<input type='hidden' id='comprobante_cheque_extranjero_cotizacion' value='" + _cotizacion + "'>";
      _linea+="<input class='valor_importe' type='hidden' id='comprobante_cheque_extranjero_total' value='" + _total + "'>";
      _linea+="</td>";
      _linea+="<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
      _linea+="</tr>";
      $('#tbl_lista_comprobantes').append(_linea);
      $('#cheque_extranjero_importe').val('');
      $('#cheque_extranjero_numero').val('');
      //$('#cheque_extranjero_fecha_emision').val('');
      //$('#cheque_extranjero_fecha_vencimiento').val('');
      TotalizarValores_CEM();
   }
}
function AddChequeExtranjero_CEM2() {
    var _linea = '';
    var _importe = $('#cheque_extranjero_importe2').val() * 1;
    var _banco = $('#id_banco_cheque_extranjero2').val() * 1;
    var _numero = $('#cheque_extranjero_numero2').val();
    var _fecha_emision = $('#cheque_extranjero_fecha_emision2').val();
    var _fecha_vencimiento = $('#cheque_extranjero_fecha_vencimiento2').val();
    var _cotizacion = $('#cheque_extranjero_cotizacion2').val() * 1;
    var _total = $('#cheque_extranjero_total2').val() * 1;
    var _tipo = $("#div_cheque_extranjero2 input[type='radio']:checked").val();
    var _orden = "";
    if ($('#cheque_extranjero_orden2').is(":checked")) { _orden = "S"; } else { _orden = "N"; };
    if (_importe == 0 || _fecha_emision == '' || _fecha_vencimiento == '' || _tipo == undefined || _cotizacion == 0) {
        alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Importe\n - Fecha de emisión\n - Fecha de depósito\n - Tipo de cheque\n - Cotización');
    }
    else {
        _linea = "<tr>";
        var _sVal = "CX:" + _importe + "^" + _banco + "^" + _numero + "^" + _TOOLS.reformatDate(_fecha_emision) + "^" + _TOOLS.reformatDate(_fecha_vencimiento) + "^" + _tipo + "^" + _orden + "^" + _cotizacion + "^" + _total;
        _linea += "<td><input type='hidden' id='linea_valor' name='linea_valor' value='" + _sVal + "'></td>";
        _linea += "<td><b>Cheque / " + _numero + " " + $("#id_banco_cheque_extranjero2 option:selected").text() + "</b></td>";
        _linea += "<td align='right'>$ " + _total + "</td>";
        _linea += "<td>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_importe2' value='" + _importe + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_banco2' value='" + _banco + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_numero2' value='" + _numero + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_fecha_emision2' value='" + _fecha_emision + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_fecha_vencimiento2' value='" + _fecha_vencimiento + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_tipo2' value='" + _tipo + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_orden2' value='" + _orden + "'>";
        _linea += "<input type='hidden' id='comprobante_cheque_extranjero_cotizacion2' value='" + _cotizacion + "'>";
        _linea += "<input class='valor_importe' type='hidden' id='comprobante_cheque_extranjero_total2' value='" + _total + "'>";
        _linea += "</td>";
        _linea += "<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
        _linea += "</tr>";
        $('#tbl_lista_comprobantes').append(_linea);
        $('#cheque_extranjero_importe2').val('');
        $('#cheque_extranjero_numero2').val('');
        //$('#cheque_extranjero_fecha_emision').val('');
        //$('#cheque_extranjero_fecha_vencimiento').val('');
        TotalizarValores_CEM();
    }
}
function AddMoneda_Extranjera_CEM() {
    var _linea = '';
    var _importe = $('#moneda_extranjera_importe').val() * 1;
    var _cotizacion = $('#moneda_extranjera_cotizacion').val() * 1;
    var _total = $('#moneda_extranjera_total').val() * 1;

    if (_importe == 0 || _cotizacion == 0) {
        alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Importe\n - Cotización');
    }
    else {
        _linea = "<tr>";
        var _sVal = "DL:" + _importe + "^" + _cotizacion;
        _linea += "<td><input type='hidden' id='linea_valor' name='linea_valor' value='" + _sVal + "'></td>";
        _linea += "<td><b>Moneda extranjera " + _importe + " x " + _cotizacion + " = " + _total + "</b></td>";
        _linea += "<td align='right'>$ " + _total + "</td>";
        _linea += "<td>";
        _linea += "<input type='hidden' id='comprobante_moneda_extranjera_importe' value='" + _importe + "'>";
        _linea += "<input type='hidden' id='comprobante_moneda_extranjera_cotizacion' value='" + _cotizacion + "'>";
        _linea += "<input class='valor_importe' type='hidden' id='comprobante_moneda_extranjera_total' value='" + _total + "'>";
        _linea += "</td>";
        _linea += "<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
        _linea += "</tr>";
        $('#tbl_lista_comprobantes').append(_linea);
        $('#retencion_importe').val('');
        $('#id_retencion_cobranza').val('0');
        TotalizarValores_CEM();
    }
}
function AddMoneda_Extranjera_CEM2() {
    var _linea = '';
    var _importe = $('#moneda_extranjera_importe2').val() * 1;
    var _cotizacion = $('#moneda_extranjera_cotizacion2').val() * 1;
    var _total = $('#moneda_extranjera_total2').val() * 1;

    if (_importe == 0 || _cotizacion == 0) {
        alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Importe\n - Cotización');
    }
    else {
        _linea = "<tr>";
        var _sVal = "PG:" + _importe + "^" + _cotizacion;
        _linea += "<td><input type='hidden' id='linea_valor' name='linea_valor' value='" + _sVal + "'></td>";
        _linea += "<td><b>£ Efectivo " + _importe + " x " + _cotizacion + " = " + _total + "</b></td>";
        _linea += "<td align='right'>$ " + _total + "</td>";
        _linea += "<td>";
        _linea += "<input type='hidden' id='comprobante_moneda_extranjera_importe2' value='" + _importe + "'>";
        _linea += "<input type='hidden' id='comprobante_moneda_extranjera_cotizacion2' value='" + _cotizacion + "'>";
        _linea += "<input class='valor_importe' type='hidden' id='comprobante_moneda_extranjera_total2' value='" + _total + "'>";
        _linea += "</td>";
        _linea += "<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
        _linea += "</tr>";
        $('#tbl_lista_comprobantes').append(_linea);
        $('#retencion_importe').val('');
        $('#id_retencion_cobranza').val('0');
        TotalizarValores_CEM();
    }
}
function AddTransferencia_CEM(_modo)
{
   var _linea='';
   var _importe=$('#transferencia_importe').val()*1;
   var _cuenta=$('#id_cuenta_bancaria').val()*1;
   var _fecha=$('#transferencia_fecha').val();
   var _tipo=$("#div_transferencia input[type='radio']:checked").val();

   if (_importe==0 || _cuenta=='' || _fecha=='' || _tipo==undefined)
   {
      alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Importe\n - Cuenta\n - Fecha\n - Tipo');
   }   
   else
   {
      _linea="<tr>";
       var _sVal = "TR:" + _importe + "^" + _TOOLS.reformatDate(_fecha) +"^"+_cuenta;
      if (_modo=="REC"){_sVal+="^"+_tipo;}
      _linea+="<td><input type='hidden' id='linea_valor' name='linea_valor' value='"+_sVal+"'></td>";
      _linea+="<td><b>Transferencia - Depósito / " + $("#id_cuenta_bancaria option:selected").text() + "</b></td>";
      _linea+="<td align='right'>$ " + _importe + "</td>";
      _linea+="<td>";
      _linea+="<input class='valor_importe' type='hidden' id='comprobante_transferencia_importe' value='" + _importe + "'>";
      _linea+="<input type='hidden' id='comprobante_transferencia_banco' value='" + _cuenta + "'>";
      _linea+="<input type='hidden' id='comprobante_transferencia_fecha' value='" + _fecha + "'>";
      _linea+="<input type='hidden' id='comprobante_transferencia_tipo' value='" + _tipo + "'>";
      _linea+="</td>";
      _linea+="<td style='cursor:hand;' onclick=javascript:if(confirm('Confirma?')){$(this).parent('tr:first').remove();TotalizarValores_CEM();}><img src='./assets/img/media/imagenes/delete_small.png'></td>";
      _linea+="</tr>";
      $('#tbl_lista_comprobantes').append(_linea);
      $('#transferencia_importe').val('');
      $('#id_cuenta_bancaria').val('0');
      var _dia = _TOOLS.getTodayDate("ymd","-");
      $('#transferencia_fecha').val(_dia);
      TotalizarValores_CEM();
   }
}
function TotalizarValoresMovCaja_CEM()
{
   var _total=0;
   $('.valor_importe_contable').each(function(){_total+=$(this).val()*1;})
   _total=Math.round(_total*100)/100;
   $('#deuda_total').val(_total);
   $('#td_deuda_total').html("<h2>$ " + _total + "</h2>");
   TotalizarValores_CEM();
}
function TotalizarReciboCEM(oThis)
{
    var _regs=0;
    var _total=0;
    var item="";
    var concatenado = "";
    
    $('.a_pagar').each(function() {
        if(_regs<=10) {
            // limpia tambien espacios que sino son traducidos como NaN
            if( ("" + $(this).val()).trim()!='' && $(this).val()!='-') {
                if(isNaN($(this).val())){$(this).val("");}
                _total+=($(this).val()*1);
                _regs+=1;

                // Si hay valor en el a pagar, lo concateno

                // objeto $(this)
                let $elemento_importe = $(this); // objeto jquery
                // tomo la cc que esta en el mismo nivel
                let $sibl = $elemento_importe.siblings("input[name='id_cuenta_corriente']");  // objeto jquery
                // tomo el contenido del div que mantiene el dato acumulado concatenado y le concateeno lo nuevo, si 
                // lo nuevo no esta vacio
                //let valor  = ( ($('#itemsPagadosCC').val() == null ) || ( $('#itemsPagadosCC').val() == 'undefined' ) ) ? "" : $('#itemsPagadosCC').val();  
                let valor  = "~" + $sibl.val() + "^" + parseFloat($elemento_importe.val()).toFixed(2);
                concatenado+=valor;

            }
 
        }
        else {
            alert('No puede ingresar más de 8 ítems por recibo!');
            oThis.value='';
        }
    });
    if(concatenado.substring(0,1)=="~") {
        concatenado = concatenado.substring(1,concatenado.length-1);
    }
    $('#itemsPagadosCC').val(concatenado);
    //$('#itemsPagadosCC').val(XXX);
    
    _total=Math.round(_total*100)/100;
    $('#Importe').val(_total);
    $('#tdTotal').html("<b>$ " + _total + "</b>");
    $('#deuda_total').val(_total);
    $('#td_deuda_total').html("<h2>$ " + _total + "</h2>");
    TotalizarACuenta();
}
function TotalizarValores_CEM() {
   var _total=0;
   $('.valor_importe').each(function() {
       _total+=$(this).val()*1;
    })
   _total=Math.round(_total*100)/100;
   $('#valores_total').val(_total);
   $('#td_valores_total').html("<h2>$ " + _total + "</h2>");
   TotalizarACuenta();
}
function TotalizarACuenta()
{
   var _deuda=($('#deuda_total').val()*1);
   var _valores=($('#valores_total').val()*1);
   var _a_cuenta=(_valores-_deuda);
   _a_cuenta=Math.round(_a_cuenta*100)/100;
   $('#importe_a_cuenta').val(_a_cuenta);
   $('#td_importe_a_cuenta').html("<h2>$ " + _a_cuenta + "</h2>");
    return _a_cuenta;
   //alert("neteando:   deuda:" + _deuda + " valores:" + _valores);
}
function attach(id) {
    var obj=null;
    try {
        obj= window.document.all ? window.document.all[id] : window.document.getElementById(id);
    }
    catch(err) {
        obj=null;
    }
    return obj
}
function SelText(oThis) { 
    oThis.select();
}
function ValidateNumericRange(oVal,_min,_max)
{
var bRet=true;
if(oVal.value!=undefined)
    {   
    if (oVal.value!="")
    {
        if (oVal.value!="-")
            {
                if (isNaN(oVal.value))  
                {
                    alert(oVal.value + " no es un valor numérico");
                    oVal.value="";
                    bRet=false;
                }
                else
                {
                    var iVal=(oVal.value*1);
                    if (isNaN(_min)) { _min=0; }
                    if (isNaN(_min)) { _max=0; }
                    var iMin=(_min*1);
                    var iMax=(_max*1);
                    if (iMin==iMax) 
                        {
                            oVal.value=iMin;
                            alert("Los valor mínimos y máximos son iguales");
                            bRet=false;
                        }
                    else
                        {
                        if (iVal>_max) 
                            {
                                alert("El valor excede el límite superior de control (" + iMax + ")");
                                oVal.value="";
                                bRet=false;
                            } 
                        else
                            {
                                if (iVal<_min) 
                                    {
                                    alert("El valor está por debajo del límite inferior de control (" + iMin + ")");
                                    oVal.value="";
                                    bRet=false;
                                    }
                            }
                        }
                }
            }
    }
    }
    else
    {
        bRet=false;
    }
    return bRet;   
}
function ValidateNumericRangeConfirm(oVal,_min,_max)
{
    if (oVal.value!="")
    {
        if (oVal.value!="-")
            {
                if (isNaN(oVal.value))  
                {
                    alert(oVal.value + " no es un valor numérico");
                    oVal.value="";
                }
                else
                {
                    var iVal=(oVal.value*1);
                    if (isNaN(_min)) { _min=0; }
                    if (isNaN(_min)) { _max=0; }
                    var iMin=(_min*1);
                    var iMax=(_max*1);
                    if (iMin==iMax) 
                        {
                            oVal.value=iMin;
                            alert("Los valor mínimos y máximos son iguales");
                        }
                    else
                        {
                        if (iVal>_max){alert("El valor excede el límite superior de control (" + iMax + ")")}else{if (iVal<_min){if(!confirm("El valor está por debajo del límite inferior de control (" + iMin + "). Confirma?")){oVal.value="";}}}
                        }
                }
            }
    }
}
function CabeceraRecibo_CEM(datos) {
    var _id_empresa = _AJAX._id_sucursal;
    var _id_caja_tesoreria = "1";
    let hoy = new Date().toISOString().slice(0, 10);
    let hoyDatetimeFull = new Date();
    if (_id_empresa == "") { _id_empresa = "1"; }
    if (_id_caja_tesoreria == "") { _id_caja_tesoreria = "1"; }
    _temp = "<table class='all_data'>";
    _temp += "<tr>";
    _temp += "<td><b>Empresa</b></td>";
    _temp += "<td>";
    _temp += getCombo_Plain(datos.empresaSucursal, "ID_EmpresaSucursal", "id", "descripcion", "", "false", "false", "javascript:SetChildCombo(this.value,'ID_Caja_Tesoreria');");
    _temp += "</td><td><b>Caja de tesorería</b></td><td>";
    _temp += getCombo_Plain_Array(datos.cajaTesoreria, "ID_Caja_Tesoreria", "id", "descripcion", "", "false", "false", "", _id_empresa);
    _temp += "</td>";
    _temp += "</tr>";
    _temp += "</table>";
    _temp += "<script>SetChildCombo($('#ID_EmpresaSucursal').val(),'ID_Caja_Tesoreria');</script>";
    _temp += "<script>attach('ID_Caja_Tesoreria').value='" + _id_caja_tesoreria + "';</script>";
    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Nºrecibo</div>';
    _temp += '    <div class="col" >';
    _temp += '       <input name="Nro_Recibo" type="text" maxlength="256" size="15" id="Nro_Recibo" style="border-color:Silver;border-style:Solid;">';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Fecha caja</div>';
    _temp += '    <div class="col" >';
    _temp += '       <input name="Fecha_Caja" type="date" id="Fecha_Caja" value="'+hoy+'" style="border-color:Silver;border-style:Solid;">';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Fecha emisión</div>';
    _temp += '    <div class="col" >';
    _temp += '       <input name="Fecha_Emision" type="date" id="Fecha_Emision" value="'+hoy+'" style="border-color:Silver;border-style:Solid;">';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Fecha contable</div>';
    _temp += '    <div class="col" >';
    _temp += '       <input name="Fecha_Contable" type="date" id="Fecha_Contable" value="'+hoy+'" style="border-color:Silver;border-style:Solid;">';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Conciliación</div>';
    _temp += '    <div class="col" >';
    _temp += '       <input id="Conciliacion_0" type="radio" name="Conciliacion" value="S"><label for="Conciliacion_0">Si</label>';
    _temp += '    </div>';
    _temp += '    <div class="col" >';
    _temp += '       <input id="Conciliacion_1" type="radio" name="Conciliacion" value="N" checked="checked"><label for="Conciliacion_1">No</label>';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Observaciones</div>';
    _temp += '    <div class="col" >';
    _temp += '       <textarea name="Observaciones" rows="3" cols="50" id="Observaciones" style="border-color:Silver;border-style:Solid;"></textarea>';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += '<div class="container">';
    _temp += '  <div class="row">';
	_temp += '    <div class="col" style="font-weight:bold;">Activo?</div>';
    _temp += '    <div class="col" >';
    _temp += '       <input id="Estado_0" type="radio" name="Estado" value="S" checked="checked" onclick="return false;"><label for="Estado_0">Si</label>';
    _temp += '    </div>';
    _temp += '    <div class="col" >';
    _temp += '       <input id="Estado_1" type="radio" name="Estado" value="N" onclick="return false;"><label for="Estado_1">No</label>';
    _temp += '    </div>';
	_temp += '  </div>';
    _temp += '</div>';

    _temp += "<div id='divTitle'>";
    _temp += "<b>Deuda a cobrar</b><hr>";
    _temp += "<table cellpadding='5'>";
    _temp += "<tr>";
    _temp += "<td><input type='hidden' id='id_cliente' name='id_cliente'></input></td>";
    _temp += "<td><input type='hidden' id='id_pagador' name='id_pagador'></input></td>";
    _temp += "</tr>";
    _temp += "<tr>";
    _temp += "<td colspan='4'><div id='msg_cliente' style='border:solid 0px cyan;'></div></td>";
    _temp += "</tr>";
    _temp += "</table>";
    _temp += "</div>";
    _temp += "<div id='div_cuenta_corriente'></div>";
    $('#detalleDeValores').append(_temp); 
}
function GetValoresBuilder_CEM(_modo, _id_empresa, datos) {
    var h = new Date();
    sToday = _TOOLS.formatDDMMYYYY(h.toISOString(), "/");
    var _temp = "";
    _temp += "<p><b>Carga de valores</b></p>";
    _temp += "<table cellpadding='2'>";
    _temp += "<tr><td><input id='btnEfectivo' class='btn btn-raised btn-dark' type='button' value='Efectivo' onclick=javascript:$('.comprobante').hide();$('#div_efectivo').show()></td>";
    _temp += "<td><input id='btnCheque' class='btn btn-raised btn-dark' type='button' value='Cheque' onclick=javascript:$('.comprobante').hide();$('#div_cheque').show()></td>";
    _temp += "<td><input id='btnPagare' class='btn btn-raised btn-dark' type='button' value='£ Efectivo' onclick=javascript:$('.comprobante').hide();$('#div_moneda_extranjera2').show()></td>";
    _temp += "<td><input id='btnRetencion' class='btn btn-raised btn-dark' type='button' value='£ Cheque / Transf.' onclick=javascript:$('.comprobante').hide();$('#div_cheque_extranjero2').show()></td>";
    _temp += "<td><input id='btnTransferencia' class='btn btn-raised btn-dark' type='button' value='Transferencia' onclick=javascript:$('.comprobante').hide();$('#div_transferencia').show()></td>";
    _temp += "</tr>";
    _temp += "<tr>";
    _temp += "<td><input type='button' class='btn btn-raised btn-dark' value='Tarjeta crédito' onclick=javascript:$('.comprobante').hide();$('#div_tarjeta_credito').show()></td>";
    _temp += "<td><input id='btnMonedaExtranjera' class='btn btn-raised btn-dark' type='button' value='U$S Efectivo' onclick=javascript:$('.comprobante').hide();$('#div_moneda_extranjera').show()></td>";
    switch(_modo) {
        case "REC":
            _temp += "<td><input id='btnChequeExtranjero' class='btn btn-raised btn-dark' type='button' value='U$S Cheque / Transf.' onclick=javascript:$('.comprobante').hide();$('#div_cheque_extranjero').show()></td>";
            _temp += "<td><input class='btn btn-raised btn-dark' type='button' value='Tarjeta débito' onclick=javascript:$('.comprobante').hide();$('#div_tarjeta_debito').show()></td>";
            break;
        default:
            break;
    }
    _temp += "</tr></table>";
    // efectivo
    _temp += "<div class='comprobante' id='div_efectivo'>";
    _temp += "<table>";
    _temp += "<tr>";
    _temp += "<td><b>Importe</b></td><td><input type='text' id='efectivo_importe' class='form-control' name='efectivo_importe' size='10' value=''></td>";
    _temp += "<td><b>El efectivo está físicamente en</b></td>";
    _temp += "<td><input class='form-control' type='radio' id='efectivo_tipo' name='efectivo_tipo' value='P' checked style='width: 25px;'><td><b>CHACARITA</b></td></td>";
    _temp += "<td><input class='form-control' type='radio' id='efectivo_tipo' name='efectivo_tipo' value='T' style='width: 25px;'><td><b>NOGUÉS</b></td></td>";
    _temp += "<td align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar efectivo' onclick='javascript:AddEfectivo_CEM();'></td>";
    _temp += "</tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Cheque
    _temp += "<div class='comprobante' id='div_cheque'>";
    _temp += "<table>";
    _temp += "<tr><td><b>Importe</b></td><td colspan='3'><input type='text' class='form-control' id='cheque_importe' name='cheque_importe' size='10' value=''></td></tr>";
    _temp += "<tr>";

    switch(_modo){
        case "REC":
            _temp += "<td><b>Banco</b></td>";
            //_temp += "<td>" + /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque", "SELECT id_Banco, Codigo_Bancos + ' - ' + Desc_Bancos as descripcion FROM dbo.banco ORDER BY Codigo_Bancos", "id_Banco", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += "<td>"; //+ /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque", "SELECT id_Banco, Codigo_Bancos + ' - ' + Desc_Bancos as descripcion FROM dbo.banco ORDER BY Codigo_Bancos", "id_Banco", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += getCombo_Plain(datos.bancos, "id_banco_cheque", "id_Banco", "descripcion", "0", "true", "false", "");
            _temp += "</td>";
            break;
        default:
            _temp += "<td><b>Cuenta bancaria</b></td>"
            //_temp += "<td>" + /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque", "SELECT id_Cuenta_Bancaria, detalle as descripcion FROM dbo.vw_Cuenta_Bancaria ORDER BY detalle", "id_Cuenta_Bancaria", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += "<td>";
            _temp += getCombo_Plain(datos.cuentasBancarias, "id_banco_cheque", "id_Cuenta_Bancaria", "descripcion", "0", "true", "false", "");
            _temp += "</td>";
    }
    _temp += "<td><b>Nºde cheque</b></td>";
    _temp += "<td><input type='text' id='cheque_numero' name='cheque_numero' size='20' value='' class='form-control'></td>";
    _temp += "</tr>";	
    _temp += "<tr>";
    _temp += "<td><b>Fecha de emisión</b></td>";
    //_temp += "<td>" +/* _html.html_getDate(sToday, "cheque_fecha_emision", "", False, False, "", "", "", "") + */ "</td>";
    _temp += "<td>" + "<input type='date' id='cheque_fecha_emision' name='cheque_fecha_emision' >" +  "</td>";
    _temp += "<td><b>Fecha de depósito</b></td>";
    //_temp += "<td>" + /* _html.html_getDate(sToday, "cheque_fecha_vencimiento", "", False, False, "", "", "", "") + */ "</td>";
    _temp += "<td>" + "<input type='date' id='cheque_fecha_vencimiento' name='cheque_fecha_vencimiento' >" + "</td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='4'><b>El cheque está físicamente en</b></td></tr>";
    _temp += "<tr>";
    _temp += "<td class='tdTCHE'><input type='radio' id='cheque_tipo' name='cheque_tipo' value='P' checked ><b>CHACARITA</b></td>";
    _temp += "<td class='tdTCHE'><input type='radio' id='cheque_tipo' name='cheque_tipo' value='T'><b>NOGUÉS</b></td>";
    _temp += "<td><b>¿Es no a la orden?</b></td>";
    _temp += "<td><input type='checkbox' id='cheque_orden' name='cheque_orden' value='S'></td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='4' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar cheque' onclick='javascript:AddCheque_CEM();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Cheque extranjero -> U$S Cheque / Transf.
    _temp += "<div class='comprobante' id='div_cheque_extranjero'>";
    _temp += "<table>"
    _temp += "<tr><td><b>Importe</b></td><td colspan='3'><input type='text' id='cheque_extranjero_importe' class='form-control' name='cheque_extranjero_importe' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarChequeExtranjeroCEM();}></td></tr>";

    _temp += "<tr><td><b>Cotización</b></td><td><input type='text' id='cheque_extranjero_cotizacion' name='cheque_extranjero_cotizacion' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarChequeExtranjeroCEM();}></td></tr>";
    _temp += "<tr><td><b>Total en $<input type='hidden' id='cheque_extranjero_total' name='cheque_extranjero_total' value='0'></b></td><td id='td_cheque_extranjero_total'>0</td></tr>";

    _temp += "<tr>";
    switch (_modo){
        case "REC":
            _temp += "<td><b>Banco</b></td>";
            //_temp += "<td>" + /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque_extranjero", "SELECT id_Banco, Codigo_Bancos + ' - ' + Desc_Bancos as descripcion FROM dbo.banco ORDER BY Codigo_Bancos", "id_Banco", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += "<td>";
            _temp += getCombo_Plain(datos.bancos, "id_banco_cheque_extranjero", "id_Banco", "descripcion", "0", "true", "false", "");
            _temp += "</td>";
            break;
        default:
            _temp += "<td><b>Cuenta bancaria</b></td>";
            //_temp += "<td>" + /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque_extranjero", "SELECT id_Cuenta_Bancaria, detalle as descripcion FROM dbo.vw_Cuenta_Bancaria ORDER BY detalle", "id_Cuenta_Bancaria", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += "<td>";
            _temp += getCombo_Plain(datos.cuentasBancarias, "id_banco_cheque_extranjero", "id_Cuenta_Bancaria", "descripcion", "0", "true", "false", "");
            _temp += "</td>";
    }
    _temp += "<td><b>Nºde cheque</b></td>";
    _temp += "<td><input type='text' id='cheque_extranjero_numero' name='cheque_extranjero_numero' size='20' value=''></td>";
    _temp += "</tr>";
    _temp += "<tr>";
    _temp += "<td><b>Fecha de emisión</b></td>";
    //_temp += "<td>" + /* _html.html_getDate(sToday, "cheque_extranjero_fecha_emision", "", False, False, "", "", "", "") + */ "</td>";
    _temp += "<td>" + "<input type='date' id='cheque_extranjero_fecha_emision' name='cheque_extranjero_fecha_emision'>" + "</td>";
    _temp += "<td><b>Fecha de depósito</b></td>";
    //_temp += "<td>" + /* _html.html_getDate(sToday, "cheque_extranjero_fecha_vencimiento", "", False, False, "", "", "", "") + */ "</td>";
    _temp += "<td>" + "<input type='date' id='cheque_extranjero_fecha_vencimiento' name='cheque_extranjero_fecha_vencimiento'>" + "</td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='4'><b>El cheque está físicamente en</b></td></tr>";
    _temp += "<tr>";
    _temp += "<td class='tdTCHE'><input type='radio' id='cheque_extranjero_tipo' name='cheque_extranjero_tipo' value='P' checked ><b>CHACARITA</b></td>";
    _temp += "<td class='tdTCHE'><input type='radio' id='cheque_extranjero_tipo' name='cheque_extranjero_tipo' value='T'><b>NOGUÉS</b></td>";
    _temp += "<td><b>¿Es no a la orden?</b></td>";
    _temp += "<td><input type='checkbox' id='cheque_extranjero_orden' name='cheque_extranjero_orden' value='S'></td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='4' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar U$S Cheque / Transf.' onclick='javascript:AddChequeExtranjero_CEM();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Cheque Extranjero2 -> £ Cheque / Transf.
    _temp += "<div class='comprobante' id='div_cheque_extranjero2'>";
    _temp += "<table>";
    _temp += "<tr><td><b>Importe</b></td><td colspan='3'><input type='text' id='cheque_extranjero_importe2' class='form-control' name='cheque_extranjero_importe2' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarChequeExtranjeroCEM2();}></td></tr>";

    _temp += "<tr><td><b>Cotización</b></td><td><input type='text' id='cheque_extranjero_cotizacion2' name='cheque_extranjero_cotizacion2' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarChequeExtranjeroCEM2();}></td></tr>";
    _temp += "<tr><td><b>Total en $<input type='hidden' id='cheque_extranjero_total2' name='cheque_extranjero_total2' value='0'></b></td><td id='td_cheque_extranjero_total2'>0</td></tr>";

    _temp += "<tr>";
    switch ( _modo ){
        case "REC":
            _temp += "<td><b>Banco</b></td>";
            //_temp += "<td>" + /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque_extranjero2", "SELECT id_Banco, Codigo_Bancos + ' - ' + Desc_Bancos as descripcion FROM dbo.banco ORDER BY Codigo_Bancos", "id_Banco", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += "<td>";
            _temp += getCombo_Plain(datos.bancos, "id_banco_cheque_extranjero2", "id_Banco", "descripcion", "0", "true", "false", "");
            _temp += "</td>";
            break;    
        default:
            _temp += "<td><b>Cuenta bancaria</b></td>";
            //_temp += "<td>" + /* GetComboFromDatabase_Plain(_local_database, "id_banco_cheque_extranjero2", "SELECT id_Cuenta_Bancaria, detalle as descripcion FROM dbo.vw_Cuenta_Bancaria ORDER BY detalle", "id_Cuenta_Bancaria", "descripcion", "0", True, False, "") + */ "</td>";
            _temp += "<td>";
            _temp += getCombo_Plain(datos.cuentasBancarias, "id_banco_cheque_extranjero2", "id_Cuenta_Bancaria", "descripcion", "0", "true", "false", "");
            _temp += "</td>";
    }
    _temp += "<td><b>Nºde cheque</b></td>";
    _temp += "<td><input type='text' id='cheque_extranjero_numero2' name='cheque_extranjero_numero2' size='20' value=''></td>";
    _temp += "</tr>";
    _temp += "<tr>";
    _temp += "<td><b>Fecha de emisión</b></td>";
    //_temp += "<td>" + /* _html.html_getDate(sToday, "cheque_extranjero_fecha_emision2", "", False, False, "", "", "", "") + */ "</td>";
    _temp += "<td>" + "<input type='date' id='cheque_extranjero_fecha_emision2' name='cheque_extranjero_fecha_emision2' >" + "</td>";
    _temp += "<td><b>Fecha de depósito</b></td>";
    //_temp += "<td>" + /* _html.html_getDate(sToday, "cheque_extranjero_fecha_vencimiento2", "", False, False, "", "", "", "") + */ "</td>";
    _temp += "<td>" + "<input type='date' id='cheque_extranjero_fecha_vencimiento2' name='cheque_extranjero_fecha_vencimiento2' >" + "</td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='4'><b>El cheque está físicamente en</b></td></tr>";
    _temp += "<tr>";
    _temp += "<td class='tdTCHE'><input type='radio' id='cheque_extranjero_tipo2' name='cheque_extranjero_tipo2' value='P' checked ><b>CHACARITA</b></td>";
    _temp += "<td class='tdTCHE'><input type='radio' id='cheque_extranjero_tipo2' name='cheque_extranjero_tipo2' value='T'><b>NOGUÉS</b></td>";
    _temp += "<td><b>¿Es no a la orden?</b></td>";
    _temp += "<td><input type='checkbox' id='cheque_extranjero_orden2' name='cheque_extranjero_orden2' value='S'></td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='4' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar £ Cheque / Transf.' onclick='javascript:AddChequeExtranjero_CEM2();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Moneda Extranjera -> £ Efectivo
    _temp += "<div class='comprobante' id='div_moneda_extranjera'>";
    _temp += "<table>";
    _temp += "<tr><td><b>Importe</b></td><td><input type='text' class='form-control' id='moneda_extranjera_importe' name='moneda_extranjera_importe' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarMonedaExtranjeraCEM();}></td></tr>";
    _temp += "<tr><td><b>Cotización</b></td><td><input type='text' id='moneda_extranjera_cotizacion' name='moneda_extranjera_cotizacion' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarMonedaExtranjeraCEM();}></td></tr>";
    _temp += "<tr><td><b>Total en $<input type='hidden' id='moneda_extranjera_total' name='moneda_extranjera_total' value='0'></b></td><td id='td_moneda_extranjera_total'>0</td></tr>";
    _temp += "<tr><td colspan='2' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar U$S Efectivo' onclick='javascript:AddMoneda_Extranjera_CEM();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Moneda Extranjera 2 -> U$S Efectivo
    _temp += "<div class='comprobante' id='div_moneda_extranjera2'>";
    _temp += "<table>";
    _temp += "<tr><td><b>Importe</b></td><td><input type='text' class='form-control' id='moneda_extranjera_importe2' name='moneda_extranjera_importe2' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarMonedaExtranjeraCEM2();}></td></tr>";
    _temp += "<tr><td><b>Cotización</b></td><td><input type='text' id='moneda_extranjera_cotizacion2' name='moneda_extranjera_cotizacion2' size='10' value='' onkeyup=javascript:if(IsNumeric(this.value)){TotalizarMonedaExtranjeraCEM2();}></td></tr>";
    _temp += "<tr><td><b>Total en $<input type='hidden' id='moneda_extranjera_total2' name='moneda_extranjera_total2' value='0'></b></td><td id='td_moneda_extranjera_total2'>0</td></tr>";
    _temp += "<tr><td colspan='2' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar £ Efectivo' onclick='javascript:AddMoneda_Extranjera_CEM();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Retencion. (No se estaria usando...)
    _temp += "<div class='comprobante' id='div_retencion'>";
    _temp += "<table>";
    _temp += "<tr><td><b>Importe</b></td><td><input type='text' class='form-control' id='retencion_importe' name='retencion_importe' size='10' value=''></td></tr>";
    _temp += "<tr><td><b>Tipo de retención</b></td><td>" + /* GetComboFromDatabase_Plain(_local_database, "id_retencion_cobranza", "SELECT id_RetencionCobranza, Desc_RetencionCobranza FROM dbo.RetencionCobranza ORDER BY Desc_RetencionCobranza", "id_RetencionCobranza", "Desc_RetencionCobranza", "0", True, False, "") + */ "</td></tr>";
    _temp += "<tr><td colspan='2' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar £ Cheque / Transf.' onclick='javascript:AddRetencion_CEM();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Transferencia
    _temp += "<div class='comprobante' id='div_transferencia'>";
    _temp += "<table>";
    _temp += "<tr>";
    _temp += "<td><b>Importe</b></td><td><input type='text' class='form-control' id='transferencia_importe' name='transferencia_importe' size='10' value=''></td>";
    _temp += "<td colspan='3'><input type='radio' id='transferencia_tipo' name='transferencia_tipo' value='D'><b>Transferencia por CBU</b>";
    _temp += " <input type='radio' id='transferencia_tipo' name='transferencia_tipo' value='C'><b>Deposito directo</b>";
    _temp += " <input type='radio' id='transferencia_tipo' name='transferencia_tipo' value='N'><b>DNI</b>";
    _temp += " <input type='radio' id='transferencia_tipo' name='transferencia_tipo' value='R'><b>DRE</b></td>";
    _temp += "</tr>";
    _temp += "<tr>";
    _temp += "<td><b>Fecha transferencia/depósito</b></td>";
    //_temp += "<td>" + /* _html.html_getDate(sToday, "transferencia_fecha", "", False, False, "", "", "", "") + */ "</td>";

    var _dia = _TOOLS.getTodayDate("ymd","-");
    _temp += "<td>" + "<input type='date' id='transferencia_fecha' name='transferencia_fecha' value='" + _dia + "' >" + "</td>"; // _TOOLS.getTodayDate("ymd","-")
    _temp += "<td><b>Cuenta bancaria...</b></td><td colspan='2'>"; //+ /* GetComboFromDatabase_Plain(_local_database, "id_cuenta_bancaria", "SELECT id_Cuenta_Bancaria, banco + ' - ' + tipo_cuenta + ' ' + nro_cuenta as descripcion FROM dbo.vw_Cuenta_Bancaria ORDER BY 2", "id_Cuenta_Bancaria", "descripcion", "6", True, False, "") + */ "</td>";
    _temp += getCombo_Plain(datos.cuentasBancarias, "id_cuenta_bancaria", "id_Cuenta_Bancaria", "descripcion", "6", "true", "false", "")+"</td>";
    _temp += "</tr>";
    _temp += "<tr><td colspan='5' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar transferencia' onclick=javascript:AddTransferencia_CEM('" + _modo + "')></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Tarjeta Credito
    _temp += "<div class='comprobante' id='div_tarjeta_credito'>";
    _temp += "<table>";
    _temp += "<tr><td><b>Importe</b></td><td><input type='text' class='form-control' id='tarjeta_credito_importe' name='tarjeta_credito_importe' size='10' value=''></td></tr>";
    _temp += "<tr><td colspan='2' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar tarjeta de crédito' onclick='javascript:AddTarjeta_Credito_CEM();'></td></tr>";
    _temp += "</table>";
    _temp += "</div>";

    // Tarjeta Debito
    switch ( _modo) {
        case "REC":
            _temp += "<div class='comprobante' id='div_tarjeta_debito'>";
            _temp += "<table>";
            _temp += "<tr><td><b>Importe</b></td><td><input type='text' class='form-control' id='tarjeta_debito_importe' name='tarjeta_debito_importe' size='10' value=''></td></tr>";
            _temp += "<tr><td colspan='2' align='right'><input type='button' class='btn btn-raised btn-primary' value='Agregar tarjeta de débito' onclick='javascript:AddTarjeta_Debito_CEM();'></td></tr>";
            _temp += "</table>";
            _temp += "</div>";
            //break;
    }
    _temp += "<script>$('.comprobante').hide();</script>";


    _temp += "<div class='lista_comprobantes' id='div_lista_comprobantes' style='border:solid 1px red;'>";
    _temp += "<table cellpadding='3' id='tbl_lista_comprobantes' class='table table-condensed'>";
    _temp += "<tr bgcolor='silver'><td></td><td><b>Descripcion</b></td><td align='right'><b>Importe</b></td><td></td><td></td></tr>";
    _temp += "</table>";
    _temp += "</div>";
    _temp += "</div>";

    switch (_modo) {
        case "REC":
            break;
        default:
            _temp += "<script>"
            _temp += "$('#btnPagare').hide();";
            _temp += "$('#btnRetencion').hide();";
            _temp += "$('#btnMonedaExtranjera').hide();";
            _temp += "$('.tdTCHE').hide();";
            _temp += "</script>";
            //break;
    }
    _temp += "<script>"
    switch ( _id_empresa) {
        case "1": // Chacarita
            _temp += "$('[name=\'cheque_tipo\'][value=\'P\']').prop('checked',true);";
            _temp += "$('[name=\'cheque_extranjero_tipo\'][value=\'P\']').prop('checked',true);";
            _temp += "$('[name=\'efectivo_tipo\'][value=\'P\']').prop('checked',true);";
            break;
        case "2": // Nogues
            _temp += "$('[name=\'cheque_tipo\'][value=\'T\']').prop('checked',true);";
            _temp += "$('[name=\'cheque_extranjero_tipo\'][value=\'T\']').prop('checked',true);";
            _temp += "$('[name=\'efectivo_tipo\'][value=\'T\']').prop('checked',true);";
    }
    _temp += "</script>";
    $('#detalleDeValores').append(_temp);
}
function Totales_CEM() {
    _temp = "<h4>TOTALES</h4><div id='totales' style='border:solid 3px navy;'>";
    _temp += "<table cellpadding='3' id='tbl_totales' class='table table-condensed'>";
    _temp += "<tr valign='top'>";
    _temp += "<td valign='top'><b>Deuda a cobrar</b><input type='hidden' id='deuda_total' name='deuda_total' value='0'></td><td id='td_deuda_total' align='right' valign='top'><h2>$ 0</h2></td>";
    _temp += "<td valign='top'><b>Valores cargados</b><input type='hidden' id='valores_total' name='valores_total' value='0'></td><td id='td_valores_total' align='right' valign='top'><h2>$ 0</h2></td>";
    _temp += "<td valign='top'><b>Importe a cuenta</b><input type='hidden' id='importe_a_cuenta' name='importe_a_cuenta' value='0'></td><td id='td_importe_a_cuenta' align='right' valign='top'><h2>$ 0</h2></td>";
    _temp += "</tr>";
    _temp += "</table>";
    _temp += "<input type='hidden' id='itemsPagadosCC' name='itemsPagadosCC' value=''>";
    _temp += "<input type='hidden' id='valores_recibidos' name='valores_recibidos' value=''>";
    $('#detalleDeValores').append(_temp);
}
