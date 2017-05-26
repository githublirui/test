<?php

$host = '10.3.255.21:3870';
$user = 'off_dbsrt';
$password = '65c16c8b6';
$db = 'widget';
$charset = 'UTF8';

mysql_connect($host, $user, $password);
mysql_select_db($db);
mysql_query("set names " . $charset);

$f_handle = fopen("all_line.txt", "r");
while (!feof($f_handle)) {
    $ids_str = fgets($f_handle);
    $ids = "62641479,60356184,59709044,59709044";
    $sql = "SELECT loginID FROM  user_mob_device WHERE installID in "
            . "(" . $ids . ");";
    #1. 查询loginid
    $loginid_query = mysql_query($sql);
    while ($row = mysql_fetch_assoc($loginid_query)) {
        $loginid = $row['loginID'];
        #通过logid查询是否绑定
        
    }
}

$phone = 13083056971;
//var_dump(is_numeric($phone));
//die; #为数字或者数字字符
$line_data_num = count(explode(",", "111111,"));
//var_dump($line_data_num);
//die;
$arr = file_get_contents('1.txt');
$str = implode(",", explode("\r\n", $arr));
file_put_contents("2.txt", $str);
//die;

/**
 * 
 * 文本操作，多行数据，合并指定行数据到一行
 * 
 */
function mergeLine($filepath, $newfile, $line_num = 2, $separator = ",") {
    $filepath = realpath($filepath);
    if (!is_file($filepath)) {
        return;
    }
    $handle = fopen($filepath, "r");
    $line_data = array();
    while (!feof($handle)) {
        $content = trim(fgets($handle));
        if ($content) {
            $line_data[] = $content;
        }
//        var_dump($line_data_num == $line_num - 1);
//        var_dump($line_data_num);
//        var_dump($line_num);
//        echo "<br/>";
        if (count($line_data) == $line_num) {
            file_put_contents($newfile, implode($separator, $line_data) . "\r\n", FILE_APPEND);
            $line_data = array();
        }
    }
}

mergeLine('all.txt', 'all_line.txt', 100);
