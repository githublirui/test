<?php

require 'conn.php';
require 'function.php';

$ac = $_GET['ac'];


if ($ac == 'a3') {
    //地址联动
    include ('template/a3.php');
} else if ($ac == 'collect') {
    //小区采集
    include ('template/collect.php');
} else if ($ac == 'al') {
    //刷新添加记录
    include ('template/al.php');
} else if ($ac == 'a2') {
    include ('template/a2.php');
} else if ($ac == 'addarea') {
    $province = getProvinceByid($_POST['province']);
    $city = getCityByid($_POST['city']);
    $area_name = trim($_POST['area_name']);
    $area_code = trim($_POST['area_code']);

    //判断区县是会否存在
    $sql_area_exist = "select count(*) as num from tbl_areas where areaid=" . $area_code;
    $r_area_exist = mysql_query($sql_area_exist);
    $arr_area_exist = mysql_fetch_assoc($r_area_exist);
    if ($arr_area_exist['num'] <= 0) {
        $sql = "insert into tbl_areas (`areaid`,`area`,`cityid`) values ('" . $area_code . "','" . $area_name . "','" . $city['cityid'] . "')";
        mysql_query($sql);
    } else {
        echo 'exist';
        die;
    }
} else {
    echo '404';
    die;
}