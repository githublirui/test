<?php
/**
 * Created by PhpStorm.
 * User: sherlock
 * Date: 14-3-12
 * Time: 上午10:54
 */
require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';
require_once GANJI_CONF . '/DBConfig.class.php';
require_once CODE_BASE2 . '/util/db/SqlBuilderV5.class.php';

class SqlBuilderV5Test extends PHPUnit_Framework_TestCase {
    private $sqlBuilder = null;

    public function setUp() {

        $dbLink = DBMysqlNamespace::createDBHandle(DBConfig::$SERVER_BANG_SLAVE, 'bang');

        $this->sqlBuilder = new SqlBuilderV5('bang_promotion', array(
            'id' => 'INT',
            'bang_id' => 'INT',
            'title' => 'VARCHAR',
            'description' => 'VARCHAR',
            'create_at' => 'INT',
            'status' => 'INT',
            'refuse_reason' => 'VARCHAR',
        ), $dbLink);

    }

    public function testCreateSelectSql() {
        $sql1 = $this->sqlBuilder->createSelectSql('bang_id,title', array('title' => '111'), 'create_at desc', 3, 0);
        $this->assertEquals($sql1, "SELECT bang_id,title FROM bang_promotion WHERE `title`='111' ORDER BY create_at desc LIMIT 0,3");


        $sql2 = $this->sqlBuilder->createSelectSql('bang_id,title', array('title' => '111', 'test' => 111), 'create_at desc', 3);
        $this->assertEquals($sql2, "SELECT bang_id,title FROM bang_promotion WHERE `title`='111' ORDER BY create_at desc LIMIT 0,3");

        $sql3 = $this->sqlBuilder->createSelectSql('*', 'id>3', 'create_at');
        $this->assertEquals($sql3, "SELECT * FROM bang_promotion WHERE id>3 ORDER BY create_at");

        $sql4 = $this->sqlBuilder->createSelectSql();
        $this->assertEquals($sql4, "SELECT * FROM bang_promotion");
    }

    public function testCreateInsertSql() {
        $sql1 = $this->sqlBuilder->createInsertSql(array(
            'id' => 'INT',
            'bang_id' => 'INT',
            'title' => 'VARCHAR',
            'description' => 'VARCHAR',
            'create_at' => 'INT',
            'status' => 'INT',
            'refuse_reason' => 'VARCHAR',
        ));
        $this->assertEquals($sql1, "INSERT INTO `bang_promotion` (`title`, `description`, `refuse_reason`) VALUES ('VARCHAR', 'VARCHAR', 'VARCHAR')");

        $sql2 = $this->sqlBuilder->createInsertSql(array(
            'bang_id' => '111',
            'title' => 'sdfsdf""""sdfsd"""sdfsdf',
            'description' => 123,
            'create_at' => '0',
            'refuse_reason' => 'VAR\1""\d\d\dCHAR',
        ));

        $this->assertEquals($sql2, 'INSERT INTO `bang_promotion` (`bang_id`, `title`, `create_at`, `refuse_reason`) VALUES (111, \'sdfsdf\"\"\"\"sdfsd\"\"\"sdfsdf\', 0, \'VAR\\\\1\"\"\\\\d\\\\d\\\\dCHAR\')');

        $sql3 = $this->sqlBuilder->createInsertSql(array());
        $this->assertSame($sql3, '');
    }

    public function testCreateUpdateSql() {
        $sql1 = $this->sqlBuilder->createUpdateSql(array(
            'bang_id' => 'INT',
            'title' => 'VARCHAR',
            'description' => 'VARCHAR',
            'create_at' => 'INT',
            'status' => 'INT',
            'refuse_reason' => 'VARCHAR',
        ), 'title=1');
        $this->assertSame($sql1, "UPDATE bang_promotion  SET `title`='VARCHAR',`description`='VARCHAR',`refuse_reason`='VARCHAR'  WHERE title=1");

        $sql2 = $this->sqlBuilder->createUpdateSql(array(
            'bang_id' => '123',
            'title' => 'VARCHAR',
            'description' => 'VARCHAR',
            'create_at' => 'INT',
            'status' => 'INT',
            'refuse_reason' => 'VARCHAR',
        ), array('title' => '你好""你好', id => '234'));
        $this->assertSame($sql2, 'UPDATE bang_promotion  SET `bang_id`=123,`title`=\'VARCHAR\',`description`=\'VARCHAR\',`refuse_reason`=\'VARCHAR\'  WHERE `title`=\'你好\"\"你好\' AND `id`=234');

    }

    public function testCreateDeleteSql() {
        $sql1 = $this->sqlBuilder->createDeleteSql(array(
            'bang_id' => 'INT',
            'title' => 'VARCHAR',
            'description' => 'VARCHAR',
            'create_at' => 'INT',
            'status' => 'INT',
            'refuse_reason' => 'VARCHAR',
        ));
        $this->assertSame($sql1, "DELETE FROM bang_promotion  WHERE `title`='VARCHAR' AND `description`='VARCHAR' AND `refuse_reason`='VARCHAR'");

        $sql2 = $this->sqlBuilder->createDeleteSql(array(
            'bang_id' => 123,
        ));
        $this->assertSame($sql2, "DELETE FROM bang_promotion  WHERE `bang_id`=123");

        $sql3 = $this->sqlBuilder->createDeleteSql("create_at>32323 and id<555");
        $this->assertSame($sql3, "DELETE FROM bang_promotion  WHERE create_at>32323 and id<555");
    }

    public function testCreateWhereSql() {
        $sql1 = $this->sqlBuilder->createWhereSql(array(
            'bang_id' => 'INT',
            'title' => 'VARCHAR',
            'description' => 'VAR""C\HAR',
            'create_at' => 'INT',
            'status' => 'INT',
            'refuse_reason' => 'VARCHAR',
        ));
        $this->assertSame($sql1, ' WHERE `title`=\'VARCHAR\' AND `description`=\'VAR\"\"C\\\HAR\' AND `refuse_reason`=\'VARCHAR\'');

    }

}