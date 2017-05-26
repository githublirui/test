<?php

include_once dirname(__FILE__) . '/../../lib/common.php';

$select = SqlBuilderNamespace::buildSelectSql('category_itemlv1', '*');

$baseModel = new BaseModel();
$handle = $baseModel->getDbHandler('lrtest');
#1. 生成首页应用列表
$customerId = 705;
$categoryId = 0;
$versionId = '5.6.0';

$lv1Filter = array(
    array('path', '=', $customerId . '_' . $categoryId),
    array('status', '=', '1'),
);

$result = array();
$lv1Sql = SqlBuilderNamespace::buildSelectSql('category_itemlv1', '*', $lv1Filter);
$lv1s = DBMysqlNamespace::getAll($handle, $lv1Sql);
$category_lv1 = array();
foreach ($lv1s as $lv1) {
    $category_lv1['id'] = $lv1['item_id'];
    $category_lv1['title'] = $lv1['title'];
    $category_lv1['mode'] = $lv1['mode'];
    //额外字段
    $exts = json_decode($lv1['ext'], true);
    if (is_array($exts) && !empty($exts)) {
        foreach ($exts as $extk => $extv) {
            $category_lv1[$extk] = $extv;
        }
    }
    //获取item list
    /**
     * 获取二级应用列表
     * @param type $lv1ItemId
     */
    $lv2Filter = array(
        array('lv1_id', '=', $lv1['id']),
        array('status', '=', '1'),
    );
    $lv2Sql = SqlBuilderNamespace::buildSelectSql('category_itemlv2', '*', $lv2Filter);
    $lv2s = DBMysqlNamespace::getAll($handle, $lv2Sql);
    foreach ($lv2s as $lv2) {
        $ItemList = array();
        $ItemList['id'] = $lv2['item_id'];
        $ItemList['title'] = $lv2['title'];
        $ItemList['imgUrl'] = $lv2['imgUrl'];
        $ItemList['jumpType'] = $lv2['jumpType'];
        $ItemList['dataParams'] = (array) json_decode($lv2['dataParams'], true);
        //额外字段
        $exts = json_decode($lv2['ext'], true);
        if (is_array($exts) && !empty($exts)) {
            foreach ($exts as $extk => $extv) {
                $ItemList[$extk] = $extv;
            }
        }
        $category_lv1['itemList'][] = $ItemList;
    }
    $categoryInfo[] = $category_lv1;
}
$result['info'] = $categoryInfo;
$result['version'] = time();

//上线
#1. 查询线上是否存在一级
$lv1Filter = array(
    array('path', '=', $customerId . '_' . $categoryId),
    array('status', '=', '1'),
);
$lv1Sql = SqlBuilderNamespace::buildSelectSql('category_itemlv1', '*', $lv1Filter);
$lv1s = DBMysqlNamespace::getAll($handle, $lv1Sql);
foreach ($lv1s as $lv1) {
    $lv1FilterOnline = array(
        array('path', '=', $lv1['path']),
        array('item_id', '=', $lv1['item_id']),
        array('status', '=', '2'),
    );
    $lv1OnlineSql = SqlBuilderNamespace::buildSelectSql('category_itemlv1', '*', $lv1FilterOnline);
    $lv1Online = DBMysqlNamespace::getRow($handle, $lv1OnlineSql);
    if (is_array($lv1Online) && !empty($lv1Online)) {
        //update
        $filter = array(
            array('id', '=', $lv1Online['id']),
        );
        $updateArr = array(
            'title' => $lv1['title'],
            'mode' => $lv1['mode'],
            'ext' => $lv1['ext'],
            'updated' => time(),
        );
        $lv1OnlineSql = SqlBuilderNamespace::buildUpdateSql('category_itemlv1', $updateArr, $filter);
    } else {
        //insert
        $insertArr = array(
            'path' => $lv1['path'],
            'item_id' => $lv1['item_id'],
            'title' => $lv1['title'],
            'mode' => $lv1['mode'],
            'ext' => $lv1['ext'],
            'status' => 2,
            'created' => time(),
            'updated' => time(),
        );
        $lv1OnlineSql = SqlBuilderNamespace::buildInsertSql('category_itemlv1', $insertArr);
    }
    DBMysqlNamespace::execute($handle, $lv1OnlineSql);
    #1.lv2上线
    $lv2Filter = array(
        array('lv1_id', '=', $lv1['id']),
        array('status', '=', '1'),
    );
    $lv2Sql = SqlBuilderNamespace::buildSelectSql('category_itemlv2', '*', $lv2Filter);
    $lv2s = DBMysqlNamespace::getAll($handle, $lv2Sql);
    foreach ($lv2s as $lv2) {
        $lv2FilterOnline = array(
            array('lv1_id', '=', $lv2['lv1_id']),
            array('item_id', '=', $lv2['item_id']),
            array('status', '=', '2'),
        );
        $lv2OnlineSql = SqlBuilderNamespace::buildSelectSql('category_itemlv2', '*', $lv2FilterOnline);
        $lv2Online = DBMysqlNamespace::getRow($handle, $lv2OnlineSql);
        if (is_array($lv2Online) && !empty($lv2Online)) {
            //update
            $filter = array(
                array('id', '=', $lv2Online['id']),
            );
            $updateArr = array(
                'title' => $lv2['title'],
                'imgUrl' => $lv2['imgUrl'],
                'jumpType' => $lv2['jumpType'],
                'dataParams' => $lv2['dataParams'],
                'ext' => $lv2['ext'],
                'updated' => time(),
            );
            $lv2OnlineSql = SqlBuilderNamespace::buildUpdateSql('category_itemlv2', $updateArr, $filter);
        } else {
            //insert
            $insertArr = array(
                'lv1_id' => $lv2['lv1_id'],
                'item_id' => $lv2['item_id'],
                'title' => $lv2['title'],
                'imgUrl' => $lv2['imgUrl'],
                'jumpType' => $lv2['jumpType'],
                'dataParams' => $lv2['dataParams'],
                'ext' => $lv2['ext'],
                'status' => 2,
                'created' => time(),
                'updated' => time(),
            );
            $lv2OnlineSql = SqlBuilderNamespace::buildInsertSql('category_itemlv2', $insertArr);
        }
        DBMysqlNamespace::execute($handle, $lv2OnlineSql);
    }
}

    