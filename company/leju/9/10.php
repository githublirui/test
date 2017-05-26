<?php

//$pattern = "/\\u5e10\\u53f7/is";
$pattern = "/帐/is";
$str = "账号12345密码6789112";
//$str = json_encode("账号12345密码6789");
//var_dump($str);
//die;
//preg_match_all($pattern, $str, $matches);
//var_dump($matches);
$row['partnerkey'] = "你好你是大佛大厦内发生地方撒旦法斯蒂芬阿斯蒂芬爱的色放阿苏大发送到发是";
$len = strlen($row['partnerkey']) / 2;
$row['partnerkey_private'] = substr_replace($row['partnerkey'], str_repeat('*', $len), floor(($len) / 2), $len);

var_dump($row['partnerkey_private']);
die;
