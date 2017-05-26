<?php
$s = '{"weixin_house_id":"334","hid":"100023","city_code":"bj","check_data":"[{"type":3,"cat_type":"add","title":"","pic":"http:\/\/src.house.sina.com.cn\/imp\/imp\/deal\/64\/d8\/a\/12396be5391806ba5e1ff6dc3f8_p10_mk10.png","url":"","video_id":""}]","type":2}';

var_dump(json_decode($s),true);
die;
$a = array(
    array(
        "type" => "1",
        "title" => "1aaaa",
        "cat_type" => "二居室",
        "pic" => "http://www.pic.com",
        "url" => "1aaaa",
        "video_id" => "961494912731",
    ),
);

var_dump(json_encode($a));
die;
$c = 'lily4205 ..';

echo mb_convert_encoding('utf-8', 'GBK//TRANSLIT', $c);
?>
