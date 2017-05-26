<?php

#1.微型计算机,2.显示器，3.盘式蚊香，4.轻型汽车，5.预拌混凝土,6.彩色电视机广播接收器,7.数字式多功能复印机,8.喷墨和

/**
 *  home数据库
 *  删除公司名称重复的产品库
 *  删除个别分类的产品
 */
$dbhost = "localhost";
$dbuser = 'root';
$dbpw = '';
$dbname = 'home';
$dbcharset = 'GBK';
$pro_dels = array(
    '微型计算机',
    '显示器',
    '盘式蚊香',
    '轻型汽车',
    '预拌混凝土',
    '彩色电视机广播接收器',

    '数字式多功能复印机',
    '喷墨和',
);
$link = mysql_connect($dbhost, $dbuser, $dbpw) or die('connect error');
mysql_select_db($dbname, $link) or die('select db error');
mysql_query("SET NAMES " . $dbcharset);
set_time_limit(0);

$sql = 'select * from think_chanpin group by company_name';
$re = mysql_query($sql);
$i = 0;
while ($row = mysql_fetch_assoc($re)) {
    $company_name = $row['company_name'];
//    $product_category = iconv("GBK", "UTF-8//ignore", $row['product_category']);
    #删除在此分类内的
//    if (in_array($product_category, $pro_dels)) {
////        $del_sql = "delete from think_chanpin where id=" . $row['id'];
////        mysql_query($del_sql);
//        echo '.';
//        flush();
//        $i++;
//    }
    #排除名字重复的
    $del_sql = "delete from think_chanpin where id !=" . $row['id'] . " and company_name='" . $company_name . "'";
    mysql_query($del_sql);
    echo '.';
    flush();
}
var_dump($i);
