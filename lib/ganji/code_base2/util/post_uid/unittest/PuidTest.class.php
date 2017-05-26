<?php
require_once dirname(__FILE__) . "/../../../config/config.inc.php";
require_once dirname(__FILE__) . "/../PostUIDNamespace.class.php";
require_once CODE_BASE2 . "/../ganji_conf/unittest/DBConfig.class.php";

class Logger {
    public static function logError($msg) {
            print_r( $msg );
        }
}
class PUIDTest extends PHPUnit_Framework_TestCase{
    public function setUp() {
        //TODO: setup database
    }
    
    public function testInsertPost() {
        $puid = 99;
        $ret = PostUIDNamespace::insertIndex( $puid, 'beijing', 'house_source_rent');
        $this->assertTrue( $ret );
        
        $ret = PostUIDNamespace::updateIndex( $puid, 100);
        $this->assertTrue( $ret );
        
        $ret = PostUIDNamespace::lookUpIndex( $puid );
        //var_dump( $ret );
        $this->assertEquals( 1 , $ret['system_id'] );
        $this->assertEquals( 'beijing' , $ret['db_name'] );
        $this->assertEquals( 'house_source_rent' , $ret['table_name'] );
        $this->assertEquals( 100 , $ret['post_id'] );
        
        $puid = 9;
        $ret = PostUIDNamespace::insertIndex( $puid, 'beijing', 'house_source_rent');
        $this->assertTrue( $ret );
        
        $ret = PostUIDNamespace::updateIndex( $puid, 101);
        $this->assertTrue( $ret );
        
        $ret = PostUIDNamespace::lookUpIndex( $puid );
        $this->assertEquals( 101 , $ret['post_id'] );
        
    }
    
    public function testInsertPostFail() {
	$puid = 99;
        $ret = PostUIDNamespace::insertIndex( $puid, 'beijing', 'house_source_rent1');
        $this->assertFalse( $ret );
	}    
    public function testGenId() {
            $id1 = PostUIDNamespace::generateId();
            
            $this->assertNotEquals( 0, $id1 );
        }
   
}
