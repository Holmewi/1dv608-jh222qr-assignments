<?php

require_once("model/Logger.php");
require_once("view/HTMLView.php");
require_once("controller/LogController.php");

$nav = new \view\NavigationView();
$m = new \model\Logger();
$v = new \view\HTMLView($nav);
$c = new \controller\LogController($m, $v, $nav);

$m->loggHeader("A header");
$m->loggThis("write a message");
$m->loggThis("include call trace", null, true);
$m->loggThis("include an object", new \Exception("foo exception"), false);

$c->doControl();

$container = $c->getView();

echo $v->getHTML($container);