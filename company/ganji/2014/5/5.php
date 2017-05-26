<?php

$str = '';

var_dump(count(explode(',', $str)));
die;
$dir = 'E:\projects\ganji\ganji_online\mobile_client\tools';
$list = scandir($dir); // 得到该文件下的所有文件和文件夹


foreach ($list as $file) { //遍历
    $filePath = $dir . DIRECTORY_SEPARATOR . $file;
    if (is_file($filePath)) { //判断是不是文件夹
        $files[] = $filePath;
    }
}
$file = 'E:\kuaipan\test\company\ganji\2014\5\pushed_61_8.log';
$noticeId = explode('_', basename($file));
$noticeId = $noticeId[1];
var_dump($noticeId);
die;
$fp = fopen($file, 'r');


//while (!feof($fp)) {
$content = fgets($fp);
//}
$content = explode("\t", $content);
$date = $content[0];
$countDeviceIds = count(explode(',', $content[1]));
var_dump($date);
var_dump($countDeviceIds);
die;

function test() {
    return true;
}

$a = array(1, 2);
$b = array(3, 4);
var_dump(array_merge($a, $b));
?>
