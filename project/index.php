<?php

require_once("Settings.php");
require_once("exceptions/ImageException.php");
require_once("exceptions/ProductException.php");
require_once("exceptions/DatabaseException.php");

require_once("model/DAL/ConnectDB.php");
//require_once("controller/AdminController.php");
require_once("controller/MasterController.php");
require_once("view/HTMLView.php");

if (Settings::DISPLAY_ERRORS) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

session_start();

$mc = new \controller\MasterController();
$mc->doMasterControl();

$container = $mc->generateContainer();
$aside = $mc->generateAside();

$html = new \view\HTMLView();
$html->getHTML($container->getResponse(), $aside->getResponse());

