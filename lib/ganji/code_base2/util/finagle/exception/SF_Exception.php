<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/exception/SF_Exception.php
 * @create        [2014-04-22]赵卫国   封装SF框架的所有exception
 * @update        [2014-05-07]赵卫国   根据业务需要拆分成两种类型: SF_Normal and SF_Fatal(单独分出两个文件)
 *
 * @other
 *
 * @brief 把Exception类进行封装，所有finagle-php的Exception都继承自此类
 */

require_once FINAGLE_BASE . "/exception/SF_FatalException.php";
require_once FINAGLE_BASE . "/exception/SF_NormalException.php";

/**
 * Class SF_Exception
 * @brief: 对Exception进行简单封装，此方法为剩余Exception类的父类，SF开头的Exeption都是此类的子类
 *
 */
class SF_Exception extends Exception {

    /**
     * @param string $message 异常信息
     * @param int $code  异常代码
     * @todo 是否弄一个(code=>exception)对应表，一个code对应一exception
     *          如: 3代表http类型错误, 30001 代表SF_HttpStatusException; 30002代表SF_HttpInvalidException
     * @param Exception $previous
     */
    public function __construct($message, $code=0, Exception $previous = null) {
        // @todo 做一些个性化的工作
        //parent::__construct($message."<==>".$previous, $code);
//        parent::__construct("[SF_Exception]".$message, $code);
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function log() {
        // @todo 写入日志，方便日后分析
    }

}



