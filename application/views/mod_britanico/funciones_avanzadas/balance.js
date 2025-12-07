
function nullToEmpty(valor) {
	if (valor == null) { return ""; }
	if (valor == "null") { return ""; }
	if (typeof valor === "undefined") { return ""; }
	return valor;
}

function nullTo(valor,ret) {
	if (valor == null) { return ret; }
	if (valor == "null") { return ret; }
	if (typeof valor === "undefined") { return ret; }
	return valor;
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
		_AJAX.UiGetBalance(myObj).then(function (datajson) {
			var decTotAnt = 0;
			var decTotDeb = 0;
			var decTotCre = 0;
			var decTotAct = 0;
			var fila = 0;
			var totRubSdoAnt = 0;
			var totRubDeb = 0;
			var totRubCre = 0;
			var totrubSdoAct = 0;
			var corte = "";
			var corte_ant = "";
			var data = [
				{ nCuenta: "Nro.de Cuenta", sCuenta: "Nombre de cuenta", sAnt: "Saldo Anterior", tDeb: "Total Debitos", tCred: "Total Creditos", nSaldo: "Saldo Actual" }
			];
			$.each(datajson.estadistica, function (j, val1) {
				if (Number(nullTo(val1.qryHasMovs, 0)) > 0) {
					if (corte_ant == "") { corte_ant = val1.RUBRO; }
					if (corte_ant !== val1.RUBRO) {
						/*
						data.push({ nCuenta: "", sCuenta: "", sAnt: "", tDeb: "", tCred: "", nSaldo: "" });
						data.push({
							nCuenta: "",
							sCuenta: (nullToEmpty(val1.qryIndent) + nullToEmpty(corte_ant)),
							sAnt: _TOOLS.showNumber(nullToEmpty(totRubSdoAnt), 2, "", "0"),
							tDeb: _TOOLS.showNumber(nullToEmpty(totRubDeb), 2, "", "0"),
							tCred: _TOOLS.showNumber(nullToEmpty(totRubCre), 2, "", "0"),
							nSaldo: _TOOLS.showNumber(nullToEmpty(totrubSdoAct), 2, "", "0")
						});
						*/
						//data.push({ nCuenta: "", sCuenta: "", sAnt: "", tDeb: "", tCred: "", nSaldo: "" });
						totRubSdoAnt = 0;
						totRubDeb = 0;
						totRubCre = 0;
						totrubSdoAct = 0;
						corte_ant = val1.RUBRO;
					}
					if (((adicionales == "C") && val1.ROC == "C") || ((adicionales == "R") && val1.ROC == "R") || (adicionales == "B")) {
						data.push({
							nCuenta: nullToEmpty(val1.NUMERO),
							sCuenta: val1.NOMBRE,
							//sCuenta: _TOOLS.showNumber(nullToEmpty(val1.qrySdoAnt), 2, "", ""),
							sAnt: _TOOLS.showNumber(nullToEmpty(val1.qrySdoAnt), 2, "", ""),
							tDeb: _TOOLS.showNumber(nullToEmpty(val1.qryTotDeb), 2, "", ""),
							tCred: _TOOLS.showNumber(nullToEmpty(val1.qryTotCre), 2, "", ""),
							nSaldo: _TOOLS.showNumber(nullToEmpty(val1.qrySdoAct), 2, "", "")
						});
					}
					if (val1.ROC == "C") {
						decTotAnt = decTotAnt + Number(val1.qrySdoAnt);
						decTotDeb = decTotDeb + Number(val1.qryTotDeb);
						decTotCre = decTotCre + Number(val1.qryTotCre);
						decTotAct = decTotAct + Number(val1.qrySdoAct);
						totRubSdoAnt = totRubSdoAnt + Number(val1.qrySdoAnt);
						totRubDeb = totRubDeb + Number(val1.qryTotDeb);
						totRubCre = totRubCre + Number(val1.qryTotCre);
						totrubSdoAct = totrubSdoAct + Number(val1.qrySdoAct);
					}
					fila = fila + 1;
					corte = val1.RUBRO;
				}
			});
			if (fila > 0) {
				/*
				data.push({ nCuenta: "", sCuenta: "", sAnt: "", tDeb: "", tCred: "", nSaldo: "" });
				data.push({
					nCuenta: "",
					sCuenta: nullToEmpty(corte),
					sAnt: _TOOLS.showNumber(nullToEmpty(totRubSdoAnt), 2, "", "0"),
					tDeb: _TOOLS.showNumber(nullToEmpty(totRubDeb), 2, "", "0"),
					tCred: _TOOLS.showNumber(nullToEmpty(totRubCre), 2, "", "0"),
					nSaldo: _TOOLS.showNumber(nullToEmpty(totrubSdoAct), 2, "", "0")
				});
				*/
				data.push({ nCuenta: "", sCuenta: "", sAnt: "", tDeb: "", tCred: "", nSaldo: "" });
				data.push({
					nCuenta: "TOTALES",
					sCuenta: "",
					sAnt: _TOOLS.showNumber(nullToEmpty(decTotAnt), 2, "", "0"),
					tDeb: _TOOLS.showNumber(nullToEmpty(decTotDeb), 2, "", "0"),
					tCred: _TOOLS.showNumber(nullToEmpty(decTotCre), 2, "", "0"),
					nSaldo: _TOOLS.showNumber(nullToEmpty(decTotAct), 2, "", "0")
				});
			}
			var dataToDownload = convertToCsv(data, ";");
			dataToDownload = ("BALANCE DE SUMAS Y SALDOS " + titulo + "\n" + dataToDownload);
			downloadStringAsFile("balance.csv", dataToDownload);
		});
	} else {
		_AJAX.UiGetBalance(myObj).then(function (datajson) {
			var _html = "";
			var _header = "";
			var _footer = "";
			_header += "<br/><table style='width: 100%;padding: 10px' border=0 cellspacing=0>";
			_header += "<tr><th style='text-align:center;border: 2px solid black'>Nro de Cuenta</th><th style='text-align:center;border: 2px solid black'>Nombre de cuenta</th><th style='text-align:center;border: 2px solid black' >Saldo Anterior</th><th style='text-align:center;border: 2px solid black' >Total Débitos</th><th style='text-align:center;border: 2px solid black'  >Total Créditos</th><th style='text-align:center;border: 2px solid black' >Saldo Actual</th></tr>";
			var _tituloAnt = "";
			var decTotAnt = 0;
			var decTotDeb = 0;
			var decTotCre = 0;
			var decTotAct = 0;
			var fila = 0;
			var totRubSdoAnt = 0;
			var totRubDeb = 0;
			var totRubCre = 0;
			var totrubSdoAct = 0;
			var corte = "";
			var corte_ant = "";
			$.each(datajson.estadistica, function (j, val1) {
				if (Number(nullTo(val1.qryHasMovs, 0)) > 0) {
					if (corte_ant == "") { corte_ant = val1.RUBRO; }
					if (corte_ant !== val1.RUBRO) {
						_html += "<tr><td style='text-align:left;' colspan=6></td>";
						_html += "<tr><td style='text-align:left;'>&nbsp;</td>";
						_html += "<td style = 'text-align:left;'><b> " + nullToEmpty(val1.qryIndent) + nullToEmpty(corte_ant) + "</b></td >";
						_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totRubSdoAnt), 2, "", "0") + "</b></td>";
						_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totRubDeb), 2, "", "0") + "</b></td>";
						_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totRubCre), 2, "", "0") + "</b></td>";
						_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totrubSdoAct), 2, "", "0") + "</b></td>";
						_html += "<tr><td style='text-align:left;' colspan=6></td>";
						totRubSdoAnt = 0;
						totRubDeb = 0;
						totRubCre = 0;
						totrubSdoAct = 0;
						corte_ant = val1.RUBRO;
					}
					if (((adicionales == "C") && val1.ROC == "C") || ((adicionales == "R") && val1.ROC == "R") || (adicionales == "B")) {
						_html += "<tr><td style='text-align:left;'>" + nullToEmpty(val1.qryIndent) + nullToEmpty(val1.NUMERO) + "</td>";
						_html += "<td style = 'text-align:left;'> " + nullToEmpty(val1.qryIndent) + nullToEmpty(val1.NOMBRE) + "</td >";
						_html += "<td style='text-align:right;'>" + _TOOLS.showNumber(nullToEmpty(val1.qrySdoAnt), 2, "", "") + "</td>";
						_html += "<td style='text-align:right;'>" + _TOOLS.showNumber(nullToEmpty(val1.qryTotDeb), 2, "", "") + "</td>";
						_html += "<td style='text-align:right;'>" + _TOOLS.showNumber(nullToEmpty(val1.qryTotCre), 2, "", "") + "</td>";
						_html += "<td style='text-align:right;'>" + _TOOLS.showNumber(nullToEmpty(val1.qrySdoAct), 2, "", "") + "</td>";
					}
					if (val1.ROC == "C") {
						decTotAnt = decTotAnt + Number(val1.qrySdoAnt);
						decTotDeb = decTotDeb + Number(val1.qryTotDeb);
						decTotCre = decTotCre + Number(val1.qryTotCre);
						decTotAct = decTotAct + Number(val1.qrySdoAct);

						totRubSdoAnt = totRubSdoAnt + Number(val1.qrySdoAnt);
						totRubDeb = totRubDeb + Number(val1.qryTotDeb);
						totRubCre = totRubCre + Number(val1.qryTotCre);
						totrubSdoAct = totrubSdoAct + Number(val1.qrySdoAct);
					}
					fila = fila + 1;
					corte = val1.RUBRO;
				}
			});
			if (fila > 0) {
				_html += "<tr><td style='text-align:left;' colspan=6></td>";
				_html += "<tr><td style='text-align:left;'>&nbsp;</td>";
				_html += "<td style = 'text-align:left;'><b> " + nullToEmpty(corte) + "</b></td >";
				_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totRubSdoAnt), 2, "", "0") + "</b></td>";
				_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totRubDeb), 2, "", "0") + "</b></td>";
				_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totRubCre), 2, "", "0") + "</b></td>";
				_html += "<td style='text-align:right;'><b>" + _TOOLS.showNumber(nullToEmpty(totrubSdoAct), 2, "", "0") + "</b></td>";
				_html += "<tr><td style='text-align:left;' colspan=6></td>";
				_html += "<tr><td style='text-align:left;border-top: 2px solid black;'>TOTALES</td>";
				_html += "<td style = 'text-align:left;border-top: 2px solid black'> &nbsp;</td >";
				_html += "<td style='text-align:right;border-top: 2px solid black;'>" + _TOOLS.showNumber(nullToEmpty(decTotAnt), 2, "", "0") + "</td>";
				_html += "<td style='text-align:right;border-top: 2px solid black;'>" + _TOOLS.showNumber(nullToEmpty(decTotDeb), 2, "", "0") + "</td>";
				_html += "<td style='text-align:right;border-top: 2px solid black;'>" + _TOOLS.showNumber(nullToEmpty(decTotCre), 2, "", "0") + "</td>";
				_html += "<td style='text-align:right;border-top: 2px solid black;'>" + _TOOLS.showNumber(nullToEmpty(decTotAct), 2, "", "0") + "</td>";
			}
			_footer += "</table>";
			_html = _header + _html + _footer + "<br/><br/>";
			var win = window.open("", "Reporte", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1024,height=768,top=" + (screen.height - 0) + ",left=" + (screen.width - 0));
			win.document.body.innerHTML = "<html><title>BALANCE DE SUMAS Y SALDOS</title><body><img src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='window.print();'/><br/>Cementerio  <br/> Fecha: " + _TOOLS.getTodayDate("dmy", "/") + "<br/><h2 class='m-0 p-0' style='font-weight: bold; color: rgb(0,71,186);'><center>PERIODO " + titulo + "<br/>BALANCE DE SUMAS Y SALDOS </center></h2>" + _html + "</body></html>";
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
function convertToCsv(data, separator) {
	const headers = Object.keys(data[0]);
	const csvRows = [];
	//csvRows.push(headers.join(separator)); // Add headers
	for (const row of data) {
		const values = headers.map(header => row[header]);
		csvRows.push(values.join(separator)); // Add data rows
	}
	return csvRows.join('\n');
}

