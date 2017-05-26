<?php

//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';

$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);
