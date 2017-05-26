<?php
include dirname(__FILE__) . "/reader.php";

header("Content-type:text/html;charset=utf-8");

if ($_FILES['file']) {
    $num = 0;
    $read = new Spreadsheet_Excel_Reader();
    $read->setOutputEncoding('UTF-8');
    $read->read($_FILES['file']['tmp_name']);
    $excel_data = $read->sheets[0]['cells'];
    array_shift($excel_data);
    foreach ($excel_data as $_data) {
        $username = $_data[1];
        $cellphone = $_data[2];
        //插入数据库
        $data = array('weid' => $_W['weid'], 'from_user' => 'admin', 'realname' => $username, 'mobile' => $cellphone, 'createtime' => TIMESTAMP);
        $profile = pdo_fetch('SELECT count(*) as num FROM ' . tablename('broke_customer') . " WHERE `weid` = :weid AND `mobile` = :mobile", array(':flag' => 1, ':weid' => $_W['weid'], ':mobile' => $cellphone));
        if ($profile['num'] >0) {
            continue;
        }
//        $ret = pdo_insert('broke_customer', $data);
        if ($ret) {
            $num++;
        }
    }
    message('导入成功 ' . $num . "条", "refresh", 'success');
}
?>

<form method="post" action=""  enctype="multipart/form-data" >
    <input type="file" name="file"> 
    <input type="submit" class="btn btn-primary" value="导入XLS">

</form>

