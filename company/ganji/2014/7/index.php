<?php

require_once dirname(__FILE__) . '/common/post_operate/NormalOperate.class.php';

$o = new NormalOperate(array('cityId' => 11, 'puid' => 123));

$a = $o->operate();

var_dump($a);
die;
?>
