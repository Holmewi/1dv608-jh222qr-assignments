<?php

namespace view;

class LogListView {
	
	private $model;
	private $nav;
	private $logListArray = array();
	private $numberOfSessions;

	public function __construct(\model\Logger $model, \view\NavigationView $nav) {
		$this->model = $model;
		$this->nav = $nav;
	}

	/**
	*	Sets the array for the log list view
	*/
	private function setLogCollectionByIP() {
		// Array of objects
		$arrayList = $this->model->getLogArray();

		// Going through all IP of every log
		foreach ($arrayList as $log) {	

			if($this->isIPUnique($log->m_userIP) == false) {
				$sessions = $this->countSessions($log->m_userIP, $arrayList);

				array_push($this->logListArray, ["m_userIP" => $log->m_userIP, "m_microTime" => $log->m_microTime, "sessions" => $sessions]);
			}	
		}
	}

	/**
	*	Check if the IP do already exists in the logListArray
	*	@param string $ip
	*	@return boolean TRUE | FALSE (true if exists)
	*/
	private function isIPUnique($ip) {
		
		foreach ($this->logListArray as $item) {
			//var_dump($item['m_userIP']);
			if($ip == $item['m_userIP']) {

				return true;
			}	
		}
		return false;
	}

	

	private function countSessions($ip, $arrayList) {
		$count = 0;

		foreach ($arrayList as $log) {
			if($ip == $log->m_userIP) {
				if(!in_array($log->m_sessionID, $tmpSessionArray)) {
					$count++;
				}
			}
		}
		return $count;
	}

	public function getHTML() {
		//$this->sortedLogArray = $this->sortArray($field);
		$this->setLogCollectionByIP();

		//var_dump(count($this->collectedLogArray[1]));

		$ret = '<ul>';

		foreach ($this->logListArray as $item) {
			//var_dump($log);

			list($usec, $sec) = explode(" ", $item['m_microTime']);
			$date = date("Y-m-d H:i:s", $sec);
			$userIP = $item['m_userIP'];
			$userIPURL = $this->nav->getLogViewURL($userIP);

			$ret .= '<li>
						<span>IP: <a href="'.$userIPURL.'">'.$userIP.'</a></span>
						<span>Time: '.$date.' '.$usec.'</span>
						<span>Sessions: '.$item['sessions'].'</span>
					</li>';
					
		}

		$ret .= '</ul>';

		return $ret;
	}
}