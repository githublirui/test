<?php

include '../../conn.php';
include '../../func.php';
$pdo_instance = MyPDO::getInstance();

/**
 * 
 * 装修万事通文章目录全部转换成二级文章目录
 */
#第一步，先插入第一级目录

$sql_lv1 = "select * from zxwst_wd where pid=0";
$wd_lv1s = $pdo_instance->fetchAll($sql_lv1);

foreach ($wd_lv1s as $wd_lv1) {
    $select_lv1 = "select count(*) as num from zxwst_wd_lv2 where id='" . $wd_lv1['id'] . "'";
    $count = $pdo_instance->fetchOne($select_lv1);
    if ($count['num'] <= 0) {
        insertWdLv2($wd_lv1); #插入一级目录
    }
    #插入二级目录
    $lv2s = getLv2Wd($wd_lv1['id']);
    foreach ($lv2s as $lv2) {
        insertWdLv2($lv2);
        #1.获取二级目录的文章
        $zxwst_content_sql = "select * from zxwst_content_wz where wid=" . $lv2['id'];
        $zxwst_contents = $pdo_instance->fetchAll($zxwst_content_sql);
        foreach ($zxwst_contents as $zxwst_content) {
            $zxwst_content['wid'] = $lv2['id'];
            insertContent($zxwst_content);
        }
        $slv2s = getSonLv2($lv2['id']); #2.获取二级目录下所有有文章的目录
        foreach ($slv2s as $slv2) {#插入二级目录下的文章
            $zxwst_content_sql = "select * from zxwst_content_wz where wid=" . $slv2['id'];
            $zxwst_contents = $pdo_instance->fetchAll($zxwst_content_sql);
            foreach ($zxwst_contents as $zxwst_content) {
                $zxwst_content['wid'] = $lv2['id'];
                insertContent($zxwst_content);
            }
        }
    }

    #插入二级目录所有的目录的二级文章到二级目录
//    $sons = getSonLv2($wd_lv1['id']);
//    foreach ($sons as $son) {
//        $son['pid'] = $wd_lv1['id'];
//        $son['path'] = $wd_lv1['id'] . '-' . $son['id'];
//        insertWdLv2($son);
//    }
}

/**
 * 插入wd表
 * @global type $pdo_instance
 * @param type $data
 */
function insertWdLv2($data) {
    global $pdo_instance;
    $insert_wd_sql = "insert into zxwst_wd_lv2 (`id`,`word`,`pid`,`path`,`explain`,`order`,`subip`,`url`) 
values ('" . $data['id'] . "','" . $data['word'] . "','" . $data['pid'] . "','" . $data['path'] . "','" . $data['explain'] . "','" . $data['order'] . "','" . $data['subip'] . "','" . $data['url'] . "');            
";
    $pdo_instance->run($insert_wd_sql);
}

/**
 * 插入文章表
 * @global type $pdo_instance
 * @param type $data
 */
function insertContent($data) {
    global $pdo_instance;
    $insert_wd_sql = "insert into zxwst_content_wz_lv2 (`id`,`wid`,`title`,`content`,`order`,`subip`) 
values ('" . $data['id'] . "','" . $data['wid'] . "','" . $data['title'] . "','" . $data['content'] . "','" . $data['order'] . "','" . $data['subip'] . "');            
";
    $pdo_instance->run($insert_wd_sql);
}

/*
 * 获取二级目录
 */

function getLv2Wd($p_wd_id) {
    global $pdo_instance;
    $sql = "select * from zxwst_wd where pid=" . $p_wd_id . " and url=0";
    $son_wds = $pdo_instance->fetchAll($sql);
    return $son_wds;
}

/**
 * 递归获取分类下的所有有文章的子分类
 * @global type $pdo_instance
 * @param type $p_wd_id
 */
function getSonLv2($p_wd_id) {
    global $pdo_instance;
    static $result = array();
    $sql = "select * from zxwst_wd where pid=" . $p_wd_id . " and url=0";
    $son_wds = $pdo_instance->fetchAll($sql);
    if (count($son_wds) > 0) {
        $result = array_merge($result, $son_wds);
        foreach ($son_wds as $son_wd) {
            getSonLv2($son_wd['id']);
        }
    }
    return $result;
}