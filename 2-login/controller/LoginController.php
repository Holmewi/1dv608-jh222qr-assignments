<?php 
namespace controller;

require_once('./view/LoginView.php');
require_once('./view/DateTimeView.php');
require_once('./view/LayoutView.php');
require_once('./model/User.php');

class LoginController {
	private $user;
	private $v;
	private $dtv;
	private $lv;

	public function __construct() {
		$this->user = new \model\User(); // This model object is a simulation of an user
		$this->v = new \view\LoginView();
		$this->lv = new \view\LayoutView();
		$this->dtv = new \view\DateTimeView();
	}

	public function doRequest() {

		$message = '';

		if($this->v->getLoginRequest() /*&& $_SESSION['LoggedIn'] === false*/) {
			if(empty($this->v->getUsernameInput())) {			
				$message = 'Username is missing';
			}
			else if(empty($this->v->getPasswordInput())) {
				$message = 'Password is missing';
			}
			else if($this->v->getUsernameInput() == $this->user->getUsername() && $this->v->getPasswordInput() == $this->user->getPassword()) {
				//$_SESSION['LoggedIn'] = true;
				$this->user->login();
				$_SESSION['Username'] = $this->v->getUsernameInput();
				$message = 'Welcome';			
			}
			else {
				$message = 'Wrong name or password';
			}
		} 
		else if($this->v->getLogoutRequest() /*&& $_SESSION['LoggedIn'] === true*/) {
			$this->user->logout();
			unset($_SESSION['Username']);
			$message = 'Bye bye!';	
		}

		$this->lv->render($this->user->getLoginStatus(), $this->v, $this->dtv, $message);
		
		if(!isset($_SESSION['Username']) && /*$_SESSION['LoggedIn'] === true*/) {
			exit();
		}
	}
}