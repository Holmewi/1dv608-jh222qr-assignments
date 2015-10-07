<?php

namespace view;

class RegisterView {

	private static $register = "RegisterView::Register";
	private static $name = "RegisterView::UserName";
	private static $password = "RegisterView::Password";
	private static $repeatPassword = "RegisterView::PasswordRepeat";
	private static $messageId = "RegisterView::Message";

	private $v_nv;

	public function __construct(\view\NavigationView $v_nv) {
		$this->n_nv = $v_nv;
	}
	public function userWantsToRegister() {
		return isset($_POST[self::$register]);
	}

	public function doRegistrationForm() {
		$message = "";
		//Correct messages
		/*
		if ($this->userWantsToLogout() && $this->userDidLogout) {
			$message = "Bye bye!";
			$this->redirect($message);
		} else if ($this->userWantsToLogin() && $this->getTempPassword() != "") {
			$message =  "Wrong information in cookies";
		} else if ($this->userWantsToLogin() && $this->getRequestUserName() == "") {
			$message =  "Username is missing";
		} else if ($this->userWantsToLogin() && $this->getPassword() == "") {
			$message =  "Password is missing";
		} else if ($this->loginHasFailed === true) {
			$message =  "Wrong name or password";
		} else {
			$message = $this->getSessionMessage();
		}

		//cookies
		$this->unsetCookies();
		*/

		//generate HTML
		
		return $this->generateRegisterFormHTML($message);
	}

	private function generateRegisterFormHTML($message) {
		return "<form method='post' > 
				<fieldset>
					<legend>Register new user</legend>
					<p id='".self::$messageId."'>$message</p>
					<label for='".self::$name."'>Your username :</label>
					<input type='text' id='".self::$name."' name='".self::$name."'/>

					<label for='".self::$password."'>Your password :</label>
					<input type='password' id='".self::$password."' name='".self::$password."'/>

					<label for='".self::$repeatPassword."'>Confirm password :</label>
					<input type='password' id='".self::$repeatPassword."' name='".self::$password."'/>
					
					<input type='submit' name='".self::$register."' value='Register New User'/>
					<p>" . $this->n_nv->getLinkToLogin() . "</p>
				</fieldset>
			</form>
		";
	}
}