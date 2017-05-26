<?php
/**
 * @brief     简单的校验码，用于校验数据是否被篡改，校验码不过期，可多次使用
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 * @author    zhangshenjia <zhangshenjia@ganji.com>
 * @since     2014-4-16
 */
require_once dirname(__FILE__) . '/CheckSum.class.php';

class SimpleCheckSum extends CheckSum {

    /**
     * 校验用私钥
     * @var string
     */
    const CHECK_SECRET = 'y5FTEZkxc3EsQj';

    /**
     * 为需要校验的数据生成校验码
     * @param mixed data
     * @return string
     */
     public static function generate($data) {
         return md5(serialize($data) . self::CHECK_SECRET);
     }

    /**
     * 校验数据及对应的校验码是否匹配
     * @param mixed $data
     * @param string $checkSum
     * @return boolean
    */
    public static function validate($data, $checkSum) {
        return (string) $checkSum == self::generate($data);
    }
}