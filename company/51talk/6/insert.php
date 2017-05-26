<?php

/**
 * 
 * 生成insert语句
 */
function insertData($datas, $i) {
    $datas[0] = trim($datas[0]);
    $datas[1] = trim($datas[1]);
    $condition1 = array("grade" => $i, "name" => $datas[0]);
    $condition2 = array("grade" => $i, "name" => $datas[1]);
    $row2 = DBMysqlNamespace::fetch_row($GLOBALS['db_handle'], 'b2s_teach_materials', $condition2); //relate
    if (!$row2) {
        $insertData = array(
            'grade' => $i,
            'name' => $datas[1],
        );
        $relateId = DBMysqlNamespace::insert($GLOBALS['db_handle'], 'b2s_teach_materials', $insertData);
    } else {
        $relateId = $row2['id'];
    }
    $row1 = DBMysqlNamespace::fetch_row($GLOBALS['db_handle'], 'b2s_teach_materials', $condition1);
    if (!$row1) {
        $insertData = array(
            'grade' => $i,
            'name' => $datas[0],
            'relate_id' => $relateId,
        );
        DBMysqlNamespace::insert($GLOBALS['db_handle'], 'b2s_teach_materials', $insertData);
    }
}

$fhandle = fopen($GLOBALS['now_path'] . "/1.txt", "r");
while (!feof($fhandle)) {
    $content = fgets($fhandle);
    $datas = explode("	", $content);
    if (strpos($datas[0], "一年级") !== false) {
        for ($i = 1; $i <= 6; $i++) {
            insertData($datas, $i);
        }
    } else if (strpos($datas[0], "三年级") !== false) {
        for ($i = 3; $i <= 6; $i++) {
            insertData($datas, $i);
        }
    } else if (strpos($datas[0], "五年级") !== false) {
        for ($i = 5; $i <= 6; $i++) {
            insertData($datas, $i);
        }
    }
}

fclose($fhandle);
