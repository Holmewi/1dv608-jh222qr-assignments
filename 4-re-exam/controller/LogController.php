<?php

namespace controller;

require_once("view/LogListView.php");
require_once("view/LogView.php");
require_once("view/SessionView.php");

class LogController {
	
	private $model;
	private $view;
	private $nav;

	private $logView;
	private $sessionView;
	private $logListView;

	public function __construct(\model\Logger $model, \view\HTMLView $view, \view\NavigationView $nav) {
		$this->model = $model;
		$this->view = $view;
		$this->nav = $nav;

		$this->logListView = new \view\LogListView($this->model, $this->nav);
	}

	public function doControl() {
		if($this->logListView->adminWantsToCreateNewLogItem()) {
			$this->createNewLog();
		}
		if($this->nav->adminWantsToTraceIP()) {
			$this->logView = new \view\LogView($this->model, $this->nav);
		} 
		if($this->nav->adminWantsToTraceSession()) {
			$this->sessionView = new \view\SessionView($this->model, $this->nav);	
		}
	}

	private function createNewLog() {
		$this->model->loggHeader("A header");
		$this->model->loggThis("write a message");
		$this->model->loggThis("include call trace", null, true);
		$this->model->loggThis("include an object", new \Exception("foo exception"), false);
	}

	public function getView() {
		if($this->nav->inLogView()) {	
			return $this->logView->getHTML();
		} 
		else if($this->nav->inSessionView()) {	
			return $this->sessionView->getHTML();
		} else {
			return $this->logListView->getHTML();
		}	
	}
}