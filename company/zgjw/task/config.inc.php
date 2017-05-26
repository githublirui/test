<?php

//include_once ('360_safe3.php');
//*****************************************************
#数据库设置
$dbhost = "192.168.2.88";
$dbuser = 'root';
$dbpw = '';
$dbname = 'mytest';
$pconnect = 0;
$dbcharset = 'utf8';
$tablepre = '';

#cookie域名设置
// $cookiepre = 'uix_';              // cookie 前缀 
//$cookiedomain = '.zgjwzh.local';    // cookie 作用域
$main_domain = 'http://106.3.78.224:5000/';         //主域名
$session_expire_time = 7 * 60 * 60 * 24;         //session过期时间
ini_set('session.cookie_domain', $cookiedomain); //设置session作用域
$cookiepath = '/';   // cookie 作用路径
#其他变量设置
$dev = true; #定义开发模式 
//后台登录允许ip
$allow_ips = array(
//    '127.0.0.1',
//    '106.3.78.220',
//    '192.168.2.1 - 192.168.2.255',
//    '192.168.1.1 - 192.168.1.255',
//    '106.3.78.224 - 106.3.78.231',
);

#基础初始化
if ($dev) {
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", 0);
    error_reporting(0);
}


