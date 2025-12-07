_AJAX = {
	_uri_prefix:"index.php/", // "index.php/"; //php built in development server  -    "";  // httaccess
	_pre: "",
    _waiter: false,
	server: (window.location.protocol + "//" + window.location.host + "/"),
	_here: (window.location.protocol + "//" + window.location.host + "/"),
	_remote_mode: (typeof window.parent.ripple === "undefined"),
	_ready: false,
	_user_firebase: null,
	_uid: null,
	_id_app: null,
	_id_channel: null,
	_channels: {},
	_id_user_active: null,
	_id_type_user_active: null,
	_username_active: null,
	_master_account: null,
	_image_active: null,
	_master_image_active: null,
	_language: "es-ar",
	_token_authentication: "",
	_token_authentication_created: "",
	_token_authentication_expire: "",
	_token_transaction: "",
	_token_transaction_created: "",
	_token_transaction_expire: "",
	_token_push: null,
	_model: null,
	_function: null,
	_module: null,
	_start_time: 0,
	_id_sucursal: 0,
	_sucursal: "",
	_eventoJsLoad: null,
	
	forcePost: function (_path, _target, _parameters) {
		$("#forcedPost").remove();
		var _html = ("<form id='forcedPost' method='post' action='" + _AJAX.server + _path + "' target='" + _target + "'>");
		$.each(_parameters, function (key, value) {
			if (key == "where") { value = _TOOLS.utf8_to_b64(value); }
			_html += ("<input type='hidden' id='" + key + "' name='" + key + "' value='" + value + "'></input>");
		});
		_html += "</form>";
		$("body").append(_html);
		setTimeout(function () { $("#forcedPost").submit(); }, 1000);
	},
	formatFixedParameters: function (_json) {
		try {
			_AJAX._user_firebase.getIdToken().then(function (data) {
				_AJAX._token_push = data;
			}).catch(function (data) {
				_AJAX._token_push = "";
			});
		} catch (rex) {
			_AJAX._token_push = null;
		} finally {
			_json["id_sucursal"] = _AJAX._id_sucursal;
			_json["sucursal"] = _AJAX._sucursal;
			_json["token_push"] = _AJAX._token_push;
			_json["language"] = _AJAX._language;
			_json["token_authentication"] = _AJAX._token_authentication;
			_json["id_app"] = _AJAX._id_app;
			if (_AJAX._id_user_active == "" || _AJAX._id_user_active == null) { _AJAX._id_user_active = 0;}
			_json["id_user_active"] = _AJAX._id_user_active;
			_json["username_active"] = _AJAX._username_active;
			if (_json["id_app"] == undefined) { _json["id_app"] = _AJAX._id_app; }
			if (_json["id_type_user_active"] == undefined) { _json["id_type_user_active"] = _AJAX._id_type_user_active; }
			if (_json["id_channel"] == undefined) { _json["id_channel"] = _AJAX._id_channel; }
			if (_json["model"] == undefined) { _json["module"] = _AJAX.model; }
			if (_json["module"] == undefined) { _json["module"] = _AJAX._module; }
			if (_json["function"] == undefined) { _json["function"] = _AJAX._function; }
			if (_json["table"] == undefined) { _json["table"] = ""; }
			if (_json["method"] == undefined) { _json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; }
			if (_json["eventoJsLoad"] == undefined) { _json["eventoJsLoad"] = _AJAX._eventoJsLoad; }			
			return _json;
		}
	},
	initialize: function (_user_firebase) {
		if (_AJAX._user_firebase == null) { _AJAX._user_firebase = _user_firebase; }
		_AJAX._ready = true;
	},
	ExecuteDirect: function (_json, _method) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX.Execute(_AJAX.formatFixedParameters(_json)).then(function (datajson) {
						if (datajson.status != undefined) {
							if (datajson.status == "OK") {
								$(".raw-username_active").html(_AJAX._username_active);
								resolve(datajson);
							} else {
								reject(datajson);
							}
						} else {
							resolve(datajson);
						}
					});
				} catch (rex) {
					reject(rex);
				}
			});
	},
	Execute: function (_json) {
		_AJAX._start_time = new Date().getTime();
		return new Promise(
			function (resolve, reject) {
				try {
					if (!_AJAX._ready) { _AJAX.initialize(null); }
					$(".raw-raw-request").html(_TOOLS.prettyPrint(_json));
					var ajaxRq = $.ajax({
						type: "POST",
						dataType: "json",
						url: (_AJAX.server + _json.method),
						data: _json,
						beforeSend: function () {_AJAX.onBeforeSendExecute(); },
						complete: function () { _AJAX.onCompleteExecute(); },
						error: function (xhr, ajaxOptions, thrownError) {reject(thrownError);},
						success: function (datajson) {
							_AJAX.onSuccessExecute(datajson, _json)
								.then(function (datajson) { resolve(datajson); })
								.catch(function (err) { reject(err); });
						}
					});
				} catch (rex) {
					reject(rex);
				}
			}
		)
	},
	Load: function (_file) {
		return new Promise(
			function (resolve, reject) {
				var ajaxRq = $.ajax({
					type: "GET",
					timeout: 10000,
					dataType: "html",
					async: false,
					cache: false,
					url: _file,
					success: function (data) { resolve(data); },
					error: function (xhr, msg) { reject(msg); }
				});
			});
	},
	onBeforeSendExecute: function () {
		$(".waiter").removeClass("d-none");
		$(".wait-menu-ajax").html("<img src='" + _AJAX._pre + "./assets/img/menu.gif' style='height:24px'/>");
		$(".wait-search-ajax").html("<img src='" + _AJAX._pre + "./assets/img/search.gif' style='height:25px;width:50px;'/>");
		$(".wait-accept-ajax").html("<img src='" + _AJAX._pre + "./assets/img/accept.gif' style='height:25px;width:65px;'/>");
		if (_AJAX._waiter) {
			$(".wait-ajax").html("<img src='" + _AJAX._pre + "./assets/img/wait.gif' style='height:36px;'/>");
			$.blockUI({ message: '<img src="' + _AJAX._pre + './assets/img/wait.gif" />', css: { border: 'none', backgroundColor: 'transparent', opacity: 1, color: 'transparent' } });
		}
	},
	onCompleteExecute: function () {
		var request_time = ((new Date().getTime() - _AJAX._start_time) / 1000);
		$(".img-master").attr("src", _AJAX._master_image_active);
		$(".img-user").attr("src", _AJAX._image_active);
		$(".elapsed-time").html("Respuesta en " + request_time + " s");
		$(".waiter").html("");
		$(".status-ajax-calls").removeClass("d-none");
		if (_AJAX._waiter) { $.unblockUI(); }
		_AJAX._waiter = false;
	},
	onSuccessExecute: function (datajson, _json_original) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (datajson["message"] == "Records") { datajson["message"] = "";}
					$(".raw-raw-response").html(_TOOLS.prettyPrint(datajson));
					$(".raw-message").html(datajson["code"] + ": " + datajson["message"]);
					if (datajson["status"] == "OK") {
						$(".status-last-call").removeClass("badge-danger").addClass("badge-success");
						$(".status-message").removeClass("d-sm-inline");
						//if (parseInt(_AJAX._doc_editor) == 1) { $(".editor-mode").removeClass("d-none"); } else { $(".editor-mode").addClass("d-none"); }
						//if (parseInt(_AJAX._doc_reviser) == 1) { $(".reviser-mode").removeClass("d-none"); } else { $(".reviser-mode").addClass("d-none"); }
						//if (_AJAX._doc_publisher == 1) { $(".publisher-mode").removeClass("d-none"); } else { $(".publisher-mode").addClass("d-none"); }
					} else {
						$(".status-last-call").removeClass("badge-success").addClass("badge-danger");
						$(".status-message").html(datajson["code"] + ": " + datajson["message"]).addClass("d-sm-inline");
					}
					$(".status-last-call").html(datajson["status"]);
					if (datajson == null) {
						datajson = { "results": null };
						resolve(datajson);
					} else {
						if (datajson.compressed == null) { datajson.compressed = false; }
						if (datajson.compressed == undefined) { datajson.compressed = false; }
						if (datajson != null && datajson.compressed) {
							var zip = new JSZip();
							JSZip.loadAsync(atob(datajson.message)).then(function (zip) {
								zip.file("compressed.tmp").async("string").then(
									function success(content) {
										datajson.message = content;
										resolve(datajson);
									},
									function error(err) { reject(err); });
							});
						} else {
							if (datajson.message != "") { _FUNCTIONS.onAlert({ "message": datajson.message, "class": "alert-danger" }); }
							switch (parseInt(datajson.code)) {
								case 5400:
									_AJAX.UiReAuthenticate({}).then(function (data) {
										_FUNCTIONS.onStatusAuthentication(data);
										_AJAX.Execute(_json_original);
									})
									break;
								case 5200:
								case 5401:
									var _title = (datajson.code + ": " + datajson.message);
									var _body = "<p class='text-monospace'>Ha cambiado su token de autenticación.</p>";
									_body += "<p class='text-monospace'>Esto puede haberse debido a: ";
									_body += "<li>Sus credenciales fueron usadas en otro dispositivo estando la actual sesión activa</li>";
									_body += "<li>Desde administración, se ha modificado su perfil de seguridad</li>";
									_body += "</p > ";
									_body += "<p class='text-monospace'>Por favor autentíquese nuevamente, para seguir en este dispositivo.</p>";
									_FUNCTIONS.onInfoModal({ "title": _title, "body": _body });
									_FUNCTIONS.onReloadInit();
									break;
								default:
									resolve(datajson);
									break;
							}
						}
					}
				} catch (rex) {
					reject(rex);
				}
			}
		)
	},

	/**
	 * /
	 * MOD_BACKEND
	 */

	/*
	 * eventos app para traer datos del backend 
	 */

/*
 * FIN eventos app para traer datos del backend
 */

	UiGet: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "get";
				_AJAX._waiter = false;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSave: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "save"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiOffline: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "offline"; //function
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiOnline: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "online"; //function
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiDelete: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "delete"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiProcess: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "process"; //function
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiForm: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiBrow: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "brow";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiEdit: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "edit";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiExcel: function (_json) {
		_json["mode"] = "download"; // TOque aca!
		_json["exit"] = "download";
		_json["function"] = "excel";
		_AJAX.forcePost(_AJAX._uri_prefix + 'api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiPdf: function (_json) {
		_json["mode"] = "view";
		_json["exit"] = "download";
		_json["function"] = "pdf";
		_AJAX.forcePost(_AJAX._uri_prefix + 'api.backend/neocommand', '_blank', _AJAX.formatFixedParameters(_json));
	},
	UiAuthenticate: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["try"] = "LOCAL";
				//_json["try"] = "LDAP";
				_json["method"] = _AJAX._uri_prefix + "api.backend/authenticate"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) {
					resolve(data);
				}).catch(function (err) {
					reject(err);
				});
			});
	},
	UiReAuthenticate: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = _AJAX._uri_prefix + "api.backend/reAuthenticate"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogged: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = _AJAX._uri_prefix + "api.backend/logged"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogout: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = _AJAX._uri_prefix + "api.backend/logout"; //method
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMessageRead: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "messageRead";
				_json["module"] = "mod_backend";
				_json["table"] = "messages_attached";
				_json["model"] = "messages_attached";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMessagesNotification: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "notifications";
				_json["module"] = "mod_backend";
				_json["table"] = "messages_attached";
				_json["model"] = "messages_attached";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiSendExternal: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["module"] = "mod_backend";
				_json["table"] = "external";
				_json["model"] = "external";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand"; //method
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiLogGeneral: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["method"] = _AJAX._uri_prefix + "api.backend/logGeneral";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) {
					resolve(data);
				}).catch(function (err) {
					reject(err);
				});
			});
	},


	UiGetCuentasListados: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetCuentasListados";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Cuentas";
				_json["model"] = "Con_cuentas";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";

				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiGetPlanDeCuentas: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetPlanDeCuentas";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Cuentas";
				_json["model"] = "Con_cuentas";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";

				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiProcesarDepositos: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "procesarDeposito";
				_json["module"] = "mod_britanico";
				_json["table"] = "ValorEnCartera";
				_json["model"] = "ValorEnCartera";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			
			});
	},
	UiConfirmarDepositos: function (_json,) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "confirmarDeposito";
				_json["module"] = "mod_britanico";
				_json["table"] = "ValorEnCartera";
				_json["model"] = "ValorEnCartera";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });

			});
	},
	UiGetOrdenDePago: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetOrdenDePago";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_OrdenPago";
				_json["model"] = "OrdenDePago";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			
			});
	},
	UiRevertirDepositos: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "revertirDeposito";
				_json["module"] = "mod_britanico";
				_json["table"] = "ValorEnCartera";
				_json["model"] = "ValorEnCartera";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiGetHistoricoInhumado: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "historicoInhumado_CEM";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_inhumado";
				_json["model"] = "inhumado_SinParcelaAsociada";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiBorraHistoricoInhumados: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "BorrarMI_CEM";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_inhumado";
				_json["model"] = "inhumado_SinParcelaAsociada";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiGetHistoricoParcela: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "historicoParcela_CEM";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_parcela";
				_json["model"] = "Parcela";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetContratosArrendamiento: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "contratosArrendamiento_CEM";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_Parcela";
				_json["model"] = "Parcela";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},				
	UiGetClientePagador: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "buscar_cliente_pagador";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_Parcela";
				_json["model"] = "Parcela";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiBorraClientePagador: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "borrar_cliente_pagador";
				_json["module"] = "mod_britanico";
				_json["table"] = "Pagador";
				_json["model"] = "Pagador";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},			
	UiGetClienteParaPagador: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "buscar_cliente_pagador";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_Pagador";
				_json["model"] = "Pagador";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiAsociarClienteParaPagador: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "relacionar_cliente_pagador";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_Pagador";
				_json["model"] = "Pagador";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiGetLibroDiario: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetLibroDiario";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Encabezados";
				_json["model"] = "Funciones_avanzadas";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetLibroMayor: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetLibroMayor";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Encabezados";
				_json["model"] = "Funciones_avanzadas";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetBalance: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetBalance";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Encabezados";
				_json["model"] = "Funciones_avanzadas";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetEmailStatus: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetEmailStatus";
				_json["module"] = "mod_britanico";
				_json["table"] = "emails";
				_json["model"] = "Avisos";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiTransferenciaMensual: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "TransferenciaMensualNogues";
				_json["module"] = "mod_britanico";
				_json["table"] = "Avisos";
				_json["model"] = "Avisos";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGenerarDeudaMensual: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GenerarDeudaMensual";
				_json["module"] = "mod_britanico";
				_json["table"] = "emails";
				_json["model"] = "Avisos";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGenerarAvisosPDF: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetGenerarAvisosPDF";
				_json["module"] = "mod_britanico";
				_json["table"] = "emails";
				_json["model"] = "Avisos";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetSaldoCtasCtes: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetSaldoCtasCtes";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Encabezados";
				_json["model"] = "Funciones_avanzadas";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetAsientoDetails: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getAsientoDetail";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Encabezados";
				_json["model"] = "Sac_funcavanzadas_asientos";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetOrdenDePagoEditBaseData: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetEditBaseData";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_OrdenPago";
				_json["model"] = "OrdenDePago";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetRubros: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetRubrosByFilter";
				_json["module"] = "mod_britanico";
				_json["table"] = "CON_Rubros";
				_json["model"] = "Con_rubros";
				_json["method"] = _AJAX._uri_prefix+"api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},

	UiGetValores: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetValores";
				_json["module"] = "mod_britanico";
				_json["table"] = "vw_Salida_cheques";
				_json["model"] = "ValorEnCartera";
				_json["method"] = _AJAX._uri_prefix+"api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiActualizarObservacionesCliente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "actualizarObservacionesCliente";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetObservacionesCliente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getObservacionesCliente";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiInsertarNotaEnCliente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "insertarNotaEnCliente";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetCuentaCorriente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getCuentaCorriente";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetCuentaCorrienteHistorica: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getCuentaCorrienteHistorica";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetCashflow: function (_json) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "GetCashflow";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cashflow";
				_json["model"] = "Cashflow";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGenerarPlanDePagos: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "generarPlanDePagos";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetItemCuentaCorriente: function (_json, ) {
		return new Promise(
					function (resolve, reject) {
						_json["function"] = "getItemCuentaCorriente";
						_json["module"] = "mod_britanico";
						_json["table"] = "Cliente";
						_json["model"] = "Cliente";
						_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
						_AJAX._waiter = true;
						_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
					});
	},
	UiBorrarCuentaCorriente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "borrarComprobanteCC";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiModificarCuentaCorriente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "modificarComprobanteCC";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiInsertarCuentaCorriente: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "crearComprobanteCC";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},			
	UiGetHistoricoPagador: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "historicoPagador";
				_json["module"] = "mod_britanico";
				_json["table"] = "Pagador";
				_json["model"] = "Pagador";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},				
	UiTransferirPagador: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "transferirPagador";
				_json["module"] = "mod_britanico";
				_json["table"] = "Pagador";
				_json["model"] = "Pagador";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiTransferirTitularidad: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "transferirTitularidad";
				_json["module"] = "mod_britanico";
				_json["table"] = "Cliente";
				_json["model"] = "Cliente";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiGetEstadoDesenganche: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getEstadoDesenganche";
				_json["module"] = "mod_britanico";
				_json["table"] = "Parcela";
				_json["model"] = "Parcela";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiSetearDesengancheParcela: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "setearDesengancheParcela";
				_json["module"] = "mod_britanico";
				_json["table"] = "Parcela";
				_json["model"] = "Parcela";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiGetAnalisisGestion: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getAnalisisGestion";
				_json["module"] = "mod_britanico";
				_json["table"] = "Parcela";
				_json["model"] = "Parcela";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiGetGestionCobranza: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getReporteGestionCobranza";
				_json["module"] = "mod_britanico";
				_json["table"] = "Gestion_cobranza";
				_json["model"] = "Gestion_cobranza";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},			
	UiGetActividadEnNotas: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getActividadEnNotas";
				_json["module"] = "mod_britanico";
				_json["table"] = "Gestion_cobranza";
				_json["model"] = "Gestion_cobranza";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiGetReporteEstadisticaCobroDatosAgrupados: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getReporteEstadisticaCobroDatosAgrupados";
				_json["module"] = "mod_britanico";
				_json["table"] = "Estadistica_cobro";
				_json["model"] = "Estadistica_cobro";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},		
	UiGetReporteEstadisticaCobroPorAvisos: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getReporteEstadisticaCobroPorAvisos";
				_json["module"] = "mod_britanico";
				_json["table"] = "Estadistica_cobro";
				_json["model"] = "Estadistica_cobro";
				// CCOO
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiGetRecibo_CEM: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getRecibo";
				_json["module"] = "mod_britanico";
				_json["table"] = "Recibo";
				_json["model"] = "Recibo";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},	
	UiAnularRecibo_CEM: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "anularRecibo";
				_json["module"] = "mod_britanico";
				_json["table"] = "Recibo";
				_json["model"] = "Recibo";
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			});
	},
	UiMyPdf: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getPdf";
				_json["module"] = "mod_britanico";
				_json["table"] = "Recibo";
				_json["model"] = "Recibo";		
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			}
		);
	},	
	UiGetClientePagadorParcela: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getClientePagadorParcela";
				_json["module"] = "mod_britanico";
				_json["table"] = "Recibo";
				_json["model"] = "Recibo";		
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			}
		);
	},
	UiGetDatosVarios: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "getDatosVarios";
				_json["module"] = "mod_britanico";
				_json["table"] = "Recibo";
				_json["model"] = "Recibo";		
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			}
		);
	},		
	UiGenerarRecibo: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "generarRecibo";
				_json["module"] = "mod_britanico";
				_json["table"] = "Recibo";
				_json["model"] = "Recibo";		
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			}
		);
	},	
	UiGenerarOrdenDePago: function (_json, ) {
		return new Promise(
			function (resolve, reject) {
				_json["function"] = "generarOrdenDePago";
				_json["module"] = "mod_britanico";
				_json["table"] = "OrdenDePago";
				_json["model"] = "OrdenDePago";		
				_json["method"] = _AJAX._uri_prefix + "api.backend/neocommand";
				_AJAX._waiter = true;
				_AJAX.ExecuteDirect(_json, null).then(function (data) { resolve(data); }).catch(function (err) { reject(err); });
			}
		);
	},				
};
