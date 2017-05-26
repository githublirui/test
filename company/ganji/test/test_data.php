<?php

/**
 * 接口测试相关接口和默认值配置文件
 * @author chenyihong <chenyihong@ganji.com>
 * @version 2013/08/16
 * @copyright ganji.com 2013
 */
class InterfacePara {

    public static $TEST_DATAS = array(
        'GetLastCategory' => array(
            'categoryId' => 14,
            'cityScriptIndex' => 0,
        ),
        'GetLastCategories' => array(
            'cityScriptIndex' => 0,
        ),
        'GetPost' => array(
            'cityScriptIndex' => 0,
            'categoryId' => 7,
            'majorCategoryScriptIndex' => 0,
            'postID' => 13997745,
            'puid' => 92357170,
            'desc' => '可以单独根据puid查询， 也可以填写完整的大类、小类、postID'
        ),
        'GetPostByPuid' => array(
            'puid' => 92357170,
        ),
        'ManageUserFavorite' => array(
            'act' => 1,
            'loginId' => '50024678',
            'puid' => '90395089',
        ),
        'UserFavorites' => array(
            'ucenterUserID' => 50024678,
            'allPuid' => 0,
        ),
        'GetMajorCategoryFilters' => array(
            'categoryId' => 14,
        ),
        'VotePost' => array(
            'categoryId' => '14',
            'majorCategoryScriptIndex' => '5',
            'postId' => '9882552',
            'agent' => 0,
            'cityScriptIndex' => 0,
            'reasonId' => 1,
            'content' => '帖子信息不正确',
        ),
        'GetPostsByUserId' => array(
            'ucUserId' => '50001336',
            'isDeleted' => 0,
            'pageIndex' => 1,
            'pageSize' => 10,
        ),
        'YJJY' => array(
            'categoryScriptIndex' => 1, //二手script_index 为1
            'email' => 'chenyihong@ganji.com',
            'contents' => 'This is test contnet.',
            'username' => 'jingling',
        ),
        'CommonCategoryInfo' => array(
            'cityId' => 12,
            'categoryId' => 5,
            'majorCategoryScriptIndex' => -1,
            'virtualId' => 1,
        ),
        'CommonConsultList' => array(
            'categoryId' => 7,
            'pageIndex' => 0,
            'pageSize' => 10,
        ),
        //users 部分接口
        'GetUserInfo' => array(
            'loginId' => '50024678',
        ),
        'GetBackPassword' => array(
            'method' => 1,
            'step' => 1,
            'methodKey' => '13282987627',
            'phoneCode' => '2456',
            'password' => '123456a',
            'imageCode' => '3AAA',
            'desc' => '<img src="http://m.ganjistatic3.com/ajax.php?module=mclient_captcha&dir=common&type=get_password&tag=phone&nocach=1367034410&uuid=649B0B50A0C8D5ABF8A895D9BCDA343B&w=120&h=35" >',
        ),
        'userLogin' => array(
            'loginName' => 'lirui3',
            'password' => 'lirui123',
//            'captcha' => '3AAA',
//            'desc' => '<img src="http://m.ganjistatic3.com/ajax.php?module=mclient_captcha&dir=common&type=login&tag=phone&nocach=XXXX&uuid=649B0B50A0C8D5ABF8A895D9BCDA343B&w=120&h=35" >',
        ),
        'register' => array(
            'loginName' => 'testname21',
            'password' => '123456c',
            'captcha' => '3AAA',
            'desc' => '<img src="http://m.ganjistatic3.com/ajax.php?module=mclient_captcha&dir=common&type=register&tag=705&nocach=XXXX&uuid=649B0B50A0C8D5ABF8A895D9BCDA343B&w=120&h=35" >',
        ),
        'UserPhoneAuth' => array(
            'getCodeType' => '1',
            "method" => "GetCode",
            "loginId" => "50024678",
            "phone" => "13282987627",
            "code" => '8888',
        ),
        'GetOperationNotice' => array(
            'cityId' => '12',
        ),
        'TestInterface' => array(
            'arg' => 1,
        ),
    );
    //配置在Users 模块下的接口
    public static $USERS_INTERFACE = array(
        'GetUserInfo',
        'GetBackPassword',
        'register',
        'UserPhoneAuth',
        'userLogin',
        'TestInterface',
    );
    //配置在push 模块下的接口
    public static $PUSH_INTERFACE = array(
        'GetOperationNotice',
    );

    public static function getParaConfig() {
        self::$TEST_DATAS['GetBackPassword']['desc'] = str_replace('XXXX', time(), self::$TEST_DATAS['GetBackPassword']['desc']);
        return json_encode(self::$TEST_DATAS, JSON_HEX_TAG);
    }

    /**
     * 获取接口所属模块
     */
    public static function getInterfaceBelongHost($interfaceName) {
        if (in_array($interfaceName, self::$USERS_INTERFACE)) {
            return '/users';
        } else if (in_array($interfaceName, self::$PUSH_INTERFACE)) {
            return '/push';
        } else {
            return '/datashare';
        }
    }

    public static function getInterfaceDefaultPara($interfaceName) {
        $jsonStr = json_encode(self::$TEST_DATAS[$interfaceName]);
        $jsonStr = str_replace(',', "\n,", $jsonStr);
        return $jsonStr;
    }

}

if (isset($_POST['interfaceName'])) {
    echo InterfacePara::getInterfaceDefaultPara($_POST['interfaceName']);
    exit;
}
