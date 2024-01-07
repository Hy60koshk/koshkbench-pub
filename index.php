<?php

$ADMINMAIL = "емейл_админа";

if (isset($_POST['mail'])) {
	$message = '';
	if (isset($_POST['message'])) {
		$message = $_POST['message'];
	} else {
		exit('fail:nomsg');
	}

	$email = $_POST['email'];
	$page = $_GET['page'];
	$page = count($page) > 0 ? $page : 'index';

	if (strlen($message) < 1) {
		exit('fail:nomsg');
	}
	$message = '<p>'.htmlspecialchars($message).'</p><p style="margin: 10pt 0 0 0;">Отправлено со страницы <a href="https://koshk.ru/koshkbench/?page='.$page.'">#'.$page.'</a>';
	if (strlen($email) > 0) {
		$message .= ' от '.htmlspecialchars($email);
	}
	$message .= '</p>';

	sendMail($ADMINMAIL, 'Вам сообщение', $message);
	exit('success');
}

$pageContentUrl = '';//'pages/index.php';

$page = 'minecraft-12001';
$noscript = false;

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}
else if (isset($_POST['page_id'])) {
	$page = $_POST['page_id'];
}
if (isset($_GET['noscript'])) {
	$noscript = !!$_GET['noscript'];
}

if (isset($_POST['user_assertion_hash'])) {
	if (isset($_POST['action'])) {
		$action = $_POST['action'];
	} else {
		exit('NO ACTION');
	}

	require_once $_SERVER['DOCUMENT_ROOT']."/phpsys/koshkdyn.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/phpsys/database.php";

	Database::$DB_CON_STR = "mysql:host=localhost;dbname=DATABASENAME";
	Database::$DB_UN = 'USERNAME';
	Database::$DB_PW = 'PASSWORD';

	try {
		switch ($action) {
			case 'add_comment': {

				session_start();
				if (!isset($_SESSION['sessStart'])) {
					apiResult(false, 'Отсутствует токен сессии. Пожалуйста, обновите страницу. Если проблема повторяется, то сообщите мне об этом через форму "Написать автору".');
				}

				$ip = $_SERVER['REMOTE_ADDR'];
				
				// тут можно сделать банлист
				if ($ip == '178.34.120.179') {
					apiResult(false, '35485D5B510014504C5000144C56534956694900144953674C64');
				}
				
				$commTime = time();
				$timePassed = $commTime - $_SESSION['lastCommentTime'];
				if ($timePassed < 60) {
					apiResult(false, 'Не частите так =) Вы сможете оставить комментарий через '.(61 - $timePassed).' сек.');
				}
				$name = require_param('name', 'name');
				if (strlen($name) < 2) {
					apiResult(false, 'Имя автора комментария не указано или слишком короткое');
				}

				$_SESSION['lastCommentTime'] = $commTime;
				session_write_close();

				$comment = require_param('comment', 'comment');
				$answer_to = optional_param('answer_to', null);
				
				$args = array(
					'page_id' => $page
					, 'name' => $name
					, 'comment' => $comment
					, 'answer_to' => $answer_to
					, 'ip' => $ip
				);

				Database::getInstance()->prepare(
					"INSERT INTO `comments_kb` (`name`, `ip`, `comment`, `page_id`, `answer_to`)
						VALUES (:name, :ip, :comment, :page_id, :answer_to);"
					)->execute($args);

				$result = 'OK';
				try {
					$message = '<p>'.htmlspecialchars($comment).'</p><p style="margin: 10pt 0 0 0; font-size: 0.9em;">Отправлено со страницы <a href="https://koshk.ru/koshkbench/?page='.$page.'">#'.$page.'</a>';
					if (strlen($name) > 0) {
						$message .= ' от '.htmlspecialchars($name);
					}
					$message .= '</p>';
					sendMail($ADMINMAIL, 'Новый комментарий', $message);
				}
				catch(Exception $mex) {
					$result = 'Failed to send email';
				}

				apiResult(true, $result);
			}
			case 'get_comments': {
				$sesstart = time();
				session_start();
				$_SESSION['lastCommentTime'] = 0;
				$_SESSION['sessStart'] = $sesstart;
				session_write_close();
				
				$comments = Database::getInstance()->getTable(
					"SELECT `id`, `name`, `comment`, `date`, `answer_to`
						FROM `comments_kb` where `page_id` = :page_id
						order by (`answer_to` IS NOT NULL), `date` desc
						limit 800;"
					, array('page_id' => $page)
				);

				apiResult(true, array(
					'message' => 'OK'
					, 'comments' => $comments
					, 'page' => $page
					, 'UAH' => crc32('UG'.$sesstart.'HH')
				));
			}
			case 'get_subscribed_comments': {
				//$subs = json_decode(require_param('subs', 'subs'), true);
				$subs = require_param('subs', 'subs');

				if (count($subs) < 1) {
					apiResult(true, array(
						'message' => 'OK'
						, 'subdata' => $subs
					));
				}

				$args = array();
				$where = array();
				for ($i = 0; $i < count($subs); $i++) {
					$args['p'.$i] = $subs[$i]['page_id'];
					$args['m'.$i] = $subs[$i]['last_id'];
					$where[] = '(`page_id` = :p'.$i.' and `id` > :m'.$i.')';
				}
				
				$comments = Database::getInstance()->getTable(
					"SELECT count(*) as `new_com_count`, `name`, `comment`, `page_id`
						FROM `comments_kb`
						where ".implode(' or ', $where)."
						group by `page_id`
						order by `date` desc;"
					, $args
				);
				
				apiResult(true, array(
					'message' => 'OK'
					, 'subdata' => $comments
				));
			}
		}
	} catch (Exception $e) {
		apiResult(false, $e->getMessage());// 'Внутренняя ошибка сервера. Если обновление страницы не помогает с ней справиться, то сообщите мне об этом через форму "Написать автору".');
	}
}

if (file_exists("pages/".$page.'.php')) {
	$pageContentUrl = "pages/".$page.'.php';
}
else {
	$pageContentUrl = 'pages/404.php';
}

$pageContent = file_get_contents($pageContentUrl);
$headerContent = "";

if ($pageContent == false) {
	$pageContent = "Ошибка отображения страницы";
}
else {
	$pageSplit = explode("<!---/HEAD---->", $pageContent);
	if (count($pageSplit) > 1) {
		$headerContent = $pageSplit[0];
		$pageContent = $pageSplit[1];
	}
}


function sendMail($to_email, $subject, $message) {
	$frm_name = "KoshkBench";
	$frm_email = "ТИПА ЕМЕЙЛ САЙТА";

$content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>'.$subject.' — KoshkBench</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body style="margin: 0;padding: 0;width: 100%;height: 100%;">
<table border="0" cellpadding="0" cellspacing="0" style="
	width: 600px;
	height: 100%;
	font-family: Verdana, sans-serif;
	background-color: rgb(188, 218, 250);
"><tbody>
	<tr>
		<td rowspan="3" width="10%" style=""></td>
		<td width="80%" style="
			padding: 7pt 40pt;
			font-size: 20pt;
			text-align: center;
			vertical-align: middle;
			background-position: left center, right center;
			background-repeat: no-repeat;
			background-size: auto 80%;
			background-repeat: no-repeat;text-align:center;
			background-image:url(\'https://koshk.ru/koshkbench/pics/mail_bench.png\'), url(\'https://koshk.ru/koshkbench/pics/mail_bench.png\');
		">
			'.$subject.'
		</td>
		<td rowspan="3" width="10%" style=""></td>
	</tr>
	<tr>
		<td style="vertical-align: top;
		background-color: #f8f8f6;
		color: #000;
		font-size: 14pt;
		padding: 30pt 40pt;">
			'.$message.'
		</td>
	</tr>
	<tr>
		<td style="padding: 8pt 20pt;">--- <a href="https://koshk.ru/koshkbench/">koshk.ru/koshkbench</a> ---</td>
	</tr>
</tbody></table>
</body>
</html>';

$headers = "MIME-Version: 1.0".PHP_EOL.
"Content-Type: text/html; charset=utf-8".PHP_EOL.
'From: =?UTF-8?B?'.base64_encode($frm_name).'?= <'.$frm_email.'>'.PHP_EOL.
'Reply-To: '.$ADMINMAIL.PHP_EOL;

	mail($to_email, $subject, $content, $headers);
}

$menu = '[
	{
		"Name":"Русификаторы Minecraft",
		"Pages":[
			{
				"Caption":"Перевод для 1.20.1",
				"Link":"minecraft-12001"
			},
			{
				"Caption":"Перевод для 1.19.1",
				"Link":"minecraft-11900"
			},
			{
				"Caption":"Перевод для 1.18.2",
				"Link":"minecraft-11800"
			},
			{
				"Caption":"Перевод для 1.17.1",
				"Link":"minecraft-11700"
			},
			{
				"Caption":"Перевод для 1.16.5",
				"Link":"minecraft-11604"
			},
			{
				"Caption":"Перевод для 1.12",
				"Link":"minecraft-11200"
			},
			{
				"Caption":"Перевод для 1.11.2",
				"Link":"minecraft-11102"
			},
			{
				"Caption":"Перевод для 1.10.2",
				"Link":"minecraft-11002"
			},
			{
				"Caption":"Перевод для 1.9.4",
				"Link":"minecraft-10904"
			},
			{
				"Caption":"Перевод для 1.8.9",
				"Link":"minecraft-10809"
			},
			{
				"Caption":"Перевод для 1.7.10",
				"Link":"minecraft-10710"
			},
			{
				"Caption":"Перевод 1.7.4—1.7.9",
				"Link":"minecraft-10709"
			},
			{
				"Caption":"Перевод 1.7.4 (старый)",
				"Link":"minecraft-10704"
			},
			{
				"Caption":"Русификатор для 1.6.4",
				"Link":"minecraft-10604"
			},
			{
				"Caption":"Русификатор для 1.5.2",
				"Link":"minecraft-10502"
			},
			{
				"Caption":"Русификатор для 1.4.7",
				"Link":"minecraft-10407"
			}
		]
	},
	{
		"Name":"Шрифты для Minecraft",
		"Pages":[
			{
				"Caption":"Шрифты для 1.13",
				"Link":"mc-font-113"
			},
			{
				"Caption":"Шрифты для 1.9—1.12",
				"Link":"mc-font-109"
			},
			{
				"Caption":"Шрифт для 1.7—1.8",
				"Link":"mc-font-108"
			},
			{
				"Caption":"Шрифты для 1.7.4",
				"Link":"mc-font-107"
			}
		]
	},
	{
		"Name":"Прочие статьи",
		"Pages":[
			{
				"Caption":"TrueType шрифты",
				"Link":"fonts"
			}
		]
	}
]';

?>


<!DOCTYPE html>
<html>
<head>
<meta content='text/html; charset=UTF-8' http-equiv='Content-Type'/>
<link href='favicon.png' rel='icon' type='image/png'/>
<title>KoshkBench</title>

<link href='https://koshk.ru/css/kbcore.css' rel='StyleSheet' type='text/css'/>
<link href='https://koshk.ru/koshkbench/css/kbench.css?v=2310210500' rel='StyleSheet' type='text/css'/>

<?php
if (!$noscript) {
	//echo '<noscript><meta http-equiv="refresh" content="0; URL=https://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI].'"></noscript>';
}
?>
<script language='JavaScript' src='/js/jquery-3.5.1.min.js' type='text/javascript'></script>
<script language='JavaScript' src='/js/kb.collections.js' type='text/javascript'></script>
<script language='JavaScript' src='/js/kb.slider.js' type='text/javascript'></script>
<script language='JavaScript' src='/js/koshkbench_1.6.js?v=220617' type='text/javascript'></script>
<script language="JavaScript" src="/js/koshkdyn_0.1.js?v=210710" type="text/javascript"></script>


<!--<meta content='0db7dd8bd452e576' name='yandex-verification'/>
<meta content='29d39623559b64bd' name='wmail-verification'/>-->
<meta content='width=860' name='viewport'/>
<meta content='minecraft, rus, русификатор, перевод, шрифт, майнкрафт, snapshot, 1.20.1, 1.20, 1.19.1, 1.19, wild update, 1.18.2, 1.18, 1.17.1, 1.17, 1.16, 1.16.3, 1.16.4, 1.16.5, nether update, 1.13, 1.12, 1.11.2, 1.11, 1.10, 1.10.2, 1.9, 1.9.4, 1.8, 1.8.9, 1.7, 1.7.10, 1.7.4, 1.6.4, 1.5.2, font, optifine, русский, dokucraft, ресурспак, resourcepack, перевод модов, перевод minecraft, русифекатор' name='Keywords'/>
<script>
<?php
echo 'var _page_id = "'.$page.'";';
?>
var _UAH = null, _commentMap = {};
var cookieExpDate = 'Fri, 30 Dec 2050 13:37:00 GMT'

;var CookieObject = (function() {
	var cookieObj = {}
	var decodedCookie = decodeURIComponent(document.cookie);
	var cookies = decodedCookie.split(';');

	for (let i = 0; i < cookies.length; i++) {
		let cookie = cookies[i].split('=')
		if (cookie.length < 2) continue
		let cookieKey = cookie[0].trim()
		switch (cookieKey) {
			case 'Allow':
				cookieObj[cookieKey] = cookie[1] == 'true' ? true : false
				break
			case 'Subscriptions':
				cookieObj[cookieKey] = JSON.parse(cookie[1])
				break
			default:
				cookieObj[cookieKey] = cookie[1]
				break
		}
	}
	if (!cookieObj.Subscriptions) {
		cookieObj.Subscriptions = []
	}

	return cookieObj
})();

function checkCookie(purpose, callback) {
	if (CookieObject.Allow === undefined) {
		(new KBDialog({
			title: "Мы немножко используем куки"
			, id: 'CookieForm'
			, content: $('<div>').append(
				$('<div>').text(purpose)
				, $('<br>')
				, $('<div>').text('Вы разрешаете нам использовать куки?')
			)
			, dispose: true
			, buttons: [ {
				caption: 'Запретить'
				, action: function(_dialog) {
					CookieObject.Allow = false
					document.cookie = 'Allow=false; expires=' + cookieExpDate;
					_dialog.Hide()
				}
			}, {
				caption: 'Разрешить'
				, primary: true
				, action: function(_dialog) {
					CookieObject.Allow = true
					document.cookie = 'Allow=true; expires=' + cookieExpDate;
					_dialog.Hide()
					if (callback) {
						callback()
					}
				}
			}]
		})).Show();
	}
	else if (CookieObject.Allow && callback) {
		callback()
	}
}

$(function() {
	var subscription = CookieObject.Subscriptions.findIndex(s => s.page_id == _page_id)
	var commLastId = 0

	function initCommentForm(container$, replyId) {
		container$.empty()
		let textField$ = $('<textarea style="resize: vertical">')
		let usernameField$ = $('<input>')
		let commentButton$ = $('<div class="submitBtn">Отправить</div>').click(function() {
			let opts = {
				action: 'add_comment'
				, user_assertion_hash: _UAH
				, name: usernameField$.val()
				, comment: textField$.val()
				, page_id: _page_id
			}
			if (replyId) {
				opts.answer_to = replyId
			}
			api_request({
				options: opts
				, onSuccess: function(data) {
					getComments()
				}
			})
		})

		function sub() {
			jqt = $(this)
			checkCookie('Чтобы отображать оповещения о новых комментариях к новостям, мы используем куки.', function() {
				CookieObject.Subscriptions.push({
					page_id: _page_id
					, last_id: commLastId
				})
				subscription = CookieObject.Subscriptions.length - 1
				document.cookie = 'Subscriptions=' + JSON.stringify(CookieObject.Subscriptions) + '; expires=' + cookieExpDate;
				jqt.parent().append($(unsubHtml).click(unsub))
				jqt.remove()
			})
		}
		function unsub() {
			CookieObject.Subscriptions.splice(subscription, 1)
			subscription = -1
			document.cookie = 'Subscriptions=' + JSON.stringify(CookieObject.Subscriptions) + '; expires=' + cookieExpDate
			jqt = $(this)
			jqt.parent().append($(subHtml).click(sub))
			jqt.remove()
		}

		let subHtml = '<div class="subscribeBtn" title="Отображать уведомления о новых комментариях к этому посту">Подписаться</div>'
		let unsubHtml = '<div class="subscribeBtn" title="Перестать отображать уведомления о новых комментариях к этому посту">Отписаться</div>'
		
		container$.append(
			$('<div class="title">Оставить комментарий</div>')
			, $('<div class="post-newcomment-form-user">').append(
				$('<div class="name">Ваше имя:</div>')
				, usernameField$
			)
			, textField$
			, $('<div class="btnBox">').append(commentButton$, subscription < 0 ? $(subHtml).click(sub) : $(unsubHtml).click(unsub))
		)
	}

	function getPluralForm(num, one, two, many) {
		let dec = num % 100
		if (dec > 10 && dec < 20) {
			return many
		}
		let ed = num % 10
		if (ed == 0 || ed > 4) {
			return many
		}
		if (ed == 1) {
			return one
		}
		return two
	}

	function getComments() {
		_FLAT_MENU = {}
		for (let sect of _MENU) {
			for (let page of sect.Pages) {
				_FLAT_MENU[page.Link] = page.Caption
			}
		}

		api_request({
			options: {
				action: 'get_comments'
				, page_id: _page_id
				, user_assertion_hash: true
			}
			, onSuccess: function(data) {
				_UAH = data.UAH
				var comments$ = $('.post-comments')
				
				var commentForm$ = $('.post-newcomment-form')
				if (commentForm$.length) {
					initCommentForm(commentForm$)
				}

				if (comments$.length) {
					comments$.empty()
					_commentMap = {}
					//console.log(data)
					if (data.comments.length) {
						let i = 0;
						for (i; i < data.comments.length; i++) {
							let comm = data.comments[i]
							if (comm.answer_to) {
								break;
							}
							addComment(comm, comments$)
							if (comm.id > commLastId) {
								commLastId = comm.id
							}
						}

						let j = data.comments.length - 1;
						for (j; i <= j; j--) {
							let comm = data.comments[j]

							let targetComment = _commentMap[comm.answer_to]

							if (targetComment) {
								addComment(comm, targetComment.replies$)
							}
							if (comm.id > commLastId) {
								commLastId = comm.id
							}
						}
					}
				}

				if (CookieObject.Allow && CookieObject.Subscriptions) {
					api_request({
						options: {
							action: 'get_subscribed_comments'
							, subs: CookieObject.Subscriptions
							, user_assertion_hash: _UAH
						}
						, onSuccess: function(data) {
							if (subscription > -1) {
								CookieObject.Subscriptions[subscription].last_id = commLastId
								document.cookie = 'Subscriptions=' + JSON.stringify(CookieObject.Subscriptions) + '; expires=' + cookieExpDate;
							}
							if (data.subdata.length) {
								let notifications$ = $('.notifications-container')
								var notificationsList$ = $('<div class="notifications-list">')

								notifications$.show().append(
									$('<div class="notifications-title">').text("Оповещения")
									, notificationsList$
								)
								
								data.subdata.forEach(function(comment) {
									if (_FLAT_MENU[comment.page_id]) {
										let pageTitle = _FLAT_MENU[comment.page_id]
										let commentText = comment.name + ': "'
										commentText += comment.comment.length > 40 ? (comment.comment.substring(0, 37) + '..."') : (comment.comment + '"')
										if (comment.new_com_count > 1) {
											commentText += ' и ещё ' + (comment.new_com_count - 1) + ' комментариев'
										}

										let countText = getPluralForm(comment.new_com_count, ' новый комментарий', ' новых комментария', ' новых комментариев')

										notificationsList$.append(
											$('<div class="notification new-comment">').append(
												$('<div class="notification-title">').text(comment.new_com_count + countText + " к записи ").append(
													$('<a href="https://koshk.ru/koshkbench/?page=' + comment.page_id + '">').text(pageTitle)
												)
												, $('<div class="notification-body">').text(commentText)
											)
										)
									}
								})
							}
						}
						, onFail: function(data) {
							console.warn(data)
						}
					})
				}
			}
			, onFail: function(data) {
				console.warn(data)
				$('.post-newcomment-form').hide()
				$('.post-comments').hide()
			}
		})

		function addComment(comm, commentsList$) {
			_commentMap[comm.id] = comm
			let commDate = unix_to_date(comm.date)
			let replyForm$ = $('<div class="post-comment-replyForm">')
			let replyBtn$ = $('<div class="post-comment-replyBtn">Ответить</div>')
			replyForm$[0]._commentId = comm.id
			replyBtn$[0]._replyForm$ = replyForm$
			comm.replies$ = $('<div class="post-comment-replies">')
			commentsList$.append(
				$('<div class="post-comment">').append(
					$('<div class="post-comment-header">').append(
						$('<div class="username">').text(comm.name)
						, $('<div class="date">').text(commDate)
					)
					, $('<div class="post-comment-content">').append(
						$('<div class="comment">').bbCode(comm.comment)
					)
					, $('<div class="post-comment-footer">').append(
						replyForm$.append(replyBtn$)
						, comm.replies$
					)
				)
			)
		}
	}

	getComments()

	$(document).on('click', '.post-comment-replyBtn', function () {
		initCommentForm(this._replyForm$, this._replyForm$[0]._commentId)
	})

	/* Сбор статистики

	setTimeout(function() {
		api_request({
			apiUrl: '/visc.php'
			, options: {
				action: 'count_in'
				, page_id: _page_id
				, page_url: 'https://koshk.ru/koshkbench/'
			}
			, onFail: function(data) {
				console.warn(data)
			}
		})
	}, 1000)
	
	$(document).on('click', '.kbDlButton', function () {
		let dlid = ''
		if (this.hasAttribute('dlid')) {
			dlid = this.getAttribute('dlid')
		}
		api_request({
			apiUrl: '/visc.php'
			, options: {
				action: 'count_in'
				, page_id: _page_id + '_dl' + dlid
				, page_url: 'https://koshk.ru/koshkbench/'
			}
			, onFail: function(data) {
				console.warn(data)
			}
		})
	})

	*/

	$(document).on('click', '.feedbackButton', function () {
		var form = $('<div class="mailForm">').append(
			$('<span>Ваш e-mail для обратной связи*:  </span>')
			, $('<input class="feedbackemail" />')
			, $('<br>')
			, $('<div style="font-size:11px; padding: 3px 40px 8px 0;">').text('*обратите внимание, что это малоизвестный блог, а потому ответные сообщения могут попасть вам в папку «Спам». Поэтому не забывайте проверять папку спама, если сильно ждёте ответа от меня.')
			, $('<div>Ваше сообщение:</div>')
			, $('<textarea rows=5 class="feedbackmessage"></textarea>')
		);
		(new KBDialog({
			title: "Форма обратной связи"
			, id: 'FeedbackForm'
			, content: form
			, dispose: true
			, buttons: [ {
				caption: 'Отмена'
			}, {
				caption: 'Отправить'
				, primary: true
				, action: function(_dialog) {
					$.post('#', 
						{
							mail: true
							, email: $('#FeedbackForm .feedbackemail').val()
							, message: $('#FeedbackForm .feedbackmessage').val()
						}
						, function(data) {
							var msg = "Получен некорректный ответ от сервера"
							var action_ = function(_msgDialog) {
								_msgDialog.Hide()
							}
							if (data && data.length) {
								if (data == "fail:nomsg") {
									msg = "Ошибка: вы пытаетесь отправить пустое сообщение"
								}
								else if (data == "success") {
									msg = "Сообщение успешно отправлено!"
									action_ = function(_msgDialog) {
										_msgDialog.Hide()
										_dialog.Hide()
									}
								}
							}
							showMessageBox({
								Message: msg
								, Buttons: [{
									caption: 'OK'
									, action: action_
								}]
							})
						}
					)
				}
			}]
		})).Show();
	});

	$(document).on('click', '.permissionsButton', function () {
		let permId = this.hasAttribute('permid') && this.getAttribute('permid')

		if (window.PERMISSIONS_ && permId && window.PERMISSIONS_[permId]) {
			let perm$ = $('<div class="permsText">')
			let txt = window.PERMISSIONS_[permId]
			if (typeof txt == 'object' && txt instanceof Array) {
				for (let line of txt) {
					perm$.append($('<p>').text(line))
				}
			}
			else {
				perm$.append($('<p>').text(txt))
			}
			(new KBDialog({
				title: "Условия распространения контента"
				, id: 'PermissionsForm'
				, content: perm$
				, dispose: true
				, buttons: [ {
					caption: 'Закрыть'
				}]
			})).Show()
		}
	});

	$('.kbspoilerheader').each(function() {
		let spoilerId = this.hasAttribute('for') && this.getAttribute('for')
		if (spoilerId) {
			let spoilerCont = $('#' + spoilerId)
			if (spoilerCont.length && !$(this).hasClass('expanded')) {
				spoilerCont.hide()
			}
		}
	}).click(function() {
		let spoilerId = this.hasAttribute('for') && this.getAttribute('for')
		if (spoilerId) {
			let this$ = $(this)
			let spoilerCont = $('#' + spoilerId)
			if (spoilerCont.length) {
				if (spoilerCont.hasClass('expanded')) {
					spoilerCont.slideUp(300, function() {
						spoilerCont.removeClass('collapsing')
						this$.removeClass('collapsing')
					})
					spoilerCont.addClass('collapsing').removeClass('expanded')
					this$.addClass('collapsing').removeClass('expanded')
				} else {
					spoilerCont.slideDown(300, function () {
						spoilerCont.removeClass('expanding').addClass('expanded')
						this$.removeClass('expanding').addClass('expanded')
					})
					spoilerCont.addClass('expanding')
					this$.addClass('expanding')
				}
			}
		}
	})
})
var _FLAT_MENU, _MENU = <?php echo $menu; ?>

</script>

<?php
echo $headerContent;
?>

</head>
<body>

<div class='page-content'>

<div class="notifications-container"></div>

<div class="kbHeader"><a href='https://koshk.ru/koshkbench/'>KoshkBench</a><a href='https://koshk.ru/'>koshk.ru</a></div>

<div class='page-content-grid'>

<div class='kb-side-menu' id="sideMenu">
<?php
$menu = json_decode($menu, true);

for ($i = 0; $i < count($menu); $i++) {
	$section = $menu[$i];
	echo '<div class="menuSection"><div class="menuSectionTitle">'.$section['Name'].'</div><div class="menuSectionPages">';
	for ($j = 0; $j < count($section['Pages']); $j++) {
		$page = $section['Pages'][$j];
		echo '<a class="menuSectionLink" href="https://koshk.ru/koshkbench/?page='.$page['Link'].'">'.$page['Caption'].'</a>';
	}
	echo '</div></div>';
}
?>
</div>

<div class='article-content'>

<?php

/*$ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0; rv:11.0') !== false) || (strpos($ua, 'Opera/9.80') !== false)) {
	echo '<h3 class="post-title entry-title">Мы не дружим с Internet Explorer. Пожалуйста, зайдите на эту страницу с любого другого браузера.</h3>
	<div class="post-body entry-content" style="font-size: 1.08rem;">Ссылки на некоторые из «любых других браузеров»:
	<br><a href="https://www.mozilla.org/ru/firefox/new/">Firefox</a>
	<br><a href="https://www.opera.com/ru">Opera</a>
	<br><a href="https://brave.com/">Brave</a>
	<br><a href="https://www.waterfox.net/">Waterfox</a>
	<br>
	<br>Также, конечно же, мы поддерживаем Chrome, MS Edge, Safari, Yandex и большинство мобильных браузеров.</div>';
}
else {*/
	echo $pageContent;
	echo '<div class="post-newcomment-form"></div><div class="post-comments"></div>';
//}
?>

</div>

</div>
</div>
</body>
</html>