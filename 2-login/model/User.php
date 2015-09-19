<?php

namespace model;

// This model simulates an user account
class User {
	private $username = "Admin";
	private $password = "Password";
	private static $loginStatus = "loginStatus";

	public function __construct() {

	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function login() {
		$_SESSION[self::$loginStatus] = true;
		$_SESSION['Username'] = $this->username;
	}

	public function logout() {
		unset($_SESSION[self::$loginStatus]);
		unset($_SESSION['Username']);
	}

	public function getLoginStatus() {
		if(!isset($_SESSION[self::$loginStatus])) {
			$_SESSION[self::$loginStatus] = false;
		}
		return $_SESSION[self::$loginStatus];
	}
}