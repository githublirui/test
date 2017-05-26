<?php

header('Content-type:text/html;charset=utf-8');

define('TEST_PATH', __DIR__); //test根目录
define('LIB_PATH', __DIR__ . "/lib"); //test根目录

include TEST_PATH . '/config.php'; //引入配置文件
include LIB_PATH . '/function.php'; //引入配置文件
include LIB_PATH . '/ganji/common.php';
include LIB_PATH . '/composer/vendor/autoload.php'; //引入composer

$base = new BaseModel();
$GLOBALS['test_handle'] = $base->getDbHandler('lrtest');

$base = new BaseModel();
$GLOBALS['db_handle'] = $base->getDbHandler('lrtest');

test_dispath();

//执行方式
//web  http://test.local/index.php?company/zgjw/anjuke/tianmao/index.php
//cli  php index.php company/zgjw/anjuke/tianmao/index.php

//数据库操作
//$base = new BaseModel();
//$handle = $base->getDbHandler('lrtest');
//$s = DBMysqlNamespace::fetch_count($handle, 'members_qx');
//$id = DBMysqlNamespace::insert($handle, 'members_qx', array('name' => '我是李睿'));
//DBMysqlNamespace::update($handle, 'members_qx', array('name' => '我是李睿1'), array('id' => 2885));