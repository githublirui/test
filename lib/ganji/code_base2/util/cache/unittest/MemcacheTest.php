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
require_once dirname(__FILE__) . "/../CacheNamespace.class.php";
require_once dirname(__FILE__) . "/../../../config/config.inc.php";
require_once GANJI_CONF . "/MemcacheConfig.class.php";

class MemCacheTest extends PHPUnit_Framework_TestCase{
	/** setUp
     */
	protected function setUp(){
        $this->obj = CacheNamespace::createCache(CacheNamespace::MODE_MEMCACHE, MemcacheConfig::$GROUP_DEFAULT);
    }
	/** tearDown
     */
	protected function tearDown(){
        $this->obj = null;
    }
    /** testConnect
     */
	public function testConnect(){
            $this->assertEquals(true, ($this->obj instanceof MemCacheAdapter));
    }
    
    public function testConnectConsistent() {
        $params = array();
        $params['distribution_consistent'] = true;
        $obj = CacheNamespace::createCache(CacheNamespace::MODE_MEMCACHE, MemcacheConfig::$GROUP_DEFAULT, $params);
        $this->assertEquals(true, ($obj instanceof MemCacheAdapter));
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
        
        $this->obj->delete($key);
        $value = $this->obj->read($key);
        $this->isFalse($value);

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
                'k3' => $this->obj
                );
        $res = $this->obj->writeMulti($keys);
        $this->isTrue($res);
        $this->assertEquals(true, true);

        $value = $this->obj->readMulti(array('k1','k2','k3'));
        $this->assertEquals($value['k1'],1);
        $this->assertEquals($value['k2'],array(1,2));
        $this->assertEquals(($this->obj instanceof MemCacheAdapter),true);
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
}
