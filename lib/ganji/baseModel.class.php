<?php

require_once dirname(__FILE__) . '/config.class.php';
require_once(CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php');
require_once CODE_BASE2 . '/app/base/SqlBuilderNamespace.class.php';
require_once(CODE_BASE2 . '/util/cache/CacheNamespace.class.php');

class BaseModel {

    protected $dbHandler;
    protected $memcacheHandler;

    public function __construct() {
        $this->dbHandler = false;
        $this->memcacheHandler = false;
    }

    public function getDbHandler($DB) {
        $this->dbHandler = DBMysqlNamespace::createDBHandle(DbConfig::$local, $DB, DBConstNamespace::ENCODING_UTF8);
        if (FALSE === $this->dbHandler) {
            $error = sprintf('TIME=%s FILE=%s LINE=%s MESSAGE=%s', date('Y-m-d H:i:s'), __FILE__, __LINE__, 'db handler create fail');
            var_dump($error);
            die;
        }
        return $this->dbHandler;
    }

    public function getMcHandler() {
        if ($this->memcacheHandler == false) {
            $this->memcacheHandler = CacheNamespace::createCache(CacheNamespace::MODE_MEMCACHE, MemcacheConfig::$GROUP_MOBILE);
            if (!$this->memcacheHandler) {
                Logger::logError(sprintf('MEMCACHE TIME=%s FILE=%s LINE=%s MESSAGE=%s', date('Y-m-d H:i:s'), __FILE__, __LINE__, 'memcache handler create fail'), 'moblie');
            }
        }
        return $this->memcacheHandler;
    }

}
