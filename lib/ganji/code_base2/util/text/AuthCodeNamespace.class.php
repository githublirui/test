<?php

/**
 * 字串加密与解密
 *
 * @author longweiguo duxiang@ganji.com
 * @file  code_base2/util/text/AuthCodeNamespace.class.php
 * @date 2009-09-08
 * @desc  注意encode(), decode() 必须传递  encryptKey
 */

class AuthCodeNamespace {
    /**
     * @brief 加密
     */
    const ENCODE = 1;
    
    /**
     * @brief 加密
     */
    const DECODE = 2;
    
    /**
     * @brief 当前TYPE
     */
    private static $_TYPE = 0;
    
    /**
     * @brief 加密字符串
     * @param $str
     * @param $encryptKey
     * @return see _get()
     */
    public static function encode($str, $encryptKey) {
        self::$_TYPE = self::ENCODE;
        return self::_get($str, $encryptKey);
    }

    /**
     * @brief 解密字符串
     * @param string $str
     * @param string $encryptKey 加密key
     * @return see _get()
     */
    public static function decode($str, $encryptKey) {
        self::$_TYPE = self::DECODE;
        return self::_get($str, $encryptKey);
    }
    
    /**
     * @brief 获取key 
     * @param string $str
     * @param string $encryptKey 加密key
     * @return string 
     *   - 空字符串 
     *   - 有效的字符串
     */
    private static function _get($str, $encryptKey) {
        $str        = (string) $str;
        $encryptKey = (string) $encryptKey;
        $encryptKey = trim($encryptKey);
        
        if (strlen($encryptKey) < 1) {
            return '';
        }
        
        $key        = md5($encryptKey);
        $key_length = strlen( $key );

        $str = self::$_TYPE == self::DECODE 
             ? base64_decode( $str ) 
             : substr( md5( $str . $key ), 0, 8 ) . $str;
        $str_length = strlen( $str );
        
        $rndkey = $box = array ();
        
        for ($i = 0; $i <= 255; $i ++) {
            $rndkey[$i] = ord( $key[$i % $key_length] );
            $box[$i] = $i;
        }
        
        for ($j = $i = 0; $i < 256; $i ++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        
        $result = '';
        
        for ($a = $j = $i = 0; $i < $str_length; $i ++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr( ord( $str[$i] ) ^ ($box[($box[$a] + $box[$j]) % 256]) );
        }
        
        if (self::$_TYPE == self::DECODE) {
            if (substr( $result, 0, 8 ) == substr( md5( substr( $result, 8 ) . $key ), 0, 8 )) {
                return substr( $result, 8 );
            } else {
                return '';
            }
        } else {
            return str_replace( '=', '', base64_encode( $result ) );
        }
    }
}