<?php

namespace session\model;
use PDO,
	PDOException;

/**
 * Database class.
 */
class Database
{
	private static $connection;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if (!self::$connection) {
			$this->connect();
		}
	}

	/**
	 * Connects to MySQL database.
	 *
	 * @return bool   True if successfully connected, false if not.
	 */
	public function connect() {
		try {
			self::$connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
			// Successfully connected to database.
			return true;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * Executes a query and returns the result.
	 *
	 * @param string $query              The query to execute.
	 * @param array $params [optional]   The parameters of the query.
	 * @param mixed $type [optional]     Type of the result to return.
	 * @return mixed   The result set or true/false if $type is false.
	 */
	public function query($query, $params = array(), $type = false) {
		$st = self::$connection->prepare($query);
		// Bind parameters.
		foreach ($params as $key => $value) {
			$paramType = is_numeric($value)
			           ? PDO::PARAM_INT
			           : (is_bool($value)
			           		? PDO::PARAM_BOOL
			           		: PDO::PARAM_STR);
			$st->bindValue(':' . $key, $value, $paramType);
		}
		if ($st->execute()) {
			switch ($type) {
				case 'array':
					// Fetch row as array.
					$row = $st->fetch(PDO::FETCH_ASSOC);
					break;
				case 'object':
					// Fetch row as object.
					$row = $st->fetch(PDO::FETCH_OBJ);
					break;
				case 'column':
					// Fetch column.
					$row = $st->fetchColumn();
					break;
				default:
					return true;
					break;
			}
			return $row;
		}
		return false;
	}

	/**
	 * Destructor.
	 */
	public function __destruct() {
		self::$connection = null;
	}
}
