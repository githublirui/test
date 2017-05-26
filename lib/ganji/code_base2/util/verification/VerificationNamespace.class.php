<?php

/**
 * 配置文件
 */
include_once 'config/Vconfig.php';
/**
 * 针对属性验证的类
 */
include_once 'Attribute.php';

/**
 * 真对发帖验证函数
 * @author 刘宇峰<liuyufeng@ganji.com>
 * @version 1.0
 * @package unit
 * @copyright ganji.com
 *
 */
class VerificationNamespace {

    private static $category;
    private static $category_major;
    private static $conf;
    private static $Att;

    /**
     * 首次调用初始化大类小类ID
     *
     * @param int $category
     * @param int $category_major
     */
    public static function _init($category, $category_major) {
        self::$category = $category;
        self::$category_major = $category_major;
        self::$Att = new Attribute(self::_getConf());
    }

    /**
     * 整体验证的入口
     *
     */
    public static function Check($arr) {
    	$Err = array();
    	$ArrTmp = self::_getConf();
        if (!is_array($arr))
            return false;
        foreach ($arr as $k => $v) {
        	if (!is_array($ArrTmp[$k])) continue;
            $res = self::$Att->$k($v);
            if (false === $res ['state']) {
                $Err ['type'] = $k;
                $Err ['mes'] = $res ['message'];
                $Err ['name'] = $ArrTmp[$k]['name'];
                break;
            }
        }
        return 0 == count($Err) ? true : $Err;
    }

	/**
     * 整体验证的入口
     *
     */
    public static function CheckShowAllError($arr) {
    	$Err = array();
    	$ArrTmp = self::_getConf();
		$r_value = array();
        if (!is_array($arr))
            return false;
        foreach ($arr as $k => $v) {
        	if (!is_array($ArrTmp[$k])) continue;
            $res = self::$Att->$k($v);
            if (false === $res ['state']) {
                $Err ['type'] = $k;
                $Err ['mes'] = $res ['message'];
                $Err ['name'] = $ArrTmp[$k]['name'];
				$r_value[] = $Err;
                //break;
            }
        }
        return 0 == count($r_value) ? true : $r_value;
    }
   

    /**
     * 获得配置信息
     *
     * @return array[boolean]
     */
    private static function _getConf() {
        $key = self::$category . '_' . self::$category_major;
        $filename = CON_PATH_ROOT_PHP . self::$category;
        $ft = @fopen($filename, 'r');
        $content = unserialize(@fread($ft, @filesize($filename)));
        $Conf = $content [$key];
        if (0==count($Conf)){
            $Conf = $content [self::$category . '_1'];
        }
        return isset($Conf) ? $Conf : false;
    }

}

?>
