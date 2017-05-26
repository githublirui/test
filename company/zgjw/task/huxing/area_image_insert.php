<?php

$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$sql = "select * from tbl_content_image";
$r = mysql_query($sql);
while ($row = mysql_fetch_assoc($r)) {
    $tbl_content_id = $row['tbl_content_id'];
    $up_sql = "update tbl_content set collective_drawing='" . $row['image_token'] . "' where id=" . $tbl_content_id;
    mysql_query($up_sql);
}