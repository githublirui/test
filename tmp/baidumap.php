<?php
include 'Utils.class.php';
$ip = Utils::fetch_alt_ip();
$url = "http://api.map.baidu.com/location/ip?ak=17ea7601fa941b3cfbac9497a91c0fe8&ip=106.3.78.224&&coor=bd09ll";
$content = json_decode(file_get_contents($url));
$content_x = $content->content->point->x;
$content_y = $content->content->point->y;
var_dump($content_x);
var_dump($content_y);
die;


//$x = '116.39564504';
//$y = '39.92998578';
//116.313056, 39.982235
//116.31329, 39.983092
//http://api.map.baidu.com/place/search?query=宏状元&location=39.92998578,116.39564504&radius=500&output=html&src=yourCompanyName|yourAppName