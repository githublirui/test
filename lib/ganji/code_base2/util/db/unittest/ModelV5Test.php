<?php
/**
 * Created by PhpStorm.
 * User: sherlock
 * Date: 14-3-11
 * Time: 下午3:11
 */

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once CODE_BASE2 . '/app/bang_site/model/BangPromotionModel.class.php';

class ModelV5Test extends PHPUnit_Framework_TestCase {

    private $model = null;

    public function setUp() {
        $this->model = BangPromotionModel::getInstance();

    }

    /**
     * 强制读主库
     */
    public function testForceMaster() {
        $ret = $this->model->forceMaster()->getRow("bang_id=111");

        $this->assertCount(7, $ret);

        $this->assertArrayHasKey('bang_id', $ret);
        $this->assertArrayHasKey('title', $ret);
        $this->assertArrayHasKey('status', $ret);
    }

    /**
     * 插入数据
     */
    public function testInsert() {

        $id = $this->model->insert(array(
            'bang_id' => 111,
            'title' => '你好啊啊啊啊啊，来自ModelV5Test',
            'description' => '描述你好啊啊啊啊啊，来自ModelV5Test',
            'create_at' => time(),
            'status' => 1,
        ));
        $this->assertGreaterThan(0, $id);

        return $id;
    }

    /**
     * 更新数据
     * @depends testInsert
     */
    public function testUpdate($id) {
        $ret = $this->model->update("id={$id}", array('title' => '我是修改过的数据'));
        $this->assertTrue($ret);
        return $id;
    }


    /**
     * 删除数据
     * @depends testUpdate
     */
    public function testDelete($id) {
        $ret = $this->model->delete("id={$id}");
        $this->assertTrue($ret);
        return $id;
    }


    /**
     * 获取数量
     */
    public function testGetCount() {
        $count = $this->model->getCount("title='我是修改过的数据'");
        $this->assertEquals($count, 2);
    }

    /**
     * 获取所有记录
     */
    public function testGetList() {
        $ret = $this->model->getList('*', "bang_id=111", 'create_at asc', 5);

        $this->assertGreaterThan(3, $ret);

        $this->assertArrayHasKey('bang_id', $ret[1]);
        $this->assertArrayHasKey('title', $ret[1]);
        $this->assertArrayHasKey('status', $ret[1]);
    }


    /**
     * 根据where条件获取记录
     */
    public function testGetRow() {
        $ret = $this->model->getRow("bang_id=111");

        $this->assertGreaterThan(0, $ret);

        $this->assertArrayHasKey('bang_id', $ret);
        $this->assertArrayHasKey('title', $ret);
        $this->assertArrayHasKey('status', $ret);
    }
}