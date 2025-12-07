function getYears(permiteGenerarSN) {
	var d = new Date();
	var year = d.getFullYear();
	var month = (d.getMonth()+1);
	var year1 = Number(year) - 1;
	var year2 = Number(year) + 1;
	var _html = "";
	_html += _TOOLS.getComboFromList("cboYrs", "Año", 1, year, year1 + "," + year + "," + year2, year1 + "&nbsp;," + year+ "&nbsp;&nbsp;&nbsp;," + year2, "N ", "", "Y", "class='form-control' ", "N");
	_html += _TOOLS.getComboFromList("cboMes", "Mes", 2, 0 ,"0,1,2,3,4,5,6,7,8,9,10,11,12", "[Seleccione],Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre&nbsp;&nbsp;&nbsp;,Octubre,Noviembre,Diciembre", "N ", "", "Y", " class='form-control' ", "N");
	if (permiteGenerarSN=="S") {
		_html += '<div class="col-2 mt-3">';
		_html += '<a href="#" class="btnAction btnAccept btn btn-sm btn-primary btn-raised pull-right" onclick="GenerarDeudaMensual_CEM();">1- Generar deuda</a>';
		_html += '</div >';
	}
	_html += '<div class="col-2 mt-3"><a href="#" class="btnAction btnAccept btn btn-sm btn-info btn-raised pull-right" onclick="GenerarAvisosPDF_CEM(0);">2a - PDF Emails</a></div>';
	_html += '<div class="col-2 mt-3"><a href="#" class="btnAction btnAccept btn btn-sm btn-danger btn-raised pull-right" onclick="GenerarAvisosPDF_CEM(1);">2b - PDF Correo Postal</a></div>';
	_html += '<div class="col-2 mt-3"><a href="#" class="btnAction btnAccept btn btn-sm btn-success btn-raised pull-right" onclick="CerrarMes_CEM();">3 - Transferencia Nogués</a></div>';
	$("#selectAnio").html(_html);
}

/**
 * Trae el HTML del aviso de deuda como una ventana nueva 
 *
 * Abre el aviso de deuda mensual para todos los clientes como HTML en una ventana nueva.
 *
 * @param string $hash. AD+base64 -> el base64 esta confirmado de empresa|IdPagador|anio|mes.
 *                                   B Britanico - N Nogues / anio y mes como entero.
 * @return array Html del resumen con la deuda, incluye barcode Pago Facil ("html","paginas","caracteres","skips","registros")
 * 
 */
function GenerarAvisosPDF_CEM(_filtro)
{
	let anio=$('#cboYrs option:selected').val();
	let mes = $('#cboMes option:selected').val();
	if (mes == 0) { alert("Debe seleccionar mes"); return false; }
	generarAvisosJson = { anio: anio, mes: mes, idPagador: 0, filtro: _filtro };
	let procesar = confirm('Generar los PDF de Avisos de Deuda del período: ' + mes + '/' + anio + ' ?');
	
	if (procesar) {
		_AJAX.UiGenerarAvisosPDF(generarAvisosJson).then(function (respuestaJson) {
			// chequeo errores
			if (respuestaJson.status == 'OK') {
				_html="";
					let deuda = respuestaJson.deudaTotal; 
					_html=deuda.html;
					_paginas = deuda.paginas;
					const winUrl = URL.createObjectURL(new Blob([_html], { type: "text/html" }));
					const win = window.open(winUrl);
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}		
		});
	}
}
function GenerarDeudaMensual_CEM() {
	var anio = $('#cboYrs option:selected').val();
	var mes = $('#cboMes option:selected').val();
	if (mes == 0) { alert("Debe seleccionar mes"); return false; }
	if (!confirm('Generar Avisos de Deuda del período: ' + mes + '/' + anio + ' ?')) { return false };
	_AJAX.UiGenerarDeudaMensual({ anio: anio, mes: mes }).then(function (respuestaJson) {
		// chequeo errores
		if (respuestaJson.status == 'OK') {
			alert("Deuda generada correctamente!");
		} else {
			alert("Ocurrio un error al obtener los datos");
		}
	});
}
function CerrarMes_CEM() {
	var anio = $('#cboYrs option:selected').val();
	var mes = $('#cboMes option:selected').val();
	if (mes == 0) { alert("Debe seleccionar mes"); return false; }
	if (!confirm("¿Confirma el cierre y transferencia para el año mes seleccionado de Nogués a Chacarita?")) { return false; }
	mes = (parseInt(mes) + 1);
	if (mes == 13) {
		mes = 1;
		anio = (parseInt(anio) + 1);
	}
	_AJAX.UiTransferenciaMensual({ anio: anio, mes: mes }).then(function (data) {
   		alert("Se ha transferido el año/mes seleccionado");
	});
}

function GetEmailStatus_CEM() {
	_AJAX.UiGetEmailStatus({ Dummy: "dummy" }).then(function (datajson) { $('#SALDO-CONTAINER').html(""); });
}
function showReport() {
	alert("showReport");
}

