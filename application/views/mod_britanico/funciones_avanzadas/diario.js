
function showReport() {
	//alert("report");
	var desde = $('#TB-aDesde').val();
	var hasta = $('#TB-aHasta').val();
	var prefijo = $("#TB-PREFIJO").val();
	var destino = $("input:radio[name=DESTINO]:checked").val();
	var modo = $("input:radio[name=MODO]:checked").val();
	//alert("Modo...");
	//alert(" M: "+modo);
	//alert("d: " + desde + " h: " + hasta + " r: " + recalcula + " d: " + detallado+ " M: "+modo);

	var myJSON = '{"DESDE":"' + desde + '", "HASTA":"' + hasta + '", "PREFIJO":"' + prefijo + '", "DESTINO":"' + destino + '", "MODO":"' + modo + '"}';
	var myObj = JSON.parse(myJSON);

	//alert("report2");



	_AJAX.UiGetLibroDiario(myObj).then(function (datajson) {
		var _html = "";
		//alert("on then...");
		//alert("JSON estadistica: " + JSON.stringify(datajson.estadistica));
		//alert("json count "+datajson.estadistica.length);
		//alert("js count2: "+Object.keys(datajson.estadistica).length)
		var titulo = "";

		//alert("come back");
		var _header = "";
		var _footer = "";
		_header += "<br/><table style='width: 100%;padding: 10px' border=0 cellspacing=0>";
		if (modo=="T")
		{
			_header += "<tr><th style='text-align:left;border-bottom: 2px solid black' width=5%></th><th style='text-align:left;border-bottom: 2px solid black'  width=9%></th><th style='text-align:left;border-bottom: 2px solid black'  width=9% >Nro de Cuenta</th><th style='text-align:left;border-bottom: 2px solid black'  width=35%>Nombre</th><th style='text-align:left;border-bottom: 2px solid black'  width=21%>Débitos</th><th style='text-align:left;border-bottom: 2px solid black'  width=21%>Créditos</th></tr>";
		}
		else
		{
			_header += "<tr><th style='text-align:left;border-bottom: 2px solid black' width=5%>TC</th><th style='text-align:left;border-bottom: 2px solid black'  width=9%>N.COMP</th><th style='text-align:left;border-bottom: 2px solid black'  width=9% >Nro de Cuenta</th><th style='text-align:left;border-bottom: 2px solid black'  width=35%>Nombre</th><th style='text-align:left;border-bottom: 2px solid black'  width=21%>Débitos</th><th style='text-align:left;border-bottom: 2px solid black'  width=21%>Créditos</th></tr>";
		}

		var _tituloAnt = "";

		var _sumaDebito = 0;
		var _sumaCredito = 0;
		var _totalDebito = 0;
		var _totalCredito = 0;
		var _fecha = "";
		var _fechaAnt = "N/A";
		var _numeroAnt = "N/A";
		var _numero = "";
		var txtCre = "";
		var txtDeb = "";
		var cre = 0;
		var deb = 0;
		var fila = 0;
		$.each(datajson.estadistica, function (j, val1) {
			
			//alert(bimestre);
			_numero = val1.NUMERO;

			if (_numero != _numeroAnt) {
				/*antes de reset cierro tabla anterior siempre y cuando no sea la primer fila*/
				if (_numeroAnt != "N/A") {
					//alert(_sumaDebito);
					//alert(_sumaCredito);
					_html += "<tr><td style='text-align:left;border-bottom: 2px solid black;padding:8px;' colspan=4>" + _tituloAnt + "</td><td style='text-align:right;border-top: 2px solid black;border-bottom: 2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaDebito, 2, "", "") + "</td><td style='text-align:right;border-top: 2px solid black;border-bottom: 2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaCredito, 2, "", "") + "</td></tr>";

				}
				_tituloAnt = val1.COMENTARIO;
				_sumaCredito = 0;
				_sumaDebito = 0;
				if (modo=="T")
				{
					_html += "<tr><td style='text-align:center;padding:5px;' colspan=6> TOTALES POR CUENTA </td></tr>";
				}
				else
				{
					_html += "<tr><td style='text-align:center;padding:5px;' colspan=6> FECHA: " + val1.FECHA + " &nbsp;&nbsp; ASIENTO: " + val1.NUMERO + "</td></tr>";					
				}
				
			};
				try {
					if (isNaN(val1.qryCre)) {
						txtCre = "";
					}
					else
					{
						cre = Number(val1.qryCre);
						_sumaCredito += cre;
						_totalCredito += cre;
						txtCre = _TOOLS.showNumber(cre, 2, "", "");
					}

				} catch (errort) { txtCre = ""; }

				try {
					if (isNaN(val1.qryDeb)) {
						txtDeb = "";
					}
					else {
						deb = Number(val1.qryDeb);
						_sumaDebito += deb;
						_totalDebito += deb;
						txtDeb = _TOOLS.showNumber(deb, 2, "", "");
					}

				} catch (errort1) { txtDeb = ""; }

			_html += "<tr><td style='text-align:left;'>" + val1.qryTC + "</td><td style='text-align:left;'>" + val1.qryNUM + "</td><td style='text-align:left;'>" + val1.CUENTA + "</td><td style='text-align:left;'>" + val1.NOMBRE_CUENTA + "</td><td style='text-align:right;'>" + txtDeb + "</td><td style='text-align:right;'>" + txtCre + "</td></tr>";

			_numeroAnt = _numero;
			fila = fila + 1 ;
		});
	/*renlon d cierre*/
		if (fila > 0) {
			_html += "<tr><td style='text-align:left;border-bottom: 2px solid black;padding:8px;' colspan=4>" + _tituloAnt + "</td><td style='text-align:right;border-top: 2px solid black;border-bottom: 2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaDebito, 2, "", "") + "</td><td style='text-align:right;border-top: 2px solid black;border-bottom: 2px solid black;padding:8px;'>" + _TOOLS.showNumber(_sumaCredito, 2, "", "") + "</td></tr>";
			_html += "<tr><td style='text-align:left;' colspan=4> TOTAL DÉBITOS y CRËDITOS </td><td style='text-align:roght;'>" + _TOOLS.showNumber(_totalDebito, 2, "", "") + "</td><td style='text-align:right;'>" + _TOOLS.showNumber(_totalCredito, 2, "", "") + "</td></tr>";
		}


		//if (totimptad != 0) {
		//	_adelantados += "<tr><td style='text-align:center;border: 1px solid black'>TOTALES</td><td style='text-align:center;border: 1px solid black'>" + totcantad + "</td><td style='text-align:left;border: 1px solid black'>" + _TOOLS.showNumber(totportad, 1, "", 0) + "</td><td style='text-align:right;border: 1px solid black'>" + _TOOLS.showNumber(totimptad, 2, "", 0) + "</td></tr>";
		//}

		//if (totimptal != 0) {
		//	_aldia += "<tr><td style='text-align:center;border: 1px solid black'>TOTALES</td><td style='text-align:center;border: 1px solid black'>" + totcantal + "</td><td style='text-align:left;border: 1px solid black'>" + _TOOLS.showNumber(totportal, 1, "", 0) + "</td><td style='text-align:right;border: 1px solid black'>" + _TOOLS.showNumber(totimptal, 2, "", 0) + "</td></tr>";
		//}

		//if (totimptat != 0) {
		//	_atrasados += "<tr><td style='text-align:center;border: 1px solid black'>TOTALES</td><td style='text-align:center;border: 1px solid black'>" + totcantat + "</td><td style='text-align:left;border: 1px solid black'>" + _TOOLS.showNumber(totportat, 1, "", 0) + "</td><td style='text-align:right;border: 1px solid black'>" + _TOOLS.showNumber(totimptat, 2, "", 0) + "</td></tr>";
		//}

		_footer += "</table>";
		//alert("after chabon " + _adelantados);
		_html = _header + _html + _footer + "<br/><br/>";

		

		//////$("#REPORT-CONTAINER").html(_html);
		var subtitulo="";
		if (modo=="T"){subtitulo=" Totalizado ";} else {subtitulo="";}

		var win = window.open("", "Reporte", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1024,height=768,top=" + (screen.height - 0) + ",left=" + (screen.width - 0));
		win.document.body.innerHTML = "<html><title>Libro Diario</title><body><img src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='window.print();'/><br/>Cementerio  <br/> Fecha: " + _TOOLS.getTodayDate("dmy", "/") + "<br/><h2 class='m-0 p-0' style='font-weight: bold; color: rgb(0,71,186);'><center>Libro Diario"+subtitulo+"</center></h2>" + titulo +  _html + "</body></html>";
	});

}

