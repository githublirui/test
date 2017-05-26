<?php

/**
 * 数组移动位置
 */
$result = array();
$a = array('c' => 5, 'd' => 2, 'g' => 8, 's' => 1, 't' => 4);
foreach ($a as $k => $v) {
    if (!$result) {
        $result[$k] = $v;
    } else {
        foreach ($result as $k1 => $v1) {
            if ($v >= $v1) {
                unset($result[$k1]);
                $result[$k] = $v;
                $result[$k1] = $v1;
            } else {
                $result[$k] = $v;
            }
        }
    }
}
var_dump($result);
die;
?>
