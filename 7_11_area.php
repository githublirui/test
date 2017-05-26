<?php

@session_start();
//数据库配置
$db_server = '192.168.2.251';
$db_user = 'lirui';
$db_psw = 'lirui123456';
$db_name = 'zgjw';

$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$all_provinces_r = mysql_query('select * from area');
$result = array();
while ($row = mysql_fetch_assoc($all_provinces_r)) {
    $sql = "select * from city where code=" . $row['citycode'];
    $tmp = mysql_fetch_assoc(mysql_query($sql));
    if (!$tmp) {
        $result[] = $row['citycode'];
    }
}
var_dump($result);die;