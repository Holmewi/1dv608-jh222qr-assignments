<?php

namespace view;

class LogView {
	
	private $model;
	private $nav;

	private static $collectionSession = "LogCollection";
	private static $ipSession = "ipSession";
	
	private $collectedLogArrayByIP = array();
	private $sessionListArray = array();
	
	private $ip;

	public function __construct(\model\Logger $model, \view\NavigationView $nav) {
		$this->model = $model;
		$this->nav = $nav;
	}

	/**
	*	Creates an array of objects with same IP
	*	@param string $ip
	*	@param array $arrayList (all logs)
	*	@return array
	*/
	private function collectLogsByIP($ip, $arrayList) {
		// Holds a collection of objects with same IP
		$tmpIPArray = array();

		// Go through all IP of every log to add the same IPs to the array
		foreach ($arrayList as $log) {
			if($ip == $log->m_userIP) {
				$tmpIPArray[] = $log;
			}	
		}

		return $tmpIPArray;
	}

	/**
	*	Creates an array for the session list view
	*	@var array $this->sessionListArray
	*/
	private function setLogListArrayBySession() {
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

	/**
	*	Sort the $this->sessionListArray array by time
	*/
	private function sortByTime() {
		$sortByTime = array();
		foreach ($this->sessionListArray as $key => $row) {
			$sortByTime[$key] = $row['m_microTime'];
		}
		array_multisort($sortByTime, SORT_ASC, $this->sessionListArray);
	}

	/**
	*	@return string HTML
	*/
	public function getHTML() {
		$this->ip = $this->nav->getLogIP();
		$arrayList = $this->model->getLogArray();

		// Collect all logs with the same IP
		$this->collectedLogArrayByIP = $this->collectLogsByIP($this->ip, $arrayList);

		// Stores Array in a $_SESSION to use in SessionView
		$_SESSION[self::$collectionSession] = $this->collectedLogArrayByIP;
		// Stores IP in a $_SESSION to use in SessionView
		$_SESSION[self::$ipSession] = $this->ip;

		// Creates an array of unique Sessions to use as list view
		$this->setLogListArrayBySession();

		// Sort the $this->sessionListArray after time ASC
		$this->sortByTime();

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
}















