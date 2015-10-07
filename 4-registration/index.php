<?php
 /**
  * Solution for assignment 2
  * @author Daniel Toll
  */

require_once("Settings.php");
require_once("controller/MasterController.php");

if (Settings::DISPLAY_ERRORS) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

//session must be started before LoginModel is created
session_start();

//Dependency injection
$mc = new \controller\MasterController();

//Controller must be run first since state is changed
$mc->handleInput();

//Generate output
$mc->generateOutput();

