<?php

#小区数据省市县的中文名称关联换成邮编关联
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'test';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

$sql_province = "select * from tbl_content";

$r_province = mysql_query($sql_province);

while ($row = mysql_fetch_assoc($r_province)) {
    //如果省不存在，则删除
    if (!$row['province']) {
        $del_sql = 'delete from tbl_content where id=' . $row['id'];
        mysql_query($del_sql);
    }
    $select_pro_sql = 'select * from province where name="' . $row['province'] . '"';
    $select_city_sql = 'select * from city where name="' . $row['city'] . '"';
    $select_area_sql = 'select * from area where name="' . $row['area'] . '"';

    $s_provin = mysql_fetch_assoc(mysql_query($select_pro_sql));
    $s_city = mysql_fetch_assoc(mysql_query($select_city_sql));
    $s_area = mysql_fetch_assoc(mysql_query($select_area_sql));
    if ($s_provin) {
        $up_pro_sql = 'update tbl_content set province=' . $s_provin['code'] . ' where id=' . $row['id'];
        mysql_query($up_pro_sql);
    }
    if ($s_city) {
        $up_pro_sql = 'update tbl_content set city=' . $s_city['code'] . ' where id=' . $row['id'];
        mysql_query($up_pro_sql);
    }
    if ($s_area) {
        $up_pro_sql = 'update tbl_content set area=' . $s_area['code'] . ' where id=' . $row['id'];
        mysql_query($up_pro_sql);
    }
}
?>