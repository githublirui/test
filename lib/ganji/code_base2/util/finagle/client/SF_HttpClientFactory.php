<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tailor
 * Date: 14-3-19
 * Time: 下午2:43
 * To change this template use File | Settings | File Templates.
 */
require_once FINAGLE_BASE.'/client/SF_HttpClient.php';
require_once FINAGLE_BASE.'/client/SF_ClientFactory.php';

class SF_HttpClientFactory implements SF_ClientFactory
{

    public function __construct() {
    }

    public function getClient($builder, $host, $port) {
        $tcpTimeout = $builder->tcpConnectTimeout->inMilliseconds();
        $timeout = $builder->timeout->inMilliseconds();

        $client = new SF_HttpClient($host, $port, $tcpTimeout, $timeout);
        
        return $client;
    }
}
