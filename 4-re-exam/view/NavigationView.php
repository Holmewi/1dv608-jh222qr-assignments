<?php

namespace view;

class NavigationView {
	
	private static $logURLPrefix = "ip";
	private static $sessionURLPrefix = "id";

	public function adminWantsToTraceIP() {
		if(isset($_GET[self::$logURLPrefix])) {
			return self::$logURLPrefix;
		}
		return false;
	}

	public function adminWantsToTraceSession() {
		if(isset($_GET[self::$sessionURLPrefix])) {
			return self::$sessionURLPrefix;
		}
		return false;
	}

	public function getLogListViewURL() {
		return "?";
	}

	public function getLogViewURL($ip) {
		return "?".self::$logURLPrefix."=$ip";
	}

	public function getLogSessionURL($ip) {
		return "?".self::$sessionURLPrefix."=$ip";
	}

	public function getLogIP() {
		if($this->adminWantsToTraceIP()) {
			return $_GET[self::$logURLPrefix];
		}
		else if($this->adminWantsToTraceSession()) {
			return $_GET[self::$sessionURLPrefix];
		}
	}
}