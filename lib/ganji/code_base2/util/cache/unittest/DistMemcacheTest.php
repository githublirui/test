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
//require_once 'GanjiUnitTest.class.php';

$GLOBALS['STATSD_HOST'] = "10.3.255.215";
require_once dirname(__FILE__) . "/../../../config/config.inc.php";
require_once dirname(__FILE__) . "/../CacheNamespace.class.php";
require_once dirname(__FILE__) . "/../adapter/DistMemCacheAdapter.class.php";
require_once GANJI_CONF . "/MemcacheConfig.class.php";

/**
 * a mock adapter for easy unittest
 */
class IntegrateDistMemCacheAdapter extends DistMemCacheAdapter {
    protected function getConfig($name) {
        $configs = array(
            null => array("10.3.255.203",22411),
            "test" => array(
                'prefix' => "pfx",
                'servers' => MemcacheConfig::$GROUP_DEFAULT,
                'params' => array(),
                'is_dist' => true,
                'stat_ratio' => 1
            )
        );
        return $configs[$name];
    }
}

class MockDistMemCacheAdapter extends IntegrateDistMemCacheAdapter {
    var $mock_timing = array();
    /*
     * mock queue creating
     */
    protected function initDistQueue() {
        $this->distQueue = $this;
    }

    /**
     * mock queue set
     * @param $val
     */
    public function set($val) {
        $this->mock_queueData = $val;
    }

    /**
     * mock  timging
     * @param $cat
     * @param $v
     * @param $ratio
     */
    protected function timing($cat, $v, $ratio) {
        $this->mock_timing[] = array( $cat, $v , $ratio );
    }

    public function mock_lastTiming($n) {
        return $this->mock_timing[count($this->mock_timing)-$n];
    }
}



class DistMemCacheTest extends PHPUnit_Framework_TestCase{
	/** setUp
     */
	protected function setUp(){
        $this->obj = new MockDistMemCacheAdapter( array('name' => 'test') );
    }
	/** tearDown
     */
	protected function tearDown(){
        $this->obj = null;
    }

    /** testConnect
     */
	public function testConnect(){
            $this->assertEquals(true, ($this->obj instanceof DistMemCacheAdapter));
    }

    /** testWrite
     * @depends testConnect
     */
	public function testWrite(){
        //your test code write here!
        $key = 'code_base2_cache_unittest';
        $value = '123';
        $res = $this->obj->write($key,$value);
        $this->assertEquals($res, true);

        $this->assertEquals( $this->obj->read($key), $value );
        $this->assertEquals( $this->obj->mock_lastTiming(1), array('mc.hit.pfx',1,1) );

        $this->obj->delete($key);

        $value = $this->obj->read($key);
        $this->isFalse($value);
        $this->assertEquals( $this->obj->mock_lastTiming(1), array('mc.hit.pfx',0,1) );

        $res = $this->obj->write($key,$value,1);
        $this->isTrue($res);
    }

	/** testWriteMulti
     * @depends testConnect
     */
	public function testWriteMulti(){
        //your test code write here!
        $keys = array(
                'k1' => 1,
                'k2' => array(1,2),
                );
        $res = $this->obj->writeMulti($keys);
        $this->isTrue($res);
        $this->assertEquals(true, true);

        $value = $this->obj->readMulti(array('k1','k2','kx'));
        $this->assertEquals($value['k1'],1);
        $this->assertEquals($value['k2'],array(1,2));
        $this->assertEquals($value['kx'],null);

        $this->obj->delete('k1');
        $this->obj->delete('k1');

    }
	
	/** testIncrement
     * @depends testConnect
     * @depends testWrite
     */
	public function testIncrement(){
        //your test code write here!
        $key = 'abc';
        $this->obj->write($key,1);
        $ok = $this->obj->increment($key);
        $this->assertEquals($ok, 2);
        $ok = $this->obj->increment($key,2);
        $this->assertEquals($ok, 4);
        $this->obj->delete($key);
    }
	/** testDecrement
     */
	public function testDecrement(){
        //your test code write here!
        $key = 'abc';
        $this->obj->write($key,5);
        $ok = $this->obj->decrement($key);
        $this->assertEquals($ok, 4);
        $ok = $this->obj->decrement($key,2);
        $this->assertEquals($ok, 2);
        $this->obj->delete($key);
    }

    public function testDistDelete() {
        $key = 'abc';
        $this->obj->write($key,5);
        $this->obj->distDelete($key);

        $data = $this->obj->mock_queueData;
        $json = json_decode($data);
        $this->assertEquals($json->k, 'abc' );
    }


    public function testIntegrated() {
        $this->obj = new IntegrateDistMemCacheAdapter( array('name' => 'test') );
        $this->obj->write('xyz123',1,1000);
        // 生成一个0.5的命中率
        for( $i=0;$i<100;$i++) {
            $this->obj->read('xyz123');
            $this->obj->read('xyz123-notfound');
        }
    }
}
