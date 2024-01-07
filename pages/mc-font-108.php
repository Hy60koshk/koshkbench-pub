
<script>
var fontVariants = ['unibold_1', 'unibold_2']

$(function () {
	var imagelist = []
	var _kbslider;
	for (var i = 0; i < fontVariants.length; i++) {
		var cont = $("#imageline")
		var url = '/koshkbench/pics/fonts_1_7/' + fontVariants[i] + '.png'
		$('<div class="dlPicture" imgidx="' + i + '" style="background-image: url(\'' + url + '\')"></div>').appendTo(cont).click(function() {
			_kbslider.show()
			_kbslider.Show(this.getAttribute('imgidx'))
		})
		imagelist.push({ ImgUrl: url })
	}
	_kbslider = showImageOverlay({ Dataset: imagelist })
})
</script>

<style>
.kbDlButton {
	width: 240px;
	margin-right: 10px;
}
.dlPicture:first-child {
	border-left: 0;
}
.dlPicture {
	height: 130px;
}
</style>

<!---/HEAD---->

<h3 class='post-title entry-title'>Утолщенный шрифт юникода<br>для Minecraft 1.7.10 и 1.8</h3>

<div class='post-body entry-content'>

<span style="font-size:12pt;"><b>Без искажений текста и изуверств с кодированием.</b></span><br>
Данный пакет ресурсов просто делает стандартный юникодовый шрифт толще и удобнее для чтения.<br>
Можно использовать без русификатора или с любым русификатором, не требующим перекодирования кириллических символов.<br>
Включает в себя файлы локализации для версий Minecraft 1.7.10 и 1.8.1-1.8.8 и перевод финального диалога (по второй ссылке можно скачать версию без перевода).<br><br>

<div class="dlFlex" id="imageline"></div>
<br><br>
<div class="dlFlex">
<a href="/koshkbench/resourcepacks/fonts_1_7/Bold_Unicode_Font_1710.zip" class="kbDlButton" download><div class="downloadArrow"></div><div class='dl'>Скачать</div>Утолщенный шрифт<br>+ файлы локализации*</a>
<a href="/koshkbench/resourcepacks/fonts_1_7/Bold_Unicode_Font_nolangfiles_1710.zip" class="kbDlButton" download><div class="downloadArrow"></div><div class='dl'>Скачать</div>Только утолщенный шрифт</a>
</div>

<br><br>
<b><span style="font-size:12pt;">Установка:</span></b><br>
1. Открываем папку <i>.minecraft</i> (на Windows: нажимаем "Пуск", там вводим в поле поиска или "выполнить" слово <i>%appdata%</i>, нажимаем enter - перед нами сразу же откроется папка, содержащая папку <i>.minecraft</i><br>
(для Win7, 8 и 10 это C:\Пользователи\%username%\AppData\Roaming\).<br>
2. Копируем скачанный архив в папку <i>resourcepacks</i>.<br>
3. Запускаем Minecraft, активируем ресурспак "Bold_Unicode_Font".<br>
4. Перезапускаем Minecraft.<br>
(5.) Если не работает и вы используете несколько ресурспаков, убедитесь, что русификатор грузится последним (в самом верху списка).<br>
<br>
* Прилагается два файла локализации - Русский (1.7.10) и Русский (1.8.Х).
<br>1.7.10 - только для Minecraft 1.7.10.<br>1.8.Х - для версий Minecraft с 1.8.1 по S15w31.<br>Для других версий используйте вместе с данным ресурспаком ванильный "Русский" язык или русификатор, соответствующий той версии.
<br>
<br>

</div>

<div class='post-footer'>
<div class='date-header'>Дата публикации: 2 августа 2015 г.</div>
<div class="feedbackButton">Написать автору</div>
</div>