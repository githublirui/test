<?php

$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$sql = "select * from tbl_content where (collective_drawing is not null or collective_drawing !='')";
$r = mysql_query($sql);
while ($row = mysql_fetch_assoc($r)) {
    $sql = "update tbl_content set collective_drawing='" . $row['collective_drawing'] . "' where id=" . $row['id'] . ";\n";
    file_put_contents('update.sql', $sql, FILE_APPEND);
    echo '.';
    flush();
}