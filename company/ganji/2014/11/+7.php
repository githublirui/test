<?php

//冒泡排序
#算法思想: 两层循环，内层一一替换外层顺序
function maopao($a) {
    for ($i = 0; $i < count($a) - 1; $i++) {
        for ($j = $i + 1; $j < count($a); $j++) {
            if ($a[$i] < $a[$j]) {
                $tmp = $a[$i];
                $a[$i] = $a[$j];
                $a[$j] = $tmp;
            }
        }
    }
}

// 快速排序
function kuaisu($versions) {
    $len = count($versions);
    if ($len <= 1) {
        return $versions;
    }
    $key = $versions[0];
    $leftArr = array();
    $rightArr = array();
    for ($i = 1; $i < $len; $i++) {
        if ($versions[$i] >= $key) {
            $leftArr[] = $versions[$i];
        } else {
            $rightArr[] = $versions[$i];
        }
    }
    $leftArr = kuaisu($leftArr);
    $rightArr = kuaisu($rightArr);
    return array_merge($leftArr, array($key), $rightArr);
}

?>
