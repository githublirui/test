<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class PinyinNamespace{
    /* {{{ chinese2pinyin */
    /**
     * @brief 汉字转拼音
     *
     * @param $ch string 要获得拼音的汉字
     * @param $offset int 从第几个字开始取拼音
     * @param $length int 取多少个字的拼音
     * @param $encoding string 编码
     * @param $yindiao  boolean true =>带音调 false=>不带
     *
     * @returns   
     */

    static private $pinyinConfig;

    public static function chinese2pinyin($ch, $offset=0, $length=1, $encoding='utf-8', $yindiao = true) {
        $tmpArr = Array();
        for ($i = 0; $i < $length; $i+=1) {
            $c = mb_substr($ch, $i, 1, $encoding);
            $uni = mb_convert_encoding($c, 'UCS-2', $encoding);
            $unicode = (ord($uni[0])<<8) +  ord($uni[1]);
            $pinyin_dict = self::getPinyinConfig();
            if (array_key_exists($unicode, $pinyin_dict)) {
                $tmpArr[$i] = $pinyin_dict[$unicode][0];
                if (false === $yindiao) {
                    $tmpArr[$i] = substr($tmpArr[$i], 0, -1);
                }
            } else {
                $tmpArr[$i] = "zzz";
            }
        }
        return join('', $tmpArr);
    }//}}}

    static function getPinyinConfig(){
        $key = 'APC_PINYIN_CONFIG_v2';
        $cache = CacheNamespace::createCache(CacheNamespace::MODE_APC);
        if(!self::$pinyinConfig){
            $pinyinConfig = $cache->read($key);
            self::$pinyinConfig = $pinyinConfig;
        }
        if ($pinyinConfig===false || $pinyinConfig===null && !self::$pinyinConfig) {
            require_once dirname(__FILE__).'/PinyinConfig.class.php';
            //存放30天
            $cache->write($key,PinyinConfig::$PINYIN_DICT,30*86400);
            self::$pinyinConfig = PinyinConfig::$PINYIN_DICT;
            return self::$pinyinConfig;
        }
        return self::$pinyinConfig;
    }
}
