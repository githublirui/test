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
require_once dirname(__FILE__) . "/../CacheNamespace.class.php";
require_once dirname(__FILE__) . "/../../../config/config.inc.php";
require_once GANJI_CONF . "/MemcacheConfig.class.php";
class CacheFactoryTest extends PHPUnit_Framework_TestCase{
	/** setUp
     */
	protected function setUp(){
    }
	/** tearDown
     */
	protected function tearDown(){
    }
	/** testCreateCache
     * @dataProvider providerCreateCache($mode, $ok = true)
     */
	public function testCreateCache($mode, $servers, $ok = true){
        //your test code write here!
        $obj = CacheNamespace::createCache($mode, $servers);
        if ($mode == CacheNamespace::MODE_MEMCACHE) {
            $this->assertEquals(($obj instanceof MemCacheAdapter ), $ok);
        } 
        else {
            $this->assertEquals(($obj instanceof ApcCacheAdapter ), $ok);
        }
    }
	/** providerCreateCache
     */
	public function providerCreateCache(){
        //your test code write here!
        return array(
            array(CacheNamespace::MODE_MEMCACHE, MemcacheConfig::$GROUP_DEFAULT, true),
            array(CacheNamespace::MODE_APC, array(), true),
        );
    }

    /**
     * 测试缓存对象的缓存
     */
    public function testCacheCache() {
        $a1 = CacheNamespace::createCache(CacheNamespace::MODE_APC, array('1'));
        $a2 = CacheNamespace::createCache(CacheNamespace::MODE_APC, array('2'));
        $this->assertTrue( $a1 !== $a2 );
        $a3 = CacheNamespace::createCache(CacheNamespace::MODE_APC, array('1'));
        $this->assertTrue( $a1 === $a3 );
    }
}
