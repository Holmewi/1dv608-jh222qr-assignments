<?php

namespace view;

class NavigationView {

	private static $registerURL = "register";

	public function getLinkToLogin() {
		return "<a href='?'>Back to login</a>";
	}

	public function getLinkToRegistration() {
		return "<a href='?" . self::$registerURL . "'>Register a new user</a>";
	}

	public function inRegistration() {
		return isset($_GET[self::$registerURL]) == true;
	}
}