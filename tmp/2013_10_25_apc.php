<?php

include '../lib/utils/Apc.class.php';
ini_set("display_errors", 1);
error_reporting(E_ALL);

//设置
$apc = new Apc();
$apc->set_cache('aa', array('aaa' => 'bbb'), 3); #1.第一个参数是key,第二个参数value，第三个参数生存时间/秒 
var_dump($apc->get_cache('aa')); #1.第一个参数是key
die;