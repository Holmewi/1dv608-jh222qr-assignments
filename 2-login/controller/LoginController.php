<?php 
namespace controller;

require_once('./view/LoginView.php');
require_once('./view/DateTimeView.php');
require_once('./view/LayoutView.php');
require_once('./model/UserModel.php');

class LoginController {
	private static $sessionID = "controller::LoginController::loginStatus";
	private static $usernameSession = "Username";
	private static $passwordSession = "Password";

	private $user;
	private $v;
	private $dtv;
	private $lv;

	public function __construct() {
		$this->user = new \model\UserModel(); // This model object is a simulation of an user
		$this->v = new \view\LoginView();
		$this->lv = new \view\LayoutView();
		$this->dtv = new \view\DateTimeView();
	}

	public function doRequest() {

		$this->v->setMessage('');

		if($this->v->getLoginRequest()) {
			if(empty($this->v->getUsernameInput())) {			
				$this->v->setMessage('Username is missing');
			}
			else if(empty($this->v->getPasswordInput())) {
				$this->v->setMessage('Password is missing');
			}
			else if($this->v->getUsernameInput() == $this->user->getUsername() && $this->v->getPasswordInput() == $this->user->getPassword()) {
				$this->doLogin();
				$this->v->setMessage('Welcome');	
			}
			else {
				$this->v->setMessage('Wrong name or password');
			}
		} 
		else if($this->v->getLogoutRequest() && $this->getLoginStatus()) {
			$this->doLogout();
			$this->v->setMessage('Bye bye!');
		}

		$this->lv->render($this->getLoginStatus(), $this->v, $this->dtv);

		if(!isset($_COOKIE[self::$usernameSession]) && $this->getLoginStatus()) {
			exit();
		}
	}

	public function doLogin() {
		$_SESSION[self::$sessionID] = true;

		setcookie(self::$usernameSession, 'Username', time() + 60 * 60 * 24 * 365);
		$_COOKIE[self::$usernameSession] = $this->user->getUsername(); 

		$unique = sha1(rand());
		setcookie(self::$passwordSession, $unique, time() + 60 * 60 * 24 * 365);
		$_COOKIE[self::$passwordSession] = $this->user->getPassword();
	}

	public function doLogout() {
		unset($_SESSION[self::$sessionID]);
		// Deleting cookies
		// Source from http://www.programmerinterview.com/index.php/php-questions/how-to-delete-cookies-in-php/
		setcookie(self::$usernameSession, '', time()-300);
		setcookie(self::$passwordSession, '', time()-300);
	}

	public function getLoginStatus() {
		if(!isset($_SESSION[self::$sessionID])) {
			$_SESSION[self::$sessionID] = false;
		}
		return $_SESSION[self::$sessionID];
	}
}