<?php

include dirname(__FILE__) . '/../lib/Timer.class.php';
Timer::start();

$time = (int) date('H');
$b = $time >= 9 && $time < 22;

$api_class_name = 'test_aaa';
#匿名函数替换
$api_class_name = preg_replace_callback("/_([a-z])/", //
        create_function('$matches', 'return strtoupper($matches[1]);'), $api_class_name);
echo Timer::spend();
die;
var_dump($api_class_name);
?>