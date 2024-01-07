/**************************
 *                        *
 *    REQUIRES JQUERY     *
 *                        *
 *   -----------------    *
 *                        *
 *   REQUIRES BOOTSTRAP   *
 *   (что было ошибкой)   *
 *                        *
 **************************/

/********************************************************
 *														*
 *   SYS OBJECT DOC:									*
 *														*
 *		locale:	строки, которые должны изменяться		*
 *				в зависимости от языка или приложения	*
 *														*
 *		cfg:											*
 *			dialogHeightMargin:	отступ сверху страницы	*
 *								перед диалоговым окном	*
 *			handleErrors:	будут ли все ошибки			*
 *							выводиться в диалоге вместо	*
 *							консоли						*
 *														*
 *		initializations:	список функций, которые		*
 *							должны быть вызваны после	*
 *							загрузки окна				*
 *														*
 ********************************************************/

var TODAY_DATE = new Date().toLocaleDateString('ru-RU')

if (!jQuery.fn.deferred) {
	jQuery.fn.deferred = function(func, args) {
		var _this = this
		requestAnimationFrame(function () {
			func.apply(_this, args)
		}, this[0])
		return this
	};
};

HTMLElement.prototype.appendTag = function(tag){
	return this.appendChild(document.createElement(tag))
}
HTMLElement.prototype.prependTag = function(tag){
	return this.insertBefore(document.createElement(tag), this.firstChild)
}
HTMLElement.prototype.appendText = function(text){
	return this.appendChild(document.createTextNode(text))
}
HTMLElement.prototype.getOffsetRect = function(){
	var box = this.getBoundingClientRect()
	var scrollTop = window.pageYOffset
	var scrollLeft = window.pageXOffset
	var top  = box.top + scrollTop
	var left = box.left + scrollLeft
	return {
		top: top - top % 1
		, left: left - left % 1
	}
}
if (!Array.prototype.remove) {
	Array.prototype.remove = function (element) {
		var idx = this.indexOf(element)
		if (idx > -1) {
			this.splice(idx, 1)
		}
		return idx;
	};
}
if (!Array.removeAt) {
	Array.prototype.removeAt = function (idx) {
		if (idx > -1 && idx < this.length) {
			return this.splice(idx, 1)
		}
		return [];
	};
}
$.fn.appendText = function (text) {
	return this.each(function () {
		var textNode = document.createTextNode(text);
		this.append(textNode);
	});
};
$.fn.setLines = function (lines) {
	if (!this.length) return this
	if (typeof lines == 'string') {
		lines = lines.split(/\r\n|\n|<br>/)
	}
	this.contents().remove()

	for (var i = 0; i < lines.length; i++) {
		if (i > 0) {
			this.append($('<br>'))
		}
		this.appendText(lines[i])
	}
	return this
};

function isArray(obj, notEmpty) {
	return (typeof obj == 'object') && (obj instanceof Array) && (!notEmpty || obj.length > 0)
}

function assertString(obj, def) {
	return typeof obj == 'string' ? obj : def
}

function createEventQueue(target) {
	var _eventQueue = []
	var _target = target
	var _eventQueueFunc = function(handler) {
		if (arguments.length) {
			if (typeof handler == 'function') {
				_eventQueue.push(handler)
			}
		}
		else if (_eventQueue.length > 0) {
			_eventQueue.forEach(f => f(_target))
		}
	}
	return _eventQueueFunc
}

function unix_to_date(unix_timestamp, flag) {
	var timestamp = isNaN(unix_timestamp) ? unix_timestamp : (unix_timestamp * 1000)
	var date = new Date(timestamp)
	switch (flag) {
		case 'todaytime' : 
			var res = date.toLocaleString('ru-RU').split(', ')
			if (res[0] == TODAY_DATE) {
				return res[1]
			}
			return res[0]
		case 'time' : 
			return date.toLocaleTimeString('ru-RU')
		case 'date' : 
			return date.toLocaleDateString('ru-RU')
		default:
			return date.toLocaleString('ru-RU')
	}
}

function typeCheck(v, t, n) {
	if (typeof v != t) {
		console.error(n + ' is not a ' + t)
		return false
	}
	return true
}

/*
	file: $('input[type="file"]')[0].files[0]
*/
function getFileBase64(file, onLoad, onError) {
	onError = onError || function(error) {
		console.warn('Error: ', error)
	}
	let reader = new FileReader()
	reader.readAsDataURL(file)
	reader.onload = onLoad
	reader.onerror = onError
}

var SYS = {
	locale: {}
	, cfg: {
		handleErrors: false
	}
	, initializations: (function () {
		var initList = null
		if (window.List) {
			initList = new List()
			$(function () {
				var initfunc = SYS.initializations.head
				while (initfunc != null) {
					if (typeof initfunc.value == 'function') {
						initfunc.value()
					}
					else {
						console.error('SYS.initializations contains non-functions')
					}
					initfunc = initfunc.next
				}
			})
		}
		else {
			initList = new (function () {
				this.list = []
				this.addLast = function(func) {
					this.list.push(func)
				}
				this.addFirst = function(func) {
					this.list.unshift(func)
				}
			})();
			$(function () {
				var initfunc;
				for (var i = 0; i < initList.length; i++) {
					initfunc = initList[i]
					if (typeof initfunc == 'function') {
						initfunc()
					}
					else {
						console.error('SYS.initializations contains non-functions')
					}
				}
			})
		}
		initList.addLast(function() {
			if (typeof $.fn.tooltip == 'function') {
				$('[data-toggle="tooltip"]').tooltip()
			}
			
			//if (!kbImageOverlay.initialized) {
			//	initializeImageOverlay()
			//}
			$('div[kbSpoiler]').each(function() {
				makeSpoiler(this);
			});

			if (SYS.handleErrors) {
				window.onerror = function(msg, url, line, col, error) {
					// Note that col & error are new to the HTML 5 spec and may not be 
					// supported in every browser.
					var extra = !col ? '' : '<br>column: ' + col
					if (error) extra += '<br>error: ' + error
			
					// You can view the information in an alert to see things working like this:
					showMessageBox("Error: " + msg + "<br><br>in " + url + "<br>at line " + line + extra, 'error');
					return true
				}
			}
		})
		return initList
	})()
}

function getRandomInt(min, max) {
	return Math.floor(Math.random() * (max - min)) + min;
}

function KBPoint(itop, ileft) {
	this.top = itop;
	this.left = ileft;
}

function kbException(message) {
	this.message = message
	this.level = 0
	if (arguments.length > 1) {
		if (arguments[1])
			this.level = 1
	}
	this.toString = function() { return this.message; }
}

function processException(e) {
	if (e instanceof kbException) {
		if (e.level)
			console.warn(e.message)
		else
			console.error(e.message)
	} else {
		throw e
	}
}

/*var kbImageOverlay = {
	initialized: false
	, refreshData: function() {
		alert("Инициализация ещё не завершена, подождите буквально секунду")
	}
	, showImage: function() {
		alert("Инициализация ещё не завершена, подождите буквально секунду")
	}
}*/

$.fn.KBDropdown = function (params) {
	if (!params) return this
	var items, activeClass, _value, selectedIdx = -1
	var _dropdown = this
	var onChange = []

	if (params.Items) {
		if (typeof params.SelectedIdx == 'number') {
			selectedIdx = params.SelectedIdx
		}
		items = params.Items
	} else {
		items = params
	}
	if (typeof items == 'object' && items instanceof Array && items.length) {
		if (typeof items[0] != 'object') {
			var normalized = []
			for (var i = 0; i < items.length; i++) {
				normalized.push({ Value: items[i] })
			}
			items = normalized
		}

		this.addClass('kbDropdown').css({ position: 'relative' })
		var kbDropdownValue = $('<div class="kbDropdownValue inactive">').appendTo(this).css({ width: '100%', height: '100%' }).click(function() {
			showOptions()
		})
		var kbDropdownOptions = $('<div class="kbDropdownOptions">').appendTo(this).css({ position: 'absolute', zIndex: '1' }).hide()
		
		for (var i = 0; i < items.length; i++) {
			var item = items[i]
			var option = $('<div class="kbDropdownOption">').appendTo(kbDropdownOptions).text(item.Text || item.Value)
			if (item.Class) {
				option.addClass(item.Class)
			}
			option[0]._ValIdx = i
			option.click(function() {
				hideOptions()
				_dropdown.SetValue(this._ValIdx)
			})
		}

		function hideOptions() {
			kbDropdownOptions.hide()
			kbDropdownValue.addClass('inactive')
			kbDropdownValue.removeClass('active')

			document.removeEventListener('click', _ddClick);
		}
		function showOptions() {
			var h = kbDropdownValue.outerHeight()
			kbDropdownOptions.css({ top: h + 'px', left: '0px' })
			kbDropdownOptions.show()
			kbDropdownValue.removeClass('inactive')
			kbDropdownValue.addClass('active')

			document.addEventListener('click', _ddClick);
		}
		function _ddClick(event) {
			if (!_dropdown.has(event.target).length) {
				hideOptions()
			}
		}

		_dropdown.SetValue = function(idx, suppressEvents) {
			_value = items[idx]
			selectedIdx = idx
			kbDropdownValue.text(_value.Text || _value.Value)

			if (activeClass) {
				kbDropdownValue.removeClass(activeClass)
				activeClass = null
			}
			if (_value.Class) {
				kbDropdownValue.addClass(_value.Class)
				activeClass = _value.Class
			}
			if (!suppressEvents && onChange.length > 0) {
				onChange.forEach(f => f(idx, _value))
			}
		}

		_dropdown.OnChange = function(handler) {
			if (typeof handler == 'function') onChange.push(handler)
		}

		if (selectedIdx > -1) {
			_dropdown.SetValue(selectedIdx, true)
		}

		Object.defineProperty(_dropdown, 'Value', {
			get: function() { return _value.Value }
			, set: function(newValue) {
				var idx = items.findIndex(x => x.Value == newValue)
				_dropdown.SetValue(idx)
			}
		});

		Object.defineProperty(_dropdown, 'RawValue', {
			get: function() { return _value }
		});
	}
	return this
};

function KBDialog(options) {
	var _isCentered = false
	var _title = ""
	var _titleClose = true
	var _hasButtons = false
	var _hasCustomClasses = false
	var _contentElement = null
	var _contentRenderer = null
	var _self = this
	var _dialogContainer, _dialogContent, _dialogTitle
	var _fadeIn = 160, _fadeOut = 100
	//var _beforeShow = []
	//var _afterShow = []
	//var _beforeHide = []
	//var _afterHide = []

	this.BeforeShow = createEventQueue(_self)
	this.AfterShow = createEventQueue(_self)
	this.BeforeHide = createEventQueue(_self)
	this.AfterHide = createEventQueue(_self)

	if (options != null) {
		_isCentered = options.centered === true
		this.id = assertString(options.id, null)
		_title = assertString(options.caption, assertString(options.title, _title))
		_hasButtons = isArray(options.buttons, true)
		_hasCustomClasses = isArray(options.classes, true)
		_titleClose = options.closeBtn !== false
		if (typeof options.contentRenderer == 'function') {
			_contentRenderer = options.contentRenderer
		}
		var _content = options.content || options.contentElement
		if (typeof _content == 'object') {
			if (_content instanceof jQuery) {
				_contentElement = _content
			} else if (_content instanceof HTMLElement) {
				_contentElement = $(_content)
			}
		} else if (typeof _content == 'string') {
			_contentElement = $(_content)
		}

		if (options.fadeIn !== undefined && options.fadeIn !== true) {
			_fadeIn = options.fadeIn
		}
		if (options.fadeOut !== undefined && options.fadeOut !== true) {
			_fadeOut = options.fadeOut
		}

		if (options.dispose) {
			this.AfterHide(function() {
				_self.Dispose()	
			})
		}
	}

	/*this.BeforeShow = function(handler) {
		if (typeof handler == 'function') _beforeShow.push(handler)
	}
	this.AfterShow = function(handler) {
		if (typeof handler == 'function') _afterShow.push(handler)
	}
	this.BeforeHide = function(handler) {
		if (typeof handler == 'function') _beforeHide.push(handler)
	}
	this.AfterHide = function(handler) {
		if (typeof handler == 'function') _afterHide.push(handler)
	}*/
	if (!this.hasOwnProperty('id') || this.id === null) {
		this.id = 'kbDialog' + getRandomInt(100000, 999999)
	}

	_dialogContainer = $('<div class="kb-dialog">').appendTo(document.body).attr('id', this.id)
	_dialogContent = $('<div class="content">')
	_dialogTitle = $('<div class="title">')

	_dialogContainer.append(
		$('<div class="overlay">')
		, $('<div class="dialog">').append(
			$('<div class="header">').append(
				_dialogTitle
			)
			, _dialogContent
		)
	)
	if (_titleClose) {
		$('<div class="closeBtn">').insertAfter(_dialogTitle).click(function() {
			_self.Hide()
		})
	}
	if (_isCentered) {
		_dialogContainer.addClass('centered')
	}
	if (_hasCustomClasses) {
		_dialogContainer.addClass(options.classes)
	}
	_dialogContainer.hide()
	
	this.Dispose = function() {
		_dialogContainer.remove()
		delete _self.BeforeShow
		delete _self.BeforeHide
		delete _self.AfterShow
		delete _self.AfterHide
	}

	this.$ = function(query) {
		return _dialogContent.find(query)
	}
	this.Show = function() {
		if (_contentElement) {
			this.SetContent(_contentElement)
		}
		_dialogTitle.text(_title)
		_self.BeforeShow()
		if (_contentRenderer) {
			_contentRenderer(_dialogContent, _dialogTitle, _dialogContainer)
		}
		if (_fadeIn) {
			_dialogContainer.fadeIn(_fadeIn, function() {
				_self.AfterShow()
			})
		} else {
			_dialogContainer.show()
			_self.AfterShow()
		}
	}
	this.Hide = function() {
		_self.BeforeHide()
		if (_fadeOut) {
			_dialogContainer.fadeOut(_fadeOut, function() {
				_self.AfterHide()
			})
		} else {
			_dialogContainer.hide()
			_self.AfterHide()
		}
	}

	this.SetTitle = function(title) {
		if (typeof title != 'object') {
			_title = title
			_dialogTitle.text(_title)
		}
		else {
			console.warn(this.id + ".setTitle : the given title is an object")
		}
	}
	this.SetText = function(text) {
		if (typeof text != 'object') {
			_dialogContent.text(text)
		}
		else {
			console.warn(this.id + ".setTitle : the given text is an object")
		}
	}
	this.SetContent = function(element) {
		if (typeof element == 'string') {
			element = $(element)
		}
		if (element.hasClass('kb-content-holder')) {
			_dialogContent.append(element.contents())
		} else {
			_dialogContent.append(element)
		}
	}

	if (_hasButtons) {
		let dialogFooter = $('<div class="footer">').insertAfter(_dialogContent)
		_dialogContainer.addClass('hasFooter')
		for (let j = 0; j < options.buttons.length; j++) {
			(function (buttonData) {
				if (typeof buttonData == 'object') {
					var dialogButton = $('<div class="button">').appendTo(dialogFooter)
					dialogButton.text(assertString(buttonData.caption, assertString(buttonData.text)))
					
					var action = buttonData.action || buttonData.onclick
					if (action) {
						if (typeof action == 'string') {
							if (action == 'close') {
								dialogButton.click(function() { _self.Hide() })
							} else {
								console.warn(this.id + " add button : unknown button string action: \"" + action + "\"")
							}
						} else if (typeof action == 'function') {
							dialogButton.click(function() { action(_self) })
						} else {
							console.warn(this.id + " add button : the given button string action is neither a string nor a function")
						}
					} else {
						dialogButton.click(function() { _self.Hide() })
					}
					if (buttonData.primary) dialogButton.addClass('primary')
					if (buttonData.secondary) dialogButton.addClass('secondary')
					if (isArray(buttonData.classes, true)) {
						for (let jj = 0; jj < buttonData.classes.length; jj++) {
							dialogButton.addClass(buttonData.classes[jj])
						}
					}
				} else if (typeof buttonData == 'string') {
					$('<div class="button primary">').appendTo(dialogFooter).text(buttonData).click(function() { _self.Hide() })
				}
			})(options.buttons[j]);
		}
	}
}

function showImageOverlay(imageList) {
	if (typeof imageList != 'object') {
		return null
	}
	var fitByDefault = false
	var animationLock = 0.6
	if (!(imageList instanceof Array)) {
		fitByDefault = imageList.FitByDefault || false
		animationLock = imageList.AnimationLock || 0.6
		imageList = imageList.Dataset || imageList.Data
		if (!imageList || (typeof imageList != 'object')) {
			return null
		}
	}
	var _container$ = $('<div class="imageOverlay">').appendTo($(document.body)).kbImageSlider({ Dataset: imageList, AnimationLock: animationLock, FitByDefault: fitByDefault })
	_container$.css('z-index', '9000').hide()
	return _container$
}

/*function initializeImageOverlay() {
	kbImageOverlay.controls = {
		element: document.body.appendTag('table')
		, prepare: function() {
			this.element.classList.add('ImageOverlayControls')
			this.element.setAttribute('cellpadding', '0')
			this.element.setAttribute('cellspacing', '0')
			this.row1 = this.element.appendTag('tr')
			this.row2 = this.element.appendTag('tr')
			this.row3 = this.element.appendTag('tr')
			this.row4 = this.element.appendTag('tr')
			this.row5 = this.element.appendTag('tr')
			this.scalingBtn = this.row1.appendTag('td')
			this.row1col1 = this.row1.appendTag('td')
			this.row1col2 = this.row1.appendTag('td')
			this.row1col3 = this.row1.appendTag('td')
			this.closeBtn = this.row1.appendTag('td')
			this.row2col = this.row2.appendTag('td')
			this.left = this.row3.appendTag('td')
			this.centre = this.row3.appendTag('td')
			this.right = this.row3.appendTag('td')
			this.row4col = this.row4.appendTag('td')
			this.row5col = this.row5.appendTag('td')

			this.scalingBtn.classList.add('scalingBtn')
			this.row1col1.classList.add('row1col1')
			this.row1col2.classList.add('row1col2')
			this.row1col3.classList.add('row1col3')
			this.closeBtn.classList.add('closeBtn')
			this.row2col.setAttribute('colspan', '5')
			this.row4col.setAttribute('colspan', '5')
			this.row5col.setAttribute('colspan', '5')
			this.row2col.classList.add('row2')
			this.row4col.classList.add('row4')
			this.row5col.classList.add('row5')
			this.left.setAttribute('colspan', '2')
			this.right.setAttribute('colspan', '2')
			this.left.classList.add('left')
			this.right.classList.add('right')

			this.description = this.row5col.appendTag('span')
			this.description.classList.add('description')
			this.scalingBtn.naturalSize = false

			this.left.onclick = function() {
				kbImageOverlay.carousel.slideLeft()
			}
			this.right.onclick = function() {
				kbImageOverlay.carousel.slideRight()
			}
			this.closeBtn.onclick = function() {
				$(kbImageOverlay.controls.element).fadeOut(150)
				$(kbImageOverlay.carousel.active).fadeOut(150)
			}
			this.scalingBtn.onclick = function() {
				var c = kbImageOverlay.carousel
				this.naturalSize = !this.naturalSize
				if (this.naturalSize) {
					c.first.classList.add('naturalSize')
					c.second.classList.add('naturalSize')
					this.classList.add('naturalSize')
				} else {
					c.first.classList.remove('naturalSize')
					c.second.classList.remove('naturalSize')
					this.classList.remove('naturalSize')
				}
			}
			this.setDescription = function(descr) {
				if ((descr == null) || (typeof descr != 'string') || (descr.length == 0)) {
					this.description.style.display = 'none'
				} else {
					this.description.style.display = ''
					$(this.description).text(descr)
				}
			}
		}
	}
	kbImageOverlay.carousel = {
		first: document.body.appendTag('div')
		, second: document.body.appendTag('div')
		, prepare: function() {
			function makeImg(element) {
				element.classList.add('carouselDiv')
				element.classList.add('transitive')
				element.imageWrapper = element.appendTag('div')
				element.imageWrapper.classList.add('imageWrapper')
				element.image = element.imageWrapper.appendTag('img')
				element.image.classList.add('carouselImage')
			}
			makeImg(this.first)
			makeImg(this.second)
			this.second.style.display = 'block';
			this.active = this.first
			this.inactive = this.second
			this.inactive.style.left = '100%'
			this.data = kbImageOverlay.images
			this.current = {idx: 0, set: null}
		}
		, slideLeft: function() {
			this.current.idx--
			if (this.current.idx < 0) {
				this.current.idx = this.current.set.length - 1
			}
			this.inactive.remove()
			this.inactive.style.left = '-100%'
			var imgdata = this.current.set[this.current.idx]
			this.inactive.image.setAttribute('src', imgdata.src)
			kbImageOverlay.controls.setDescription(imgdata.descr)
			var ac = this.active
			this.active = this.inactive
			this.inactive = ac
			document.body.appendChild(this.active)
			setTimeout(function() {
				kbImageOverlay.carousel.inactive.style.left = '100%'
				kbImageOverlay.carousel.active.style.left = '0px'
			}, 5);
		}
		, slideRight: function() {
			this.current.idx++
			if (this.current.idx == this.current.set.length) {
				this.current.idx = 0
			}
			this.inactive.remove()
			this.inactive.style.left = '100%'
			var imgdata = this.current.set[this.current.idx]
			this.inactive.image.setAttribute('src', imgdata.src)
			kbImageOverlay.controls.setDescription(imgdata.descr)
			var ac = this.active
			this.active = this.inactive
			this.inactive = ac
			document.body.appendChild(this.active)
			setTimeout(function() {
				kbImageOverlay.carousel.inactive.style.left = '-100%'
				kbImageOverlay.carousel.active.style.left = '0px'
			}, 5);
		}
	}
	kbImageOverlay.images = {}
	kbImageOverlay.controls.prepare()
	kbImageOverlay.carousel.prepare()
	
	kbImageOverlay.registerImage = function(image) {
		var imgSrc = false;
		if (image == undefined) {
			console.error('addImage: image is undefined')
			return
		} else if (!((typeof image == 'object') && image instanceof HTMLElement)) {
			console.error('addImage: image is not an HTML element')
			return
		}
		if (image.tagName == 'IMG') {
			imgSrc = image.getAttribute('src')
		} else {
			imgSrc = image.style.backgroundImage
			if (imgSrc && imgSrc.length > 4) imgSrc = imgSrc.split('"')[1]
		}
		if (!imgSrc) {
			console.error('addImage: the element doesn\'t contain an image')
			return
		}

		var setname = image.getAttribute('kbSet')
		if (!setname) {
			var setname = image.getAttribute('kbImageSet')
			if (!setname) {
				console.error('addImage: kbSet is not defined for the image')
				return
			}
		}
		if (!this.images.hasOwnProperty(setname)) {
			this.images[setname] = []
		}
		this.images[setname].push({src: imgSrc, descr: image.getAttribute('title')})
		image.imgOverlayIdx = this.images[setname].length - 1
		image.imageSetName = setname
		image.onclick = function() {
			kbImageOverlay.showImage(this.imageSetName, this.imgOverlayIdx)
		}
	}

	kbImageOverlay.refreshData = function() {
		this.images = { _SINGLE: {src: null, descr: ''}}
		$('img[kbSet]').each(function() {
			kbImageOverlay.registerImage(this);
		});
		$('[kbImageSet]').each(function() {
			kbImageOverlay.registerImage(this);
		});
		this.carousel.data = this.images
	}

	kbImageOverlay.showImage = function(setname, idx) {
		if (typeof setname == 'object' && setname instanceof HTMLElement) {
			var imgSrc = null
			if (setname.tagName == 'IMG') {
				imgSrc = setname.getAttribute('src')
			} else {
				throw new Exception('kbImageOverlay.showImage: setname is not an image element')
			}
			var current = kbImageOverlay.carousel.current
			current.set = kbImageOverlay.images._SINGLE
			current.set.src = imgSrc
			current.idx = 0
			this.carousel.active.image.setAttribute('src', imgSrc)
			this.controls.setDescription('')
		} else if (typeof setname == 'string') {
			var current = this.carousel.current
			current.set = this.images[setname]
			current.idx = idx
			var imgdata = current.set[current.idx]
			this.carousel.active.image.setAttribute('src', imgdata.src)
			this.controls.setDescription(imgdata.descr)
		} else {
			throw new Exception('kbImageOverlay.showImage: setname is not a string')
		}
		$(this.controls.element).fadeIn(150)
		$(this.carousel.active).fadeIn(150)
	}

	kbImageOverlay.refreshData()
	kbImageOverlay.initialized = true
}*/

$.fn.makeSpoiler = function(target) {
	var _hider = this
	var _target = target
	_hider.plusminus = _hider.prepend('<span class="plusminus">[+] </span>')
	_hider.addClass('kbSpoiler')
	if (typeof _target == 'object') {
		if (_target instanceof HTMLElement) {
			_target = $(_target)
		}
		if (_target instanceof jQuery) {
			_target.addClass('kbSpoilerContent')
		}
		else _target = null
	}
	_hider.click(function() {
		if (!_target) return
		if (typeof _target == string) {
			_target = $(_target)
		}
		var plusminustext = _target[0].style.display == 'none' ? '[-] ' : '[+] '
		this.plusminus.text(plusminustext)
		_target.slideToggle(300)
	})
}

$.fn.kbPreviewImg = function(setName, src, descr, width) {
	descr = descr || ''
	width = width || 160
	if (typeof width == 'number') {
		width += 'px'
	}
	$('<img class="previewImg">').css('width', width).attr({
		kbSet: setName
		, src: src
		, title: descr
	}).appendTo(this)
}

function showMessageBox(text) {
	var buttons = ['ОК']
	var title = "Внимание"
	var classes = null
	if (typeof text == 'object') {
		if (typeof text.Level == 'string') {
			classes = [ text.Level ]
			switch (text.Level) {
				case 'error':
					title = 'Ошибка'
					break
				case 'warn':
					title = 'Внимание'
					break
			}
		}
		title = assertString(text.Title, title)
		if (isArray(text.Buttons)) {
			buttons = text.Buttons
		}
		text = text.Message || text.Text || ''
	}
	var dialog = new KBDialog({
		title: title
		, buttons: buttons
		, dispose: true
	})
	dialog.SetText(text)
	dialog.Show()
}

$.fn.bbCode = function (text) {
	if (!this.length) return this
	if (typeof text !== 'string') {
		return this.text(text)
	}
	this.contents().remove()

	let bbRoot = this
	let bbCurrentParent = this
	let parseStart = 0
	let cursor = 0
	let bracketEnd = 0

	while (true) {
		cursor = text.indexOf('[', cursor)
		if (cursor < 0) {
			addText(text.substring(parseStart))
			break;
		}
		bracketEnd = text.indexOf(']', cursor)
		if (bracketEnd < 0) {
			addText(text.substring(parseStart))
			break;
		}
		let tagLen = bracketEnd - cursor - 1
		if (tagLen == 1) {
			let bbTagName = text.charAt(cursor + 1).toUpperCase()
			switch (bbTagName) {
				case 'B':
				case 'I':
				case 'S':
				case 'U':
					putLastText()
					bbCurrentParent = $('<' + bbTagName + '>').appendTo(bbCurrentParent)
					break;
				case 'N':
					putLastText()
					$('<br>').appendTo(bbCurrentParent)
					break;
			}
		}
		else if (tagLen == 2) {
			let bbTagName = text.substring(cursor + 1, bracketEnd).toUpperCase()
			switch (bbTagName) {
				case 'UL':
				case 'LI':
					putLastText()
					bbCurrentParent = $('<' + bbTagName + '>').appendTo(bbCurrentParent)
					break;
				case '/B':
				case '/I':
				case '/S':
				case '/U':
					closeTag(bbTagName.charAt(1))
					break;
			}
		}
		else if (tagLen == 3) {
			let bbTagName = text.substring(cursor + 1, bracketEnd).toUpperCase()
			switch (bbTagName) {
				case '/UL':
				case '/LI':
					closeTag(bbTagName.substring(1))
					break;
				case '/HL':
					closeTag('SPAN')
					break;
			}
		}
		else {
			let classLine = text.substring(cursor + 1, bracketEnd)
			let eqidx = classLine.indexOf('=')
			if (eqidx > -1) {
				let bbTagName = classLine.substring(0, eqidx).toUpperCase()
				let bbvalue = classLine.substring(eqidx + 1)
				switch (bbTagName) {
					case 'COLOR':
						if (/^#[a-fA-F0-9]{6}$/.test(bbvalue)) {
							putLastText()
							bbCurrentParent = $('<span class="ec-hotel-format-coloredText" style="color: ' + bbvalue + '">').appendTo(bbCurrentParent)
						}
						break;
					case 'HL':
						if (/^#[a-fA-F0-9]{6}$/.test(bbvalue)) {
							putLastText()
							bbCurrentParent = $('<span class="ec-hotel-format-highlight" style="background-color: ' + bbvalue + '">').appendTo(bbCurrentParent)
						}
						break;
				}
			}
			else {
				let bbTagName = classLine.toUpperCase()
				switch (bbTagName) {
					case '/COLOR':
						closeTag('SPAN')
						break;
				}
			}
		}
		cursor = bracketEnd + 1
	}

	function closeTag(tagName) {
		let target = bbCurrentParent[0]
		let root = bbRoot[0]
		while (target != root && target.tagName != tagName) {
			target = target.parentNode
		}
		if (target != root) {
			putLastText()
			bbCurrentParent = $(target.parentNode)
		}
	}
	function addText(val) {
		if (val.length > 3) {
			val = val.split(/<br>|<br\/>|\r\n|\n/)
			bbCurrentParent.append(document.createTextNode(val[0]))
			for (let i = 1; i < val.length; i++) {
				bbCurrentParent.append($('<br>'), document.createTextNode(val[i]))
			}
		}
		else {
			bbCurrentParent.append(document.createTextNode(val))
		}
	}
	function putLastText() {
		let line = text.substring(parseStart, cursor)
		if (line.length) {
			addText(line)
		}
		parseStart = bracketEnd + 1
	}

	return this
};