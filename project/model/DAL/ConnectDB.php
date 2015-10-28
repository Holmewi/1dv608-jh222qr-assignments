<?php

namespace model;

class ConnectDB {

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

	public function getConnection() {
		
		if($this->conn == null) {
			try {
				$this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, 
										$this->username, 
										$this->password, 
										array(\PDO::FETCH_OBJ));
				$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			}
			catch(\PDOException $e)
		    {
		    	throw new \DatabaseConnectionException("Database connection failed.");
		    }
		}

		return $this->conn;		
	}
}