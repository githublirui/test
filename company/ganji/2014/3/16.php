<?php

$arr = array('a', 'b', 'c');
//$arrr = array_filter($arr, '');


$sa = '{"dayWeather":[{"temp":"19\u2103\uff5e6\u2103","temp_detail":["19","6"],"weather":"\u591a\u4e91\u8f6c\u6674","icons":["007","112"],"wind_d":"\u5317\u98ce4\uff5e5\u7ea7","wind_force":""},{"temp":"15\u2103\uff5e6\u2103","temp_detail":["15","6"],"weather":"\u9634\u8f6c\u6674","icons":["007","119"],"wind_d":"\u5fae\u98ce","wind_force":""},{"temp":"12\u2103\uff5e5\u2103","temp_detail":["12","5"],"weather":"\u9634\u8f6c\u6674","icons":["019","112"],"wind_d":"\u5317\u98ce4\uff5e5\u7ea7","wind_force":""},{"temp":"14\u2103\uff5e3\u2103","temp_detail":["14","3"],"weather":"\u6674","icons":["012","112"],"wind_d":"\u5317\u98ce3\uff5e4\u7ea7","wind_force":""},{"temp":"16\u2103\uff5e2\u2103","temp_detail":["16","2"],"weather":"\u6674","icons":["012","112"],"wind_d":"\u5fae\u98ce","wind_force":""},{"temp":"18\u2103\uff5e4\u2103","temp_detail":["18","4"],"weather":"\u6674","icons":["012","112"],"wind_d":"\u5fae\u98ce","wind_force":""}]}';
var_dump(json_decode($sa),true);
?>
