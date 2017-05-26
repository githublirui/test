<?php

/**
 * 拼图打款
 */
define('NOW_PATH', dirname(__FILE__));
header("Content-type:text/html;charset=utf-8");
set_time_limit(0);

require_once NOW_PATH . "/communication.func.php";
require_once NOW_PATH . "/db.class.php";

$teabox_db = array(
    'hostname' => "rds6nwjxl6758cmm0fwdx.mysql.rds.aliyuncs.com:3306",
    'username' => 'we706',
    'password' => 'male365qqcom',
    'database' => 'we706',
    'charset' => "utf8"
);
$db = new mysqldb($teabox_db);

while (true) {
    $sql = "select * from ims_xhw_pingtu_info where (status is null or status !='complete') AND fee != ''  AND state='浙江' order by id desc";
    $users = $db->row_query($sql);
    foreach ($users as $user) {
//判断用户是否已经输入手机号，分享
        $pingtu_user = $db->row_select_one('ims_xhw_pingtu_user', "info_id=" . $user['id']);
        if (!$pingtu_user || !$pingtu_user['tel'] || $pingtu_user ['score'] <= 0 || $pingtu_user ['share_num'] <= 0) {
            continue;
        }
        $fee = $user['fee'] * 100;

        //插入红包发放表
        $insert_arr = array(
            'info_id' => $user['id'],
            'uniacid' => $user['uniacid'],
            'openid' => $user['openid'],
            'fee' => $fee,
            'created' => time(),
        );
        $db->row_insert("ims_xhw_pingtu_pay_record", $insert_arr);
        $record_id = $db->insert_id();
        $re = send_fee($user['openid'], $fee, $record_id);

        if ($re === true) {
            //付款成功
            $update_data = array(
                'status' => 'complete',
            );
            $update_record_data = array(
                'completed' => 1,
                'pay_time' => time(),
            );
        } else {
            //付款失败
            $update_data = array(
                'status' => $re,
            );
            $update_record_data = array('log' => $re,);
        }
        $db->row_update('ims_xhw_pingtu_pay_record', $update_record_data, "id={$record_id}");
        $db->row_update('ims_xhw_pingtu_info', $update_data, "id={$user['id']}");
    }
    sleep(5);
}
echo '执行完毕';
