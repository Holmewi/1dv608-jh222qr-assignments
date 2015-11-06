<?php

namespace controller;

require_once("view/LogListView.php");
require_once("view/LogView.php");
require_once("view/SessionView.php");

class LogController {
	
	private $model;
	private $view;
	private $nav;

	public function __construct(\model\Logger $model, \view\HTMLView $view, \view\NavigationView $nav) {
		$this->model = $model;
		$this->view = $view;
		$this->nav = $nav;
	}

	public function doControl() {

	}

	public function getView() {
		if($this->nav->adminWantsToTraceIP()) {
			$logView = new \view\LogView($this->model, $this->nav);
			return $logView->getHTML();
		} 
		else if($this->nav->adminWantsToTraceSession()) {
			$sessionView = new \view\SessionView($this->model, $this->nav);
			return $sessionView->getHTML();
		} else {
			$logListView = new \view\LogListView($this->model, $this->nav);
			return $logListView->getHTML();
		}	
	}
}