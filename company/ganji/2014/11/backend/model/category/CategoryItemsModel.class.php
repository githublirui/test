<?php

/**
 * (主界面首页、大类首页)新的类目信息配置关联表
 * @author lirui1 <lirui1@ganji.com>
 * @copyright ganji.com
 */
require_once CODE_BASE2 . '/app/mobile_client/model/baseConsoleModel.class.php';

class CategoryItemsModel extends baseConsoleModel {

    private static $_TABLE = 'category_items';

    public function getItemList($columns = array('*'), $filter = array(), $offsetArr = array(), $orders = array()) {
        $handle = $this->getDbHandler(false);
        $sql = SqlBuilderNamespace::buildSelectSql(self::$_TABLE, $columns, $filter, $offsetArr, $orders);
        return (array) DBMysqlNamespace::query($handle, $sql);
    }

    /**
     * 获取新的item id
     * @param type $customer_id
     * @param type $category_id
     */
    public function getNewItemId($customer_id, $category_id, $moduleId = '') {
        $result = '';
        $pregItemId = empty($moduleId) ? $category_id . '.1.' : $moduleId . ".";
        #查询该module下的最后的item id
        $filter = array(
            array('customer_id', '=', $customer_id),
            array('category_id', '=', $category_id),
            array('item_id', 'like', $pregItemId . "%"),
        );
        $handle = $this->getDbHandler(false);
        $sql = SqlBuilderNamespace::buildSelectSql(self::$_TABLE, 'item_id', $filter, array(1), array('item_id' => 'desc'));
        $ItemId = DBMysqlNamespace::getOne($handle, $sql);
        if (empty($ItemId)) {
            return $pregItemId . '1';
        }
        $ItemId = explode('.', $ItemId);
        $ItemId[count($ItemId) - 1] += 1;
        return implode('.', $ItemId);
    }

    /**
     * 保存数据
     * @param type $param
     */
    public function doSave($param, $isInsert = true, $filter = '') {
        $handle = $this->getDbHandler(false);
        if ($isInsert) {
            $sql = SqlBuilderNamespace::buildInsertSql(self::$_TABLE, $param);
        } else {
            $sql = SqlBuilderNamespace::buildUpdateSql(self::$_TABLE, $param, $filter);
        }
        return DBMysqlNamespace::execute($handle, $sql);
    }

}
