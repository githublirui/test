<?php

$fhandle = fopen($GLOBALS['now_path'] . "/1.txt", "r");

while (!feof($fhandle)) {
    $content = fgets($fhandle);
    $datas = explode("	", $content);
    $datas[0] = trim($datas[0]);
    $datas[1] = trim($datas[1]);

    $condition1 = "name='{$datas[0]}'";

    $row1 = DBMysqlNamespace::fetch_row($GLOBALS['db_handle'], 'b2s_teach_materials', $condition1); //relate
    if (!$row1) {
        file_put_contents($GLOBALS['now_path'] . '/tmp.log', "\n", FILE_APPEND);
    }

    $condition2 = "name='{$datas[1]}' and grade={$row1['grade']}";
    $row2 = DBMysqlNamespace::fetch_all($GLOBALS['db_handle'], 'b2s_teach_materials', $condition2); //relate

    if (!$row2) {
        file_put_contents($GLOBALS['now_path'] . '/tmp1.log', "\n", FILE_APPEND);
    }

    $updateData = array(
        'status' => 1,
        'relate_id' => $row2['id'],
    );
    DBMysqlNamespace::update_by_id($GLOBALS['db_handle'], 'b2s_teach_materials', $row1['id'], $updateData);
    echo ".";
}
?>
