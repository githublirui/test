<?php

defined('LIB') or define('LIB', dirname(__FILE__));
defined('CODE_BASE2') or define('CODE_BASE2', LIB . '/code_base2');

class DbConfig {

    public static $local = array(
        'host' => TEST_DBHOST,
        'username' => TEST_DBUSER,
        'password' => TEST_DBPASS,
        'port' => TEST_DBPORT,
    );

}
