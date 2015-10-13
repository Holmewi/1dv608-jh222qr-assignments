<?php

namespace model;

class RegisterModel {
	
	private $conn;
	private $userDAL;

	public function __construct(\PDO $conn) {
		$this->conn = $conn;
	}

	public function doRegister(\model\RegisterCredentials $regCred) {
		
		$this->userDAL = new \model\UserDAL($this->conn);

		if($this->userDAL->checkDuplicateUsername($regCred)) {
			return false;
		}
		// TODO: ADD THE USER TO DATABASE
		return true;
	}
}