<?php
/**
 * wushuiyong@ganji.com
 * 2014-01-2
 */
require_once dirname(__FILE__)  . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/util/redis/RedisClient.class.php';
require_once GANJI_CONF . '/RedisConfig.class.php';

function getRedis() {
    $server = array( 
              // "host"     => "10.3.2.9",// test
              "host"     => "192.168.113.149",// slaver
              // "host"     => "192.168.113.228",// master
              // "port"     => 6379,
              "port"     => 6380,
              // "port"     => 6379,
              'db'       => 0,
              'weight'   => 1,
              'password' => '', 
              'timeout'  => 3
    );

    RedisConfig::$GROUP_LNGLAT=array(
            'servers' => array(
                0 => $server));
    
    $beginTime = microtime(true);
    $redis = new RedisClient(RedisConfig::$GROUP_LNGLAT);
    $redis = $redis->getMasterRedis();
    $s = microtime(true)-$beginTime;
    if ($s>0.5) {
        exit('redis连接时间过长'.$s);
    }   
    return $redis;
}

function clear() {
    $redis = getRedis();
    $size = $redis->dbSize() / 4; // 34560421 
    $cnt = 0;

    if ($size < 103681263) return true;

    // $f = fopen("/tmp/clearRedis.txt", 'a');
    while ($cnt < 34560421) {
    // while ($cnt < 10) {
        $key = $redis->randomkey();
        $ttl = $redis->ttl($key);
        if ($ttl != -1) {
            // write for revert
            // fwrite($f, sprintf("%s %s %s\n", $key, $redis->get($key), $ttl));
            $redis->get($Key);
            $redis->del($key);

            $redis->get($Key);
            // sleep 1s per 100keys
            if (++$cnt % 100 == 0) sleep(1);
        }

    }   
}

################################ clear 1/4 redis #######################################

clear();