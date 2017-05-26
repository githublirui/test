<?php
defined("NOW_PATH") or define("NOW_PATH", dirname(__FILE__));
header("Content-type:text/html;charset=utf-8");
//    ini_set('display_errors', 'On');
require_once NOW_PATH . "/communication.func.php";

if (isset($_POST['openid'])) {

    if ($_POST['min'] > $_POST['max']) {
        echo '最大最小金额错误';
        die;
    }
    require_once NOW_PATH . "/db.class.php";
    $db = new mysqldb($teabox_db);
    $_arr = array();
    $arr = array(
        'order_id' => $_POST['order_id'],
        'openid' => $_POST['openid'],
        'activity' => $_POST['activity'],
        'gift' => $_POST['gift'],
        'uniacid' => $_POST['uniacid'],
        'amount' => $_POST['amount'],
        'left_amount' => $_POST['amount'],
        'min' => $_POST['min'],
        'max' => $_POST['max'],
        'raw_add_time' => date('Y-m-d H:i:s'),
    );
    $db->row_insert('ims_teabox_penny_cron', $arr);
    $id = mysql_insert_id();
    echo '数据已经提交(' . $id . ')';
    die;
}
?>
<body  style="display:block;" onload = "">
    <form method="post" action="" name="uform">
        <div style="margin:0 auto;width:450px;">
            <table>
                <tr>
                    <td>发放编码：</td>
                    <td><input type="text" name="order_id" value="">每次必须唯一</td>
                </tr>
                <tr>
                    <td>openid：</td>
                    <td><input type="text" name="openid" value=""></td>
                </tr>
                <tr>
                    <td>活动ID(uniacid)：</td>
                    <td><input type="text" name="uniacid" value=""></td>
                </tr>
                <tr>
                    <td>gift：</td>
                    <td><input type="text" name="gift" value=""></td>
                </tr>
                <tr>
                    <td>activity：</td>
                    <td><input type="text" name="activity" value=""></td>
                </tr>
                <tr>
                    <td>发放金额：</td>
                    <td><input type="text" name="amount" value=""></td>
                </tr>
                <tr>
                    <td>最小值：</td>
                    <td><input type="text" name="min" value=""></td>
                </tr>
                <tr>
                    <td>最大值：</td>
                    <td><input type="text" name="max" value=""></td>
                </tr>
                <tr >
                    <td colspan=2><input type="submit" value="确认"></td>
                <tr>
            </table></div>
    </form></body>
