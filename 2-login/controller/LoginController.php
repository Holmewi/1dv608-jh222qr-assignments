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

		$this->v->setMessage('');

		if($this->v->getLoginRequest()) {
			if(empty($this->v->getUsernameInput())) {			
				$this->v->setMessage('Username is missing');
			}
			else if(empty($this->v->getPasswordInput())) {
				$this->v->setMessage('Password is missing');
			}
			else if($this->v->getUsernameInput() == $this->user->getUsername() && $this->v->getPasswordInput() == $this->user->getPassword()) {
				$this->user->login();
				$this->v->setMessage('Welcome');	
			}
			else {
				$this->v->setMessage('Wrong name or password');
			}
		} 
		else if($this->v->getLogoutRequest() && $this->user->getLoginStatus()) {
			$this->user->logout();
			$this->v->setMessage('Bye bye!');
		}

		$this->lv->render($this->user->getLoginStatus(), $this->v, $this->dtv);

		if(!isset($_SESSION['Username']) && $this->user->getLoginStatus()) {
			exit();
		}
	}
}