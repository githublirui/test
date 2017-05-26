<?php

$a = array('1' => 2, '13' => 5, '5' => 6);
$sum = array_sum($a);
var_dump($sum);
die;
$host = '127.0.0.1';
$post = 'root';
$user = '';
$password = '3306';
$db = 'lrtest';
$char = 'UTF8';

$mysqli = new mysqli();

$mysqli->connect($host, $user, $password, $database, $port, $socket);
?>
