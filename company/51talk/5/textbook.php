<?php

/**
 * 
 * 采集教材数据
 */
$base = new BaseModel();
$handle = $base->getDbHandler('lrtest');

//username and password of account
$username = '18911228229';
$password = '123456';

//set the directory for the cookie using defined document root var
$dir = $GLOBALS['now_path'] . "/ctemp";
//build a unique path with every request to store 
//the info per user with custom func. 
//$path = build_unique_path($dir);
$path = $dir;

//login form action url
$url = "http://ucenter.17zuoye.com/j_spring_security_check";
$postinfo = "_a_loginForm=立即登录&j_username=" . $username . "&j_password=" . $password;

$cookie_file_path = $path . "/cookie.txt";


$header[] = "Content-Type:application/x-www-form-urlencoded";
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

//set the cookie the site has for certain features, this is optional
curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
$ret = curl_exec($ch);
$header = curl_getinfo($ch);
var_dump($ret);
var_dump($header);
die;


if (curl_error($ch)) {
    echo curl_error($ch);
    die;
}

$parse_url = parse_url($header['url']);
list($key, $key_value) = explode("=", $parse_url["query"]);
$postinfo = "dataKey=''&j_userType=STUDENT&j_key=" . $key_value;
$login_url = "http://ucenter.17zuoye.com/j_spring_security_check";

//page with the content I want to grab
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
//do stuff with the info with DomDocument() etc
$html = curl_exec($ch);
curl_close($ch);
var_dump($html);
die;
