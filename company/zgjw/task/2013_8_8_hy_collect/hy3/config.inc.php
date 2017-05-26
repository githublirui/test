<?php


//*****************************************************
$dbhost = "localhost";
$dbuser = 'root';
$dbpw = '';
$dbname = 'zgjw';
$pconnect = 0;
$dbcharset = 'GBK';
$tablepre = '';
$cookiepre = 'uix_';   // cookie 前缀
$cookiedomain = '';    // cookie 作用域
$cookiepath = '/';   // cookie 作用路径
$dev = false; #定义开发模式
//后台登录允许ip
$allow_ips = array(
    '127.0.0.1',
    '106.3.78.220',
    '192.168.2.1 - 192.168.2.255',
    '192.168.1.1 - 192.168.1.255',
    '106.3.78.224 - 106.3.78.231',
);
if ($dev) {
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", 0);
    error_reporting(0);
}
try {
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpw);
} catch (PDOException $e) {
    echo $e->getMessage();
}
$dbh->query("set names gbk");

$url_base = "http";
if (isset($_SERVER['HTTPS'])) {
    $url_base .= "s";
}
$url_base .= "://" . $_SERVER["SERVER_NAME"];
if (isset($_SERVER["SERVER_PORT"]) && !empty($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
    $url_base .= ":" . $_SERVER["SERVER_PORT"];
}

defined('ZGJW_APP_PATH') or define('ZGJW_APP_PATH', dirname(__FILE__)); #定义根目录,
defined('UC_CONNECT') or define('UC_CONNECT', 'mysql');
defined('UC_DBHOST') or define('UC_DBHOST', "$dbhost");
defined('UC_DBUSER') or define('UC_DBUSER', $dbuser);
defined('UC_DBPW') or define('UC_DBPW', $dbpw);
defined('UC_DBNAME') or define('UC_DBNAME', 'ucenter');
defined('UC_DBCHARSET') or define('UC_DBCHARSET', 'gbk');
defined('UC_DBTABLEPRE') or define('UC_DBTABLEPRE', '`ucenter`.uc_');
defined('UC_DBCONNECT') or define('UC_DBCONNECT', '0');
defined('UC_KEY') or define('UC_KEY', 'a1b2c3');
defined('UC_API') or define('UC_API', $url_base . "/ucter");
defined('UC_CHARSET') or define('UC_CHARSET', 'gbk');
defined('UC_IP') or define('UC_IP', '');
defined('UC_APPID') or define('UC_APPID', '4');
defined('UC_PPP') or define('UC_PPP', '20');

//*****************************************************
