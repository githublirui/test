<?php

//3. 针对指定城市，版本的替换
$cityReplaceItems = CategoryInfoConfig::$CITY_REPLACE_ITEMS;
foreach ($cityReplaceItems as $cityReplaceItem) {
    $replaceItemData = $cityReplaceItem['replaceTtems'];
    $replaceItemData = $replaceItemData[$this->para['customerId']];
    foreach ($replaceItemData as $cityRversion => $replaceVersionItems) {
        //多版本
        if (clientPara::versionCompare($this->para['versionId'], $cityRversion) >= 0) {
            //多个item 替换
            foreach ($replaceVersionItems as $old => $new) {
                if (!is_numeric($old)) {
                    //指定城市替换
                    if (in_array($this->para['cityId'], $cityReplaceItem['cityIds'])) {
                        $itemList[$old] = $data['allItems'][$new];
                    }
                } else {
                    //指定城市显示
                    if (!in_array($this->para['cityId'], $cityReplaceItem['cityIds'])) {
                        unset($itemList[$new]);
                    }
                }
            }
        }
    }
}