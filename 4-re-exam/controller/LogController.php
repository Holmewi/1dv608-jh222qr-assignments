<?php

namespace controller;

require_once("view/LogView.php");

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
		// Check view to show
	}

	public function getView() {
		$logView = new \view\LogView($this->model);
		return $logView->getHTML();
	}
}