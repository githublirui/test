<?php

//DROP TABLE IF EXISTS `tbl_content_image`;
//CREATE TABLE `tbl_content_image` (
//  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
//  `tbl_content_id` INTEGER(11) DEFAULT NULL COMMENT '小区id',
//  `image_type` TINYINT(4) DEFAULT NULL COMMENT '图片类型，1-实景图',
//  `image_token` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
//  PRIMARY KEY (`id`),
//  UNIQUE KEY `image_token` (`image_token`)
//)ENGINE=InnoDB
//AUTO_INCREMENT=1 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
//COMMENT='小区图片表';
#注: 搜房图片有水印，暂时没有采
include 'UtilsImage.php';
ini_set("display_errors", 1);
error_reporting(E_ALL);
#采集小区图片
//数据库配置
$db_server = 'localhost';
$db_user = 'root';
$db_psw = '';
$db_name = 'zgjw';
$link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
mysql_select_db($db_name, $link) or die('select db error');
mysql_query("SET NAMES UTF8");
set_time_limit(0);
#排除的图片地址
$no_images = array(
    'http://img.soufun.com/house/detail/images/shipin0.gif',
    'http://img.soufun.com/house/images/beian_min.gif',
    'http://pages.anjukestatic.com/img/global/nopic2_385x240.gif',
);
$sql = "select * from tbl_content where urltext !='' and (urltext like '%anjuke%') and (collective_drawing is null or collective_drawing='') order by id asc";
$r = mysql_query($sql);
$preg_url = "/http:\/\/\d+\.sousdffun\.com\//is";
while ($row = mysql_fetch_assoc($r)) {
    $url = $row['urltext'];
    if (strpos($url, 'anjuke') !== false) {
        echo $url . "\n";
        collectImageFromAnjuke($row);
    }
//    else if (strpos($url, 'soufun') !== false) {
////        collectImageFromSouFun($row);
//    }
}

function collectImageFromAnjuke($row) {
    global $no_images;
    $url = $row['urltext'];
    $anjuke_arr = explode("/", $url);
    $image_url = $anjuke_arr[0] . '//' . $anjuke_arr[2] . "/community/photos2/b/" . $anjuke_arr[5];
    $content = file_get_contents($image_url);
    $preg = "/<img\s+[^>]*src=\"(http:\/\/pic1\.ajkimg\.com\/display\/.*?)\"\s+[^>]*>/is";
    preg_match($preg, $content, $martch);
    if (isset($martch[1])) {
        donloadImage($martch[1], $row);
    }
}

function collectImageFromSouFun($row) {
    $url = $row['urltext'];
    $content = file_get_contents("compress.zlib://" . $url);
    $preg = "/<img\s+[^>]*src=\"(http:\/\/imgs\.soufun\.com\/house\/\d+_*\d+\/.*?)\"\s+[^>]*>/is";
    $preg1 = "/<img\s+[^>]*src=\"(http:\/\/img1\.soufunimg\.com\/agents\/.*?)\"[^>]*>/is";
    $preg2 = "/<img[^>]*src=\"(http:\/\/img\.soufun\.com\/house\/\d+_\d+\/.*?)\"[^>]*>/is";
    $preg4 = "/<img[^>]*src=\"(http:\/\/img\.soufun\.com\/house\/\d+_*\d+\/.*?)\"[^>]*>/is";

    $token = substr(md5(uniqid(rand(), true)), 0, 10);
    preg_match($preg, $content, $martch);
    preg_match($preg1, $content, $martch1);
    preg_match($preg2, $content, $martch2);
    if (isset($martch[1])) {
        donloadImage($martch[1], $row);
        return;
    } else if (isset($martch1[1])) {
        donloadImage($martch1[1], $row);
        return;
    } else if (isset($martch2[1])) {
        donloadImage($martch2[1], $row);
        return;
    }
}

function donloadImage($url, $row) {
    global $no_images;
    $file_name = substr(md5(uniqid(rand(), true)), 0, 10) . time();
    if (in_array($url, $no_images)) {
        return;
    }
    echo '.';
    flush();
    $ext = strrchr(strtolower($url), ".");
    if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png") {
        return;
    }
    file_put_contents("d:/area_image/original/" . $file_name . $ext, file_get_contents($url));
    $file_path = "d:/area_image/original/" . $file_name . $ext;
    UtilsMiniImg::MiniImg($file_path, '100*100', "d:/area_image/100x100/" . $file_name . $ext);
    #插入数据库
    $sql = "insert into tbl_content_image (`tbl_content_id`,`image_type`,`image_token`) value (" . $row['id'] . ",1,'" . $file_name . $ext . "')";
    mysql_query($sql);
    $up_sql = "update tbl_content set collective_drawing='" . $file_name . $ext . "' where id=" . $row['id'];
    mysql_query($up_sql);
}

