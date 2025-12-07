
function nullToEmpty(valor) {
	if (valor == null) { return ""; }
	if (valor == "null") { return ""; }
	if (typeof valor === "undefined") { return ""; }
	return valor;
}

/*pendiente*/
function RevertirMovBan_CEM(_id_valorencartera,_id_salida_cheques)
{
	if(confirm("Se revertirá el depósito, revise atentamente.\nConfirma?"))
	{
		var myJSON = '{"id_valorencartera":"' + _id_valorencartera + '", "id_salida_cheques":"' + _id_salida_cheques + '"}';
			var myObj = JSON.parse(myJSON);

		_AJAX.UiRevertirDepositos(myObj).then(function (datajson) {
			//$('#btnBuscarValores').click();
			showReport();
		}).catch(function(datajson){
			alert("Error al revertir depósitos. Intente nuevamente");
		});
		//var _param=('action=revertir_movban_cem&id_valorencartera='+_id_valorencartera+"&id_salida_cheques="+_id_salida_cheques);
		//var _ret=AjaxExec('_ax_1_tools.ashx',_param);

	}
}

    function createPDF() {
        var sTable = document.getElementById('REPORT-CONTAINER').innerHTML;

        var style = "<style>";
        style = style + "table {width: 100%;font: 17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px;text-align: center;}";
        style = style + "</style>";

        // CREATE A WINDOW OBJECT.
        var win = window.open('', '', 'height=700,width=700');

        win.document.write('<html><head>');
        win.document.write('<title>Depósitos bancarios</title>');   // <title> FOR PDF HEADER.
        win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
        win.document.write('</head>');
        win.document.write('<body>');
        win.document.write(sTable);         // THE TABLE CONTENTS INSIDE THE BODY TAG.
        win.document.write('</body></html>');

        win.document.close(); 	// CLOSE THE CURRENT WINDOW.

        win.print();    // PRINT THE CONTENTS.
    }

function GrabarDeposito_CEM(_target) {
	$("#btnGrabarDeposito").attr("disabled", true);
	var sObj = '#' + _target;
	var _id_valores_efectivo = "";
	var _id_valores_cheques = "";
	var _ID_EmpresaSucursal = ($('#id_empresa').val() * 1);
	var _ID_Caja_Tesoreria = ($('#id_caja_tesoreria').val() * 1);
	$('input[id*=importe_cheque_]').each(
		function () {
			if ($(this).prop('checked')) {
				var _val = $(this).prop('id').split('_')[2];
				_id_valores_cheques += (_val + ',');
			}
		}
	);
	$('input[id*=importe_efectivo_]').each(
		function () {
			if ($(this).prop('checked')) {
				var _val = $(this).prop('id').split('_')[2];
				_id_valores_efectivo += (_val + ',');
			}
		}
	);
	var _valores = (_id_valores_efectivo + _id_valores_cheques);
	var _id_cuenta_bancaria = ($('#id_cuenta_bancaria').val() * 1);
	var _fecha_deposito = $('#TB-fecha_deposito').val();
	if (_id_cuenta_bancaria == 0 || _id_cuenta_bancaria == -1 || _fecha_deposito == '' || _valores == '' || _ID_EmpresaSucursal == 0 || _ID_Caja_Tesoreria == 0) {
		$("#btnGrabarDeposito").attr("disabled", false);
		alert('Faltan datos requeridos para procesar la carga.\nVerifique los siguientes datos.\n - Empresa\n - Caja de tesorerí­a\n - Cuenta bancaria\n - Fecha del depósito\n - Algún valor seleccionado');
		return false;
	}
	else {
		var myJSON = '{"id_cuenta_bancaria":"' + _id_cuenta_bancaria + '", "fecha_deposito":"' + _fecha_deposito + '","valores":"' + _valores + '", "id_empresa_sucursal":"' + _ID_EmpresaSucursal + '", "ID_Caja_Tesoreria":"' + _ID_Caja_Tesoreria + '"}';
		var myObj = JSON.parse(myJSON);
		_AJAX.UiProcesarDepositos(myObj).then(function (datajson) {
			showReport();
		}).catch(function (datajson) {
			$(sObj).html("<h2>Error al grabar depósitos. Intente nuevamente</h2><br/><hr/>");
		});
		$("#btnGrabarDeposito").attr("disabled", false);
		return true;
	}
}
function TotalizarMovBan_CEM()
{
    var _importe_EF=0;
    var _importe_CH=0;
    var _importe_total=0;
    $('input[id*=importe_cheque_]').each(function(){if($(this).prop('checked')){_importe_CH+=($(this).val().replace(',','.')*1);}});
    $('input[id*=importe_efectivo_]').each(function(){if($(this).prop('checked')){_importe_EF+=($(this).val().replace(',','.')*1);}});
    _importe_EF=Math.round(_importe_EF*100)/100;
    _importe_CH=Math.round(_importe_CH*100)/100;
    _importe_total=(_importe_EF+_importe_CH);
    _importe_total=Math.round(_importe_total*100)/100;
    $('#total_efectivo').html('$ ' + _importe_EF);
    $('#total_cheques').html('$ ' + _importe_CH);
    $('#total_general').html('$ ' + _importe_total);
}

function showReport() {
	//alert("report");
	var fdesde = $('#TB-fDesde').val();
	var fhasta = $('#TB-fHasta').val();

	var lnao = "N";

	if ($('#LNAO').is(":checked")) {
		lnao = "S";
	}

	var vc = "N";

	if ($('#VC').is(":checked")) {
		vc = "S";
	}

	var ve = "N";

	if ($('#VE').is(":checked")) {
		ve = "S";
	}

	var vvfc = "N";

	if ($('#VVFC').is(":checked")) {
		vvfc = "S";
	}

	var vvfn = "N";

	if ($('#VVFN').is(":checked")) {
		vvfn = "S";
	}

	var idcaja = $('#id_caja_tesoreria option:selected').val();
	var _id_empresa_sucursal = _AJAX._id_sucursal;
	var myJSON = '{"FDESDE":"' + fdesde + '", "FHASTA":"' + fhasta + '","LNAO":"' + lnao + '", "VC":"' + vc + '", "VE":"' + ve + '", "VVFC":"' + vvfc + '", "VVFN":"' + vvfn + '", "CAJA":"' + idcaja +  '", "EMPRE":"' + _id_empresa_sucursal +   '"}';
	var myObj = JSON.parse(myJSON);

	_AJAX.UiGetValores(myObj).then(function (datajson) {
		var _html = "";
		_html = _html + "<img src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='createPDF();'/><br/><br/>";
		_html = _html + "<div id='div_valores_en_cartera'><table id='tblValores' cellpadding='2' cellspacing='2' style='border:solid 1px silver;'><tr valign='top' bgcolor='silver'><th>Seleccionado</th><th>Valor</th><th>Vencimiento</th><th>Nro Cheque</th><th>Banco</th><th>Cliente</th><th>Importe</th><th>Recibo</th><th>Fisicamente en</th></tr>";
		var _color="";
		var _visiblecheck="";
		var _html_revertir = "";
		var _id_salida_cheques = "";
		var _dias_pasados = "";
		var _valor="";
		var _tipo_cheque="";
		var _id_empresa_sucursal = datajson._id_empresa_sucursal;
		var _formattedDate="";

		var row=0;
		$.each(datajson.registros, function (j, val1) {
			row++;
			_valor = val1.Codigo_Valor;

			if (val1.Vencido=='N') { _color = "red"; } else { _color = "black";}
			_html = _html + "<tr  class='" + val1.Codigo_Valor + "' style='color:" + _color + ";'>";

			_visiblecheck="";
			_html_revertir = "";

			_id_salida_cheques = nullToEmpty(val1.ID_Salida_Cheques);

        	_dias_pasados = val1.dias_pasados;

			if (val1.Vencido=='N')
			{
				_html = _html + "<td></td>";
			}
			else
			{
				if (_id_salida_cheques == "")
				{
					_visiblecheck = "display:block;";
					_html_revertir = "";
				}
				else
				{
					_visiblecheck = "display:none;";
					if (_dias_pasados == "0") 
					{ 
						_html_revertir = "<input type='button' id='revertir_" + val1.ID_ValorEnCartera + "' value='Revertir' onclick=javascript:RevertirMovBan_CEM('" + val1.ID_ValorEnCartera + "','" + _id_salida_cheques + "') />"; 
					} else { _html_revertir = ""; }
				}
				switch (_valor)
				{
					case "EF":
						_html = _html +"<td align='center'><input style='" + _visiblecheck + "' type='checkbox' id='importe_efectivo_" + val1.ID_ValorEnCartera + "' value='" + _TOOLS.showNumber(val1.Importe,2,'','0.00') + "' onclick='javascript:TotalizarMovBan_CEM();'/>" + _html_revertir + "</td>";
						break;
					case "CH":
					case "CX":
						_html = _html +"<td align='center'><input style='" + _visiblecheck + "' type='checkbox' id='importe_cheque_" + val1.ID_ValorEnCartera + "' value='" + _TOOLS.showNumber(val1.Importe,2,'','0.00') + "' onclick='javascript:TotalizarMovBan_CEM();'/>" + _html_revertir + "</td>";
						break;
					default:
						_html = _html +"<td></td>";
						break;
				}
			}
			_tipo_cheque = nullToEmpty(val1.Tipo_Cheque);
			_html = _html + "<td>"+val1.Codigo_Valor+"</td>";
			_formattedDate = nullToEmpty(val1.Fecha_Vencimiento);
			if (_formattedDate != "") {  _formattedDate = _TOOLS.getTextAsFormattedDate(_formattedDate, "dmy", "/"); } else { _formattedDate = ""; }
			_html = _html + "<td>"+_formattedDate+"</td>";
			_html = _html + "<td>"+nullToEmpty(val1.Nro_Cheque)+"</td>";
			_html = _html + "<td>"+nullToEmpty(val1.Desc_Bancos)+"</td>";
			_html = _html + "<td>"+val1.RazonSocial+"</td>";
			_html = _html + "<td class='importe_" + _valor + "'>"+_TOOLS.showNumber(val1.Importe,2,'','0.00')+"</td>";
			_html = _html + "<td>"+val1.Nro_Recibo+"</td>";
			switch (_tipo_cheque)
			{
				case "P":
					_html = _html + "<td>CHACARITA</td>";
					break;
				case "T":
					_html = _html + "<td>NOGUÉS</td>";
					break;
				default:
					switch (_id_empresa_sucursal)
					{
						case "1":
							_html = _html + "<td>CHACARITA</td>";
							break;
						case "2":
							_html = _html + "<td>NOGUÉS</td>";
							break;
					}
					break;
			}
			_html = _html + "</tr>";
		});
		_html = _html + "</table></div>";
		_html = _html + "<table cellpadding='2' style='border:solid 0px silver;'>";
		_html = _html + "<tr>";
		_html = _html + "<td><b>Total efectivo</b></td><td id='total_efectivo'></td>";
		_html = _html + "<td><b>Total cheques</b></td><td id='total_cheques'></td>";
		_html = _html + "<td><b>Total general</b></td><td id='total_general'></td>";
		_html = _html + "</tr>";
		_html = _html + "<tr>";
		_html = _html + "<td><b>Cuenta bancaria</b></td>";
		_html = _html + "<td>";
		_html += _TOOLS.getComboFromJson(datajson.data, { "selected": -1, "id": "id_Cuenta_Bancaria", "description": "detalle" },"N",8,"id_cuenta_bancaria","","class='form-control combo dbase'")+"</td>";
		var hoy1 = _TOOLS.getTodayDate("amd", "-");
		_html += "<td><b>Fecha Depósito</b></td><td>" + _TOOLS.getDateBoxWithOutLabel("fecha_deposito", "", 12, hoy1, "N", "class='form-control text dbase'") + "</td>";
		_html = _html + "<td></td><td><input id='btnGrabarDeposito' type='button' class='button' value='Grabar depósito' onclick=javascript:GrabarDeposito_CEM('div_valores_en_cartera')></td>";
		_html = _html + "</tr>";
		_html = _html + "</table>";
        var _cuantos = "";
		switch (_id_empresa_sucursal) {
			case "1":
				if (parseInt(datajson.cuantos) != 0) { _html += ("<h1 style='color:red;'>Existen valores en la caja de Nogués que deben ser revisados</h1>"); }
				break;
			case "2":
				if (parseInt(datajson.cuantos) != 0) { _html += ("<h1 style='color:red;'>Existen valores en la caja de Chacarita que deben ser revisados</h1>"); }
				break;
		}
		$("#REPORT-CONTAINER").html(_html);
	});

}

