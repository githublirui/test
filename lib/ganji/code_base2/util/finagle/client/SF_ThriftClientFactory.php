<?php
/**
 *
 * User: tailor
 * Date: 14-3-19
 * Time: 下午3:07
 *
 */

/**
 * 创建实际的ClientWrapr对象
 *
 * 2014-04-29 add accelerated
 */
require_once FINAGLE_BASE."/client/SF_ThriftClient.php";

class SF_ThriftClientFactory
{
    public function __construct($className, $accelerated=false) {
        $this->className = $className;
        $this->reflectionClass = new ReflectionClass( $className );
        $this->accelerated=$accelerated;
    }

    public function getClient($builder, $host, $port) {
        $tcpConnectTimeout = $builder->tcpConnectTimeout;
        $responseTimeout = $builder->timeout;
        $client = new SF_ThriftClient(
        		$this, $host, $port,
                $tcpConnectTimeout->inMilliseconds(),$responseTimeout->inMilliseconds()
        		);
        $client->setAccelerated($this->accelerated);
        $client->connect();
        return $client;
    }

    public function getInnerClient($protocol, $protocol_out) {
        return $this->reflectionClass->newInstanceArgs(array($protocol, $protocol_out));
    }
}
