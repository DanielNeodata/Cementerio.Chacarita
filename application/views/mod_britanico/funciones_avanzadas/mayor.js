
function nullToEmpty(valor) {
	if (valor == null) { return ""; }
	if (valor == "null") { return ""; }
	if (typeof valor === "undefined") { return ""; }
	return valor;
}

function SaldosCtasCtes_CEM() {
	var fSaldo = $('#TB-fSaldo').val();
	var myJSON = '{"FSALDO":"' + fSaldo + '"}';
	var myObj = JSON.parse(myJSON);

	_AJAX.UiGetSaldoCtasCtes(myObj).then(function (datajson) {
		var _html = "";

		//alert("JSON: " + JSON.stringify(datajson.estadistica));

		//alert("sldo: "+datajson.estadistica[0].Saldo);
		const fechasaldo = new Date(fSaldo + " 00:00:00");
		var fechasaldostr = _TOOLS.getFormattedDate(fechasaldo, "dmy", "/");

		$('#SALDO-CONTAINER').html("<b>Saldo al "+fechasaldostr+": $ "+datajson.estadistica[0].Saldo+"</b>"); 
	});

}

function showReport() {
	var fdesde = $('#TB-fDesde').val();
	var fhasta = $('#TB-fHasta').val();
	var cdesde = $('#TB-aDesde').val();
	var chasta = $('#TB-aHasta').val();
	var prefijo = $("#TB-PREFIJO").val();
	var destino = $("input:radio[name=DESTINO]:checked").val();
	var adicionales = $("input:radio[name=ADICIONALES]:checked").val();
	var myJSON = '{"FDESDE":"' + fdesde + '", "FHASTA":"' + fhasta + '","CDESDE":"' + cdesde + '", "CHASTA":"' + chasta + '", "PREFIJO":"' + prefijo + '", "DESTINO":"' + destino + '", "ADICIONALES":"' + adicionales + '"}';
	var myObj = JSON.parse(myJSON);
	const fechadesde = new Date(fdesde + " 00:00:00");
	var fechadesdestr = _TOOLS.getFormattedDate(fechadesde, "dmy", "/");
	const fechahasta = new Date(fhasta + " 00:00:00");
	var fechahastastr = _TOOLS.getFormattedDate(fechahasta, "dmy", "/");
	var titulo = "" + fechadesdestr + " - " + fechahastastr;
	if (destino == "E") {
		_AJAX.UiGetLibroMayor(myObj).then(function (datajson) {
			var _tituloAnt = "";
			var _sumaDebito = 0;
			var _sumaCredito = 0;
			var _sumaImp = 0;
			var _totalDebito = 0;
			var _totalCredito = 0;
			var _totalImp = 0;
			var _fecha = "";
			var _fechaAnt = "N/A";
			var _numeroAnt = "N/A";
			var _numero = "";
			var txtCre = "";
			var txtDeb = "";
			var cre = 0;
			var deb = 0;
			var fila = 0;
			var acocom = "";
			var qrySdoAnt = 0;
			var tot = 0;
			var _formattedDate = "";
			var totalqrySdoAnt = 0;

			var data = [
				{ Cuenta: "Nro.cuenta y nombre", Fecha: "Fecha", nAsiento: "Nro.asiento", TC: "TC", nComprobante: "Nro.comprobante", Debitos: "Debitos", Creditos: "Creditos", sAcu: "Saldo Acumulado" },
				{ Cuenta: "Comentario del asiento", Fecha: "", nAsiento: "", TC: "", nComprobante: "Saldo anterior", Debitos: "", Creditos: "", sAcu: "Saldo actual" }
			];
			$.each(datajson.estadistica, function (j, val1) {
				_numero = val1.NUMERO;
				if (_numero != _numeroAnt) {
					if (_numeroAnt != "N/A") {
						tot = (qrySdoAnt + _sumaImp);
						data.push({
							Cuenta: "TOTALES: " + _tituloAnt,
							Fecha: "",
							nAsiento: "",
							TC: "",
							nComprobante: _TOOLS.showNumber(qrySdoAnt, 2, "", "0"),
							Debitos: _TOOLS.showNumber(_sumaDebito, 2, "", "0"),
							Creditos: _TOOLS.showNumber(_sumaCredito, 2, "", "0"),
							sAcu: _TOOLS.showNumber(tot, 2, "", "0") 
						});
					}
					_tituloAnt = val1.NUMERO;
					qrySdoAnt = (val1.qrySdoAnt * 1);
					_sumaCredito = 0;
					_sumaDebito = 0;
					_sumaImp = 0;
					data.push({
						Cuenta: (val1.NUMERO + " " + val1.NOMBRE),
						Fecha: "",
						nAsiento: "",
						TC: "",
						nComprobante: "",
						Debitos: "",
						Creditos: "",
						sAcu: ""
					});
				};
				try {
					cre = (val1.qryCre * 1);
					_sumaCredito += (cre * 1);
					_totalCredito += (cre * 1);
					txtCre = _TOOLS.showNumber(cre, 2, "", "");
				} catch (errort) { txtCre = ""; }

				try {
					deb = (val1.qryDeb * 1);
					_sumaDebito += (deb * 1);
					_totalDebito += (deb * 1);
					txtDeb = _TOOLS.showNumber(deb, 2, "", "");
				} catch (errort1) { txtDeb = ""; }
				_sumaImp += (deb - cre);
				totalqrySdoAnt = qrySdoAnt;
				if (adicionales == "A") { acocom = _TOOLS.showNumber(_sumaImp, 2, "", "0"); } else { acocom = val1.qryObsRen; }
				try {
					_formattedDate = nullToEmpty(val1.FECHA);
					if (_formattedDate != "") { _formattedDate = _TOOLS.getTextAsFormattedDate(_formattedDate, "dmy", "/"); } else { _formattedDate = ""; }
				} catch (errord) { _formattedDate = ""; }
				data.push({
					Cuenta: nullToEmpty(val1.COMENTARIO),
					Fecha: _formattedDate,
					nAsiento: nullToEmpty(val1.ASIENTO),
					TC: nullToEmpty(val1.TIPCOM),
					nComprobante: nullToEmpty(val1.NUMCOM),
					Debitos: txtDeb,
					Creditos: txtCre,
					sAcu: nullToEmpty(acocom)
				});
				_numeroAnt = _numero;
				fila = fila + 1;
			});
			if (fila > 0) {
				var totfin = (totalqrySdoAnt + _totalDebito - _totalCredito);
				data.push({
					Cuenta: ("TOTALES: " + _tituloAnt),
					Fecha: "",
					nAsiento: "",
					TC: "",
					nComprobante: _TOOLS.showNumber(qrySdoAnt, 2, "", "0"),
					Debitos: _TOOLS.showNumber(_sumaDebito, 2, "", "0"),
					Creditos: _TOOLS.showNumber(_sumaCredito, 2, "", "0"),
					sAcu: _TOOLS.showNumber(totfin, 2, "", "0")
				});

				data.push({
					Cuenta: "TOTAL DÉBITOS y CREDITOS",
					Fecha: "",
					nAsiento: "",
					TC: "",
					nComprobante: _TOOLS.showNumber(totalqrySdoAnt, 2, "", "0"),
					Debitos: _TOOLS.showNumber(_totalDebito, 2, "", "0"),
					Creditos: _TOOLS.showNumber(_totalCredito, 2, "", "0"),
					sAcu: _TOOLS.showNumber(totfin, 2, "", "0")
				});
			}
			var dataToDownload = convertToCsv(data, ";");
			dataToDownload = ("MAYOR " + titulo + "\n" + dataToDownload);
			downloadStringAsFile("mayor.csv", dataToDownload);
		});
	} else {
		_AJAX.UiGetLibroMayor(myObj).then(function (datajson) {
			var _html = "";
			var _header = "";
			var _footer = "";
			_header += "<br/>";
			_header += "<table style='width: 100%;padding: 10px' border=0 cellspacing=0>";
			_header += "   <tr>";
			_header += "      <th style='text-align:center;border: 2px solid black;' colspan='2'>Nºcuenta y nombre</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>Fecha</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>Nºasiento</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>TC</th>";
			_header += "      <th style='text-align:center;border: 2px solid black'>Nºcomprobante</th>";
			_header += "      <th style='text-align:center;border: 2px solid black'>Débitos</th>";
			_header += "      <th style='text-align:center;border: 2px solid black'>Créditos</th>";
			_header += "      <th style='text-align:center;border: 2px solid black'>Saldo Acumulado</th>";
			_header += "   </tr>";
			_header += "   <tr>";
			_header += "      <th style='text-align:center;border: 2px solid black;' colspan='5'>Comentario del asiento</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>Saldo anterior</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>&nbsp;</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>&nbsp;</th>";
			_header += "      <th style='text-align:center;border: 2px solid black;'>Saldo actual</th>";
			_header += "   </tr>";
			var _tituloAnt = "";
			var _sumaDebito = 0;
			var _sumaCredito = 0;
			var _sumaImp = 0;
			var _totalDebito = 0;
			var _totalCredito = 0;
			var _totalImp = 0;
			var _fecha = "";
			var _fechaAnt = "N/A";
			var _numeroAnt = "N/A";
			var _numero = "";
			var txtCre = "";
			var txtDeb = "";
			var cre = 0;
			var deb = 0;
			var fila = 0;
			var acocom = "";
			var qrySdoAnt = 0;
			var tot = 0;
			var _formattedDate = "";
			var totalqrySdoAnt = 0;
			$.each(datajson.estadistica, function (j, val1) {
				_numero = val1.NUMERO;
				if (_numero != _numeroAnt) {
					if (_numeroAnt != "N/A") {
						tot = (qrySdoAnt + _sumaImp);
						_html += "<tr>";
						_html += "   <td style='text-align:left;border-bottom:2px solid black;padding:8px;' colspan='2'>TOTALES: " + _tituloAnt + "</td>";
						_html += "   <td style='text-align:right;border-bottom:2px solid black;padding:8px;'></td>";
						_html += "   <td style='text-align:right;border-bottom:2px solid black;padding:8px;'></td>";
						_html += "   <td style='text-align:right;border-bottom:2px solid black;padding:8px;'></td>";
						_html += "   <td style='text-align:right;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(qrySdoAnt, 2, "", "0") + "</td>";
						_html += "   <td style='text-align:right;border-top:2px solid black;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaDebito, 2, "", "0") + "</td>";
						_html += "   <td style='text-align:right;border-top:2px solid black;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaCredito, 2, "", "0") + "</td>";
						_html += "   <td style='text-align:right;border-top:2px solid black;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(tot, 2, "", "0") + "</td>";
						_html += "</tr>";
					}
					_tituloAnt = val1.NUMERO;
					qrySdoAnt = (val1.qrySdoAnt * 1);
					_sumaCredito = 0;
					_sumaDebito = 0;
					_sumaImp = 0;
					_html += "<tr style='font-weight:bold;'>";
					_html += "   <td style='text-align:left;padding:5px;' colspan='9'>" + val1.NUMERO + " " + val1.NOMBRE + "</td>";
					_html += "</tr>";
				};
				try {
					cre = (val1.qryCre * 1);
					_sumaCredito += (cre * 1);
					_totalCredito += (cre * 1);
					txtCre = _TOOLS.showNumber(cre, 2, "", "");
				} catch (errort) { txtCre = ""; }

				try {
					deb = (val1.qryDeb * 1);
					_sumaDebito += (deb * 1);
					_totalDebito += (deb * 1);
					txtDeb = _TOOLS.showNumber(deb, 2, "", "");
				} catch (errort1) { txtDeb = ""; }
				_sumaImp += (deb - cre);
				totalqrySdoAnt = qrySdoAnt;
				if (adicionales == "A") { acocom = _TOOLS.showNumber(_sumaImp, 2, "", "0"); } else { acocom = val1.qryObsRen; }
				try {
					_formattedDate = nullToEmpty(val1.FECHA);
					if (_formattedDate != "") { _formattedDate = _TOOLS.getTextAsFormattedDate(_formattedDate, "dmy", "/"); } else { _formattedDate = ""; }
				} catch (errord) { _formattedDate = ""; }
				_html += "<tr>";
				_html += "   <td style='text-align:left;' colspan='2'>" + nullToEmpty(val1.COMENTARIO) + "</td>";
				_html += "   <td style='text-align:left;'>" + _formattedDate + "</td>";
				_html += "   <td style='text-align:left;'>" + nullToEmpty(val1.ASIENTO) + "</td>";
				_html += "   <td style='text-align:left;'>" + nullToEmpty(val1.TIPCOM) + "</td>";
				_html += "   <td style='text-align:right;'>" + nullToEmpty(val1.NUMCOM) + "</td>";
				_html += "   <td style='text-align:right;'>" + txtDeb + "</td>";
				_html += "   <td style='text-align:right;'>" + txtCre + "</td>";
				_html += "   <td style='text-align:right;'>" + nullToEmpty(acocom) + "</td>";
				_html += "</tr>";
				_numeroAnt = _numero;
				fila = fila + 1;
			});
			if (fila > 0) {
				var totfin = (totalqrySdoAnt + _totalDebito - _totalCredito);
				_html += "<tr>";
				_html += "    <td style='text-align:left;border-bottom:2px solid black;padding:8px;' colspan='2'>TOTALES: " + _tituloAnt + "</td>";
				_html += "    <td style='text-align:right;border-bottom:2px solid black;padding:8px;'></td>";
				_html += "    <td style='text-align:right;border-bottom:2px solid black;padding:8px;'></td>";
				_html += "    <td style='text-align:right;border-bottom:2px solid black;padding:8px;'></td>";
				_html += "    <td style='text-align:right;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(qrySdoAnt, 2, "", "0") + "</td>";
				_html += "    <td style='text-align:right;border-top:2px solid black;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaDebito, 2, "", "0") + "</td>";
				_html += "    <td style='text-align:right;border-top:2px solid black;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaCredito, 2, "", "0") + "</td>";
				_html += "    <td style='text-align:right;border-top:2px solid black;border-bottom:2px solid black;padding:8px;'>" + _TOOLS.showNumber(totfin, 2, "", "0") + "</td>";
				_html += "</tr>";
				_html += "<tr>";
				_html += "   <td style='text-align:left;' colspan='2'>TOTAL DÉBITOS y CREDITOS</td>";
				_html += "   <td style='text-align:right;'></td>";
				_html += "   <td style='text-align:right;'></td>";
				_html += "   <td style='text-align:right;'></td>";
				_html += "   <td style='text-align:right;'>" + _TOOLS.showNumber(totalqrySdoAnt, 2, "", "") + "</td>";
				_html += "   <td style='text-align:right;'>" + _TOOLS.showNumber(_totalDebito, 2, "", "") + "</td>";
				_html += "   <td style='text-align:right;'>" + _TOOLS.showNumber(_totalCredito, 2, "", "") + "</td>";
				_html += "   <td style='text-align:right;'>" + _TOOLS.showNumber(totfin, 2, "", "") + "</td>";
				_html += "</tr>";
			}
			_footer += "</table>";
			_html = _header + _html + _footer + "<br/><br/>";
			var win = window.open("", "Reporte", "");
			win.document.body.innerHTML = "<html><title>Libro Mayor</title><body>Cementerio <br/> Fecha: " + _TOOLS.getTodayDate("dmy", "/") + "<br/><h2 class='m-0 p-0' style='font-weight: bold; color: rgb(0,71,186);'><center>PERIODO " + titulo + "<br/>MAYOR DE LAS CUENTAS AL " + _TOOLS.getTodayDate("dmy", "/") + "</center></h2>" + _html + "</body></html>";
		});
	}
}
function downloadStringAsFile(filename, text) {
	var _boom = "\uFEFF";
	const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
	const url = URL.createObjectURL(blob);
	const a = document.createElement('a');
	a.href = url;
	a.download = filename;
	document.body.appendChild(a);
	a.click();
	document.body.removeChild(a);
	URL.revokeObjectURL(url);
}
function convertToCsv(data,separator) {
	const headers = Object.keys(data[0]);
	const csvRows = [];
	//csvRows.push(headers.join(separator)); // Add headers
	for (const row of data) {
		const values = headers.map(header => row[header]);
		csvRows.push(values.join(separator)); // Add data rows
	}
	return csvRows.join('\n');
}


