
<script>
var fontVariants = [
	{
		folder: 'f128_classic'
		, slides: ['ascii.png', 'nonlatin_european.png']
	}
	, {
		folder: 'f512_qrounded'
		, slides: ['ascii.png', 'nonlatin_european.png']
	}
	, {
		folder: 'f512_qroundedsilver'
		, slides: ['ascii.png', 'nonlatin_european.png']
	}
	, {
		folder: 'f512_qroundedgold'
		, slides: ['ascii.png', 'nonlatin_european.png']
	}
]

$(function () {
	for (var i = 0; i < fontVariants.length; i++) {
		(function(font) {
			var cont = $("#dl_" + font.folder)
			var imagelist = []
			for (var j = 0; j < font.slides.length; j++) {
				var url = '/koshkbench/pics/' + font.folder + '/' + font.slides[j]
				$('<div class="dlPicture" imgidx="' + j + '" style="background-image: url(\'' + url + '\')"></div>').appendTo(cont).click(function() {
					this.parentNode.__kbslider.show()
					this.parentNode.__kbslider.Show(this.getAttribute('imgidx'))
				})
				imagelist.push({ ImgUrl: url, asBackground: true })
			}
			cont[0].__kbslider = showImageOverlay(imagelist)

		})(fontVariants[i])
	}
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
	width: 31%;
	display: flex;
	flex-direction: column;
	place-content:center;
	color: white;
}
.TP-dl-dl {
    font-size: 1.4em;
}
</style>

<!---/HEAD---->

<h3 class='post-title entry-title'>Шрифты для Minecraft 1.13</h3>

<div class='post-body entry-content'>

Альтернативные шрифты для Minecraft 1.13 и более поздних версий.
<br><br>Чтобы установить, скачайте пакет ресурсов с понравившимся шрифтом, положите его в папку .minecraft\resourcepacks (или где у вас находятся пакеты ресурсов), активируйте пакет в настройках игры.

<br><br>

<div class="TP-dl-row">
<div class="TP-dlPicture" style="background-image: url('/koshkbench/pics/f128_classic/pack.png')"></div>
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Классический</div>
<div style="font-size: 1.1em;">x128 (8px/символ)</div>
</div>
<div class="dlFlex" id="dl_f128_classic" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/KBFonts13_Classic.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dlPicture" style="background-image: url('/koshkbench/pics/f512_qrounded/pack.png')"></div>
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">QRouded</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
</div>
<div class="dlFlex" id="dl_f512_qrounded" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/KBFonts13_QRounded.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dlPicture" style="background-image: url('/koshkbench/pics/f512_qroundedsilver/pack.png')"></div>
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">QRouded</div>
<div style="font-size: 1.1em;">Серебряный градиент<br>x512 (32px/символ)</div>
</div>
<div class="dlFlex" id="dl_f512_qroundedsilver" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/KBFonts13_QRoundedSilver.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dlPicture" style="background-image: url('/koshkbench/pics/f512_qroundedgold/pack.png')"></div>
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">QRouded</div>
<div style="font-size: 1.1em;">Золотой градиент<br>x512 (32px/символ)</div>
</div>
<div class="dlFlex" id="dl_f512_qroundedgold" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/KBFonts13_QRoundedGold.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>


<span style="font-size:10px;">* Данные пакеты ресурсов не содержат перевода как такового, но могут содержать правки к некоторым строкам моего перевода, чтобы надписи на кнопках не выезжали за границы кнопок. Эти правки никак не повлияют на игру, если вы не используете мой перевод.</span><br><br>

</div>

<div class='post-footer'>
<div class='date-header'>Дата публикации: 24 декабря 2018 г.</div>
<div class="feedbackButton">Написать автору</div>
</div>