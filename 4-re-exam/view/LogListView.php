<?php

namespace view;

class LogListView {
	
	private $model;
	private $nav;
	private $ipListArray = array();
	private $numberOfSessions;

	private static $create = "LogListView::Create";

	public function __construct(\model\Logger $model, \view\NavigationView $nav) {
		$this->model = $model;
		$this->nav = $nav;
	}

	/**
	*	@return $_POST to check if admin wants to create a new log item
	*/
	public function adminWantsToCreateNewLogItem() {
		if(isset($_POST[self::$create])) {
			return isset($_POST[self::$create]);
		}
	}

	/**
	*	Creates an array for the ip list view
	*	@var array $this->ipListArray
	*/
	private function setLogListArrayByIP() {
		$arrayList = $this->model->getLogArray();

		foreach ($arrayList as $log) {	
			if($this->isIPUnique($log->m_userIP) == false) {
				$sessions = $this->countSessions($log->m_userIP, $arrayList);

				array_push($this->ipListArray, ["m_userIP" => $log->m_userIP, "m_microTime" => $log->m_microTime, "sessions" => $sessions]);
			}	
		}
	}

	/**
	*	Check if the IP do already exists in the ipListArray
	*	@param string $ip
	*	@return boolean TRUE | FALSE (true if exists)
	*/
	private function isIPUnique($ip) {	
		foreach ($this->ipListArray as $item) {
			//var_dump($item['m_userIP']);
			if($ip == $item['m_userIP']) {
				return true;
			}	
		}
		return false;
	}

	/**
	*	Count the number of sessions for each unique IP
	*	@param string $ip
	*	@param array $arrayList
	*	@return int $count
	*/
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

	/**
	*	Sort the $this->ipListArray array by time
	*/
	private function sortByTime() {
		$sortByTime = array();
		foreach ($this->ipListArray as $key => $row) {
			$sortByTime[$key] = $row['m_microTime'];
		}
		array_multisort($sortByTime, SORT_ASC, $this->ipListArray);
	}

	/**
	*	@return string HTML
	*/
	public function getHTML() {
		// Creates an array of unique IPs to use as list view
		$this->setLogListArrayByIP();

		// Sort the ipListArray after time ASC
		$this->sortByTime();


		$ret = '<h3>Log List View</h3><p>Logs are listed by IP</p>				
					<form method="post" class="delete-submit">
						<input type="submit" name="'.self::$create.'" value="Create new log"/>
					</form><ul>';

		foreach ($this->ipListArray as $item) {
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