_FUNCTIONS = {
	_API_VIDEO: null,
	_scrollY: 0,
	_cache: {},
	_croppie: null,
	_ATTACH_LIMIT: 1.5,
	_timerPushAlert: 0,
	_id_channel: 2,
	_defaultAttachDir: "./attached/threads/",
	_defaultBrowserSearch: "browser_search",
	_defaultBrowserSearchOperator: "like",
	_defaultBrowserSearchFields: ["code", "description"],
	_defaultProviderFooter: "<img src='./assets/img/small.png' style='width:32px;' />",
	_max_filesize_upload: 50,
	_TIMEOUT_ALERT: 3000,
	_TIMER_MODAL: 0,
	_TIMER_INTRANET: 0,
	_TIMER_FORM: 0,
	_last_form: "",
	onReloadInit: function () {
		$(".sidebar-wrapper").fadeOut("slow");
		$(".dyn-area").fadeOut("slow", function () { setTimeout(function () { window.location = "/"; }, 10000); });
	},
	onAlert: function (_json) {
		try {
			clearTimeout(_FUNCTIONS._timerPushAlert);
			$(".push-alert").remove();
			if (_json["message"] == "") { return false; }
			var _html = "<div style='position:fixed; bottom:60px;' class='push-alert alert " + _json["class"] + " alert-dismissible fade show' role='alert'>";
			_html += "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
			_html += _json["message"];
			_html += "</div>";
			$(".alert-frame").append(_html);
			_FUNCTIONS._timerPushAlert = setTimeout(function () { $(".push-alert").alert('close'); }, 7500);
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onShowAlert: function (_message, _title) {
		_FUNCTIONS.onDestroyModal("#modal-alert");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-alert' class='modal fade' style='z-index:9999;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header'>";
		if (_title != "") {
			_html += "            <h4>" + _title + "<button type='button' class='close pull-right' data-dismiss='modal' aria-hidden='true'>&times;</button></h4>";
		} else {
			_html += "            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='modal-body danger alert-danger'>";
		_html += "            <p style='color:darkred;'>" + _message + "</p>";
		_html += "         </div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-alert").on('hide.bs.modal', function () { clearInterval(_FUNCTIONS._TIMER_MODAL); });
		$("#modal-alert").modal({ backdrop: true, keyboard: true });
		_FUNCTIONS._TIMER_MODAL = setTimeout(function () { _FUNCTIONS.onDestroyModal("#modal-alert"); }, _FUNCTIONS._TIMEOUT_ALERT);
	},
	onShowInfo: function (_message, _title) {
		_FUNCTIONS.onDestroyModal("#modal-info");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-info' class='modal fade' style='z-index:9999;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header'>";
		if (_title != "") {
			_html += "            <h4>" + _title + "<button type='button' class='close pull-right' data-dismiss='modal' aria-hidden='true'>&times;</button></h4>";
		} else {
			_html += "            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='modal-body danger alert-default'>";
		_html += "            <p style='color:darkred;'>" + _message + "</p>";
		_html += "         </div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-info").on('hide.bs.modal', function () { });
		$("#modal-info").modal({ backdrop: true, keyboard: true });
	},
	onShowHtmlModal: function (_title, _message, _callback) {
		_FUNCTIONS.onDestroyModal("#modal-html");
		if (_title == undefined) { _title = ""; }
		var _html = "";
		_html += "<div id='modal-html' class='modal fade' style='z-index:9999;'>";
		_html += "   <div class='modal-dialog'>";
		_html += "      <div class='modal-content'>";
		_html += "         <div class='modal-header'>";
		if (_title != "") {
			_html += "            <h4>" + _title + "<button type='button' class='close pull-right' data-dismiss='modal' aria-hidden='true'>&times;</button></h4>";
		} else {
			_html += "            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
		}
		_html += "         </div>";
		_html += "         <div class='modal-body danger alert-default'>" + _message + "</div>";
		_html += "      </div>";
		_html += "   </div>";
		_html += "</div>";
		$("body").append(_html);
		$("#modal-html").on('hide.bs.modal', function () { });
		$("#modal-html").modal({ backdrop: true, keyboard: true });
		if ($.isFunction(_callback)) { _callback(); }
	},

	onDestroyModal: function (_target) {
		$(_target).remove();
		$(".modal-backdrop").remove();
		$("body").removeClass("modal-open");
	},
	onInfoModal: function (_json, _callBack) {
		try {
			_FUNCTIONS.onDestroyModal("#infoModal");
			if (_json["close"] == undefined) { _json["close"] = false; }
			if (_json["size"] == undefined) { _json["size"] = "modal-md"; }
			if (_json["center"] == undefined) { _json["center"] = ""; }
			if (_json["center"] === true) { _json["center"] = "modal-dialog-centered"; } else { _json["center"] = ""; }
			var _html = "<div class='modal fade' id='infoModal' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-scrollable " + _json["center"] + " " + _json["size"] + "' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			if (_json["close"]) { _html += "<button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>"; }
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += _json["body"];
			if (!_json["close"]) {
				_html += "       <div class='progress' style='height:5px;'>";
				_html += "          <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:100%;'></div>";
				_html += "       </div>";
			}
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			$('.trumbo').trumbowyg({ lang: 'es_ar' });
			if ($.isFunction(_callBack)) { _callBack(); }

			$("#infoModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onBriefModal: function (_this) {
		var _title = _this.attr("data-title");
		var _body = _this.attr("data-body");
		if (_body == "" || _body == null || _body == undefined) { _body = ""; } else { _body = _TOOLS.b64_to_utf8(_body); }
		_FUNCTIONS.onInfoModal({ "title": _title, "body": _body, "close": true, "size": "modal-xl", "center": false });
	},

	onMessengerModal: function (_json) {
		try {
			_FUNCTIONS._scrollY = window.scrollY;

			_FUNCTIONS.onDestroyModal("#messengerModal");
			if (_json["close"] == undefined) { _json["close"] = false; }
			if (_json["size"] == undefined) { _json["size"] = "modal-lg"; }
			if (_json["center"] == undefined) { _json["center"] = ""; }
			var _html = "<div class='modal fade' id='messengerModal' tabindex='-1' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-centered " + _json["size"] + "' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			if (_json["close"]) { _html += "<button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>"; }
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += _json["body"];
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			$('.trumbo').trumbowyg({ lang: 'es_ar' });
			$("body").off("click", ".btn-reply-message").on("click", ".btn-reply-message", function () {
				$(".btn-reply-message").fadeOut("slow");
				var _body = $("#reply_body").val();
				var _json = {
					"id": $("#id_record").val(),
					"code": $("#code").val(),
					"token": $("#token").val(),
					"from_table": $("#table").val(),
					"body": _body,
				};
				_AJAX.UiDirectMessenger(_json).then(function (datajson) {
					_FUNCTIONS.onDestroyModal("#messengerModal");
					setTimeout(function () { window.scrollTo(0, _FUNCTIONS._scrollY); }, 250);
					if (datajson.status == "OK") {
						alert("¡La respuesta ha sido enviada!");
						if ($(".pagination").html().trim() != "") {
							$(".page-item.active a").click();
						} else {
							$(".btn-browser-search").click();
						}
					} else {
						alert(datajson.message);
						$(".btn-reply-message").fadeIn("fast");
					}
				}).catch(function (error) { alert(error.message); });
			});

			$("#messengerModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onEmailModal: function (_json) {
		try {
			_FUNCTIONS.onDestroyModal("#emailModal");
			if (_json["close"] == undefined) { _json["close"] = false; }
			if (_json["size"] == undefined) { _json["size"] = "modal-lg"; }
			if (_json["center"] == undefined) { _json["center"] = ""; }
			var _html = "<div class='modal fade' id='emailModal' role='dialog'>";
			_html += " <div class='modal-dialog modal-dialog-centered " + _json["size"] + "' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			if (_json["close"]) { _html += "<button type='button' class='close btn-close-modal' data-dismiss='modal'>&times;</button>"; }
			_html += "    </div>";
			_html += "    <div class='modal-body'>";
			_html += _json["body"];
			if (!_json["close"]) {
				_html += "       <div class='progress' style='height:5px;'>";
				_html += "          <div class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width:100%;'></div>";
				_html += "       </div>";
			}
			_html += "    </div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += _FUNCTIONS._defaultProviderFooter;
			_html += "</div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			$('.trumbo').trumbowyg({ lang: 'es_ar' });
			$("body").off("drop", ".drop_zone").on("drop", ".drop_zone", function (ev) {
				$(this).removeClass("drop_zone_over");
				ev.preventDefault();
				if (ev.originalEvent.dataTransfer.items) {
					for (var i = 0; i < ev.originalEvent.dataTransfer.items.length; i++) {
						if (ev.originalEvent.dataTransfer.items[i].kind === 'file') {
							var file = ev.originalEvent.dataTransfer.items[i].getAsFile();
							if (file.size > (_FUNCTIONS._ATTACH_LIMIT * 1024000)) {
								$(".ls-images").append("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='label label-danger'>" + file.name + "</span> porque excede los " + _FUNCTIONS._ATTACH_LIMIT + "mb!</li>");
							} else {
								var reader = new FileReader();
								reader.onloadend = (function (data) { return function (evt) { $(".ls-images").append(_TOOLS.createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.items[i].getAsFile());
								reader.readAsDataURL(file);
							}
						}
					}
				} else {
					for (var i = 0; i < ev.originalEvent.dataTransfer.files.length; i++) {
						var file = ev.originalEvent.dataTransfer.files[i].getAsFile();
						if (file.size > (_FUNCTIONS._ATTACH_LIMIT * 1024000)) {
							$(".ls-images").append("<li class='list-group-item' style='padding:10px;'>¡No se adjuntará <span class='label label-danger'>" + file.name + "</span> porque excede los " + _FUNCTIONS,_ATTACH_LIMIT + "mb!</li>");
						} else {
							var reader = new FileReader();
							reader.onloadend = (function (data) { return function (evt) { $(".ls-images").append(createFileItem(data.name, evt.target.result)); } })(ev.originalEvent.dataTransfer.files[i].getAsFile());
							reader.readAsDataURL(file);
						}
					}
				}
				if (ev.originalEvent.dataTransfer.items) {
					ev.originalEvent.dataTransfer.items.clear();
				} else {
					ev.originalEvent.dataTransfer.clearData();
				}
			});
			$("body").off("dragover", ".drop_zone").on("dragover", ".drop_zone", function (ev) {
				$(this).addClass("drop_zone_over");
				ev.preventDefault();
			});
			$("body").off("dragleave", ".drop_zone").on("dragleave", ".drop_zone", function (ev) {
				$(this).removeClass("drop_zone_over");
				ev.preventDefault();
			});
			$("body").off("click", ".btn-deattach").on("click", ".btn-deattach", function () {
				if (!confirm("¿Confirma la operación?")) { return false; }
				$("." + $(this).attr("data-id")).remove();
			});
			$("body").off("click", ".btn-send-reply").on("click", ".btn-send-reply", function () {
				$(".btn-send-reply").fadeOut("slow");
				var _body = $("#reply_body").val();
				var _names = "";
				var _attachs = "";
				$(".attach").each(function () {
					_names += $(this).attr("data-name") + "[NAME]";
					_attachs += $(this).attr("data-url") + "[FILE]";
				});
				var _json = {
					"id_operator_task": $("#id").val(),
					"email": $("#email").val(),
					"body": _body,
					"subject": "Contacto",
					"from": "info@cementerio.com",
					"names": _names,
					"attachs": _attachs
				};
				_AJAX.UiDirectEmail(_json).then(function (datajson) {
					if (datajson.status == "OK") {
						alert("¡La respuesta ha sido enviada!");
						$(".btn-send-reply").fadeIn("fast");
						$("#emailModal").modal("toggle");
					} else {
						alert(datajson.message);
						$(".btn-send-reply").fadeIn("fast");
					}
				}).catch(function (error) { alert(error.message); });
			});

			$("#emailModal").modal({ backdrop: false, keyboard: true, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onImageModal: function (_json) {
		try {
			_FUNCTIONS._croppie = null;
			$('.modal-body').croppie("destroy");
			var _html = "<div class='modal fade' id='imageModal'>";
			_html += " <div class='modal-dialog modal-dialog-centered' role='document'>";
			_html += "  <div class='modal-content'>";
			_html += "    <div class='modal-header'>";
			_html += "      <h4 class='modal-title'>" + _json["title"] + "</h4>";
			_html += "      <button type='button' class='close' data-dismiss='modal'>&times;</button>";
			_html += "    </div>";
			_html += "    <div class='modal-body'></div>";
			_html += "    <div class='modal-footer font-weight-light'>";
			_html += "       <button type='button' class='btn btn-primary btn-crop'>Recortar</button>";
			_html += "    </div>";
			_html += "  </div>";
			_html += " </div>";
			_html += "</div>";
			$("body").append(_html);
			_FUNCTIONS._croppie = $('.modal-body').croppie(
				{
					viewport: { height: 250, width: 250, type: "square" },
					boundary: { height: 400, width: 400, }
				}
			);
			_FUNCTIONS._croppie.croppie('bind', { url: _json["image"], points: [77, 469, 280, 739] });
			$("body").off("click", ".btn-crop").on("click", ".btn-crop", function () {
				var _args = { type: _json["type"], format: _json["format"], quality: _json["quality"] };
				_FUNCTIONS._croppie.croppie('result', _args).then(function (_image) {
					if (!_json["multi"]) {
						$(_json["input"]).val(_image);
						$(_json["target"]).attr("src", _image);
					} else {
						var _id = _TOOLS.UUID();
						var _line = "<li class='list-group-item li-" + _id + "'>";
						_line += "<img data-id='" + _id + "' src='" + _image + "' style='width:40px;' class='new-file img-" + _id + "' data-filename='" + _json["filename"] + "' /> ";
						_line += "<div class='badge badge-primary text-truncate' style='display:inline-block;max-width:100%;' title='" + _json["filename"] + "'>" + _FUNCTIONS._defaultAttachDir + _json["filename"] + "</div> ";
						_line += "<a href='#' data-id='" + _id + "' class='btn btn-sm btn-danger float-right btn-upload-delete'><i class='material-icons'>delete</i></a>";
						_line += "</li>";
						$(_json["target"]).append(_line);
					}
					_FUNCTIONS.onDestroyModal("#imageModal");
				});
			});
			$("#imageModal").modal({ backdrop: false, keyboard: false, show: true });
			return true;
		} catch (rex) {
			alert(rex.message);
			return false;
		}
	},
	onProcessSelectedFilesFolders: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var x = document.querySelector(_this.attr("data-click"));
					for (var i = 0; i < x.files.length; i++) {
						var f = x.files[i];
						var _file_type = f.type;
						var _filename = f.name;
						if (f.size > (_FUNCTIONS._max_filesize_upload * 1024000)) { throw ("Se aceptan archivos de hasta " + _FUNCTIONS._max_filesize_upload + "mb"); }
						var fr = new FileReader();
						fr.onload = function (e) {
							var _result = this.result;
							var _image = _TOOLS.iconByMime(_file_type, fr.result);
							_FUNCTIONS.onFoldersModal(
								{
									"target": _this.attr("data-target"),
									"input": _this.attr("data-input"),
									"title": "Agregar archivo",
									"filename": _filename,
									"image": _image,
									"result": _result,
									"module": _this.attr("data-module"),
								});
							resolve(_image);
						}
						fr.readAsDataURL(f);
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onProcessSelectedFiles: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					var x = document.querySelector(_this.attr("data-click"));
					for (var i = 0; i < x.files.length; i++) {
						var f = x.files[i];
						var _file_type = f.type;
						var _filename = f.name;
						if (f.size > (_FUNCTIONS._max_filesize_upload * 1024000)) { throw ("Se aceptan archivos de hasta " + _FUNCTIONS._max_filesize_upload + "mb"); }
						var fr = new FileReader();
						fr.onload = function (event) {
							//var _image = _TOOLS.iconByMime(_file_type, fr.result);
							var _image = fr.result;
							_FUNCTIONS.onImageModal(
								{
									"target": _this.attr("data-target"),
									"input": _this.attr("data-input"),
									"type": _this.attr("data-type"),
									"format": _this.attr("data-format"),
									"quality": _this.attr("data-quality"),
									"crop": _this.attr("data-crop"),
									"multi": _this.attr("data-multi"),
									"title": "Ajustar la imagen",
									"filename": _filename,
									"image": _image
								});
							resolve(_image);
						}
						fr.readAsDataURL(f);
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onResetSelectedFile: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del archivo?")) {
						$(_this.attr("data-target")).attr("src", _this.attr("data-default"));
						$(_this.attr("data-input")).val("");
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedFile: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del archivo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-file")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-file");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedLink: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del link externo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-link")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-link");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedFileFolders: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del archivo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-folder-item")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-file");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onDeleteSelectedLinkFolders: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (confirm("¿Confirma el borrado del link externo?")) {
						var _id = _this.attr("data-id");
						if ($(".img-" + _id).hasClass("new-link")) {
							$(".li-" + _id).fadeOut("fast").remove();
						} else {
							$(".li-" + _id).addClass("d-none")
							$(".img-" + _id).addClass("del-link");
						}
					}
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onProcessDirectEmail: function (_this) {
		var _body = "";
		_body += "<label>Email</label><input type='text' id='email' name='email' class='form-control' value='" + _this.attr("data-email") + "'/>"
		_body += "<label>Mensaje</label><textarea id='reply_body' name='reply_body' class='shadow trumbo' style='width:100%;'></textarea> ";
		_body += "<hr/>";
		_body += "<div id='drop_zone' class='drop_zone'>";
		_body += "<p>Arrastre y suelte archivos a adjuntar en esta zona</p>";
		_body += "</div>";
		_body += "<hr/>";
		_body += "<ul class='ls-images' style='padding:0px;'></ul>";
		_body += "<hr/>";
		_body += "<a href='#' class='btn btn-raised btn-lg btn-success btn-send-reply'>Enviar!</a>";
		_FUNCTIONS.onEmailModal({ "close": true, "title": "Responder", "body": _body });
	},
	onProcessDirectMessenger: function (_this) {
		var _body = "";
		_body += "<label>Conversación: " + _this.attr("data-code") + "</label><br/>"
		_body += "<input type='hidden' id='table' name='table' value='" + _this.attr("data-table") + "'/>"
		_body += "<input type='hidden' id='code' name='code' value='" + _this.attr("data-code") + "'/>"
		_body += "<input type='hidden' id='id_record' name='id_record' value='" + _this.attr("data-id") + "'/>"
		_body += "<input type='hidden' id='token' name='token' value='" + _this.attr("data-token") + "'/>"
		_body += "<label>Mensaje</label><textarea id='reply_body' name='reply_body' class='shadow textarea' style='width:100%;height:200px;margin-left:auto;' rows='10' cols='100'></textarea> ";
		_body += "<hr/>";
		_body += "<a href='#' class='btn btn-raised btn-lg btn-success btn-reply-message'>Enviar!</a>";
		_FUNCTIONS.onMessengerModal({ "close": true, "title": "Responder", "body": _body });
	},

	onLogin: function (_this, _scoope) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (_scoope == undefined) { _scoope = "backend"; }
					if (_TOOLS.validate(".validate", true)) {
						var _json = _TOOLS.getFormValues(".dbase", _this);
						_AJAX._id_sucursal = _this.attr("data-id_sucursal");
						_AJAX._sucursal = _this.attr("data-sucursal");
						_json["scoope"] = _scoope;
						_AJAX.UiAuthenticate(_json)
							.then(function (_auth) {
								if (_auth.status == "OK") {
									_FUNCTIONS.onStatusAuthentication(_auth).then(function (datajson) {
										_AJAX.UiLogged({}).then(function (data) {
											if (data.status == "OK") {
												switch (_scoope) {
													case "backend":
														$(".main").fadeOut("fast", function () {
															$(".main").removeClass("container").addClass("container-flex").html(data.message).fadeIn("slow");
															//_AJAX._id_sucursal = _this.attr("data-id_sucursal");
															//_AJAX._sucursal = _this.attr("data-sucursal");
															//alert(_AJAX._sucursal + _AJAX._id_sucursal);
															$("span.cl-empresa-sucursal").html(_AJAX._sucursal.replace("neo_", ""));
															resolve(data);
														});
														break;
													default:
														window.location = "/site/logged";
														resolve(data);
														break;
												}
											} else {
												throw data;
											}
										}).catch(function (error) { throw error; });
									}).catch(function (error) { throw error; });
								} else {
									throw data;
								}
							}).catch(function (error) {
								alert(error.message);
								throw error;
							});
					}
				} catch (rex) {
					alert(rex.message);
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onLogout: function (_this, _mode) {
		if (_mode == undefined) { _mode = "/"; }
		_AJAX.UiLogout({}).then(function (data) {
			window.location = _mode;
		});
	},

	onStatusAuthentication: function (datajson) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX.channels = datajson.data.channels;
					_AJAX._token_authentication = datajson.data.token_authentication;
					_AJAX._token_authentication_created = datajson.data.token_authentication_created;
					_AJAX._token_authentication_expire = datajson.data.token_authentication_expire;
					_AJAX._id_app = datajson.data.id;
					_AJAX._id_user_active = datajson.data.id;
					_AJAX._id_type_user_active = datajson.data.id_type_user;
					_AJAX._username_active = datajson.data.username;
					_AJAX._image_active = datajson.data.image;
					_AJAX._master_image_active = datajson.data.master_image;
					$(".raw-id_user_active").html(_AJAX._id_user_active);
					$(".raw-id_type_user_active").html(_AJAX._id_type_user_active);
					$(".raw-username_active").html(_AJAX._username_active);
					$(".raw-a-token_created_datetime").html(_AJAX._token_authentication_created);
					$(".raw-a-token_ttl_datetime").html(_AJAX._token_authentication_expire);
					$(".raw-a-token_key").html(_AJAX._token_authentication);
					resolve(datajson);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onStatusClick: function (_this) {
		if (_this.hasClass("btn-menu-click")) { _this.find(".label-menu").append("<span class='mx-0 px-1 waiter wait-menu-ajax'></span>"); }
		if (_this.hasClass("btn-browser-search")) { _this.html("<span class='mx-0 px-1 waiter wait-search-ajax'></span>"); }
		if (_this.hasClass("btn-abm-accept")) { _this.html("<span class='mx-0 px-1 waiter wait-accept-ajax'></span>"); }
	},

	onMenuOpen: function (_this, e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
		$(".info-heading").addClass("d-none").fadeOut("slow");
	},
	onMenuClose: function (_this, e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
		$(".info-heading").removeClass("d-none").fadeIn("slow");
	},
	onMenuClick: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					$("." + _FUNCTIONS._defaultBrowserSearch).val("");
					switch (_this.attr("data-action")) {
						case "brow":
							_FUNCTIONS.onBrowserSearch(_this);
							break;
						default:
							_FUNCTIONS.onFormSearch(_this);
							break;
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},

	onRecordEdit: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					clearInterval(_FUNCTIONS._TIMER_FORM);
					var _json = _TOOLS.getFormValues(null, _this);
					// CCOO
					_AJAX.UiEdit(_json).then(function (data) {
						if (data.status == "OK") {
							$(".dyn-area").addClass("d-none").hide();
							$(".abm").html(data.message).removeClass("d-none").fadeIn("slow");
							resolve(data);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordRemove: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma el borrado del registro?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiDelete(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onBrowserSearch(_this).then(function () {
								_FUNCTIONS.onAlert({ "message": data.message, "class": "alert-info" });
								resolve(data);
							}).catch(function (error) { throw error; });
							resolve(data);
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordOffline: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma sacar de línea el registro?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiOffline(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onBrowserSearch(_this).then(function () {
								_FUNCTIONS.onAlert({ "message": data.message, "class": "alert-info" });
								resolve(data);
							}).catch(function (error) { throw error; });
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordOnline: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma poner en línea el registro?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiOnline(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onBrowserSearch(_this).then(function () {
								_FUNCTIONS.onAlert({ "message": data.message, "class": "alert-info" });
								resolve(data);
							}).catch(function (error) { throw error; });
						} else {
							throw data;
						}
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},
	onRecordProcess: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					if (!confirm("¿Confirma la ejecución del proceso?")) {
						resolve(null);
						return false;
					}
					var _json = _TOOLS.getFormValues(null, _this);
					_AJAX.UiProcess(_json).then(function (data) {
						if (data.status == "OK") {
							_FUNCTIONS.onBrowserSearch(_this).then(function () {
								_FUNCTIONS.onAlert({ "message": data.message, "class": "alert-info" });
								resolve(data);
							}).catch(function (error) { throw error; });
						} else {
							throw data;
						}
					}).error(function (err) {
						throw err;
					});
				} catch (rex) {
					_FUNCTIONS.onBrowserSearch(_this).then(function () {
						_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
						reject(rex);
					}).catch(function (error) { throw error; });
				}
			});
	},

	onAbmAccept: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					_AJAX._waiter = true;
					_AJAX.onBeforeSendExecute();
					setTimeout(function () {
						if (_TOOLS.validate(".validate", true)) {
							var _json = _TOOLS.getFormValues(".dbase", _this);
							_AJAX.UiSave(_json).then(function (data) {
								console.log(data);
								if (data.status == "OK") {
									if ("customMensaje" in data) {alert(data.customMensaje);}
									$(".abm").addClass("d-none").hide();
									$(".browser").removeClass("d-none").show();
									_FUNCTIONS.onAlert({ "message": "Se ha grabado el registro", "class": "alert-success" });
									$('#principalCliente').removeClass("tranferencia"); // saco la pantalla del modo transferencia
									if ($(".pagination").html().trim() != "") {
										$(".page-item.active a").click();
									} else {
										$(".btn-browser-search").click();
									}
									resolve(data);
								} else {
									throw data;
								}
							});
						}
					}, 250);
				} catch (rex) {
					setTimeout(function () { _AJAX.onCompleteExecute(); }, 50);
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});

	},
	onAbmCancel: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					$(".abm").addClass("d-none").hide();

					$('#principalCliente').removeClass("tranferencia"); // saco la pantalla del modo transferencia

					$(".browser").removeClass("d-none").fadeIn("slow");
					_FUNCTIONS.onAlert({ "message": "No se han efectuado cambios al registro", "class": "alert-info" });
					$(".page-item.active a").click();
					resolve(true);
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				}
			});
	},

	onBrowserSearch: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					clearInterval(_FUNCTIONS._TIMER_FORM);
					_AJAX._last_form = "";
					$(".abm").html("").addClass("d-none").hide();
					var _html = _this.html();
					_FUNCTIONS.onStatusClick(_this);
					var _json = _TOOLS.getFormValues(null, _this);
					var data_adicional = _this.attr("data-adicional");
					if (data_adicional!='' && data_adicional!=undefined) {_json["data_adicional"] = data_adicional;}
					var _data_mode = _this.attr("data-mode");
					if (_data_mode == undefined) { _data_mode = "brow"; }
					var _data_filters = _this.attr("data-filters");
					var _forced_field = _this.attr("data-forced-field");
					var _forced_value = _this.attr("data-forced-value");
					if (_forced_field == undefined) { _forced_field = ""; }
					if (_forced_value == undefined) { _forced_value == ""; }
					if (_data_filters == undefined || _data_filters == "[]") {
						_data_filters = [{ "name": _FUNCTIONS._defaultBrowserSearch, "operator": _FUNCTIONS._defaultBrowserSearchOperator, "fields": _FUNCTIONS._defaultBrowserSearchFields }];
					} else {
						_data_filters = JSON.parse(_data_filters);
					};
					if (_forced_field != "") { _json["forced_field"] = _forced_field; }
					if (_forced_value != "") { _json["forced_value"] = _forced_value; }
					var _where = "";
					var _arrS = [];
					var _arrW = [];
					$.each(_data_filters, function (i, item) {
						if (item.name == "browser_gestionExterna" && $("#" + item.name).val() == "") {
							var ix = 0;
						} else {
							if ($("#" + item.name).val() != undefined && $("#" + item.name).val() != "") {
								var _value = $("#" + item.name).val();
								var _temp = "";
								_arrS.push({ "name": item.name, "value": _value });
								$.each(item.fields, function (ix, field) {
									if (_temp != "") { _temp += " OR "; }
									switch (item.operator.toLowerCase()) {
										case "like":
											_temp += (field + " " + item.operator + " '%" + _value + "%'");
											break;
										default:
											var _search = _value;
											if (item.type != undefined) {
												switch (item.type) {
													case "date":
														if (_search.indexOf(":") == -1) {
															if (item.operator == ">=") { _search += " 00:00:00"; }
															if (item.operator == "<=") { _search += " 23:59:59"; }
														}
														break;
												}
											}
											_temp += (field + " " + item.operator + " '" + _search + "'");
											break;
									}
								});
								if (_temp != "") { _arrW.push("(" + _temp + ")"); }
							}
						}
					});
					for (var i = 0; i < _arrW.length; i++) { if (_where != "") { _where += " AND "; } _where += ("(" + _arrW[i] + ")"); }
					if (_forced_field != "" && _forced_value != "") {
						_arrS.push({ "name": "browser_" + _forced_field, "value": _forced_value });
						_where = (_forced_field + "=" + _forced_value);
					}
					_json["where"] = _where;
					switch (_data_mode) {
						case "brow":
							_AJAX.UiBrow(_json).then(function (data) {
								if (data.status == "OK") {
									$(".browser").html(data.message).removeClass("d-none").fadeIn("slow");
									for (var i = 0; i < _arrS.length; i++) { $("#" + _arrS[i]["name"]).val(_arrS[i]["value"]); }
									resolve(data);
								} else {
									throw data;
								}
							}).catch(function (error) {
								throw error;
							});
							break;
						case "excel":
							//alert("excel");
							_AJAX.UiExcel(_json);
							break;
						case "pdf":
							_AJAX.UiPdf(_json);
							break;
					}
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				} finally {
					setTimeout(function () { _this.html(_html) }, 250);
				}
			});
	},
	onFormSearch: function (_this) {
		return new Promise(
			function (resolve, reject) {
				try {
					clearInterval(_FUNCTIONS._TIMER_FORM);
					var _html = _this.html();
					var _target = _this.attr("data-forced");
					if (_target == undefined) {
						$(".abm").html("").addClass("d-none").hide();
						_target = ".browser";
					} else {
						$(".hideable").removeClass("active").removeClass("in");
						$(".nav-link").removeClass("active").removeClass("show");
					}
					_FUNCTIONS.onStatusClick(_this);
					var _json = _TOOLS.getFormValues(null, _this);
					_json["function"] = _this.attr("data-action");
					_AJAX._last_form = _json.model;
					_AJAX.UiForm(_json).then(function (data) {
						if (data.status == "OK") {
							$(_target).html(data.message).removeClass("d-none").fadeIn("slow");
							resolve(data);
						} else {
							throw data;
						}
					}).catch(function (error) {
						throw error;
					});
				} catch (rex) {
					_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
					reject(rex);
				} finally {
					setTimeout(function () { _this.html(_html) }, 250);
				}
			});
	},
	prueba: function(_this) {
		alert("prueba");
	},
	eventoJsLoad: function(_this) {
		alert("eventoJsLoad");
	}, 
	onAClickHistoricoInhumado: function(_this) {
		let id_vw_inhumado = (_this).data("id");
		data = {id_inhumado: id_vw_inhumado};
		var myObj = data;
		_AJAX.UiGetHistoricoInhumado(myObj).then(function (datajson) {
			let x = null;
			try {
				x = JSON.parse(datajson); 
			} catch (e) {
				x = datajson;
			}
			let data = datajson.historico;
			let NroInhumado = "";
			let nombre = "";
			let tabla = "";
			let tablaH = '';
			let tablaB = '';
			tablaH = "<thead class='thead-light'><tr>";
			tablaH += "<th>Fecha</th>";
			tablaH += "<th>Tipo Servicio</th>";
			tablaH += "<th>Nombre Inhumado</th>";
			tablaH += "<th>Nro.Inhumado</th>";
			tablaH += "<th>Nro.Movimiento</th>";
			tablaH += "<th>Parcela</th>";
			tablaH += "<th>Nro.Cliente</th>";
			tablaH += "<th></th>";
			tablaH += "</tr></thead>";
			
			tablaB = "<tbody>";
			for(var i = 0; i < data.length; i++){
				tablaB += "<tr>";
				var f = data[i].FechaMovimiento.substring(0,10).split("-");
				tablaB += "		<td>" + f[2] + "/" + f[1] + "/" + f[0] + "</td>"; // YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY
				tablaB += "		<td>" + data[i].NombreTipoServicio + "</td>";
				tablaB += "		<td>" + data[i].Nombre + "</td>";
				tablaB += "		<td>" + data[i].NumeroInhumado + "</td>";
				tablaB += "		<td>" + data[i].NumeroMovimiento + "</td>";
				tablaB += "		<td>" + data[i].parcela_formateada + "</td>";
				tablaB += "		<td>" + data[i].numerocliente + "</td>";
				tablaB += "		<td class='borrarmov1'>";
				if (datajson.permisosAdicionalesBorrarMovimientoInhumado == "S") {
					tablaB += '			<input type="button" class="btn borrarmov btn-sm btn-outline-dange" value="(B)" data-id="' +data[i].NumeroMovimiento+'">';
				} else {
					tablaB += '			<input type="button" disabled class="btn borrarmov btn-sm btn-outline-danger" value="B" data-id="' +data[i].NumeroMovimiento+'">';					
				}
				tablaB += "		</td>";
				tablaB += "</tr>";

				NroInhumado = data[i].NumeroInhumado;
				nombre = data[i].Nombre;
			}
			tablaB += "</tbody>";
			tabla = "<table>"+tablaH+tablaB+"</table>";

			var html = '<!-- Modal --> \
			<div class="modal fade" id="myModal" role="dialog"> \
			  <div class="modal-dialog modal-lg"> \
			  \
				<!-- Modal content-->\
				<div class="modal-content modal-lg">\
				  <div class="modal-header">\
					<h4 class="modal-title">TITULO</h4>\
					<button type="button" class="close" data-dismiss="modal">&times;</button>\
				  </div>\
				  <div id="modalContenido" class="modal-body modal-lg">\
					<p>Some text in the modal.</p>\
				  </div>\
				  <div class="modal-footer modal-lg">\
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
				  </div>\
				</div>\
				\
			  </div>\
			</div>';

			$('#agregado').html(html);
			$('#modalContenido').text('');
			$('#modalContenido').append(tabla);
			$('#myModal').modal('toggle');
			$('.modal-title').text('Nro.Inhumado: ' + NroInhumado + " - " + nombre);
			
			//$('#myModal').modal('show');
			//$('#myModal').modal('hide');

		});
	},
	onAClickBorrarHistorico: function(_this) {
		let NumeroMovimiento = (_this).data("id");
		data = {NumeroMovimiento: NumeroMovimiento};
		var myObj = data;
		_AJAX.UiBorraHistoricoInhumados(myObj).then(function (datajson) {
			let data = datajson.borrado;
			if (data[0].err == 0){
				alert("El historico fue borrado");
			} else {
				alert("Ocurrio un error al borrar el historico");
			}
	
		});
	},	 
	onAClickGetHistoricoParcela: function(_this) {
		let id_parcela = (_this).data("id");
		data = {id_Parcela: id_parcela};
		var myObj = data;
		_AJAX.UiGetHistoricoParcela(myObj).then(function (datajson) {
			let actual = datajson.historicoActual;
			let completo = datajson.historicoCompleto;
			let NroInhumado = "";
			let nombre = "";
			// Actual
			let htmlActual = "";
			let tabla = "";
			let tablaH = '';
			let tablaB = '';

			tablaH = "<thead class='thead-light'><tr>";
			tablaH += "<th>Fecha</th>";
			tablaH += "<th>Tipo Serv.</th>";
			tablaH += "<th>Nomb. Inh.</th>";
			tablaH += "<th>Nro. Inh.</th>";
			tablaH += "<th>Parcela</th>";
			tablaH += "<th>Nro. Clnt.</th>";
			tablaH += "</tr></thead>";
			
			tablaB = "<tbody>";
			for(var i = 0; i < actual.length; i++){
				tablaB += "<tr>";
				var f = actual[i].FechaMovimiento.substring(0,10).split("-");
				tablaB += "<td>" + f[2] + "/" + f[1] + "/" + f[0] + "</td>"; // YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY
				tablaB += "<td>" + actual[i].NombreTipoServicio + "</td>";
				tablaB += "<td>" + actual[i].Nombre + "</td>";
				tablaB += "<td>" + actual[i].NumeroInhumado + "</td>";
				tablaB += "<td>" + actual[i].parcela_formateada + "</td>";
				tablaB += "<td>" + actual[i].numerocliente + "</td>";
				tablaB += "</tr>";

				NroInhumado = actual[i].NumeroInhumado;
				nombre = actual[i].Nombre;
			}
			
			tablaB += "</tbody>";
			tabla = "<table>"+tablaH+tablaB+"</table>";
			tabla = "<div id='actual'>ACTUAL"+tabla+"</div>";
			htmlActual = tabla;

			// Completo
			let htmlCompleto = "";
			tabla = "";
			tablaH = '';
			tablaB = '';

			tablaH = "<thead class='thead-light'><tr>";
			tablaH += "<th>Fecha</th>";
			tablaH += "<th>Tipo Serv.</th>";
			tablaH += "<th>Nomb. Inh.</th>";
			tablaH += "<th>Nro. Inh.</th>";
			tablaH += "<th>Parcela</th>";
			tablaH += "<th>Nro. Clnt.</th>";
			tablaH += "<th></th>";
			tablaH += "</tr></thead>";
			
			tablaB = "<tbody>";
			
			for(var i = 0; i < completo.length; i++){
				tablaB += "<tr>";
				var f = completo[i].FechaMovimiento.substring(0,10).split("-");
				tablaB += "<td>" + f[2] + "/" + f[1] + "/" + f[0] + "</td>"; // YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY
				tablaB += "<td>" + completo[i].NombreTipoServicio + "</td>";
				tablaB += "<td>" + completo[i].Nombre + "</td>";
				tablaB += "<td>" + completo[i].NumeroInhumado + "</td>";
				tablaB += "<td>" + completo[i].parcela_formateada + "</td>";
				tablaB += "<td>" + completo[i].numerocliente + "</td>";
				tablaB += "<td>" + "<input type='button' class='btn borrarinhumado btn-sm btn-outline-dange' data-id='"+ completo[i].NumeroMovimiento +"' value='(-)'>" + "</td>";
				tablaB += "</tr>";

				NroInhumado = completo[i].NumeroInhumado;
				nombre = completo[i].Nombre;
			}
			
			tablaB += "</tbody>";
			tabla = "<table>"+tablaH+tablaB+"</table>";
			tabla = "<div id='completo'>COMPLETO"+tabla+"</div>";

			htmlCompleto = tabla;

			html = '<!-- Modal --> \
			<div class="modal fade" id="myModal" role="dialog"> \
			  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen modal-xl" style="max-width: 80%;"> \
			  \
				<!-- Modal content-->\
				<div class="modal-content">\
				  <div class="modal-header">\
					<h4 class="modal-title">TITULO</h4>\
					<button type="button" class="btn-close" data-dismiss="modal">&times;</button>\
				  </div>\
				  <div id="modalContenido" class="modal-body">\
					<p>Some text in the modal.</p>\
				  </div>\
				  <div class="modal-footer">\
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
				  </div>\
				</div>\
				\
			  </div>\
			</div>';

			$('#agregado').html(html);
			$('#modalContenido').text('');
			
			let bloque = "<div>";
   			bloque += 		"<table style='margin-left: auto; margin-right: auto'>";
        	bloque += 		"<tr valign='top'>";
           	bloque += 			"<td valign='top'>";
            bloque += 				"<div>" + htmlActual + "</div>";
           	bloque += 			"</td>";
			bloque += 			"<td valign='top'>";
            bloque += 				"<div>" + htmlCompleto + "</div>";
           	bloque +=			"</td>";
        	bloque +=		"</tr>";
   			bloque +=		"</table>";
			bloque += "</div>";
			$('#modalContenido').append(bloque);
			$('#myModal').modal('toggle');
			//$('.modal-title').text('Nro.Inhumado: ' + NroInhumado + " - " + nombre);
			$('.modal-title').text("Historico Parcela");

		});
	},	 
	onAClickGetContratosArrendamiento: function(_this) {
		let id_parcela = (_this).data("id");
		data = {id_Parcela: id_parcela};
		var myObj = data;
		_AJAX.UiGetContratosArrendamiento(myObj).then(function (datajson) {
			let data = datajson.contratos;
			let NroInhumado = "";
			let nombre = "";
			let html = "";
			let tabla = "";
			let tablaH = '';
			let tablaB = '';
			tablaH = "<thead class='thead-light'><tr>";
			tablaH += "<th>Vence</th>";
			tablaH += "<th>Emitido</th>";
			tablaH += "<th>Nro. Contrato</th>";
			tablaH += "<th>Parcela</th>";
			tablaH += "<th>Cliente</th>";
			tablaH += "</tr></thead>";
			tablaB = "<tbody>";
			for(var i = 0; i < data.length; i++){
				tablaB += "<tr>";
				var f = data[i].Fecha_Vencimiento.substring(0,10).split("-");
				tablaB += "<td>" + f[2] + "/" + f[1] + "/" + f[0] + "</td>"; // YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY
				var f = data[i].Fecha_Emision.substring(0,10).split("-");
				tablaB += "<td>" + f[2] + "/" + f[1] + "/" + f[0] + "</td>"; // YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY

				tablaB += "<td>" + data[i].Nro_Arrendamiento + "</td>";
				tablaB += "<td>" + data[i].parcelaReducido + "</td>";
				tablaB += "<td>" + data[i].numerocliente + " " + data[i].cliente + "</td>";
				tablaB += "</tr>";

				NroInhumado = data[i].NumeroInhumado;
				nombre = data[i].Nombre;
			}
			
			tablaB += "</tbody>";
			tabla = "<table>"+tablaH+tablaB+"</table>";
			
			
			html = '<!-- Modal --> \
			<div class="modal fade" id="myModal" role="dialog"> \
			  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen modal-xl"> \
			  \
				<!-- Modal content-->\
				<div class="modal-content">\
				  <div class="modal-header">\
					<h4 class="modal-title">TITULO</h4>\
					<button type="button" class="btn-close" data-dismiss="modal">&times;</button>\
				  </div>\
				  <div id="modalContenido" class="modal-body">\
					<p>Some text in the modal.</p>\
				  </div>\
				  <div class="modal-footer">\
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
				  </div>\
				</div>\
				\
			  </div>\
			</div>';

			$('#agregado').html(html);
			$('#modalContenido').text('');
			//$('#modalContenido').append("<div>"+htmlActual+"</div>"+"<div>"+htmlCompleto+"</div>");

			$('#modalContenido').append(tabla);
			$('#myModal').modal('toggle');
			$('.modal-title').text("Contrato Arrandamiento Parcela");
			
		});
	},
	onKeyUpEntrada: function(_this){
		let searchKey = $('#entrada').val();
		$('#resultados').html('');	
		if(searchKey.length>3){
			data = {searchKey: '%'+searchKey+'%'};
			var myObj = data;
			_AJAX.UiGetClientePagador(myObj).then(function (datajson) {
				let vuelta = datajson.cliente_pagador;
				let res='';
				for(let i=0;i<vuelta.length;i++){
					res += '<div class="suggest-element" data-id="'+vuelta[i].id+'" id="'+vuelta[i].id+'">'+vuelta[i].detalle+'</div>';
				}
				$('#resultados').html(res);
				$('#resultados').fadeIn(1000);
			});			
		}
	},	 	

	onLoadEntrada: function(_this) {
		alert("onLoadEntrada");
	},	
	onAClickBorrarClientePagador: function(_this) {
		let idClienteRelacionado = (_this).attr("data-idClienteRelacionado");
		id_cliente = idClienteRelacionado.split(".")[0] ;
		id_pagador = idClienteRelacionado.split(".")[1];
		data = {id_cliente: id_cliente, id_pagador: id_pagador};
		var myObj = data;

		_AJAX.UiBorraClientePagador(myObj).then(function (datajson) {
			if (datajson.status=="OK") {
				alert("Borrado OK");
				$(".tr-" + id_cliente).remove();
			} else {
				alert("Ocurrio un error con el borrado?");
			}
		});
	},	 
	
	onGetOrdenDePagoEditBaseData: function(_this){
		try {
			var _params = {"txtNro": $("#ID").val()};
			_AJAX.UiGetOrdenDePagoEditBaseData(_params).then(function (datajson) {
				var _html = "";
				_html += "<div class='row'>";
				var hoy1 = _TOOLS.getTodayDate("amd", "-");
				const cbo = {selected:"", id:"id", description:"descripcion"};
				_html += _TOOLS.getComboFromJson(datajson.suc,cbo,"Y",2,"aEmpresa","Empresa: ","class='form-control text dbase' ");
				_html += _TOOLS.getComboFromJson(datajson.caja,cbo,"Y",2,"aCaja","Caja de Tesorería: ","class='form-control text dbase' ");
				_html += _TOOLS.getDateBox("aFechaEmision", "Fecha de Emisión", 2, hoy1, "Y", "class='form-control text dbase'  ") + "";
				_html += _TOOLS.getDateBox("aFechaPago", "Fecha de pago", 2, hoy1, "Y", "class='form-control text dbase'  ") + "";
				_html += "</div>";
				_html += "<div class='row'>";
				_html += _TOOLS.getTextArea("aComentario", "Comentario", 4, "", "Y", "class='form-control text dbase' onkeydown='_TOOLS.limitText(this,null,500); ' onkeyup='_TOOLS.limitText(this,null,500);'",3,50) + "";
				_html += "</div>";
				_html += "<p><b>Cuentas e importes a registrar</b></p>" ;
				_html += "<div class='row'>";
				_html += _TOOLS.getComboFromJson(datajson.cuentas,cbo,"Y",2,"aCuenta","Cuenta contable: ","class='form-control text dbase' ");
				_html += "<div class='col-3'>";
				_html += "<span id='LBL-mporte-contable'>Importe:</span><br/>";
				_html += "<input type='number' class='form-control' id='importe-contable'/>";
				_html += "</div>";
				_html += "<div class='col-3'>";
				_html += "<input type='button' id='agregar-importe' class='btn btn-primary btn-raised' value='Agregar importe'>";
				_html += "</div>";
				_html += "<div class='col-3'>";
				_html += "<div id='cuentas'>";
				_html += "</div>";
				_html += "</div>";
				$("#form-detail").append(_html);
			});
		} catch (rex) {
			_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
			reject(rex);
		}
	},
	onGetAsientoDetail: function (_this) {
		try {
			var _params = { "txtNro": $("#ID").val() };
			_AJAX.UiGetAsientoDetails(_params).then(function (datajson) {
				var _html = "";
				_html += "<div id='form-detail-generator'>";
				_html += "<div class='row mx-1 p-1' style='border-radius: 10px;  border: 1px solid #000000;'>";
				_html += "<div class='col-md-3'>";
					_html += "<label for='SELECT-CTA'>Seleccione Cuenta</label>";
					_html += "<select class='form-control select dbase' name='SELECT-CTA' id='SELECT-CTA'>";
					_html += "<option value='0' selected>Seleccione</option > ";
					$.each(datajson.cuentas, function (j, val1) {_html += "<option value='" + val1.NUMERO + "'>" + val1.qryDEN + "</option > ";});
					_html += "</select>";
					_html += "<div class='invalid-feedback invalid-TEST-FIELD-1 d-none'></div>";
				_html += "</div>";
				/*fin select*/

				_html += "<div class='col-md-3'>";
					_html += "<label for='SELECT-DH'>Seleccione DH</label>";
					_html += "<select class='form-control select dbase' name='SELECT-DH' id='SELECT-DH'>";
					_html += "<option value='0' selected>Seleccione</option > ";
					$.each(datajson.debehaber, function (j, val1) {_html += "<option value='" + val1.COD + "'>" + val1.DEN + "</option > ";});
					_html += "</select>";
					_html += "<div class='invalid-feedback invalid-TEST-FIELD-1 d-none'></div>";
				_html += "</div>";
				_html += "<div class='col-md-3'>";
					_html += "<label for='TIPCON'>Código Comprobante</label>";
					_html += "<input data-type='text' autocomplete='nope' value='0' class='form-control text dbase ' type='text' name='TIPCOM' id='TIPCOM' data-clear-btn='false' placeholder='Código Comprobante'>";
					_html += "<div class='invalid-feedback invalid-TIPCON d-none'></div>";
				_html += "</div>";


				_html += "<div class='col-md-3'>";
					_html += "<label for='NUMCON'>Número Comprobante</label>";
					_html += "<input data-type='text' autocomplete='nope' value='0' class='form-control text dbase ' type='text' name='NUMCOM' id='NUMCOM' data-clear-btn='false' placeholder='Número Comprobante'>";
					_html += "<div class='invalid-feedback invalid-NUMCON d-none'></div>";
				_html += "</div>";


				_html += "<div class='col-md-3'>";
					_html += "<label for='IMPORTE'>Importe</label>";
					_html += "<input data-type='text' autocomplete='nope' value='' class='form-control text dbase ' type='text' name='IMPORTE' id='IMPORTE' data-clear-btn='false' placeholder='Importe'>";
					_html += "<div class='invalid-feedback invalid-IMPORTE d-none'></div>";
				_html += "</div>";

				_html += "<div class='col-md-9'>";
					_html += "<label for='DCOMENTARIO'>Comentario</label>";
					_html += "<input data-type='text' autocomplete='nope' value='' class='form-control text dbase ' type='text' name='DCOMENTARIO' id='DCOMENTARIO' data-clear-btn='false' placeholder='Comentario'>";
					_html += "<div class='invalid-feedback invalid-COMENTARIO d-none'></div>";
				_html += "</div>";

				

				_html += "<div class='col-12'>";
					/*container para el armado del item a agregar*/
					_html += "<div id='OPER-CONTAINER' name='OPER-CONTAINER'></div>";
					/*fin de container*/
					_html += "</BR>";
					/*inicio boton y textbox con total*/
					_html += "<table width=100%><tr><td width=20% style='text-align: left;'>";
					_html += "<button type='button' class='btn-raised' id='OPER-ADD-BTN' name='OPER-ADD-BTN' disabled onclick=javascript:insertarOnClick();><i class='material-icons'>done</i>Insertar</button>";
					_html += "</td><td width=50% style='text-align: right;'><div id='txtWarning' name='txtWarning'></div>";
					_html += "</td><td width=30% style='text-align: right;'>";
					_html += "<label for='TOTAL-1'>Balance $</label>";
					_html += "<input style='text-align:right;' data-type='text' autocomplete='nope' disabled value='0' class='form-control text dbase' type='text' name='TOTAL-1' id='TOTAL-1' data-clear-btn='false' placeholder='Balance'></input>";
					_html += "<div class='invalid-feedback invalid-TOTAL-1 d-none'></div>";
					_html += "</td></tr></table>";
					/*fin boton y textbox*/
					_html += "</div>";
					_html += "</br>";
					_html += "<input type=text class='form-control text dbase' style='display: none' name='OPER-COUNTER' id='OPER-COUNTER' value=0></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' name='OPER-MODIFIED' id='OPER-MODIFIED' value=0></input>";
					//_html += "<input type=hidden name='OPER-PRECIOS' id='OPER-PRECIOS' value='" + JSON.stringify(datajson.precios) + "'></input>";
					//_html += "<input type=hidden name='OPER-COTIZACION' id='OPER-COTIZACION' value='" + JSON.stringify(datajson.cotizacion) + "'></input>";
				_html += "   </div>";
				_html += "</div>";

				_html += "<table border=1 width=100% id='table-detail' name='table-detail' style='width:100%;'>";
				var tot = 0;
				_html += "<tr>";
				_html += "<th></th><th>Nro</th><th>Nro de Cuenta</th><th>Nombre</th><th>D/H</th><th>C. C.</th><th>Nro CP</th><th>Importe</th>";
				_html += "</tr>";
				//alert("2");
				var counter = 0;
				$.each(datajson.detalle, function (i, val) {
					counter += 1;
					tot = tot + parseFloat(val.IMPORTE);
					_html += "<tr id='detail-form-row-" + counter + "'>";
					_html += "   <td><a href='#' onClick=\"javascript:deleteRow('detail-form-row-" + counter + "','detail-importe-" + counter + "','" + counter + "');return false;\">eliminar</a></td>";
					_html += "   <td id='detail-form-row-" + counter + "-id'>" + val.RENGLON + "</td>";
					_html += "   <td id='detail-form-row-" + counter + "-cuenta'>" + val.CUENTA + "</td>";
					_html += "   <td id='detail-form-row-" + counter + "-nombre'>" + val.NOMBRE + "</td>";
					_html += "   <td id='detail-form-row-" + counter + "-dh'>" + val.DH + "</td>";
					_html += "   <td id='detail-form-row-" + counter + "-tipcom>'" + val.TIPCOM + "</td>";
					_html += "   <td id='detail-form-row-" + counter + "-numcom'>" + val.NUMCOM + "</td>";
					_html += "   <td id='detail-form-row-" + counter + "-importe'>" + val.IMPORTE; 
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-id-" + counter + "' name='detail-id-" + counter + "' value='" + val.RENGLON + "'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-cuenta-" + counter + "' name='detail-cuenta-" + counter + "' value='" + val.CUENTA+"'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-nombre-" + counter + "' name='detail-nombre-" + counter + "' value='" + val.NOMBRE+ "'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-dh-" + counter + "' name='detail-dh-" + counter + "' value='" + val.DH + "'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-tipcom-" + counter + "' name='detail-tipcom-" + counter + "' value='" + val.TIPCOM + "'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-numcom-" + counter + "' name='detail-numcom-" + counter + "' value='" + val.NUMCOM + "'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-comentario-" + counter + "' name='detail-comentario-" + counter + "' value='" + val.COMENTARIO + "'></input>";
					_html += "<input type=text class='form-control text dbase' style='display: none' id='detail-importe-" + counter + "' name='detail-importe-" + counter + "' value='" + val.IMPORTE + "'></input>";
					_html += "</td>";
					_html += "</tr>";
					
				});

				//alert("3");
				//if ($("#NRO_RECIBO").val() != "") {
				//	_html += "<tr id='detail-form-total'>";
				//	_html += "   <td colspan=3 id='detail-form-con' align=right> Total $</td>";
				//	_html += "   <td id='detail-form-total'>" + tot + "</td>";
				//	_html += "</tr>";
				//}
				//_html += "</table>"
				_html += "</BR>";


				$("#form-detail").append(_html);
				$("#OPER-COUNTER").val(counter);


				$("#SELECT-CTA").change(function () { validReng(); });
				$("#SELECT-DH").change(function () { validReng(); });
				$("#TIPCOM").mouseleave (function () { validReng(); });
				$("#NUMCOM").mouseleave (function () { validReng(); });
				$("#IMPORTE").mouseleave (function () { validReng(); });
				$("#DCOMENTARIO").mouseleave(function () { validReng(); });

				/*armo fecha de hoy en el recibo*/
				var fer = $("#FECHA").val();
				if (fer != "") {
					$("#FECHA").prop('disabled', true);
				}
				else {
					var hoy1 = _TOOLS.getTodayDate("amd", "-");
					$("#FECHA").val(hoy1);
				}
				var nr = $("#NUMERO_ENCABEZADO").val();
				if (nr != "") { $("#NUMERO_ENCABEZADO").prop('disabled', true); }
				tot = tot.toFixed(2);
				$("#TOTAL-1").val(tot);
				enableSendForm();
			});
		} catch (rex) {
			_FUNCTIONS.onAlert({ "message": rex.message, "class": "alert-danger" });
			reject(rex);
		}


	},
	onKeyUpEntradaPagador: function(_this){
		let searchKey = $('#entradaPagador').val();
		$('#resultadosPagador').html('');	
		if(searchKey.length>3){
			data = {searchKey: searchKey};
			var myObj = data;
			_AJAX.UiGetClienteParaPagador(myObj).then(function (datajson) {
				let vuelta = datajson.cliente_pagador;
				let res='';
				for(let i=0;i<vuelta.length;i++){
					res += '<div class="suggest-element-pagador" data-id="'+vuelta[i].id+'" id="'+vuelta[i].id+'">'+vuelta[i].detalle+'</div>';
				}
				$('#resultadosPagador').html(res);
				$('#resultadosPagador').fadeIn(1000);
			});			
		}
	},	 
	UiAsociarClienteParaPagador: function(_this){},
	onInsertarNotaEnCliente: function(_this){
		let nota = $('#nota').val();
		let id_cliente = $('.btn-abm-accept').attr('data-id'); // el boton aceptar para guardar, tiene el id del cliente-
		let id_tipo_nota = $('#id_tipo_notas').children("option:selected").val();
		if (id_cliente=='null'||id_cliente=='undefined'||id_cliente==0||
			id_tipo_nota=='null'||id_tipo_nota=='undefined'||id_tipo_nota==0||
			nota=='null'||nota=='undefined'||nota=='')
		{
			alert("Para guardar una nota: la misma tiene que tener contenido, tiene que haber seleccionado un tipo de nota y estar editando un cliente ya existente");
			return;
		}
		data = {id_cliente: id_cliente, id_tipo_nota: id_tipo_nota, nota: nota};

		var myObj = data;
		_AJAX.UiInsertarNotaEnCliente(myObj).then(function (datajson) {
			let vuelta = datajson.notaAgregadaCliente;
			var dt = vuelta.fecha_alta.split(" ");
			var f = dt[0].substring(0,10).split("-"); // parte fecha
			var h = dt[1].split(":"); // parte hora
			var fechaFormateada=  f[2] + "/" + f[1] + "/" + f[0] + " " + h[0] + ":" + h[1] ; // YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY  HH:MM
			let newrow = "<tr><td>" + fechaFormateada 	+ "</td>" + 
					"<td>" + vuelta.tipo_nota  	+ "</td>" + 
					"<td>" + vuelta.nota       	+ "</td>" +
					"<td>" + vuelta.usuario 	+ "</td></tr>";
			$('#notasAsociadasAlCliente > tbody').prepend(newrow);
			$('#nota').val("");
		});	
	},
	onActualizarObservacionesCliente: function(_this){
		let id_cliente = $('.btn-abm-accept').attr('data-id'); // el boton aceptar para guardar, tiene el id del cliente-
		let obs = $('#trDatosComplementarios').val();
		data = {id_cliente: id_cliente, observaciones: obs};
		var myObj = data;
		_AJAX.UiActualizarObservacionesCliente(myObj).then(function (datajson) {
			let vuelta = datajson.observacionesCliente;
		});	
	},
	GrabarDatosComplementarios_CEM: function(idCliente){
		let obs = $('#txtDatosComplementarios').val();
		data = {id_cliente: idCliente, observaciones: obs};
		var myObj = data;
		_AJAX.UiActualizarObservacionesCliente(myObj).then(function (datajson) {
			let vuelta = datajson.observacionesCliente;
		});	
	},
	onGetObservacionesCliente: function(_this){
		let id_cliente = $('.btn-abm-accept').attr('data-id'); // el boton aceptar para guardar, tiene el id del cliente-
		if (id_cliente === undefined || id_cliente == 'undefined' || id_cliente == 'null') {
			id_cliente = $('#id_cliente').val();
		}
		data = {id_cliente: id_cliente};
		var myObj = data;
		_AJAX.UiGetObservacionesCliente(myObj).then(function (datajson) {
			let observ
			if (Array.isArray(datajson.observacionesCliente))
			{
				if (datajson.observacionesCliente.length>0) {
					observ = datajson.observacionesCliente[0].observaciones;
				} else {
					observ = "";
				}
			} else {
				observ = datajson.observacionesCliente.observaciones;
			}
			$('#btnObservacionesCliente').attr('title', observ);
			$('#txtDatosComplementarios').val(observ);
		});	
	},	
	/**
	 * Arma la cuenta corriente y sus versiones. Impagos, Todos, Plan de pagos.
	 *
	 * Y cantidad de parametros para por ejemplo ocultar botones o mostrarlos dado que tambien se usa la misma pantalla desde la ventana de recibos.
	 *  
	 * @param string _this
	 * @param string tipoCC IMPAGA  COMPLETA   RESUMENDEUDA  
	 * @param string arg_id_cliente
	 * @param string arg_titulo
	 * @param string arg_div 
	 * @param string arg_paga 
	 * @param string arg_botones	  
	 * 
	 */    
	onGetCuentaCorriente: function(_this, tipoCC, arg_id_cliente, arg_titulo, arg_div, arg_paga, arg_botones, arg_readonly){
		let readonly = false;
		switch (arguments.length) {
			case 8:
				if (arg_readonly.length>0) {
					if (arg_readonly=="true") {
						readonly = true;		
					}
					if (arg_readonly=="false") {
						readonly = false;		
					}
				}
			case 7:
			case 6:
			case 5:
			case 4:
				titulo = arg_titulo;
			case 3:
				id_cliente = arg_id_cliente;
			default:  // menos de 2 no puede haber
				if (  (arg_id_cliente==null || arg_id_cliente=='undefined')){
					id_cliente=$('#contenidoGenerico').attr('data-id');
					if (id_cliente=='undefined'||id_cliente==null||id_cliente==''){
						id_cliente = $('.btn-abm-accept').attr('data-id');  // lo uso desde cliente
					}
				}
				if (arg_titulo==null || arg_titulo=='undefined'){
					titulo=$('#contenidoGenerico').attr('data-titulo');
					if (titulo=='undefined' || titulo==undefined || titulo=='null' || titulo==null) {
						titulo='';
					}
				}				
		}
		var _display = "";
		if (arg_paga=='true') {
			_display="display:block;";
		} else {
			_display="display:none;";
		}
		data = {id_cliente: id_cliente};
		_AJAX.UiGetCuentaCorriente(data).then(function (datajson) {
			let permisosUpd = datajson.permisosAdicionalesUpd;
			let permisosDel = datajson.permisosAdicionalesDel;
			let cc = [];
			if (typeof tipoCC !== "undefined") {
				if (tipoCC.toUpperCase() === "IMPAGA"){
					tipoCC = "IMPAGA";
				}
				if (tipoCC.toUpperCase() === "COMPLETA"){
					tipoCC = "COMPLETA";
				}				
				if (tipoCC.toUpperCase() === "RESUMENDEUDA"){
					tipoCC = "RESUMENDEUDA";
				}				
			} else {
				tipoCC = "COMPLETA";
			}
			
			if (tipoCC==="IMPAGA" || tipoCC==="COMPLETA") {
				if (tipoCC==="IMPAGA") {
					// Solo Impaga
					cc = datajson.cuentaCorrienteImpago;
				} else {
					// Full
					cc = datajson.cuentaCorriente;
				}
				tabla =  "<table id= 'tablaCC' class='table table-sm table-striped'>";
				tabla += "<thead class='thead-light'>";
				tabla += 	"<tr>";
				tabla += 		"<th></th>";
				tabla += 		"<th><b>Comprobante</b></th>";
				tabla +=        "<th><b>Recibo</b></th>";
				tabla += 		"<th><b>Emision</b></th>";
				tabla += 		"<th><b>Vencimiento</b></th>";
				tabla += 		"<th><b>Importe</b></th>";
				tabla += 		"<th><b>Saldo</b></th>";
				tabla += 		"<th><b>SEC</b></th>";
				tabla += 		"<th><b>Parcela</b></th>";
				tabla += 		"<th><b>Descripcion</b></th>";
				tabla += 		"<th><b><b>a pagar</b></td>";
				tabla += 		"<th><b>Meses</b></th>";
				tabla += 		"<th><b>Tipo Plan</b></th>";
				tabla += 		"<th></th>";
				tabla += 	"</tr>";
				tabla += "</thead>";
				tabla += "<tbody>";
				let _saldo = 0.0;
				for(var i = 0; i < cc.length; i++){
					let muestra_borrar_cc = true;
					tabla += "<tr data-id='"+cc[i].ID_CuentaCorriente+"'>";
  		     		tabla += "<td>";
					if (permisosUpd.length>0) {
						if (permisosUpd[0].permitido === "S") {
							tabla += 	"<input type='button' id='btnModificarCC' class='col btn btn-sm btn-outline-primary botonModCC botonCC rounded-pill w-100 mx-auto' value='M'>";
						}
					} 
					tabla += "</td>";
					tabla += "<td>" + cc[i].comprobante + "</td>";
					let r = "";
					if (cc[i].recibos !== null && cc[i].recibos != "null" && cc[i].recibos != "") {

						r = "<td class='masterTooltip toggleTR' data-id='" + cc[i].ID_CuentaCorriente +"' data-body='" + btoa(cc[i].recibos) + "' title='" + _TOOLS.htmlToText(cc[i].recibos) + "'><img src='./assets/img/media/imagenes/addfav.png'></td>";
						muestra_borrar_cc = false;
					} else {
						r = "<td></td>";
					}					
					tabla += r;
					tabla += "<td>" + _TOOLS.formatDDMMYYYY(cc[i].Fecha_Emision,"/") + "</td>";
					tabla += "<td>" + _TOOLS.formatDDMMYYYY(cc[i].Fecha_Vencimiento,"/") + "</td>";
					tabla += "<td>" + cc[i].Importe + "</td>";
					tabla += "<td>" + cc[i].Saldo + "</td>";
					tabla += "<td>" + ((cc[i].SEC=='null' || cc[i].SEC==null) ? '' : cc[i].SEC) + "</td>";
					tabla += "<td>" + cc[i].parcelaReducido + "</td>";
					tabla += "<td>" + ((cc[i].nombrePrecio=='null'||cc[i].nombrePrecio==null) ? '' : cc[i].nombrePrecio) + "</td>";
					tabla += "<td align='right' style='" + _display + "'>";
					var _ver = "block";
					if (cc[i].id_tipo_comprobante == "17") { _ver = "none"; }
					if (cc[i].estado_desenganche != "A5" && cc[i].estado_desenganche != "B5") {
						if (cc[i].Saldo < 0.0) {
							tabla += "<input class='a_pagar' style='text-align:right;display:" + _ver + ";' size='10' type='text' id='importe_a_pagar' name='importe_a_pagar' value='' onkeyup=javascript:ValidateNumericRange(this," + cc[i].Saldo + ",0);TotalizarReciboCEM(this);>";
						} else {
							tabla += "<input class='a_pagar' style='text-align:right;display:" + _ver + ";' size='10' type='text' id='importe_a_pagar' name='importe_a_pagar' value='' onkeyup=javascript:ValidateNumericRange(this,0," + cc[i].Saldo + ");TotalizarReciboCEM(this);>";
						}
					}
					tabla += "<input type='hidden' id='id_cuenta_corriente' name='id_cuenta_corriente' value='" + cc[i].ID_CuentaCorriente + "'>";
					tabla += "</td>";
					tabla += "<td>" + ((cc[i].Meses=='null'||cc[i].Meses==null)?'':cc[i].Meses) + "</td>";
					let p1 = "", p2 = "", p="";
					if (cc[i].plan_pago !== null && cc[i].plan_pago != "null" && cc[i].plan_pago != "") {
						p1 = cc[i].plan_pago;
					}
					if (cc[i].tipo_plan !== null && cc[i].tipo_plan != "null" && cc[i].plan_pago != "") {
						p2 = cc[i].tipo_plan;
						p = "<td class='masterTooltip' title='"+ _TOOLS.htmlToText(p1) +"'>"+p2+"</td>";
					} else {
						p = "<td></td>";
					}
					tabla += p;
					tabla += "<td data-id='"+cc[i].ID_CuentaCorriente+"'>";
					// Si hay recibo --> no se puede borrar
					if (muestra_borrar_cc===true){
						// verificar permisos adicionales
						if (permisosDel.length>0) {
							if (permisosDel[0].permitido === "S") {
								tabla += "<input type='button' id='btnElimininarCC' class='col btn btn-sm btn-outline-danger rounded-pill botonDelCC botonCC' value='D'>";
							}
						} 						
					}
					tabla += "</td>";
					tabla += "</tr>";
					tabla += "<tr class='d-none detalle-" + cc[i].ID_CuentaCorriente + "'><td colspan='12'>"+cc[i].recibos+"</td></tr>";

					_saldo = ((parseFloat(_saldo).toFixed(2) * 100 + parseFloat(cc[i].Saldo).toFixed(2) * 100) / 100 ).toFixed(2);
				}
				tabla +="<tr bgcolor='ivory'>";
				tabla +="<td align='center'></td>";
				tabla +="<td colspan='4' align='right'><b>Total Saldo</b></td>";
				tabla +="<td align='right' id='td_total_saldo'>" + _saldo + "</td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="</tr>";
				tabla +="<tr bgcolor='ivory'>";
				tabla +="<td align='center'></td>";
				tabla +="<td colspan='4' align='right'><b>Total a pagar</b></td>";
				tabla +="<td align='right' id='tdTotal'></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="<td></td>";
				tabla +="</tr>";
				tabla += 	"</tbody>";
				tabla += "</table>";
				let _htmlModal = '<!-- Modal --> \
				<div class="modal fade xxx" id="myModalGenerica" role="dialog"  style="z-index:9999;"> \
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="width: 1000px; max-width: 100%;"> \
				\
					<!-- Modal content-->\
					<div class="modal-content custom-modal-big">\
					<div class="modal-header">\
						<h4 class="modal-title">TITULO</h4>\
						<button type="button" class="close" data-dismiss="modal">&times;</button>\
					</div>\
					<div id="myModalGenericaContenido" class="modal-body modal-xl">\
						<p>Some text in the modal.</p>\
					</div>\
					<div class="modal-footer modal-xl">\
						<button type="button" class="btn btn-danger closeMyModalGenerica" data-dismiss="modal">Close</button>\
					</div>\
					</div>\
					\
				</div>\
				</div>\
				<div id="myModal2"></div>\
				<div id="masData"></div>';
				if (tipoCC=='IMPAGA') {
					_htmlModal = _htmlModal.replace(/TITULO/gi, "Cuenta Corriente - Impagos" + " / " + titulo);
				} else {
					_htmlModal = _htmlModal.replace(/TITULO/gi, "Cuenta Corriente" + " / " + titulo);
				}
				vDisabled = "";
				if (readonly===true){vDisabled = "disabled";}
				let final = "<div class='container'>"+
								"<div class='row'>"+
									"<input type='button' id='btnNuevoComprobante' class='col btn-sm btn-outline-success rounded-pill botonInsCC botonCC' value='Más Deuda?'>"+
									"<input type='button' id='btnObservacionesCliente' class='col btn-sm btn-outline-success rounded-pill mastertooltip' value='Datos Cliente' title=''></input>"+
									"<input type='button' id='btnCuentaCorriente' class='col btn-sm btn-outline-success rounded-pill' value='Cta.Cte.' " + vDisabled + " ></input>"+
									"<input type='button' id='btnCuentaCorrienteImpaga' class='col btn-sm btn-outline-success rounded-pill' value='Impago' " + vDisabled + " ></input>"+
									"<input type='button' id='btnCuentaCorrienteHistorica' class='col btn-sm btn-outline-success rounded-pill' value='CC Histórica'></input>"+
									"<input type='button' id='btnResumenDeuda' class='col btn-sm btn-outline-success rounded-pill' value='Resumen Deuda'></input>"+
									"<input type='button' id='btnPlanPago' class='col btn-sm btn-outline-success rounded-pill' value='Plan Pagos'></input>"+													
								"</div>" +
								"<div class='row'>" +
									"<div id='trDatosComplementarios' class='border border-dark rounded-5' style='display: none'>" +
										"<label class='align-top' for='txtDatosComplementarios'>Datos Complementarios:</label>" +
										"<input type='button' class='btn-sm btn-outline-success rounded-pill align-botton float-right' value='Grabar datos' onclick='javascript:_FUNCTIONS.GrabarDatosComplementarios_CEM(" + id_cliente + ");'></input>" +
										"<textarea id='txtDatosComplementarios' name='datosComplementarios'  rows='8' cols='60'>" +
										"</textarea>" +
									"</div>" +
								"</div>" +								
								"<div class='row'>" + 
									"<div id='tablaEdicion' class='d-none'></div>"+
									"<div id='tablaAlta' class='d-none'></div>"+
									tabla + 
								"</div>"+
							"</div>";	
				if (arg_div==null || arg_div=='undefined') {
				// Al contenedor generico le pongo un modal, pero el modal se llama myModal
					$('#contenidoGenerico').html('');
					$('#contenidoGenerico').html(_htmlModal);
					$('#myModalGenericaContenido').html('');
					$('#myModalGenericaContenido').html(final)
				} else {
					if (arg_botones=='null' || arg_botones=='undefined' || arg_botones == 'true') {
						$(arg_div).html(final + "<div id='masData'></div>");	
					} else {
						// este es el caso de recibos, vamos a probar de ocultar botones. tengo que pasarle arg_botones = false
						// reformulo la estructura del html de   final.
						final = "<div class='container'>"+
									"<div class='row'>" + 
										"<div id='tablaEdicion' class='d-none'></div>"+
										"<div id='tablaAlta' class='d-none'></div>"+
										tabla + 
									"</div>"+
								"</div>";	
						$(arg_div).html(final + "<div id='masData'></div>");	
					}
				}
				let tipoComprobante = datajson.tiposDeComprobanteProv;
				let selectTipoDeComprobanteProvisorio = "<select class='d-none' id='tipoComprobanteData'>";				
				$.each(tipoComprobante, function (index, valueItem) {
					selectTipoDeComprobanteProvisorio += "  <option selected='false' value='"+ valueItem.id_tipo_comprobante +"'>"+  valueItem.CodigoComprobante +"</option>";
				});
				selectTipoDeComprobanteProvisorio += "</select>";
				let listaPrecios = datajson.listaPrecios;
				let selectlistaPrecios = "<select class='d-none' id='listapreciosData'>";				
				$.each(listaPrecios, function (index, valueItem) {
					selectlistaPrecios += "  <option selected='false' data-precio='"+valueItem.Precio+"' value='"+ valueItem.id_ConceptoListaPrecio +"'>"+  valueItem.operacion +"</option>";
				});
				selectlistaPrecios += "</select>";
				$('#masData').append("<div id='dataListaDePrecios'></div>");
				$('#dataListaDePrecios').attr("data-idcliente", id_cliente);								
				$('#dataListaDePrecios').attr("data-jsonb64-listaprecios", JSON.stringify( listaPrecios )) ;	// guardo la data como array de json sin base64						
				let parcela = datajson.parcelasDelCliente;
				let selectParcelas = "<select class='d-none' id='parcelasData'>";				
				$.each(parcela, function (index, valueItem) {
					selectParcelas += "  <option selected='false' value='"+ valueItem.id_Parcela +"'>"+  valueItem.parcela_formateada +"</option>";
				});
				selectParcelas += "</select>";		
				let inhumados = datajson.inhumadosEnParcela;
				let selectInhumados = "<select class='d-none' id='selectInhumadosData'>";				
				$.each(inhumados, function (index, valueItem) {
					selectInhumados += "  <option selected='true' value='"+ valueItem.id_inhumado +"'>"+  valueItem.Nombre +"</option>";
				});
				selectInhumados += "</select>";					
				if (arg_div=='undefined' || arg_div==null){
					$('#myModalGenericaContenido').append(selectTipoDeComprobanteProvisorio);
					$('#myModalGenericaContenido').append(selectlistaPrecios);
					$('#myModalGenericaContenido').append(selectParcelas);
					$('#myModalGenericaContenido').append(selectInhumados);
					$('#myModalGenerica').modal('show');
				} else {
					$(arg_div).append(selectTipoDeComprobanteProvisorio);
					$(arg_div).append(selectlistaPrecios);
					$(arg_div).append(selectParcelas);
					$(arg_div).append(selectInhumados);
				}
			} // Fin Completa o Impaga
			if (tipoCC === "RESUMENDEUDA") {
				cc = datajson.resumenDeDeuda;
			} // Fin Resumen
		});	// Fin Ajax 
	},
	onGetCuentaCorrienteHistorica: function(_this, arg_div, arg_paga, arg_botones, arg_readonly){
		let id_cliente = $('.btn-abm-accept').attr('data-id');
		if (id_cliente==null || id_cliente=='undefined'){id_cliente=$('#contenidoGenerico').attr('data-id');}
		let clienteIntento2 = $("div#masData div#dataListaDePrecios").attr("data-idcliente");
		if ((id_cliente=='undefined' || id_cliente==null)&& clienteIntento2!='null' &&clienteIntento2!='undefined'){id_cliente = clienteIntento2;}
		data = {id_cliente: id_cliente};
		let readonly = false;
		if (arg_readonly.length>0) {
			if (arg_readonly=="true") {
				readonly = true;		
			}
			if (arg_readonly=="false") {
				readonly = false;		
			}
		}
		let _htmlModal2 = '<!-- Modal --> \
		<div class="modal fade xxx" id="myModalGenerica2" data-backdrop="static" role="dialog" style="z-index:99999;"> \
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"  \
		\
			<!-- Modal content-->\
			<div class="modal-content">\
			<div class="modal-header">\
				<h4 class="modal-title">TITULO</h4>\
				<button type="button" class="close" data-dismiss="modal">&times;</button>\
			</div>\
			<div id="myModalGenericaContenido2" class="modal-body modal-lg">\
				|ITEM|\
			</div>\
			<div class="modal-footer modal-xl">\
				<button type="button" class="btn btn-danger closeMyModalGenerica2" data-dismiss="modal">Cerrar</button>\
			</div>\
			</div>\
			\
		</div>\
		</div>\
		<div id="masData2"></div>';

		_AJAX.UiGetCuentaCorrienteHistorica(data).then(function (datajson) {
			if (datajson.status==="OK") {
				cch = datajson.ccHistorica;
				tabla =  "<table id= 'tablaCC' class='table table-sm table-striped'>";
				tabla += "<thead class='thead-light'>";
				tabla += 	"<tr>";
				tabla += 		"<th><b>Fecha proceso</b></th>";
				tabla += 		"<th><b>Fecha valor</b></th>";
				tabla += 		"<th><b>Fecha vencimiento</b></th>";
				tabla += 		"<th><b>Comprobante</b></th>";
				tabla += 		"<th><b>Detalle</b></th>";
				tabla += 		"<th align='right'><b>Importe</b></th>";
				tabla += 		"<th align='right'><b>Importe pagado</b></th>";
				tabla += 		"<th align='right'><b>Saldo</b></th>";
				tabla += 	"<tr>";
				tabla += "</thead>";
				tabla += "<tbody>";
				for(var i = 0; i < cch.length; i++){
					tabla += "      <tr>";
					tabla += "        <td align='center'>" + cch[i].FechaProcesoStr + "</td>";
					tabla += "        <td align='center'>" + cch[i].FechaValorStr + "</td>";
					tabla += "        <td align='center'>" + cch[i].FechaVencimientoStr + "</td>";
					tabla += "        <td>" + cch[i].Comprobante + "</td>";
					tabla += "        <td>" + cch[i].Detalle + "</td>";
					tabla += "        <td align='right'>" + cch[i].ImporteStr + "</td>";
					tabla += "        <td align='right'>" + cch[i].ImportePagadoStr + "</td>";	
					tabla += "        <td align='right'>" + cch[i].SaldoStr + "</td>";
					tabla += "      </tr>";
				}
				tabla += 	"</tbody>";
				tabla += "</table>";
				_htmlModal2 = _htmlModal2.replace(/TITULO/gi, "Cuenta Corriente Historica, anterior al 1/11/2012");
				_htmlModal2 = _htmlModal2.replace("|ITEM|", tabla);
				$('#myModal2').html(_htmlModal2);
				$('#myModalGenerica2').modal("show");
			} else {
				alert("Ocurrio un error al recuperar los datos.");
			}
		});	// Fin Ajax 
	},
	onGetResumenDeDeuda: function(_this){
		let id_cliente = $('.btn-abm-accept').attr('data-id');
		if (id_cliente==null || id_cliente=='undefined'){id_cliente=$('#contenidoGenerico').attr('data-id');}
		let clienteIntento2 = $("div#masData div#dataListaDePrecios").attr("data-idcliente");
		if ((id_cliente == 'undefined' || id_cliente == null) && clienteIntento2 != 'null' && clienteIntento2 != 'undefined') { id_cliente = clienteIntento2; }
		data = { id_cliente: id_cliente };
		console.log("resumen de cuenta");
		console.log(data);
		_AJAX.UiGetCuentaCorriente(data).then(function (datajson) {
			console.log(datajson);
			let resDeuda = datajson.resumenDeDeuda;
			let totalItems=-1;
			let deudaTotal=parseFloat(0.00);
			_html = "<div id='resumenDeuda'>";
			$.each(resDeuda, function (index, valueItem) {
				totalItems = index;
				deudaTotal += parseFloat(valueItem.Saldo);
				if (index == 0) {
					_html += 	"<table width='100%'>" +
								"   <tr>" +
								"      <td align='center'>" +
								"         <h3>Corporación del cementerio Británico de Buenos Aires</h3>" +
								"         <h3>British Cemetery Corporation</h3>" +
								"         <b style='font-size:12px;'>(fundada antes de 1821, con personería jurídica desde 1913)</b><br/>" +
								"      </td>" +
								"   </tr>" +
								"</table>" +
								"<br/>" +
								"<table width='100%'>" +
								"   <tr>" +
								"      <td align='center' style='border:solid 1px silver;'>" +
								"         <table style='font-size:12px;'>" +
								"            <tr><td align='left'>" + valueItem.pagador + "</td></tr>" +
								"            <tr><td align='left'>" + valueItem.domicilio + "</td></tr>" +
								"            <tr><td align='left'>" + valueItem.CodigoPostal + " - " + valueItem.Localidad + "</td></tr>" +
								"         </table>" +
								"      </td>" +
								"      <td align='right' style='font-size:12px;'>" +
								"         <b>ADMINISTRACIÓN</b></br>Av.Elcano 4568<br/>(1427) Buenos Aires<br/>Tel/Fax (54 11) 4553-3403 / 4555-3957<br/><br/>" +
								"         <b>FILIAL CEMENTERIO JARDÍN</b><br/>Morse 203 y Av.Sesquicentenario (Ruta 197)<br/>Ingeniero Pablo Nogués<br/><b>(1613) Prov.de Buenos Aires</b><br/>Tel/Fax (54 11) 4463-0045" +
								"      </td>" +
								"   </tr>" +
								"</table>" +
								"<br/>" +
								"<table width='100%'>" +
								"   <tr>" +
								"      <td><b>Estimado cliente,</b><p>Según nuestros registros los siguientes saldos figuran como impagos.  De ser así, agradeceríamos su pronta cancelación.</p><h4>De existir error, comunicarse telefónicamente, por e-mail o personalmente, así podremos efectuar las correcciones.</h4></td>" +
								"   </tr>" +
								"</table>" +
								"<br/>" +
								"<h4>Resúmen de deuda del cliente nº " + valueItem.numerocliente + " - " + valueItem.cliente + "</h4>" +
								"<table width='100%' style='font-size:12px;' cellpadding='0' cellspacing='0'>" +
								"      <tr>" ;
					_html += 	"        <td align='center'><b>Fecha emisión</b></td>" +
								"        <td align='center'><b>Fecha finalización</b></td>" +
								"        <td align='center'><b>Comprobante</b></td>" +
								"        <td align='right'><b>Importe</b></td>" +
								"        <td align='right'><b>Saldo</b></td>" +
								"        <td align='right'><b>Parcela</b></td>" +
								"      </tr>";
				} // index = 0
				_html += "      <tr class='lineas_tr' id='tr_" + index + "' style='font-size:12px;' >" +
  							"        <td align='center'>" + _TOOLS.formatDDMMYYYY(valueItem.Fecha_Emision, "/") + "</td>" +
							"        <td align='center'>" + _TOOLS.formatDDMMYYYY(valueItem.Fecha_Vencimiento, "/") + "</td>" +
							"        <td align='center'>" + valueItem.comprobante + "</td>" +
							"        <td align='right'>" + parseFloat(valueItem.Importe).toFixed(2) + "</td>" +
							"        <td align='right'>" + parseFloat(valueItem.Saldo).toFixed(2) + "</td>" +
							"        <td align='right'>" + valueItem.parcelaReducido + "</td>" +
							"      </tr>";
			});
			if (totalItems>0){
				_html += 	"      <tr>" +
							"        <td colspan='8'><hr></td>" +
							"      </tr>" +
							"      <tr>" +
							"        <td></td>" +
							"        <td></td>" +
							"        <td></td>" +
							"        <td align='right'><h4>Total de deuda</h4></td>" +
							"        <td align='right'><h4>" + parseFloat(deudaTotal).toFixed(2) + "</h4></td>" +
							"        <td></td>" +
							"      </tr>" +
							"</table>";
			}
			_html += 	"<table>" +
						"   <tr valign='top'>" +
						"      <td>" +
						"         <h4>Para efectuar pagos usted podra utilizar los siguientes medios:</h4>" +
						"         <p style='font-size:12px;'>" +
						"         1) Oficinas de Chacarita o Nogués - en efectivo, cheque, o con tarjeta de credito o debito VISA, o tarjeta de credito AMEX. CUIT: 30-52641781-6<br/>" +
						"         2) Telefónicamente - con tarjeta de credito VISA<br/>" +
						"         3) Banco Patagonia: en cualquier sucursal del Banco <b>indicando numero de cliente y nombre y apellido.</b><br/>" +
						"         4) Transferencia bancaria o cajero de autoservicio - CBU 0340316500014498202000 alias: cementerio.britanico<b>.  Recuerde enviar por whatsapp 11 3772-2359 o e-mail copia del deposito, ya que en estas modalidades de pago el sistema bancario no nos brinda suficiente información dificultando la correcta imputación del pago.</b>" +
						"         </p>" +
						"      </td>" +
						"   </tr>" +
						"</table>";
			_html += "</div>";
			let _print = "";
			_print += "<style>		";
			_print += "@media print";
			_print += "{    ";
			_print += "    .no-print, .no-print *";
			_print += "    {";
			_print += "       display: none !important;";
			_print += "    }";
			_print += "}";
			_print += "</style>";
			let _btnprint = "<img class='no-print' src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='window.print();'></img><br />"; 
			var w = window.open();
			$(w.document.body).html(_print+_html+_btnprint);
		}); // fin _AJAX.UiGetCuentaCorriente
	}, // fin onGetResumenDeDeuda
	onArmarPlanDePago: function (_this) {
		let id_cliente = $('.btn-abm-accept').attr('data-id');
		if (id_cliente==null || id_cliente=='undefined'){id_cliente=$('#contenidoGenerico').attr('data-id');}
		let clienteIntento2 = $("div#masData div#dataListaDePrecios").attr("data-idcliente");
		if ((id_cliente=='undefined' || id_cliente==null)&& clienteIntento2!='null' &&clienteIntento2!='undefined'){id_cliente = clienteIntento2;}
		data = {id_cliente: id_cliente};
		_AJAX.UiGetCuentaCorriente(data).then(function (datajson) {
			let impago = datajson.cuentaCorrienteImpago;
			let totalItems=-1;
			let deudaTotal=parseFloat(0.00);
			_html = "<div id='planDePago'>";
			_html += 	"<table>";
			_html +=	 	"<thead>";
			_html += 			"<tr>";
			_html +=				"<th></th>";	
			_html +=				"<th>Fecha emisión</th>";
			_html +=				"<th>Fecha vencimiento</th>";
			_html +=				"<th>Comprobante</th>";
			_html +=				"<th>Importe</th>";
			_html +=				"<th>Saldo</th>";
			_html +=				"<th>Parcela</th>";
			_html +=			"</tr>";
			_html +=		"</thead>";
			_html +=	 	"<tbody>";
			$.each(impago, function (index, valueItem) {
				deudaTotal = parseFloat(deudaTotal) + parseFloat(valueItem.Saldo);
				totalItems = index+1; // index es zero based
				_html += 			"<tr>";
				_html +=				"<td><input type='checkbox' class='deudaParaPlan' id='idcc_"+ valueItem.ID_CuentaCorriente +"' value='idcc_"+ valueItem.ID_CuentaCorriente +"' data-id='"+ valueItem.ID_CuentaCorriente +"' data-saldo='"+ valueItem.Saldo + "' ></td>";	
				_html +=				"<td>" + _TOOLS.formatDDMMYYYY(valueItem.Fecha_Emision, "/") + "</td>";
				_html +=				"<td>" + _TOOLS.formatDDMMYYYY(valueItem.Fecha_Vencimiento, "/") + "</td>";
				_html +=				"<td>" + valueItem.comprobante + "</td>";
				_html +=				"<td>"+valueItem.Importe+"</td>";
				_html +=				"<td>"+valueItem.Saldo+"</td>";
				_html +=				"<td>"+valueItem.parcelaReducido+"</td>";
				_html +=			"</tr>";				
			});		
			_html +=		"</tbody>";
			_html +=	"</table>";			
			_html += "<div># Items: " + totalItems + "</div>"; 
			_html += "<div> Deuda Total: " + deudaTotal + "</div>"; 
			_html += "</div>";
			let _htmlModal = '<!-- Modal --> \
			<div class="modal fade" id="myModalGenerica" role="dialog"  style="z-index:9999;"> \
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="width: 1000px; max-width: 100%;"> \
			\
				<!-- Modal content-->\
				<div class="modal-content custom-modal-big">\
				<div class="modal-header">\
					<h4 class="modal-title">TITULO</h4>\
					<button type="button" class="close" data-dismiss="modal">&times;</button>\
				</div>\
				<div id="myModalGenericaContenido" class="modal-body modal-xl">\
					<p>Some text in the modal.</p>\
				</div>\
				<div class="modal-footer modal-xl">\
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>\
				</div>\
				</div>\
				\
			</div>\
			<div id="myModal2"></div>\
			</div>';
			_htmlModal = _htmlModal.replace(/TITULO/gi, "Generar Plan de Pagos");
			_fullHtml = "<div class='row'>" +
							"<div class='col'>" +
								_html + 
							"</div>"+
							"<div class='col'>" +
								"<div class='row'>" +
									"<span>Financia deuda nueva: <div id='deudaNueva'><input class='financiacion' type='radio' name='financia' checked id='financia' value='F'></div><div id='deudaNuevaObs'></div></span>" + 
								"</div>" +
								"<div class='row'>" +
									"<span>Refinancia Deuda Anterior: <div id='deudaAnterior'><input class='financiacion' type='radio' name='financia' id='refinancia' value='R'></div><div id='deudaAnteriorObs'></div></span>" +
								"</div>" +
								"<div class='row'>" +
									"<span>Items de deuda a incluir: <div id='itemsDeuda'></div><div id='itemsCtaCte'></div></span>" +
								"</div>" +
								"<div class='row'>" +
									"<span>Total de deuda a incluir: <div id='totalDeuda'></div><div id='totalDeudaObs'></div></span>" +
								"</div>" +
								"<div class='row'>" +
									"<span>Total del Plan: <div><input type='number' id='totalPlan'></div><div id='totalPlanObs'></div></span>" +
								"</div>" +
								"<div class='row'>" +
									"<span>Cuotas del Plan: </span>"+
									"<select id='selCantCuotas'>" +
									"  <option selected='true' disabled='disabled'>seleccione</option>" +
									"  <option value='1'>x1</option>" +
									"  <option value='2'>x2</option>" +
									"  <option value='3'>x3</option>" +
									"  <option value='4'>x4</option>" +
									"  <option value='5'>x5</option>" +
									"  <option value='6'>x6</option>" +
									"  <option value='7'>x7</option>" +
									"  <option value='8'>x8</option>" +
									"  <option value='9'>x9</option>" +
									"  <option value='10'>x10</option>" +
									"  <option value='11'>x11</option>" +
									"  <option value='12'>x12</option>" +
									"  <option value='13'>x13</option>" +
									"  <option value='14'>x14</option>" +
									"  <option value='15'>x15</option>" +
									"  <option value='16'>x16</option>" +
									"  <option value='17'>x17</option>" +
									"  <option value='18'>x18</option>" +
									"  <option value='19'>x19</option>" +
									"  <option value='20'>x20</option>" +
									"  <option value='21'>x21</option>" +
									"  <option value='22'>x22</option>" +
									"  <option value='23'>x23</option>" +
									"  <option value='24'>x24</option>" +
									"  <option value='25'>x25</option>" +
									"  <option value='26'>x26</option>" +
									"  <option value='27'>x27</option>" +
									"  <option value='28'>x28</option>" +
									"  <option value='29'>x29</option>" +
									"  <option value='30'>x30</option>" +
									"  <option value='31'>x31</option>" +
									"  <option value='32'>x32</option>" +
									"  <option value='33'>x33</option>" +
									"  <option value='34'>x34</option>" +
									"  <option value='35'>x35</option>" +
									"  <option value='36'>x36</option>" +
									"  <option value='37'>x37</option>" +
									"  <option value='38'>x38</option>" +
									"  <option value='39'>x39</option>" +
									"  <option value='40'>x40</option>" +
									"  <option value='41'>x41</option>" +
									"  <option value='42'>x42</option>" +
									"  <option value='43'>x43</option>" +
									"  <option value='44'>x44</option>" +
									"  <option value='45'>x45</option>" +
									"  <option value='46'>x46</option>" +
									"  <option value='47'>x47</option>" +
									"  <option value='48'>x48</option>" +
									"</select>" +  
									"<div id='cuotas'></div><div id='cantCuotas'></div>" +
								"</div>" +
								"<div class='row'>" +
									"<span>Valor Cuota: <div id='valorCuota'></div></span>"+ 
								"</div>"+
								"<div class='row'>" +
									"<input type='button' id='btnGenerarPlan' class='col btn-sm btn-outline-success' value='Generar'>"+
								"</div>"+
							"</div>"+ // col
						"</div>";
			$('#contenidoGenerico').html('');
			$('#contenidoGenerico').html(_htmlModal);
			// Al modal le agrego la tabla
			$('#myModalGenericaContenido').html('');
			$('#myModalGenericaContenido').html("<div class='row'>" + _fullHtml + "</div>");  
			$('#myModalGenerica').modal('hide');
			$('#myModalGenerica').modal('show');
		}); // Ajax UiGetCuentaCorriente
	}, // onUiPlanDePago
	onGenerarPlanDePagos: function(_this){
		let itemsCtaCte = $('#itemsCtaCte').html();
		itemsCtaCte = itemsCtaCte.substring(0, itemsCtaCte.length - 2); // limpiarle el ', ' final
		itemsCtaCte = itemsCtaCte.replace(/, /gi,"|");
		let total_plan = parseFloat($('#totalPlan').val());
		let totalDeuda = parseFloat($('#totalDeuda').html());
		let cuotas_plan = parseInt( $('#cantCuotas').html() );
		let importe_cuota_plan = parseFloat($('#valorCuota').html());
		let diferencia_plan = (parseFloat(total_plan)-parseFloat(totalDeuda)).toFixed(2) ;
		let tipo_plan = $('#deudaNuevaObs').html();
		let id_cliente = $('.btn-abm-accept').attr('data-id');
		if (id_cliente==null || id_cliente=='undefined'){id_cliente=$('#contenidoGenerico').attr('data-id');}
		let clienteIntento2 = $("div#masData div#dataListaDePrecios").attr("data-idcliente");
		if ((id_cliente=='undefined' || id_cliente==null)&& clienteIntento2!='null' &&clienteIntento2!='undefined'){id_cliente = clienteIntento2;}
		total_original_plan = totalDeuda;
		data = {vCuenta_corriente: itemsCtaCte, total_original_plan: total_original_plan, total_plan: total_plan, cuotas_plan: cuotas_plan, importe_cuota_plan: importe_cuota_plan, diferencia_plan: diferencia_plan, tipo_plan: tipo_plan, id_cliente: id_cliente};
		$('#myModalGenerica').modal('hide');
		_AJAX.UiGenerarPlanDePagos(data).then(function (datajson) {
			let vuelta = datajson.rcPlanDePago;
			$('#myModalGenerica').modal('hide');
			alert("data.id_cliente" + data.id_cliente);
			// Limpio la CC
			$('#entradaPagadorRecibo').val('');
			//refresco la grilla para que traiga el nuevo registro.
			_FUNCTIONS.onGetCuentaCorriente( null, "IMPAGA", data.id_cliente, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'true');
		});	
	},
	OnClickBorrarCuentaCorriente: function(_this, opCC) {
		let ID_CuentaCorriente = opCC.idCuentaCorriente;
		data = {idCuentaCorriente: ID_CuentaCorriente};		
		let flag = 0;
		_AJAX.UiBorrarCuentaCorriente(data).then(function (datajson) {
			let respuesta = datajson.borradoCC[0]; // viene un array, lo paso a escalar
			if (respuesta.error == 0 && respuesta.registros > 0) {
				_this.remove();  // quito la row de la tabla
			} else if (respuesta.error == 0 && respuesta.registros == 0) {
				alert("Para el movimiento " + respuesta.id + " no habia registro alguno para borrar."); //  ----->	ver de cerrar modal o algo.				
			} else if (respuesta.error != 0) {
				alert("No fu posible borrar el movimiento " + respuesta.id); //  ----->	ver de cerrar modal o algo.				
			}
		});
	},	 
	OnClickModificarCuentaCorriente: function(_this, opCC) {
		let ID_CuentaCorriente = opCC.idCuentaCorriente;  /////////// Agregar el resto de los datos
		data = {ID_CuentaCorriente: ID_CuentaCorriente};		
		let _htmlModal2 = '<!-- Modal --> \
		<div class="modal fade xxx" id="myModalGenerica2" data-backdrop="static" role="dialog" style="z-index:99999;"> \
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"  \
		\
			<!-- Modal content-->\
			<div class="modal-content">\
			<div class="modal-header">\
				<h4 class="modal-title">Modificacion CC</h4>\
				<button type="button" class="close" data-dismiss="modal">&times;</button>\
			</div>\
			<div id="myModalGenericaContenido2" class="modal-body modal-lg">\
				|ITEM|\
			</div>\
			<div class="modal-footer modal-xl">\
				<button type="button" class="btn btn-danger closeMyModalGenerica2" data-dismiss="modal">Cerrar</button>\
				<button type="button" class="btn btn-primary saveMyModalGenerica2" onclick="_FUNCTIONS.setModificacionCuentaCorriente();">Guardar</button>\
			</div>\
			</div>\
			\
		</div>\
		</div>\
		<div id="masData2"></div>';
		let _htmlItemCC = "Emision, Venciomiento, Concepto, Importe, Saldo";
		let respuesta;
		_AJAX.UiGetItemCuentaCorriente(data).then(function (datajson) {
			respuesta = datajson;
			let itemCuentaCorriente =datajson.itemCuentaCorriente[0];
			let conceptosListaPrecios =datajson.listaPrecios; 
			if (datajson.status==="OK") {
				let item = "";
				let conceptos = "";
				conceptos = "<select id=\"conceptos_mod\" name=\"conceptos_mod\">";
				conceptosListaPrecios.forEach(element => {
											let _selected = ( itemCuentaCorriente.id_ConceptoListaPrecio == element.id_ConceptoListaPrecio ? "selected" : "" );
											conceptos +=    "<option value=\"" + element.id_ConceptoListaPrecio + "\" " + _selected + " >" + 
																element.Operacion + 
															"</option>";
										});
				conceptos+="</select>";
				item = "";
				item += "<div id=\"ID_CuentaCorriente_mod\">ID: " + itemCuentaCorriente.ID_CuentaCorriente + 
								"<input id=\"ID_CuentaCorrienteModInp\" name=\"ID_CuentaCorrienteModInp\" type=\"hidden\" value=\""+ itemCuentaCorriente.ID_CuentaCorriente +"\">"+
						"</div>";
				item += "<div id=\"concepto_mod\">concepto: " + conceptos + //select conceptos
								"<input id=\"conceptoModInp\" name=\"conceptoModInp\" type=\"hidden\" value=\""+ itemCuentaCorriente.id_ConceptoListaPrecio +"\">"+
						"</div>";
				item += "<div>" + " <label for=\"Fecha_Emision\">Fecha Emision: </label> " + 
										"<input type=\"date\" id=\"Fecha_Emision_mod\" name=\"Fecha_Emision\" value=\"" + 
											_TOOLS.formatYYYYMMDD(itemCuentaCorriente.Fecha_Emision) +"\" required >" + 
						"</div>";
				item += "<div>" + " <label for=\"Fecha_Vencimiento\">Fecha Vencimiento: </label> " + 
										"<input type=\"date\" id=\"Fecha_Vencimiento_mod\" name=\"Fecha_Vencimiento\" value=\"" + 
											_TOOLS.formatYYYYMMDD(itemCuentaCorriente.Fecha_Vencimiento) +"\" required >" + 
						"</div>";
				item += "<div>" + " <label for=\"Importe\">Importe: </label> " + 
										"<input type=\"number\" step=\"0.01\" id=\"Importe_mod\" name=\"Importe\" value=\"" + 
											itemCuentaCorriente.Importe +"\" required >"  + 
						"</div>";
				item += "<div>" + " <label for=\"Saldo\">Saldo: </label> " + 
										"<input type=\"number\" step=\"0.01\" id=\"Saldo_mod\" name=\"Saldo\" value=\"" + 
										itemCuentaCorriente.Saldo +"\" required >"  + 
						"</div>";
				_htmlModal2 = _htmlModal2.replace("|ITEM|", item);
				$('#myModal2').html(_htmlModal2);
				$('#myModalGenerica2').modal("show");
			}
		});
		let final2 = "<div class='container'>"+
						"<div class='row'>"+
							"<input type='button' id='btnNuevoComprobante' class='col btn-sm btn-outline-success botonInsCC botonCC' value='Nuevo'>"+
							"<input type='button' id='btnCuentaCorriente' class='col btn-sm btn-outline-success' value='Cta.Cte.'></input>"+
							"<input type='button' id='btnCuentaCorrienteImpaga' class='col btn-sm btn-outline-success' value='Impago'></input>"+
							"<input type='button' id='btnResumenDeuda' class='col btn-sm btn-outline-success' value='Resumen Deuda'></input>"+
							"<input type='button' id='btnPlanPago' class='col btn-sm btn-outline-success' value='Plan Pagos'></input>"+													
						"</div>" +
						"<div class='row'>" + 
							"<div id='tablaEdicion' class='d-none'></div>"+
							"<div id='tablaAlta' class='d-none'></div>"+
						"</div>"+
					"</div>";	
	},
	setModificacionCuentaCorriente: function() {
		let id = $('#ID_CuentaCorrienteModInp').val(); 
		let concepto = $('#conceptoModInp').val(); // hay que ver de tomar un valor aunque no haya sido modificacion el select.
		let emision = $('#Fecha_Emision_mod').val();
		let venc = $('#Fecha_Vencimiento_mod').val();
		let imp = $('#Importe_mod').val();
		let saldo = $('#Saldo_mod').val();
		let descconc = $('XXXXXXXXXX').val(); // cambiar
		paramCC = {idCuentaCorriente: id, 
						id_ConceptoListaPrecio_mcc: concepto, 
						fecha_emision_mcc: emision, 
						fecha_vencimiento_mcc: venc, 
						importe_mcc: imp, 
						saldo_mcc: saldo,
						descr_concepto: "WWWWWW", 
					};
		_FUNCTIONS.OnGuardarModificacionCuentaCorriente(null, paramCC);
	},
	OnGuardarModificacionCuentaCorriente: function(_this, paramCC) {
		_AJAX.UiModificarCuentaCorriente(paramCC).then(function (datajson) {
			respuesta = datajson;
			if (datajson.status==="OK") {
				$('#myModalGenerica2').modal("hide");
				let buscado = $("table#tablaCC").find("tr[data-id='" + paramCC.idCuentaCorriente + "']");
				buscado.eq(2).html(paramCC.fecha_emision_mcc);
				buscado.eq(3).html(paramCC.fecha_vencimiento_mcc);
				buscado.eq(4).html(paramCC.importe_mcc);
				buscado.eq(5).html(paramCC.saldo_mcc);
				buscado.eq(6).html(paramCC.id_ConceptoListaPrecio);
				buscado.eq(8).html(paramCC.descr_concepto);
				$("table#tablaCC tbody tr").each(function(index){
					if($(this).attr('data-id')==paramCC.idCuentaCorriente) {
						$(this).eq(2).html(paramCC.fecha_emision_mcc);
						$(this).eq(3).html(paramCC.fecha_vencimiento_mcc);
						$(this).eq(4).html(paramCC.importe_mcc);
						$(this).eq(5).html(paramCC.saldo_mcc);
						$(this).eq(6).html(paramCC.id_ConceptoListaPrecio);
						$(this).eq(8).html(paramCC.descr_concepto);
					}
				});
				alert("Modificacion al Item de CC guardado correctamente. ID: " + paramCC.idCuentaCorriente);
				$("#btnCuentaCorriente").click();  // hay que hacer andar el refresh solo del la row en cuestion, pero no esta andando...
			} else {
				$('#myModalGenerica2').modal("hide");
				alert("Error al tratar de guardar los datos del Item de CC ID:" + paramCC.idCuentaCorriente);
			}
		});
	},
	nullToEmpty:function (valor) {
		if (valor == null) { return ""; }
		if (valor == "null") { return ""; }
		if (typeof valor === "undefined") { return ""; }
		return valor;
	},
	ImprimirOrdenPago_CEM: function (val_id)
	{
		var myJSON = '{"VAL_ID":"' + val_id + '"}';
		var myObj = JSON.parse(myJSON);
		_AJAX.UiGetOrdenDePago(myObj).then(function (datajson) {
			var _html = "";
			_html += "<style>		";
			_html += "@media print";
			_html += "{    ";
			_html += "    .no-print, .no-print *";
			_html += "    {";
			_html += "       display: none !important;";
			_html += "    }";
			_html += "}";
			_html += "</style>";
			var _formattedDate = "";
			var _formattedDate1 = "";
			_html += "<div style='position:absolute;left:5mm;top:10mm;width:210mm;'>";
			/*cabecera*/
			_html += "<table width='100%' cellpadding='2' border='0'>";
			_html += "<tr><td align='LEFT' colspan='2'><b>Asociación Civil Corporación del Cementerio Británico de Bs. As.</b></td></tr>";
			_formattedDate = _FUNCTIONS.nullToEmpty(datajson.op[0].Fecha_OP);
			if (_formattedDate != "") {  _formattedDate = _TOOLS.getTextAsFormattedDate(_formattedDate, "dmy", "/"); } else { _formattedDate = ""; }
			if (datajson.op[0].Codigo_Comprobante=='OP'){
				_html += "<tr><td align='LEFT'><b> Sres. "+datajson.op[0].Razon_Social +"</b></td><td align='right'><b>Bs.As. &nbsp;</b>"+_formattedDate+"</td></tr> ";
				_html += "<tr><td align='right' colspan='2'><b>Orden de pago Nº</b> "+datajson.op[0].Nro_OP+"</td></tr>";
			}
			else
			{
				_html += "<tr><td align='LEFT'><b> </b></td><td align='right'><b>Bs.As.</b>"+_formattedDate+"</td></tr> ";
				_html += "<tr><td align='right' colspan='2'><b> Orden de pago interna Nº</b> "+datajson.op[0].Nro_OP+"</td></tr>";
			}
			_html += "</table>";
			_html += "<br>";
			_html += "<table cellpadding='2' border='0' width='100%'>";
			_html += "<tr>";
			_html += "<td style='border:solid 1px black;'><b>Pagos de los comprobantes detallados</b></td>";
			_html += "<td style='border:solid 1px black;'><b>Valores entregados en forma de pago</b></td>";
			_html += "</tr>";
			_html += "<tr>";
			_html += "  <td style='border:solid 1px black;'>";
			_html += "    <table width='100%'>";
			_html += "      <tr>";
			_html += "        <td align='left'>Detalle</td>";
			_html += "        <td align='right'>Importe</td>";
			_html += "      </tr>";
			var _total = 0
			var _importe = 0;
			$.each(datajson.ropic, function (j, val1) {
				_importe = Number(val1.Importe);
				_total += _importe;
				_html += "      <tr>";
				_html += "        <td align='left'>" + val1.cuenta + "</td>";
				_html += "        <td align='right'>" + _TOOLS.showNumber(_importe, 2, "", "0") + "</td>";
				_html += "      </tr>";
			});
			_html += "      <tr>";
			_html += "        <td align='left' style='border-top:solid 1px black;'><b>Total</b></td>";
			_html += "        <td align='right' style='border-top:solid 1px black;'>" + _TOOLS.showNumber(_total, 2, "", "0")  + "</td>";
			_html += "      </tr>";
			_html += "    </table>";
			_html += "  </td>";
			_html += "  <td style='border:solid 1px black;'>";
			_html += "    <table width='100%'>";
			_html += "      <tr>";
			_html += "        <td align='left'>Tipo</td>";
			_html += "        <td align='left'>Número</td>";
			_html += "        <td align='left'>Banco</td>";
			_html += "        <td align='left'>Fecha</td>";
			_html += "        <td align='right'>Importe</td>";
			_html += "      </tr>";
			_total = 0
			$.each(datajson.ropvalor, function (j, val1) {
				_importe = Number(val1.importe_cheque);
				_formattedDate1 = _FUNCTIONS.nullToEmpty(val1.fecha_cheque);
				if (_formattedDate1 != "") {  _formattedDate1 = _TOOLS.getTextAsFormattedDate(_formattedDate1, "dmy", "/"); } else { _formattedDate1 = ""; }
				_total += _importe
				_html += "      <tr>";
				_html += "        <td align='left'>" + val1.Desc_Valores + "</td>";
				_html += "        <td align='left'>" + _FUNCTIONS.nullToEmpty(val1.Nro_cheque) + "</td>";
				_html += "        <td align='left'>" + _FUNCTIONS.nullToEmpty(val1.Desc_Bancos) + "</td>";
				_html += "        <td align='left'>" + _formattedDate1 + "</td>";
				_html += "        <td align='right'>" + _TOOLS.showNumber(_importe, 2, "", "0") + "</td>";
				_html += "      </tr>";
			});
			_html += "      <tr>";
			_html += "        <td align='left' style='border-top:solid 1px black;'></td>";
			_html += "        <td align='left' style='border-top:solid 1px black;'></td>";
			_html += "        <td align='left' style='border-top:solid 1px black;'></td>";
			_html += "        <td align='left' style='border-top:solid 1px black;'><b>Total</b></td>";
			_html += "        <td align='right' style='border-top:solid 1px black;'>" + _TOOLS.showNumber(_total, 2, "", "0") + "</td>";
			_html += "      </tr>";
			_html += "    </table>";
			_html += "  </td>";
			_html += "</tr>";
			_html += "</table>";
			_html += "<table width='100%' cellpadding='2' border='0'>";
			_html += "<tr><td align='left'><b>Recibí conforme</b></td></tr>";
			_html += "</table>";
			_html += "<br>";
			_html += "<br>";
			_html += "<br>";
			_html += "<br>";
			_html += "<table width='100%' cellpadding='2' border='0'>";
			_html += "<tr><td align='right'><b>_______________________</b></td><td align='right'><b>_______________________</b></td><td align='right'><b>_______________________</b></td></tr>";
			_html += "<tr><td align='right'><b>         Firma         </b></td><td align='right'><b>         Acaración         </b></td><td align='right'><b>         Documento         </b></td></tr>";
			_html += "</table>";
			_html += "<br>";
			_html += "<table width='100%' cellpadding='2' border='0'>";
			_html += "<tr><td align='LEFT'></td></tr>";
			_html += "</table>";
			_html += "<hr>";
			_html +="</div>";
			var win = window.open("", "Reporte", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1024,height=768,top=" + (screen.height - 0) + ",left=" + (screen.width - 0));
			win.document.body.innerHTML = "<html><title>Orden de Pago Interna</title><body><img class='no-print' src='" + _AJAX._here + "/assets/img/print.jpg' style='height:35px;' onclick='window.print();'></img><br /> " + _html + "</body></html>";
		});
	},
	OnClickInsertarCuentaCorriente: function(_this, opCC) {
		data = {ID_EmpresaSucursal: opCC.ID_EmpresaSucursal, id_cliente: opCC.id_cliente, id_Parcela: opCC.id_Parcela, id_tipo_comprobante: opCC.id_tipo_comprobante, items: opCC.items, fecha_alta: opCC.fecha_alta, ajuste: opCC.ajuste};	
		_AJAX.UiInsertarCuentaCorriente(data).then(function (datajson) {
			if (datajson.status == "OK") {
				alert("Movimiento CC guardados con exito.");
				$('#entradaPagadorRecibo').val('');
				_FUNCTIONS.onGetCuentaCorriente(null, "IMPAGA", opCC.id_cliente, 'CC desde ReC', '#div_cuenta_corriente', 'true', 'true');
			} else {
				alert("Error al guardar Movimientos CC.");				
			}
		});
	},	 	
	OnClickGetHistoricoPagador: function(_this, parametros) {
		data = parametros;	
		_AJAX.UiGetHistoricoPagador(data).then(function (datajson) {
			let respuesta = datajson.historicoPagador;
			$('#myModalHistorico').modal('toggle'); // muestro la modal, pero en la ventana de pagadores
			let contenido =    "<table width='100%'>";
			contenido +=    "      <tr>";
			contenido +=    "        <td align='right'><b>Nºpagador original</b></td>";
			contenido +=    "        <td align='center'><b>Fecha histórico</b></td>";
			contenido +=    "        <td><b>Nombre</b></td>";
			contenido +=    "        <td><b>Teléfono</b></td>";
			contenido +=    "        <td align='right'><b>Nºdocumento</b></td>";
			contenido +=    "      </tr>";
			let NumeroPagador="";
			respuesta.forEach(element => {
				if (NumeroPagador==""){NumeroPagador = element.NumeroPagador;}
				contenido +=    "      <tr>";
				contenido +=    "        <td align='right'>" + element.NumeroPagador + "</td>";
				contenido +=    "        <td align='center'>" + _TOOLS.formatDDMMYYYY(element.fecha_historico, "/") + "</td>";
				contenido +=    "        <td>" + element.Nombre + "</td>";
				contenido +=    "        <td>" + element.Telefono1 + "</td>";
				contenido +=    "        <td align='right'>" + element.NumeroDocumento + "</td>";
				contenido +=    "      </tr>";
			});
			contenido +=    "</table>";
			contenido = "<h2>Registro histórico del pagador Nº" + NumeroPagador + " </h2>" + contenido;
			$('#titulo').html('Registro Historico');
			$('#modalContenido').html(contenido);
		});
	},	 	
	OnClickTransferirPagador: function(_this, parametros) {
		let id_pagador = $('.btn-abm-accept').attr('data-id');
		data = {id_pagador: id_pagador};			
		_AJAX.UiTransferirPagador(data).then(function (datajson) {
			let respuesta = datajson.transferenciaPagador[0];
			if (respuesta.error != 0) { alert("Ocurrio un error al intentar hacer la transferencia"); }
		});
	},	 		
	OnClickTransferirTitularidad: function(_this, parametros) {
		let id_cliente = $('.btn-abm-accept').attr('data-id');
		data = {id_cliente: id_cliente};	
		_AJAX.UiTransferirTitularidad(data).then(function (datajson) {
			let respuesta = datajson.transferenciaTitularidad[0]; // tomo el primero para no ciclar, pero deberia venir un array de 1 elemento porque es un insert
			if (respuesta.error != 0) { alert("Ocurrio un error al intentar hacer la transferencia"); }
		});
	},	 		
	OnGetEstadoDesenganche: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetEstadoDesenganche(dataInput).then(function (respuestaJson) {
			let respuesta = respuestaJson.desenganche[0]; // tomo el primero para no ciclar
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.desenganche.length>0) {
					alert("El estado de desenganche de la parcela es: " + respuestaJson.desenganche[0].estado);
					if ($('#id_cliente').attr('data-id')!=0 && $('#id_cliente').attr('data-id')!=null && $('#id_cliente').attr('data-id')!="") {
						alert("con cliente");
						$('.btnA').hide();
						$('.btnB').hide();
						$('.btnX').show();
						switch (respuestaJson.desenganche[0].estado)
						{ 
							case "A1":
								$('#A_2').show();
								break;
							case "A2":
								$('#A_3').show();
								break;
							case "A3":
								$('#A_4').show();
								break;
							case "A4":
								$('#A_5').show();
								break;
							case "B1":
								$('#B_5').show();
								break;
							default:
								$('#A_1').show();
								$('#B_1').show();
								$('.btnX').hide();
								break;
						}
					} else {
						alert("sin cliente");
						$('.btnA').hide();
						$('.btnB').hide();
						$('.btnX').hide();
					}
				} else {
					if (!($('#id_cliente').attr('data-id')!=0 && $('#id_cliente').attr('data-id')!=null && $('#id_cliente').attr('data-id')!="")) {
						$('.btnA').hide();
						$('.btnB').hide();
						$('.btnX').hide();
					}
				}			
			} else {
				alert("Ocurrio un error al obtener el estado del desenganche");				
			}
		});
	},	 	
	OnSetearDesengancheParcela: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiSetearDesengancheParcela(dataInput).then(function (respuestaJson) {
			// chequeo errores
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.resultadoI == true || respuestaJson.resultadoD == true || respuestaJson.resultadoCC == true) {
					alert("Registro del desenganche exitoso");
				} else {
					alert("Algo sucedio al registrar el desenganche");
				}
			} else {
				alert("Ocurrio un error al obtener el estado del desenganche");				
			}
		});
	},	
	OnGetAnalisisGestion: function(_this, ) {
		dataInput = {};	
		_AJAX.UiGetAnalisisGestion(dataInput).then(function (respuestaJson) {
			// chequeo errores
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.analisisGestionUniverso.length>0) {
					_html ="<hr><br>";
					_html+="<table width='100%' cellspacing='0' cellpadding='4'>";
					_html+="<tr bgcolor='silver'>";
					_html+="  <td><b>Universo</b></td>";
					_html+="  <td align='right'><b>Disponible</b></td>";
					_html+="  <td align='right'><b>No disponible<br/>(inc.morosos)</b></td>";
					_html+="  <td align='right'><b>Normal</b></td>";
					_html+="  <td align='right'><b>Camino</b></td>";
					_html+="  <td align='right'><b>Jardín</b></td>";
					_html+="  <td align='right'><b>Totales</b></td>";
					_html+="</tr>";
					$.each(respuestaJson.analisisGestionUniverso, function (index, itemValue) {
						_html+="<tr>";
						_html+="  <td align='right'>"+itemValue.Nombre+"</td>";
						_html+="  <td align='right'>"+itemValue.Disponible+"</td>";
						_html+="  <td align='right'>"+itemValue.NoDisponible+"</td>";
						_html+="  <td align='right'>"+itemValue.Normal+"</td>";
						_html+="  <td align='right'>"+itemValue.Camino+"</td>";
						_html+="  <td align='right'>"+itemValue.Jardin+"</td>";
						_html+="  <td align='right'>"+itemValue.Total+"</td>";						
						_html+="</tr>";
						
					});
					_html+="</table>";
					$('#agregado').html('');
					$('#agregado').append(_html);
				} else {
					alert("No hay datos para el reporte de Analisis de Gestion - Universo");
				}			
				if (respuestaJson.analisisGestionMorosos.length>0) {
					_html ="<hr><br>";
					_html+="<table width='100%' cellspacing='0' cellpadding='4'>";
					_html+="<tr bgcolor='silver'>";
					_html+="  <td><b>Morosos</b></td>";
					_html+="  <td align='right'><b>Tramo 1</b></td>";
					_html+="  <td align='right'><b>Clientes 1</b></td>";
					_html+="  <td align='right'><b>Tramo 2</b></td>";
					_html+="  <td align='right'><b>Clientes 2</b></td>";
					_html+="  <td align='right'><b>Tramo 3</b></td>";
					_html+="  <td align='right'><b>Clientes 3</b></td>";
					_html+="  <td align='right'><b>Tramo 4</b></td>";
					_html+="  <td align='right'><b>Clientes 4</b></td>";
					_html+="  <td align='right'><b>Total Gral.</b></td>";
					_html+="</tr>";
					$.each(respuestaJson.analisisGestionMorosos, function (index, itemValue) {
						_html+="<tr>";
						_html+="  <td align='right'>"+itemValue.Nombre+"</td>";
						_html+="  <td align='right'>"+itemValue.Tramo1+"</td>";
						_html+="  <td align='right'>"+itemValue.Clientes1+"</td>";
						_html+="  <td align='right'>"+itemValue.Tramo2+"</td>";
						_html+="  <td align='right'>"+itemValue.Clientes2+"</td>";
						_html+="  <td align='right'>"+itemValue.Tramo3+"</td>";
						_html+="  <td align='right'>"+itemValue.Clientes3+"</td>";
						_html+="  <td align='right'>"+itemValue.Tramo4+"</td>";
						_html+="  <td align='right'>"+itemValue.Clientes4+"</td>";						
						_html+="  <td align='right'>"+itemValue.TramoTotal+"</td>";
						_html+="</tr>";
					});
					_html+="</table>";
					$('#agregado').append(_html);
				} else {
					alert("No hay datos para el reporte de Analisis de Gestion - Morosos");
				}			
			} else {
				alert("Ocurrio un error al obtener el estado del desenganche");				
			}
		});
	},		
	OnGetGestionCobranza: function(_this, param) {
		dataInput = param;	
		_AJAX.UiGetGestionCobranza(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.gestionCobranzaTotal.length>0) {
					_html ="<hr><br>";
					_html+="<div class='container'>";
					$.each(respuestaJson.gestionCobranzaTotal, function (index, itemValue) {
						_html+="<div class='row' align='left'>"+"<b>Gestión de cobranza de deuda: " + itemValue.descripcion + "</b></div>"; 
						_html+="<div class='row'  align='left'><b>"+itemValue.ClientesMorosos + "</b>&nbsp clientes en el segmento seleccionado con &nbsp<b>" + itemValue.ClientesMorososGestionados+ "</b>&nbsp clientes con gestión activa, por un total de &nbsp<b>$" + parseFloat(itemValue.ImporteMoroso).toFixed(2) +"</b>.-</div>";
					});
					$('#REPORT-CONTAINER').html('');
					$('#REPORT-CONTAINER').append(_html);
				} else {
					alert("No hay datos para el reporte de Gestion de Cobranza - Total");
				}			
				if (respuestaJson.gestionCobranzaCabecera.length>0) {
					_html ="<br>";
					_html+="<table class='table table-striped' width='100%' cellspacing='0' cellpadding='4'>";
					_html+="<tr bgcolor='silver'>";
					_html+="  <td align='right'></td>";
					_html+="  <td align='right'><b>#</b></td>";
					_html+="  <td align='right'><b>Nro Cliente</b></td>";
					_html+="  <td align='right'><b>Cliente</b></td>";
					_html+="  <td align='right'><b>Saldo</b></td>";
					_html+="  <td align='right'><b>Telefono</b></td>";
					_html+="  <td align='right'></td>";
					_html+="  <td align='right'><b>Nota</b></td>";
					_html+="  <td align='right'></td>";										
					_html+="</tr>";
					let numeroclienteActual = "";
					let i=0;
					$.each(respuestaJson.gestionCobranzaCabecera, function (index, itemValue) {
						if (itemValue.numerocliente != numeroclienteActual) {
							i++;
							numeroclienteActual = itemValue.numerocliente;
						} else {
							return true; // true -> continue; false -> break
						}
						_html+="<tr>";
						$htmlfinan = "<img src='./assets/img/media/imagenes/message.png' style='cursor:hand;' title='Con financiación!'/>";
						$htmlrefinan = "<img src='./assets/img/media/imagenes/message.png' style='cursor:hand;' title='Con refinanciación!'/>";
						_html+="  <td align='right'>";
						if (itemValue.financiacion != 0) {
							_html+=$htmlfinan;
						} else if(itemValue.refinanciacion != 0){
							_html+=$htmlrefinan;
						}
						_html+="  </td>";
						_html+="  <td align='right'>"+ i +"° </td>";
						_html+="  <td align='right'>"+itemValue.numerocliente+"</td>";
						_html+="  <td align='right'>"+itemValue.cliente+"</td>";
						_html+="  <td align='right'>"+parseFloat(itemValue.saldo_mcc).toFixed(2)+"</td>";
						_html+="  <td align='right'>"+itemValue.telefono1+"</td>";
						_html+="  <td align='right'>"+itemValue.telefono2+"</td>";
						_html+="  <td align='right'>"+itemValue.ultima_nota+"</td>";
						_html+="  <td align='right'>"+"<input type='button' class='button' value='C.C.' onclick='javascript:VerDetallesDeuda_CEM(" + itemValue.id_cliente + ", \""+itemValue.numerocliente+"-"+itemValue.cliente+"\");'</td>";					
						_html+="</tr>";
					});
					_html+="</table>";
					$('#REPORT-CONTAINER').append(_html);
				} else {
					alert("No hay datos para el reporte de Gestion de Cobranza - Cabecera");
				}			
			} else {
				alert("Ocurrio un error al obtener el estado del reporte");				
			}
		});
	},		
	OnGetActividadEnNotas: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetActividadEnNotas(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.actividadEnNotas.length>0) {
					_html ="<hr><br>";
					_html+="<div class='text-center'><h4>Actividad en Notas</h4></div>";
					_html+="<table >"; //class='table table-striped'
					_html+="	<thead>";
					_html+="		<tr>";
					_html+="			<td><b>Tipo</b></td>";								
					_html+="			<td><b>Fecha</b></td>";										
					_html+="			<td><b>Nota</b></td>";										
					_html+="			<td><b>Numero Cliente</b></td>";
					_html+="			<td><b>Razon Social</b></td>";					
					_html+="		</tr>";										
					_html+="	</thead>";
					_html+="	<tbody>";	
					let usuario_anterior="";				
					$.each(respuestaJson.actividadEnNotas, function (index, itemValue) {
						_html+="<tr>";
						if (usuario_anterior!=itemValue.id_usuario+"-"+itemValue.usuario) {
							usuario_anterior=itemValue.id_usuario+"-"+itemValue.usuario;
							_html+="<tr class='table-success'>";
							_html+="<td colspan='5'>" + itemValue.id_usuario+"-"+itemValue.usuario + "</td>";	
							_html+="</tr>";
						}
						_html+="<tr>";
						_html+="<td>" + itemValue.tipo_nota + "</td>";
						_html+="<td>" + itemValue.fecha_alta + "</td>";
						_html+="<td>" + itemValue.nota + "</td>";
						_html+="<td>" + itemValue.NumeroCliente + "</td>";
						_html+="<td>" + itemValue.RazonSocial + "</td>";
						_html+="</tr>";						
					});
					_html+="	</tbody>";
					_html+="	</table>";										
					$('#REPORT-CONTAINER').html('');
					$('#REPORT-CONTAINER').append(_html);
				} else {
					alert("No hay datos para el reporte de Actividad en Notas");
				}			

			} else {
				alert("Ocurrio un error al obtener el estado del reporte");				
			}
		});
	},			
	OnGetReporteEstadisticaCobroPorAvisos: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetReporteEstadisticaCobroPorAvisos(dataInput).then(function (respuestaJson) {
			let total = 0.0;
			let cuenta = 0;
			let total_impago=0.0;
			let total_g0=0.0;
			let total_g1=0.0;
			let total_g2=0.0;
			let total_g3=0.0;
			let total_g4=0.0;
			let total_g5=0.0;
			let porc_g0=0.0;
			let porc_g1=0.0;
			let porc_g2=0.0;
			let porc_g3=0.0;
			let porc_g4=0.0;
			let porc_g5=0.0;
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.porAvisosA.length>0) {
					$.each(respuestaJson.porAvisosA, function (index, itemValue) {total_impago+= parseFloat(itemValue.cobrado);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - A");
				}			
				if (respuestaJson.porAvisosB.length>0) {
					$.each(respuestaJson.porAvisosB, function (index, itemValue) {total_g0+= parseFloat(itemValue.cobrado);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - B");
				}						
				if (respuestaJson.porAvisosB.length>0) {
					$.each(respuestaJson.porAvisosC, function (index, itemValue) {total_g1+= parseFloat(itemValue.cobrado);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - C");
				}						
				if (respuestaJson.porAvisosB.length>0) {
					$.each(respuestaJson.porAvisosD, function (index, itemValue) {total_g2+= parseFloat(itemValue.cobrado);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - D");
				}						
				if (respuestaJson.porAvisosE.length>0) {
					$.each(respuestaJson.porAvisosE, function (index, itemValue) {total_g3+= parseFloat(itemValue.cobrado);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - E");
				}						
				if (respuestaJson.porAvisosF.length>0) {
					$.each(respuestaJson.porAvisosF, function (index, itemValue) {total_g4+= parseFloat(itemValue.cobrado);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - F");
				}	
				if (respuestaJson.cantidad.length>0) {
					$.each(respuestaJson.cantidad, function (index, itemValue) {cuenta+= parseInt(itemValue.cantidad);});
				} else {
					alert("No hay datos para el reporte de Estadistica de Cobro - cantidad");
				}						
				total += +total_impago;
				total += +total_g0;
				total += +total_g1;
				total += +total_g2;
				total += +total_g3;
				total += +total_g4;
	
				total_g5 = +total;
				total_g5 -= +total_g0;
				total_g5 -= +total_g1;
				total_g5 -= +total_g2;
				total_g5 -= +total_g3;
				total_g5 -= +total_g4;
	
				porc_g0 = ((+total_g0 / +total) * 100.0);
				porc_g1 = ((+total_g1 / +total) * 100.0);
				porc_g2 = ((+total_g2 / +total) * 100.0);
				porc_g3 = ((+total_g3 / +total) * 100.0);
				porc_g4 = ((+total_g4 / +total) * 100.0);
				porc_g5 = ((+total_g5 / +total) * 100.0);				

				_html = "<div class='container'>";
				_html += "<div class='row'><h3>Por avisos emitidos y deuda generada</h3></div>";
				_html += "<div class='row'><table cellpadding='3' cellspacing='0'>";
				_html += "   <tr>";
				_html += "      <td><b>Cantidad de avisos generados</b></td>";
				_html += "      <td>" + cuenta + ", </td>";
				_html += "      <td><b>por un total de</b></td>";
				_html += "      <td>$ " + total.toFixed(2) + "</td>";
				_html += "   </tr>";
				_html += "</table></div>";
				_html += "</div>";

				_html += "<table width='100%' cellpadding='3' cellspacing='0'>";
				_html += "   <tr bgcolor='silver'>";
				_html += "      <td><b>menos de 0 días</b></td>";
				_html += "      <td><b>de 0 a 60 días</b></td>";
				_html += "      <td><b>de 61 a 90 días</b></td>";
				_html += "      <td><b>de 90 a 120 días</b></td>";
				_html += "      <td><b>más de 120 días</b></td>";
				_html += "      <td><b>sin cobrar</b></td>";
				_html += "   </tr>";
	
				_html += "   <tr bgcolor='ivory'>";
				_html += "      <td>$ " + parseFloat(total_g0).toFixed(2) + "</td>";
				_html += "      <td>$ " + parseFloat(total_g1).toFixed(2) + "</td>";
				_html += "      <td>$ " + parseFloat(total_g2).toFixed(2) + "</td>";
				_html += "      <td>$ " + parseFloat(total_g3).toFixed(2) + "</td>";
				_html += "      <td>$ " + parseFloat(total_g4).toFixed(2) + "</td>";
				_html += "      <td>$ " + parseFloat(total_g5).toFixed(2) + "</td>";
				_html += "   </tr>";
				_html += "   <tr bgcolor='ivory'>";
				_html += "      <td>" + porc_g0.toFixed(2) + " %</td>";
				_html += "      <td>" + porc_g1.toFixed(2) + " %</td>";
				_html += "      <td>" + porc_g2.toFixed(2) + " %</td>";
				_html += "      <td>" + porc_g3.toFixed(2) + " %</td>";
				_html += "      <td>" + porc_g4.toFixed(2) + " %</td>";
				_html += "      <td>" + porc_g5.toFixed(2) + " %</td>";
				_html += "   </tr>";
				_html += "</table>";				

				$('#REPORT-CONTAINER').html('');
				$('#REPORT-CONTAINER').append(_html);
				$('.totalizador').html(parseFloat(total).toFixed(2));
			} else {
				alert("Ocurrio un error al obtener el reporte de Estadistica de Cobro");				
			}
		});
	},			
	OnGetReporteEstadisticaCobroDatosAgrupados: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetReporteEstadisticaCobroDatosAgrupados(dataInput).then(function (respuestaJson) {
			let total = 0.00, parcial=0.00;
			if (respuestaJson.status == 'OK') {
				if (respuestaJson.datosAgrupados.length>0) {
					_html="<table width='100%' cellspacing='0' cellpadding='4'>";
					$.each(respuestaJson.datosAgrupados, function (index, itemValue) {
						if (index==0){
							_html+="   <tr>";
							_html+="      <td width='25'><img src='./assets/img/media/imagenes/ico_mas.gif' onclick=\"javascript:$('.c2').toggle();\" style='cursor:pointer;'/></td>";
							_html+="      <td colspan='2' bgcolor='silver'>" + itemValue.Nombre + " (" + itemValue.listaCodigos + ")</td>";
							_html+="   </tr>";
						}
						if (itemValue.importe!=0) {
							total = +(parseFloat( total).toFixed(2)) + +(parseFloat( itemValue.importe).toFixed(2));
							_html+="   <tr class='c2' style='display:none;'>";
							_html+="      <td width='25'>index: "+index+"</td>";
							_html+="      <td>" + parseFloat(itemValue.importe).toFixed(2) + " - " + itemValue.Nombre+ " CC: " + itemValue.numero + "</td>";
							_html+="      <td align='right'>$ " + parseFloat(itemValue.importe).toFixed(2) + "</td>";
							_html+="   </tr>";
						}						
					});
					_html+="   <tr><td></td><td colspan='2'><hr/></td></tr>";
					_html+="   <tr>";
					_html+="      <td></td>";
					_html+="      <td>Parcial Grupo</td>";
					_html+="      <td align='right' class='parcial-grupo'>$ " + parseFloat(total).toFixed(2) + "</td>";
					_html+="   </tr>";
					_html+="   <tr></tr>"; // separador para el proximo grupo
					_html+="</table>";
					$('#REPORT-CONTAINER').append(_html);
					let totalizador = 0.0;
					totalizador = parseFloat( $('.totalizador').text() ).toFixed(2);
					totalizador = +(parseFloat(totalizador).toFixed(2)) + +(parseFloat(total).toFixed(2));
					$('.totalizador').text( parseFloat(totalizador).toFixed(2)); // aca incremento lo que tiene el grupo, pero a la vez en la pagina acumulo tras los sucesivos llamados
				}			
			} else {
				alert("Ocurrio un error al obtener el reporte de Estadistica de Cobro - Agrupados");				
			}
		});
		// No deberia estar usando la opcion de totalizar como parametro.
		let total_grupos=0.00;
		if (parametros.totalizar="S"){
			$(".parcial-grupo").each(function(){total_grupos += parseFloat($(this).val());})
			$(".totalizador").text(total_grupos);
		}	
	},
	OnGetpos: function (_jsonConfigEntidad, _clave,_eje) {
		switch (_eje) {
			case "x":
			case "X":
				return _jsonConfigEntidad[_clave].x + _jsonConfigEntidad[_clave].unit;
			case "y":
			case "Y":
				return _jsonConfigEntidad[_clave].Y + _jsonConfigEntidad[_clave].unit;
		}
	},

	OnGetStringConfigImpresion: function(_jsonConfigEntidad, _clave) {
		let _salida="";
		let _x=_jsonConfigEntidad[_clave].x;
		let _y=_jsonConfigEntidad[_clave].y;
		let _width=_jsonConfigEntidad[_clave].width;
		let _height=_jsonConfigEntidad[_clave].height;
		let _unidad=_jsonConfigEntidad[_clave].unit;	
		return _salida;
	},
	OnGetStringConfigImpresionXY: function(_jsonConfigEntidad, _clave) {
		let _salida="";
		let _x=_jsonConfigEntidad[_clave].x;
		let _y=_jsonConfigEntidad[_clave].y;
		let _width=_jsonConfigEntidad[_clave].width;
		let _height=_jsonConfigEntidad[_clave].height;
		let _unidad=_jsonConfigEntidad[_clave].unit;
		_salida += "left:" + _x + _unidad + ";top:" + _y + _unidad +";" ;
		return _salida;
	},
	OnGetStringConfigImpresionWidth: function(_jsonConfigEntidad, _clave) {
		let _salida="";
		let _x=_jsonConfigEntidad[_clave].x;
		let _y=_jsonConfigEntidad[_clave].y;
		let _width=_jsonConfigEntidad[_clave].width;
		let _height=_jsonConfigEntidad[_clave].height;
		let _unidad=_jsonConfigEntidad[_clave].unit;
		_salida += "width:" + _width + _unidad + ";" ;
		return _salida;
	},
	OnGetStringConfigImpresionHeight: function(_jsonConfigEntidad, _clave) {
		let _salida="";
		let _x=_jsonConfigEntidad[_clave].x;
		let _y=_jsonConfigEntidad[_clave].y;
		let _width=_jsonConfigEntidad[_clave].width;
		let _height=_jsonConfigEntidad[_clave].height;
		let _unidad=_jsonConfigEntidad[_clave].unit;
		_salida += "height:" + _height + _unidad + ";" ;
		return _salida;
	},
	OnImprimirRecibo_CEM: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetRecibo_CEM(dataInput).then(function (respuestaJson) {
			var _html_block="";
			var _sb="";
			var _numero  = "";
			var _domicilio = "";
			var _fecha_recibo ;
			var _fecha_emision ;
			var _fecha_proximo ;
			var _proxima_conservacion;
			var _proximo_arrendamiento;
			var _observaciones  = "";
			var _letras = "";
			var _cuit  = "";
			var _cliente  = "";
			var _pagador  = "";
			var _numero_pagador  = "";
			var _numero_cliente  = "";
			var _parcelas  = "";
			var _cancelaciones = "";
			var _clase = "";
			var _fechas_A  = "";
			var _fechas_C  = "";
			var _importe  = 0.00;
			var _importe_efectivo  = 0.00;
			var _importe_cheque  = 0.00;
			var _importe_transferencia = 0.00;
			var _importe_transferencia_cbu  = 0.00;
			var _importe_transferencia_dni = 0.00;
			var _importe_transferencia_dre = 0.00;
			var _importe_tarjeta = 0.00;
			var _importe_extranjero  = 0.00;
			var _importe_extranjero2  = 0.00;
			var _detalle_cheques  = "";
			var _total_cancelaciones  = 0.00;
			var _total  = 0.00;
			// chequeo errores
			if (respuestaJson.status == 'OK') {
				_html="";
				$.each(respuestaJson.recibo, function (index, itemValue) {
					_numero = itemValue.Nro_Recibo;
					_fecha_recibo = itemValue.Fecha_Emision;
					_observaciones = itemValue.Observaciones;
					_cuit = itemValue.Cuit;
					_cliente = itemValue.RazonSocial;
					_pagador = itemValue.Pagador;
					_numero_pagador = itemValue.NumeroPagador;
					_numero_cliente = itemValue.NumeroCliente;
					_letras = itemValue.letras;
					_domicilio = itemValue.domicilio;
				});
				_cancelaciones += "<table width='100%' style='font-size:18px;'>" ;
				_vParcela_last = "";
				$.each(respuestaJson.reciboCC, function (index, itemValue) {
					var _vParcela = itemValue.parcelaReducido;
					var _detalle_precio = itemValue.nombrePrecio;
					if ( _detalle_precio.Length > 30 ) {_detalle_precio = _detalle_precio.Substring(0, 27).Trim() + "(...)";}
					_fecha_emision = itemValue.Fecha_Vencimiento;
					_fecha_proximo = itemValue.proximo_vencimiento; 		  // DD/MM/YYYY
					_proxima_conservacion = itemValue.proxima_conservacion;   // DD/MM/YYYY
					_proximo_arrendamiento = itemValue.proximo_arrendamiento; // DD/MM/YYYY
					if (_proximo_arrendamiento == null) { _proximo_arrendamiento = "01/0001 "; }
					if (_proxima_conservacion == null) { _proxima_conservacion = "01/0001 "; }
					_fechas_A = _proximo_arrendamiento.slice(-7) + " ";
					_fechas_C = _proxima_conservacion.slice(-7) + " ";
					if (_fechas_A == "01/0001 ") { _fechas_A = "N/D"; }
					if (_fechas_C == "01/0001 ") { _fechas_C = "N/D"; }
					if (_vParcela_last != _vParcela) {
						_vParcela_last = _vParcela;
						_parcelas += (_vParcela + ",");
					}
					if (_parcelas.indexOf(",") > 0) { _parcelas = _parcelas.substring(0, _parcelas.length - 1); }
					_clase = itemValue.clase;
					_importe = parseFloat(itemValue.Importe_Cancelado);
					_total_cancelaciones += _importe;
					_cancelaciones += ("      <tr>");
					_cancelaciones += ("        <td align='left' style='font-size:12px;'>" + _detalle_precio + "</td>");
					_cancelaciones += ("        <td align='center' style='font-size:12px;width:135px;'>" + _TOOLS.formatDDMMYYYY(_fecha_emision, "/") + "</td>");
					_cancelaciones += ("        <td align='right' style='font-size:12px;width:85px;'>" + parseFloat(_importe).toFixed(2) + "</td>");
					_cancelaciones += ("      </tr>");
				});
				_cancelaciones += "</table>";
				// Aca totalizo
				var _importe_efectivo = 0.0;
				$.each(respuestaJson.reciboValoresEfectivo, function (index, itemValue) {_importe_efectivo +=parseFloat(itemValue.importe);});
				var _importe_transferencia=0.0;
				$.each(respuestaJson.reciboValoresTrfBco, function (index, itemValue) {_importe_transferencia+=parseFloat(itemValue.importe);});

				var _importe_transferencia_cbu = 0.0;
				$.each(respuestaJson.reciboValoresTrfCbu, function (index, itemValue) {_importe_transferencia_cbu+=parseFloat(itemValue.importe);});

				var _importe_transferencia_dni=0.0;
				$.each(respuestaJson.reciboValoresTrfDni, function (index, itemValue) {_importe_transferencia_dni+=parseFloat(itemValue.importe);});

				var _importe_transferencia_dre=0.0;
				$.each(respuestaJson.reciboValoresTrfDre, function (index, itemValue) {_importe_transferencia_dre+=parseFloat(itemValue.importe);});

				var _importe_extranjero=0.0;
				$.each(respuestaJson.reciboValoresMonedaExtranjera1, function (index, itemValue) {_importe_extranjero+=parseFloat(itemValue.importe);});

				var _importe_extranjero2=0.0;
				$.each(respuestaJson.reciboValoresMonedaExtranjera2, function (index, itemValue) {_importe_extranjero2+=parseFloat(itemValue.importe);});

				var _importe_tarjeta=0.0;
				$.each(respuestaJson.reciboValoresTarjeta, function (index, itemValue) {_importe_tarjeta+=parseFloat(itemValue.importe);});

				var _importe_cheque = 0.0;
				var _detalle_cheques = "";
				$.each(respuestaJson.reciboValoresCheques, function (index, itemValue) {
					_importe_cheque += parseFloat(itemValue.importe);
					_detalle_cheques += itemValue.desc_valores + " " + itemValue.nro_comprobante + " - ";
				});
				
				var _total=0.0;
				$.each(respuestaJson.reciboValoresTotal, function (index, itemValue) {_total+=parseFloat(itemValue.importe);});
				_html_block="";

				let _r = respuestaJson.coordenadasImpresion.recibo; 
				let _divAbsolute = "<div style='position:absolute;";
				let _divEnd = "</div>";
				_html_block += "<div style='position:absolute;left:20px;top:5px;'>";
				_html_block += "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAAA3CAMAAAASRKdlAAAC6FBMVEUAAAD////+/v79/f38/Pz7+/v6+vr5+fn4+Pj39/f29vb19fX09PTz8/Py8vLx8fHw8PDv7+/u7u7t7e3s7Ozr6+vq6urp6eno6Ojn5+fm5ubl5eXk5OTj4+Pi4uLh4eHg4ODf39/e3t7d3d3c3Nzb29va2trZ2dnY2NjX19fW1tbV1dXU1NTT09PS0tLR0dHQ0NDPz8/Ozs7Nzc3MzMzLy8vKysrJycnIyMjHx8fGxsbFxcXExMTDw8PCwsLBwcHAwMC/v7++vr69vb28vLy7u7u6urq5ubm4uLi3t7e2tra1tbW0tLSzs7OysrKxsbGwsLCvr6+urq6tra2srKyrq6uqqqqpqamoqKinp6empqalpaWkpKSjo6OioqKhoaGgoKCfn5+enp6dnZ2cnJybm5uampqZmZmYmJiXl5eWlpaVlZWUlJSTk5OSkpKRkZGQkJCPj4+Ojo6NjY2MjIyLi4uKioqJiYmIiIiHh4eGhoaFhYWEhISDg4OCgoKBgYGAgIB/f39+fn59fX18fHx7e3t6enp5eXl4eHh3d3d2dnZ1dXV0dHRzc3NycnJxcXFwcHBvb29ubm5tbW1sbGxra2tqamppaWloaGhnZ2dmZmZlZWVkZGRjY2NiYmJhYWFgYGBfX19eXl5dXV1cXFxbW1taWlpZWVlYWFhXV1dWVlZVVVVUVFRTU1NSUlJRUVFQUFBPT09OTk5NTU1MTExLS0tKSkpJSUlISEhHR0dGRkZFRUVERERDQ0NCQkJBQUFAQEA/Pz8+Pj49PT08PDw7Ozs6Ojo5OTk4ODg3Nzc2NjY1NTU0NDQzMzMyMjIxMTEwMDAvLy8uLi4tLS0sLCwrKysqKiopKSkoKCgnJycmJiYlJSUkJCQjIyMiIiIhISEgICAfHx8eHh4dHR0cHBwbGxsaGhoZGRkYGBgXFxcWFhYVFRUUFBQTExMSEhIQEBAODg4MDAwKCgoICAgGBgYEBAQCAgL///8vhimaAAAA+HRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////ACjOtjcAAAAJcEhZcwAACxMAAAsTAQCanBgAAAa2aVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0OCA3OS4xNjQwMzYsIDIwMTkvMDgvMTMtMDE6MDY6NTcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDowQjc4RTQyRTQyOTIxMUVBQjM2QUFDQjYxQzZFRkMyNCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo4MzRmOWVhYy1hNTI2LWI0NDAtYmQxNS02ZmIxOTQzODJhYTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowQjc4RTQyRTQyOTIxMUVBQjM2QUFDQjYxQzZFRkMyNCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSBXaW5kb3dzIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyNC0wOS0xNFQxMTowNDozNy0wMzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjQtMDktMTRUMTI6Mjg6MDYtMDM6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjQtMDktMTRUMTI6Mjg6MDYtMDM6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIyIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowRkUxODI3RDNCOTUxMUVBOEEyRjg5MEUxQUI2RDlEOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowRkUxODI3RTNCOTUxMUVBOEEyRjg5MEUxQUI2RDlEOSIvPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphNmU0NmRiNS1iZmI1LTkyNGMtOWE0Zi1lNWY4MzIwNWE3YWEiIHN0RXZ0OndoZW49IjIwMjQtMDktMTRUMTI6MjU6MjUtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ODM0ZjllYWMtYTUyNi1iNDQwLWJkMTUtNmZiMTk0MzgyYWE1IiBzdEV2dDp3aGVuPSIyMDI0LTA5LTE0VDEyOjI4OjA2LTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+PUlj5AAAEuZJREFUaN7lWnmQG9WZ/6alVqulVutotVpqtVpqtUbSaCT1aEYja6zRaC7P6Rkf2PgCc5uYw15cBkMOjnAsSTiWBUIwN+wCgaVchrAQL5WCSgVINktR61BgNsuybGopZlmMAevv/V5LMyMfyZarMJWadE09Sf1973vv977zvTfQ9hf7wP/PQjEuj5ul/vKgU4w3Xqwua2cpfJYmdMpKnwwZzcf6rgGAFxVmqUKn7IL/JEZtcWcurwN5qiK9RA3eIoztEa0nWLun8/vQeL7MOJYodFo9+wexY/VKWe1C8QoC+5zZm1DtArUkoVPO/M/vz9hbcNOcENJL/0GQX56JGN+GLSq9FKFTjDIFX9YW3BmBh1JdZ95H3Lw+mfTzoRp8mXMsQegUG99G1FvhKWLnrKT3zLx3+ObNF78M8CMjEKi313oAhsUlGOEp3wozlC33UhTNesLFO4i69z+Lzcs9Xi68pfvz7G2wLUovPehW8YYrDn7rMih6GJcQjL7+7hzAG+Zi1JeLtCP66rKKNgSHDe5kaqdsnEcQ3E6Wpb8mq7A6ePaEZGOxC4GA6PeLouCyUq28Lhv1J2X9MXoDunDJBExvrXeFwokV+7VYbrp+/mhl0469/1IKMm2sAoPPhzpegRHJepJiz6dljXwul01r3q/HKihOK0Sdx0+X9hUGx4Z6jUKlWhAWwBDejEAvzsbt463HyWqhnwjd4u7th9rG92qFnTCgiIH0RP3SbCCo6pqXodpoj6LKbrF09MWU4/gJUY5I+UqA7z8JcB7o7CmjPFmBSPkvgGnf8QRGIlb47e2vYFv0WVp4QWOagihWKfTgr2NlwTFvjvd1zuj7h4mnH/h9fWc65Amkp5+AR2sKx9hME0b3R2NmIqtguXTc+lFsbArgl+X2VBVn1MWdInKacztOYkjCFPR4jodudQ18BrDe6JwFeFdnWnghyqAg1FEbHcJp9NuPk4X0PxnhB36+Aerrc6qX4csfm14+kov47FSL6XT87LVu/7F+Q0vDAJ9WQg4uOoHKcJ2izj36ZuVES6FYORU6oaimrJlbAKbD7sJjAAWuhVfz0CgIDYHiMgDjGn2cLM1j/ePQae/YGQAXd8d8rIXydsHNZ8EBVObmgtQyAavUDzcVVKHVC525T3E6ig2HSO8Cw0VZbQxtodooC22jbQxD4xfSmlM3f2MKwQe/WiiKS38LigK+IzykF22zESa6EeaaPRYG064HmJSY5A6Abg53Wza6wcsxRFCctViFtTCcdFHm6I1xMVPznI2IZhqyWoQS6BY+vfcpeED3mCr15c4vlS8dW3E5ql6ztKwS1/53cPTCXJhu8SSi9G4S+C1CJ2g8H4zpMdHBuEKarkbiekQUFV1XcMNLE5Ie5hwCRpCIqusiyypoKSvb3Q5BiZu9OFWPa7oekVVNYCibW9b0uMItgNeuA5iNBsr7YC7NcTiCFk8QXtmnoqBlQV5I9q+oJAW7OboSS2i8jfFGNJmz0j5FT+hhnrayplAfcQ9oszhCXXNQn2hW8JTUczTZ/rnqEbQO7RiPw517ONHzYueiidLaDwEujJr2hMsrJMcAPoG5kjqJy3bH058AbPj4RmwzLk5H0i6AvjRxpnd+8gm8k5VGngG47tz0sn1wkPSKFHEdgUhAhw14MmcBoIKHtHkzI9DPq12G1NWap4Afh+4yecGYQkE71qeR+zsA+zQy+g2k75hixsZ2T+xOgBtRajjSZwq9s+Cn24B2x7phTakjaCKiLM6OnXBOalBiLAzLHOskFoYXM32bFoMZnfhHgHVhqrkykTUAG9f8HubyZNpQGsFmc3EUVRtWCWl6K8wtG8SXG4rDn8LKSAdOdCaFi/HsLOmV60DgG4a2rRn/FGa11M1YPxrVOdOfFqDfuoNA/7Qs6+vxY2KK8EJvFwrqlVMA94xtBsh3bECeWi9yZIMlpBewKIHRGhYrRq0pdK6Lp8Dbfu3RUtRL6hGE5leyq2D/JdXJBE+fmMjkzuLwzk3CQkVB6wh9g2JpLJq7G+DttSuuwhH0cYC7Y7mHcUQ1/wjsTPcQUm0trkfpbnwZyT4MOzV9N85QxV7bRs1e2q1Iikly992wLo/rtjXpiWE87+EXoW/Ia90o5d1MFOvPm/IhwgvFDApKOqW+7cWeM/GnhhZ2S2cE17xX7ER6Gc3p7JRSuLhv+dy80MsjNEQ+vjodIPHUwknJ0ui5PwD43+++UJ/tUjHmHZuK5DXEfB5t5+e93aricl8VN3/SrsQ0wKtXnL9j1/ZosILv/YkHsDYm7e6uYZO0bdd2PYPBqk+I3wC743GccTWOirhhm9lLQtIy3sLEr4f15XtwGUTKjQY60dw7EOjTso3P4qD9URzhgihNeKEnjYJ0hotku6pIK8pIu1ITsF3mJbGxhoswIjIeUa0uCIWMHYK9UQxvFFZlXePPweLz35sqOt+K3eIp/g/O8ru7f7GQkKgArv37BnFGyhXHogbuKIfVuCaIywF2C/r9uOKk3d09RkiqomlSCgGUvQijCT2B81qVbvRCUpHDCHIdbKigPdV8bRyay8bgIvRJqY2SZwCmdBzhXMVCeJvQWV9u4hH4MYGO0HZHfcubA8EwOv+AF+O+f2BBKORZcGIiwQDMRfrq86jfuLDxeb1qX8yqTn/OPLZYM/Bh1LGQ6w18MxHGzGBTyoCF02+6GYcUD4UqBDrRepm0u41BQuIZT1whWi/7UFkN6AM6an1WMXsFF6GvL2ESnwxZPRWisAZ0tBSYDlKW8CyBXlmEXiTQ2z0d6Mxrz8WfYTJ6lGi97CP0QVz4yaCV4cP9C0IhbQdSAlrYQG69iby+d+t9MJU0qtMP/1sdslxj8+7gfQG9hMj/Zsvrj5WeWCxDaBl1dqgacbtCxU+ewynBVEA2IKXj62sU4uuDpL2mq0hImj8BeYJpSEFfvyadQInTOYT+cI/ZK46kfq/Fnr4Fzu7GxbrRkJLoY0ZzFgb6wKYk7+/BkFDR0YW26wzhhUoWc0cxiRZ456qzUck51PI1aQV/12RSBw0j4lsKgXD7b4ovzgvdKNOAMp1iavCnOLcXVlWN9sIM9LIOb1DryHeETIw2IZnP9prnc1OzQ7+qPbIInWI1dPC5/o5k4WkoZBDwXLHnUujsXAVwIF/F6DpD2mdKKUKqGX1QrL1PXvZ/Cg93J89BLANwCIMQ6ZXpQNKYbHf3vA83dmc2YmboxdBcCzZiiRu/w/vVdAHRPZ5JotU/aLgIL4waKGhi7R5MbGufAjhj5xYcvWxgOJzOlJE+XlhJskrPx9l4qSl0f4ajwOIIZoduQqlfDWfCPo6NPQq9NFlkxsmZW0fKk6/D28OT10N9dvDAyMonv0xwrduX0i7TOy7oEN3RgXfw21tZ9SryZiMstopuksDoaHmpkeQM5/ZdCo1eJCdBjPOTj9/KiZqZtMtKo6ZtbF8az/ZsaL35xWfyQqZonhlfRIqCS3ABGu5KmkHza3vySvKxVnUHCqbQNRksboHTcWde3z3QKTucgWjYm7gOyq1VMO01DkAtlf1b+KCUfQgemvoMetzHFDq8KIdDIs9QlNUhyIrkYe0eORqLSHIkpoZJq/hZmpBk0cmJCy8FJ+dXFIlneUkxe7mVmCq5GNavxMJeptljPs1YnXJUi0UVRVHDPMO4Q1GUwBJeVeJdfkUWeG8Q+UU5ElFjsUhIUqKqHJAInWPcQSUs8hjOGVOoi2RucF12z7qBgiawVrtcrP+4PXcTVFpKVZsPS5xaOj0J9UowhnoZG4PKcSdVlMVqbe4+8atZwltp2mrFptGSd02uRRK+xMUy2Rc+8G2DZbHH4v6WiCPPIq+lKc4UZHY031gbQyy21PxIx0wWcBlkP6nw2zAk3vXoyMCDMLK47bNLuSfrU7nCzFGsCu0ymtnAzEcvacxSOKUDXL7GcrQp5711Luxdjd7nXNitoSH8KBtL/wHg9oyDks4nvjfzz0XRtrSuG/WfvXEW/Dva9A3OeRvjSvDTjOBN1vd/OBuwUp4yQM6Tg1e7BWvLiT3r4lqqXqwRfKLg9fpcp24bFG23WcwAwp5+w2qBnry9vuUPsO6v3+5lmn7LKPU38z67d7gzl5Yw0NrVdXvC9sj58GbOtTAzC6d0pl0t0NmwUelNJzqzivNUp28TIo2iWgj7bN8cdCr9FWx5EVatKkcbwYWiBeOLgkgz/i8UD0u0YXEIgsPiWYY7C33hGIUWMFsEW6zAof8C3lmW2YZ7LuYUlc53QjGAA1kVkNlvCjqaaflJ2LQPZkt9gcYplIXPXN+n8pxH7euU7U1DwLjAxDFt1wLzacDCF56HaGtSUCbgUDGMNW7+FA/rrNIY7E+z37DWKUcYo/iZO2BVZTzYCP5M9D4Y7c11lwZXH61pHDOfaCy+2r9CPcI0anuWF3G7rJGTIt7ROIry9uOuktMOQY/H6XGzDOdx0lSDznCcw8mTbQPN8m6OseBO2e12NkOFPfyMea1J0V6/y85ynIt30nYX4bMwrgW2rxs6l/wKYN0srB/ZG2uM4DTg81deesush46uLSQV0W3ix7IgXbyocT9n80Xb9dL3IM6Lqt6u+c3g5C7DoV6lAHOdXjmXk72qEeOdJl2UkplYWO+I8owQ0eJamLdxwXZd5hp1iytSuhqu1hkLl0gGfWomHY8poqwlEmHeFYwvsH3dvk5zvT+B1Svh2hnIN6zXvRyOeer7b9643EjKWF9ZaJtpA7Q8BOU0xn0jD+PlYdgi0w3o5nNdTyh9Je4hDVidypn0swqvwqXJwkHIxp48kGkvQo+agKGuvY0zU2vohewouduj/QehqPVjxEhtxgqqcgV0ZWF0nu10hLm7YHYarp2FYiM68VjrP/LtM8aGqn3lcmVkz7NzBM+9q1uSujML+wwp9wT0XwHTy6rPgWprar0S7/wOPJRKb4K+UCdsLO9s0AsbYERN74FqCSphIXMb9BiwtpJwm5jseiFX2QH3JlnvZlgeLcMvC8EMXFIrjUF/D3xvnu00QM8fgdlRuGoGljWgs5nPYSIZwhzt5t3ecKpQKlcGt38IocW4jYl+c5xN7IEVczDekTbSYlPr7xpuN+5GSx3roBxIwuahJr19CqpS7GIYHYUiT6vjUO3cCq+VTZGU66rOZOdKcuvJYzc0lv1pVwrOLrVn83r7AttpgG7tBpjt/x3afMXe3IxPQMXXuNFBG9BLlWU9hUKhw7MYzT19sFljEzfCGNppRBBxX0Cg92GYYyzdhxDYOuiTUrB5uEnXxqE/EL0YVqxA6FYV1aniwkGOXF4w4gWqIBr3wUWatwF9X5JLwtk5vz8gBpKDTbbTAb3jK9ha2jYwDYNs83KkDOuDjTPHtrbA5QDv3b2+lIu2XIxwBjxqSMaLMHoljEZ5X563YdCXRjG5+dwDqPXkahjWDNha3WXSjfRqGIkkd8B4FaqKL3MtFGSljDh9mF3FTE6kaWUWoKJthppWguezXAT2FwSP/9qgmjXZTo/Bi4WbnzKeqEzCrc1C1lmAvQvXOCGs3+HgawBftkBnIqhVEuaqFTjYFVXfRIug7OQerqbru+ABXS7CeLYIV/eWGvTcBTDennscRo1X3s4lilDWlAcLw5Bzt1HOxJ6ybLOF0YDOL26DkY4KzBk86r+WVHVVezBrsp2eQtbmkZXU8LVVKDULKXvmyIH0vHuJKwCenzAyKZlpvYiL9eB250KARP4CgDWSE6M/N58S1qY8XHQafRfgKjVL6DI5Mkj9lpxI6P0Al3TIXpkcEaITW93mMal9Zr53hjQ8o3W/DP8lS2qT7fRAb7PYvOn7p/L3abb5y5XLjubZxYgGHxkC1iXHHNM6/KFgSI1LvEeSJQ/bSPuxdDImh1WJo63OgCwFohGf003onKAmIn5J02WeE4KhgIexuSQl6ENDolgxpvA0zSvJtK7GdSUg65popxx+OeR3sbwUNtlOE3SKkcuHq+nI/FU8FVwJ2fnTVy4D9WtyJ2RWymJjbPhnWbxXxG928pipn7LayMXj/J0ikgi7HUsjcjNJNfqbjJbGdWSzM7I1LghNBssC22mCTkvFwyvH/n4oG/E4GHIG4lTTAVtjD+eQMrXJuz9Pe77WgurPZb/exoS/2DT29N3TR36diiuS5HdzHnKeRbNOt6zl7vnd7EvTr0j0koRu9WjjR3499vw7Fw18cKD3rwZTkbAq8wwnqfoHL40cfmH94cnXA0sTOm5c/bJ+5WcXr5h6+rbJI6Ovbd30Ty8rTmHmseJHv5qY+ODzcl+EW5oGT4ISw0eHD563b9UHs4dnJo9M33V7u0vIfNJ3z+xzq57Ohz0Omlqi0E3Ni0r76v+8/KLPRu99fOLmH8663fEjhvF6Kix57Uvq3yXhZEeDvCgpen5iqOOsT964j3eIqiz6efv8ye3Shd74lx875xG8HjGqCHba7mS/7pz6Zwu9eRSNxQlrp5ce5ubzf1zZw+KfkP4JAAAAAElFTkSuQmCC' style='width:250px;'/>";
				_html_block += "<br/><b>Asociación Civil corporación del Cementerio Británico de Buenos Aires</b>";
				_html_block += "</div>"

				// Cuadro 1
				//left:5mm;top:38mm;
				_html_block += _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro1Line1Original') + "font-size:15px;'><b>Recibimos de:</b><br/>" + _pagador + _divEnd;
				//left:5mm;top:43mm;
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro1Line2Original') + "font-size:15px;'>" + _domicilio + _divEnd;
		
				// Cuadro 2
				//"left:175mm;top:23mm;width:100mm;"
				_html_block += _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro2Line1Original') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro2Line1Original') +"font-size:15px;'><b>Nºde recibo</b><br/><b>" + _numero + " - " + _TOOLS.formatDDMMYYYY(_fecha_recibo, "/") + "</b>" + _divEnd;
				//left:175mm;top:35mm;width:100mm;height:47mm
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro2Line2Original') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro2Line2Original') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'recuadro2Line2Original') + "font-size:12px;'>" + _pagador + " <i>(" + _numero_pagador + ")</i>" + _divEnd;

				//left:175mm;top:45mm;width:100mm;height:10mm;
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro2Line3Original') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro2Line3Original') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'recuadro2Line3Original') + "font-size:15px;'>" + _parcelas  + _divEnd;
				
				// Cuadro 3
				//left:0mm;top:110mm;width:150mm;height:55mm;
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro3Line1Original') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro3Line1Original') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'recuadro3Line1Original') + "font-size:14px;'>" + _cancelaciones  + _divEnd;
				//left:86mm;top:170mm;
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro3Line10Col1Original') + "font-size:14px;'>Cons.: " + _fechas_C + " Arr: " + _fechas_A  + _divEnd;
				//left:138mm;top:160mm;
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro3Line10Col2Original') + "font-size:14px;'><b style='text-decoration: underline;'>" + parseFloat( _total_cancelaciones).toFixed(2) + "</b>" + _divEnd;
				
				// Cuadro 4
				// Valores del pago

				//"left:185mm;top:102mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line1Col1Original') + "font-size:12px;'>Efectivo" + _divEnd;
				// "left:185mm;top:107mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line2Col1Original') + "font-size:12px;'>Cheques" + _detalle_cheques + _divEnd;
				// "left:185mm;top:112mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line3Col1Original') + "font-size:12px;'>DD" + _divEnd;
				// "left:185mm;top:117mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line4Col1Original') + "font-size:12px;'>CBU" + _divEnd;
				// "left:185mm;top:122mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line5Col1Original') + "font-size:12px;'>DNI" + _divEnd;
				// "left:185mm;top:127mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line6Col1Original') + "font-size:12px;'>DRE" + _divEnd;
				// "left:185mm;top:132mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line7Col1Original') + "font-size:12px;'>Tarjetas" + _divEnd;
				// "left:185mm;top:137mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line8Col1Original') + "font-size:12px;'>U$S" + _divEnd;
				// "left:185mm;top:142mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line9Col1Original') + "font-size:12px;'>£" + _divEnd;
				// "left:185mm;top:170mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line10Col1Original') + "font-size:12px;'>Total" + _divEnd;
		
				// Importes de los valores

				// "left:250mm;top:102mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line1Col2Original') + "font-size:12px;'>" + parseFloat( _importe_efectivo ).toFixed(2) + "</div>";
				// "left:250mm;top:107mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line2Col2Original') + "font-size:12px;'>" + parseFloat(_importe_cheque).toFixed(2) + "</div>";
				// "left:250mm;top:112mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line3Col2Original') + "font-size:12px;'>" + parseFloat(_importe_transferencia).toFixed(2) + "</div>";
				// "left:250mm;top:117mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line4Col2Original') + "font-size:12px;'>" + parseFloat(_importe_transferencia_cbu).toFixed(2) + "</div>";
				// left:250mm;top:122mm
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line5Col2Original') + "font-size:12px;'>" + parseFloat(_importe_transferencia_dni).toFixed(2) + "</div>";
				// "left:250mm;top:127mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line6Col2Original') + "font-size:12px;'>" + parseFloat(_importe_transferencia_dre).toFixed(2) + "</div>";
				// "left:250mm;top:132mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line7Col2Original') + "font-size:12px;'>" + parseFloat(_importe_tarjeta).toFixed(2) + "</div>";
				// "left:250mm;top:137mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line8Col2Original') + "font-size:12px;'>" + parseFloat(_importe_extranjero).toFixed(2) + "</div>";
				// "left:250mm;top:142mm;"
				_html_block+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line9Col2Original') + "font-size:12px;'>" + parseFloat(_importe_extranjero2).toFixed(2) + "</div>";
				// "left:250mm;top:170mm;"
				_html_block += _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line10Col2Original') + "font-size:12px;'>" + parseFloat(_total).toFixed(2) + "</div>";

				var _pie = "<table style='width:100%'>";
				_pie += "   <tr style='font-weight:bold;'>";
				_pie += "      <td valign='top' style='width:35%;'>CEMENTERIO CHACARITA</td>";
				_pie += "      <td valign='top' style='width:35%;'>CEMENTERIO JARDÍN NOGUÉS</td>";
				_pie += "      <td valign='top' style='width:30%;border:double 3px black;' align='center' ><b>ORIGINAL</b></td>";
				_pie += "   </tr>";
				_pie += "   <tr>";
				_pie += "      <td style='width:35%;' valign='top'>Av.Elcano 4568 - C1427CIQ - CABA</td>";
				_pie += "      <td style='width:35%;' valign='top'>Morse 203 y Av Sesquicentenario (Ex ruta 197)<br/>B1613WAB - Ing.Pablo Nogués - Pcia. de Bs.As.</td>";
				_pie += "      <td style='width:30%;' valign='top'>CUIT: 30-52641781-6 IIBB: EXENTO Imp.Int.: NO RESPONSABLE</td>";
				_pie += "   </tr>";
				_pie += "   <tr>";
				_pie += "      <td valign='top' style='width:35%;'>TE: 4553-3403 / 4554-0092</td>";
				_pie += "      <td valign='top' style='width:35%;'>TE: +54-011 4463-0045</td>";
				_pie += "      <td valign='top' style='width:30%;'>Inicio de actividades: 1820 IVA: EXENTO</td>";
				_pie += "   </tr>";
				_pie += "</table>";
				_pie += "<b>El presente recibo tendrá efecto cancelatorio únicamente si los valores detallados en el presente se acreditan y se hacen efectivos.</b>";
				_html_block += "<div style='position:absolute;left:10mm;top:110mm;width:100%;'><br/><b>Firma y Sello:</b><br/><br/><br/>" + _pie + "</div>";
				
				////// Duplicado
				let _html_block2 = "";
				_html_block2 += "<div style='position:absolute;left:20px;top:" + (parseInt(_r["recuadro1Line1Duplicado"].y) - 25) + "mm;'>";
				_html_block2 += "<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPoAAAA3CAMAAAASRKdlAAAC6FBMVEUAAAD////+/v79/f38/Pz7+/v6+vr5+fn4+Pj39/f29vb19fX09PTz8/Py8vLx8fHw8PDv7+/u7u7t7e3s7Ozr6+vq6urp6eno6Ojn5+fm5ubl5eXk5OTj4+Pi4uLh4eHg4ODf39/e3t7d3d3c3Nzb29va2trZ2dnY2NjX19fW1tbV1dXU1NTT09PS0tLR0dHQ0NDPz8/Ozs7Nzc3MzMzLy8vKysrJycnIyMjHx8fGxsbFxcXExMTDw8PCwsLBwcHAwMC/v7++vr69vb28vLy7u7u6urq5ubm4uLi3t7e2tra1tbW0tLSzs7OysrKxsbGwsLCvr6+urq6tra2srKyrq6uqqqqpqamoqKinp6empqalpaWkpKSjo6OioqKhoaGgoKCfn5+enp6dnZ2cnJybm5uampqZmZmYmJiXl5eWlpaVlZWUlJSTk5OSkpKRkZGQkJCPj4+Ojo6NjY2MjIyLi4uKioqJiYmIiIiHh4eGhoaFhYWEhISDg4OCgoKBgYGAgIB/f39+fn59fX18fHx7e3t6enp5eXl4eHh3d3d2dnZ1dXV0dHRzc3NycnJxcXFwcHBvb29ubm5tbW1sbGxra2tqamppaWloaGhnZ2dmZmZlZWVkZGRjY2NiYmJhYWFgYGBfX19eXl5dXV1cXFxbW1taWlpZWVlYWFhXV1dWVlZVVVVUVFRTU1NSUlJRUVFQUFBPT09OTk5NTU1MTExLS0tKSkpJSUlISEhHR0dGRkZFRUVERERDQ0NCQkJBQUFAQEA/Pz8+Pj49PT08PDw7Ozs6Ojo5OTk4ODg3Nzc2NjY1NTU0NDQzMzMyMjIxMTEwMDAvLy8uLi4tLS0sLCwrKysqKiopKSkoKCgnJycmJiYlJSUkJCQjIyMiIiIhISEgICAfHx8eHh4dHR0cHBwbGxsaGhoZGRkYGBgXFxcWFhYVFRUUFBQTExMSEhIQEBAODg4MDAwKCgoICAgGBgYEBAQCAgL///8vhimaAAAA+HRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////ACjOtjcAAAAJcEhZcwAACxMAAAsTAQCanBgAAAa2aVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzE0OCA3OS4xNjQwMzYsIDIwMTkvMDgvMTMtMDE6MDY6NTcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDowQjc4RTQyRTQyOTIxMUVBQjM2QUFDQjYxQzZFRkMyNCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo4MzRmOWVhYy1hNTI2LWI0NDAtYmQxNS02ZmIxOTQzODJhYTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowQjc4RTQyRTQyOTIxMUVBQjM2QUFDQjYxQzZFRkMyNCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxOSBXaW5kb3dzIiB4bXA6Q3JlYXRlRGF0ZT0iMjAyNC0wOS0xNFQxMTowNDozNy0wMzowMCIgeG1wOk1vZGlmeURhdGU9IjIwMjQtMDktMTRUMTI6Mjg6MDYtMDM6MDAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMjQtMDktMTRUMTI6Mjg6MDYtMDM6MDAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIyIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowRkUxODI3RDNCOTUxMUVBOEEyRjg5MEUxQUI2RDlEOSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowRkUxODI3RTNCOTUxMUVBOEEyRjg5MEUxQUI2RDlEOSIvPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDphNmU0NmRiNS1iZmI1LTkyNGMtOWE0Zi1lNWY4MzIwNWE3YWEiIHN0RXZ0OndoZW49IjIwMjQtMDktMTRUMTI6MjU6MjUtMDM6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMS4wIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6ODM0ZjllYWMtYTUyNi1iNDQwLWJkMTUtNmZiMTk0MzgyYWE1IiBzdEV2dDp3aGVuPSIyMDI0LTA5LTE0VDEyOjI4OjA2LTAzOjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgMjEuMCAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9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' style='width:250px;'/>";
				_html_block2 += "</div>";
				// Cuadro 1
				//left:5mm;top:38mm;
				_html_block2 += _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro1Line1Duplicado') + "font-size:15px;'><b>Recibimos de:</b><br/>" + _pagador + _divEnd;
				//left:5mm;top:43mm;
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro1Line2Duplicado') + "font-size:15px;'>" + _domicilio + _divEnd;
		
				// Cuadro 2
				//"left:175mm;top:23mm;width:100mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro2Line1Duplicado') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro2Line1Original')+"font-size:15px;'><b>Nº de recibo:<b/><br/><b>" + _numero + " - " + _TOOLS.formatDDMMYYYY(_fecha_recibo, "/") + "</b>" + _divEnd;
				//left:175mm;top:35mm;width:100mm;height:47mm
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro2Line2Duplicado') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro2Line2Original') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'recuadro2Line2Original') + "font-size:12px;'>" + _pagador + " <i>(" + _numero_pagador + ")</i>" + _divEnd;
				//left:175mm;top:45mm;width:100mm;height:10mm;
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro2Line3Duplicado') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro2Line3Original') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'recuadro2Line3Original') + "font-size:15px;'>" + _parcelas  + _divEnd;
				
				// Cuadro 3
				//left:0mm;top:110mm;width:150mm;height:55mm;
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro3Line1Duplicado') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'recuadro3Line1Original') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'recuadro3Line1Original') + "font-size:14px;'>" + _cancelaciones  + _divEnd;
				//left:86mm;top:170mm;
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro3Line10Col1Duplicado') + "font-size:14px;'>Cons.: " + _fechas_C + " Arr: " + _fechas_A  + _divEnd;
				//left:138mm;top:160mm;
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro3Line10Col2Duplicado') + "font-size:14px;'><b style='text-decoration: underline;'>" + parseFloat( _total_cancelaciones).toFixed(2) + "</b>" + _divEnd;
				
				// Cuadro 4
				// Valores del pago
				//"left:185mm;top:102mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line1Col1Duplicado') + "font-size:12px;'>Efectivo" + _divEnd;
				// "left:185mm;top:107mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line2Col1Duplicado') + "font-size:12px;'>Cheques" + _detalle_cheques + _divEnd;
				// "left:185mm;top:112mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line3Col1Duplicado') + "font-size:12px;'>DD" + _divEnd;
				// "left:185mm;top:117mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line4Col1Duplicado') + "font-size:12px;'>CBU" + _divEnd;
				// "left:185mm;top:122mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line5Col1Duplicado') + "font-size:12px;'>DNI" + _divEnd;
				// "left:185mm;top:127mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line6Col1Duplicado') + "font-size:12px;'>DRE" + _divEnd;
				// "left:185mm;top:132mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line7Col1Duplicado') + "font-size:12px;'>Tarjetas" + _divEnd;
				// "left:185mm;top:137mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line8Col1Duplicado') + "font-size:12px;'>U$S" + _divEnd;
				// "left:185mm;top:142mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line9Col1Duplicado') + "font-size:12px;'>£" + _divEnd;
				// "left:185mm;top:170mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line10Col1Duplicado') + "font-size:12px;'>Total" + _divEnd;
		
				// Importes de los valores
				// "left:250mm;top:102mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line1Col2Duplicado') + "font-size:12px;'>" + parseFloat( _importe_efectivo ).toFixed(2) + "</div>";
				// "left:250mm;top:107mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line2Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_cheque).toFixed(2) + "</div>";
				// "left:250mm;top:112mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line3Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_transferencia).toFixed(2) + "</div>";
				// "left:250mm;top:117mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line4Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_transferencia_cbu).toFixed(2) + "</div>";
				// left:250mm;top:122mm
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line5Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_transferencia_dni).toFixed(2) + "</div>";
				// "left:250mm;top:127mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line6Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_transferencia_dre).toFixed(2) + "</div>";
				// "left:250mm;top:132mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line7Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_tarjeta).toFixed(2) + "</div>";
				// "left:250mm;top:137mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line8Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_extranjero).toFixed(2) + "</div>";
				// "left:250mm;top:142mm;"
				_html_block2+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line9Col2Duplicado') + "font-size:12px;'>" + parseFloat(_importe_extranjero2).toFixed(2) + "</div>";
				// "left:250mm;top:170mm;"
				_html_block2 += _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'recuadro4Line10Col2Duplicado') + "font-size:12px;'>" + parseFloat(_total).toFixed(2) + "</div>";

				_html_block += "<div style='position:absolute;left:10mm;top:250mm;width:100%;font-size:10px;'>" + _pie.replace("ORIGINAL","DUPLICADO") + "</div>";
				
				let _control = {xx: {x: "100", y: "145", width: "62", height: "42", unit: "mm"},};
				let _html_block_control = _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_control, 'xx') + "font-size:12px;'>" + '--' + "</div>";
				let _control2 = {xx: {x: "100", y: "294", width: "62", height: "42", unit: "mm"},};
				let _html_block_control2 = _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_control2, 'xx') + "font-size:12px;'>" + '--' + "</div>";
				_sb = "";
				let _noprint="";
				_noprint += "<style>		";
				_noprint += "@media print";
				_noprint += "{    ";
				_noprint += "    .noprint, .noprint *";
				_noprint += "    {";
				_noprint += "       display: none !important;";
				_noprint += "    }";
				_noprint += "}";
				_noprint += "</style>";
				let _printButton = "";
				_printButton =  "<button class='noprint' onclick='alert('button');'>";
				_printButton += "    <img class='noprint' src='" + _AJAX._here + "assets/img/print.jpg' style='height:35px;'></img>";
				_printButton += "</button>";
				let _js = "";
				_js += " ";
				_js += "function ejecutar() { ";
				_js += "	alert('hola!!!');";
				_js += "	window.print(); ";
				_js += "	return false; ";
				_js += "} ";
				_js += "function ejecutar2() { ";
				_js += "	alert('hola2!!!');";
				_js += "	window.print(); ";
				_js += "	return false; ";
				_js += "} ";				
				_js += "document.addEventListener('DOMContentLoaded', function(event) { ";
				_js += "                                                 	ejecutar(); ";
				_js += "                                              }); ";
				_js = "<script>"+_js+"</script>";

				// Original
				//left:5mm;top:5mm;width:210mm;
				_sb+= _divAbsolute + _FUNCTIONS.OnGetStringConfigImpresionXY(_r, 'paperSize') + _FUNCTIONS.OnGetStringConfigImpresionWidth(_r, 'paperSize') + _FUNCTIONS.OnGetStringConfigImpresionHeight(_r, 'paperSize') +"font-size:12px;'>";
				_sb+= _html_block; // original
				_sb+= _html_block_control; // guion de referencia
				_sb+= _html_block2; // duplicado
				_sb+= _html_block_control2; // guion de referencia
				_sb+= _divEnd;

				_sb = "<!DOCTYPE html><html><head><title>Impresión Recibo</title>" + _noprint + "</head><body>" + _sb + _js + "</body></html>";
				var myW = window.open();
        		myW.document.write(_sb);
				var p = {title: "Recibo "+_numero, html: _sb, filename: "Recibo-"+_numero+".pdf"};
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},
	OnAnularRecibo_CEM: function(_this, parametros) {
		dataInput = parametros;	
		let id = dataInput.ID_Recibo;
		let target_class = ".record-"+id; // esta es clase que va en el tr de la lista de recibos.
		_AJAX.UiAnularRecibo_CEM(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				_html="";
				$(target_class).children('td').eq(1).html('<p class="text-monospace text-break" style="display:block;"><b style="color:red;">ANULADO</b></p>');
				$(target_class).children('td').eq(9).html('');
				$('#REPORT-CONTAINER').append(_html);
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},	
	OnMyPdf: function(_this, parametros) {
		_AJAX.UiMyPdf( _this ).then().catch();
	},
	OnGetClientePagadorParcela: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetClientePagadorParcela(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				_html="";
				$.each(respuestaJson.clientePagadorParcela, function (index, itemValue) {_domicilio = itemValue.XXXXXXX;});
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},
	OnGetDatosVariosRecibos: function (_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetDatosVarios(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				_html="";
				CabeceraRecibo_CEM(respuestaJson);
				GetValoresBuilder_CEM("REC", 1, respuestaJson);
				Totales_CEM();
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},	
	OnGenerarRecibo: function (_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGenerarRecibo(dataInput).then(function (respuestaJson) {
			// chequeo errores
			if (respuestaJson.status == 'OK') {
				_html="";
				let _id, _error, _numrows;
				$.each(respuestaJson.recibo, function (index, itemValue) {
					_Nro_Recibo=itemValue.Nro_Recibo;
				});
				alert("Recibo generado ! (Nº de recibo "+_Nro_Recibo+")");
				$('#mibotoncancelar').click();
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},	
	OnGetDatosVariosOP: function (_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGetDatosVarios(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				_html="";
				CabeceraOP_CEM(respuestaJson);
				GetValoresBuilder_CEM("", 1, respuestaJson);
				TotalesOP_CEM();
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},
	OnGenerarOrdenDePago: function(_this, parametros) {
		dataInput = parametros;	
		_AJAX.UiGenerarOrdenDePago(dataInput).then(function (respuestaJson) {
			if (respuestaJson.status == 'OK') {
				_html="";
				let _id, _error, _numrows;
				console.log(respuestaJson.recibo);
				_id = respuestaJson.recibo.Id;
				_Nro_OP = respuestaJson.recibo.Nro_OP;
				if (_id>0){
					alert("OP generada correctamente! (Nro.OP "+_Nro_OP+")");
					$('#mibotoncancelar').click(); // actualizo
				} else {
					alert("Ocurrio un error al generar el recibo!");
				}
			} else {
				alert("Ocurrio un error al obtener los datos");				
			}
		});
	},
}  