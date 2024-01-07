
<script>

var fontsSections = [
{ 
	ID: 'kb-fonts'
	, FontList: [
		{
			Caption: 'KoshkPix x4'
			, Size: '3px'
			, Image: 'koshkpix'
			, Filename: 'koshkpix-x4'
		}
		, {
			Caption: 'KB UniPix'
			, Size: '12px'
			, Image: 'kb_unipix'
			, Filename: 'kb-uni-pix'
		}
	]
},{ 
	ID: 'non-commercial'
	, FontList: [
		{
			Caption: 'Gnomoria'
			, Size: '6px'
			, Image: 'gnomoria'
			, Filename: 'gnomoria-rus'
		}
		, {
			Caption: 'Minecraft'
			, Size: '12px'
			, Image: 'minecraft'
			, Filename: 'minecraft-rus'
		}
		, {
			Caption: 'Minecraft Alt'
			, Size: '12px'
			, Image: 'minecraft_alt'
			, Filename: 'minecraft-rus-alternative'
		}
		, {
			Caption: 'VA-11 Standart'
			, Size: '10.5 (21px)'
			, Image: 'va11'
			, Filename: 'va-11-hall-a-cyr-10px'
		}
		, {
			Caption: 'VA-11 AE'
			, Size: '7.5px (15px)'
			, Image: 'va11_6px'
			, Filename: 'va-11-hall-a-6px-10px'
		}
		, {
			Caption: 'VA-11 AE non-monospace'
			, Size: '7.5px (15px)'
			, Image: 'va11_6pxnm'
			, Filename: 'va-11-hall-a-non-mono'
		}
		, {
			Caption: 'VA-11 AE Bold'
			, Size: '9px'
			, Image: 'va11_6pxb'
			, Filename: 'va-11-hall-a-6px-bold'
		}
		, {
			Caption: 'VA-11 Banter'
			, Size: '9.75px (39px)'
			, Image: 'va11'
			, Filename: 'va-11-banter'
		}
		, {
			Caption: 'VA-11 Jukebox'
			, Size: '8.25px (33px)'
			, Image: 'va11'
			, Filename: 'va-11-jukefont'
		}
	]
}]

$(function () {
	for (var fsi = 0; fsi < fontsSections.length; fsi++) {
		(function(section) {
			var imagelist = []
			var _kbslider;
			var cont = $("#dl-" + section.ID)

			for (var i = 0; i < section.FontList.length; i++) {
				var font = section.FontList[i]
				
				var picurl = '/koshkbench/fonts/previews/' + font.Image + '.png'
				var dlurl = '/koshkbench/fonts/' + font.Filename + '.zip'
				
				var pic$ = $('<div class="dlPicture" imgidx="' + i + '">').click(function() { // style="background-image: url(\'' + picurl + '\')"
					_kbslider.show()
					_kbslider.Show(this.getAttribute('imgidx'))
				})

				pic$[0].style.setProperty('--bgimage', 'url(\'' + picurl + '\')');

				cont.append(
					$('<div class="TP-dl-row">').append(
						$('<div class="TP-dl-descr">').append(
							$('<div style="font-size: 1.4em;">').text(font.Caption)
							, $('<div style="font-size: 1.1em;">').text('Базовый размер: ' + font.Size)
						)
						, pic$
						, $('<a href="' + dlurl + '" class="kbDlButton TP-dl-dl" download><div class="downloadArrow"></div>Скачать</a>')
					)
				)
				imagelist.push({
					ImgUrl: picurl
					, Caption: $('<span>' + font.Caption + ' / ' + font.Size + ' — <a href="' + dlurl + '" download>Скачать</a></span>')
				})
			}

			_kbslider = showImageOverlay(imagelist)

		})(fontsSections[fsi]);
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
.dlPicture {
	background-size: 250%;
    background-position: 15% 85%;
	background-image: linear-gradient(0deg, #14477b, transparent 8%,transparent 92%, #14477b), var(--bgimage);
    border: 4px solid #14477b;
}
.TP-dl-descr {
	width: 45%;
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

<h3 class='post-title entry-title'>TrueType шрифты</h3>

<div class='post-body entry-content'>

<p>В ходе своей переводческой деятельности мне иногда нужно было делать TTF-шрифты.</p>
<p>И вот те, которыми можно было бы поделиться.</p>
<p>Первый блок: целиком мои творения. Их разрешается использовать где угодно и в любых целях.</p>
<div id="dl-kb-fonts">
</div>
<hr>
<p>Второй блок: копии ширфтов из игр, перерисованные с расширенным диапазоном символов: иногда только кириллицей, а иногда и чуть шире.</p>
<p>Ввиду того, что эти шрифты основаны на шрифтах, являющихся интеллектуальной собственностью компаний, <b>перечисленные ниже шрифты запрещено использовать в коммерческих целях</b>.</p>
<div id="dl-non-commercial">
</div>

</div>

<div class='post-footer'>
<div class='date-header'>Дата публикации: 9 сентября 2020 г.</div>
<div class="feedbackButton">Написать автору</div>
</div>