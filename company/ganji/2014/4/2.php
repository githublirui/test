<?php

$post['post_at'] = 1398070806;
echo date('Y-m-d', $post['post_at']);
$result = '';
$hourS = 60 * 60; //1小时
$secoendDiff = time() - $post['post_at']; //发帖时间间隔/秒
if ($secoendDiff <= 60) {
    $result = '刚刚'; //一分钟内
} else if ($secoendDiff > 60 && $secoendDiff <= $hourS) {
    $result = floor($secoendDiff / 60) . '分钟前'; //几分钟前
} else if ($secoendDiff > $hourS && $secoendDiff <= $hourS * 24) {
    $result = floor($secoendDiff / $hourS) . '小时前'; //几小时前
} else if ($secoendDiff > $hourS * 24 && $secoendDiff <= $hourS * 24 * 3) {
    $result = floor($secoendDiff / ($hourS * 24)) . '天前'; //1到3天内，几天前
} else if ($secoendDiff > $hourS * 24 * 3) {
    $result = date("m-d", $post['post_at']); //大于三天显示日期
}
var_dump($result);
die;


$time = time();
sleep(5);

echo time() - $time;
die;

//echo assert(is_int('1'));
//echo 1231;

$tasklist = "C:/Windows/System32/tasklist.exe";
@exec($tasklist, $plist);
//var_dump($plist);die;
foreach ($plist as $p) {
//    print_r($plist);
}