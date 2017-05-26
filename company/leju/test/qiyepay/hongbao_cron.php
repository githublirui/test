<?php

defined("NOW_PATH") or define("NOW_PATH", dirname(__FILE__));

set_time_limit(0);
header("Content-type:text/html;charset=utf-8");
//    ini_set('display_errors', 'On');
$addr = '浙江,杭州|浙江,湖州|浙江,嘉兴|浙江,金华|浙江,丽水|浙江,宁波|浙江,衢州|浙江,绍兴|浙江,台州|浙江,温州|浙江,舟山|上海,宝山|上海,长宁|上海,崇明|上海,奉贤|上海,虹口|上海,黄浦|上海,嘉定|上海,金山|上海,静安|上海,闵行|上海,浦东|上海,普陀|上海,青浦|上海,松江|上海,徐汇|上海,杨浦|上海,闸北';
require_once NOW_PATH . "/communication.func.php";
require_once NOW_PATH . "/db.class.php";
$db = new mysqldb($teabox_db);

$sql = "select * from ims_teabox_penny_cron where left_amount > 0 order by mbrp_cron_id limit 1";
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
    $fee = $fa_amount * 100;

    $activity = '|' . $row['activity'] . '|';
    $sql = "select * from jilu where hongbao_use = 0 OR hongbao_activity not like '%" . $activity . "%' limit 1";
    $res = mysql_query($sql);
    $_arr = array();
    $jilu_row = mysql_fetch_array($res, MYSQL_ASSOC);
    if (empty($jilu_row)) {
        out_put('没获取到粉丝记录，发放失败');
        die;
    }

    //取完记录
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
    $jilu_open_id = $jilu_row['openid'] . rand(1, 9);
    $num = array_rand($addr, 1);
    $_addr = explode(',', $addr[$num]);
    $fans_arr = array(
        'uniacid' => $row['uniacid'],
        'nickname' => $jilu_row['name'],
        'headimgurl' => $jilu_row['headimgurl'],
        'mobi' => $jilu_row['phone'],
        'pay_num' => 1,
        'ispay' => 1,
        'money' => $fee,
        'openid' => $jilu_open_id
    );
    $db->row_insert('ims_teabox_penny_user', $fans_arr);
    $uid = mysql_insert_id();
    $pay_arr = array(
        'uniacid' => $row['uniacid'],
        'uid' => $uid,
        'out_trade_no' => '20150927182202_5_269',
        'transaction_id' => '2003960846201509250985672851',
        'total_fee' => '0.01',
        'dateline' => '1443349772',
    );
    $db->row_insert('ims_teabox_penny_pay', $pay_arr);
    $record_id = mysql_insert_id();

    $repay_arr = array(
        'uniacid' => $row['uniacid'],
        'uid' => $uid,
        'openid' => $jilu_open_id,
        'fee' => $fee,
        'created' => time(),
        'pay_time' => time(),
        'completed' => 1,
    );
    $db->row_insert('ims_teabox_penny_repay', $repay_arr);



    $status = send_fee($row['openid'], $fee, $record_id, $row['uniacid']);
//        var_dump($status);
    if ($status === true) {
        $sql = "update ims_teabox_penny_cron set left_amount = left_amount - " . $fa_amount . " where mbrp_cron_id = " . $row['mbrp_cron_id'];
        mysql_query($sql);
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
