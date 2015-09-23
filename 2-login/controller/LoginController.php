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

	public function doRequest() {

		$this->v->setMessage('');

		if($this->v->getLoginRequest() && !$this->lm->getLoginStatus()) {
			if($this->lm->authenticate()) {
				$this->lm->doLogin();
			}
		}

		if($this->v->getLogoutRequest() && $this->lm->getLoginStatus()) {
			$this->lm->doLogout();
		}

		if(!$this->lm->getLoginStatus()) {
			$this->lm->doCookieLogin();
		} 

		

		$this->lv->render($this->lm->getLoginStatus(), $this->v, $this->dtv);
	}
}