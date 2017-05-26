<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tailor
 * Date: 14-4-1
 * Time: 下午2:35
 *
 */
class SF_MySQLClient
{
    public function __construct($host, $port, $timeout) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }
}
