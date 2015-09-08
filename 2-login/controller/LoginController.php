<?php 

require_once('./view/LoginView.php');

class LoginController {
	private $v;

	public function __construct(LoginView $v) {
		$this->v = $v;
	}

	public function doLogin() {

	}
}