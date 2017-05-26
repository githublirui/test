<?php
/**
 * @brief     Mysql事务
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 * @author    zhangshenjia <zhangshenjia@ganji.com>
 * @since     2014-5-28
 * @example
 *
 * $handle = DBMysqlNamespace::createDBHandle(DBConfig::$SERVER_WANTED_BACKEND_MASTER, 'wanted_premier');
 * $transaction = DBTransaction::beginTransaction($handle);
 * try {
 *     ....
 *     $created = DBMysqlNamespace::execute($handle, $sql);
 *     if (!$created) throw new Expception('创建失败');
 *     .....
 *     $transaction->commit();
 * } catch (Exception $e) {
 *     Logger::logError($e->getMessage());
 *     $transaction->rollback();
 * }
 */
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';

class DBTransaction {

    /**
     * 事务相关的SQL语句
     * @var string
     */
    const SQL_BEGIN = 'BEGIN';
    const SQL_COMMIT = 'COMMIT';
    const SQL_ROLLBACK = 'ROLLBACK';

    /**
     * 数据库主库句柄
     * @var mysql
     */
    private $_handle;

    /**
     * 事务是否结束
     * @var boolean
     */
    private $_finish = false;

    /**
     * 构造方法（自动开启事务）
     * @param mysql $handle
     */
    public function __construct($handle) {
        $this->_handle = $handle;
        $this->_runSQL(self::SQL_BEGIN);
    }

    /**
     * 析构方法（如果没有提交/回滚，则自动回滚）
     */
    public function __destruct() {
        if (!$this->_finish) {
            $this->rollback();
        }
    }

    /**
     * 提交事务
     * @return mixed
     */
    public function commit() {
        $this->_finish = true;
        return $this->_runSQL(self::SQL_COMMIT);
    }

    /**
     * 回滚事务
     * @return mixed
     */
    public function rollback() {
        $this->_finish = true;
        return $this->_runSQL(self::SQL_ROLLBACK);
    }

    /**
     * 运行SQL
     * @param string $sql
     * @return mixed
     */
    private function _runSQL($sql) {
        return DBMysqlNamespace::execute($this->_handle, $sql);
    }
}