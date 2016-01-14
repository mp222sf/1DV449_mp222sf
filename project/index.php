<?php

header("Access-Control-Allow-Origin: *");

date_default_timezone_set('Europe/Stockholm');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

require_once("controller/MasterController.php");
require_once("view/LayoutView.php");

$lv = new LayoutView();
$ms = new MasterController($lv);

$ms->run();
$lv->render($ms->getStationsHTML(), $ms->getWeatherHTML());