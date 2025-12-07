
function startAbm(){
	alert("sarasa11111");
}

function validReng() {
	//alert("1");
	enableSendForm();
	var cboRenCue = document.getElementById('SELECT-CTA');
	var cboRenDeb = document.getElementById('SELECT-DH');
	var txtRenCod = document.getElementById('TIPCOM');
	var txtRenNum = document.getElementById('NUMCOM');
	var txtRenImp = document.getElementById('IMPORTE');
	var btnAddRen = document.getElementById('OPER-ADD-BTN');
	//alert("2");
	if (
		(cboRenCue.options[cboRenCue.selectedIndex].value == "0") ||
		(cboRenDeb.options[cboRenDeb.selectedIndex].value == "0") ||
		(txtRenCod.value == "") ||
		(isNaN(parseFloat(txtRenNum.value))) ||
		(isNaN(parseFloat(txtRenImp.value)))
	) {
		btnAddRen.disabled = true;
	}
	else {
		btnAddRen.disabled = false;
	}

	enableSendForm();
	//alert("out");
}


function enableSendForm() {
/*el balance tiene que ser 0*/
	var bce = parseFloat($("#TOTAL-1").val());
	$("#RENGLONES").prop('disabled', true);

	if (bce != 0) {
		$(".btn-abm-accept-confirm").prop('disabled', true);
		return false;
	}

	var rows = Number($("#OPER-COUNTER").val());
	$("#RENGLONES").val(rows);

	if (rows == 0) {
		$(".btn-abm-accept-confirm").prop('disabled', true);
		return false;
	}

	var mod = Number($("#OPER-MODIFIED").val());
	if (mod == 0) {
		$(".btn-abm-accept-confirm").prop('disabled', true);
		return false;
	}
	
	$(".btn-abm-accept-confirm").prop('disabled', false);
	return true;
	//
}

function deleteRow(nombre, importe, fila) {
	var rows = parseFloat($("#OPER-COUNTER").val());
	var row = parseFloat(fila);
	var total = parseFloat($("#TOTAL-1").val());
	var descontar = parseFloat($("#" + importe).val());
	var _html = "";
	//alert("antes delete");
	$("#" + nombre).remove();
	if (row < rows) {
		/*si la fila es menor a la cant de filas tengo que renumerar todo, inclusive las anteriores xq reemplazo todo el html*/
		var i = 0;
		var j = 1;/*este es el numerador*/
		//alert("en if row rows");
		for (i = 1; i <= rows; i++) {
			/*la fila a borrar ya la borre por lo que la salteo*/
			//alert("en for i="+i+" de rows: "+rows+" fila: "+fila);
			if (fila != i) {
				//alert("en fila dis i");
				var cuenta = $("#detail-cuenta-" + i).val();
				//alert("cuenta: "+cuenta);
				var cuentanom = $("#detail-nombre-" + i).val();
				//alert("cuentanom: " + cuentanom);
				var dh = $("#detail-dh-" + i).val();
				//alert("DH: "+dh);
				var tipcom = $("#detail-tipcom-" + i).val();
				//alert("tipcom: " + tipcom);
				var numcom = $("#detail-numcom-" + i).val();
				//alert("numcom: " + numcom);
				var comentario = $("#detail-comentario-" + i).val();
				//alert("comentario: " + comentario);
				var importe = $("#detail-importe-" + i).val();
				//alert("importe: " + importe);

				//alert("vars set j: " + j + " i: " + i + " cuenta: " + cuenta + " nombre " + cuentanom + " DH: " + dh + " tipcom: " + tipcom + " nomcom: " + numcom + " comm: " + comentario + " imp: " +importe );
				

				_html += "<tr id='detail-form-row-" + j + "'>";
				_html += "   <td><a href='#' onClick=\"javascript:deleteRow('detail-form-row-" + j + "','detail-importe-" + j + "','" + j + "');return false;\">eliminar</a></td>";
				_html += "   <td id='detail-form-row-" + j + "-id'>" + j + "</td>";
				_html += "   <td id='detail-form-row-" + j + "-cuenta'>" + cuenta + "</td>";
				_html += "   <td id='detail-form-row-" + j + "-nombre'>" + cuentanom + "</td>";
				_html += "   <td id='detail-form-row-" + j + "-dh'>" + dh + "</td>";
				_html += "   <td id='detail-form-row-" + j + "-tipcom'>" + tipcom + "</td>";
				_html += "   <td id='detail-form-row-" + j + "-numcom'>" + numcom + "</td>";
				
				_html += "   <td id='detail-form-row-" + j + "-importe'>" + importe;

				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-id-" + j + "' name='detail-id-" + j + "' value='" + j + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-cuenta-" + j + "' name='detail-cuenta-" + j + "' value='" + cuenta + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-nombre-" + j + "' name='detail-nombre-" + j + "' value='" + cuentanom + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-dh-" + j + "' name='detail-dh-" + j + "' value='" + dh + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-tipcom-" + j + "' name='detail-tipcom-" + j + "' value='" + tipcom + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-numcom-" + j + "' name='detail-numcom-" + j + "' value='" + numcom + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-comentario-" + j + "' name='detail-comentario-" + j + "' value='" + comentario + "'></input>";
				_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-importe-" + j + "' name='detail-importe-" + j + "' value='" + importe + "'></input>";


				//alert("reg set");
				
				_html += "</td>";
				_html += "</tr>";

				//alert("alert j and i "+i+" - "+j);

				$("#detail-form-row-" + i).remove();

				//alert("deleted");
				j = j + 1;
			}
		}
		/*ahora reemplazo los datos de la tabla*/
		//alert(_html);
		$("#table-detail").append(_html);
	}
	rows = rows - 1;
	//alert(rows);
	$("#OPER-COUNTER").val(rows);
	total = (total - descontar);
	total = total.toFixed(2);
	$("#TOTAL-1").val(total);
	var mod = parseFloat($("#OPER-MODIFIED").val());
	$("#OPER-MODIFIED").val(mod + 1);
	//alert("Dond done");
	//actualizar total
	//actualizar contador de filas
	enableSendForm();
}

function insertarOnClick() {

	var rows = Number($("#OPER-COUNTER").val());

	//alert("rows actuales: "+rows);
	rows = rows + 1;
	validReng();

	
	var _html = "";
	var cuenta = $("#SELECT-CTA option:selected").val();
	var cuentanom = $("#SELECT-CTA option:selected").text();

	var dh = Number($("#SELECT-DH option:selected").val());

	//alert("insertar: " + dh);
	/*Pagos a cuenta*/

	var importe = 0;

	try {
		importe = $("#IMPORTE").val();
		//alert(apertura);
		if (importe == "") { alert("Debe ingresar un número en importe mayor o igual a cero"); return; }
		importe = parseFloat(importe);

		if (isNaN(importe)) { alert("Debe ingresar un número en importe mayor o igual a cero"); return; }

		if (importe < 0) { alert("El número en importe tiene que ser mayor o igual a cero"); return; }
	} catch (err) {
		alert("Error al buscar número en importe");
		return;
	}

	var dh2 = "D";
	if (dh == 2) { importe = importe * -1; dh2 = "H";}/*si es haber va en negativo*/

	var tipcom = $("#TIPCOM").val();
	var numcom = $("#NUMCOM").val();
	var comentario = $("#DCOMENTARIO").val();
	//alert("insertar: " + importe);
	var total = parseFloat($("#TOTAL-1").val());
	//alert("insertar: " + total);
	total = (total + importe);
	//alert("insertar sumado: " + total);

	_html = "";

	_html += "<tr id='detail-form-row-" + rows + "'>";
	_html += "   <td><a href='#' onClick=\"javascript:deleteRow('detail-form-row-" + rows + "','detail-importe-" + rows + "','" + rows + "');return false;\">eliminar</a></td>";
	_html += "   <td id='detail-form-row-" + rows + "-id'>" + (rows) + "</td>";
	_html += "   <td id='detail-form-row-" + rows + "-cuenta'>" + cuenta + "</td>";
	_html += "   <td id='detail-form-row-" + rows + "-nombre'>" + cuentanom + "</td>";
	_html += "   <td id='detail-form-row-" + rows + "-dh'>" + dh2 + "</td>";
	_html += "   <td id='detail-form-row-" + rows + "-tipcom'>" + tipcom + "</td>";
	_html += "   <td id='detail-form-row-" + rows + "-numcom'>" + numcom + "</td>";
	_html += "   <td id='detail-form-row-" + rows + "-imp'>" + importe;
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-id-" + rows + "' name='detail-id-" + rows + "' value='" + (rows) + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-cuenta-" + rows + "' name='detail-cuenta-" + rows + "' value='" + cuenta + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-nombre-" + rows + "' name='detail-nombre-" + rows + "' value='" + cuentanom + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-dh-" + rows + "' name='detail-dh-" + rows + "' value='" + dh2 + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-tipcom-" + rows + "' name='detail-tipcom-" + rows + "' value='" + tipcom + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-numcom-" + rows + "' name='detail-numcom-" + rows + "' value='" + numcom + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-comentario-" + rows + "' name='detail-comentario-" + rows + "' value='" + comentario + "'></input>";
	_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-importe-" + rows + "' name='detail-importe-" + rows + "' value='" + importe + "'></input>";
	_html += "</td>";
	_html += "</tr>";

	$("#OPER-COUNTER").val(rows);
	$("#TOTAL-1").val(total);
	$("#PESOS").val(total);
	$("#table-detail").append(_html);

	var mod = Number($("#OPER-MODIFIED").val());
	$("#OPER-MODIFIED").val(mod + 1);

	enableSendForm();

	//adjustTotalzPlain(0);

}






