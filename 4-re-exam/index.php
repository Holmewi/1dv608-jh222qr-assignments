<?php

require_once("model/Logger.php");
require_once("view/HTMLView.php");
require_once("controller/LogController.php");

session_start();

$nav = new \view\NavigationView();
$m = new \model\Logger();
$v = new \view\HTMLView($nav);
$c = new \controller\LogController($m, $v, $nav);

$c->doControl();

$container = $c->getView();

echo $v->getHTML($container);