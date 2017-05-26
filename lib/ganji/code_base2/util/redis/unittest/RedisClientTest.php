<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2011, www.ganji.com
 */
require_once( dirname(__FILE__) . "/../../../config/config.inc.php");

require_once dirname(__FILE__) . "/../RedisClient.class.php";
require_once( dirname(__FILE__) . "/../../log/Logger.class.php");


class FakeRedis /*extends Redis*/ {
    public function connect() {
        return true;
    }
    public function auth() {
        return true;
    }
    public function select() {
        return true;
    }

    public function ping() {
        return true;
    }
}



class RedisClientTest  extends PHPUnit_Framework_TestCase{
	/** {{{ setUp
     */
	protected function setUp(){
    }//}}}
	/** {{{ tearDown
     */
	protected function tearDown(){
    }//}}}
	/** {{{ testGetMasterRedis
     */
	public function testGetMasterRedis($key = null, $ok = true){

        /*
        //覆盖redis方法
        $mockRedis = $this->getMockBuilder('Redis')
            ->setMethods(array('connect','auth','select','ping'))
            ->getMock();
        $mockRedis->expects($this->any())
            ->method("connect")
            ->will($this->returnValue(true));

        $ok = $mockRedis->connect();*/
        $mockRedis = new FakeRedis();

        $options = array(
               'servers'   => array(
                   array('host' => '127.0.0.1', 'port' => 6379,'db' => 16, 'weight' => 1, 'password' => 'ganji_!q2w#','timeout' => 3)
                   )
                );
        $mockRedisClient = $this->getMockBuilder('RedisClient')
            ->setConstructorArgs(array($options))
            ->setMethods(array('getRedisObj','loginRedis','isHitConsumeUpperLimit'))
            ->getMock();
        $mockRedisClient->expects($this->any())
            ->method("getRedisObj")
            ->will($this->returnValue($mockRedis));
        $mockRedisClient->expects($this->any())
            ->method("loginRedis")
            ->will($this->returnValue(true));

        $this->assertEquals($mockRedis,$mockRedisClient->getMasterRedis('123'));
    }//}}}
	/** {{{ testSetOption
     */
	public function testSetOption(){
        //your test code write here!
        $this->assertEquals(1,1);
    }//}}}
	/** {{{ testLoginRedis
     */
	public function testLoginRedis(){
        //your test code write here!
        $this->assertEquals(1,1);
    }//}}}
	/** {{{ testHashServer
     */
	public function testHashServer(){
        //your test code write here!
        $this->assertEquals(1,1);
    }//}}}
	/** {{{ testClose
     */
	public function testClose(){
        //your test code write here!
        $this->assertEquals(1,1);
    }//}}}
}
