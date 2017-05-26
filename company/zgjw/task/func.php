<?php

/**
 * 编码修改,处理中文乱码问题 
 * @param str or array
 */
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

function curl_get($url, $gzip = false) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    if ($gzip)
        curl_setopt($curl, CURLOPT_ENCODING, "gzip"); // 关键在这里
    $content = curl_exec($curl);
    curl_close($curl);
    return $content;
}

function v($data) {
    var_dump($data);
    die;
}
function curl_file_get_contents($durl) {
    $ch = curl_init($durl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    return curl_exec($ch);
}