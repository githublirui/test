<?php
require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once dirname(__FILE__) . '/../MysqlInsertBuffer.class.php';
require_once GANJI_CONF . '/DBConfig.class.php';

$buffer = new MysqlInsertBuffer(array(
    'server' => DBConfig::$SERVER_WANTED_BACKEND_MASTER,
    'database' => 'wanted_premier',
    'table' => 'wanted_reserve_refresh_log',
    'fields' => array(
        'refresh_id' => '%d',
        'user_id' => '%d',
        'puid' => '%d',
        'refresh_type' => '%d',
        'status' => '%d',
        'refresh_time' => '%d',
        'create_time' => '%d'
    ),
    'size' => 3,
    'log' => true
));
$i = 10;
while ($i--) {
    $buffer->push(array(
        'refresh_id' => 0,
        'user_id' => 31669376,
        'puid' => 92361400,
        'refresh_type' => 4,
        'status' => 2,
        'refresh_time' => time(),
        'create_time' => time()
    ));
}