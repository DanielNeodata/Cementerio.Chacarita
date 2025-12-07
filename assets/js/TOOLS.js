var _TOOLS = {
	_timerIcon: 0,
	latitude: null,
	longitude: null,
	altitude: null,
	accuracy: null,
	heading: null,
	speed: null,
	timestamp: null,
	observable: function (value) {
		var listeners = [];
		function notify(newValue) {
			listeners.forEach(function (listener) { listener(newValue); });
		}
		function accessor(newValue) {
			if (arguments.length && newValue !== value) {
				value = newValue;
				notify(newValue);
			}
			return value;
		}
		accessor.subscribe = function (listener) { listeners.push(listener); };
		return accessor;
	},

	reformatDate: function (_str) {
		var _a = _str.split("-");
		return (_a[2] + "-" + _a[1] + "-" + _a[0]);
	},

	todayYYYYMMDD: function (_separator) {
		var currentDate = new Date();
		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1;
		var year = currentDate.getFullYear();
		if (day < 10) { day = "0" + day; }
		if (month < 10) { month = "0" + month; }
		return (year + _separator + month + _separator + day);
	},
	toDeg: function (r) { return r * 180 / Math.PI; },
	getNow: function () {
		var currentDate = new Date();
		var second = currentDate.getSeconds();
		var minute = currentDate.getMinutes();
		var hour = currentDate.getHours();
		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1;
		var year = currentDate.getFullYear();
		if (day < 10) { day = "0" + day; }
		if (month < 10) { month = "0" + month; }
		if (hour < 10) { hour = "0" + hour; }
		if (minute < 10) { minute = "0" + minute; }
		if (second < 10) { second = "0" + second; }
		return day + "/" + month + "/" + year + " " + hour + ":" + minute + ":" + second;
	},
	getNowYYYYMMDD: function () {
		var currentDate = new Date();
		var second = currentDate.getSeconds();
		var minute = currentDate.getMinutes();
		var hour = currentDate.getHours();
		var day = currentDate.getDate();
		var month = currentDate.getMonth() + 1;
		var year = currentDate.getFullYear();
		if (day < 10) { day = "0" + day; }
		if (month < 10) { month = "0" + month; }
		if (hour < 10) { hour = "0" + hour; }
		if (minute < 10) { minute = "0" + minute; }
		if (second < 10) { second = "0" + second; }
		return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
	},
	isValidEmail: function (email) {
		var em = /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
		return em.test(email);
	},
	validate: function (_selector, _seeAlert) {
		if (_seeAlert == undefined) { _seeAlert = false; }
		var _ret = true;
		$(_selector).each(function () { _ret = _TOOLS.formatValidation($(this)) && _ret; });
		if (!_ret && _seeAlert) {
			if (_AJAX._waiter) { $.unblockUI(); }
			_AJAX._waiter = false;
			_FUNCTIONS.onAlert({ "message": "Complete los datos requeridos", "class": "alert-danger" });
		}
		return _ret;
	},
	formatValidation: function (_obj) {
		var _ret = true;
		var property = _obj.attr('name');
		switch (_obj.prop("tagName")) {
			case "TEXTAREA":
			case "INPUT":
				switch (_obj.attr("type")) {
					case "email":
						if (!_TOOLS.isValidEmail(_obj.val())) { _ret = false; }
						break;
					case "radio":
						_ret = ($("input[name='" + property + "']:checked").val() != undefined);
						break;
					case "checkbox":
						var _checked = _obj.is(":checked");
						if (!_checked) { _ret = false; }
						break;
					default:
						if (_obj.hasClass("data-list")) {
							if (_obj.attr("data-selected-id") == "" || _obj.attr("data-selected-id") == undefined) { _ret = false; }
						} else {
							if (_obj.val() == "") { _ret = false; }
						}
						break;
				}
				break;
			case "SELECT":
				if (_obj.val() == "-1" || _obj.val() == undefined || _obj.val() == null || _obj.val() == "") { _ret = false; }
				break;
		}
		if (_ret) {
			_obj.removeClass("is-invalid").addClass("is-valid");
			$(".invalid-" + _obj.prop("name")).html("").addClass("d-none");
		} else {
			_obj.removeClass("is-valid").addClass("is-invalid");
			var _msg = _obj.attr("placeholder");
			if (_msg == undefined) { _msg = "el valor de selección";}
			$(".invalid-" + _obj.prop("name")).html("Debe completar " + _msg).removeClass("d-none");
		}
		return _ret;
	},
	getFormValues: function (_selector, _this) {
		try {
			var _jsonSave = {};
			$(_selector).each(function () {
				var property = $(this).attr('name');
				var value = "";
				switch ($(this).attr("data-type")) {
					case "select":
						if ($(this).length == 0) { value = ""; } else { value = $(this).val(); }
						if (value == null || value == "-1" || value == "0") { value = ""; }
						break;
					case "radio":
						value = $("input[name='" + property + "']:checked").val();
						if (value == undefined) { value = ""; }
						break;
					case "checkbox":
						//alert("check");
						//alert($(this).attr("checkboxtype"));
						if ($(this).attr("checkboxtype") == "01") {
							if ($(this).prop("checked")) {
								value = $(this).val();
								if (parseInt(value) == 0 || value == '') { value = 1; }
							} else {
								value = 0;
							}
						}
						else if ($(this).attr("checkboxtype") == "SN") {
							//alert("if SN");
							if ($(this).prop("checked")) {
								//alert("if checked");
								//value = $(this).val();
								value = "S"; 
							} else {
								//alert("if else checked");
								value = "N";
							}
							//alert("valor: ".value);
						} else {
							if ($(this).prop("checked")) {
								value = $(this).val();
								if (parseInt(value) == 0 || value == '') { value = 1; }
							} else {
								value = 0;
							}
						}
						break;
					default:
						value = $(this).val();
						break;
				}
				_jsonSave[property] = value;
			});
			//Process attached files
			/* GENERAL */
			var _newFiles = [];
			var _newLinks = [];
			var _delFiles = [];
			var _delLinks = [];
			var _newMessages = [];
			$(".new-file").each(function () { _newFiles.push({ "src": $(this).attr('src'), "filename": $(this).attr('data-filename') }); });
			$(".new-link").each(function () { _newLinks.push({ "src": $(this).attr('data-link'), "link": $(this).attr('data-filename') }); });
			$(".del-file").each(function () { _delFiles.push({ "id": $(this).attr('data-id') }); });
			$(".del-link").each(function () { _delLinks.push({ "id": $(this).attr('data-id') }); });
			$(".new-message").each(function () { _newMessages.push({ "message": $(this).html() }); });

			/* MOD_FOLDERS */
			var _newFolderItems = [];
			$(".new-folder-item").each(function () {
				_newFolderItems.push(
					{
						"src": $(this).attr('data-result'),
						"filename": $(this).attr('data-filename'),
						"description": $(this).attr('data-description'),
						"keywords": $(this).attr('data-keywords'),
						"id_type_folder_item": $(this).attr('data-type'),
						"priority": $(this).attr('data-priority'),
					});
			});

			_jsonSave["new-files"] = _newFiles;
			_jsonSave["new-links"] = _newLinks;
			_jsonSave["del-files"] = _delFiles;
			_jsonSave["del-links"] = _delLinks;
			_jsonSave["new-messages"] = _newMessages;
			_jsonSave["new-folder-items"] = _newFolderItems;

			_jsonSave["id"] = _this.attr("data-id");
			_jsonSave["module"] = _this.attr("data-module");
			_jsonSave["model"] = _this.attr("data-model");
			_jsonSave["table"] = _this.attr("data-table");
			if (_this.attr("data-page") == undefined) { _this.attr("data-page", 1); };
			_jsonSave["page"] = _this.attr("data-page");
		} catch (rex) { };
		return _jsonSave;
	},
	UUID: function () {
		var s = [];
		var hexDigits = "0123456789abcdef";
		for (var i = 0; i < 36; i++) { s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1); }
		s[14] = "4";
		s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
		s[8] = s[13] = s[18] = s[23] = "-";
		var uuid = s.join("");
		return uuid;
	},
	iconByMime: function (_file_type, _data) {
		var _icon = "";
		switch (true) {
			case (_file_type.indexOf("image") != -1):
				_icon = "./assets/img/image.png";
				break;
			case (_file_type.indexOf("wav") != -1):
			case (_file_type.indexOf("mp3") != -1):
			case (_file_type.indexOf("audio") != -1):
				_icon = "./assets/img/audio.png";
				break;
			case (_file_type.indexOf("video") != -1):
			case (_file_type.indexOf("youtube") != -1):
			case (_file_type.indexOf("video") != -1):
				_icon = "./assets/img/video.png";
				break;
			case (_file_type.indexOf("pdf") != -1):
				_icon = "./assets/img/pdf.png";
				break;
			default:
				_icon = "./assets/img/file.png";
				break;
		}
		return _icon;
	},
	diffSeconds: function (_from, _to) {
		_from = moment(_from);
		_to = moment(_to);
		var _duration = moment.duration(_from.diff(_to));
		return _duration.asSeconds();
	},
	prettyPrint: function (obj) {
		return JSON.stringify(obj, undefined, 4);
	},
	NASort: function (a, b) {
		if (a.innerHTML == 'NA') {
			return 1;
		}
		else if (b.innerHTML == 'NA') {
			return -1;
		}
		return (a.innerHTML > b.innerHTML) ? 1 : -1;
	},
	replaceAll: function (str, find, replace) {
		return str.replace(new RegExp(find, 'g'), replace);
	},
	loadCombo: function (datajson, params) {
		return new Promise(
			function (resolve, reject) {
				try {
					$(params.target).empty();
					if (params.selected == -1) { $(params.target).append('<option selected value="-1">[Seleccione]</option>'); }
					$.each(datajson.data, function (i, item) {
						$(params.target).append('<option value="' + item[params.id] + '">' + item[params.description] + '</option>');
					});
					resolve(true);
				} catch (rex) {
					reject(rex);
				}
			});
	},
	loadBrowser: function (datajson, params) {
		return new Promise(
			function (resolve, reject) {
				try {
					var _full = false;
					var _html = "";
					$.each(datajson.data, function (i, item) {
						if (i == 0) {
							_full = true;
							_html += "<table class='table table-condensed'>";
							_html += " <thead>";
							_html += "  <tr>";
							$.each(params.cols, function (i, col) {
								_html += "<th><b>" + col.title + "</b></th>";
							});
							_html += "  </tr>";
							_html += " </thead>";
							_html += " <tbody>";
						}
						_html += "<tr>";
						$.each(params.cols, function (i, col) {
							_html += "<td>" + item[col.field] + "</td>";
						});
						_html += "</tr>";
					});
					if (_full) {
						_html += " </tbody>";
						_html += "</table>";
					}
					$(params.target).html(_html);
					resolve(true);
				} catch (rex) {
					reject(rex);
				}
			});
	},
	successTelemetry: function (position) {
		_TOOLS.latitude = position.coords.latitude;
		_TOOLS.longitude = position.coords.longitude;
		_TOOLS.altitude = position.coords.altitude;
		_TOOLS.accuracy = position.coords.accuracy;
		_TOOLS.heading = position.coords.heading;
		_TOOLS.speed = position.coords.speed;
		_TOOLS.timestamp = position.coords.timestamp;
	},
	errorTelemetry: function (error) {
		_TOOLS.latitude = null;
		_TOOLS.longitude = null;
		_TOOLS.altitude = null;
		_TOOLS.accuracy = null;
		_TOOLS.heading = null;
		_TOOLS.speed = null;
		_TOOLS.timestamp = null;
	},
	createFileItem: function(_name, _result) {
		var _id = _TOOLS.UUID();
		return "<li class='list-group-item attach " + _id + "' data-name='" + _name + "' data-url='" + _result + "' style='padding:10px;'>Se ha adjuntado <span class='badge badge-success'>" + _name + "</span><a href='#' class='btn btn-xs btn-deattach btn-danger pull-right' data-id='" + _id + "' style='margin:0px;'><i class='material-icons'>delete_forever</i></a></li>"
	},
	getTodayDate(format,sepChar) {
		//alert("a");
		var today = "";

		var d = new Date();
		var month = '' + (d.getMonth() + 1);
		var day = '' + d.getDate();
		var year = d.getFullYear();

		if (month.length < 2) { month = '0' + month; }
		if (day.length < 2) { day = '0' + day; }

		if (format == "" || format == null) {
			today = [month, day, year].join(sepChar);
		} else if (format == "ar" || format == "es") {
			today = [day, month, year].join(sepChar);
		} else if (format == "amd" || format == "YMD" || format == "ymd" || format == "AMD") {
			//alert("aammdd");
			today = [year, month, day].join(sepChar);
		} else if (format == "dmy" || format == "DMY" || format == "DMA" || format == "dma") {
			today = [day, month, year].join(sepChar);
		} else if (format == "mda" || format == "MDA" || format == "mdy" || format == "MDY") {
			today = [month, day, year].join(sepChar);
		}
		
		//alert("b");
		return today;
	},


	nullToEmpty: function (cadena) {

		if (cadena==null || cadena == "null" || cadena == "undefined" || cadena === "" ) { return ""; } else { return cadena; }

	},
	getFormattedDate(fec, format, sepChar) {
		//alert("a");
		var today = "";

		var d = fec;
		var month = '' + (d.getMonth() + 1);
		var day = '' + d.getDate();
		var year = d.getFullYear();

		if (month.length < 2) { month = '0' + month; }
		if (day.length < 2) { day = '0' + day; }

		if (format == "" || format == null) {
			today = [month, day, year].join(sepChar);
		} else if (format == "ar" || format == "es") {
			today = [day, month, year].join(sepChar);
		} else if (format == "amd" || format == "YMD" || format == "ymd" || format == "AMD") {
			//alert("aammdd");
			today = [year, month, day].join(sepChar);
		} else if (format == "dmy" || format == "DMY" || format == "DMA" || format == "dma") {
			today = [day, month, year].join(sepChar);
		} else if (format == "mda" || format == "MDA" || format == "mdy" || format == "MDY") {
			today = [month, day, year].join(sepChar);
		}

		//alert("b");
		return today;
	},
	getTextAsFormattedDate(fec, format, sepChar) {
		//alert("a");
		var today = "";

		/*completo con la hora xq sino no pone bien el dia... resta 1....*/
		if (fec.length < 11) { fec = fec + ' 00:00:00';}

		const d = new Date(fec);
		//var d = fec;
		var month = '' + (d.getMonth() + 1);
		var day = '' + d.getDate();
		var year = d.getFullYear();

		if (month.length < 2) { month = '0' + month; }
		if (day.length < 2) { day = '0' + day; }

		if (format == "" || format == null) {
			today = [month, day, year].join(sepChar);
		} else if (format == "ar" || format == "es") {
			today = [day, month, year].join(sepChar);
		} else if (format == "amd" || format == "YMD" || format == "ymd" || format == "AMD") {
			//alert("aammdd");
			today = [year, month, day].join(sepChar);
		} else if (format == "dmy" || format == "DMY" || format == "DMA" || format == "dma") {
			today = [day, month, year].join(sepChar);
		} else if (format == "mda" || format == "MDA" || format == "mdy" || format == "MDY") {
			today = [month, day, year].join(sepChar);
		}

		//alert("b");
		return today;
	},


	getTextBox: function (nombre, descripcion, largo, valor,hasDiv, classText) {

		var _html = "";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		_html += "<label for='LBL-" + nombre + "'>" + descripcion + "</label>";
		//_html += "&nbsp;&nbsp;";

		if (valor == null) { valor = "";}

		_html += "<input data-type='text' autocomplete='nope' value='" + valor + "' " + classText + " type='text' name='TB-" + nombre + "' id='TB-" + nombre + "' data-clear-btn='false' placeholder='" + descripcion + "'>";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
			_html += "</div>";
		}
		return _html;
	},
	getTextArea: function (nombre, descripcion, largo, valor, hasDiv, classText,rows,cols) {

		var _html = "";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		_html += "<label for='LBL-" + nombre + "'>" + descripcion + "</label>";
		//_html += "&nbsp;&nbsp;";

		if (valor == null) { valor = ""; }

		_html += "<textarea data-type='text' rows='"+rows+"' cols='"+cols+"' autocomplete='nope' " + classText + " name='TB-" + nombre + "' id='TB-" + nombre + "' data-clear-btn='false' placeholder='" + descripcion + "'>"+valor+"</textarea>";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
			_html += "</div>";
		}
		return _html;
	},
	getNumberBox: function (nombre, descripcion, largo, valor, hasDiv, classText) {

		var _html = "";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		_html += "<label for='LBL-" + nombre + "'>" + descripcion + "</label>";
		//_html += "&nbsp;&nbsp;";

		if (valor == null) { valor = "0"; }

		_html += "<input data-type='number' autocomplete='nope' value='" + valor + "' " + classText + " type='number' name='TB-" + nombre + "' id='TB-" + nombre + "' data-clear-btn='false' placeholder='" + descripcion + "'>";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
			_html += "</div>";
		}
		return _html;
	},
	getDateBox: function (nombre, descripcion, largo, valor, hasDiv, classText) {

		var _html = "";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		_html += "<label for='LBL-" + nombre + "'>" + descripcion + "</label>";
		//_html += "&nbsp;&nbsp;";

		if (valor == null) { valor = ""; }

		_html += "<input data-type='date' autocomplete='nope' value='" + valor + "' " + classText + " type='date' name='TB-" + nombre + "' id='TB-" + nombre + "' data-clear-btn='false' placeholder='" + descripcion + "'>";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
			_html += "</div>";
		}
		return _html;
	},
	getDateBoxWithOutLabel: function (nombre, descripcion, largo, valor, hasDiv, classText) {

		var _html = "";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		
		//_html += "&nbsp;&nbsp;";

		if (valor == null) { valor = ""; }

		_html += "<input data-type='date' autocomplete='nope' value='" + valor + "' " + classText + " type='date' name='TB-" + nombre + "' id='TB-" + nombre + "' data-clear-btn='false' placeholder='" + descripcion + "'>";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
			_html += "</div>";
		}
		return _html;
	},
	getTimeBox: function (nombre, descripcion, largo, valor, hasDiv, classText, step) {

		var _html = "";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		_html += "<label for='LBL-" + nombre + "'>" + descripcion + "</label>";
		//_html += "&nbsp;&nbsp;";

		if (valor == null) { valor = ""; }

		_html += "<input data-type='time' step='"+step+"' autocomplete='nope' value='" + valor + "' " + classText + " type='time' name='TB-" + nombre + "' id='TB-" + nombre + "' data-clear-btn='false' placeholder='" + descripcion + "'>";
		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
			_html += "</div>";
		}
		return _html;
	},
	compareDates: function (a, b) {
		// Compare two dates (could be of any type supported by the convert
		// function above) and returns:
		//  -1 : if a < b
		//   0 : if a = b
		//   1 : if a > b
		// NaN : if a or b is an illegal date
		// NOTE: The code inside isFinite does an assignment (=).
		return (
			isFinite(a = this.convert(a).valueOf()) &&
				isFinite(b = this.convert(b).valueOf()) ?
				(a > b) - (a < b) :
				NaN
		);
	},

	limitText: function (limitField, limitCount, limitNum) {

		if (limitField.value.length > limitNum) {
			if (limitCount == null) {
				alert("Llegó al máximo de " + limitNum + " caracteres permitidos");
			}
			limitField.value = limitField.value.substring(0, limitNum);
		} else {
			if (limitCount != null) {
				limitCount.value = limitNum - limitField.value.length;
			}
		}
	},

	getRadioButton: function (nombre,id, descripcion, valor,additionalStr) {

		var _html = "";
		_html += "<span id='LBLR-" + nombre + "'>" + descripcion + "</span>";
		_html += "<input id='" + id + "' type='radio' name='" + nombre + "' value='" + valor + "' " + additionalStr + ">"; 
		return _html;
	},
	getRadioYesNoButton: function (nombre, descripcion, largo, selected,yesval,noval,yeslbl,nolbl) {

		var _html = "";
		_html += "<div class='col-md-" + largo + "'>";
		_html += "<label for='LBL-" + nombre + "'>" + descripcion + "</label>";
		var noextra = "";
		var yesextra = "";
		if (selected == noval) { noextra = " checked "; }
		if (selected == yesval) { yesextra = " checked "; }
		_html += "&nbsp;&nbsp;";
		_html += this.getRadioButton(nombre, nombre + "_s", yeslbl, yesval, yesextra);
		_html += "&nbsp;&nbsp;";
		_html += this.getRadioButton(nombre, nombre + "_n", nolbl, noval, noextra);
		_html += "<div class='invalid-feedback invalid-TB" + nombre + " d-none'></div>";
		_html += "</div>";
		return _html;
	},
	getComboFromJson: function (datajson, params,hasDiv,largo,nombre,descripcion,classTExt) {
		
		var _html="";
		try {
			if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
				
				_html += "<div class='col-md-" + largo + "'>";
				
			}
			if (descripcion!="")
			{
				_html += "<span id='LBL-" + nombre + "'>" + descripcion + "</span>";
			}
			
			_html += "<select " + classTExt + " id='" + nombre + "'>";
			

		

			if (params.selected == -1) { _html +='<option selected value="-1">[Seleccione]</option>'; }
		
		
			$.each(datajson, function (i, item) {
				_html +='<option value="' + item[params.id] + '">' + item[params.description] + '</option>';
				//alert("combo: "+_html);
			});
		
			
			_html += "</select>";

			if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
				_html += "</div > ";
			}
			return _html;
		} catch (rex) {
			//alert("por error");
			return "";
		}
		
	},
	getComboFromList: function (nombre, descripcion, largo, selected, valList, displayList, hasEmptyOption, emptyText,hasDiv, classTExt, readonlyFlag) {
		var valArray = valList.split(",");
		var disArray = displayList.split(",");
		var i = 0;
		var _html = "";
		var selectedText = "";

		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "<div class='col-md-" + largo + "'>";
		}
		_html += "<span id='LBL-" + nombre + "'>" + descripcion + "</span>";
		_html += "<select " + classTExt + " id='" + nombre + "'>";
		if (hasEmptyOption == "Y" || hasEmptyOption == "S" || hasEmptyOption == "y" || hasEmptyOption == "s") {

			if (selected == "0") {
				selectedText = " selected ";
			}
			_html += "<option value='0' " + selectedText + ">" + emptyText + "</option > ";
		}
		for (i = 0; i < valArray.length; i++) {
			if (selected == valArray[i]) {
				selectedText = " selected ";
			}
			else {
				if (readonlyFlag == "Y" || readonlyFlag == "S" || readonlyFlag == "y" || readonlyFlag == "s") {
					selectedText = " disabled ";
				}
				else {
					selectedText = "";
				}
			}
			_html += "<option value='" + valArray[i] + "' " + selectedText + ">" + disArray[i] + "</option > ";

		}
		_html += "</select>";

		if (hasDiv == "Y" || hasDiv == "S" || hasDiv == "y" || hasDiv == "s") {
			_html += "</div > ";
		}
		return _html;


	},
	isPositiveInteger: function (value,elemId) {
		//alert(value);
		var myRe = /[^0-9]/;
		var text_is_invalid = myRe.test(value);

		if (text_is_invalid == false) {
			//alert("es valido");
			return true;
		} else {
			alert("El numero tiene que ser mayor o igual a cero!");
			//inputText.value.focus();
			setTimeout(function () {
				document.getElementById(elemId).focus();
			}, 0);

			return false;
		}
	} ,
	
	showNumber: function (num, dec, group, valueIfNull) {

		if ((num == null) || (num == "") || num == " ") { return valueIfNull; }

		var numShow = new String;
		var decimalsStr = new String;
		var integerStr = new String;
		var numberFormated = "";
		var separator = group;
		var isNegative = false;

		if (separator == ".") { decimalStr = ","; } else { decimalStr = "."; }

		if (isNaN(num)) {
			num = 0;
		}

		num = parseFloat(num);

		if (Math.abs(num) != num) {
			num = Math.abs(num);
			isNegative = true;
		}

		// Ajusto la cantidad de decimales y lo paso a string
		var tempNum = "";
		tempNum = num.toFixed(dec);

		var posDot = tempNum.indexOf(decimalStr);

		// Si no hay punto es porque no hay decimales
		if (posDot == -1) {
			decimalsStr = "";
			integerStr = tempNum;
		} else {
			decimalsStr = tempNum.slice(posDot + 1);
			integerStr = tempNum.slice(0, posDot);
		}

		var integerGroups = new Array();
		var tempIntegerStr;

		// Separo la parte entera en grupos de 3
		while (integerStr.length > 3) {
			tempIntegerStr = integerStr.slice(integerStr.length - 3, integerStr.length);

			integerStr = integerStr.slice(0, integerStr.length - 3);

			integerGroups[integerGroups.length] = tempIntegerStr;
		}

		// Si sobro algo lo mando al final
		if (integerStr != "") {
			integerGroups[integerGroups.length] = integerStr;
		}

		// Doy vuelta el array
		integerGroups.reverse();

		// Genero el string de vuelta
		if (isNegative) {
			numberFormated += "-";
		}
		if (dec == 0) {
			numberFormated += integerGroups.join(separator);
		} else {
			numberFormated += integerGroups.join(separator) + decimalStr + decimalsStr;
		}

		return numberFormated;
	},
	
	showNumber2: function (num, dec, group, valueIfNull) {

		if ((num == null) || (num == "") || num == " ") { return valueIfNull; }

		var numShow = new String;
		var decimalsStr = new String;
		var integerStr = new String;
		var numberFormated = "";
		var separator = group;
		var isNegative = false;

		if (separator == ".") { decimalsStr = ","; } else { decimalsStr = "."; }

		if (isNaN(num)) {
			num = 0;
		}

		num = parseFloat(num);

		if (Math.abs(num) != num) {
			num = Math.abs(num);
			isNegative = true;
		}

		// Ajusto la cantidad de decimales y lo paso a string
		var tempNum = "";
		tempNum = num.toFixed(dec);

		var posDot = tempNum.indexOf(decimalsStr);

		// Si no hay punto es porque no hay decimales
		if (posDot == -1) {
			decimalsStr = "";
			integerStr = tempNum;
		} else {
			decimalsStr = tempNum.slice(posDot + 1);
			integerStr = tempNum.slice(0, posDot);
		}

		var integerGroups = new Array();
		var tempIntegerStr;

		// Separo la parte entera en grupos de 3
		while (integerStr.length > 3) {
			tempIntegerStr = integerStr.slice(integerStr.length - 3, integerStr.length);

			integerStr = integerStr.slice(0, integerStr.length - 3);

			integerGroups[integerGroups.length] = tempIntegerStr;
		}

		// Si sobro algo lo mando al final
		if (integerStr != "") {
			integerGroups[integerGroups.length] = integerStr;
		}

		// Doy vuelta el array
		integerGroups.reverse();

		// Genero el string de vuelta
		if (isNegative) {
			numberFormated += "-";
		}
		if (dec == 0) {
			numberFormated += integerGroups.join(separator);
		} else {
			numberFormated += integerGroups.join(separator) + decimalsStr;
		}

		return numberFormated;
	},
	formatNumber: function (format, num, isCurrency) {
		alert("a");
		var Nume = num;

		if (isCurrency == "Y" || isCurrency == "S" || isCurrency == "y" || isCurrency == "s") {

			if (format == "ar" || format == "es") {
				alert("ar antes");
				Nume = new Intl.NumberFormat("es-ES", { style: "currency" }).format(num);
				alert("ar despues");
			}
		}
		else {
			if (format == "ar" || format == "es") {
				alert("ar antes");
				Nume = new Intl.NumberFormat("es-ES").format(num);
				alert("ar despues");
			}
		}
		alert("b");
		return Nume;
	},

	numberWithLeadingZeros: function (num, size) {
		num = num.toString();
		while (num.length < size) num = "0" + num;
		return num;
	},

	numberWithLeadingZerosFixed: function (num, size) {
		var s = "00000000" + num;
		return s.substr(s.length - size);
	},

	numeroAtexto: function (n) {

		var o = new Array("diez", "once", "doce", "trece", "catorce", "quince", "dieciséis", "diecisiete", "dieciocho", "diecinueve", "veinte", "veintiuno", "veintidós", "veintitrés", "veinticuatro", "veinticinco", "veintiséis", "veintisiete", "veintiocho", "veintinueve");
		var u = new Array("cero", "uno", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve");
		var d = new Array("", "", "", "treinta", "cuarenta", "cincuenta", "sesenta", "setenta", "ochenta", "noventa");
		var c = new Array("", "ciento", "doscientos", "trescientos", "cuatrocientos", "quinientos", "seiscientos", "setecientos", "ochocientos", "novecientos");

		var n = parseFloat(n).toFixed(2); /*se limita a dos decimales, no sabía que existía toFixed() :)*/
		var p = n.toString().substring(n.toString().indexOf(".") + 1); /*decimales*/
		var m = n.toString().substring(0, n.toString().indexOf(".")); /*número sin decimales*/
		var m = parseFloat(m).toString().split("").reverse(); /*tampoco que reverse() existía :D*/
		var t = "";

		/*Se analiza cada 3 dígitos*/
		for (var i = 0; i < m.length; i += 3) {
			var x = t;
			/*formamos un número de 2 dígitos*/
			var b = m[i + 1] != undefined ? parseFloat(m[i + 1].toString() + m[i].toString()) : parseFloat(m[i].toString());
			/*analizamos el 3 dígito*/
			t = m[i + 2] != undefined ? (c[m[i + 2]] + " ") : "";
			t += b < 10 ? u[b] : (b < 30 ? o[b - 10] : (d[m[i + 1]] + (m[i] == '0' ? "" : (" y " + u[m[i]]))));
			t = t == "ciento cero" ? "cien" : t;
			if (2 < i && i < 6)
				t = t == "uno" ? "mil " : (t.replace("uno", "un") + " mil ");
			if (5 < i && i < 9)
				t = t == "uno" ? "un millón " : (t.replace("uno", "un") + " millones ");
			t += x;
			//t=i<3?t:(i<6?((t=="uno"?"mil ":(t+" mil "))+x):((t=="uno"?"un millón ":(t+" millones "))+x));
		}

		t += " con " + p + "/100";
		/*correcciones*/
		t = t.replace("  ", " ");
		t = t.replace(" cero", "");
		//t = t.replace(" Cinco Cientos", " quiñentos");
		//t = t.replace(" Uno Cientos", " Ciento");
		//t=t.replace("ciento y","cien y");
		//alert("Numero: "+n+"\nNº Dígitos: "+m.length+"\nDígitos: "+m+"\nDecimales: "+p+"\nt: "+t);
		//document.getElementById("esc").value=t;
		return t;
	},


	utf8_to_b64: function (str) { return window.btoa(unescape(encodeURIComponent(str))); },
	b64_to_utf8: function (str) { str = str.replace(/\s/g, ''); return decodeURIComponent(escape(window.atob(str))); },

	formatDateDDMMYYYY: function (d) {
		//Date.parse(new Date()).toString('yyyy-MM-dd H:i:s')
		return Date.parse(d).toString('dd/MM/yyyy');
	},
	formatYYYYMMDD: function (d) {
		// d: 'YYYY-MM-DD HH:MM:SS'
		// -> 'YYYY-MM-DD'
		let fecha = d.split(" ");
		let formated = fecha[0];
		return formated; // YYYY-MM-DD
	},
	formatMMYYYY: function (fechaHora, outSeparator) {
		// d: 'YYYY-MM-DD HH:MM:SS'      sep: '/'
		// -> MM/YYYY
		let fecha = fechaHora.substring(0,10).split("-");
		if (outSeparator === null || outSeparator === 'null' || outSeparator == 'undefined') {
			outSeparator = '/';
		}
		let formated = fecha[1] + outSeparator + fecha[0];
		return formated; // MM/YYYY
	},
	formatDDMMYYYY: function (fechaHora, outSeparator) {
		// d: 'YYYY-MM-DD HH:MM:SS'      sep: '/'
		// -> DD/MM/YYYY
		if (fechaHora!==null){
			let fecha = fechaHora.substring(0,10).split("-");
			if (outSeparator === null || outSeparator === 'null' || outSeparator == 'undefined') {
				outSeparator = '/';
			}
			let formated = fecha[2] + outSeparator + fecha[1] + outSeparator + fecha[0];
			return formated; // DD/MM/YYYY
		} else {
			return "";
		}
	},
	formatDDMMYYYYHHMM: function (fechaHora, outSeparator) {
		// d: 'YYYY-MM-DD HH:MM:SS'      sep: '/'
		// -> DD/MM/YYYY HH:MM
		if (fechaHora!==null){
			if (outSeparator === null || outSeparator === 'null' || outSeparator == 'undefined') {
				outSeparator = '/';
			}
			let parteFecha = fechaHora.split(" ")[0].substring(0,10).split("-");
			let parteHora = fechaHora.split(" ")[1].split(":");
			return  parteFecha[2] + outSeparator + parteFecha[1] + outSeparator + parteFecha[0] + " " + parteHora[0] + ":" + parteHora[1]; // DD/MM/YYYY HH:MM
		} else {
			return "";
		}
	},
	htmlToText: function (html) {
		// remueve los tag html de un string
		return  html.replace(/<\/?("[^"]*"|'[^']*'|[^>])*(>|$)/g, "");
	},
   /**
	* String Builder Implementation
	*
	* 	var sb = new StringBuilder();
	*	sb.append("This is");
	*	var myString = sb.toString();
	*	sb.clear();
	*
	* @url https://stackoverflow.com/questions/2087522/does-javascript-have-a-built-in-stringbuilder-class 
	*	 
	* @param mixed string a concatenar.
	*/
	//https://stackoverflow.com/questions/2087522/does-javascript-have-a-built-in-stringbuilder-class
	StringBuilder: function (value) {
	
		this.strings = new Array();
		this.append(value);
		StringBuilder.prototype.append= function (value) {
			var _s = "";
			if (value) {

				if (arguments.length>1) {
					for (var i = 1; i < arguments.length; i++) {
						//x = x.replace(/\{0\}/g, "John");
						//var _x = new RegExp('^(\\d+) users');
						//new RegExp('^(\\d+) users')
						var _rs = "{" + (i-1).toString() + "}";
						var _r = new RegExp(_rs, "g");
						_s =  value.replace(_r, arguments[i]);
					}
				} else {
					_s=value
				}

				this.strings.push(_s);
			}
		}
		StringBuilder.prototype.clear = function () {
			this.strings.length = 0;
		}
		StringBuilder.prototype.toString = function () {
			return this.strings.join("");
		}

	},
};
