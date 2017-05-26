<?php

//无限极分类 数组格式化
$categorys = array(
    1 => array(
        'id' => 1,
        'parentid' => 0,
    ),
    2 => array(
        'id' => 2,
        'parentid' => 0,
    ),
    3 => array(
        'id' => 3,
        'parentid' => 1,
    ),
    4 => array(
        'id' => 4,
        'parentid' => 1,
    ),
    5 => array(
        'id' => 5,
        'parentid' => 2,
    ),
    6 => array(
        'id' => 6,
        'parentid' => 2,
    ),
    7 => array(
        'id' => 7,
        'parentid' => 4,
    ),
);

function generateTree($items) {
    $tree = array();
    foreach ($items as $item) {
        if (isset($items[$item['parentid']])) {
            $items[$item['parentid']]['child'][] = &$items[$item['id']];
        } else {
            $tree[] = &$items[$item['id']];
        }
    }
    return $tree;
}

var_dump(generateTree($categorys));
die;

//json中文支持
function my_urlencode($arr) {
    $result = array();
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $result[$k] = my_urlencode($v);
            }
            $result[$k] = urlencode($v);
        }
    } else {
        $result = urlencode($arr);
        var_dump($result);
        die;
    }
    return $result;
}

function my_urldecode($arr, $decode = true) {
    $result = array();
    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $result[$k] = my_urldecode($v);
            }
            if ($decode) {
                $result[$k] = urldecode($v);
            } else {
                $result[$k] = urlencode($v);
            }
        }
    } else {
        if ($decode) {
            $result = urldecode($arr);
        } else {
            $result = urlencode($arr);
        }
    }
    return $result;
}

$arr = array('%E6%96%AF%E8%92%82%E8%8A%AC%E6%96%AF%E8%92%82%E8%8A%AC', 'sdfsd%E6%96%AF%E8%92%82%E8%8A%AC');
var_dump(my_urldecode($arr));
die;
