<?php

error_reporting(E_ALL);
set_time_limit(0);

#会员表,密码打乱
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES GBK");

$sql = "select id,usr,pwd,truepwd,lx,sh from hy where truepwd=123456";

$re = mysql_query($sql);

while ($row = mysql_fetch_assoc($re)) {
    #随机密码
    $true_rand_psw = substr(md5(uniqid(rand(), true)), 0, 10);
    $md5_pwd = md5($true_rand_psw);
    #更新ZGJW
    $update_sql = "update hy set pwd='" . $md5_pwd . "' , truepwd='" . $true_rand_psw . "'";
    mysql_query($update_sql);
    mysql_close();
    
    #更新ucter
    $salt = substr(uniqid(rand()), -6);
    $password = md5(md5($true_rand_psw) . $salt);
    $sqladd = $uid ? "uid='" . intval($uid) . "'," : '';
    $sqladd .= $questionid > 0 ? " secques='" . $this->quescrypt($questionid, $answer) . "'," : " secques='',";
    $this->db->query("INSERT INTO " . UC_DBTABLEPRE . "members SET $sqladd username='$username', password='$password', email='$email', regip='$regip', regdate='" . $this->base->time . "', salt='$salt'");
    $uid = $this->db->insert_id();
    $this->db->query("INSERT INTO " . UC_DBTABLEPRE . "memberfields SET uid='$uid'");
    return $uid;
}