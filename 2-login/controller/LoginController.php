<?php 
namespace controller;

require_once('./model/LoginModel.php');
require_once('./view/LoginView.php');
require_once('./view/DateTimeView.php');
require_once('./view/LayoutView.php');

class LoginController {

	private $lm;
	private $v;
	private $dtv;
	private $lv;

	public function __construct() {		
		$this->v = new \view\LoginView();
		$this->lv = new \view\LayoutView();
		$this->dtv = new \view\DateTimeView();
		$this->lm = new \model\LoginModel($this->v);
	}

	//TODO: Create a model for the messages.
	public function doRequest() {

		$this->v->setMessage('');

		if(!$this->lm->getLoginStatus()) {
			if($this->lm->doCookieLogin()) {
				$this->v->setMessage('Welcome back with cookie');
				$this->lm->doLogin();
			}

			else if($this->v->getLoginRequest()) {
				if(empty($this->v->getUsernameInput())) {
					$this->v->setMessage('Username is missing');
				}
				else if(empty($this->v->getPasswordInput())) {
					$this->v->setMessage('Password is missing');
				}
				else {
					$user = new \model\UserModel($this->v->getUsernameInput(), $this->v->getPasswordInput());

					if($this->lm->authenticate($user)) {
						$this->v->setMessage('Welcome');
						$this->lm->doLogin();
						if($this->v->getKeepRequest()) {
							$this->v->setMessage('Welcome and you will be remembered');
							$this->lm->doKeepLogin();
						}
					} else {
						$this->v->setMessage('Wrong name or password');
					}
				}
			}
		}
		

		if($this->v->getLogoutRequest() && $this->lm->getLoginStatus()) {
			$this->v->setMessage('Bye bye!');
			$this->lm->doLogout();
		}

		$this->lv->render($this->lm->getLoginStatus(), $this->v, $this->dtv);
	}
}