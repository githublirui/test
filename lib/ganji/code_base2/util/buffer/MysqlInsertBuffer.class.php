<?php
/**
 * @brief     Mysql插入缓冲
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 * @author    zhangshenjia <zhangshenjia@ganji.com>
 * @since     2014-3-27
 * @example
 * $buffer = new MysqlInsertBuffer(array(
 *     'server' => DBConfig::WANTED_SERVER_MASTER,
 *     'database' => 'wanted'
 *     'table' => 'post',
 *     'fields' => array(
 *         'puid' => '%d',
 *         'title' => '%s'
 *     ),
 *     'size' => 1000,
 *     'log' => true
 * ));
 * $buffer->push(array('puid' => 12345, 'title' => 'abcd'));
 * $buffer->push(array('puid' => 12346, 'title' => 'abcde'));
 * ...
 * $buffer->flush();
 */
require_once dirname(__FILE__) . '/Buffer.class.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once CODE_BASE2 . '/util/datetime/Timer.class.php';

class MysqlInsertBuffer extends Buffer {

    /**
     * 必须提供的配置项
     * @var array
     */
    private static $_REQUIRED_CONFIGS = array(
        'server',    // DBConfig
        'database',  // 库名
        'table',     // 表名
        'fields'     // 字段列表（key为字段名，value为sprintf的格式化串）
    );

    /**
     * 默认的配置项
     * @var array
     */
    private static $_DEFAULT_CONFIGS = array(
        'log' => false,    // 是否输出进度日志
        'size' => 1000     // 缓冲区尺寸
    );

    /**
     * 数据库句柄
     * @var mysql
     */
    private $_DB;

    /**
     * 计时器
     * @var Timer
     */
    private $_timer;

    /**
     * 构造方法
     * @param array $config
     * @return MysqlInsertBuffer
     */
    public function __construct($config = array()) {

        // 检查必须的配置
        foreach (self::$_REQUIRED_CONFIGS as $name) {
            if (!isset($config[$name])) {
                echo 'MysqlInsertBuffer: config [ ' . $name . ' ] is required.' . PHP_EOL;
                exit();
            }
        }
        parent::__construct($config);

        $this->config['fieldList'] = $this->_generateFieldList($config);
        $this->config['valueTemplate'] = $this->_generateValueTemplate($config);

        // 初始化计时器
        $this->_timer = new Timer(false);
    }

    /**
     * 析构释放资源
     */
    public function __destruct() {
        if (!$this->_DB) return;

        //防止多个model使用连接相同库时在一个作用域中被多次释放
        if (@$this->_DB->ping()) {
            DBMysqlNamespace::releaseDBHandle($this->_DB);
        }
        unset($this->_DB);
    }

    /**
     * 执行批量插入操作
     * @return void
     */
    public function flush() {

        $this->_timer->start();

        // 获得格式化后的数据
        $records = $this->_formatRecords();
        if (empty($records)) return;

        // 插入成功，清空缓冲区
        if ($this->_flushToDB($records)) {
            $this->_log(count($records) . ' inserted.');
            $this->clear();
        }
        // 插入失败，输出日志
        else {
            $this->_log('insert failed!');
        }

        $this->_timer->stop();
    }

    /**
     * 获得耗时统计
     * @param string $format
     * @return string
     */
    public function getTimeCost($format = 'ms') {
        return $this->_timer->format($format);
    }

    /**
     * 获取DB handle
     * @return mysql
     */
    private function _getDB() {
        if (!isset($this->_DB)) {
            $this->_DB = DBMysqlNamespace::createDBHandle($this->config['server'], $this->config['database']);
        }
        return $this->_DB;
    }

    /**
     * 格式化数据（确保字段顺序）
     * @param array $record
     * @return array
     */
    private function _renderRecord(&$record) {
        $rendered = array();
        foreach (array_keys($this->config['fields']) as $field) {
            $rendered[] = $record[$field];
        }
        return $rendered;
    }

    /**
     * 构建字段名列表
     * @param array $config
     * @return string
     */
    private function _generateFieldList($config) {
        $fields = array();
        foreach (array_keys($config['fields']) as $field) {
            $fields[] = '`' . $field . '`';
        }
        return '(' . implode(', ', $fields) . ')';
    }

    /**
     * 构建字段格式模板
     * @param array $config
     * @return string
     */
    private function _generateValueTemplate($config) {
        $formats = array();
        foreach ($config['fields'] as $name => $format) {
            $formats[] = ($format == '%s') ? "'%s'" : $format;
        }
        return '(' . implode(', ', $formats) . ')';
    }

    /**
     * 获得格式化后的数据
     * @return array
     */
    private function _formatRecords() {
        $records = array();
        foreach ($this->_buffer as $record) {
            $records[] = call_user_func_array('sprintf', array_merge(
                array($this->config['valueTemplate']),
                $this->_renderRecord($record)
            ));
        }
        return $records;
    }

    /**
     * 将数据写入DB中
     * @param array $records
     * @return boolean
     */
    private function _flushToDB($records) {
        $sql = sprintf("insert into `%s` %s values %s",
            $this->config['table'],
            $this->config['fieldList'],
            implode(', ', $records)
        );
        return DBMysqlNamespace::execute($this->_getDB(), $sql);
    }

    /**
     * 输出日志
     * @param string $message
     * @return void
     */
    private function _log($message) {
        if ($this->config['log']) {
            echo '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        }
    }
}