<?php

/**
 * 导入其他分类
 * 2014-11-04
 */
$filesArr = array(
    '5' => '705',
    '7_5.10.0' => '705',
);

//模块字段
$GLOBALS['moduleColumns'] = array(
    'id',
    'title',
    'mode',
    'ext',
    'imgUrls',
    'icon',
    'itemList',
);
//元素阻断
$GLOBALS['itemColumns'] = array(
    'id',
    'title',
    'imgUrls',
    'shortTitle',
    'ext',
    'imgUrl',
    'dataSource',
    'dataParams',
);
define('NOW_FILE_PATH', dirname(__FILE__));
$baseModel = new BaseModel();
$GLOBALS['handle'] = $baseModel->getDbHandler('lrtest');
$dir = NOW_FILE_PATH . '/category';
$dirHandle = opendir($dir);
while (($file = readdir($dirHandle)) !== false) {
    if ($file == '.' || $file == '..') {
        continue;
    }
    $categoryFileInfo = explode('_', $file);
    $customerId = $categoryFileInfo[0];
    $categoryId = $categoryFileInfo[1];
    $categoryFileInfoThree = isset($categoryFileInfo[2]) ? $categoryFileInfo[2] : '';
    $startVersion = '';
    $virtualId = 0;
    if ($categoryFileInfoThree != '') {
        if ($categoryFileInfoThree == '5.10.0') {
            $startVersion = '5.10.0';
        } else {
            $virtualId = $categoryFileInfoThree;
        }
    }
    $filePah = $dir . '/' . $file;
    if (!file_exists($filePah)) {
        var_dump($filePah . ' 文件未找到');
        die;
    }
    $data = file_get_contents($filePah);
    $data = unserialize($data);
//    $data['info'][0]['ids']=111;
//    print_r(serialize($data));die;
    $categoryInfos = $data['info'];
    foreach ($categoryInfos as $categoryInfo) {
        insertModule($categoryInfo, $customerId, $categoryId, $startVersion, $virtualId);
        if (is_array($categoryInfo['itemList'])) {
            foreach ($categoryInfo['itemList'] as $item) {
                insertItem($item, $customerId, $categoryId, $startVersion, $virtualId);
            }
        }
    }
}

function insertModule($categoryInfo, $customerId, $categoryId, $startVersion, $virtualId) {
    $columnsAndValues = array();
    $itemId = array();
    $columns = array_keys($categoryInfo);
//查看是否在设定的字段内
    foreach ($columns as $column) {
        if (!in_array($column, $GLOBALS['moduleColumns'])) {
            var_dump($column);
            var_dump($customerId);
            var_dump($categoryId);
            die;
        }
    }
    $columnsAndValues['customer_id'] = $customerId;
    $columnsAndValues['category_id'] = $categoryId;
    $columnsAndValues['virtual_id'] = $virtualId;
    if (!$startVersion) {
        $columnsAndValues['end_version'] = '5.10.0';
    } else {
        $columnsAndValues['start_version'] = $startVersion;
    }
    if (isset($categoryInfo['id'])) {
        $columnsAndValues['module_id'] = $categoryInfo['id'];
    }
    if (isset($categoryInfo['title'])) {
        $columnsAndValues['module_title'] = $categoryInfo['title'];
    }
    if (isset($categoryInfo['icon'])) {
        $columnsAndValues['module_icon'] = $categoryInfo['icon'];
    }
    if (isset($categoryInfo['mode'])) {
        $columnsAndValues['module_mode'] = $categoryInfo['mode'];
    }
    if (isset($categoryInfo['ext'])) {
        $columnsAndValues['ext'] = $categoryInfo['ext'];
    }
//    if (!empty($categoryInfo['ext'])) {
//        var_dump($categoryInfo['ext']);
//        var_dump($customerId);
//        var_dump($startVersion);
//        die;
//    }
    if (isset($categoryInfo['imgUrls'])) {
        $columnsAndValues['ext']['imgUrls'] = @$categoryInfo['imgUrls'];
    }
    if (isset($columnsAndValues['ext'])) {
        $columnsAndValues['ext'] = json_encode($columnsAndValues['ext']);
    }
    $columnsAndValues['status'] = 1;

#获取items
    foreach ($categoryInfo['itemList'] as $itemOne) {
//        $filter = array(array('item_id', '=', $itemOne['id']), array('category_id', '=', $categoryId), array('customer_id', '=', $customerId));
//        $categoryItemsSql = SqlBuilderNamespace::buildSelectSql('category_items', array('item_id'), $filter);
//        $categoryItems = DBMysqlNamespace::getOne($GLOBALS['handle'], $categoryItemsSql);
//        if ($categoryItems) {
        $itemId[] = $itemOne['id'];
//        }
    }
    $columnsAndValues['items'] = implode(",", $itemId);
//判断是否存在
    $filter = array(array('customer_id', '=', $columnsAndValues['customer_id']), //
        array('category_id', '=', $columnsAndValues['category_id']),
        array('module_id', '=', $columnsAndValues['module_id']),
        array('start_version', '=', $startVersion),
    );
    if ($virtualId) {
        $filter[] = array('virtual_id', '=', $virtualId);
    }
    $selectSql = SqlBuilderNamespace::buildSelectSql('client_category_item_conf', 'id', $filter);
    $row = DBMysqlNamespace::getOne($GLOBALS['handle'], $selectSql);
    if ($row > 0) {
//update 
        $sql = SqlBuilderNamespace::buildUpdateSql('client_category_item_conf', $columnsAndValues, array(array('id', '=', $row)));
        DBMysqlNamespace::execute($GLOBALS['handle'], $sql);
    } else {
//insert
        $sql = SqlBuilderNamespace::buildInsertSql('client_category_item_conf', $columnsAndValues);
        DBMysqlNamespace::execute($GLOBALS['handle'], $sql);
    }
}

function insertItem($item, $customerId, $categoryId, $startVersion, $virtualId) {
    $columnsAndValues = array();
    $itemId = array();
    $columns = array_keys($item);
//查看是否在设定的字段内
    foreach ($columns as $column) {
        if (!in_array($column, $GLOBALS['itemColumns'])) {
            var_dump($column);
            var_dump($customerId);
            var_dump($categoryId);
            die;
        }
    }
    $columnsAndValues['customer_id'] = $customerId;
    $columnsAndValues['category_id'] = $categoryId;
    $columnsAndValues['virtual_id'] = $virtualId;
    $columnsAndValues['status'] = 1;
    $columnsAndValues['item_id'] = $item['id'];
    if (isset($item['title'])) {
        $columnsAndValues['title'] = $item['title'];
    }
    if (isset($item['shortTitle'])) {
        $columnsAndValues['short_title'] = $item['shortTitle'];
    }
    if (isset($item['imgUrl'])) {
        $columnsAndValues['img_url'] = $item['imgUrl'];
    }
    if (isset($item['dataSource'])) {
        $columnsAndValues['data_source'] = $item['dataSource'];
    }
    $columnsAndValues['created'] = time();
    if (isset($item['dataParams']['itemList'])) {
        $subItems = $item['dataParams']['itemList'];
        unset($item['dataParams']['itemList']);
        foreach ($subItems as $subItem) {
            $subId = insertItem($subItem, $customerId, $categoryId, $startVersion, $virtualId);
           $subItemIds[] = $subId;
        }
        $columnsAndValues['data_params'] = json_encode($item['dataParams']);
    } else {
        $columnsAndValues['data_params'] = json_encode($item['dataParams']);
    }
    if (isset($item['ext'])) {
        $columnsAndValues['ext'] = $item['ext'];
    }
//    if (!empty($categoryInfo['ext'])) {
//        var_dump($customerId);
//        var_dump($categoryId);
//        die;
//    }
    if (isset($item['imgUrls'])) {
        $columnsAndValues['ext'] ['imgUrls'] = $item['imgUrls'];
    }
    if (isset($columnsAndValues['ext'])) {
        $columnsAndValues['ext'] = json_encode($columnsAndValues['ext']);
    }
// 判断是否存在
    $filter = array(array('customer_id', '=', $columnsAndValues['customer_id']), //
        array('category_id', '=', $columnsAndValues['category_id']),
        array('item_id', '=', $columnsAndValues['item_id']),
    );
    if ($virtualId) {
        $filter[] = array('virtual_id', '=', $virtualId);
    }
    $selectSql = SqlBuilderNamespace::buildSelectSql('category_items', 'id', $filter);
    $row = DBMysqlNamespace::getOne($GLOBALS['handle'], $selectSql);
    if ($row > 0) {
//update 
        $sql = SqlBuilderNamespace::buildUpdateSql('category_items', $columnsAndValues, array(array('id', '=', $row)));
        DBMysqlNamespace::execute($GLOBALS['handle'], $sql);
        return $row;
    } else {
//insert
        $sql = SqlBuilderNamespace::buildInsertSql('category_items', $columnsAndValues);
        return DBMysqlNamespace::insertAndGetID($GLOBALS['handle'], $sql);
    }
}

?>
