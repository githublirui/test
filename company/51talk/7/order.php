<?php

$fhandle = fopen($GLOBALS['now_path'] . "/order.txt", "r");

$base = new BaseModel();
$dbHandle = $base->getDbHandler('lrtest');

while (!feof($fhandle)) {
    $content = fgets($fhandle);
    $datas = explode("	", $content);
    #1.查询agent id
    DBMysqlNamespace::fetch_row($GLOBALS['db_handle'], 'b2s_teach_materials', $condition2); //relate
}
?>
