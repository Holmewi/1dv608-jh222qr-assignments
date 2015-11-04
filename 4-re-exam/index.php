<?php

require_once("model/Logger.php");
require_once("view/LogView.php");
require_once("controller/LogController.php");

$m = new \model\Logger();
$v = new \view\LogView();
$c = new \controller\LogController($m);

$c->doControl();

echo $v->getHTML();