<?php
/**
 * @brief     校验码基类（用于校验数据是否被篡改）
 * @copyright Copyright (c) 2005-2012 GanJi Inc. (http://www.ganji.com)
 * @author    zhangshenjia <zhangshenjia@ganji.com>
 * @since     2014-4-16
 */

abstract class CheckSum {

    /**
     * 为需要校验的数据生成校验码（需在子类中实现）
     * @param mixed data
     * @return string
     */
     abstract public static function generate($data);

    /**
     * 校验数据及对应的校验码是否匹配（需在子类中实现）
     * @param mixed $data
     * @param string $checkSum
     * @return boolean
    */
    abstract public static function validate($data, $checkSum);
}