<?php

$now_path = dirname(__FILE__);
$file_path = $now_path . "/order.txt";

//12_28
$content = file_get_contents($file_path);
$arr = explode(",", $content);
$sub_arr = array_chunk($array, 5000);
$fhandle = fopen($file_path, 'r');
$result = array();
while (!feof($fhandle)) {
    $line = fgets($fhandle);
//    $arr = explode('			', $line);
//    if ($arr[0]) {
//        $result[0][] = trim($arr[0]);
//    }
//    if ($arr[1]) {
//        $result[1][] = trim($arr[1]);
//    }
//    if(isset($arr[2]) && $arr[2]) {
//        $result[1][] = trim($arr[2]);
//    }
}
$ret = array_diff($result[1], $result[0]);
file_put_contents($now_path . "/order_ret.txt", implode("\n", $ret));
