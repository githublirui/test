<?php

require 'function.php';
require_once 'uc_client/client.php';

//ini_set("display_errors", 1);
//error_reporting(E_ALL);

@session_start();
$link = mysql_connect($dbhost, $dbuser, $dbpw) or die('connect error');
mysql_select_db($dbname, $link) or die('select db error');
mysql_query("SET NAMES " . $dbcharset);
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

$select_sql = "select * from e_zhan_hy_tmp";
$query = mysql_query($select_sql);

while ($row = mysql_fetch_assoc($query)) {

    $url = $row['url'];
    $url = str_replace('index', 'about', $url);
    #采集信息
    $content = curl_file_get_contents($url);

#店铺名称
    $pattern_name = '/<p>\s*<strong>店铺全称\：<\/strong>\s*<strong>(.*?)<\/strong>\s*<\/p>/is';
    preg_match_all($pattern_name, $content, $matche_name);
    $name = iconv('UTF-8', 'GBK', $matche_name[1][0]);
#店铺简介
    $pattern_des = '/<p\s+style=\"padding-left\:63px;">(.*?)<\/p>/is';
    preg_match_all($pattern_des, $content, $matche_des);
    $des = iconv('UTF-8', 'GBK', $matche_des[1][0]);
#专长
    $pattern_fav = '/<p>\s*<strong>专长\：<\/strong>(.*?)<\/p>/is';
    preg_match_all($pattern_fav, $content, $matche_fav);
    $fav = iconv('UTF-8', 'GBK', $matche_fav[1][0]);
#联系方式
    $pattern_lxr = '/<p><span\s*style=\"letter-spacing\:6px\;\">联系<\/span>人\：(.*?)<br\s*\/>联系电话\：(.*?)<br\s*\/>传.*真\：(.*?)<br\s*\/>联系手机：(.*?)<br\s*\/>邮政编码：(.*?)<br\s*\/>电子邮件：(.*?)<br\s*\/>QQ：(.*?)<br\s*\/>MSN：(.*?)<br\s*\/>联系地址：(.*?)<br\s*\/>公司网址：(.*?)<br\s*\/>\s*<\/p>/is';
    preg_match_all($pattern_lxr, $content, $matche_lxr);

    $lxr = iconv('UTF-8', 'GBK', $matche_lxr[1][0]);
    $lxdh = iconv('UTF-8', 'GBK', $matche_lxr[2][0]);
    $fax = iconv('UTF-8', 'GBK', $matche_lxr[3][0]);
    $phone = iconv('UTF-8', 'GBK', $matche_lxr[4][0]);
    $yzbm = iconv('UTF-8', 'GBK', $matche_lxr[5][0]);
    $email = iconv('UTF-8', 'GBK', $matche_lxr[6][0]);
    $qq = iconv('UTF-8', 'GBK', $matche_lxr[7][0]);
    $msn = iconv('UTF-8', 'GBK', $matche_lxr[8][0]);
    $address = iconv('UTF-8', 'GBK', $matche_lxr[9][0]);
    $wz = iconv('UTF-8', 'GBK', $matche_lxr[10][0]);
    #验证邮箱
    $preg = '/^[a-zA-Z0-9_+.-]+\@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]{2,4}$/i';
    if (!$email || preg_match_all($preg, $email, $match_email) == 0 || $email == '保密') {
        $email = 'office@chhome.cn';
    }
    
    #判断是否存在
    $select_hy_exist_sql = "select count(*) as num from hy where usr='" . $name . "' or gsmc='" . trim($name) . "'";
    $select_num = mysql_fetch_assoc(mysql_query($select_hy_exist_sql));
    if ($select_num['num'] > 0) {
        continue;
    }

    $colunm = 'code,usr,psw,truepwd,regrq,lx,email,tel,sh,gsmc,gsdz,lxr,gsjj,wz,province,city,qq,sj,fax,yzbm,special_skill';
    $true_psw = substr(md5(uniqid(rand(), true)), 0, 6);
    $uid = uc_user_register($name, $true_psw, $email);
    if ($uid > 0) {
        $sql = "insert into hy (`code`,`usr`,`pwd`,`truepwd`,`regrq`,`lx`,`email`,`tel`,`sh`,`gsmc`,`gsdz`,`lxr`,`gsjj`,`wz`,`province`,`city`,`qq`,`sj`,`fax`,`yzbm`,`special_skill`,`collect_flag`)
         values ('" . getHyUniqueCode() . "','" . $name . "','" . md5($true_psw) . "','" . $true_psw . "','" . date('Y-m-d H:i:s', $row['created_at']) . "',4,'" . $email . "','" . $lxdh . "',1,
             '" . $name . "','" . $address . "','" . $lxr . "','" . $des . "','" . $wz . "','" . $row['province'] . "','" . $row['city'] . "','" . $qq . "','" . $phone . "','" . $fax . "','" . $yzbm . "','" . $fav . "',1)   
        ;";
        mysql_query($sql);

        #插入人才库
        $q = 'insert into `phpyun`.phpyun_userid(username,password,email,date_reg,usertype,truepwd) values("' . $name . '","' . md5($true_psw) . '","' . $email . '","' . mktime() . '","0","' . $true_psw . '")';
        mysql_query($q);
        $id = mysql_insert_id();
        mysql_query('insert into `phpyun`.phpyun_resume(uid,name) values(' . $id . ',"' . $name . '")');
        echo '.';
        flush();
    }
}

