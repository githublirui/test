<?php

/**
 * 录制视频
 */
header("Content-type:text/html;charset=utf-8");
//    ini_set('display_errors', 'On');
require_once "./communication.func.php";
require_once "./db.class.php";

$db = new mysqldb($db_config);

$record_arr = array(
    'url' => 'http://www.baidu.com',
);
$db->row_insert('video', $record_arr);

