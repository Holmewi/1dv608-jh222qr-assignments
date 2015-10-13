<?php

namespace model;

class Connect {

	private $host;
	private $database;
	private $username;
	private $password;
	private $conn;

	public function __construct($host, $database, $username, $password) {
		$this->host = $host;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
	}

	public function doConnect() {
		
		if($this->conn == null) {
			try {
				$this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password, array(\PDO::FETCH_OBJ));
				$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			}
			catch(\PDOException $e)
		    {
		    	// TODO: Create a better error handling for the users
		    	throw new \PDOException("Database connection failed.");
		    }
		}

		return $this->conn;		
	}
	

}
