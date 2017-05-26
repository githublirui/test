<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tailor
 * Date: 14-3-28Time: 下午4:12
 * @lastupdate: 2014-05-05  赵卫国
 * @lastupdate: 整体修改以适应SF_HttpRequest作为http请求的公共类
 */

class SF_HttpRequest
{
    /**
     * 常量列表
     */
    const HTTP_VERSION_1_0 = 'HTTP/1.0';
    const HTTP_VERSION_1_1 = 'HTTP/1.1';

    const HTTP_METHOD_GET = "GET";
    const HTTP_METHOD_POST = "POST";

    const SCHEMA_HTTP = "http";
    const SCHEMA_HTTPS = "https";

    const HTTP_USER_AGENT_GANJI_DEFAULT = "ganji.com API PHP5 Client 1.0 (non-curl)";

    const HTTP_ACCEPT_ALL = "*/*";
    const HTTP_ACCEPT_JSON = "application/json";

    const HTTP_CONTENT_TYPE_URLENCODED = "application/x-www-form-urlencoded";
    const HTTP_CONTENT_TYPE_GIF = "image/gif";

    // http请求客户端
    const HTTP_CLIENT_TYPE_SOCKET = "socket";
    const HTTP_CLIENT_TYPE_CURL = "curl";


    /**
     * @brief HTTP请求
     *
     * @param string $schema 请求schema(默认http)
     * @param string $method http请求类型(POST/GET),默认为get
     * @param string $uri 资源地址
     */
    public function __construct($schema=self::SCHEMA_HTTP,
                                $method=self::HTTP_METHOD_GET,
                                $uri) {

        $this->uri = $uri;
        $this->method = strtoupper($method);
        $this->schema = $schema;

        // 默认参数
        $this->version = self::HTTP_VERSION_1_0; //默认http版本1.0
        $this->userAgent = self::HTTP_USER_AGENT_GANJI_DEFAULT;
        $this->accept = self::HTTP_ACCEPT_ALL;
        $this->contentType = self::HTTP_CONTENT_TYPE_URLENCODED;
        $this->clientType = self::HTTP_CLIENT_TYPE_SOCKET;  //默认使用socket方式，为更好的控制timeout
        $this->postStr = "";

    }   // end of function __construct


    public function getUri() {
        return $this->uri;
    }
    public function getMethod() {
        return $this->method;
    }
    public function getSchema() {
        return $this->schema;
    }

    /**
     * @param $accept
     *
     * @brief http版本
     *
     * @default: * /*
     */
    public function setVersion($version) {
        $this->version = $version;
    }
    public function getVersion() {
        return $this->version;
    }
    /**
     * @param $accept
     *
     * @brief 设定http接收类型
     *
     * @default: * /*
     */
    public function setAccept($accept) {
        $this->accept = $accept;
    }
    public function getAccept() {
        return $this->accept;
    }
    /**
     * @param $httpUserAgent
     *
     * @brief 设定http请求的userAgent
     *
     * @default: ganji.com API PHP5 Client 1.0 (non-curl)
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }
    public function getUserAgent() {
        return $this->userAgent;
    }

    /**
     * @param $contentType
     *
     * @brief 设定http请求的Content-type
     *
     * @default:
     *      application/x-www-form-urlencoded
     * @others:
     *      application/json
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
    }
    public function getContentType() {
        return $this->contentType;
    }


    /**
     * @param $postStr
     *
     * @brief post类型请求，设定post内容[注: post类型请求调用]
     */
    public function setPostStr($postStr) {
        $this->postStr = $postStr;
    }
    public function getPostStr() {
        return $this->postStr;
    }

    // 设定http客户端请求类型
    public function setClientType($type) {
        $this->clientType = $type;
    }
    public function getClientType() {
        return $this->clientType;
    }

}
