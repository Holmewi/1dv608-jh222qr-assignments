<?php

namespace model;

class UserDAL {

	private $conn;
	private static $table = "users";
	private static $colUsername = "username";
	private static $colPassword = "password";

	public function __construct(\PDO $conn) {
		$this->conn = $conn;
	}

	public function checkUsernameExists(\model\RegisterCredentials $regCred) {

		// SOURCE: https://www.youtube.com/watch?v=1EjPUJ5QLSY
		$smt = $this->conn->prepare("SELECT " . self::$colUsername . " FROM " . self::$table . " WHERE " . self::$colUsername . "=?");

		if($smt->execute([$regCred->getUserName()])) {
			if($smt->fetchAll() == null) {
				//var_dump("Username is ok");
				return false;
			} else {
				//var_dump("Username already exists");
				return true;
			}
		}
	}

	public function addUserToDatabase(\model\RegisterCredentials $regCred) {

		$smt = $this->conn->prepare("INSERT INTO " . self::$table . " (" . self::$colUsername . ", " . self::$colPassword . ") 
										VALUES ('" . $regCred->getUserName() . "', '" . $regCred->getPassword() . "')");

		$smt->execute();
	}

	public function getUserFromDatabase(\model\UserCredentials $uc) {

		$smt = $this->conn->prepare("SELECT * FROM " . self::$table . " WHERE " . self::$colUsername . "=?");

		if($smt->execute([$uc->getName()])) {
			return $smt->fetchObject();
		}
	}
} 