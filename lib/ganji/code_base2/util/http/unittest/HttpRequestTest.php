<?php 
require_once dirname(__FILE__)  . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/util/http/HttpRequest.class.php';

/**
 * @brief http分析与跳转的测试
 * @author    duxiang duxiang@ganji.com
 * @copyright (c) 2013 Ganji Inc.
 * @date      时间: 2014-3-18
 * @version   1.0
 */
class HttpRequestTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider socketgetData
     */
    public function testSocketget($bool, $url, $connectTimeout, $readTimeout = 0, $retry = 0) {
        
        $star = microtime(true);
        $ret = HttpRequest::socketGet($url, $connectTimeout, $readTimeout, $retry);
        	$end = microtime(true);
	    $runtime =  $end - $star;
//        var_dump($runtime,$ret);exit();
        $this->assertEquals($ret && strlen($ret) > 0, $bool);
    }
    

    /**
     * @dataProvider getData
     */
    public function testGet($bool, $url, $timeout, $connectTimeout = 0, $readTimeout = 0, $retry = 0) {
        
        $star = microtime(true);
        $ret = HttpRequest::get($url, $timeout, $connectTimeout, $readTimeout, $retry);
        	$end = microtime(true);
	    $runtime =  $end - $star;
//        var_dump($runtime,strlen($ret),$bool);exit();
        $this->assertEquals($ret && strlen($ret) > 0, $bool);
    }

    public  function socketgetData() {
        $ret = array(
            array(true, 'http://www.sina.com.cn', 1000, 200, 2),
            array(false, 'http://www.sina.com.cn', 20, 20, 2),
            array(true, 'http://www.baidu.com', 1000, 1000, 2),
        );
        return $ret;
    }
    
    public function getData() {
        $ret = array(
            array(true, 'http://www.sina.com.cn', 2),
        // 'callback( {"error":100019,"error_description":"code to access token error"} );
            array(true, 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=213134&client_secret=23d8c52998aae027f2507e068f15a69a&code=01AC9A8B9E80C740B786A92572889769&redirect_uri=http%3A%2F%2Fwww.ganji.com%2Fuser%2Fthirdparty%2Fqq_callback.php', 2),
            array(true, 'http://www.baidu.com', 3),
        );
        return $ret;
    }
    
    
    protected function setUp() {
    }

    protected function tearDown() {
    }
}