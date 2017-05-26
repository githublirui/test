<?php

/**
 * 类目信息配置
 * @author lirui1 <lirui1@ganji.com>
 * @copyright ganji.com
 */
require_once CODE_BASE2 . '/app/mobile_client/model/baseConsoleModel.class.php';
require_once APP_BACKEND . '/model/category/CategoryItemsModel.class.php';

class ClientCategoryItemConfModel extends baseConsoleModel {

    private static $_TABLE = 'client_category_item_conf';

    public function getModuleList($columns = array('*'), $filter = array(), $offsetArr = array(), $orders = array()) {
        $handle = $this->getDbHandler(false);
        $sql = SqlBuilderNamespace::buildSelectSql(self::$_TABLE, $columns, $filter, $offsetArr, $orders);
        return (array) DBMysqlNamespace::query($handle, $sql);
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

    /**
     * 获取新的moudle id
     * @param type $customer_id
     * @param type $category_id
     */
    public function getNewModuleId($customer_id, $category_id) {
        $result = '';
        $handle = $this->getDbHandler(false);
        $filter = array(
            array('customer_id', '=', $customer_id),
            array('category_id', '=', $category_id),
        );
        $sql = SqlBuilderNamespace::buildSelectSql(self::$_TABLE, 'module_id', $filter, array(1), array('module_id' => 'desc'));
        $moduleId = DBMysqlNamespace::getOne($handle, $sql);
        if (empty($moduleId)) {
            return $category_id . ".1";
        }
        $moduleId = explode('.', $moduleId);
        $moduleId[count($moduleId) - 1] += 1;
        return implode('.', $moduleId);
    }

    /**
     *  生成配置 json
     * @param type $customerId
     */
    public function generateConfig($customerId, $categoryId) {
        $result = array();
        #1 查询module
        $filter = array(
            array('customer_id', '=', $customerId),
            array('category_id', '=', $categoryId),
        );
        $categoryItemsModel = new CategoryItemsModel();
        $modules = $this->getData(false, '*', $filter, array(), array('module_id' => 'asc'));
        #2 取出所有的元素
        $items = $categoryItemsModel->getData(false, '*', $filter, array(), array('item_id' => 'asc'));
        #3 组合
        foreach ($modules as $module) {
            $_data = $this->combineMoudle($module);
            $_data['itemList'] = $module['items'];
            $result['modules'][$module['module_id']][$module['version_id']] = $_data;
        }
        foreach ($items as $item) {
            $result['items'][$item['item_id']] = $this->combineItem($item);
        }
        $result['version'] = time(); //版本
        return serialize($result);
    }

    /**
     * 组合module数据
     * @param type $module
     */
    public function combineMoudle($module) {
        $result = array();
        $result['id'] = $module['module_id'];
        $result['title'] = $module['module_title'];
        $result['icon'] = $module['module_icon'];
        $result['mode'] = $module['module_mode'];
        $exts = json_decode($module['ext'], true);
        if (is_array($exts)) {
            foreach ($exts as $extK => $extV) {
                $result[$extK] = $extV;
            }
        }
        return $result;
    }

    /**
     * 组合item数据
     * @param type $item
     */
    public function combineItem($item) {
        $result = array();
        $result['id'] = $item['item_id'];
        $result['title'] = $item['title'];
        $result['imgUrl'] = $item['img_url'];
        $result['jumpType'] = $item['jump_type'];
        $result['dataParams'] = json_decode($item['data_params'], true);
        if (!empty($item['is_new'])) {
            $result['is_new'] = $item['is_new'];
        }
        if (!empty($item['short_title'])) {
            $result['shortTitle'] = $item['short_title'];
        }
        if (($item['data_source']) > 0) {
            $result['dataSource'] = $item['data_source'];
        }
        $exts = json_decode($item['ext'], true);
        if (is_array($exts)) {
            foreach ($exts as $extK => $extV) {
                $result[$extK] = $extV;
            }
        }
        return $result;
    }

}
