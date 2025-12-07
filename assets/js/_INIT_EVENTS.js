(function () {
	var today = new Date();
	$.getScript("./assets/js/AJAX.js?" + today.toDateString()).done(function (script, textStatus) {
		$.getScript("./assets/js/TOOLS.js?" + today.toDateString()).done(function (script, textStatus) {
			$.getScript("./assets/js/FUNCTIONS.js?" + _TOOLS.UUID()).done(function (script, textStatus) {
				$.getScript("./assets/js/generics.js?" + _TOOLS.UUID()).done(function (script, textStatus) {

					moment().tz("America/Argentina/Buenos_Aires").format();
					window.addEventListener("dragover", function (e) { e = e || event; e.preventDefault(); }, false);
					window.addEventListener("drop", function (e) { e = e || event; e.preventDefault(); }, false);

					$("body").off("keyup", ".search-trigger").on("keyup", ".search-trigger", function (e) {
						var keyCode = (e.keyCode || e.which);
						if (keyCode === 13) { _FUNCTIONS.onBrowserSearch($(".btn-browser-search")); }
					});
					$("body").off("change", ".search-trigger").on("change", ".search-trigger", function () {
						if ($(this).is("select") === true) { _FUNCTIONS.onBrowserSearch($(".btn-browser-search")); }
					});
					$("body").off("click", ".btn-login").on("click", ".btn-login", function () {
						_FUNCTIONS.onLogin($(this)).then(function (data) { _AJAX.UiLogged({}); });
					});

					//$("body").off("dblclick", ".record-dbl-click").on("dblclick", ".record-dbl-click", function () {
					//	_FUNCTIONS.onRecordEdit($(this));
					//});

					$("body").off("click", ".openModal").on("click", ".openModal", function () {
						_FUNCTIONS.onShowInfo(atob($(this).attr("data-body")), "Detalle del recibo");
					});
					$("body").off("click", ".toggleTR").on("click", ".toggleTR", function () {
						var _key = (".detalle-" + $(this).attr("data-id"));
						if ($(_key).hasClass("d-none")) {
							$(_key).removeClass("d-none");
						} else {
							$(_key).addClass("d-none");
						}
					});

					$("body").off("click", ".btn-logout").on("click", ".btn-logout", function () {
						_FUNCTIONS.onLogout($(this));
					});
					$("body").off("click", ".btn-menu-open").on("click", ".btn-menu-open", function (e) {
						_FUNCTIONS.onMenuOpen($(this), e);
					});
					$("body").off("click", ".btn-menu-close").on("click", ".btn-menu-close", function (e) {
						_FUNCTIONS.onMenuClose($(this), e);
					});
					$("body").off("click", ".btn-menu-click").on("click", ".btn-menu-click", function (e) {
						_FUNCTIONS.onMenuClick($(this));
					});
					$("body").off("click", ".btn-record-edit").on("click", ".btn-record-edit", function (e) {
						_FUNCTIONS.onRecordEdit($(this));
					});
					$("body").off("click", ".btn-check-paycode").on("click", ".btn-check-paycode", function (e) {
						_FUNCTIONS.onCheckPaycode($(this));
					});
					$("body").off("click", ".btn-record-remove").on("click", ".btn-record-remove", function (e) {
						_FUNCTIONS.onRecordRemove($(this));
					});
					$("body").off("click", ".btn-record-offline").on("click", ".btn-record-offline", function (e) {
						_FUNCTIONS.onRecordOffline($(this));
					});
					$("body").off("click", ".btn-record-online").on("click", ".btn-record-online", function (e) {
						_FUNCTIONS.onRecordOnline($(this));
					});
					$("body").off("click", ".btn-record-process").on("click", ".btn-record-process", function (e) {
						_FUNCTIONS.onRecordProcess($(this));
					});
					$("body").off("click", ".btn-abm-accept").on("click", ".btn-abm-accept", function (e) {
						try {
							$(".html").each(function () {
								//alert(this.name);
								$("#" + this.name).val($('.nicEdit-main').html());
							});
							//$('#ModeloNotificacionHtml').val($('.nicEdit-main').html());
						} catch (error) {
							console.error(error);
						}
						_FUNCTIONS.onAbmAccept($(this));
					});


					$("body").off("click", ".btn-abm-accept-confirm").on("click", ".btn-abm-accept-confirm", function (e) {
						try {
							if (!confirm("¿Confirma la operación?")) { return false; }
							$(".html").each(function () {
								//alert(this.name);
								$("#" + this.name).val($('.nicEdit-main').html());
							});
							//$('#ModeloNotificacionHtml').val($('.nicEdit-main').html());
						} catch (error) {
							console.error(error);
						}

						_FUNCTIONS.onAbmAccept($(this));
					});

					$("body").off("click", ".btn-abm-cancel").on("click", ".btn-abm-cancel", function (e) {
						_FUNCTIONS.onAbmCancel($(this));
					});
					$("body").off("click", ".btn-browser-search").on("click", ".btn-browser-search", function (e) {
						_FUNCTIONS.onBrowserSearch($(this));
					});
					$("body").off("click", ".btn-excel-search").on("click", ".btn-excel-search", function (e) {
						_FUNCTIONS.onBrowserSearch($(this));
					});
					$("body").off("click", ".btn-pdf-search").on("click", ".btn-pdf-search", function (e) {
						_FUNCTIONS.onBrowserSearch($(this));
					});
					$("body").off("click", ".btn-brief").on("click", ".btn-brief", function (e) {
						_FUNCTIONS.onBriefModal($(this));
					});
					$("body").off("click", ".btn-verify-signs").on("click", ".btn-verify-signs", function (e) {
						_FUNCTIONS.onVerifySigns($(this));
					});
					$("body").off("click", ".btn-close-modal").on("click", ".btn-close-modal", function (e) {
						$($(this).attr("data-click")).click();
						_FUNCTIONS.onDestroyModal(".modal");
					});
					$("body").off("click", ".btn-upload").on("click", ".btn-upload", function (e) {
						$($(this).attr("data-click")).click();
					});
					$("body").off("click", ".btn-upload-reset").on("click", ".btn-upload-reset", function (e) {
						_FUNCTIONS.onResetSelectedFile($(this));
					});
					$("body").off("click", ".btn-upload-delete").on("click", ".btn-upload-delete", function (e) {
						_FUNCTIONS.onDeleteSelectedFile($(this));
					});
					$("body").off("change", ".btn-pick-files-image").on("change", ".btn-pick-files-image", function (e) {
						_FUNCTIONS.onProcessSelectedFiles($(this));
					});
					$("body").off("change", ".btn-pick-files-image_apaisada").on("change", ".btn-pick-files-image_apaisada", function (e) {
						_FUNCTIONS.onProcessSelectedFiles($(this));
					});
					$("body").off("change", ".btn-folders-files-folders").on("change", ".btn-folders-files-folders", function (e) {
						_FUNCTIONS.onProcessSelectedFilesFolders($(this));
					});
					$("body").off("change", ".id_type_command").on("change", ".id_type_command", function (e) {
						_FUNCTIONS.onTypeCommandChange($(this));
					});
					$("body").off("click", ".btn-message-external").on("click", ".btn-message-external", function (e) {
						_FUNCTIONS.onFolderMessagesModal($(this));
					});
					$("body").off("click", ".btn-message-read").on("click", ".btn-message-read", function (e) {
						_FUNCTIONS.onMessageRead($(this));
					});
					$("body").off("click", ".btn-record-check").on("click", ".btn-record-check", function (e) {
						_FUNCTIONS.onCheckRecord($(this));
					});
					$("body").off("click", ".btn-external-link").on("click", ".btn-external-link", function () {
						_FUNCTIONS.onAddLinkExternal($(this));
					});

					$("body").off("click", ".btn-external-link1").on("click", ".btn-external-link1", function () {
						_FUNCTIONS.onAddLinkExternal1($(this));
					});

					$("body").off("click", ".btn-receipt-search").on("click", ".btn-receipt-search", function (e) {
						_FUNCTIONS.onReceiptSearch($(this));
					});

					$("table .table-browser").on("load", function () {
						alert("init load");
					});
					$("body").off("click", ".historico").on("click", ".historico", function (e) {
						_FUNCTIONS.onAClickHistoricoInhumado($(this));
					});
					$("body").off("click", ".borrarmov").on("click", ".borrarmov", function (e) {
						_FUNCTIONS.onAClickBorrarHistorico($(this));
					});
					$("body").off("click", ".HistoricoParcela_CEM").on("click", ".HistoricoParcela_CEM", function (e) {
						_FUNCTIONS.onAClickGetHistoricoParcela($(this));
					});
					$("body").off("click", ".ContratosArrendamiento_CEM").on("click", ".ContratosArrendamiento_CEM", function (e) {
						_FUNCTIONS.onAClickGetContratosArrendamiento($(this));
					});
					$("body").off("click", ".borrarinhumado").on("click", ".borrarinhumado", function (e) {
						_FUNCTIONS.onAClickBorrarHistorico($(this));
					});
					$("body").off("click", ".borrarclirel").on("click", ".borrarclirel", function (e) {
					});
					$("body").off("keyup", "#entrada").on("keyup", "#entrada", function (e) {
						_FUNCTIONS.onKeyUpEntrada($(this));
					});
					$("body").off('click', '.suggest-element').on('click', '.suggest-element', function () {
						var id = $(this).attr('id');
						var dataid = $(this).attr('data-id');
						var detalle = $(this).text();
						var idcliente = dataid.split('.')[0];
						var idpagador = dataid.split('.')[1];
						$('#resultados').fadeOut(1000);
						$('#entrada').attr('data-idcliente', idcliente);
						$('#entrada').attr('data-idpagador', idpagador);
						$('#entrada').val(detalle + "|*|" + dataid);
						$('#borrarentrada').val("<input type='button' class='btn borrarcliente btn-sm btn-outline-dange' value='XX'>X</input>");
						return false;
					});
					$("body").off('click', '#borrarentrada').on('click', '#borrarentrada', function () {
						alert("Borrando...click #borrarentrada");
						this
						$('#entrada').val("");
						$('#entrada').attr("data-idcliente", "");
						$('#entrada').attr("data-idclientepagador", "");
						$('#borrarentrada').val("");
						return false;
					});
					$("body").off('click', '.borrarcliente').on('click', '.borrarcliente', function () {
						if (confirm("Esta a punto de borrar la relacion cliente/Parcela.\nEsta seguro?")) {
							idClienteRelacionado = $(this).attr("data-idClienteRelacionado");
							_FUNCTIONS.onAClickBorrarClientePagador($(this));
							return false;
						} else {
							return false;
						}
					});
					$("body").off("load", "#entrada").on("load", "#entrada", function (e) {
						_FUNCTIONS.onLoadEntrada($(this));
					});
					$(function () { });
					$("body").off("keyup", "#entradaPagador").on("keyup", "#entradaPagador", function (e) {
						_FUNCTIONS.onKeyUpEntradaPagador($(this));
					});
					$("body").off("dblclick", "#entradaPagadorRecibo").on("dblclick", "#entradaPagadorRecibo", function (e) {
						let resp = confirm("Limpiar Búsquedas?");
						if (resp) {
							$('#entradaPagadorRecibo').val('');
							_FUNCTIONS.onGetCuentaCorriente(null, "COMPLETA", 0, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'true');
						}
					});
					$("body").off('click', '.suggest-element-pagador').on('click', '.suggest-element-pagador', function () {
						var id = $(this).attr('id');
						var dataid = $(this).attr('data-id');
						var detalle = $(this).text();
						var idcliente = dataid.split('.')[0];
						var idpagador = dataid.split('.')[1];
						$('#resultadosPagador').fadeOut(1000);
						var idpagadorABM = $('#entradaPagador').attr('data-idpagador');
						if (idpagadorABM == 0) {
							if (idpagador == 0 || idpagador == null || idpagador == "") {
								alert("El pagador aún no fue grabado, solo podrá relacionar pagador y cliente con un pagador existente. Luego de grabar el pagador vuelva a modificarlo para así relacionarlo con un cliente.");
								return false;
							}
						}
						let x = '<tr><td>' + dataid + '</td><td>' + detalle + '</td><td></td></tr>';
						$('#clientesRelacionadoConPagador tr:last').after(x);
						idpagador = $('#entradaPagador').attr('data-idpagador'); // data-idpagador
						var obj = { id_cliente: idcliente, id_pagador: idpagador };
						var j = JSON.stringify(obj);
						_AJAX.UiAsociarClienteParaPagador(obj).then(function (datajson) {
							let data = datajson.cliente_pagador;
						});
						return false;
					});
					$("body").off('click', '#agregarNotaAlCliente').on('click', '#agregarNotaAlCliente', function () {
						_FUNCTIONS.onInsertarNotaEnCliente($(this));
						return false;
					});
					$("body").off('click', '#btnVerHistoricoTitularidadCliente').on('click', '#btnVerHistoricoTitularidadCliente', function () {
						$('#myModalHistorico').modal('toggle');
					});
					$("body").off('click', '#btnObservacionesCliente').on('click', '#btnObservacionesCliente', function () {
						$("#trDatosComplementarios").toggle();
						_FUNCTIONS.onGetObservacionesCliente($(this));
					});
					$("body").off('click', '#btnCuentaCorriente').on('click', '#btnCuentaCorriente', function () {
						if ($('#principalCliente').length) {
							_FUNCTIONS.onGetCuentaCorriente($(this), "COMPLETA");
						} else {
							let idcliente = $('#id_cliente').val();
							_FUNCTIONS.onGetCuentaCorriente(null, "COMPLETA", idcliente, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'true', 'false');
						}
					});
					$("body").off('click', '#btnCuentaCorrienteImpaga').on('click', '#btnCuentaCorrienteImpaga', function () {
						if ($('#principalCliente').length) {
							_FUNCTIONS.onGetCuentaCorriente($(this), "IMPAGA");
						} else {
							let idcliente = $('#id_cliente').val();
							_FUNCTIONS.onGetCuentaCorriente(null, "IMPAGA", idcliente, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'true', 'false');
						}
					});
					$("body").off('click', '#btnResumenDeuda').on('click', '#btnResumenDeuda', function () {
						_FUNCTIONS.onGetResumenDeDeuda($(this), "RESUMENDEUDA");
					});
					$("body").off('click', '#btnPlanPago').on('click', '#btnPlanPago', function () {
						_FUNCTIONS.onArmarPlanDePago($(this));
					});
					$('body').off('change', '#selCantCuotas').on('change', '#selCantCuotas', function () { });
					$('body').off('change', '#selCantCuotas').on('change', '#selCantCuotas', function () {
						let cuotas = parseInt($(this).val());
						$('#cantCuotas').html(cuotas);
						let totalPlan = parseFloat($('#totalPlan').val()).toFixed(2);
						let valorCuota = 0.0;
						if (cuotas > 0) {
							valorCuota = parseFloat(parseFloat(totalPlan) / parseFloat(cuotas)).toFixed(2);
							$('#valorCuota').html(valorCuota);
						}
					});
					$('body').off('change', '#totalPlan').on('change', '#totalPlan', function () {
						let totalPlan = parseFloat($('#totalPlan').val()).toFixed(2);
						let cuotas = parseInt($('#cantCuotas').html());
						let finan = $('#financia').val();
						if (finan == 'F') {
							$('#deudaNuevaObs').html(finan);
							$('#deudaAnteriorObs').html(finan);
						} else {
							$('#deudaNuevaObs').html(finan);
							$('#deudaAnteriorObs').html(finan);
						}
						$('#cantCuotas').html(cuotas);
						let valorCuota = 0.0;
						if (cuotas > 0) {
							valorCuota = parseFloat(parseFloat(totalPlan) / parseFloat(cuotas)).toFixed(2);
							$('#valorCuota').html(valorCuota);
						} else {
							$('#valorCuota').html('');
						}
					});
					$('#totalPlan').keyup(function () {
						alert("Key up detected");
					});
					$('body').off('change', '.financiacion').on('change', '.financiacion', function () {
						let finan = $('#financia').val();
						if (finan == 'F') {
							$('#deudaNuevaObs').html(finan);
							$('#deudaAnteriorObs').html(finan);
						}
						if (finan == 'R') {
							$('#deudaNuevaObs').html(finan);
							$('#deudaAnteriorObs').html(finan);
						}
						let texto = '';
						if (financiacion == 'F') {
							texto += 'Financiación';
						}
						if (financiacion == 'R') {
							texto += 'Refinanciación';
						}
						let totalPlan = parseFloat($('#totalPlan').val());
						if (totalPlan > 0) {
							texto += ' de $' + totalPlan.toFixed(2);
						}
						$('#totalPlanObs').html(texto);
					});

					$('body').off('change', '#planDePago').on('change', '#planDePago', function () {
						let i = 0;
						let totalDeudaIncluidaEnPlan = parseFloat(0.00);
						let listaItemsParaIncluir = "";
						$('.deudaParaPlan:checked').each(function () {
							totalDeudaIncluidaEnPlan = (parseFloat(totalDeudaIncluidaEnPlan) + parseFloat($(this).attr("data-saldo"))).toFixed(2);
							let itemIncluido = $(this).attr("data-id");
							listaItemsParaIncluir += itemIncluido + ", ";
							i++;
						});
						$('#itemsDeuda').html(i);
						$('#itemsCtaCte').html(listaItemsParaIncluir);
						$('#totalDeuda').html(totalDeudaIncluidaEnPlan);
						$('#totalPlan').val(totalDeudaIncluidaEnPlan); ///// ver de permitir poner lo que el usuario quiera.
						let finan = $('#financia').val();
						if (finan == 'F') {
							$('#deudaNuevaObs').html(finan);
							$('#deudaAnteriorObs').html(finan);
						}
						if (finan == 'R') {
							$('#deudaNuevaObs').html(finan);
							$('#deudaAnteriorObs').html(finan);
						}
						let cuotas = parseFloat($('#cantCuotas').html());
						let total = parseFloat($('#totalPlan').val());
						if (cuotas > 0) {
							valorCuota = total / cuotas;
							$('#valorCuota').html(valorCuota.toFixed(2));
						}
					});
					$('body').off('click', '#btnGenerarPlan').on('click', '#btnGenerarPlan', function () {
						let items = $('#itemsCtaCte').html();
						let totalPlan = parseFloat($('#totalPlan').val());
						let totalDeuda = parseFloat($('#totalDeuda').html());
						let tipo = $('#deudaNuevaObs').html();
						let cuotas = parseInt($('#cantCuotas').html());
						let valorCuota = parseFloat($('#valorCuota').html());
						if (!(items.length > 0) || !(totalPlan > 0) || !(totalDeuda > 0) || !(cuotas > 0) || !(valorCuota > 0)) {
							alert("Complete los datos Requeridos");
							return;
						} else {
							if (!(tipo == 'R') && !(tipo == 'F')) {
								alert("Complete la financiacion");
							} else {
								_FUNCTIONS.onGenerarPlanDePagos($(this));
							}
						}
					});
					$("body").off('click', '#btnCuentaCorrienteHistorica').on('click', '#btnCuentaCorrienteHistorica', function () {
						_FUNCTIONS.onGetCuentaCorrienteHistorica($(this), '#div_cuenta_corriente', "", "true", "true");
					});
					$('body').off('change', '#listapreciosDataCC').on('change', '#listapreciosDataCC', function () {
						var precio3 = $("#listapreciosDataCC option:selected").attr('data-precio');
						$('#importeCC').val(precio3);
					});
					$('body').off('click', '.botonCC').on('click', '.botonCC', function () {
						let _row = $(this).closest("tr");
						if (_row.hasClass("highlight")) { _row.removeClass('highlight'); } else { _row.addClass('highlight').siblings().removeClass('highlight'); }
						let idcc = $(this).closest("tr").attr("data-id"); // hago click en el tr->td->button
						let tipo = "";
						let mod = $(this).hasClass("botonModCC");
						let del = $(this).hasClass("botonDelCC");
						let ins = $(this).hasClass("botonInsCC");
						if (mod) {
							tipo = "M";
						} else if (del) {
							tipo = "D";
						} else if (ins) {
							tipo = "I";
						}
						let v = $(this).val();
						let opCC = {};
						if (tipo == "I") {
							let h = $('#tablaEdicion').hasClass('d-none');
							if (h) {
								$('#tablaEdicion').removeClass('d-none');
								$('#tablaAlta').removeClass('d-none');
							} else {
								$('#tablaEdicion').addClass('d-none');
								$('#tablaAlta').addClass('d-none');
							}
							let hoy = new Date().toISOString().substring(0, 10);
							let edicion =
								"<div class='container'>" +
								"<div class='row'>" +
								"<label for='tipocomprobante'>Tipos Comprobante: </label><select id='tipoComprobanteCC'></select>" + // Tipo Comprobante
								"<label for='parcela'>Parcela: </label><select id='parcelasDataCC'></select>" + // Parcela
								"<label for='FechaInicio'>Fecha Inicio Periodo: </label><input id='FechaInicioCC' name='FechaInicio' type='date' value='" + hoy + "'>" +  //  @fecha_alta 
								"</div>" +
								"<div class='row'>" +
								"<label for='operacion'>Operacion: </label><select id='listapreciosDataCC'></select>" + // Operacion
								"<label for='importe'>Importe: </label><input id='importeCC' name='importe' type='number' min='0.00' step='0.01' placeholder='1.00'>" + // Importe
								"<label for='idinhumado'>Inhumado: </label><select id='selectInhumadosDataCC'></select>" + // Inhumado
								"</div>" +
								"<div class='row'>" +
								"<label for='descripcion'>Descripcion: </label><textarea id='descripcionCC' name='descripcion' rows='3' cols='75'></textarea>" +  //  Descripcion
								"<button id='btnAgregarConceptoCC' class='col btn-sm btn-outline-success'>1 - Agregar Concepto</button>" +
								"<button id='btnGrabarConceptosCC' class='col btn-sm btn-primary'>2 - GRABAR</button>";
							"</div>";

							let alta =
								"<table>" +
								"<thead>" +
								"<tr>" + "Operacion" + "</th>" +
								"<tr>" + "Descripcion" + "</th>" +
								"<tr>" + "Importe" + "</th>" +
								"<tr>" + "Inhumado" + "</th>" +
								"<tr>" + "" + "</th>" +
								"</thead>" +
								"<tbody>" +
								"</tbody>" +
								"</table>" +

								$('#tablaEdicion').html(edicion);
							var $select = $('#tipoComprobanteData').clone().attr('id', 'tipoComprobanteDataCloned');
							$('#tipoComprobanteCC').html($select.html()).removeClass('d-none');
							var $selectListaData = $('#listapreciosData').clone().attr('id', 'listapreciosDataCloned');
							$('#listapreciosDataCC').html($selectListaData.html()).removeClass('d-none');
							var $parcelasData = $('#parcelasData').clone().attr('id', 'parcelasDataCloned');
							$('#parcelasDataCC').html($parcelasData.html()).removeClass('d-none');
							var $selectInhumadosData = $('#selectInhumadosData').clone().attr('id', 'selectInhumadosDataCloned');
							$('#selectInhumadosDataCC').html($selectInhumadosData.html()).removeClass('d-none');
							$('#tablaAlta').html(''); // reseteo
						}
						if (tipo == "M") {
							opCC = { idCuentaCorriente: idcc, operacion: tipo };
							_FUNCTIONS.OnClickModificarCuentaCorriente($(this), opCC);
						}
						if (tipo == "D") {
							if (!confirm("Desea borrar el ítem de la CC?")) { return false; }
							opCC = { idCuentaCorriente: idcc, operacion: tipo };
							let resp = 0;
							resp = _FUNCTIONS.OnClickBorrarCuentaCorriente($(this).closest("tr"), opCC);
							if (resp > 0) {
								_row.remove();
							}
						}
					});
					$('.masterTooltip').hover(function () {
						var title = $(this).attr('title');
						$(this).data('tipText', title).removeAttr('title');
						$('<p class="tooltip"></p>')
							.text(title)
							.appendTo('body')
							.fadeIn('slow');
					}, function () {
						$(this).attr('title', $(this).data('tipText'));
						$('.tooltip').remove();
					}).mousemove(function (e) {
						var mousex = e.pageX + 20; //Get X coordinates
						var mousey = e.pageY + 10; //Get Y coordinates
						$('.tooltip').css({ top: mousey, left: mousex })
					});

					$('body').off('.solo_decimal').on('.solo_decimal', function () {
						var self = $(this);
						self.val(self.val().replace(/[^0-9\.]/g, ''));
						if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
							evt.preventDefault();
						}
					});
					$('body').off('.solo_entero').on('.solo_entero', function () {
						var self = $(this);
						self.val(self.val().replace(/\D/g, ""));
						if ((evt.which < 48 || evt.which > 57)) {
							evt.preventDefault();
						}
					});
					$('body').off('click', '#tablaAlta tbody tr button').on('click', '#tablaAlta tbody tr', function () {
						$(this).closest("tr").remove();
						return false;
					});
					$('body').off('click', '#btnGrabarConceptosCC').on('click', '#btnGrabarConceptosCC', function () {
						let codigo = '', descripcion = '', importe = 0.0, inhumado = null, fechaInicio = null, parcela, tipoComprobante;
						codigo = $('#listapreciosDataCC').val();
						inhumado = $('#selectInhumadosDataCC').val();
						importe = $('#importeCC').val();
						descripcion = $('#descripcionCC').val();
						tipoComprobante = $('#tipoComprobanteCC').val();
						parcela = $('#parcelasDataCC').val();
						fechaInicio = $('#FechaInicioCC').val();
						if (tipoComprobante == 'undefined' || tipoComprobante == null ||
							parcela == 'undefined' || parcela == null ||
							fechaInicio == 'undefined' || fechaInicio == null) {
							alert("Complete datos de la cabecera de los movimientos");
							return false;
						}
						let chartData = [];
						let items = "";
						$("#tablaAlta tbody tr").each(function (index, valueItem) {
							let $row = $(valueItem);
							chartData.push({
								codigo: $.trim($row.find('td:eq(0)').text()),
								descripcion: $.trim($row.find('td:eq(1)').text()),
								importe: $.trim($row.find('td:eq(2)').text()),
								id_inhumado: $.trim($row.find('td:eq(3)').text()),
							});
						});
						chartData.forEach(function (itemValue, index) {
							if (index >= 1) { items += "~"; }
							items += itemValue.codigo + "^";
							if (itemValue.descripcion == null || itemValue.descripcion == "null" || itemValue.descripcion == 'undefined') {
								items += "" + "^";
							} else {
								items += itemValue.descripcion + "^";
							}
							items += itemValue.importe + "^";
							if (itemValue.inhumado == null || itemValue.inhumado == "null" || itemValue.inhumado == 'undefined') {
								items += "0";
							} else {
								items += itemValue.inhumado;
							}
						});
						let id_cliente = $('.btn-abm-accept').attr('data-id');
						let clienteIntento2 = $("div#masData div#dataListaDePrecios").attr("data-idcliente");
						if ((id_cliente == 'undefined' || id_cliente == null) && clienteIntento2 != 'null' && clienteIntento2 != 'undefined') {
							id_cliente = clienteIntento2;
						}
						if (id_cliente == 'undefined' || id_cliente == 'null') {
							alert("Algo salio mal, no tengo numero de cliente.");
						}
						if (items == '' || items == null || items.length == 0 || items == 'false') {
							alert("Complete Datos");
							return false;
						}
						let opCC = { ID_EmpresaSucursal: _AJAX._id_sucursal, id_cliente: id_cliente, id_Parcela: parcela, id_tipo_comprobante: tipoComprobante, items: items, fecha_alta: fechaInicio, ajuste: "S" };
						_FUNCTIONS.OnClickInsertarCuentaCorriente($(this), opCC);
						return false;
					});

					$('body').off('click', '#btnAgregarConceptoCC').on('click', '#btnAgregarConceptoCC', function () {
						let codigo = '', descripcion = '', importe = 0.0, inhumado = null, fechaInicio = null, parcela, tipoComprobante;
						codigo = $('#listapreciosDataCC').val();
						inhumado = $('#selectInhumadosDataCC').val();
						importe = $('#importeCC').val();
						descripcion = $('#descripcionCC').val();

						tipoComprobante = $('#tipoComprobanteCC').val();
						parcela = $('#parcelasDataCC').val();
						fechaInicio = $('#FechaInicioCC').val();

						// validaciones
						if (codigo == null || codigo == "undefined" || codigo == "" ||
							importe == null || importe == "undefined" || importe == "" || parseFloat(importe) <= 0.0) {
							alert("Complete datos de los items");
							return false;
						}

						let alta =
							"<div class='container'>" +
							"<div class='row'>" +
							"<table>" +
							"<thead>" +
							"<tr>" +
							"<th>" + "Operacion" + "</th>" +
							"<th>" + "Descripcion" + "</th>" +
							"<th>" + "Importe" + "</th>" +
							"<th>" + "Inhumado" + "</th>" +
							"<th>" + "" + "</th>" +
							"</tr>" +
							"</thead>" +
							"<tbody>" +
							"</tbody>" +
							"</table>" +
							"</div>" +
							"</div>";

						if ($('#tablaAlta tbody').length == 0) {
							$('#tablaAlta').html(alta); // reseteo y inicializo con la tabla, cuando no existia.
						}
						$('#tablaAlta tbody').append("<tr><td>" + codigo + "</td><td>" + descripcion + "</td><td>" + importe + "</td><td>" + "" + "</td><td><button class='btn-sm btn-outline-danger'>" + "B" + "</button></td></tr>");
						return false;  // sin esto hace bubbling el evento y se va de nuevo al evento de alta de la CC y termina invalidandose la sesion y sale del sistema.
					});

					$('body').off('click', '#btnTransferirTitularidad').on('click', '#btnTransferirTitularidad', function () {
						if (window.confirm("Se pasará al histórico el cliente actual.  Debe cambiar los datos de este registro por los del nuevo cliente.\nConfirma?")) {
							$('#principalCliente').addClass("tranferencia");
							_FUNCTIONS.OnClickTransferirTitularidad($(this), null)
						} else {
							$('#principalCliente').removeClass("tranferencia");
						}

					});
					$('body').off('click', '#btnTransferirPagador').on('click', '#btnTransferirPagador', function () {
						if (window.confirm("Se pasará al histórico el pagador actual.  Debe cambiar los datos de este registro por los del nuevo pagador.\nConfirma?")) {
							$('#principalPagador').addClass("tranferencia");
							op = { id_cliente: 1 };
							_FUNCTIONS.OnClickTransferirPagador($(this), null)
						} else {
							$('#principalPagador').removeClass("tranferencia");
						}
					});
					$('body').off('click', '#btnVerHistoricoPagador').on('click', '#btnVerHistoricoPagador', function () {
						let id_pagador = $('.btn-abm-accept').attr('data-id');
						let parametros = { id_pagador: id_pagador };
						_FUNCTIONS.OnClickGetHistoricoPagador($(this), parametros);
					});
					$('body').on('show.bs.modal', '.modal', function () {
						if ($(".modal-backdrop").length > 1) { $(".modal-backdrop").not(':last').remove(); }
					});
					$('body').on('hide.bs.modal', '.modal', function () {
						if ($(".modal-backdrop").length > 1) { $(".modal-backdrop").remove(); }
					});
					$('body').on('click', '.button', function () { });
					$('body').off('click', '#agregar-importe').on('click', '#agregar-importe', function () {
						alert("agregar");
					});
					$('body').off('change', '#conceptos_mod').on('change', '#conceptos_mod', function () {
						$('#concepto_mod').val($('#conceptos_mod').val());
						$('#conceptoModInp').val($('#conceptos_mod').val());
					});
				}); // -- Cierre _FUNCTION.getScript()
			});
		});
	});
})();

