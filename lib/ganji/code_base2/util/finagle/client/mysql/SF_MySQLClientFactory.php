<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tailor
 * Date: 14-4-1
 * Time: 下午2:37
 * To change this template use File | Settings | File Templates.
 */
class SF_MySQLClientFactory
{
    public function __construct($serviceName) {
    }

    public function getClient($builder, $host, $port) {
        $timeout = $builder->timeout;
        $client = new SF_HttpClient($host, $port, $timeout->inMilliseconds());
        return $client;
    }
}
