<?php

namespace controller;

class LogController {
	
	private $model;

	public function __construct(\model\Logger $model) {
		$this->model = $model;
	}

	public function doControl() {
		// Check view to show
	}
}