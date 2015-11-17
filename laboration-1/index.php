<?php

require_once('controller/MasterController.php');
require_once('view/LayoutView.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');
libxml_use_internal_errors(TRUE);

$lv = new LayoutView();
$mc = new MasterController();

$mc->start(); 

$lv->render($mc->getHTML());
