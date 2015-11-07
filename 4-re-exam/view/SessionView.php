<?php

namespace view;

class SessionView {
	
	private $model;
	private $nav;

	private static $collectionSession = "LogCollection";
	private static $ipSession = "ipSession";

	private $collectedLogArrayByIP = array();
	private $collectedLogArrayBySessionID = array();
	private $sessionID;

	public function __construct(\model\Logger $model, \view\NavigationView $nav) {
		$this->model = $model;
		$this->nav = $nav;

		$this->collectedLogArrayByIP = $_SESSION[self::$collectionSession];
		$this->ip = $_SESSION[self::$ipSession];
	}

	/**
	*	Creates an array of objects with same IP
	*	@param string $ip
	*	@param array $arrayList (all logs)
	*/
	private function collectTracesBySessionID($sessionID) {
		// Holds a collection of objects with same IP
		$tmpSessionArray = array();

		// Go through all IP of every log again to add the same IPs to the array
		foreach ($this->collectedLogArrayByIP as $log) {
			if($sessionID == $log->m_sessionID) {
				$tmpSessionArray[] = $log;
			}	
		}

		$tmpIPArray = $this->sortObjectsInArrayByField($tmpIPArray, 'm_microTime', 'DESC');

		return $tmpSessionArray;
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

	/**
	*	@return string HTML
	*/
	public function getHTML() {
		// Gets the Session ID from the URL
		$this->sessionID = $this->nav->getLogIP();
		//var_dump($this->sessionID);

		// Collect all logs with the same Session
		$this->collectedLogArrayBySessionID = $this->collectTracesBySessionID($this->sessionID);

		$debugItems = "";

		foreach ($this->collectedLogArrayBySessionID as $log) {
			$debugItems .= $this->showDebugItem($log);
		}
		$ret = '<h3>Session ID: '.$this->sessionID.'</h3><p><a href="'.$this->nav->getLogViewURL($this->ip).'">Back</a></p>
				<div>
					<hr/>
					<h2>Debug</h2>
					<table>
						<tr>
					   		<td>
					   			<h3>Debug Items</h3>
					   			<ol>
					   				'.$debugItems.'
					   			</ol>
						 	</td>
						</tr>
				    </table>
			    </div>';

		return $ret;
	}

	/**
	*	@return string HTML
	*/
	private function showDebugItem(\model\LogItemBLL $item) {
		if ($item->m_debug_backtrace != null) {
			$debug = '<h4>Trace:</h4>
					 <ul>';

			foreach ($item->m_debug_backtrace AS $key => $row) {
				//the two topmost items are part of the logger
				//skip those
				if ($key < 2) { 
					continue;
				}
				$key = $key - 2;
				$debug .= '<li> '.$key.' ' . $item->cleanFilePath($row['file']) . ' Line : ' . $row["line"] .  '</li>';
			}
			$debug .= '</ul>';
		} else {
			$debug = "";
		}
		
		if ($item->m_object != null)
			$object = print_r($item->m_object, true);
		else 
			$object = "";
		list($usec, $sec) = explode(" ", microtime());
		$date = date("Y-m-d H:i:s", $sec);
		$ret =  '<li>
					<Strong>'.$item->m_message.'</strong>'.$item->m_calledFrom.' 
					<div style="font-size:small">'.$date . ' ' .$usec.'</div>
					<pre>'.$object.'</pre>
					
					'.$debug.'
					
				</li>';	
		return $ret;
	}
}