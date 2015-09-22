<?php

namespace model;

// This model simulates an user account
class UserModel {
	private $username = "Admin";
	private $password = "Password";

	public function __construct() {

	}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}
}