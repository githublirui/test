<?php
/**
 * @brief     缓冲区基类
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 * @author    zhangshenjia <zhangshenjia@ganji.com>
 * @since     2014-3-27
 */

abstract class Buffer {

    /**
     * 默认缓冲区大小
     * @var int
     */
    const DEFAULT_SIZE = 1000;

    public $config = array();

    /**
     * 缓冲区
     * @var array
     */
    protected $_buffer = array();

    /**
     * 缓冲区配置
     * @var array
     */

    /**
     * 构造方法
     * @param array $config
     * @return Buffer
     */
    public function __construct($config = array()) {
        if (!isset($config['size'])) {
            $config['size'] = self::DEFAULT_SIZE;
        }
        $this->config = $config;
    }

    /**
     * 析构方法
     */
    public function __destruct() {
    }

    /**
     * 向缓冲区中写入内容
     * @param $item
     * @return void
     */
    public function push($item) {
        array_push($this->_buffer, $item);
        if (count($this->_buffer) >= $this->config['size']) {
            $this->flush();
        }
    }

    /**
     * 输出缓冲区（需要子类实现，并在其中调用$this->clear()）
     * @return void
     */
    abstract public function flush();

    /**
     * 清空缓冲区
     * @return void
     */
    protected function clear() {
        $this->_buffer = array();
    }
}