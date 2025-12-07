function ConfirmarDeposito_CEM(_id_salida_cheque, _obj) {
	if (confirm('Confirmar el depósito?')) {
		var myJSON = '{"id_salida_cheque":"' + _id_salida_cheque + '"}';
		var myObj = JSON.parse(myJSON);
		_AJAX.UiConfirmarDepositos(myObj).then(function (datajson) {
			$(".btn-" + _id_salida_cheque).hide();
		}).catch(function (datajson) {
			$(sObj).html("<h2>Error al confirmar depósitos. Intente nuevamente</h2><br/><hr/>");
		});
	}
}
function ImprimirRecibo_CEM(id) {
	let x = { ID_Recibo: id };
	_FUNCTIONS.OnImprimirRecibo_CEM(null, x);
}
function AnularRecibo_CEM(id) {
	let x = { ID_Recibo: id };
	_FUNCTIONS.OnAnularRecibo_CEM(null, x);
}