<?php

/**
 * @file CommonPostOperateModel.class.php
 * @brief 获取赶集叮咚帖子运营操作类
 * @author lirui1(lirui1@ganji.com)
 * @version 1.0
 * @date 2014-06-26
 */
require_once CLIENT_APP_DATASHARE . '/config/common/CommonDingDongConfig.php';

class CommonPostOperateModel {

    /**
     * 处理帖子操作
     * @param type $para
     */
    public static function operate($param) {
        $type = $param['type'];
        $className = ucfirst(CommonDingDongConfig::$bangStr[$type]) . 'Operate';
        require_once $className . '.class.php';
        $operateObj = new $className($param);
        return $operateObj->operate();
    }

    /**
     * 招聘
     * @param type $param
     */
    private static function _wantedOperate($param) {
        
    }

    /**
     * 二手
     * @param type $param
     */
    private static function _ershoucheOperate($param) {
        
    }

    /**
     * 服务
     * @param type $param
     */
    private static function _servicestoreOperate($param) {
        
    }

    /**
     * 其他身份
     * @param type $param
     */
    private static function _normalUserOperate($param) {
        $result = array();
        $operationType = $param['operationType'];
        switch ($operationType) {
            case CommonDingDongConfig::$REFRESH;
                //刷新
                break;
            case CommonDingDongConfig::$STICK;
                //置顶
                break;
            case CommonDingDongConfig::$STICK_RENEW;
                //置顶续费
                break;
        }
        return $result;
    }

}
