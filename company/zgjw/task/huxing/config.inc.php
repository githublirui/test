<?php

//*****************************************************
$dbhost = "localhost";
$dbuser = 'root';
$dbpw = '';
$dbname = 'zgjw1';
$dbcharset = 'gbk';
$dev = false; #���忪��ģʽ
if ($dev) {
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", 0);
    error_reporting(0);
}
set_time_limit(0);
$conn = @mysql_connect($dbhost, $dbuser, $dbpw) or die('E010001'); // 连接数据源
@mysql_select_db($dbname) or die('E010002'); // 选择数据库
@mysql_query("set names utf8");

//*****************************************************
