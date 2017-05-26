<?php
/**
 * Created by PhpStorm.
 * User: sherlock
 * Date: 14-3-11
 * Time: 下午3:11
 */

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/app/bang/model/BangSecondmarketPromotionAuditModel.class.php';
require_once CODE_BASE2 . '/app/vehicle/model/VehiclePostModel2.class.php';

class ModelTest extends PHPUnit_Framework_TestCase {

    private $model = null;

    public function setUp() {
        $this->model = BangSecondmarketPromotionAuditModel::getInstance();

    }

    /**
     * 强制读主库
     */
    public function testForceMaster() {
        $ret = $this->model->forceMaster()->getRow("audit_type=1");
        $this->assertCount(5, $ret);

        $this->assertArrayHasKey('puid', $ret);
        $this->assertArrayHasKey('audit_result', $ret);

        $ret2 = $this->model->releaseForceMaster();
        $this->assertSame($ret2, null);
    }

    /**
     * 插入数据
     */
    public function testInsert() {
        $puid = time();

        $id = $this->model->insert(array(
            'puid' => $puid,
            'audit_type' => 1,
            'audit_result' => 2,
            'reason' => 'VARCHAR',
            'create_at' => 'INT',
        ));
        $this->assertSame('0', $id);

        return $puid;
    }

    /**
     * 更新数据
     * @depends testInsert
     */
    public function testUpdate($puid) {
        $ret = $this->model->update("puid={$puid}", array('reason' => '我是修改过的', 'title' => 'sdfsdf', 'audit_types' => 1));
        $this->assertTrue($ret);

        $ret2 = $this->model->update(array('puid' => $puid), "reason='我是修改过的数据'");
        $this->assertTrue($ret2);

        $ret3 = $this->model->update('', "reason='我是修改过的数据'");
        $this->assertFalse($ret3);

        $ret4 = $this->model->update("puid={$puid}", '');
        $this->assertFalse($ret4);

        return $puid;
    }


    /**
     * 删除数据
     * @depends testUpdate
     */
    public function testDelete($puid) {
        $ret = $this->model->delete("puid>=1394690579 and puid<{$puid}");
        $this->assertTrue($ret);
        return $puid;
    }


    /**
     * 获取数量
     * @depends testDelete
     */
    public function testGetCount($puid) {
        $count = $this->model->getCount("puid>=1394690579 and puid<={$puid}");
        $this->assertEquals($count, 1);
    }

    /**
     * 获取所有记录
     */
    public function testGetList() {
        $ret = $this->model->getAll(array('audit_type' => 1), 'puid desc', 5, 0, '*');

        $this->assertCount(5, $ret);

        $this->assertArrayHasKey('audit_result', $ret[0]);
        $this->assertArrayHasKey('reason', $ret[0]);

        $this->assertSame($ret[0]['reason'], '我是修改过的数据');
    }


    /**
     * 根据where条件获取记录
     */
    public function testGetRow() {
        $ret = $this->model->getRow(array('audit_type' => 1), 'reason', 'puid desc');

        $this->assertCount(1, $ret);
        $this->assertArrayHasKey('reason', $ret);
        $this->assertSame($ret['reason'], '我是修改过的数据');
    }


    public function testUpdateById() {
        $now = time();
        $ret = VehiclePostModel2::getInstance('beijing')->updateById(948125, array('refresh_at' => $now));
        $this->assertTrue($ret);

        $refreshAt = VehiclePostModel2::getInstance('beijing')->getOne("refresh_at", "id=948125");
        $this->assertEquals($now, $refreshAt);
    }

    public function testGetRowById() {
        $ret = VehiclePostModel2::getInstance('beijing')->getRowById(948125);
        $this->assertEquals($ret['id'], 948125);

        $sqlHistory = Model::getSqlHistory();
        $this->assertEquals(end($sqlHistory), "SELECT * FROM vehicle_post WHERE id=948125 LIMIT 0,1");
    }

}