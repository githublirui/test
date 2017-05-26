<?php 
require_once dirname(__FILE__)  . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/util/http/HttpNamespace.class.php';

/**
 * @brief http分析与跳转的测试
 * @author    duxiang duxiang@ganji.com
 * @copyright (c) 2013 Ganji Inc.
 * @date      时间: 2013-8-8:下午09:26:57
 * @version   1.0
 */
class HttpNamespaceTest extends PHPUnit_Framework_TestCase {

    
    public function testgjReqId() {
        $ret = HttpNamespace::getReqId();
        $this->assertEquals(strlen($ret), 49);
    }
    
    /**
     * @dataProvider ipData
     */
    public function testgetIp($assertIp = '', $REMOTE_ADDR = '',$HTTP_GJ_CLIENT_IP = '', $HTTP_X_FORWARDED_FOR = '') {
        $_SERVER['REMOTE_ADDR']       = $REMOTE_ADDR;
        $_SERVER['HTTP_GJ_CLIENT_IP'] = $HTTP_GJ_CLIENT_IP;
        putenv("HTTP_X_FORWARDED_FOR=$HTTP_X_FORWARDED_FOR");
        $ip = HttpNamespace::getIp(false);

        $this->assertEquals($assertIp, $ip);
    }

    public function ipData() {
        $ret = array(
            //全是外网或者没有
            array('59.83.177.100', '59.83.177.100', '59.83.177.101', '59.83.177.102'),
            array('59.83.177.101', '', '59.83.177.101', '59.83.177.102'),
            array('59.83.177.102', '', '', '59.83.177.102'),

            // REMOTE_ADDR 内网
            array('59.83.177.101', '192.168.1.100', '59.83.177.101', '59.83.177.102'),
            array('59.83.177.102', '192.168.1.100', '', '59.83.177.102'),

            // REMOTE_ADDR 内网, 但是其他的都没有
            array('192.168.1.100', '192.168.1.100', '', ''),
            
            // REMOTE_ADDR 内网B类, 但是其他的都没有
            array('59.83.177.101', '172.19.0.0', '59.83.177.101', '59.83.177.102'),
            
            // REMOTE_ADDR 外网172.59.xx
            array('172.59.1.1', '172.59.1.1', '59.83.177.101', '59.83.177.102'),
        );
        return $ret;
    }

    /**
     * @dataProvider ip2cityData
     */
    public function atestIp2city($city_id = '', $ip = '') {
//        $ip = '112.65.150.30';
        $ret = HttpNamespace::ip2City($ip);
//        var_dump($ip, $ret);exit();
        $this->assertEquals($ret['city_id'], $city_id);
    }

    public function ip2cityData() {
        $ret = array(
            //北京
            array(12, '58.83.177.166'),
            //上海
            array(13, '112.65.150.30'),
        );
        return $ret;
    }

    public function testgetSafeNext() {

        $next = HttpNamespace::getSafeNext();
        $this->assertEquals('', $next);

        $next = HttpNamespace::getSafeNext('next');
        $this->assertEquals('', $next);

        $next = HttpNamespace::getSafeNext('next', '/mao');
        $this->assertEquals('/mao', $next);

        $_GET['next'] = 'http://www.ganji.cn/mao';
        $next = HttpNamespace::getSafeNext('next', '/mao');
        $this->assertEquals($_GET['next'], $next);

        $_GET['next'] = 'http://www.guazi.com/mao';
        $next = HttpNamespace::getSafeNext();
        $this->assertEquals($_GET['next'], $next);

        $_GET['next'] = 'http://www.baidu.com/mao';
        $next = HttpNamespace::getSafeNext('next_url', '/mao');
        $this->assertEquals('/mao', $next);

        $_GET['next'] = '/mao/123456x.htm';
        $next = HttpNamespace::getSafeNext('next', 'http://www.ganji.com/login.php');
        $this->assertEquals($_GET['next'], $next);

        $_GET['next'] = '/mao/123456x.htm';
        $next = HttpNamespace::getSafeNext('next_url', 'http://www.ganji.com/login.php');
        $this->assertEquals('http://www.ganji.com/login.php', $next);

    }

    
    protected function setUp() {
    }

    protected function tearDown() {
    }
}