<?php
/**
 * @brief COOKIE操作类
 * @author    duxiang duxiang@ganji.com
 * @copyright (c) 2013 Ganji Inc.
 * @date      时间: 2013-6-9:下午01:13:22
 * @version   1.0
 * @todo 对指定域名做 允许cookie的限制
 */
class CookieNamespace {
    /**
     * @brief 设置cookie, 参数 php setcookie
     * @param string $name
     * @param $value
     * @param $expire
     * @param $path
     * @param $domain
     * @param $secure
     * @param $httponly
     * @return bool
     */
    public static function setcookie ($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null) {
    	return setcookie ($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * @brief 删除cookie, 参数 see setcookie
     * @param string $name 必须
     * @param $path
     * @param $domain
     * @param $secure
     * @param $httponly
     * @return bool
     * @example
     *   CookieNamespace::deleteCookie('abc', "/", '.ganji.com');
     */
    public static function deleteCookie ($name, $path = null, $domain = null, $secure = null, $httponly = null) {
        return setcookie ($name, 'deleted', 1, $path, $domain, $secure, $httponly);
    }

    /**
     * @brief 获取一个cookie
     * @param string $key
     * @param $default
     * @return mix
     */
    public static function getValue($key, $default = null) {
        return (array_key_exists($key, $_COOKIE) ? $_COOKIE[$key] : $default);
    }
}
