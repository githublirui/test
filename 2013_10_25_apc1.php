<?php

include '../lib/utils/Apc.class.php';
ini_set("display_errors", 1);
error_reporting(E_ALL);

$apc = new Apc();
var_dump($apc->get_cache('aa'));
die;