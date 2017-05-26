<?php

/**
 * pagelet的基类
 * 负责加载配置文件
 * 负责查找骨架
 * 负责查找所有小的pagelet页面
 * 负责将数据传递给render类, 让render类去渲染所有的pagelet
 * @author zhanglei <zhanglei19881228@sina.com>
 * @date 2015-05-28 17:30
 */
abstract class Pagelet {

    const PREFIX = 'pagelet';

    // 配置文件内容, 只第一次加载
    private $_configs = array();
    // 是否是骨架
    protected $skeleton = false;
    // pagelet的名称
    protected $name = '';
    // pagelet的模板名称
    protected $tpl = '';
    // 骨架下所有的pagelets
    private $_children = array();
    // see $this->setValue() $this->getValue()
    private $_data = array();

    /**
     * 负责加载配置文件
     * 同时将pagelet加载到$this->_children中去
     */
    public function __construct() {
        $this->_configure();
        $this->createChildren();
    }

    /**
     * 引入配置文件
     */
    private function _configure() {
        if (empty($this->_configs)) {
            $configs = include_once(CONFIG . DS . 'config.php');
            // 如果是骨架, 并且在配置中存在, 则将配置文件有关$this->name的信息记录到$this->_configs中
            if ($this->getSkeleton()) {
                $this->_configs = $configs;
            } else {
                $this->_configs = array();
            }
        }
    }

    /**
     * 返回模板文件路径
     * @return type
     */
    public function getTemplate() {
        return $this->tpl;
    }

    /**
     * 将所有的pagelet放在$this->_children中去
     * @return type
     */
    public function createChildren() {
        if (!$this->getSkeleton()) {
            return array();
        }

        if ($this->name && !empty($this->_configs)) {
            $this->_children = isset($this->_configs[$this->name]) ? $this->_configs[$this->name] : array();
        }
    }

    /**
     * 返回所有的pagelet
     * @return type array()
     */
    public function getChildren() {
        return $this->_children;
    }

    /**
     * 通过名称, 得到pagelet类的实例化
     * @param type $name
     */
    public function getPagelet($name) {
        $classname = self::PREFIX . ucwords(strtolower($name));
        if (!class_exists($classname)) {
            throw new Exception(sprintf("%s类不存在", $classname));
        }
        return new $classname;
    }

    /**
     * 得到是否是骨架
     * @return type boolean
     */
    public function getSkeleton() {
        return $this->skeleton;
    }

    // 准备输出的数据
    abstract public function prepareData();

    public function setValue($name, $value) {
        $this->_data[$name] = $value;
    }

    public function getValue($name) {
        return isset($this->_data[$name]) ? $this->_data[$name] : '';
    }

}
