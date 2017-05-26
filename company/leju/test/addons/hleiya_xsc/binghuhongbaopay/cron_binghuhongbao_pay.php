<?php

/**
 * 拼图打款
 */
define('NOW_PATH', dirname(__FILE__));
header("Content-type:text/html;charset=utf-8");
set_time_limit(0);

require_once NOW_PATH . "/communication.func.php";
require_once NOW_PATH . "/db.class.php";

$hleiya_db = array(
    'hostname' => "5602b83c0f291.sh.cdb.myqcloud.com:10990",
    'username' => 'cdb_outerroot',
    'password' => 'male365@qqcom',
    'database' => 'hfwxz',
    'charset' => "utf8"
);
$db = new mysqldb($hleiya_db);

while (true) {
    $sql = "SELECT * FROM ims_hleiya_penny_user WHERE ispay=1 AND pay_num<=0 AND money>0 AND dopenid !='' AND province='安徽'";
    $users = $db->row_query($sql);
    foreach ($users as $user) {
        //判断用户是否已经发送了
        $count = $db->row_count('ims_hleiya_penny_repay', "uid=" . $user['id'] . " AND completed=1");
        if ($count > 0) {
            continue;
        }
        $fee = $user['money'];
        $insert_data = array(
            'uid' => $user['id'],
            'uniacid' => $user['uniacid'],
            'openid' => $user['openid'],
            'fee' => $fee,
            'created' => time(),
        );
        $db->row_insert('ims_hleiya_penny_repay', $insert_data); //插入返现表
        $record_id = $db->insert_id();
        $re = send_fee($user['openid'], $fee, $record_id);
        if ($re === true) {
            //付款成功
            $update_data = array(
                'completed' => 1,
                'pay_time' => time(),
            );
            $sql = "update ims_hleiya_penny_user set pay_num=pay_num+1 where id=" . $user['id'];
            $db->query($sql);
            $db->row_update('ims_hleiya_penny_repay', $update_data, "id={$record_id}");
        } else {
            //付款失败
            $update_data = array(
                'log' => $re,
            );
            $db->row_update('ims_hleiya_penny_repay', $update_data, "id={$record_id}");
        }
    }
}
echo '执行完毕';
