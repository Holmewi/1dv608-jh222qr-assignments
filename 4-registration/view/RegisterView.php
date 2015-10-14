<?php

namespace view;

class RegisterView {

	private static $register = "RegisterView::Register";
	private static $name = "RegisterView::UserName";
	private static $password = "RegisterView::Password";
	private static $repeatPassword = "RegisterView::PasswordRepeat";
	private static $messageId = "RegisterView::Message";

	private $registerHasSucceeded = false;

	private $message = "";
	private $model;
	private $v_nv;
	private $v_lv;

	public function __construct(\model\RegisterModel $model, \view\NavigationView $v_nv, \view\LoginView $v_lv) {
		$this->model = $model;
		$this->v_nv = $v_nv;
		$this->v_lv = $v_lv;
	}
	public function userWantsToRegister() {
		return isset($_POST[self::$register]);
	}
	
	public function getRegisterCredentials() {
		return new \model\RegisterCredentials($this->getRequestedUserName(), $this->getRequestedPassword());
	}

	public function setRegisterSucceeded() {
		$this->registerHasSucceeded = true;
	}

	public function getRegistrationStatus() {
		return $this->registerHasSucceeded;
	}

	public function doControlFormInput() {
		
		if(strlen($this->getRequestedUserName()) < 3 || $this->getRequestedUserName() == "") {
			$this->message = "Username has too few characters, at least 3 characters.<br>";

			if(strlen($this->getRequestedPassword()) < 6 || $this->getRequestedPassword() == "" || $this->getRepeatedPassword() == "") {
				$this->message .= "Password has too few characters, at least 6 characters.";
				return false;
			}

			return false;
		}
	 	if(strlen($this->getRequestedPassword()) < 6 || $this->getRequestedPassword() == "" || $this->getRepeatedPassword() == "") {
			$this->message = "Password has too few characters, at least 6 characters.";
			return false;
		}
		else if($this->getRequestedPassword() != $this->getRepeatedPassword()) {
			$this->message = "Passwords do not match.";
			return false;
		}
		else if($this->model->getUsernameExistsStatus($this->getRegisterCredentials())) {
			$this->message = "User exists, pick another username.";
			return false;
		}
		else if(!preg_match("^[0-9A-Za-z_]+$^", $this->getRequestedUserName())) {
			$this->message = "Username contains invalid characters.";
			return false;
		}

		$this->message = "Registered new user.";
		return true;	
	}

	public function response() {
		if($this->registerHasSucceeded) {
			$this->v_lv->redirectRegistration($this->message, $this->getRequestedUserName());
			//return $this->registrationCompleteView();
		}
		return $this->generateRegisterFormHTML();
	}

	private function generateRegisterFormHTML() {
		return "<form method='post' > 
				<fieldset>
					<legend>Register new user</legend>
					<p id='".self::$messageId."'>".$this->message."</p>
					<label for='".self::$name."'>Your username :</label>
					<input type='text' id='".self::$name."' name='".self::$name."' value='".$this->getRequestedUserName()."'/>

					<label for='".self::$password."'>Your password :</label>
					<input type='password' id='".self::$password."' name='".self::$password."'/>

					<label for='".self::$repeatPassword."'>Confirm password :</label>
					<input type='password' id='".self::$repeatPassword."' name='".self::$repeatPassword."'/>
					
					<input type='submit' name='".self::$register."' value='Register New User'/>
					<p>" . $this->v_nv->getLinkToLogin() . "</p>
				</fieldset>
			</form>
		";
	}

	private function registrationCompleteView() {
		return "<form method='post' > 
				<fieldset>
					<legend>Register new user</legend>
					<p id='".self::$messageId."'>".$this->message."</p>
					<p>" . $this->v_nv->getLinkToLogin() . "</p>
				</fieldset>
			</form>
		";
	}

	private function getRequestedUserName() {
		if (isset($_POST[self::$name]))
			return trim($_POST[self::$name]);
	}

	private function getRequestedPassword() {
		if (isset($_POST[self::$password]))
			return trim($_POST[self::$password]);
	}

	private function getRepeatedPassword() {
		if (isset($_POST[self::$repeatPassword]))
			return trim($_POST[self::$repeatPassword]);
	}
}