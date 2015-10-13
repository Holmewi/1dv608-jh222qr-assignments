<?php

namespace model;

class UserDAL {

	private $conn;
	private static $table = "users";
	private static $colUsername = "username";

	public function __construct(\PDO $conn) {
		$this->conn = $conn;
	}

	public function checkDuplicateUsername(\model\RegisterCredentials $regCred) {

		// SOURCE: https://www.youtube.com/watch?v=1EjPUJ5QLSY

		$smt = $this->conn->prepare("SELECT " . self::$colUsername . " FROM " . self::$table . " WHERE " . self::$colUsername . "='" . $regCred->getUserName() . "'");

		if($smt->execute()) {
			if($smt->fetchAll() !== null) {
				//var_dump("Username did not exist");
				return true;
			} else {
				//var_dump("Username already exists");
				return false;
			}
		}
	}
} 