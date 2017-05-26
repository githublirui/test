<?php
/**
 *
 * @author: zhaoweiguo
 * @date: 2014-06-12
 *
 */

require_once FINAGLE_BASE."/client/SF_THttpClient.php";

class SF_THttpClientFactory
{
    public function __construct($className, $path, $accelerated=false) {
        $this->className = $className;
        $this->reflectionClass = new ReflectionClass( $className );
        $this->path = $path;
        $this->accelerated=$accelerated;
    }

    public function getClient($builder, $host, $port) {
        $tcpConnectTimeout = $builder->tcpConnectTimeout;
        $responseTimeout = $builder->timeout;
        $client = new SF_THttpClient(
            $this, $host, $port, $this->path,
            $tcpConnectTimeout->inMilliseconds(),$responseTimeout->inMilliseconds()
        );
//        $client->setAccelerated($this->accelerated);
        $client->connect();
        return $client;
    }

    public function getInnerClient($protocol, $protocol_out) {
        return $this->reflectionClass->newInstanceArgs(array($protocol, $protocol_out));
    }

}
