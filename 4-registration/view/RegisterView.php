<?php

namespace view;

class RegisterView {

	private static $register = "RegisterView::Register";
	private static $name = "RegisterView::UserName";
	private static $password = "RegisterView::Password";
	private static $repeatPassword = "RegisterView::PasswordRepeat";
	private static $messageId = "RegisterView::Message";

	private $registerHasFailed = false;
	private $registerHasSucceeded = false;

	private $v_nv;

	public function __construct(\view\NavigationView $v_nv) {
		$this->n_nv = $v_nv;
	}
	public function userWantsToRegister() {
		return isset($_POST[self::$register]);
	}
	
	public function getRegisterCredentials() {
		return new \model\RegisterCredentials($this->getRequestedUserName(), $this->getRequestedPassword());
	}

	public function setRegisterFailed() {
		$this->registerHasFailed = true;
	}

	public function setRegisterSucceeded() {
		$this->registerHasSucceeded = true;
	}
	

	public function doRegistrationForm() {
		$message = "";

		//Correct messages
		if($this->userWantsToRegister()) {
			if(strlen($this->getRequestedUserName()) < 3 || $this->getRequestedUserName() == "") {
				$message = "Username has too few characters, at least 3 characters.";
			}
			else if(strlen($this->getRequestedPassword()) < 6 || $this->getRequestedPassword() == "" || $this->getRepeatedPassword() == "") {
				$message = "Password has too few characters, at least 6 characters.";
			}
			else if($this->getRequestedPassword() != $this->getRepeatedPassword()) {
				$message = "Passwords do not match.";
			}
		}
		
		//generate HTML
		return $this->generateRegisterFormHTML($message);
	}

	private function generateRegisterFormHTML($message) {
		return "<form method='post' > 
				<fieldset>
					<legend>Register new user</legend>
					<p id='".self::$messageId."'>$message</p>
					<label for='".self::$name."'>Your username :</label>
					<input type='text' id='".self::$name."' name='".self::$name."' value='".$this->getRequestedUserName()."'/>

					<label for='".self::$password."'>Your password :</label>
					<input type='password' id='".self::$password."' name='".self::$password."'/>

					<label for='".self::$repeatPassword."'>Confirm password :</label>
					<input type='password' id='".self::$repeatPassword."' name='".self::$repeatPassword."'/>
					
					<input type='submit' name='".self::$register."' value='Register New User'/>
					<p>" . $this->n_nv->getLinkToLogin() . "</p>
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