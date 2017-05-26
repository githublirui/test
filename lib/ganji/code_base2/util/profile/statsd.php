<?php

/**
 * A simple PHP class for interacting with a statsd server
 * @author John Crepezzi <john.crepezzi@gmail.com>
 * @package ganji\profile\
 */
/**
 * Statsd的接口封装模块
 * 对statsd接口进行封装，提供多种不同的采集API。用法：
 *
 * Statsd::get()->count('ms.post.pub',1) # 记录主站发帖数
 *
 * Statsd::get()->timing('ms.wu.search', 500, 0.1) # 记录查询的速度，单位ms，采样率0.1
 *
 * 可以到 http://cateye.corp.ganji.com/metric/own/ 订阅自己关心的图表曲线，未来这个API也会和Apollo进行对接。
 * 注意：请不要使用此接口记录敏感数据或者非常重要的数据。
 * @package ganji\profile\
 * @author tailor
 */
class StatsD {

    /**
     * 地址和端口
     * @var string
     * @access private
     */
    private $host, $port;

    /**
     * 实例
     * @var null
     * @access private
     */
    private static $statsd = null;

    /**
     * 获取一个实例，用于操作相关API
     * @static
     * @return StatsD 返回单例的StatsD对象
     */
    public static function get() {
    	if( StatsD::$statsd == null ) {
    		StatsD::$statsd = new StatsD( $GLOBALS['STATSD_HOST'], $GLOBALS['STATSD_PORT']);
    	}
    	return StatsD::$statsd;
    }

    /**
     * 构造函数
     * @param string $host
     * @param int $port
     * @access private
     */
    public function __construct($host = 'localhost', $port = 8125) {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * 记录一个时间数据.
     * @param $key  采集的key
     * @param float $time 时间数据，单位自定义
     * @param float $rate 采样率，0.1表示1/10的采样率
     */
    public function timing($key, $time, $rate = 1) {
        $this->send("$key:$time|ms", $rate);
    }

    /**
     * 记录某个回调函数的执行时间.
     * @param $key 采集的key
     * @param $callback 回调函数
     * @param float $rate 采样率，0.1表示1/10的采样率
     */
    public function time_this($key, $callback, $rate = 1) {
        $begin = microtime(true);
        $callback();
        $time = floor((microtime(true) - $begin) * 1000);
        // And record
        $this->timing($key, $time, $rate);
    }

    /**
     * 记录一个累计的数据.
     * @param $key 采集的key
     * @param int $amount 累积的增量
     * @param float $rate 采样率，0.1表示1/10的采样率
     */
    public function counting($key, $amount = 1, $rate = 1) {
        $this->send("$key:$amount|c", $rate);
    }

    /**
     * 发送数据
     * @param $value
     * @param $rate
     * @access private
     */
    private function send($value, $rate) {
    	if( !$this->host )
    		return;
        if ($rate < 1 && (mt_rand() / mt_getrandmax()) > $rate)
            return;
        $fp = fsockopen('udp://' . $this->host, $this->port, $errno, $errstr);
        // Will show warning if not opened, and return false
        if ($fp) {
            fwrite($fp, "$value|@$rate");
            fclose($fp);
        }
    }

}
