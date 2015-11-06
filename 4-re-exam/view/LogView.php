<?php

namespace view;

class LogView {
	
	private $model;
	private $nav;
	
	private $sessionListArray = array();
	private $collectedLogArray = array();
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

		//$tmpIPArray = $this->sortObjectsInArrayByField($tmpIPArray, 'm_microTime', 'DESC');

		return $tmpIPArray;
	}

	private function setSessionCollection() {
		foreach ($this->collectedLogArray as $log) {	

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
	*	@author Brainstorms of a Webdev
	*	SOURCE: http://www.bswebdev.com/2012/12/php-snippet-of-the-day-sort-object-by-field-name/
	*
	* 	@param array $array Array of objects to sort.
 	* 	@param string $field Name of field.
 	* 	@param string $order (ASC|DESC)
 	*	@return array
	*/
	private function sortObjectsInArrayByField(&$array, $field, $order = 'DESC') {
		$comparer = ($order === 'ASC')
	        ? "return -strcmp(\$a->{$field},\$b->{$field});"
	        : "return strcmp(\$a->{$field},\$b->{$field});";
	    usort($array, create_function('$a,$b', $comparer));
	    return $array;
	}

	public function getHTML() {
		$this->ip = $this->nav->getLogIP();
		$arrayList = $this->model->getLogArray();

		$this->collectedLogArray = $this->collectLogsByIP($this->ip, $arrayList);
		$this->setSessionCollection();

		$ret = '<h3>'.$this->ip.'</h3><p><a href="'.$this->nav->getLogListViewURL().'">Back</a></p>';

		foreach ($this->sessionListArray as $item) {

			list($usec, $sec) = explode(" ", $item['m_microTime']);
			$date = date("Y-m-d H:i:s", $sec);
			$sessionID = $item['m_sessionID'];
			$userIPURL = $this->nav->getLogSessionURL($this->ip);

			$ret .= '<li>
						<span>Session ID: <a href="'.$userIPURL.'">'.$sessionID.'</a></span>
						<span>Time: '.$date.' '.$usec.'</span>
					</li>';
					
		}

		return $ret;
	}
}















