<?php

//include 'simple_html_dom.php';
//ini_set("display_errors", 1);
//error_reporting(E_ALL);
$base_url = '/anjuke'; //定义url访问地址

@session_start();
/**
 * 获取所有省 
 */
if (!function_exists('getAllProvinces')) {

    function getAllProvinces() {
        $result = array();
        $all_provinces_r = mysql_query('select * from province');
        while ($row = mysql_fetch_assoc($all_provinces_r)) {
            $result[] = ($row);
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
        $city_r = mysql_query("select * from city where id=" . $city_id);
        $city_r_a = mysql_fetch_assoc($city_r);
        $city_code = $city_r_a['code'];
        $all_areas_r = mysql_query('select * from area where citycode=' . $city_code);
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
        $area_r = mysql_query("select * from area where id=" . $id);
        if ($area_r) {
            return mysql_fetch_assoc($area_r);
        } else {
            return FALSE;
        }
    }

}

/**
 * 编码修改,处理中文乱码问题 
 * @param str or array
 */
function GbkToUtf8($str_arr) {
    if (is_array($str_arr)) {
        foreach ($str_arr as $key => $value) {
            if (is_array($value)) {
                $str_arr[$key] = GbkToUtf8($value);
            } else {
                $str_arr[$key] = iconv("GBK", "UTF-8//IGNORE", $value);
            }
        }
    } else {
        $str_arr = iconv("GBK", "UTF-8//IGNORE", $str_arr);
    }
    return $str_arr;
}

function Utf8ToGbk($str_arr) {
    if (is_array($str_arr)) {
        foreach ($str_arr as $key => $value) {
            if (is_array($value)) {
                $str_arr[$key] = Utf8ToGbk($value);
            } else {
                $str_arr[$key] = iconv("UTF-8", "GBK", $value);
            }
        }
    } else {
        $str_arr = iconv("UTF-8", "GBK", $str_arr);
    }
    return $str_arr;
}

?>