<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */
namespace model;

require_once("UserCredentials.php");
require_once("TempCredentials.php");
require_once("TempCredentialsDAL.php");
require_once("LoggedInUser.php");
require_once("UserClient.php");



class LoginModel {

	//TODO: Remove static to enable several sessions
	private static $sessionUserLocation = "LoginModel::loggedInUser";

	/**
	 * @var null | TempCredentials
	 */
	private $tempCredentials = null;

	private $tempDAL;

	private $conn;
	private $userDAL;
	private $existingUser;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;

		self::$sessionUserLocation .= \Settings::APP_SESSION_NAME;

		if (!isset($_SESSION)) {
			//Alternate check with newer PHP
			//if (\session_status() == PHP_SESSION_NONE) {
			assert("No session started");
		}
		$this->tempDAL = new TempCredentialsDAL();
		
	}

	/**
	 * Checks if user is logged in
	 * @param  UserClient $userClient The current calls Client
	 * @return boolean                true if user is logged in.
	 */
	public function isLoggedIn(UserClient $userClient) {
		if (isset($_SESSION[self::$sessionUserLocation])) {
			$user = $_SESSION[self::$sessionUserLocation];

			if ($user->sameAsLastTime($userClient) == false) {
				return false;
			}
			return true;
		} 

		return false;
	}

	/**
	 * Attempts to authenticate
	 * @param  UserCredentials $uc
	 * @return boolean
	 */
	public function doLogin(UserCredentials $uc) {

		$this->tempCredentials = $this->tempDAL->load($uc->getName());

		$this->userDAL = new \model\UserDAL($this->conn);
		$this->existingUser = $this->userDAL->getUserFromDatabase($uc);

		if($this->existingUser == null) {
			$loginByUsernameAndPassword = false;
		} else {
			$loginByUsernameAndPassword = $this->existingUser->{"username"} === $uc->getName() && $this->existingUser->{"password"} === $uc->getPassword();
		}
		
		$loginByTemporaryCredentials = $this->tempCredentials != null && $this->tempCredentials->isValid($uc->getTempPassword());

		if ( $loginByUsernameAndPassword || $loginByTemporaryCredentials) {
			$user = new LoggedInUser($uc); 

			$_SESSION[self::$sessionUserLocation] = $user;

			return true;
		}
		return false;
	}

	public function doLogout() {
		unset($_SESSION[self::$sessionUserLocation]);
	}

	/**
	 * @return TempCredentials
	 */
	public function getTempCredentials() {
		return $this->tempCredentials;
	}

	/**
	 * renew the temporary credentials
	 * 
	 * @param  UserClient $userClient 
	 */
	public function renew(UserClient $userClient) {
		if ($this->isLoggedIn($userClient)) {
			$user = $_SESSION[self::$sessionUserLocation];
			$this->tempCredentials = new TempCredentials($user);
			$this->tempDAL->save($user, $this->tempCredentials);
		}
	}
	
}