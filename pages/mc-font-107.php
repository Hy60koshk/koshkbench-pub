<script>
var fontVariants = [
	{
		Caption: 'Скруглённый (Springhy) 16px/символ'
		, Url: 'springhy'
		, Filename: 'Font_x16_Springhy-rounded.zip'
	}
	, {
		Caption: 'Скруглённый 32px/символ'
		, Url: 'rounded'
		, Filename: 'Font_x32_KB-classic-rounded.zip'
	}
	, {
		Caption: 'Calibri 32px/символ'
		, Url: 'calibri'
		, Filename: 'Font_x32_KB-calibri.zip'
	}
	, {
		Caption: 'Orpheus 32px/символ'
		, Url: 'orpheus'
		, Filename: 'Font_x32_KB-orpheus.zip'
	}
]

$(function () {
	var imagelist = []
	var _kbslider;
	for (var i = 0; i < fontVariants.length; i++) {
		var font = fontVariants[i]
		var cont = $("#dl_" + font.Url)
		var url = '/koshkbench/pics/fonts_1_7/' + font.Url + '.png'
		$('<div class="dlPicture" imgidx="' + i + '" style="background-image: url(\'' + url + '\')"></div>').appendTo(cont).click(function() {
			_kbslider.show()
			_kbslider.Show(this.getAttribute('imgidx'))
		})
		var dlurl = '/koshkbench/resourcepacks/fonts_1_7/' + font.Filename
		$('<a href="' + dlurl + '" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>').appendTo(cont.parent())

		var caption = $('<span>' + font.Caption + ' — <a href="' + dlurl + '" download>Скачать</a></span>')
		imagelist.push({ ImgUrl: url, Caption: caption })
	}
	_kbslider = showImageOverlay({ Dataset: imagelist, FitByDefault: true })
})
</script>

<style>
.TP-dl-row {
	width: 100%;
	display: flex;
	flex-direction: row;
	background-color: #14477b;
    border-radius: 8px;
	margin-bottom: 20px;
}
.TP-dlPicture {
	height: 80px;
	width: 64px;
	background-size: contain;
	background-position: center;
	background-repeat: no-repeat;
}
.TP-dl-descr {
	width: 50%;
    height: 80px;
    padding-left: 16px;
    display: flex;
    flex-direction: column;
    place-content: center;
    color: white;
}
.TP-dl-dl {
	padding: 12px 0;
    width: 86px;
    font-size: 1.4em;
}
</style>

<!---/HEAD---->

<h3 class='post-title entry-title'>Шрифты для Minecraft 1.7.4</h3>

<div class='post-body entry-content'>

Несколько дополнительных шрифтов для русификатора с «классическим» шрифтом.
<br><br>Рекомендуется использовать с русификаторами <a href="https://koshk.ru/koshkbench/?page=minecraft-10704">1.7.4 rev2</a> или <a href="https://koshkbench.blogspot.ru/2014/01/minecraft-s14w02c.html">S14wX</a>.
<br><br>Чтобы установить, добавьте ресурспак с нужным шрифтом и поставьте на самый верх очереди загрузки ресурспаков в игре.
<br><br>Чтобы х32 шрифты не искажались, используйте разрешение экрана не менее 1280х960.
<br><br>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Скруглённый</div>
<div style="font-size: 1.1em;">x256 (16px/символ)<br><span style="font-size: 0.9em;">Основан на шрифте Springhy</span></div>
</div>
<div class="dlFlex" id="dl_springhy" style="flex-grow: 1;">
</div>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Скруглённый</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
</div>
<div class="dlFlex" id="dl_rounded" style="flex-grow: 1;">
</div>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Calibri</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
</div>
<div class="dlFlex" id="dl_calibri" style="flex-grow: 1;">
</div>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Orpheus</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
</div>
<div class="dlFlex" id="dl_orpheus" style="flex-grow: 1;">
</div>
</div>



</div>

<div class='post-footer'>
<div class='date-header'>Дата публикации: 20 января 2014 г.</div>
<div class="feedbackButton">Написать автору</div>
</div>