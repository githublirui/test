<?php
error_reporting(E_ALL);
#删除重复的小区
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'test';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$sql = "select * from tbl_content";
$r = mysql_query($sql);
while ($row = mysql_fetch_assoc($r)) {
    $sql1 = 'select * from tbl_content where name="' . $row['name'] .'" and area='.$row['area'] .' and id !='.$row['id'];
    $r1 = mysql_query($sql1);
	#如果存在则删除
	while ($row1 = mysql_fetch_assoc($r1)) {
			$sql2 = "delete from tbl_content where id=" . $row1['id'];
		mysql_query($sql2);
	    echo '.';
		flush();
   }
}