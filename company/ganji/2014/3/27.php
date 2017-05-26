<?php

$s = file_get_contents('705_0');
$content = unserialize($s);

//var_dump($content['info'][0]['itemList']);
//die;

function checkItemListByClientVersion($itemList) {
    $result = array();
    $version = 123;
    $versionItemList = array(
        '0' => array('0.1.1'),
        '5.1.0' => array(),
    );
    //如果有配置该版本的item则返回该版本的，否则返回默认
    if (isset($versionItemList[$version])) {
        $versionItemListIds = $versionItemList[$version];
    } else {
        $versionItemListIds = $versionItemList[0];
    }
    foreach ($versionItemListIds as $versionItemListId) {
        foreach ($itemList as $item) {
            if ($versionItemListId == $item['id']) {
                $result[] = $item;
            }
        }
    }
    return $result;
}

//var_dump(checkItemListByClientVersion($content['info'][0]['itemList']));
//die;
$pageType = 1;
$pagePosition = 3;
$categoryId = 55354;
$majorCategoryId = 1;

$categoryIdLength = 6; //大类id最大长度
$majorCategoryIdLength = 6; //小类id最大长度
$categoryIdPosition = implode(array_pad(array(), $categoryIdLength - strlen($categoryId), '0')) . $categoryId;
$majorCategoryIdPosition = implode(array_pad(array(), $majorCategoryIdLength - strlen($majorCategoryId), '0')) . $majorCategoryId;
$positionId = $pageType . $categoryIdPosition . $majorCategoryIdPosition . $pagePosition;

$pageType = (int) substr($positionId, 0, 1);
$categoryId = (int) substr($positionId, 1, 6);
$majorCategoryId = (int) substr($positionId, 7, 6);
$pagePosition = (int) substr($positionId, 13, 1);


$positionId = intval($pageType) . sprintf("%1$06d", $categoryId) . sprintf("%1$06d", $majorCategoryId) . $pagePosition;

var_dump($positionId);
?>
