<?php

/**
 * @Copyright (c) 2014 Ganji Inc.
 * @file          /finagle-php/client/http/HttpRequet.php
 * @create        [2014-04-17]赵卫国
 * @lastupdate    [2014-04-28]赵卫国
 * @other   用来替换原来的/code_base2/util/http/HttpRequest
 *
 *
 * @brief 使用Socket形式封装发起Http请求
 */

require_once dirname(__FILE__) . '/../../exception/SF_Exception.php';

class SF_SocketHttpRequest
{

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
     *
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
                                $httpContentType,
                                $httpPostStr) {
		$this->host = $host;
        $this->uri = $uri;

        $this->method = $method;


        if($schema == 'https') {
            $this->schema = 'ssl';
            $this->port = $port != 80 ? $port : 443;
            $this->hostname = 'ssl://' . $host;
        } else {
            $this->schema = 'http';
            $this->port = $port;
            $this->hostname = $host;
        }
		$this->connectTimeout = $connectTimeout;
		$this->readTimeout = $readTimeout;

        // 默认参数
        $this->httpVer = $httpVer;
        $this->httpUserAgent = $httpUserAgent;
        $this->httpAccept = $httpAccept;
        $this->httpContentType = $httpContentType;
        $this->httpPostStr = $httpPostStr;

	}   // end of function __construct



    /*
     * 执行http请求:
     *
     * 返回值:
     *  1. 成功，则返回http请求的内容
     *  2. 出错并且重试未解决,则抛出不同的exception
     *
     */
    public function request() {

        $fp = @fsockopen($this->hostname, $this->port, $errno, $errstr, $this->connectTimeout / 1000.0);
        if (!$fp) {
            throw new SF_SocketOpenException("Timeout when connect to socket: [$this->schema]$this->host:$this->port; connectTimeout: $this->connectTimeout");


        } else {
            $in  = "$this->method $this->uri $this->httpVer\r\n";
            $in .= "Accept: $this->httpAccept\r\n";
            $in .= "User-Agent: $this->httpUserAgent\r\n";
            $in .= "Host: $this->host\r\n";
            $in .= "Content-type: $this->httpContentType\r\n";
            if($this->method == 'POST') {
                $in .= "Content-Length: " . strlen($this->httpPostStr) . "\r\n";
            }
            $in .= "Connection: Close\r\n\r\n";
            if($this->method == 'POST') {
                $in .= $this->httpPostStr . "\r\n\r\n";
            }

            if(!fwrite($fp, $in)) { // 写失败的情况
                fclose($fp);
                throw new SF_SocketWriteException("Failed to run fwrite() for writing to socket $this->schema $this->host : $this->port ");
            };
            if ($this->readTimeout != 0) {
                //设置读取超时
                stream_set_timeout($fp, 0, $this->readTimeout * 1000);
            }
            unset($in);

            $out = '';
            while (!feof($fp)) {
//                $out .= fgets($fp, 512);
                $result = fread($fp, 512);
                if ($result === false || $result === '') {
                    $md = stream_get_meta_data($fp);
                    if ($md['timed_out'] == true) {
                        fclose($fp);
                        throw new SF_SocketTimeoutException("Timeout when read from socket:[$this->schema]$this->host:$this->port; responseTimeout:$this->readTimeout");
                    }
                }
                $out .= $result;
            }
            fclose($fp);

            $pos = strpos($out, "\r\n\r\n");
            $head = substr($out, 0, $pos);        //http head
            $status = substr($head, 0, strpos($head, "\r\n"));        //http status line
            $body = substr($out, $pos + 4, strlen($out) - ($pos + 4));        //page body

            if (preg_match("/^HTTP\/\d\.\d\s([\d]+)\s.*$/", $status, $matches)) {
                if (intval($matches[1]) / 100 == 2) {//return http get body
                    return $body;
                } else {
                    throw new SF_HttpStatusException('http status not ok:'. $matches[1], 30001);
                }
            } else {
                throw new SF_HttpInvalidException('http status invalid:' . $head . "\nOUT: " , 30002);
            }

        }

    }   // end of function request/0


}