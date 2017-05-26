<?php
error_reporting(E_ALL);
set_time_limit(36000);


if(file_exists(dirname(dirname(__FILE__)) . "/autoload/loader.class.php")){
	include_once(dirname(dirname(__FILE__)) . "/autoload/loader.class.php");
}else{
	throw new Exception('autoload class is not exists');
}
spl_autoload_register(array('loader', 'loadClass'));

// 配置选项, 主机， 端口, redis的数据库
$conf = array(
    'host'      => '127.0.0.1',
    'port'      => 6379,
    'password'  => 'redis',
    'dbname'    => 1
);

$redis = RedisClient::getInstance($conf);
$flag = 'users';

// 数据库
$db_conf = array(
	'host' => 'localhost',
	'user' => 'root',
	'pass' => 'root',
	'name' => 'replication',
	'port' => '3306',
	'char' => 'utf8'
);
$db = Db::getInstance($db_conf);