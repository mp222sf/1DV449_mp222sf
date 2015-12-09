<?php

require_once("controller/MasterController.php");
require_once("view/LayoutView.php");

$ms = new MasterController();
$lv = new LayoutView();

$ms->run();
$lv->render($ms->getHTML());