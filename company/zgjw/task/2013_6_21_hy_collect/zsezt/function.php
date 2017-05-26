<?php

include 'config.inc.php';

@session_start();
$link = mysql_connect($dbhost, $dbuser, $dbpw) or die('connect error');
mysql_select_db($dbname, $link) or die('select db error');
mysql_query("SET NAMES " . $dbcharset);
set_time_limit(0);

/**
 * 获取所有省 
 */
if (!function_exists('getAllProvinces')) {

    function getAllProvinces() {
        $result = array();
        $all_provinces_r = mysql_query('select * from province');
        while ($row = mysql_fetch_assoc($all_provinces_r)) {
            $result[] = $row;
        }
        return $result;
    }

}

/**
 * 通过省id获取市 
 */
if (!function_exists('getCitysByProId')) {

    function getCitysByProId($pro_id) {
        $result = array();
        $pro_r = mysql_query("select * from province where id=" . $pro_id);
        $pro_r_a = mysql_fetch_assoc($pro_r);
        $pro_code = $pro_r_a['code'];
        $all_citys_r = mysql_query('select * from city where provincecode=' . $pro_code);

        while ($row = mysql_fetch_assoc($all_citys_r)) {
            $result[] = $row;
        }
        return $result;
    }

}

/**
 * 通过市id获取区县 
 */
if (!function_exists('getAreasByCityId')) {

    function getAreasByCityId($city_id) {
        $result = array();
        $city_r = mysql_query("select * from tbl_cities where id=" . $city_id);
        $city_r_a = mysql_fetch_assoc($city_r);
        $city_code = $city_r_a['cityid'];
        $all_areas_r = mysql_query('select * from tbl_areas where cityid=' . $city_code);
        while ($row = mysql_fetch_assoc($all_areas_r)) {
            $result[] = $row;
        }
        return $result;
    }

}

/*
 * 通过id获取省
 */
if (!function_exists('getProvinceByid')) {

    function getProvinceByid($id) {
        $pro_r = mysql_query("select * from province where id=" . $id);
        if ($pro_r) {
            return mysql_fetch_assoc($pro_r);
        } else {
            return FALSE;
        }
    }

}

/*
 * 通过id获取市
 */
if (!function_exists('getCityByid')) {

    function getCityByid($id) {
        $city_r = mysql_query("select * from city where id=" . $id);
        if ($city_r) {
            return mysql_fetch_assoc($city_r);
        } else {
            return FALSE;
        }
    }

}

/*
 * 通过id获取区
 */
if (!function_exists('getAreaByid')) {

    function getAreaByid($id) {
        $area_r = mysql_query("select * from tbl_areas where id=" . $id);
        if ($area_r) {
            return mysql_fetch_assoc($area_r);
        } else {
            return FALSE;
        }
    }

}

/**
 * CURL采集
 * @param type $durl
 * @return type
 */
function curl_file_get_contents($durl) {
    $ch = curl_init($durl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    return curl_exec($ch);
}

?>