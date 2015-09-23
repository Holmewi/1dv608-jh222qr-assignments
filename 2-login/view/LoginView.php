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
	private $message = '';

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn) {

		if($isLoggedIn) {
			$response = $this->generateLogoutButtonHTML($this->message);
		}
		else {
			$response = $this->generateLoginFormHTML($this->message);
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
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getUsernameInput() . '" />

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
		return isset($_POST[self::$login]);
	}

	public function getLogoutRequest() {
		return isset($_POST[self::$logout]);
	}

	public function getKeepRequest() {
		return isset($_POST[self::$keep]);
	}

	public function getUsernameInput() {
		if (isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
	}

	public function getPasswordInput() {
		if (isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
	}

	public function getCookieNameString() {
		return self::$cookieName;
	}

	public function getCookiePasswordString() {
		return self::$cookiePassword;
	}

	public function setMessage($message) {
    	$this->message = $message;
  	}
}