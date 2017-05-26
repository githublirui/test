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

class CacheFileTest extends PHPUnit_Framework_TestCase{
	/** setUp
     */
	protected function setUp(){
        $this->obj = CacheNamespace::createCache(CacheNamespace::MODE_APC);
        $this->key = 'code_base2_ppc_unittest';
    }
	/** tearDown
     */
	protected function tearDown(){
        $this->obj = null;
        $this->key = null;
    }
	/** testWrite
     */
	public function testWrite(){
        //your test code write here!
        $key = 'code_base2_ppc_unittest';
        $value = 'apc123';
        $res = $this->obj->write($key,$value);
        $this->assertEquals($res, true);
        $value = $this->obj->read($key);
        $this->assertEquals($value, 'apc123');        
    }
}
