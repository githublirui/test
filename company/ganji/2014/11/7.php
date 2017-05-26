<?php

/**
 * 版本号比较，A > B 返回1， A < B 返回-1， 相同返回0
 */
function versionCompare($versionA, $versionB) {
    //以点号拆分
    $aArr = explode('.', $versionA);
    $bArr = explode('.', $versionB);

    $flag = 0;
    //从左到右以数字定大小，数字相同，位数多为大
    foreach ($aArr as $key => $item) {
        if (!isset($bArr[$key]) || (int) $item > $bArr[$key]) {
            return 1;
        }
        if ((int) $item < $bArr[$key]) {
            return -1;
        }
    }
    if (count($bArr) > count($aArr)) {
        return -1;
    }
    return 0;
}

$arr = array('5.10.0', '6.0.0', '5.3.0', '6.5.0', '5.8.0', '5.2.0', '5.20.0');

// 快速排序
function versionrRSort($versions) {
    $len = count($versions);
    if ($len <= 1) {
        return $versions;
    }
    $key = $versions[0];
    $left_arr = array();
    $right_arr = array();
    for ($i = 1; $i < $len; $i++) {
        if (versionCompare($versions[$i], $key) >= 0) {
            $leftArr[] = $versions[$i];
        } else {
            $rightArr[] = $versions[$i];
        }
    }
    $leftArr = versionrRSort($leftArr);
    $rightArr = versionrRSort($rightArr);
    return array_merge($leftArr, array($key), $rightArr);
}

//冒泡排序
var_dump(versionrRSort($arr));
?>
