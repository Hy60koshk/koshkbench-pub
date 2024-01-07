
<script>
var fontVariants = [
	{
		Caption: 'Классический'
		, Url: 'classic'
	}
	, {
		Caption: 'Скруглённый'
		, Url: 'rounded'
	}
	, {
		Caption: 'Скруглённый (QRounded)'
		, Url: 'qrounded'
	}
	, {
		Caption: 'Pinkerton'
		, Url: 'pinkerton'
	}
	, {
		Caption: 'Spigo'
		, Url: 'spigo'
	}
	, {
		Caption: 'Faithful'
		, Url: 'faithful'
	}
	, {
		Caption: 'Dokucraft Silver'
		, Url: 'silver'
	}
	, {
		Caption: 'Dokucraft Gold'
		, Url: 'gold'
	}
	, {
		Caption: 'Утолщенный стандартный юникодовый'
		, Url: 'unibold'
	}
	, {
		Caption: 'Скруглённый x512'
		, Url: 'rounded_512'
	}
	, {
		Caption: 'Скруглённый (QRounded) x512'
		, Url: 'qrounded_512'
	}
	, {
		Caption: 'Tales of a Font'
		, Url: 'tales'
	}
]

$(function () {
	var imagelist = []
	var _kbslider;
	for (var i = 0; i < fontVariants.length; i++) {
		var font = fontVariants[i]
		var cont = $("#dl_" + font.Url)
		var url = '/koshkbench/pics/fonts_1_12/' + font.Url + '_preview.png'
		$('<div class="dlPicture" imgidx="' + i + '" style="background-image: url(\'' + url + '\')"></div>').appendTo(cont).click(function() {
			_kbslider.show()
			_kbslider.Show(this.getAttribute('imgidx'))
		})
		imagelist.push({ ImgUrl: url, Caption: font.Caption })
	}
	_kbslider = showImageOverlay(imagelist)
})

var PERMISSIONS_ = {
	common: ['Шрифты «Классический», «Скруглённый», «QRouded», «Spigo» и «Dokucraft» являются моими альтерациями оригинального шрифта Minecraft, права на который принадлежат Mojang.'
			, 'Эти шрифты могут использоваться кем угодно в некоммерческих продуктах, связанных с игрой Minecraft (в бесплатных пакетах ресурсов, личном некоммерческом производстве фанатской атрибутики и т.д.)'
			, "Упоминание автора (меня) а также вдохновителей (Dokucraft — в случае шрифтов из серии Dokucraft) приветствуется, но не обязательно."]
	, pinkerton: ['Шрифт Pinkerton являются моей альтерацией и адаптацией шрифта, разработанного ArtPinkerton.'
			, 'Этот шрифт может использоваться кем угодно в любых некоммерческих продуктах, с обязательной ссылкой на автора оригинального шрифта: https://artinpinkerton.com/']
	, faithful: ['Шрифт Faithful являются моей альтерацией шрифта, разработанного авторами пакета Faithful.'
			, 'Этот шрифт может использоваться кем угодно в некоммерческих продуктах, связанных с игрой Minecraft, с обязательной ссылкой на авторов оригинального пака: https://faithfulpack.net/']
	, tales: ['Шрифт Tales of a Font является моей альтерацией шрифта, разработанного автором пакета Tales of a Font.'
			, 'Этот шрифт может использоваться кем угодно в некоммерческих продуктах, связанных с игрой Minecraft,'
			+ ' с обязательной ссылкой на оригинальный пак: http://www.planetminecraft.com/texture_pack/32x-hd-font---tales-of-a-font-10/ или https://www.planetminecraft.com/texture-pack/hd-font---tales-of-a-gradient/']
	, unibold: ['Шрифт «Утолщённый юникод» является моей разработкой, попыткой уместить читаемый шрифт в размеры unicode-шрифта игры.'
			, 'Этот шрифт может использоваться кем угодно и как угодно. Упоминание автора (меня) приветствуется, но не обязательно.']
}
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
	width: 60%;
    min-height: 80px;
	padding: 8px 6px 9px 16px;
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
.dlPicture {
	height: 100%;
	background-size: 192px auto;
	background-position: left 75%;
}
.permissionsButton.smallLine {
	padding-top: 4px;
}

.TP-dl-descr a {
	font-size: 0.8em;
	color: #fff;
	display: block;
}
.TP-dl-descr a:hover
, .permissionsButton.smallLine:hover {
	color: #dcf0ff;
}
</style>

<!---/HEAD---->

<h3 class='post-title entry-title'>Шрифты для Minecraft 1.9—1.12</h3>

<div class='post-body entry-content'>

Альтернативные шрифты для Minecraft 1.9 и более поздних версий.
<br>
<br>Чтобы установить, скачайте пакет ресурсов с понравившимся шрифтом, положите его в папку .minecraft\resourcepacks (или где у вас находятся пакеты ресурсов), активируйте пакет в настройках игры.
<br>На версиях более ранних, чем 1.11, нужно также перезапустить игру.
<br><br>


<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Классический</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_classic" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Classic_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Скруглённый</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_rounded" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Rounded_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">QRouded</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_qrounded" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Q_Rounded_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Pinkerton</div>
<div style="font-size: 1.1em;">x256 (16px/символ)<br><a href="https://artinpinkerton.com/">Сайт автора оригинального шрифта для латиницы</a></div>
<div class="permissionsButton smallLine" permid="pinkerton">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_pinkerton" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Pinkerton_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Spigo</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_spigo" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Spigo_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Faithful</div>
<div style="font-size: 1.1em;">x256 (16px/символ)<br><a href="https://faithfulpack.net/">Сайт авторов оригинального пака</a></div>
<div class="permissionsButton smallLine" permid="faithful">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_faithful" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Faithful_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Dokucraft Silver</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_silver" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Doku_Silver_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Dokucraft Gold</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_gold" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Doku_Gold_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Утолщённый юникод</div>
<div style="font-size: 1.1em;">x256 (16px/символ)</div>
<div class="permissionsButton smallLine" permid="unibold">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_unibold" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Uni_Bold_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<!---------512---------->

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Скруглённый</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_rounded_512" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Q_Rounded_512_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Скруглённый с градиентом</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_rounded_512_g" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Q_Rounded_512_Gradient_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">QRouded</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_qrounded_512" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Q_Rounded_512_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">QRouded с градиентом</div>
<div style="font-size: 1.1em;">x512 (32px/символ)</div>
<div class="permissionsButton smallLine" permid="common">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_qrounded_512_g" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Q_Rounded_512_Gradient_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>

<div class="TP-dl-row">
<div class="TP-dl-descr">
<div style="font-size: 1.4em;">Tales of a Font</div>
<div style="font-size: 1.1em;">x512 (32px/символ)<br><a href="http://www.planetminecraft.com/texture_pack/32x-hd-font---tales-of-a-font-10/">Оригинал от Tatenokai</a></div>
<div class="permissionsButton smallLine" permid="tales">Условия распространения</div>
</div>
<div class="dlFlex" id="dl_tales" style="flex-grow: 1;">
</div>
<a href="/koshkbench/resourcepacks/fonts_1_12/KB_Tales_Of_A_Font.zip" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>
</div>


<span style="font-size:10px;">* Данные пакеты ресурсов не содержат перевода как такового, но могут содержать правки к некоторым строкам моего перевода, чтобы надписи на кнопках не выезжали за границы кнопок. Эти правки никак не повлияют на игру, если вы не используете мой перевод.</span><br><br>

</div>

<div class='post-footer'>
<div class='date-header'>Дата публикации: 1 марта 2016 г.<br>Последнее обновление: 21 июня 2023 г.</div>
<div class="feedbackButton">Написать автору</div>
</div>