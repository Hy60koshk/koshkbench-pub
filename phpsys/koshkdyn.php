<?php

$LOGGING_ON = false;
$scriptStart = microtime(true) * 1000;

// Журналирование текста в файл с заданным именем. Производит ротацию, если размер файла превысит 25 МБ
function logFile($filename, $text) {
	$filename = '/var/www/html/logs/'.$filename;
	if (!file_exists($filename)) {
		touch($filename); // Если файла нет, то создается
	}
	if (filesize($filename) > 10 * 1024 * 1024) {
		$filename2 = "$filename.old";
		if (file_exists($filename2))
			unlink($filename2);
		rename($filename, $filename2);
		touch($filename);
	}
	if (is_writable($filename)) {
		$handle = fopen($filename, 'a');
		fwrite($handle, $text);
		fclose($handle);
	}
}

function logMessage($message) {
	$name = "default";
	if (isset($_SESSION)) {
		if (isset($_SESSION['logging']) && !$_SESSION['logging']) {
			return false;
		}
		if (isset($_SESSION['app'])) {
			$name = $_SESSION['app'];
		}
	}

	$mes = date('d.m.Y H:i:s') . "\r\n" . $message . "\r\n";

	logFile($name.'.txt', $mes);
}

function require_param($param_name, $userfriendly_name, $params_arr = false, $as_image = false) {
	if ($params_arr) {
		if (!isset($params_arr[$param_name])) {
			if ($as_image) {
				returnImageError("Отсутствует params_arr|параметр: $userfriendly_name");
				exit();
			}
			else {
				throw new Exception("Отсутствует params_arr параметр: $userfriendly_name");
			}
		}
		return $params_arr[$param_name];
	}
	else {
		if (!isset($_POST[$param_name])) {
			if ($as_image) {
				returnImageError("Отсутствует параметр:|$userfriendly_name");
				exit();
			}
			else {
				throw new Exception("Отсутствует параметр: $userfriendly_name");
			}
		}
		return $_POST[$param_name];
	}
}

function optional_param($param_name, $default = null, $params_arr = false) {
	if ($params_arr) {
		if (isset($params_arr[$param_name])) {
			return $params_arr[$param_name];
		}
		return $default;
	}
	else {
		if (isset($_POST[$param_name])) {
			return $_POST[$param_name];
		}
		return $default;
	}
}

function apiResult($success, $result = null) {
	try {
		global $scriptStart, $LOGGING_ON;

		$execution_time = round(microtime(true) * 1000 - $scriptStart);
		if (is_array($result)) {
			$result["success"] = ($success === true);
			$result["execution_time"] = $execution_time;
		}
		else {
			$result = array(
				'success' => ($success === true)
				, 'execution_time' => $execution_time
				, 'message' => $result
			);
		}
		$result = json_encode($result);
		if ($LOGGING_ON) {
			logMessage($result);
		}
		exit($result);
	}
	catch (Exception $e) {
		safeApiResult(false, array('preresult' => $result));
	}
}

function safeApiResult($success, $result = null) {
	if (is_array($result)) {
		$result["success"] = ($success === true);
	}
	else {
		$result = array(
			'success' => ($success === true)
			, 'message' => $result
		);
	}
	$result = json_encode($result);
	exit($result);
}

?>