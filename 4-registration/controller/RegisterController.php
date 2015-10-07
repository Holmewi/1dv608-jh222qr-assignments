<?php

namespace controller;

require_once("view/RegisterView.php");

class RegisterController {

	private $view;

	public function __construct(\view\RegisterView $view) {
		$this->view = $view;
	}

	public function doRegister() {
		
		echo "register";
	}
}