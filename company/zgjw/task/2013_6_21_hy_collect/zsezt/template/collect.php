<?php

//ALTER TABLE `hy` ADD COLUMN `collect_flag` TINYINT(4) DEFAULT 0  COMMENT '1-采集数据标志';
//#临时表，存放采集信息
//CREATE TABLE `e_zhan_hy_tmp` (
//  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
//  `name` VARCHAR(255) COLLATE gbk_chinese_ci DEFAULT NULL,
//  `province` VARCHAR(20) COLLATE utf8_general_ci DEFAULT NULL,
//  `city` VARCHAR(20) COLLATE utf8_general_ci DEFAULT NULL,
//  `url` VARCHAR(255) COLLATE gbk_chinese_ci DEFAULT NULL,
//  `collecter` VARCHAR(255) COLLATE gbk_chinese_ci DEFAULT NULL,
//  `created_at` INTEGER(11) DEFAULT NULL,
//  PRIMARY KEY (`id`)
//)ENGINE=InnoDB
//AUTO_INCREMENT=1 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
unset($_SESSION['add_hy']);
/**
 * 装饰公司采集
 */
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$province = getProvinceByid(trim($_POST['province']));
$city = getCityByid(trim($_POST['city']));
$collecter = trim($_POST['collecter']);
$zsezt_province = trim($_POST['zsezt_province']);
$zsezt_city = trim($_POST['zsezt_city']);
$base_url1 = 'http://www.zsezt.com/xuanren/list.php?group=7&exper=1&location=' . $zsezt_province . '&select3=' . $zsezt_city . '&page=1';
$base_url2 = 'http://www.zsezt.com/xuanren/list.php?group=7&exper=2&location=' . $zsezt_province . '&select3=' . $zsezt_city . '&page=1';
collectFromUrl($base_url1);
collectFromUrl($base_url2);

function collectFromUrl($url) {
    global $zsezt_province,$zsezt_city,$collecter,$province,$city,$collecter;
  
    $base_url = $url;
    $exper = 1;
    $pre_exper = "/exper=(\d)/is";
    preg_match($pre_exper, $base_url,$match_exper);
    $exper = $match_exper[1];
#装修E站通
    $content = curl_file_get_contents($base_url);
#采集最大页数
    $pattern_max_pager = '/<span>\s*共\d*条记录\,\s*第\d*\/(\d*)页[^<]*<\/span>/is';
    preg_match_all($pattern_max_pager, $content, $matche_max_pager);
    $no_page_url = substr($base_url, 0, strripos($base_url, '='));
    $max_page = $matche_max_pager[1][0];
#循环采集
    for ($i = 1; $i <= $max_page; $i++) {
        $url = $no_page_url . '=' . $i;
        $url = 'http://www.zsezt.com/xuanren/list.php?group=7&exper='.$exper.'&location=' . $zsezt_province . '&select3=' . $zsezt_city . '&page=' . $i;
        $content = curl_file_get_contents($url);
        #采集列表
        $pattern_al = '/<h5>\s*<a[^>]*href="(\/team\/index\-.+?\.html)"[^>]*>(.+?)<\/a>/is';
        preg_match_all($pattern_al, $content, $matche_list);
        $martch_urls = $matche_list[1];

        foreach ($martch_urls as $j => $martch_url) {
            #判断是否存在
            $select_exist_sql = "select count(*) as num from e_zhan_hy_tmp where name='" . trim($matche_list[2][$j]) . "'";
            $select_num = mysql_fetch_assoc(mysql_query($select_exist_sql));
            if ($select_num['num'] <= 0) {
                #插入
                $insert_sql = "insert into e_zhan_hy_tmp(`name`,`url`,`province`,`city`,`collecter`,`created_at`) values
            ('" . trim($matche_list[2][$j]) . "','" . 'http://www.zsezt.com' . $martch_url . "','" . $province['code'] . "','" . $city['code'] . "','" . $collecter . "','" . time() . "')";
                if (mysql_query($insert_sql)) {
                    //插入session,刷新输出
                    $_SESSION['add_hy'][] = trim($matche_list[2][$j]);
                }
            }
        }
    }
}