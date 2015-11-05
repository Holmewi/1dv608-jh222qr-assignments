<?php

namespace view;

class NavigationView {
	
	private static $IPURLPrefix = "m_userIP";
	private static $TimeURLPrefix = "m_microTime";
	private static $SessionURLPrefix = "m_sessionID";

	public function isIPListed() {
		if(isset($_GET[self::$IPURLPrefix])) {
			return true;
		}
		return false;
	}

	public function isTimeListed() {
		if(isset($_GET[self::$TimeURLPrefix])) {
			return true;
		}
		return false;
	}

	public function isSessionListed() {
		if(isset($_GET[self::$SessionURLPrefix])) {
			return true;
		}
		return false;
	}

	public function getListIPURL() {
		return "?".self::$IPURLPrefix;
	}

	public function getListTimeURL() {
		return "?".self::$TimeURLPrefix;
	}

	public function getListSessionURL() {
		return "?".self::$SessionURLPrefix;
	}
}