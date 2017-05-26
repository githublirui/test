<?php

/**
 * 
 * 绑定手机号的用户统计
 */
ini_set('display_errors', 0);

require_once dirname(__FILE__) . '/../config/config.inc.php';
require_once dirname(__FILE__) . '/../config/ConfigUcRequest.class.php';
require_once CODE_BASE2 . '/app/mobile_client/model/baseWidgetModel.class.php';
include_once CODE_BASE2 . '/interface/uc/UserInterface.class.php';

$base = new baseWidgetModel();
$handle = $base->getDbHandler();

$f_handle = fopen("all_line.txt", "r");

while (!feof($f_handle)) {
    $ids = fgets($f_handle);
    $sql = "SELECT loginID FROM  user_mob_device WHERE installID in "
            . "(" . $ids . ");";
    #1. 查询loginid
    $loginids = DBMysqlNamespace::getAll($handle, $sql);
    $binduser = array(); //已经绑定过的user
    foreach ($loginids as $loginid) {
        $id = $loginid['loginID'];
        #通过logid查询是否绑定
        $userInfo = UserInterface::getUser($id);
        if (is_array($userInfo) //存在这个userinfo
                && $userInfo['phone'] > 0 #并且手机号不为0
                && !in_array($id, $binduser)) { #并且不在绑定记录之内
            $line_data = $id . "," . $userInfo['phone'] . "\r\n";
            file_put_contents('binduser.txt', $line_data, FILE_APPEND); //绑定手机号存入记录
            $binduser[] = $id; //添加绑定记录
            echo '.';
            flush();
        }
    }
}