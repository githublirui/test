<?php
/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /code_base2/util/queue/QueueConnection.class.php
 * @author        caifeng@ganji.com
 * @date          2011-05-20
 *
 * 访问Q4M的封装对象
 */
require_once (dirname ( __FILE__ ) . "/../db/DBMysqlNamespace.class.php");

/**
 * @brief 队列连接对象
 */
class QueueConnection {

    private $conn = false;

    protected $queueName = "";
    protected $dbName = "";

    /**
     * @brief 初始化队列对象
     * @param array $array_config 队列的数据库配置
     * @param string $db_name 库名
     * @param string $queue_name 队列名
     */
    function __construct($array_config, $db_name, $queue_name) {
        $this->queueName = $queue_name;
        $this->dbName = $db_name;

        if (! empty ( $array_config )) {
            foreach( $array_config as $config ) {
                 $this->conn = DBMysqlNamespace::createDBHandle ( $config, $db_name );
                 if( $this->conn !== false )
                    break;
            }
        }
    }

    /**
     * @breif 返回数据库句柄
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * @brief 插入一条记录到队列
     * @param array $array_data 数据字典，包含要写入的所有字段
     * @return True 成功，False 失败
     */
    public function enqueue($array_data) {
        if (! $this->conn)
            return false;

        $sql = $this->buildInsertSql ( $array_data );
        if ($sql === false)
            return $sql;

        return DBMysqlNamespace::execute ( $this->conn, $sql );
    }

    /**
     * @brief 插入一条记录到队列
     * @param int $timeout 超时时间，缺省值为mysql超时时间
     * @param bool $rm 出队列的同时，是否执行删除该队列的操作
     * @return array 返回一条数据库记录
     */
    public function dequeue($timeout = false, $rm = false) {
        if (!$this->conn)
            return false;

        if ($timeout === false)
            $sql = "select * from {$this->dbName}.{$this->queueName} where queue_wait(\"{$this->dbName}.{$this->queueName}\")";
        else {
            $timeout = ( int ) $timeout;
            $sql = "select * from {$this->dbName}.{$this->queueName} where queue_wait(\"{$this->dbName}.{$this->queueName}\", $timeout)";
        }
        $data = DBMysqlNamespace::queryFirst ( $this->conn, $sql );
        if($rm && $data) {
            $this->_rmDequeue();
        }
        return $data;
    }

    /**
     * Q4M清空队列
     */
    public function clearQ4M() {
        if (!$this->conn) {
            return false;
        }
        $this->_rmDequeue();
        $sql = "delete from {$this->dbName}.{$this->queueName}";
        $ret = DBMysqlNamespace::execute ( $this->conn, $sql );
        return $ret;
    }

    /**
     * Q4M获取当前队列中元素的个数
     * @return int 当前队列中元素的个数
     */
    public function countQ4M() {
        if (!$this->conn) {
            return false;
        }
        $sql = "select count(*) as c from {$this->dbName}.{$this->queueName}";
        $data = DBMysqlNamespace::queryFirst ( $this->conn, $sql );
        return intval($data['c']);
    }

    public function buildInsertSql($array_data) {
        $keys = $valuds = array();
        foreach ($array_data as $key => $val) {
            $keys[] = "`{$key}`";
            $values[] = "'" . addslashes($val) . "'";
        }
        $sql = sprintf("insert into %s (%s) values(%s);",
                        $this->queueName,
                        implode(", ", $keys),
                        implode(", ", $values)
                );

        return $sql;
    }

    /**
     * @brief 主动删除已出队消息
     * @return boolean
     */
    public function _rmDequeue() {
        return DBMysqlNamespace::queryFirst($this->conn, "select queue_end()");
    }
}

