<?php

#把上海关联的城市市辖区改成上海的，原是北京的
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'test';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$sql = "select * from tbl_content where province=310000 and city=110100;";
$r = mysql_query($sql);
while ($row = mysql_fetch_assoc($r)) {
    $sql = "update tbl_content set city=310100 where id=" . $row['id'];
    mysql_query($sql);
    echo '.';
    flush();
}