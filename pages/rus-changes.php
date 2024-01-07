<script language='JavaScript' src='ru_ru.js' type='text/javascript'></script>
<script language='JavaScript' src='ru_ru_orig.js' type='text/javascript'></script>
<script language='JavaScript' src='en_us.js' type='text/javascript'></script>
<script language='JavaScript' src='ru_mismatches.js' type='text/javascript'></script>

<style>
td.statuscontrols {
	width: 64px;
}

#differencesTable td, #differencesTable th {
	font-size: 11px;
	font-family: Verdana;
}
#differencesTable .switch {
	width: 20px;
	height: 20px;
	border: 1px solid #888;
	display: inline-block;
}
#differencesTable .switch.active {
	border: 3px solid #111;
}
#differencesTable .switch.white {
	background-color: #fff;
}
#differencesTable .switch.yellow {
	background-color: #f0f020;
}
#differencesTable .switch.red {
	background-color: #e60c0c;
}
#differencesTable tr.yellow {
	background-color: #ff7;
}
#differencesTable tr.red {
	background-color: #f99;
}
#differencesTable tr:nth-child(odd) {
	background-color: #e9e9e9;
}
#differencesTable tr.yellow:nth-child(odd) {
	background-color: #e9e970;
}
#differencesTable tr.red:nth-child(odd) {
	background-color: #e98990;
}

#pageControl {
	display: flex;
	flex-direction: row;
	place-content: space-between;
}
#pageControl .page {
	width: 40px;
	height: 24px;
	cursor: pointer;
	display: flex;
	flex-direction: column;
	place-content: center;
	align-items: center;
	border: 1px solid #aaa;
	border-radius: 10px;
}
#pageControl .pageInput {
	width: 70px;
	height: 24px;
	text-align: center;
	border: 1px solid #666;
}

</style>

<script>

var mismatches = []
var _pageIdx = 0
var _lastPageIdx = 0
var _perPage = 200
var statusClasses = ['white', 'yellow', 'red']
var pageControl$, dfc$;

function prepare() {
	for (let k in ENGLISH) {
		let rv = RUSSIAN_VANILLA[k]
		if (RUSSIAN[k] != rv) {
			if (rv && (rv.length > 3)) {
				let res = ''
				for (let i = 0; i < rv.length; i++) {
					if ((rv.length > i + 5) && (rv.charAt(i) == '\\') && (rv.charAt(i + 1) == 'u')) {
						res += String.fromCharCode(parseInt(rv.substring(i + 2, i + 6)))
					}
					else {
						res += rv.charAt(i)
					}
				}
				rv = res
			}
			mismatches.push({ key: k, eng: ENGLISH[k], rukb: RUSSIAN[k], rusv: rv, status: RUMMS[k] || 0 })
		}
	}
	_lastPageIdx = Math.ceil(mismatches.length / _perPage) - 1
	showMMPage(0)
}

function showMMPage(pageIdx) {
	if (pageIdx < 0 || pageIdx > _lastPageIdx) {
		return
	}
	_pageIdx = pageIdx
	$('input.pageInput').val(_pageIdx + 1)

	let table = $('<table id="differencesTable">')
	$('<tr>').appendTo(table).append(
		$('<th>').text('Английский')
		, $('<th>').text('Русский оригинал')
		, $('<th>').text('Русский KB')
		, $('<th>').text('стутус')
	)
	for (let i = pageIdx * _perPage; (i < mismatches.length) && (i < (pageIdx + 1) * _perPage); i++) {
		let diff = mismatches[i]
		let row = $('<tr>').appendTo(table).append(
			$('<td>').text(diff.eng)
			, $('<td>').text(diff.rusv)
			, $('<td>').text(diff.rukb)
			, $('<td class="statuscontrols">').append(
				$('<div class="switch white">')
				, $('<div class="switch yellow">')
				, $('<div class="switch red">')
			).attr("mmidx", i)
		)
		let cls = statusClasses[diff.status]
		row.addClass(cls)
		row.find('.' + cls).addClass('active')
	}

	dfc$.empty()
	dfc$.append(table)

	//pageControl$.clear()
	//pageControl$.append(genPage(1))
}

function genPage(pageIdx) {
	return $('<div class="page" page="' + pageIdx + '">').text(pageIdx + 1).click(function() {
		showMMPage(parseInt(this.getAttribute("page")))
	})
}

function getMM() {
	let rumms = {}
	for (let i = 0; i < mismatches.length; i++) {
		let mm = mismatches[i]
		if (mm.status > 0) {
			rumms[mm.key] = mm.status
		}
	}
	console.log(rumms)
}

$(function() {
	dfc$ = $('#differencesTableContainer')
	pageControl$ = $('#pageControl')

	prepare()

	pageControl$.append(
		genPage(0)
		, $('<div class="page">').text('<').click(function() {
			showMMPage(_pageIdx - 1)
		})
		, $('<input class="pageInput">').val(_pageIdx + 1).keypress(function(e) {
			if (e.key == 'Enter') {
				showMMPage(parseInt(this.value) - 1)
			}
		})
		, $('<div class="page">').text('>').click(function() {
			showMMPage(_pageIdx + 1)
		})
		, genPage(_lastPageIdx)
	)

	$(document).on('click', '#differencesTable .switch.white', function () {
		updateStatus(this, 0)
	});
	$(document).on('click', '#differencesTable .switch.yellow', function () {
		updateStatus(this, 1)
	});
	$(document).on('click', '#differencesTable .switch.red', function () {
		updateStatus(this, 2)
	});
	function updateStatus(el, stat) {
		let el$ = $(el)
		let td$ = el$.parent()
		let mmobj = mismatches[parseInt(td$.attr("mmidx"))]
		mmobj.status = stat
		td$.find('.active').removeClass('active')
		el$.addClass('active')
		td$.parent().removeClass().addClass(statusClasses[stat])
	}
})
</script>

<!---/HEAD---->

<h3 class='post-title entry-title'>Сравнение с официальным переводом Minecraft (на момент 1.18 Pre-1)</h3>

<div class='post-body entry-content'>

<p>Для особой публики, я решил перечислить ВСЕ отличия моего перевода от официальной русской локализацией.</p>

<p>Сплеши и финальный текст сюда выкладывать смысла нет, т.к. их в оригинале нет вообще.</p>

<p>Белые - изменения вкусовые и стилистические.<br>Жёлтые - исправления грамматических ошибок, в т.ч. теряющихся связей во фразах в форматируемых строках.<br>Красные - исправления объективных ошибок перевода.</p>

<div id="differencesTableContainer"></div>
<div id="pageControl"></div>

</div>

<div class='post-footer'>
<div class='date-header'>Дата публикации: 13 ноября 2021 г.<!--<br>Последнее обновление: 16 октября 2021 г.--></div>
<div class="feedbackButton">Написать автору</div>
</div>