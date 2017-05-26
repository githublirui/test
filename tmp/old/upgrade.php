<?php

/**
 * sql更新脚本
 * cmd下  php upgrade.php folder 运行
 * 
 */
$dbhost = "localhost";
$dbuser = 'root';
$dbpw = '';
$dbname = 'zgjw';
$is_run_all = false; //是否全部执行

$conn = @mysql_connect($dbhost, $dbuser, $dbpw) or die('E010001'); // 连接数据源
@mysql_select_db($dbname) or die('E010002'); // 选择数据库
@mysql_query("set names gbk");
$folder = $argv[1];
$version = isset($argv[2]) ? (int) $argv[2] : 1;

$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $folder;
if (!file_exists($path)) {
    echo '文件夹不存在';
    die;
}

var_dump(update($version));
die;

function update($version) {
    global $path;
    $file_path = $path . DIRECTORY_SEPARATOR . $version . '.sql';
    $file_o_path = $path . DIRECTORY_SEPARATOR . $version . '_o.sql';

    if (file_exists($file_path)) {
        $sql = file_get_contents($file_location);
        mysql_query($sql);
    }
}

$list = scandir($path); // 得到该文件下的所有文件和文件夹
foreach ($list as $file) {//遍历
    $file_location = $path . DIRECTORY_SEPARATOR . $file; //生成路径
    if (!is_dir($file_location) && $file != "." && $file != "..") { //判断是不是文件夹
        //判断是否全部执行
        if ($is_run_all || preg_match('/[0-9]+\.sql/is', $file) != 0) {
            var_dump($file);
            $sql = explode(';', file_get_contents($file_location));
//            if (mysql_query($sql)) {
//                echo '执行文件 ' . $file . ' 成功';
//            } else {
//                echo '执行文件 ' . $file . ' 失败 ' . mysql_errno();
//            }
        }
    }
}
die;
