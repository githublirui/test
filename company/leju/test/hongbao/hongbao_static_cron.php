<?php
	header("Content-type:text/html;charset=utf-8");
    require_once "./db.class.php";
    $hongbao_db = array(
        'hostname' => "localhost",
        'username'=> 'root',
        'password' => 'male365@qq.com',
        'database' => 'we706',
        'charset'  => "utf8"
    );
    $db = new mysqldb($hongbao_db);
    $sql =  "SELECT count(*) as total_fans FROM ims_mbrp_fans  WHERE `uniacid` = 5";
    $res = mysql_query($sql);
    $row = mysql_fetch_array($res, MYSQL_ASSOC);
    $sql2 =  "SELECT total,real_total FROM  ims_mbrp_static WHERE id=1";
    $res2 = mysql_query($sql2);
    $row2 = mysql_fetch_array($res2, MYSQL_ASSOC);
    $add = $row['total_fans'] - $row2['real_total'] + 2177;
    $sql = "update ims_mbrp_static set real_total = ".$row['total_fans'].",total = total + ".$add." where id = 1";
    mysql_query($sql);
    out_put('同步完毕');

function out_put($msg) {
    $msg = date('Y-m-d H:i:s').$msg."\n";
    exit($msg);
}
//$res = send_fee('o5YC3t6prx_1YWhaCypbCOQsegDM', 100, $record_id);
