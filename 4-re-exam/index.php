<?php

require_once("model/Logger.php");
require_once("view/LogView.php");
require_once("controller/LogController.php");

$m = new \model\Logger();
$v = new \view\LogView();
$c = new \controller\LogController($m);

$m->loggHeader("A header");
$m->loggThis("write a message");
$m->loggThis("include call trace", null, true);
$m->loggThis("include an object", new \Exception("foo exception"), false);

$c->doControl();

echo $v->getHTML();