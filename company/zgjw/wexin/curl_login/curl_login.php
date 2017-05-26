<?php
#此为CURL模拟登录DZ
$discuz_url = 'https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN'; //论坛地址
//$login_url = $discuz_url . 'logging.php?action=login'; //登录页地址

$post_fields = array();
//以下两项不需要修改
$post_fields['f'] = 'json';
$post_fields['imgcode'] = '';
//用户名和密码，必须填写
$post_fields['username'] = 'zimumdm@163.com';
$post_fields['password'] = md5('mdm516516');


//POST数据，获取COOKIE,cookie文件放在网站的temp目录下
$cookie_file = tempnam('./temp', 'cookie');

$ch = curl_init($discuz_url);


curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).'/weixin.qq.pem');

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_exec($ch);

var_dump(curl_error($ch));

curl_close($ch);