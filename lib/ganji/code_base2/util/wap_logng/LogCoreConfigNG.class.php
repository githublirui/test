<?php
/**
 * 日志存储配置
 *
 * @author    wangjian
 * @touch     2012-11-28
 * @category  wap
 * @package   LogCoreConfigNG.class.php
 * @version   0.1.0
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 */
class LogCoreConfigNG
{
    /**
     * log文件存储位置
     * @alarm 请注意添加key值，要求小写, {webhandler}_BasePath
     */
    public static $LogBasePath=array (
    'waplog_BasePath' => '/data/waplog/mobilelog/wap/',
     'waplogv2_BasePath' => '/data/waplog/mobilelog/wapv2/',
    'waplogng_BasePath' => '/data/waplog/mobilelog/wapng/',
    'waplogac_BasePath' => '/data/waplog/mobilelog/wapac/',
    'waplogads_BasePath' => '/data/waplog/mobilelog/wapads/',
    'waplogaclk_BasePath' => '/data/waplog/mobilelog/wapaclk/',
    'waplogjiuyou_BasePath' => '/data/waplog/mobilelog/wapjiuyou/',
    'waplogua_wurfl_BasePath'=>'/data/waplog/mobilelog/wapWURFL/',
    'waplogdz_BasePath' => '/data/waplog/mobilelog/wapdz/',
    'waplogtouch_BasePath' => '/data/waplog/mobilelog/waptouch/',
    'waploghtml5_BasePath' => '/data/waplog/mobilelog/waphtml5/',
    'waplogdns_BasePath' => '/data/waplog/mobilelog/waplogDNS/',
/*
            'waplog_BasePath' => '/home/wangjian/logs/wap/',
            'waplogv2_BasePath' => '/home/wangjian/logs/wapv2/',
            'waplogng_BasePath' => '/home/wangjian/logs/wapng/',
            'waplogac_BasePath' => '/home/wangjian/logs/wapac/',
            'waplogads_BasePath' => '/home/wangjian/logs/wapads/',
            'waplogaclk_BasePath' => '/home/wangjian/logs/wapaclk/',
            'waplogjiuyou_BasePath' => '/home/wangjian/logs/wapjiuyou/',
*/
    );


    /**
     * 每产生一个文件时间间隔
     * @access 一个log记录文件最长时间 ,单位秒
     */
    const logFileMaxHoldSecond = 3600;
}