<?PHP

#require_once 'PHPUnit/Framework.php';

$GLOBALS['CODEBASE_ROOT'] = dirname(__FILE__) . '/../../../';

require_once $GLOBALS['CODEBASE_ROOT'] . '/../ganji_conf/DBConfig.class.php';

include_once dirname(__FILE__) . '/../DBMysqlNamespace.class.php';
if( !isset($GLOBALS['UNITTEST_DB_CONFIG'])) {
    $GLOBALS['UNITTEST_DB_CONFIG'] = array(
        'host'      => "127.0.0.1",
        'username'  => "test",
        'password'  => "test",
        'port'      => 3306,
    );
}

class DBMysqlNamespaceTest extends PHPUnit_Framework_TestCase {
    var $dbname = 'test';
    var $encoding = DBConstNamespace::ENCODING_UTF8;
    var $db_config;
    
	public function setUp() {
        $this->db_config = $GLOBALS['UNITTEST_DB_CONFIG'];

        $handle = DBMysqlNamespace::createDBHandle($this->db_config, $this->dbname, $this->encoding);
        $arg = is_bool($handle) && ($handle === FALSE);
        $this->assertEquals($arg, FALSE);
        
        $sql = 'create table if not exists tb_unittest(id int NOT NULL auto_increment,c1 int, PRIMARY KEY (id) )';
        $ret = DBMysqlNamespace::execute($handle, $sql);
        $this->assertEquals($ret, TRUE);

        $sql = 'truncate tb_unittest';
        $ret = DBMysqlNamespace::execute($handle, $sql);
        $this->assertEquals($ret, TRUE);

        $this->handle = $handle;
	}
    
    public function tearDown() {
        $sql = 'drop table if exists tb_unittest';
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, TRUE);
        DBMysqlNamespace::releaseDBHandle($this->handle);
    }
	
	public function test_getDBHandle() {
        $dbname = 'test';
        $encoding = DBConstNamespace::ENCODING_UTF8;

        //$handle = DBMysqlNamespace::createDBHandle(DBMysqlConfig::$ERROR_DB, $dbname, $encoding);
        //$this->assertEquals($handle, FALSE);
	}

    public function test_execute() {
        $sql = 'create table tb_unittest(c1 int)';
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, FALSE);
    }
    
    public function test_insertAndGetID() {
        $sql = 'alter table tb_unittest auto_increment=4000';
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, TRUE);
        
        $sql = 'insert into tb_unittest(c1) values (100)';
        $ret = DBMysqlNamespace::insertAndGetID($this->handle, $sql);
        $this->assertEquals($ret, 4000);
    }

    public function test_query() {
        $sql = 'insert into tb_unittest(c1) values (100)'; 
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, TRUE);
        
        $sql = 'select c1 from tb_unittest';
        $ret = DBMysqlNamespace::query($this->handle, $sql);
        $this->assertEquals(count($ret), 2);

    }

    public function test_queryFrist() {
        $sql = 'insert into tb_unittest(c1) values (100)';
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, TRUE);

        $sql = 'select id,c1 from tb_unittest';
        $ret = DBMysqlNamespace::queryFirst($this->handle, $sql);
        $this->assertEquals($ret, array( "id" => 1 , "c1" => 100 ) );
    }

    public function test_lastAffected() {
        $sql = 'insert into tb_unittest(c1) values (100)';
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, TRUE);
        $ret = DBMysqlNamespace::lastAffected($this->handle);
        $this->assertEquals($ret, 1);
        
        $sql = 'delete from tb_unittest';
        $ret = DBMysqlNamespace::execute($this->handle, $sql);
        $this->assertEquals($ret, TRUE);
        $ret = DBMysqlNamespace::lastAffected($this->handle);
        $this->assertEquals($ret, 2);
    }

    public function test_multiInstance() {
        
        $h1 = DBMysqlNamespace::createDBHandle($this->db_config, $this->dbname, $this->encoding);
        //print_r( $h1 );
        $arg = is_bool($h1) && ($h1 === FALSE);
        $this->assertEquals($arg, FALSE);
        
        DBMysqlNamespace::releaseDBHandle( $h1 );

    }
}

?>
