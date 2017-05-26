<?php
/**
 * Created by JetBrains PhpStorm.
 * User: caifeng 
 * Date: 13-5-22
 * Time: 下午5:53
 * To change this template use File | Settings | File Templates.
 */

$GLOBALS['CODEBASE_ROOT'] = dirname(__FILE__) . '/../../../';

include_once dirname(__FILE__) . '/../DBHandle.class.php';
include_once dirname(__FILE__) . '/../DBMysqlNamespace.class.php';

class DBHandleTest extends PHPUnit_Framework_TestCase
{
    var $encoding = DBConstNamespace::ENCODING_UTF8;

    public function setUp() {
        $this->db_config = $GLOBALS['UNITTEST_DB_CONFIG'];
        $handle = DBMysqlNamespace::createDBHandle2( $this->db_config, "test", $this->encoding);
        assert( $handle );

        $sql = 'create table if not exists tb_unittest(id int NOT NULL auto_increment,c1 int, PRIMARY KEY (id) )';
        $ret = $handle->execute( $sql);
        $this->assertEquals($ret, TRUE);

        $this->handle = $handle;
    }

    public function tearDown() {
        $sql = 'drop table if exists tb_unittest';
        if( isset( $this->handle )) {
            $ret = $this->handle->execute( $sql);
            $this->assertEquals($ret, TRUE);
            $this->handle->close();
        }
    }

    public function testExecute() {
        $this->assertTrue( $this->handle->execute("select 1"));
    }
}
