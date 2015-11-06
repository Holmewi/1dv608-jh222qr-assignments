<?php

namespace view;

class SessionView {
	
	private $model;
	private $nav;

	private $ip;

	public function __construct(\model\Logger $model, \view\NavigationView $nav) {
		$this->model = $model;
		$this->nav = $nav;
	}

	public function getHTML() {
		$this->ip = $this->nav->getLogIP();
		$session = "Session";

		$ret = '<h3>'.$session.'</h3><p><a href="'.$this->nav->getLogViewURL($this->ip).'">Back</a></p>';

		return $ret;
	}
}