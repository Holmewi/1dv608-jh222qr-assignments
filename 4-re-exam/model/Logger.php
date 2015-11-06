<?php

namespace model;

require_once("model/LogItemBLL.php");
require_once("model/LogRepository.php");

class Logger {
	
	private $repository;

	public function __construct() {
		$this->repository = new \model\LogRepository();
	}

	public function loggHeader($logMessageString) {
		$this->repository->saveToFile(new \model\LogItemBLL("<h2>$logMessageString</h2>", null, false));
	}

	public function loggThis($logMessageString, $logThisObject = null, $includeTrace = false) {
		$this->repository->saveToFile(new \model\LogItemBLL($logMessageString, $includeTrace, $logThisObject));
	}

	public function getLogArray() {
		$this->repository->loadFromFile();
		return $this->repository->getLogArray();
	}
}