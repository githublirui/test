<?php
/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/client/http/HttpRequet.php
 * @create        [2014-05-07]赵卫国
 * @lastupdate    [2014-05-07]赵卫国
 * @other
 *
 *
 * @brief 把SF_Exception类进行封装，这儿的Exception是致命错误，不需要被重试
 */



/**
 * Class SF_FatalException
 * @brief: finagle-php的致命错误都继承自些类
 *
 */
class SF_FatalException extends SF_Exception {

    /**
     * @param string $message 异常信息
     * @param int $code  异常代码
     * @param Exception $previous
     */
    public function __construct($message, $code=0, Exception $previous = null) {
//        parent::__construct("[SF_FatalException]".$message, $code, $previous);
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function log() {
    }

}


/**
 * Class SF_NoEffectiveRemoteServiceException
 * @brief: 没有找到有效的远程server
 */
class SF_NoEffectiveRemoteServiceException extends  SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code);
    }

    public  function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
/**
 * Class SF_NoRemoteServiceException
 * @brief: 没有找到正确$all_states[$serviceName]，即它为null或非object
 */
class SF_NoRemoteServiceException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code);
    }

    public  function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

/**
 * Class SF_NodeGetWeightException
 * @brief: 权重轮询调度算法获得当时最大weight时没有找到正确weight
 */
class SF_NodeGetWeightException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code);
    }

    public  function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

/**
 * Class SF_HttpStatusException
 * @brief: http请求时，返回值为非200时抛出的异常
 */
class SF_HttpStatusException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code);
    }

    public  function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}


/**
 * Class SF_HttpInvalidException
 * @brief: http请求时，返回数据格式不正确时抛出的异常
 */
class SF_HttpInvalidException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code);
    }

    public  function __toString() {
        return parent::__toString();
    }

}



/**
 * Class SF_NoSuchHttpClientException
 * @brief: 没有此http请求类型，或此类型还没有实现
 * @other: 目前只支持SF_HttpRequest::HTTP_CLIENT_TYPE_SOCKET
 *      未来会支持SF_HttpRequest::HTTP_CLIENT_TYPE_CURL
 */
class SF_NoSuchHttpClientException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }

}

/**
 * Class SF_NoSuchHttpClientException
 * @brief: 关闭远程远程client时报错
 */
class SF_CloseRemoteClientException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }

}



/**
 * Class SF_ServiceLocationClientException
 * @brief: 连接注册中心服务时报错
 */
class SF_ServiceLocationClientException extends SF_FatalException {

    public function __construct($message, $code=0, Exception $previous = null) {
        parent::__construct("[". get_class($this) ."]".$message, $code, $previous);
    }

    public function __toString() {
        return parent::__toString();
    }
}




