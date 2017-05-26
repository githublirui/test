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
    'hostname' => "rdszoxgi3lk53s6y271cpublic.mysql.rds.aliyuncs.com",
    'username' => 'we7062',
    'password' => 'male365qqcom',
    'database' => 'we706',
    'charset' => "utf8"
);
$db = new mysqldb($hleiya_db);

while (true) {
    $sql = "SELECT * FROM ims_voice_fans WHERE uniacid=239 and localss !='' and ishare>0 and pay_num<=0";
    $users = $db->row_query($sql);
    foreach ($users as $user) {
        //判断用户是否已经发送了
//        $count = $db->row_count('ims_hleiya_penny_repay', "uid=" . $user['id'] . " AND completed=1");
//        if ($count > 0) {
//            continue;
//        }
        $fee = rand(100, 300);
        $insert_data = array(
            'uid' => $user['id'],
            'uniacid' => $user['uniacid'],
            'openid' => $user['openid'],
            'fee' => $fee,
            'created' => time(),
        );

        $db->row_insert('ims_voice_record', $insert_data); //插入返现表
        $record_id = $db->insert_id();

        $re = send_fee($user['openid'], $fee, $record_id);

        if ($re === true) {
            //付款成功
            $update_data = array(
                'completed' => 1,
                'pay_time' => time(),
            );
            $db->row_update('ims_voice_record', $update_data, "id={$record_id}");
            
            $sql = "update ims_voice_fans set pay_num=pay_num+1 where id=" . $user['id'];
            $db->query($sql);
        } else {
            //付款失败
            $update_data = array(
                'log' => $re,
            );
            //风险帐号
            if (strpos($re, '风险') !== false) {
                $sql = "update ims_voice_fans set pay_num = -1 where id=" . $user['id'];
                $db->query($sql);
            }
            $db->row_update('ims_voice_record', $update_data, "id={$record_id}");
        }
    }
}
echo '执行完毕';
