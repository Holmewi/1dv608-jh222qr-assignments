<?php

//INCLUDE THE FILES NEEDED...
require_once('controller/LoginController.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//START SESSION
session_start();

//CREATE OBJECTS OF THE CONTROLLER
$lC = new \controller\LoginController();
$lC->doRequest();

