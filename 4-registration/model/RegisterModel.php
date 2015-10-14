<?php

namespace model;

class RegisterModel {
	
	private $conn;
	private $userDAL;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;
	}

	public function doRegister(\model\RegisterCredentials $regCred) {
		

		if($this->getUsernameExistsStatus($regCred)) {
			return false;
		} 
		$this->userDAL->addUserToDatabase($regCred);
		return true;
	}

	public function getUsernameExistsStatus(\model\RegisterCredentials $regCred) {
		$this->userDAL = new \model\UserDAL($this->conn);
		return $this->userDAL->checkUsernameExists($regCred);
	}
}