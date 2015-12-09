<?php

$date = '/Date(1449588333993/+0100)/';
$secs = substr($date, 6, 10);
$dt = new DateTime("@$secs"); 

var_dump($dt);