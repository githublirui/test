<?php
/**
 * ajax 校验 ，加密
 * Created by PhpStorm.
 * User: zhaozhiqiang
 * Date: 14-7-7
 * Time: 下午7:09
 */
require_once CODE_BASE2 . '/util/des/DesNamespace.class.php';

class HashVerify {
    const VERIFY_CODE = 'ganji!@#';
    /**
     * 创建加密__hash__ 通过时间戳判断有效期
     */
    public static function createEncryptHash() {
        $encrypt = DesNamespace::create(DesNamespace::MODE_DES, self::VERIFY_CODE);
        $_SERVER['GJ_REQUEST_ID'] = isset($_SERVER['GJ_REQUEST_ID']) ? $_SERVER['GJ_REQUEST_ID'] : HttpNamespace::uniqidV4();
        return $encrypt->encrypt($_SERVER['GJ_REQUEST_ID'] . '_' . time());;
    }

    /**
     * 解密 __hash__
     * @param string  $hash 需要解密的字符
     * @param string $time 有效期
     */
    public static function decryptHash($hash, $time = 600) {
        $encrypt = DesNamespace::create(DesNamespace::MODE_DES, self::VERIFY_CODE);
        $code    = $encrypt->decrypt($hash);
        $result  = explode("_",$code);
        if($result[count($result)-1] + $time >= time()) {
            return true;
        }
        return false;
    }
}