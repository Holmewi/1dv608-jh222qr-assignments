<?php

namespace model;

require_once('./model/UserModel.php');

class LoginModel {

	private static $username = "Admin";
	private static $password = "Password";

	private static $usernameCookie = "Username";
	private static $passwordCookie = "Password";
	private static $sessionID = "controller::LoginController::loginStatus";

	private $user;

	public function __construct(\view\LoginView $v) {
		$this->v = $v;
	}

	public function authenticate() {

		if(empty($this->v->getUsernameInput())) {
			$this->v->setMessage('Username is missing');
		}
	
		else if(empty($this->v->getPasswordInput())) {
			$this->v->setMessage('Password is missing');
		}
		else if($this->v->getUsernameInput() === self::$username && $this->v->getPasswordInput() == self::$password) {
			$this->user = new \model\UserModel(self::$username, self::$password);

			return true;
		}
		else {
			$this->v->setMessage('Wrong name or password');

			return false;
		}
	}

	public function doLogin() {
		$this->v->setMessage('Welcome');

		$_SESSION[self::$sessionID] = true;

		if($this->v->getKeepRequest()) {
			setcookie(self::$usernameCookie, 'Username', time() + 60 * 60 * 24 * 365);
			$_COOKIE[self::$usernameCookie] = $this->user->getUsername();

			$unique = sha1(rand());
			setcookie(self::$passwordCookie, $unique, time() + 60 * 60 * 24 * 365);
			$_COOKIE[self::$passwordCookie] = $this->user->getPassword();
		}
	}

	public function doLogout() {
		$this->v->setMessage('Bye bye!');

		unset($_SESSION[self::$sessionID]);

		// Deleting cookies
		// Source from http://www.programmerinterview.com/index.php/php-questions/how-to-delete-cookies-in-php/
		setcookie(self::$usernameCookie, '', time()-300);
		setcookie(self::$passwordCookie, '', time()-300);
	}

	public function getLoginStatus() {
		if(!isset($_SESSION[self::$sessionID])) {
			$_SESSION[self::$sessionID] = false;
		}
		return $_SESSION[self::$sessionID];
	}

	public function doCookieLogin() {
		if(isset($_COOKIE[self::$passwordCookie])) {
			$this->doLogin();
		}
		
	}
}