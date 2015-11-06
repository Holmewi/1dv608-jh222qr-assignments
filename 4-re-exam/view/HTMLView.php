<?php

namespace view;

require_once("view/NavigationView.php");

class HTMLView {

	private $nav;

	public function __construct(\view\NavigationView $nav) {
		$this->nav = $nav;
	}

	public function getHTML($container) {
		// CREATE HTML VIEW
		return '<!DOCTYPE html>
	      	<html>
	        	<head>
	          		<meta charset="utf-8">
	          		<link rel="stylesheet" type="text/css" href="css/style.css">
	          		<title>Assignment 4. Re-exam. Persistent log-system</title>
	        	</head>
	        	<body>
	        		<div id="wrapper">
		          		<h1>Assignment 4. Re-exam</h1>
		          		<h3>Persistent log-system</h3>	

		          		<div id="container">
		          			' . $container . '
		          		</div>

	          		</div>
	         	</body>
	      	</html>
    	';
	}
}