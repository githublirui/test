<?php

include_once dirname(__FILE__) . '/../../lib/common.php';
$str = implode('.', range(1, 1000));
$timer = new Timer();
$count = count(explode('.', $str));
echo $timer->spent();
echo "<br/>";
echo $count;
?>
