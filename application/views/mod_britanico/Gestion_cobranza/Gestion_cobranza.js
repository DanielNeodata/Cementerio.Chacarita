function changeSegmento(idsegmento) {
	parametros = {idSegmento: idsegmento}
	_FUNCTIONS.OnGetGestionCobranza(null,parametros);
}
function VerActividadNotas() {
	let fechaActividad = $('#fechaActividad').val();
	var parametros = {fechaActividad: fechaActividad};
	_FUNCTIONS.OnGetActividadEnNotas(null,parametros);  // Hay que terminarla

}
function VerDetallesDeuda_CEM(idcliente, titulo){
	$('#contenidoGenerico').attr('data-id', idcliente);
	$('#contenidoGenerico').attr('data-titulo', titulo);
	_FUNCTIONS.onGetCuentaCorriente( $(this), "COMPLETA", idcliente, titulo);
}
