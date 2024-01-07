/**
	FrameInit(frame$) - взывается при инициализации фреймов
	NextSlide(data, shownFrame$, previousFrame$) - вызывается при показе следующего слайда
	PrevSlide(data, shownFrame$, previousFrame$) - вызывается при показе предыдущего слайда
		data - данные, переданные в SliderContainer$.NextSlide/PrevSlide
		shownFrame$ - слайд, который теперь отображается
		previousFrame$ - слайд, который отображался до этого
		
	CarouselSize - размер карусели (буфера слайдера). Только нечётные значения, не менее 3.
		Если есть анимация, то для корректного её отображения следует ставить CarouselSize не менее 5
		(и вообще чем больше, тем лучше. если предполагается, что пользователь может как бешеный
		тыкать в кнопки вправо-влево, то лучше ставить ~11).
	AnimationLock - время (сек), на которое экран блокируется, чтобы не дать пользователю затыкивать слайдер.
		Рекомендуем для долгой анимации перехода, но может использоваться и при короткой анимации,
		если хочется компенсировать маленький CarouselSize.
**/
;
jQuery.fn.extend({
	kbImageSlider: function(options) {
		function putTextOrChild(el$, obj) {
			if (typeof obj == 'object' && ((obj instanceof HTMLElement) || (obj instanceof jQuery))) {
				el$.empty().append(obj)
			} else if (obj !== null && obj !== undefined) {
				el$.text(obj)
			}
		}
		options = options || {}
		options.FrameInit = function(frame$) {
			frame$.mediaContainer$ = $('<div class="mediaContainer">').appendTo(frame$);
			frame$.title$ = $('<div class="title">').appendTo(frame$);
			frame$.subtitle$ = $('<div class="subtitle">').appendTo(frame$);
		}
		var _setImg = function(container, slide) {
			container[0].style.backgroundImage = 'url("' + slide + '")'
		}
		/*options.asBackground ? function(container, slide) {
			container[0].style.backgroundImage = 'url("' + slide + '")'
		} : function(container, slide) {
			container.append($('<img class="sliderImage" />').attr('src', slide))
		};*/
		options.FramePopulate = function(slide, shownFrame$) {
			putTextOrChild(shownFrame$.title$, slide.Caption)
			putTextOrChild(shownFrame$.subtitle$, slide.Name)
			//shownFrame$.title$.text(slide.Caption)
			//shownFrame$.subtitle$.text(slide.Name)
			//shownFrame$.mediaContainer$.children().remove()
			_setImg(shownFrame$.mediaContainer$, slide.ImgUrl)
		}
		var slider = this.kbSlider(options)
		$('<div class="kbExpandImageBtn">').appendTo(slider).click(function() {
			if (slider.hasClass('imgFit')) {
				slider.removeClass('imgFit')
			} else {
				slider.addClass('imgFit')
			}
		})
		if (options.FitByDefault) {
			slider.addClass('imgFit')
		}
		return slider
	}
    , kbSlider: function(options) {
		options = options || {}
		options.Dataset = options.Dataset || []
		
		var _container$ = this;
		this.addClass('kbSlider')
		var _slideList = options.Dataset

		var _sliderWrapper$ = $('<div class="kbSliderWrapper">').appendTo(_container$);
		if (_slideList.length > 1) {
			$('<div class="kbPrevSlideBtn">').appendTo(_container$).click(function() {
				_stopSlides()
				_container$.PrevSlide()
			})
			$('<div class="kbNextSlideBtn">').appendTo(_container$).click(function() {
				_stopSlides()
				_container$.NextSlide()
			})
		}
		$('<div class="kbCloseSliderBtn">').appendTo(_container$).click(function() {
			_stopSlides()
			_container$.hide()
		})
		var _sliderFrames = new List()
		
		var _onFrameInit = (typeof options.FrameInit == 'function') ? options.FrameInit : function(){};
		var _framePopulate = (typeof options.FrameInit == 'function') ? options.FramePopulate : function(){};
		var _onNextSlide = (typeof options.NextSlide == 'function') ? options.NextSlide : function(){};
		var _onPrevSlide = (typeof options.PrevSlide == 'function') ? options.PrevSlide : function(){};
		var _animationLockTime = (typeof options.AnimationLock == 'number') ? options.AnimationLock * 1000 : 0;
		var _carouselSize = (typeof options.CarouselSize == 'number') ? options.CarouselSize : 5;
		var _carouselShift = (_carouselSize - 1) / 2;
		_sliderWrapper$.css('overflow-x', 'hidden')
		var _animationLocker$;
		var _autoRun = options.Speed ? true : false;
		var _slideDuration = options.Speed || 240
		var _nextSlideTimeout = false
		var _slideIdx = 0

		for (var i = 0; i < _carouselSize; i++) {
			var frame$ = $('<div class="kbSliderContainer">').appendTo(_sliderWrapper$)
			_onFrameInit(frame$)
			frame$.css('left', ((i - _carouselShift) * 100) + '%')
			_sliderFrames.add(frame$)
		}
		
		if (_animationLockTime > 0) {
			_animationLocker$ = $('<div class="kbSliderAnimationLocker">').appendTo(_container$).css({
				'left': '0px'
				, 'top': '0px'
				, 'height': '100%'
				, 'width': '100%'
				, 'position': 'absolute'
				, 'z-index': '1'
			})
			_animationLocker$.hide()
		}

		this.Show = function(startIdx, autoRun) {
			_slideIdx = startIdx || 0
			if (_autoRun && autoRun !== false) {
				_nextSlideTimeout = setTimeout(_runSlides, _slideDuration * 1000);
			} else {
				var visibleSlide = _sliderFrames.head
				for (i = 0; i < _carouselShift; i++) {
					visibleSlide = visibleSlide.next
				}
				_framePopulate(_slideList[_slideIdx], visibleSlide.value)
			}
		}
		
		function _runSlides() {
			_stopSlides()
			_container$.NextSlide()
			_nextSlideTimeout = setTimeout(_runSlides, _slideDuration * 1000);
		}
		function _stopSlides() {
			if (_nextSlideTimeout) {
				clearTimeout(_nextSlideTimeout)
			}
		}

		this.NextSlide = function(data) {
			if (!data) {
				_slideIdx++
				if (_slideIdx >= _slideList.length) {
					_slideIdx = 0;
				}
				data = _slideList[_slideIdx]
			}
			if (_animationLockTime > 0) {
				_animationLocker$.show()
				setTimeout(function () {
					_animationLocker$.hide()
				}, _animationLockTime);
			}
			var i = -1;
			_sliderFrames.forEach(function (node) {
				node.value.css('left', ((i - _carouselShift) * 100) + '%')
				i++
			})
			var lastFrame = _sliderFrames.shiftToHead()
			lastFrame.value.css('left', ((_carouselSize - 1 - _carouselShift) * 100 + _carouselShift) + '%')
			lastFrame.prev.value.show()
			var head$ = _sliderFrames.head.value
			head$.hide()
			_sliderWrapper$[0].insertBefore(head$[0], _sliderFrames.head.next.value[0])
			var visibleSlide = _sliderFrames.head
			for (i = 0; i < _carouselShift; i++) {
				visibleSlide = visibleSlide.next
			}
			_framePopulate(data, visibleSlide.value)
			_onNextSlide(data, visibleSlide.value, visibleSlide.prev.value)
		}
		this.PrevSlide = function(data) {
			if (!data) {
				_slideIdx--
				if (_slideIdx < 0) {
					_slideIdx = _slideList.length - 1;
				}
				data = _slideList[_slideIdx]
			}
			if (_animationLockTime > 0) {
				_animationLocker$.show()
				setTimeout(function () {
					_animationLocker$.hide()
				}, _animationLockTime);
			}
			var i = 1;
			_sliderFrames.forEach(function (node) {
				node.value.css('left', ((i - _carouselShift) * 100) + '%')
				i++
			})
			var firstFrame = _sliderFrames.shiftToTail()
			firstFrame.value.css('left', ((0 - _carouselShift) * 100 - _carouselShift) + '%')
			firstFrame.next.value.show()
			var tail$ = _sliderFrames.tail.value
			tail$.hide()
			_sliderWrapper$[0].appendChild(tail$[0])
			var visibleSlide = _sliderFrames.head
			for (i = 0; i < _carouselShift; i++) {
				visibleSlide = visibleSlide.next
			}
			_framePopulate(data, visibleSlide.value)
			_onPrevSlide(data, visibleSlide.value, visibleSlide.next.value)
		}
		return _container$;
	}
});