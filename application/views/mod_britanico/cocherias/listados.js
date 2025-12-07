function showFilters() {
	//alert("Ad");
	var _html = "";
	_html = _html + _TOOLS.getTextBox("aDesde", "Desde", 3, "0", "Y", "class='form-control text dbase'");
	_html = _html + "<br/><br/>";
	_html = _html + _TOOLS.getTextBox("aHasta", "Hasta", 3, "9999999", "Y", "class='form-control text dbase'");

	$("#FILTER-CONTAINER").html(_html);
}

function showReport() {
	//alert("report");
	var desde = $('#TB-aDesde').val();
	var hasta = $('#TB-aHasta').val();

	//alert(desde + "-" + hasta);

	var myJSON = '{"DESDE":"' + desde + '", "HASTA":"' + hasta + '"}';
	var myObj = JSON.parse(myJSON);
	//alert("report2");

	_AJAX.UiGetRubros(myObj).then(function (datajson) {
		var _html = "";
		//alert("JSON: " + JSON.stringify(datajson.rubros));

		//_html = "<br/><div style='width: 100%;padding: 10px;border: 2px solid gray;margin: 0;'> <table style='width: 100%;padding: 10px' border=0>";
		_html += "<br/><table style='width: 100%;padding: 10px' border=0>";
		_html += "<tr><th style='text-align:left;border-bottom: 2px solid black'>Número de Cuenta</th><th style='text-align:left;border-bottom: 2px solid black'>Nombre de Cuenta</th></tr>";

		$.each(datajson.rubros, function (j, val1) {
			_html += "<tr><td style='text-align:left;'>" + val1.qryIndent + val1.NUMERO + "</td><td style='text-align:left;'>" + val1.qryIndent + val1.NOMBRE + "</td></tr>";
		});
		_html += "</table>";

		//$("#REPORT-CONTAINER").html(_html);

		var win = window.open("", "Reporte", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1024,height=768,top=" + (screen.height - 0) + ",left=" + (screen.width - 0));
		win.document.body.innerHTML = "<html><title>Reporte de Plan de Cuentas</title><body><img src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='window.print();'/><br/><br/>Cementerio Disidentes <br/> Fecha: " + _TOOLS.getTodayDate("dmy", "/") +"<br/><br/> <h1 class='m-0 p-0' style='font-weight: bold; color: rgb(0,71,186);'>Listado de Plan de Cuentas</h1>" + _html + "</body></html>";
	});

	
}
