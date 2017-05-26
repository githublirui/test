<?php

include_once dirname(__FILE__) . '/705_0.php';
include_once dirname(__FILE__) . '/801_0.php';
include_once dirname(__FILE__) . '/c.php';
$baseModel = new BaseModel();
$handle = $baseModel->getDbHandler('lrtest');
#1. 插入item
#801
$items801 = $category801_arr3[0]['itemList'];
$items705 = $category705_arr3[0]['itemList'];
//$items = array_merge($items705, $items801);
foreach ($items801 as $item) {
    $columnsAndValues = array();
    $columnsAndValues['customer_id'] = 801;
    $columnsAndValues['category_id'] = 0;
    $columnsAndValues['version_id'] = '5.0.0';
    $columnsAndValues['item_id'] = $item['id'];
    $columnsAndValues['title'] = $item['title'];
    $columnsAndValues['img_url'] = $item['imgUrl'];
    $columnsAndValues['jump_type'] = $item['jumpType'];
    $columnsAndValues['jump_type'] = $item['jumpType'];
    $columnsAndValues['status'] = 1;
    $columnsAndValues['created'] = time();
    $columnsAndValues['data_params'] = (json_encode($item['dataParams']));
    if (isset($item['imgInfo'])) {
        $columnsAndValues['ext']['imgInfo'] = $item['imgInfo'];
    }
    if (isset($item['imgUrls'])) {
        $columnsAndValues['ext']['imgUrls'] = $item['imgUrls'];
    }
    if (isset($columnsAndValues['ext']) && count($columnsAndValues['ext']) > 0) {
        $columnsAndValues['ext'] = json_encode($columnsAndValues['ext']);
    }
    if (isset($item['is_new'])) {
        $columnsAndValues['is_new'] = $item['is_new'];
    }
    //判断是否存在
    $filter = array(array('customer_id', '=', $columnsAndValues['customer_id']), //
        array('item_id', '=', $columnsAndValues['item_id']));
    $selectSql = SqlBuilderNamespace::buildSelectSql('category_items', 'id', $filter);
    $row = DBMysqlNamespace::getOne($handle, $selectSql);
    if ($row > 0) {
        //update 
        $sql = SqlBuilderNamespace::buildUpdateSql('category_items', $columnsAndValues, array(array('id', '=', $row)));
        DBMysqlNamespace::execute($handle, $sql);
    } else {
        //insert
        $sql = SqlBuilderNamespace::buildInsertSql('category_items', $columnsAndValues);
        DBMysqlNamespace::execute($handle, $sql);
    }
}
foreach ($items705 as $item) {
    $columnsAndValues = array();
    $columnsAndValues['customer_id'] = 705;
    $columnsAndValues['category_id'] = 0;
    $columnsAndValues['version_id'] = '5.0.0';
    $columnsAndValues['item_id'] = $item['id'];
    $columnsAndValues['title'] = $item['title'];
    $columnsAndValues['img_url'] = $item['imgUrl'];
    $columnsAndValues['jump_type'] = $item['jumpType'];
    $columnsAndValues['status'] = 1;
    $columnsAndValues['created'] = time();
    $columnsAndValues['data_params'] = (json_encode($item['dataParams']));
    if (isset($item['imgInfo'])) {
        $columnsAndValues['ext']['imgInfo'] = $item['imgInfo'];
    }
    if (isset($item['imgUrls'])) {
        $columnsAndValues['ext']['imgUrls'] = $item['imgUrls'];
    }
    if (isset($columnsAndValues['ext']) && count($columnsAndValues['ext']) > 0) {
        $columnsAndValues['ext'] = json_encode($columnsAndValues['ext']);
    }
    if (isset($item['is_new'])) {
        $columnsAndValues['is_new'] = $item['is_new'];
    }
    //判断是否存在
    $filter = array(
        array('customer_id', '=', $columnsAndValues['customer_id']), //
        array('item_id', '=', $columnsAndValues['item_id']), //
    );
    $selectSql = SqlBuilderNamespace::buildSelectSql('category_items', 'id', $filter);
    $row = DBMysqlNamespace::getOne($handle, $selectSql);
    if ($row > 0) {
        //update 
        $sql = SqlBuilderNamespace::buildUpdateSql('category_items', $columnsAndValues, array(array('id', '=', $row)));
        DBMysqlNamespace::execute($handle, $sql);
    } else {
        //insert
        $sql = SqlBuilderNamespace::buildInsertSql('category_items', $columnsAndValues);
        DBMysqlNamespace::execute($handle, $sql);
    }
}
#2. 插入module
$moduleNames = array('第一层', '第二层', '第三层');
foreach ($versionItemList as $cus => $versionItems) {
    foreach ($versionItems as $m => $versionItem) {
        foreach ($versionItem as $version => $item) {
            $columnsAndValues = array();
            $itemId = array();
            $columnsAndValues['customer_id'] = $cus;
            $columnsAndValues['category_id'] = 0;
            $columnsAndValues['start_version'] = $version;
//            $columnsAndValues['end_version'] = '';
            $columnsAndValues['module_id'] = '0.' . ($m + 1);
            $columnsAndValues['module_title'] = $moduleNames[$m];
            $columnsAndValues['module_icon'] = '';
            $columnsAndValues['module_mode'] = 3;
            $columnsAndValues['ext'] = '';
            $columnsAndValues['status'] = 1;

            #获取items
            foreach ($item as $itemOne) {
                $filter = array(array('item_id', '=', $itemOne), array('customer_id', '=', $cus));
                $categoryItemsSql = SqlBuilderNamespace::buildSelectSql('category_items', array('item_id'), $filter);
                $categoryItems = DBMysqlNamespace::getOne($handle, $categoryItemsSql);
                $itemId[] = $categoryItems;
            }
            $columnsAndValues['items'] = implode(",", $itemId);
            //判断是否存在
            $filter = array(array('customer_id', '=', $columnsAndValues['customer_id']), //
                array('module_id', '=', $columnsAndValues['module_id']),
                array('start_version', '=', $version),
            );
            $selectSql = SqlBuilderNamespace::buildSelectSql('client_category_item_conf', 'id', $filter);
            $row = DBMysqlNamespace::getOne($handle, $selectSql);
            if ($row > 0) {
                //update 
                $sql = SqlBuilderNamespace::buildUpdateSql('client_category_item_conf', $columnsAndValues, array(array('id', '=', $row)));
                DBMysqlNamespace::execute($handle, $sql);
            } else {
                //insert
                $sql = SqlBuilderNamespace::buildInsertSql('client_category_item_conf', $columnsAndValues);
                DBMysqlNamespace::execute($handle, $sql);
            }
        }
    }
}
?>
