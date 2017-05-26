<?php

defined("NOW_PATH") or define("NOW_PATH", dirname(__FILE__));
header("Content-type:text/html;charset=utf-8");

require_once NOW_PATH . "/communication.func.php";
require_once NOW_PATH . "/db.class.php";

/**
 * 数据库配置
 */
$hongbao_db = array(
    'hostname' => "rdszoxgi3lk53s6y271cpublic.mysql.rds.aliyuncs.com:3306",
    'username' => 'we7062',
    'password' => 'male365qqcom',
    'database' => 'we706',
    'charset' => "utf8"
);
$uniacid = 267; //微信ID
$activityid = 526; //活动ID

$db = new mysqldb($hongbao_db);

$sql = "select * from `ims_weisrc_dragonboat_fans` where weid={$uniacid} AND rid={$activityid} AND credit>=100 AND tel!='' AND todaysharenum>0";
$rows = $db->row_query($sql);
foreach ($rows as $row) {
    //查询是否已经发放
    $send_num = $db->row_count('ims_mbsk_records_copy', "uniacid={$row['weid']} AND activity={$row['rid']} AND uid={$row['id']} AND `status`='complete'");
    if ($send_num > 0) {
        continue; //已经发放
    }

    //判断是否关注
    $sql = "select * from `ims_mc_oauth_fans` where oauth_openid='{$row['from_user']}'";
    $oauth_fans = $db->row_query_one($sql);

    $follow_num = $db->row_count('ims_mc_mapping_fans', "uniacid={$row['weid']} AND openid='{$oauth_fans['openid']}' AND `follow`=1");
    if ($follow_num <= 0) {
        continue; //未关注
    }

    $fa_amount = rand(100, 200); //发放金额
    $fa_amount = ($fa_amount / 100);

    $record_arr = array(
        'uniacid' => $row['weid'],
        'activity' => $row['rid'],
        'uid' => $row['id'],
        'fee' => $fa_amount,
        'status' => 'complete',
        'created' => time(),
        'completed' => time(),
    );
    $db->row_insert('ims_mbsk_records_copy', $record_arr);
    $record_id = mysql_insert_id();
    if ($record_id <= 0) {
        continue;
    }

    $fee = $fa_amount * 100;
    $status = send_fee($row['from_user'], $fee, $record_id);
    if ($status === true) {
        //打款成功
        echo ".";
    } else {
        //打款失败
        $sql = "update ims_mbsk_records_copy set status = 'fail',log='{$status}' where id = " . $record_id;
        mysql_query($sql);
    }
}

out_put('本次已经发放完成end');

function out_put($msg) {
//    echo iconv("UTF-8", "GBK//IGNORE", $msg);
    echo $msg;
}

//$res = send_fee('o5YC3t6prx_1YWhaCypbCOQsegDM', 100, $record_id);
