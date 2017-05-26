<?php

$a = range(1, 5000);

function convert($size) {
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

echo convert(memory_get_usage(true));
die;

$msg = '<img width="\"500\"" height="281" src="uploads/file/news/2015-03-09/2ce79eb65f2df442f00348cc95531994.jpg" alt="\"201503091142518224.jpg\"" >';
$pa = "/<img.+src=['\"](.*)['\"]\/?>/is";
$pa = "/(<img.+src=['\"])(.*?)(['\"].+\/?>)/is";
$str = preg_replace($pa, '$1http://www.baidu.com/$2$3', $msg);
var_dump($str);
die;
