<?php
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/html; charset=utf-8');
//require('360.php');
require('webscan.php');
$dbname = '数据库名';
$host = 'localhost';
$port = '3306';
$user = '用户';
$pwd = '密码';
require('mysql_class.php');
$config = include('config.php');
$mysql = new MySQL($host,$user,$pwd,$dbname,$port);
$site=$config['site'];
$admin=$config['admin'];//后台密码
$pp=$config['pp'];//单个ip价格
$ti=$config['ti'];//最低提现金额
$song=$config['song'];;//注册赠送金额
$song1='';//下线注册赠送金额
$p1=$config['p1'];//下线提成分成