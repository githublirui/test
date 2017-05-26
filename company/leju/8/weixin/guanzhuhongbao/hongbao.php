<?php
header("Content-type:text/html;charset=utf-8");
//    ini_set('display_errors', 'On');
require_once "./communication.func.php";

if (isset($_POST['openid'])) {

    if ($_POST['min'] > $_POST['max']) {
        echo '最大最小金额错误';
        die;
    }
    require_once "./db.class.php";
    $db = new mysqldb($hongbao_db);
    $_arr = array();

    //插入pingtu user
//    $num = ceil($_POST['amount'] / $_POST['min']);
//    $sql = "select * from jilu where  limit " . $num;
//    $sql = "select * from jilu where hongbao_huodong not like '%" . $_POST['weid'] . "%' limit " . $num;
//    $res = mysql_query($sql);
//    while ($row = mysql_fetch_assoc($res)) {
//        $jilu_row[] = $row;
//    }
//    $left_amount = $_POST['amount'];
//    $i = 0;
//    while ($left_amount > 0) {
//        if ($left_amount > 0) {
////            $fa_amount = rand($_POST['min'] * 100, $_POST['max'] * 100);
////            $fa_amount = ($fa_amount / 100);
//            $fa_amount = rand($_POST['min'], $_POST['max']);
//            if ($fa_amount > $left_amount) {
//                $fa_amount = $left_amount;
//            }
//            // 插入模拟数据表
//            $pintu_user_arr = array(
//                'uname' => $jilu_row[$i]['name'],
//                'weid' => $_POST['weid'],
//                'tel' => rand_phone(),
//                'score' => $fa_amount,
//                'createtime' => time(),
//            );
//            $db->row_insert('ims_xhw_pingtu_user', $pintu_user_arr);
//            $db->row_update('jilu', array('hongbao_huodong' => $jilu_row[$i]['hongbao_huodong'] . '|' . $_POST['weid']), ' id=' . $jilu_row[$i]['id']);
//        }
//        $left_amount -= $fa_amount;
//        $i++;
//    }
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
    $db->row_insert('ims_mbrp_cron', $arr);
    $id = mysql_insert_id();
    echo '数据已经提交(' . $id . ')';
    die;
}

//    $sql =  "select * from jilu limit 10";
//    $res = mysql_query($sql);
//    $_arr = array();
//    while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
//        $_arr[] = $row;
//    }
//    var_dump($_arr);die;
//$res = send_fee('o5YC3t6prx_1YWhaCypbCOQsegDM', 100, $record_id);
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
