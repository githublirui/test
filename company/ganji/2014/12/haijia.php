<?php

//海淀驾校模拟curl
$url = 'http://haijia.bjxueche.net/Han/ServiceBooking.asmx/GetCars';
$login_url = 'http://haijia.bjxueche.net/login.aspx';
$url = 'http://ganji.local/2014/12/6_curlRes.php';
$login_url = 'http://ganji.local/2014/12/6_curlRes.php';
//登录
$post_fields['txtUserName'] = '340826';
$post_fields['txtPassword'] = 'aaa';
//POST数据，获取COOKIE,cookie文件放在网站的temp目录下
$cookie_file = tempnam('F:/kuaipan/test/company/ganji/2014/12/ba', 'cookie');
//$ch = curl_init($login_url);
//curl_setopt($ch, CURLOPT_HEADER, 0);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
//$result = curl_exec($ch);
//curl_close($ch);
//var_dump($result);die;
$post_fields = '{"yyrq":"20141213","yysd":"15","xllxID":"2","pageSize":35,"pageNum":1}';
$header = array(
    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Encoding:	gzip, deflate',
    'Accept-Language:	zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3',
    'Cache-Control:	no-cache',
    'Connection:	keep-alive',
    'Content-Length:	'.  strlen($post_fields),
    'Content-Type:	application/json; charset=utf-8',
//    'Host:	haijia.bjxueche.net',
    'Pragma:	no-cache',
    'Referer:	http://haijia.bjxueche.net/ych2.aspx',
    'User-Agent:	Mozilla/5.0 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0',
    'X-Requested-With:	XMLHttpRequest',
);
var_dump($header);die;
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);  //生成cookie文件
// curl_setopt($ch, CURLOPT_NOBODY, true);
$result = curl_exec($ch);
$info = curl_getinfo($ch);
var_dump($result);
die;
