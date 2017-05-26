<?php
#去除商圈重复
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'test';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

#去除商圈重复
$sql = "select * from tbl_content group by district";
$r = mysql_query($sql);
while ($row = mysql_fetch_assoc($r)) {
    $dic = $row['district'];
    //查询商圈是否存在
    $sele_sql = "select count(*) as num from tbl_districts where areaid='" . $row['area'] . "' and district='" . trim($dic) . "'";
    $sele_re = mysql_query($sele_sql);
    $num_arr = mysql_fetch_assoc($sele_re);
    if ($num_arr['num'] == 0) {
        if (!empty($dic)) {
            $sql = "insert into tbl_districts (`districtid`,`district`,`areaid`,`username`)  
            values (0,'" . trim($dic) . "','" . $row['area'] . "','" . $row['username'] . "')";
            mysql_query($sql);
            echo '.';
            flush();
        }
    }
}