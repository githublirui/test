<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/client/http/SF_CurlHttpRequet.php
 * @create        [2014-04-28]赵卫国
 * @lastupdate    [2014-04-28]赵卫国
 *
 *
 * @brief 使用Curl形式封装发起Http请求
 */

require_once dirname(__FILE__) . '/../../exception/SF_Exception.php';

class SF_CurlHttpRequest
{

    const HTTP_VERSION_1_0 = 'HTTP/1.0';
    const HTTP_VERSION_1_1 = 'HTTP/1.1';
    const HTTP_METHOD_GET = "GET";
    const HTTP_METHOD_POST = "POST";

    const HTTP_USER_AGENT = "ganji.com API PHP5 Client 1.0 (non-curl)";
    const HTTP_ACCEPT = "*/*";
    const HTTP_CONTENT_TYPE = "application/x-www-form-urlencoded";

    //连接超时、连接重试参数
    const HTTP_CONNECT_TIMEOUT = 0;
    const HTTP_READ_TIMEOUT = 0;
    const HTTP_RETRY = 0;


    /**
     * @brief 带连接超时和重试机制的HTTP GET请求
     *
     * @param string $host  域名/ip
     * @param int $port 端口号(如80)
     * @param int $connectTimeout 连接超时(毫秒, 为0时无超时)
     * @param int $readTimeout 读取超时(毫秒, 为0无超时)
     * @param int $retry 重试次数(为0时不重试)
     * @param string $uri   资源
     * @param string $method http请求类型(POST/GET)
     * @param string $schema 请求schema(如http)
     * @param string $httpVer http版本
     * @param string $httpUserAgent
     * @param string $httpAccept
     * @param string $httpContentType
     */
    public function __construct($host,
                                $port,
                                $connectTimeout,
                                $readTimeout,
                                $uri,
                                $method,
                                $schema,
                                $httpVer,
                                $httpUserAgent,
                                $httpAccept,
                                $httpContentType) {
        $this->host = $host;
        $this->uri = $uri;

        $this->method = $method;


        if($schema == 'https') {
            $this->schema = 'ssl';
            $this->port = $port != 80 ? $port : 443;
            $this->hostname = 'ssl' . $host;
        } else {
            $this->schema = 'http';
            $this->port = $port;
            $this->hostname = $host;
        }
        $this->connectTimeout = $connectTimeout;
        $this->readTimeout = $readTimeout;



//        $this->url = "$schema://".$this->host . ':' . $this->port . $this->uri;  //url地址
    }   // end of function __construct/7



    /*
     * 执行http请求:
     *
     * 返回值:
     *  不同的exception代表不同的意思
     *
     */
    public function request() {
    // @todo 使用php自带curl方式进行封装

    }   // end of function request/0



    /**
     * @param $postStr
     *
     * @brief post类型请求，设定post内容[注: post类型请求调用]
     */
    public function setPostStr($postStr) {
        $this->postStr = $postStr;
    }

    /**
     * @param $httpAccept
     *
     * @brief 设定http接收类型
     *
     * @default: * /*
     */
    public function setAccept($httpAccept) {
        $this->httpAccept = $httpAccept;
    }

    /**
     * @param $httpUserAgent
     *
     * @brief 设定http请求的userAgent
     *
     * @default: ganji.com API PHP5 Client 1.0 (non-curl)
     */
    public function setUserAgent($httpUserAgent) {
        $this->httpUserAgent = $httpUserAgent;
    }

    /**
     * @param $httpContentType
     *
     * @brief 设定http请求的Content-type
     *
     * @default:
     *      application/x-www-form-urlencoded
     * @others:
     *      application/json
     */
    public function setContentType($httpContentType) {
        $this->httpContentType = $httpContentType;
    }



    /**
     * @brief 记录日志
     * @param string $msg
     * @param string $category
     */
    private static function _log($msg = '', $category = 'HttpRequest') {
        if (class_exists('Logger') && method_exists('Logger', 'logError')) {
            Logger::logError($msg, $category);
        }
    }


}