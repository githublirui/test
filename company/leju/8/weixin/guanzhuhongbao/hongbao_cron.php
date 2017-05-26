<?php

set_time_limit(0);
header("Content-type:text/html;charset=utf-8");
//    ini_set('display_errors', 'On');
$addr = '安徽,合肥';
require_once "./communication.func.php";
require_once "./db.class.php";
$db = new mysqldb($hongbao_db);

$sql = "select * from ims_mbrp_cron where left_amount > 0 order by mbrp_cron_id limit 1";
$res = mysql_query($sql);
$_arr = array();
$row = mysql_fetch_array($res, MYSQL_ASSOC);
if (empty($row)) {
    out_put('无需要发放的记录');
    die;
}
$left_amount = $row['left_amount'];
$addr = explode('|', $addr);
//每次发放100个红包
for ($i = 0; $i < 1000; $i++) {
    if ($left_amount <= 0) {
        out_put('本次已经发放完成mid');
        die;
    }

    $fa_amount = rand($row['min'] * 100, $row['max'] * 100);
    $fa_amount = ($fa_amount / 100);

    if ($fa_amount > $left_amount) {
        $fa_amount = $left_amount;
    }
    $left_amount -= $fa_amount;
    $activity = '|' . $row['activity'] . '|';
    $sql = "select * from jilu where hongbao_use = 0 OR hongbao_activity not like '%" . $activity . "%' limit 1";
    $res = mysql_query($sql);
    $_arr = array();
    $jilu_row = mysql_fetch_array($res, MYSQL_ASSOC);
    if (empty($jilu_row)) {
        out_put('没获取到粉丝记录，发放失败');
        die;
    }

    $num = array_rand($addr, 1);
    $_addr = explode(',', $addr[$num]);
    $fans_arr = array(
        'uniacid' => $row['uniacid'],
        'openid' => $jilu_row['openid'] . rand(1, 9),
        'nickname' => $jilu_row['name'],
        'state' => $_addr[0] ? $_addr[0] : '',
        'city' => $_addr[1] ? $_addr[1] : '',
        'avatar' => $jilu_row['headimgurl']
    );
    $db->row_insert('ims_mbrp_fans', $fans_arr);
    $uid = mysql_insert_id();
    $record_arr = array(
        'uniacid' => $row['uniacid'],
        'uid' => $uid,
        'activity' => $row['activity'],
        'gift' => $row['gift'],
        'fee' => $fa_amount,
        'status' => 'complete',
        'created' => time(),
        'completed' => time(),
    );
    $db->row_insert('ims_mbrp_records', $record_arr);
    $record_id = mysql_insert_id();

    $fee = $fa_amount * 100;

    $status = send_fee($row['openid'], $fee, $record_id, $row['uniacid']);
//        var_dump($status);
    if ($status === true) {

        $sql = "update ims_mbrp_cron set left_amount = left_amount - " . $fa_amount . " where mbrp_cron_id = " . $row['mbrp_cron_id'];
        mysql_query($sql);

        if (empty($jilu_row['hongbao_huodong'])) {
            $hongbao_huodong = '|' . $row['uniacid'] . '|';
        } else {
            $hongbao_huodong = $jilu_row['hongbao_huodong'] . $row['uniacid'] . '|';
        }
        if (empty($jilu_row['hongbao_activity'])) {
            $hongbao_activity = '|' . $row['activity'] . '|';
        } else {
            $hongbao_activity = $jilu_row['hongbao_activity'] . $row['activity'] . '|';
        }
        $sql2 = "update jilu set hongbao_use = 1,hongbao_huodong ='{$hongbao_huodong}',hongbao_activity ='{$hongbao_activity}' where id = " . $jilu_row['id'];
        mysql_query($sql2);
        echo "uniacid: " . $row['uniacid'] . " fee: " . $fee . "   openID: " . $row['openid'] . " \n";
    } else {
        $sql3 = "update ims_mbrp_records set status = '{$status}' where id = " . $record_id;
        mysql_query($sql3);
        out_put('打款失败，失败信息' . $status);
    }
    sleep(9);
}
out_put('本次已经发放完成end');

function out_put($msg) {
    $msg = date('Y-m-d H:i:s') . $msg;
    echo iconv("UTF-8", "GBK//IGNORE", $msg);
}

//$res = send_fee('o5YC3t6prx_1YWhaCypbCOQsegDM', 100, $record_id);
