<?php

$s = 'a:5:{s:5:"appid";s:18:"wx5f308c312ab3851e";s:6:"secret";s:33:"9250715c0a47b97d62b5c04bd734378c ";s:11:"share_title";s:18:"美女视频标题";s:10:"share_desc";s:18:"美女视频描述";s:9:"share_img";s:51:"images/2/2015/08/BT46bh4jB5ZtB4ZZpb86yZh8TUYpuu.jpg";}';
$a = unserialize($s);
$a['api'] = array(
    'appid' => $a['appid'],
    'secret' => $a['secret'],
);
unset($a['appid']);
unset($a['secret']);
var_dump(serialize($a));
die;
array(
    array(array(1), array(2, 3, 4, 5)),
    array(array(6), array(7, 8, 9, 10)),
);

$array = range(1, 20);
$ret = array();
$already_add = array();
//foreach ($array as $key => $row) {
//    $s = count(end($ret));
//    if (in_array($key, $already_add)) {
//        continue;
//    }
//    $right_num = 4; //分四个
//    if ($s <= 1 && !empty($ret)) {
//        $data = array_slice($array, $key, $right_num, true);
//        $ret[] = $data;
//        $already_add = array_merge($already_add, array_keys($data));
//    } else {
//        $ret[] = array($row);
//        $already_add[] = $key;
//    }
//}
$array = array_chunk($array, 5);
foreach ($array as $key => $row) {
    $_ret[0] = array_slice($row, 0, 1);
    $_ret[1] = array_slice($row, 1);
    $ret[] = $_ret;
}

var_dump($ret);
die;
?>
