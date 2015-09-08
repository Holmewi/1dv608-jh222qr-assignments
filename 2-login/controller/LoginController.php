<?php 
namespace controller;

require_once('./view/LoginView.php');
require_once('./view/DateTimeView.php');
require_once('./view/LayoutView.php');

class LoginController {
	private $v;
	private $dtv;
	private $lv;

	public function __construct() {
		$this->v = new \view\LoginView();
		$this->dtv = new \view\DateTimeView();
		$this->lv = new \view\LayoutView();
	}

	public function doRequest() {

		$message = '';

		if($this->v->getLoginRequest() && $_SESSION['LoggedIn'] === false) {

			if(empty($this->v->getUsernameInput())) {
				
				$message = 'Username is missing';
			}
			else if(empty($this->v->getPasswordInput())) {
				$message = 'Password is missing';
			}
			else if($this->v->getUsernameInput() == "Admin" && $this->v->getPasswordInput() == "Password") {
				$_SESSION['LoggedIn'] = true;
				$_SESSION['Username'] = $this->v->getUsernameInput();
				$message = 'Welcome';			
			}
			else {
				$message = 'Wrong name or password';
			}

			//$this->lv->render($_SESSION['LoggedIn'], $this->v, $this->dtv, $message);
		} 
		else if($this->v->getLogoutRequest() && $_SESSION['LoggedIn'] === true) {
			$_SESSION['LoggedIn'] = false;
			unset($_SESSION['Username']);
			$message = 'Bye bye!';	
		}

		$this->lv->render($_SESSION['LoggedIn'], $this->v, $this->dtv, $message);
		
		if(!isset($_SESSION['Username']) && $_SESSION['LoggedIn'] === true) {
			exit();
		}
	}
}