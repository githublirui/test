<?php 

/**
 * @brief cookie测试
 * @author    duxiang duxiang@ganji.com
 * @copyright (c) 2013 Ganji Inc.
 * @date      时间: 2013-6-19:上午11:43:21
 * @version   1.0
 */
ob_start();
require_once dirname(__FILE__)  . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/util/cookie/CookieNamespace.class.php';

class CookieTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider setCookieData
     */
    public function testsetcookie($bool, $name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null) {
//    	var_dump($bool,$name, $value, $expire, $path, $domain, $secure, $httponly);exit();
//        $name = array(1111);
        $ret = CookieNamespace::setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        $this->assertTrue($bool, $ret);
    }
    
    public function setCookieData() {
    	$ret = array(
    	    array(true, 'testcookie1', 'value1'),
    	    array(true, 'testcookie2', 'value2', time() + 3600, "/", '.ganji.com'), 
//    	    array(false, array(121212), 'value1'),
    	);
    	return $ret;
    }
    
    protected function setUp() {
    }

    /** tearDown
     */
    protected function tearDown(){
        ob_start();
        ob_end_flush();
    }
}