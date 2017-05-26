<?php

/**
 *
 * 赶集网PHP类，编写编码规范样例
 *
 * @author lirui <lirui1@ganji.com>
 * @since  2014-2-25
 * @copyright Copyright (c) 2005-2014 GanJi Inc. (http://www.ganji.com)
 * @desc 用户练习，代码规范
 */
//require_once dirname(__FILE__) . '/bootstrap.php';
//require_once GANJI_CONF . '/DBConfig.class.php'; //文加名全部小写加下划线sample_test.php,如果是类文件，则按照首字母大写驼峰命名法命名，如DBConfig.class.php

class Sample {

    const MYCONST = null; //类常量全部大写，php中能用小写的全部用小写

    public $public = null;
    public static $STATIC = 1;
    protected $_protected = 2;
    protected static $_PROTECTED = 2;
    private $_private = null;
    private static $_PRIVATE = null;

    //所有类成员和类方法都保持一个空行
    public function __construct() {
        return 'new a obj';
    }

    /**
     * 获取名称
     * @param
     * @return $name string 名称
     */
    public function getName() {
        return 'lirui1';
    }

    /**
     * 获取年龄
     * @return string
     */
    public function getAge() {
        return '25';
    }

}
