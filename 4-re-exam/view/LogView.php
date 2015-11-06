<?php

namespace view;

class LogView {
	
	private $model;
	private $nav;

	private static $collectionSession = "LogCollection";
	private static $ipSession = "ipSession";
	
	private $sessionListArray = array();
	private $collectedLogArrayByIP = array();
	private $ip;

	public function __construct(\model\Logger $model, \view\NavigationView $nav) {
		$this->model = $model;
		$this->nav = $nav;
	}

	/**
	*	Creates an array of objects with same IP
	*	@param string $ip
	*	@param array $arrayList (all logs)
	*/
	private function collectLogsByIP($ip, $arrayList) {
		// Holds a collection of objects with same IP
		$tmpIPArray = array();

		// Go through all IP of every log again to add the same IPs to the array
		foreach ($arrayList as $log) {
			if($ip == $log->m_userIP) {
				$tmpIPArray[] = $log;
			}	
		}

		return $tmpIPArray;
	}

	private function setSessionCollection() {
		foreach ($this->collectedLogArrayByIP as $log) {	

			if($this->isSessionUnique($log->m_sessionID) == false) {
				array_push($this->sessionListArray, ["m_sessionID" => $log->m_sessionID, "m_microTime" => $log->m_microTime]);
			}	
		}
	}

	/**
	*	Check if the Session do already exists in the sessionListArray
	*	@param string $ip
	*	@return boolean TRUE | FALSE (true if exists)
	*/
	private function isSessionUnique($session) {
		
		foreach ($this->sessionListArray as $item) {
			//var_dump($item['m_userIP']);
			if($session == $item['m_sessionID']) {

				return true;
			}	
		}
		return false;
	}

	public function getHTML() {
		$this->ip = $this->nav->getLogIP();
		$arrayList = $this->model->getLogArray();

		$this->collectedLogArrayByIP = $this->collectLogsByIP($this->ip, $arrayList);
		$_SESSION[self::$collectionSession] = $this->collectedLogArrayByIP;
		$_SESSION[self::$ipSession] = $this->ip;

		$this->setSessionCollection();

		$sortByTime = array();

		foreach ($this->sessionListArray as $key => $row) {
			$sortByTime[$key] = $row['m_microTime'];
		}

		array_multisort($sortByTime, SORT_ASC, $this->sessionListArray);

		$ret = '<h3>IP Address: '.$this->ip.'</h3><p><a href="'.$this->nav->getLogListViewURL().'">Back</a></p>';

		foreach ($this->sessionListArray as $item) {

			list($usec, $sec) = explode(" ", $item['m_microTime']);
			$date = date("Y-m-d H:i:s", $sec);
			$sessionID = $item['m_sessionID'];
			$userIPURL = $this->nav->getLogSessionURL($sessionID);

			$ret .= '<li>
						<span>Session ID: <a href="'.$userIPURL.'">'.$sessionID.'</a></span>
						<span>Time: '.$date.' '.$usec.'</span>
					</li>';
					
		}

		return $ret;
	}

	public function getCollectedLogArray() {
		return $this->collectedLogArrayByIP;
	}
}















