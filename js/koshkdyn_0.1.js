
if (SYS.locale.dyn == undefined) {
	SYS.locale.dyn = {}
}
if (SYS.cfg.dyn == undefined) {
	SYS.cfg.dyn = {
		apiUrl: 'index.php'
	}
}
(function() {
	var dynloc = SYS.locale.dyn
	if (dynloc == undefined) {
		SYS.locale.dyn = {}
	}
})();

SYS.initializations.addLast(function() {
	if (window.setLoading == undefined) {
		window.loadingOverlay = document.body.appendTag('div')
		window.loadingOverlay.classList.add('loadingOverlay')
		window.loadingOverlay.style.display = 'none'
		window.loadingOverlay.appendText('Загрузка...')
		window.setLoading = function() {
			this.loadingOverlay.style.display = 'block'
		}
		window.unsetLoading = function() {
			this.loadingOverlay.style.display = 'none'
		}
	}
})

function api_request(options) {
	if (options == undefined) {
		showMessageBox('NO REQUEST OPTIONS', 'error');
		return null;
	}
	var hasOnSuccess = options.onSuccess !== undefined;
	var hasOnFail = options.onFail !== undefined;
	if (hasOnSuccess && (typeof options.onSuccess !== 'function')) {
		showMessageBox('THE GIVEN onSuccess IS NOT A FUNCTION', 'error');
		return null;
	}
	if (hasOnFail && (typeof options.onFail !== 'function')) {
		showMessageBox('THE GIVEN onFail IS NOT A FUNCTION', 'error');
		return null;
	}
	var waitForResponce = false;
	if (options.waitForResponce || options.wait) {
		waitForResponce = true;
		setLoading('Обработка запроса');
	}
	var ajaxParams = {
		type: "POST",
		url: options.apiUrl ? options.apiUrl : SYS.cfg.dyn.apiUrl,
		dataType: 'json',
		data: options.options,
		success: function(data) {
			if (data = (options.dull !== undefined && options.dull) ? extractData(data, false, true) : extractData(data)) {
				if (hasOnSuccess) options.onSuccess(data);
			} else {
				if (hasOnFail) options.onFail(data);
			}
			if (waitForResponce) {
				unsetLoading();
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			if (hasOnFail) {
				options.onFail(errorThrown);
			}
			if (waitForResponce) {
				unsetLoading();
			}
		}
	}
	if (options.options instanceof FormData) {
		ajaxParams.processData = false
        ajaxParams.contentType = false
	}
	$.ajax(ajaxParams);
}

var APPDATA = {
	readyness: {total: 0, complete: 0, on_ready: null}
	, check: function() {
		this.readyness.complete++;
		loadingProgressBarFiller.style.width = '' + Math.floor((100 / this.readyness.total) * this.readyness.complete) + '%'
		if (this.readyness.complete === this.readyness.total) {
			var onredy = this.readyness.on_ready
			if (typeof onredy == 'function') {
				onredy(this);
			} else if ((typeof onredy == 'object') && (onredy instanceof Array)) {
				for (var ifigfdi = 0; ifigfdi < onredy.length; ifigfdi++) {
					if (typeof onredy[ifigfdi] == 'function') {
						onredy[ifigfdi](this);
					}
				}
			}
		}
	}
	, reload: function(set_name, force_reload = false) {
		if (this[set_name] === undefined || force_reload) {
			var acd = this;
			api_request({
				options : {
					action : 'get_dataset'
					, setName: set_name
				}, onSuccess : function(data) {
					acd[set_name] = {}
					acd.processData(set_name, data)
					if (typeof callback == 'function') {
						callback(acd[set_name])
					}
				}
			})
		}
	}
	, load: function(sets, on_ready) {
		setLoading()
		this.readyness.complete = 0
		this.readyness.total = sets.length
		this.readyness.on_ready = on_ready
		for (var i = 0; i < sets.length; i++) {
			var set_name = sets[i]
			if (this[set_name] === undefined) {
				this[set_name] = {}
				var acd = this;
				api_request({
					options : {
						action : 'get_dataset'
						, setName: set_name
					}, onSuccess : function(data){
						acd.processData(set_name, data)
						acd.check();
					}
				})
			} else {
				this.check();
			}
		};
	}
	, processData: function(set_name, data) {
		var ds = this[set_name]
		if (data.length < 1) return;
		if ((typeof data[0] == 'object') && data[0].hasOwnProperty('id')) {
			for(var i = 0; i < data.length; i++) {
				var key = data[i]['id']
				ds[key] = data[i]
			}
		} else {
			this[set_name] = data
		}
	}
}

function extractData(rawdata, silent = false, dull = false) {
	if (rawdata && (typeof rawdata === "object")){
		if (rawdata.success === false) {
			if (!silent) {
				if (rawdata.message)
					showMessageBox(rawdata.message, 'error');
				else
					showMessageBox('Error #101.', 'error');
			}
			return false;
		} else if (rawdata.success === true) {
			if ((rawdata.data !== undefined) && !dull)
				return rawdata.data;
			else
				return rawdata;
		} else {
			showMessageBox('Error #102.', 'error');
		}
	} else {
		var data;
		try {
			data = JSON.parse(rawdata, true);
		} catch (ex) {
			showMessageBox(rawdata);
			return false;
		}
		if (data.success === false) {
			if (!silent) {
				if (data.message)
					showMessageBox(data.message, 'error');
				else
					showMessageBox('Error #103.', 'error');
			}
			return false;
		} else {
			if ((data.data !== undefined) && !dull)
				return data.data;
			else
				return data;
		}
	}
	return false;
}