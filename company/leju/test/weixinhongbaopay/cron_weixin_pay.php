<?php

/**
 * 企业打款
 */
define('NOW_PATH', dirname(__FILE__));
header("Content-type:text/html;charset=utf-8");
set_time_limit(0);

require_once NOW_PATH . "/communication.func.php";
require_once NOW_PATH . "/db.class.php";

$teabox_db = array(
    'hostname' => "5602b83c0f291.sh.cdb.myqcloud.com:10990",
    'username' => 'cdb_outerroot',
    'password' => 'male365@qqcom',
    'database' => 'hfwxz',
    'charset' => "utf8"
);
$db = new mysqldb($teabox_db);

//返现金额
//$prize_arr = array(
//    '0' => array('id' => 1, 'prize' => 100, 'v' => 30),
//    '1' => array('id' => 2, 'prize' => 200, 'v' => 30),
//    '2' => array('id' => 3, 'prize' => 300, 'v' => 20),
//    '3' => array('id' => 4, 'prize' => 400, 'v' => 10),
//    '4' => array('id' => 5, 'prize' => 500, 'v' => 10),
//);
//foreach ($prize_arr as $key => $val) {
//    $arr[$val['prize']] = $val['v'];
//}
while (true) {
    $sql = "select * from ims_teabox_penny_user where pay_num=0 AND ispay=1 AND openid !='' ";
    $users = $db->row_query($sql);
    foreach ($users as $user) {
//    $fee = get_rand($arr); //返现概率计算
        //判断用户是否已经发送了
        $count = $db->row_count('ims_teabox_penny_repay', "uid=" . $user['id'] . " AND completed=1");
        if ($count > 0) {
            continue;
        }
        //判断用户是否已经打款
        $count = $db->row_count('ims_teabox_penny_pay', "uid=" . $user['id']);
        if ($count <= 0) {
            continue;
        }
        $users = $db->row_query($sql);

        $fee = $user['money'];
        $insert_data = array(
            'uid' => $user['id'],
            'uniacid' => $user['uniacid'],
            'openid' => $user['openid'],
            'fee' => $fee,
            'created' => time(),
        );
        $db->row_insert('ims_teabox_penny_repay', $insert_data); //插入返现表
        $record_id = $db->insert_id();
        $re = send_fee($user['openid'], $fee, $record_id);
        if ($re === true) {
            //付款成功
            $update_data = array(
                'completed' => 1,
                'pay_time' => time(),
            );
            $sql = "update ims_teabox_penny_user set pay_num=pay_num+1 where id=" . $user['id'];
            $db->query($sql);
            $db->row_update('ims_teabox_penny_repay', $update_data, "id={$record_id}");
        } else {
            //付款失败
            $update_data = array(
                'log' => $re,
            );
            $db->row_update('ims_teabox_penny_repay', $update_data, "id={$record_id}");
        }
    }
    sleep(5);
}
echo '执行完毕';
