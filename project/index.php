<?php

header("Access-Control-Allow-Origin: *");

date_default_timezone_set('Europe/Stockholm');

require_once("controller/MasterController.php");
require_once("view/LayoutView.php");

$ms = new MasterController();
$lv = new LayoutView();

$ms->run();
$lv->render($ms->getStationsHTML(), $ms->getWeatherHTML());