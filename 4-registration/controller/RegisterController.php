<?php

namespace controller;

require_once("model/RegisterCredentials.php");
require_once("model/DAL/UserDAL.php");
require_once("model/RegisterModel.php");
require_once("view/RegisterView.php");

class RegisterController {

	private $view;
	private $model;

	public function __construct(\model\RegisterModel $model, \view\RegisterView $view) {
		$this->model = $model;
		$this->view = $view;
	}

	public function doRegisterControl() {
		if($this->view->userWantsToRegister()) {
			$regcred = $this->view->getRegisterCredentials();
			if($this->model->doRegister($regcred)) {
				$this->view->setRegisterSucceeded();
			}
			return false;
		}
	}
}