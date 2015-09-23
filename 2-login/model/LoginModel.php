<?php

namespace model;

require_once('./model/UserModel.php');

class LoginModel {

	private static $username = "Admin";
	private static $password = "Password";
	private static $sessionID = "LoginController::LoginStatus";
	private static $folder = "../data/";

	private $user;

	public function __construct(\view\LoginView $v) {
		$this->v = $v;
	}

	public function authenticate(\model\UserModel $user) {
		$this->user = $user;
		if($user->getUsername() === self::$username && $user->getPassword() == self::$password) {
			return true;
		}
		else {
			return false;
		}
	}

	public function doLogin() {	
		$_SESSION[self::$sessionID] = true;
	}

	public function doKeepLogin() {	
		$uniqueString = sha1(rand());
		$filename = $this->user->getUsername() . "::" . $uniqueString;

		setcookie($this->v->getCookiePasswordString(), $filename, time() + 60 * 60 * 24 * 365);
		$_COOKIE[$this->v->getCookiePasswordString()] = $filename;

		file_put_contents($this->getFileRoot($filename), "");
	}

	public function doLogout() {
		unset($_SESSION[self::$sessionID]);
		session_destroy();

		// Deleting file
		// Source from http://php.net/manual/en/function.unlink.php
		@unlink($this->getFileRoot($_COOKIE[$this->v->getCookiePasswordString()]));
		
		// Deleting cookies
		// Source from http://www.programmerinterview.com/index.php/php-questions/how-to-delete-cookies-in-php/
		setcookie($this->v->getCookiePasswordString(), '', time()-300);

	}

	public function getLoginStatus() {
		if(!isset($_SESSION[self::$sessionID])) {
			$_SESSION[self::$sessionID] = false;
		}
		return $_SESSION[self::$sessionID];
	}

	public function doCookieLogin() {
		if(isset($_COOKIE[$this->v->getCookiePasswordString()])) {
			if(file_exists($this->getFileRoot($_COOKIE[$this->v->getCookiePasswordString()]))){
				return true;
			}
		}
		return false;
	}

	public function getFileRoot($filename) {
		return self::$folder . $filename;
	}
}