
function showReport() {
	//alert("report");
	var desde = $('#TB-aDesde').val();
	var hasta = $('#TB-aHasta').val();
	var destino = $("input:radio[name=DESTINO]:checked").val();
	//alert("Modo...");
	//alert(" M: "+modo);
	//alert("d: " + desde + " h: " + hasta + " r: ");

	var myJSON = '{"DESDE":"' + desde + '", "HASTA":"' + hasta + '"}';
	var myObj = JSON.parse(myJSON);

	//alert("report2");



	_AJAX.UiGetCashflow(myObj).then(function (datajson) {
		var _html = "";
		//alert("on then...");
		//alert("JSON estadistica: " + JSON.stringify(datajson.estadistica));
		//alert("json count "+datajson.estadistica.length);
		//alert("js count2: "+Object.keys(datajson.estadistica).length)
		var titulo = "";

		//alert("come back");
		var _header = "";
		var _footer = "";
		_header += "<br/><table style='width: 100%;padding: 10px' border=0 cellspacing=3>";
		
		//_header += "<tr><th style='text-align:left;border-bottom: 2px solid black' width=5%>TC</th><th style='text-align:left;border-bottom: 2px solid black'  width=9%>N.COMP</th><th style='text-align:left;border-bottom: 2px solid black'  width=9% >Nro de Cuenta</th><th style='text-align:left;border-bottom: 2px solid black'  width=35%>Nombre</th><th style='text-align:left;border-bottom: 2px solid black'  width=21%>Débitos</th><th style='text-align:left;border-bottom: 2px solid black'  width=21%>Créditos</th></tr>";
		

		var _tituloAnt = "";

		
		
		
		var hstyle=" style='text-align:right;border-bottom: 0px solid black' ";
		var hstyler=" style='text-align:right;border-bottom: 0px solid black' ";
		var rstyle=" style='text-align:right;border-bottom: 0px solid black' ";		
		var rstyler=" style='text-align:right;border-bottom: 0px solid black' ";		

		var fila = 0;
		$.each(datajson.estadistica, function (j, val1) {
			//alert("datos");

			if (val1.rowtype=="H")
			{
				//row header 
				_html=_html+"<tr bgcolor='"+val1.color+"' style='text-align:left;'><td colspan=5>"+val1.titulo+"</td></tr>";

			}
			if (val1.rowtype=="C")
			{
				//row campos como titulo
				_html=_html+"<tr bgcolor='"+val1.color+"'><td "+hstyle+" width=30%>CUENTAS</td><td "+hstyle+"  width=30%>INGRESOS</td><td "+hstyle+"  width=12% >DEBE</td><td "+hstyle+"  width=12%>HABER</td><td"+hstyle+"  width=16%>SALDO</td></tr>";
			}
			if (val1.rowtype=="D")
			{
				//row campos como titulo
				_html=_html+"<tr bgcolor='"+val1.color+"'><td "+rstyle+" width=30%>"+val1.cuenta.replace(/_br_/g,"<br/>")+"</td><td "+rstyle+"  width=30%>"+val1.ingresos+"</td><td "+rstyle+"  width=12% >"+_TOOLS.showNumber(val1.debe, 2, "", "0")+"</td><td "+rstyle+"  width=12%>"+ _TOOLS.showNumber(val1.haber, 2, "", "0")+"</td><td"+rstyle+"  width=16%>"+_TOOLS.showNumber(val1.saldo, 2, "", "0")+"</td></tr>";
			}
			if (val1.rowtype=="T")
			{
				//row campos como titulo
				_html=_html+"<tr bgcolor='"+val1.color+"'><td "+rstyle+" width=30%>"+val1.cuenta+"</td><td "+rstyle+"  width=30%>"+val1.ingresos+"</td><td "+rstyle+"  width=12% >"+_TOOLS.showNumber(val1.debe, 2, "", "0")+"</td><td "+rstyle+"  width=12%>"+_TOOLS.showNumber(val1.haber, 2, "", "0")+"</td><td"+rstyle+"  width=16%>"+_TOOLS.showNumber(val1.saldo, 2, "", "0")+"</td></tr>";
			}
			if (val1.rowtype=="G")
			{
				//row campos como titulo
				_html=_html+"<tr bgcolor='"+val1.color+"'><td "+rstyle+" width=30%>"+val1.cuenta+"</td><td "+rstyle+"  width=30%>"+val1.ingresos+"</td><td "+rstyle+"  width=12% >"+_TOOLS.showNumber(val1.debe, 2, "", "0")+"</td><td "+rstyle+"  width=12%>"+_TOOLS.showNumber(val1.haber, 2, "", "0")+"</td><td"+rstyle+"  width=16%>"+_TOOLS.showNumber(val1.saldo, 2, "", "0")+"</td></tr>";
			}
			
			fila = fila + 1 ;
		});
	/*renlon d cierre*/
		
		//alert("emd each");

		_footer += "</table>";
		//alert("after chabon " + _adelantados);
		_html = _header + _html + _footer + "<br/><br/>";

		

		//////$("#REPORT-CONTAINER").html(_html);
		var subtitulo="";
		

		var win = window.open("", "Reporte", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1024,height=768,top=" + (screen.height - 0) + ",left=" + (screen.width - 0));
		win.document.body.innerHTML = "<html><title>Cashflow</title><body><img src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='window.print();'/><br/>Cementerio  <br/> Fecha: " + _TOOLS.getTodayDate("dmy", "/") + "<br/><h2 class='m-0 p-0' style='font-weight: bold; color: rgb(0,71,186);'><center>Cashflow"+subtitulo+"</center></h2>" + titulo +  _html + "</body></html>";
	});

}

