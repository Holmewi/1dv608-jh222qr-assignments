<?php

namespace view;

require_once('view/LayoutView.php');

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($message) {
		//$message = '';

		/*
		if(isset($_POST[self::$login])) {
			
			if(empty($_POST[self::$name])) {
				$message = 'Username is missing';
			}
			else if(empty($_POST[self::$password])) {
				$message = 'Password is missing';
			}
			else if(isset($_POST[self::$name]) == "Admin" && isset($_POST[self::$password]) == "Password") {
				$_SESSION["LoggedIn"] = true;
				$message = 'Welcome to the page!';			
			}
			else {
				$message = 'Wrong name or password';
			}
		}
		*/

		if($_SESSION["LoggedIn"] == false) {
			$response = $this->generateLoginFormHTML($message);
		}
		else {
			$response = $this->generateLogoutButtonHTML($message);
		}
		
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
		//Ternary Operator - if condition (field is set) is true, set to field to value - else empty 
		return (isset($_POST[self::$name]) ? $_POST[self::$name] : '');
	}

	public function getLoginRequest() {
		return (isset($_POST[self::$login]));
	}

	public function getLogoutRequest() {
		return (isset($_POST[self::$logout]));
	}

	public function getUsernameInput() {
		return $_POST[self::$name];
	}

	public function getPasswordInput() {
		return $_POST[self::$password];
	}
}