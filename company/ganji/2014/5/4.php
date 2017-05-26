<?php

//if (date('H') > '12') {
//    echo '过期';
//    die;
//}

include 'Curl.class.php';
$url1 = 'http://192.168.72.254/portal/logon.cgi';
$post1 = array(
    "PtUser" => 'lirui1', //用户名
    "Domain" => '@ganji.com',
    "PtPwd" => '649037629@qq.com', //密码
    "PtButton" => 'Logon',
    "selectTargetUrl" => 'i',
    "TargetUrl" => '',
);
$curl = new Curl();
$return = $curl->login($url1, $post1, 'post');
file_put_contents("./log.txt", $return);
preg_match_all('/\"(http\:\/\/.+)\"/is', $return, $match);
$locationUrl = $match[1][0];
var_dump($curl->get($locationUrl));
