<?php
require_once dirname(__FILE__) . "/../../../config/config.inc.php";
require_once dirname(__FILE__) . "/../PostUIDNamespace.class.php";
require_once dirname(__FILE__) . "/../../unittest/MockDBHandle.class.php";
require_once( dirname(__FILE__) . "/../../db/DBHandle.class.php");

class PuidTest extends PHPUnit_Framework_TestCase{

    public function setUp() {
    }

    public function test1() {
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "getOne" => array(
                "sql"=>array(0=>"1" ,1=>"2"),
                "return"=>array(0=>1,1=>1),
            ),
            "execute" =>array(
                "sql"=>array(2=>"2"),
                "return"=>array(2=>"123"),
            )
        ));
        $dbHandle->getOne("1");
        $dbHandle->getOne("2");
        $this->assertEquals( "123", $dbHandle->execute("2"));
    }

    public function test_insertIndex() {

        $puid = 199;
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "getOne" => array(
                    "sql"=>array(0=>"select `id` from db_config where `name`='beijing'",
                                 1=>"select `id` from table_config where `name`='house_source_rent'" ),
                    "return"=>array(0=>1,1=>2),
                ),
            "execute" =>array(
                "sql"=>array(2=>"insert into post_index_99 (`puid`, `db_id`, `table_id`, `post_id` ) values ( 199, 1, 2, 0 )"),
                "return"=>array(2=>"123"),
            )
        ));

        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('getDBHandle'))
            ->getMock();


        $stub->expects($this->any())->method("getDBHandle")->will($this->returnValue($dbHandle));

        $ret = $stub->insertIndex( $puid, 'beijing', 'house_source_rent');
        $this->assertEquals( "123", $ret );
    }

    public function test_insertIndexWith0() {
        $puid = 109;
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "getOne" => array(
                "sql"=>array(0=>"select `id` from db_config where `name`='beijing'",
                    1=>"select `id` from table_config where `name`='house_source_rent'" ),
                "return"=>array(0=>1,1=>2),
            ),
            "execute" =>array(
                "sql"=>array(2=>"insert into post_index_09 (`puid`, `db_id`, `table_id`, `post_id` ) values ( 109, 1, 2, 0 )"),
                "return"=>array(2=>"123"),
            )
        ));

        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('getDBHandle'))
            ->getMock();


        $stub->expects($this->any())->method("getDBHandle")->will($this->returnValue($dbHandle));

        $ret = $stub->insertIndex( $puid, 'beijing', 'house_source_rent');
        $this->assertEquals( "123", $ret );
    }

    public function test_updateIndex() {
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "execute" =>array(
                "sql"=>array(
                    0=>"update post_index_99 set `post_id`=100 where `puid`=199 and `post_id`=0",
                    1=>"update post_index_09 set `post_id`=100 where `puid`=109 and `post_id`=0"
                ),
                "return"=>array(0=>"123",1=>"456"),
            )
        ));

        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('getDBHandle'))
            ->getMock();

        $stub->expects($this->any())->method("getDBHandle")->will($this->returnValue($dbHandle));

        $ret = $stub->updateIndex( 199, 100);
        $this->assertEquals( "123", $ret );

        $this->assertEquals( "456", $stub->updateIndex( 109, 100));
    }

    public function test_lookUpIndex() {
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "getRow" =>array(
                "sql"=>array(0=>"select * from post_index_09 where `puid`=109"),
                "return"=>array(0=>array("db_id"=>127,"table_id"=>235,"post_id"=>100)),
            ),
        ));

        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('getDBHandle','getDbInfoById','getTableNameById'))
            ->getMock();

        $stub->expects($this->any())->method("getDBHandle")
            ->will($this->returnValue($dbHandle));

        $stub->expects($this->any())->method("getDbInfoById")
            ->with($this->equalTo(127))
            ->will($this->returnValue(array("system_id"=>1,"name"=>"beijing")));

        $stub->expects($this->any())->method("getTableNameById")
            ->with($this->equalTo(235))
            ->will($this->returnValue("house_source_rent"));

        $ret = $stub->lookUpIndex( 109 );

        $this->assertEquals( 1 , $ret['system_id'] );
        $this->assertEquals( 'beijing' , $ret['db_name'] );
        $this->assertEquals( 'house_source_rent' , $ret['table_name'] );
        $this->assertEquals( 100 , $ret['post_id'] );
    }

    public function test_RetryDB() {
        $dbHandle = 12345678;
        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('real_getDBHandle'))
            ->getMock();

        $stub->expects($this->any())->method("real_getDBHandle")
            ->will($this->onConsecutiveCalls(null, null, $dbHandle));

        $ret = $stub->getDBHandle(true);
        $this->assertEquals( $dbHandle , $ret);
    }

    public function test_getDbInfoById() {
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "getRow" =>array(
                "sql"=>array(
                    0=>"select * from db_config where `id`=235",
                   // 1=>"select name from table_config where `id`=237",
                ),
                "return"=>array(
                    0=>array("system_id"=>1,"name"=>"beijing"),
                   // 1=>array("system_id"=>1,"name"=>"beijing"),
                ),
            ),
        ));

        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('getDBHandle'))
            ->getMock();

        $stub->expects($this->any())->method("getDBHandle")->will($this->returnValue($dbHandle));
        $ret = $stub->getDbInfoById(235);
        $this->assertEquals( $ret, array("system_id"=>1,"name"=>"beijing"));
    }

    public function test_getTableNameById() {
        $mock = new MockDBHandle();
        $dbHandle = $mock->get(array(
            "getRow" =>array(
                "sql"=>array(
                    0=>"select name from table_config where `id`=237",
                ),
                "return"=>array(
                    0=>array("name"=>"house_source_rent"),
                ),
            ),
        ));

        $stub = $this->getMockBuilder('PostUID')
            ->setMethods(array('getDBHandle'))
            ->getMock();

        $stub->expects($this->any())->method("getDBHandle")->will($this->returnValue($dbHandle));
        $ret = $stub->getTableNameById(237);
        $this->assertEquals( $ret, "house_source_rent");
    }
}
