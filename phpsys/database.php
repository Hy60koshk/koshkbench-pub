<?php

class Database extends PDO
{
	private static $database = null;
	
	private static $session_vars_set = false;

	public static $DB_CON_STR = null;
	public static $DB_UN = null;
	public static $DB_PW = null;
	
	public function __construct($set_db_session_vars = true)
	{
		parent::__construct(self::$DB_CON_STR, self::$DB_UN, self::$DB_PW); 
		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public static function getInstance($set_db_session_vars = true) {
		if (self::$database) {
			return self::$database;
		} else {
			return self::$database = new Database($set_db_session_vars);
		}
	}
	
	/**
	 * Фетчит первую строку
	 * Оптимально передавать sql, который будет изначально расчитан на получение только одной строки
	 * 
	 * @param string $sql текст запроса
	 * @param string $params параметры
	 * @return array ассоциативный массив со значениями первой строки
	 */
	public function getSingleRow($sql, $params = null) {
		$q = $this->prepare($sql);
		if ($params != null) {
			$q->execute($params);
		} else $q->execute();
		return $q->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Возвращает значение первого столбца первой строки.
	 * Оптимально передавать sql, который будет изначально расчитан на получение ровно одного значения
	 * 
	 * @param string $sql текст запроса
	 * @param string $params параметры
	 * @return любой значение первого столбца первой строки
	 */
	public function getSingleColumn($sql, $params = null) {
		$q = $this->prepare($sql);
		if ($params != null) {
			$q->execute($params);
		} else {
			$q->execute();
		}
		return $q->fetchColumn();
	}

	public function getValue($sql, $params = null) {
		return $this->getSingleColumn($sql, $params);
	}
	
	/**
	 * Фетчит всю таблицу.
	 * 
	 * @param string $sql текст запроса
	 * @param string $params параметры
	 * @return array массив строк ассоциативных массивов со значениями
	 */
	public function getTable($sql, $params = null) {
		$q = $this->prepare($sql);
		if ($params != null) {
			$q->execute($params);
		} else {
			$q->execute();
		}
		return $q->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getTableNum($sql, $params = array()) {
		$q = $this->prepare($sql);
		$q->execute($params);
		return $q->fetchAll(PDO::FETCH_NUM);
	}
	
	public function getTableAssoc($sql, $params = array()) {
		$q = $this->prepare($sql);
		$q->execute($params);
		return $q->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>