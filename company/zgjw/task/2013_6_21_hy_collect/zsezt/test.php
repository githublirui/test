<?php

set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
@session_start();
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';

$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);

/**
 * 获取注册用户唯一码
 */
function getHyUniqueCode() {
    $result = '';
    $code = substr(md5(uniqid(rand(), true)), 0, 10);
    $select_sql = "select count(*) as num from hy where code='" . $code . "'";
    $resouce = mysql_query($select_sql);
    while ($row = mysql_fetch_assoc($resouce)) {
        if ($row['num'] <= 0) {
            $result = $code;
        } else {
            $result = getHyUniqueCode();
        }
    }
    return $result;
}

function curl_file_get_contents($durl) {
    $ch = curl_init($durl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    return curl_exec($ch);
}

#装修E站通
//$url = 'http://www.zsezt.com/xuanren/list.php?group=7&location=%E5%A4%A9%E6%B4%A5%E5%B8%82&select3=%E5%B8%82%E8%BE%96%E5%8C%BA&page=2';
//$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
////curl_setopt($ch, CURLOPT_REFERER, $_SERVER['']);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//$contents = curl_exec($ch);
//$ff = substr($url, 0, strripos($url, '='));
//
//$pattern_al = '/<h5>\s*<a[^>]*href="(\/team\/index\-.+?\.html)"[^>]*>(.+?)<\/a>/is';
//preg_match_all($pattern_al, $contents, $matche_list);
//var_dump($matche_list);
//#采集最大页数
//$pattern_max_pager = '/<span>\s*共\d*条记录\,\s*第\d*\/(\d*)页[^<]*<\/span>/is';
//preg_match_all($pattern_max_pager, $contents, $matche_max_pager);
//var_dump($matche_max_pager[1][0]);
//die;
#装修E站通详细页采集
//$url = "http://www.zsezt.com/team/about-7318.html";
//$content = curl_file_get_contents($url);
//#店铺名称
//$pattern_name = '/<p>\s*<strong>店铺全称\：<\/strong>\s*<strong>(.*?)<\/strong>\s*<\/p>/is';
//preg_match_all($pattern_name, $content, $matche_name);
//$name = $matche_name[1][0];
//#店铺简介
//$pattern_des = '/<p\s+style=\"padding-left\:63px;">(.*?)<\/p>/is';
//preg_match_all($pattern_des, $content, $matche_des);
//$des = $matche_des[1][0];
//#专长
//$pattern_fav = '/<p>\s*<strong>专长\：<\/strong>(.*?)<\/p>/is';
//preg_match_all($pattern_fav, $content, $matche_fav);
//$fav = $matche_fav[1][0];
//#联系方式
//$pattern_lxr = '/<p><span\s*style=\"letter-spacing\:6px\;\">联系<\/span>人\：(.*?)<br\s*\/>联系电话\：(.*?)<br\s*\/>传.*真\：(.*?)<br\s*\/>联系手机：(.*?)<br\s*\/>邮政编码：(.*?)<br\s*\/>电子邮件：(.*?)<br\s*\/>QQ：(.*?)<br\s*\/>MSN：(.*?)<br\s*\/>联系地址：(.*?)<br\s*\/>公司网址：(.*?)<br\s*\/>\s*<\/p>/is';
//preg_match_all($pattern_lxr, $content, $matche_lxr);
//$lxr = $matche_lxr[1][0];
//$lxdh = $matche_lxr[2][0];
//$fax = $matche_lxr[3][0];
//$phone = $matche_lxr[4][0];
//$yzbm = $matche_lxr[5][0];
//$email = $matche_lxr[6][0];
//$qq = $matche_lxr[7][0];
//$msn = $matche_lxr[8][0];
//$address = $matche_lxr[9][0];
//$wz = $matche_lxr[10][0];
$email = '1760142825@qq.com';
$preg = '/^[a-zA-Z0-9_+.-]+\@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]{2,4}$/i';
$s = preg_match_all($preg, $email, $match);
var_dump($s);
?>