<?php

defined('APP_BACKEND') OR define('APP_BACKEND', dirname(__FILE__) . '/../'); //app根目录

class BaseConf {

    public static function getWarnMsg($checkUser = true) {
        $envName = GlobalConfig::getLocalEnv();
        $envStr = GlobalConfig::getLocalEnv() == 'test' ? '测试环境' : '线上环境';
        $warnString = '当前操作为: <span style="color:red;">' . $envStr . '</span>';
        return $warnString;
    }

    public static function getCurrentUserEmail() {
        $sessUesrInfo = SessionNamespace::getValue('backend-user-info');
        return $sessUesrInfo['Email'];
    }

}
