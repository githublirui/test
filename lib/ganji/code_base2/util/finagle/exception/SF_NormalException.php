<?php
/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/client/http/HttpRequet.php
 * @create        [2014-05-07]赵卫国
 * @lastupdate    [2014-05-07]赵卫国
 * @other
 *
 *
 * @brief 把Exception类进行封装，这儿的Exception需要被重试
 */




/**
 * Class SF_FatalException
 * @brief: finagle-php的非致命错误都继承自些类，根据重试机制进行重试
 *
 */
class SF_NormalException extends SF_Exception {

    /**
     * @param string $message 异常信息
     * @param int $code  异常代码
     * @param Exception $previous
     */
    public function __construct($message, $code=0, Exception $previous = null) {
//        parent::__construct("[SF_NormalException]".$message, $code, $previous);
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function log() {
    }

}

/**
 * Class SF_SocketOpenException
 * @brief: 打开socket时抛出的异常
 */
class SF_SocketOpenException extends SF_NormalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }

}

/**
 * Class SF_SocketWriteException
 * @brief: 往socket写数据时抛出异常
 */
class SF_SocketWriteException extends SF_NormalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }

}


/**
 * Class SF_SocketTimeoutException
 * @brief: socket超时时抛出异常
 */
class SF_SocketTimeoutException extends SF_NormalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }

}


/**
 * Class SF_SocketTimeoutException
 * @brief: socket其他错误时抛出异常
 */
class SF_SocketException extends SF_NormalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }

}


