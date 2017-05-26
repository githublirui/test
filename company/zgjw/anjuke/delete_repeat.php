<?php

include 'conn.php';
include 'function.php';

$sql = "select * from tbl_content group by name";
$re = mysql_query($sql);

while ($row = mysql_fetch_assoc($re)) {
    $del_repeat_sql = "delete from tbl_content where name='" . $row['name'] . "' and id !=" . $row['id'];
    mysql_query($del_repeat_sql);
    echo '.';
    flush();
}
