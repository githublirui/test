<?php

/**
 * @Copyright (c) 2011 Ganji Inc.
 * @file          /code_base2/util/http/HttpNamespace.class.php
 * @author        longweiguo@ganji.com
 * @date          2011-03-30
 *
 * http分析与跳转的一些操作
 */

/**
 * @class http分析与跳转的一些操作
 * 包括分析url、ip，获取$_GET、$_POST、$_REQUEST数据，页面跳转等
 */
class HttpNamespace {

    /**
     * 取得http头信息
     */
    public static function header($header)
    {
        if (empty($header)) {
            return null;
        }

        // Try to get it from the $_SERVER array first
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (isset($_SERVER[$temp]) && $_SERVER[$temp] !== '') {
            return $_SERVER[$temp];
        }

        // This seems to be the only way to get the Authorization header on
        // Apache
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) {
                return $headers[$header];
            }
        }
        return false;
    }

    /**
     *  判断是否是ajax操作的数据
     */
    public static function isAjax()
    {
        return ('XMLHttpRequest' == self::header('X_REQUESTED_WITH'));
    }

    /**
     * 判断页面是否是数据post过来
     * @return boolean
     */
    public static function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     *  判断 http  method 是否为get
     */
    public static function isGet() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }


    private static function _gpcStripSlashes(& $arr) {
        if (is_array ($arr)) {
            foreach ($arr as &$v) {
                self::_gpcStripSlashes ($v);
            }
        } else
            $arr = stripslashes ($arr);
    }

    public static function gpcStripSlashes() {
        if (get_magic_quotes_gpc ()) {
            self::_gpcStripSlashes ($_GET);
            self::_gpcStripSlashes ($_POST);
            self::_gpcStripSlashes ($_COOKIE);
            self::_gpcStripSlashes ($_REQUEST);
        }
        @set_magic_quotes_runtime (0);
    }

    /// 除去url尾部的#号
    /// @param[in] string $url 要修改的url
    /// @return string 返回修改后的url
    private static function makeSafeUrlForRedirect($url) {
        $url = htmlspecialchars_decode($url);
        if (preg_match ('/#$/', $url)) {
            $url = str_replace ('#', '', $url);
        }
        return preg_replace("/[\"\'\n\r<>]+/", "", $url);
    }

    /// 取得当前页面的url
    /// @return string 返回当前页面的url
    public static function getCurrentUrl($show_script_name = NULL) {
        $pageURL = 'http';
        if (! empty ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") $pageURL .= "s";
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . ( $show_script_name ? $_SERVER['SCRIPT_NAME'] : $_SERVER["REQUEST_URI"]);
        } else {
            $pageURL .= $_SERVER["HTTP_HOST"] . ($show_script_name ? $_SERVER['SCRIPT_NAME'] : $_SERVER["REQUEST_URI"]);
        }
        return $pageURL;
    }

    /// 302跳转
    /// @param $url
    public static function redirect($url, $inIframe=false) {
        self::_redirect($url, 302, $inIframe);
    }

    private static function _redirect($url, $code=302, $inIframe=false) {
        $url = self::makeSafeUrlForRedirect($url);
        if (!$inIframe) {
            header("HTTP/1.1 {$code} Moved Temporarily");
            header('Location: ' . $url);
        } else {
            echo '<html>
<head>
<title>redirect</title>
</head>
<body>
<script src="http://sta.ganji.com/cgi/ganji_sta.php?file=ganji" type="text/javascript"></script>
<script type="text/javascript">
GJ.use("talk_to_parent", function(){
    window.location.href = "' . $url . '";
});
</script>
</body>
</html>';
        }
        exit;
    }

    public static function parentRedirect($url) {
        $url = self::makeSafeUrlForRedirect($url);

            echo '<html>
<head>
<title>redirect</title>
</head>
<body>
<script src="http://sta.ganji.com/cgi/ganji_sta.php?file=ganji" type="text/javascript"></script>
<script type="text/javascript">
GJ.use("talk_to_parent", function(){
    GJ.talkToParent.parentRedirect("'.$url.'");
});
</script>
</body>
</html>';
        exit;
    }

    /// 301跳转
    /// @param $url
    public static function redirectPermently($url, $inIframe=false) {
        self::_redirect($url, 301, $inIframe);
    }

    /// 跳转到页面本身
    public static function redirectToSelf() {
        $url = $_SERVER['REQUEST_URI'];
        self::redirect ($url, $inIframe);
    }

    /// 跳转到www域名，用户分机房读写分离，会写数据的页面尽量用www域名
    /// 若当前域名不是www，跳转到www，并添加一个get参数domain，以及在cookie中写入citydomain
    public static function redirectToWww($inIframe=false) {
        $url    = self::getCurrentUrl();
        preg_match("/\/\/([^\.]+)\./", $url, $match);
        if (isset($match[1]) && $match[1] != 'www') {
            $domain = $match[1];
            $url    = preg_replace("/\/\/{$domain}\./", '//www.', $url);
            if (!strpos($url, '?')) {
                $url    .= '?domain=' . $domain;
            }
            else {
                $url    .= '&domain=' . $domain;
            }
            setcookie('citydomain', $domain, time() + 86400 * 365, '/', '.ganji.com');
            self::redirect($url, $inIframe);
            exit;
        }
    }

    /// redirectToWww的反函数，通过判断cookie中的citydomain跳转到城市域名
    public static function redirectToCity($inIframe=false) {
        $url    = self::getCurrentUrl();
        preg_match("/\/\/([^\.]+)\./", $url, $match);
        if (isset($match[1]) && $match[1] == 'www' && $_COOKIE['citydomain']) {
            $domain = $match[1];
            $url    = preg_replace("/\/\/{$domain}\./", "//{$_COOKIE['citydomain']}.", $url);
            self::redirect($url, $inIframe);
            exit;
        }
    }


    /// 获得query_string
    /// @return string
    public static function getQueryString() {
        return $_SERVER['QUERY_STRING'];
    }

    public static function setQueryString($key, $value = null) {

        $querys = $_GET;
        if (is_string($key) && !empty($key) && $value !== '' && $value !== null) {
            $querys[$key] = $value;
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                if (is_string($k) && !empty($k) && $v !== '' && $v !== null) {
                    $querys[$k] = $v;
                }
            }
        }
        $ret = array();
        foreach ($querys as $key => $value) {
            $ret[] = "{$key}={$value}";
        }
        return implode('&', $ret);
    }

    /**
     * 获取POST中的数据
     * @param $key                POST中的key
     * @param $default            如果数据不存在，默认返回的值。默认情况下为false
     * @param $enableHtml        返回的结果中是否允许html标签，默认为false
     * @return string
     */
    public static function getPOST($key, $default = false, $enableHtml = false) {
        if (isset ($_POST[$key])) {
            if(!$enableHtml && is_array($_POST[$key])) {
                foreach($_POST[$key] as $pkey => $pval) {
                    if (is_string($pval)) {
                        $value[$pkey] = strip_tags($pval);
                    } else {
                        $value[$pkey] = $pval;
                    }
                }
                return $value;
            } else {
                return !$enableHtml ? strip_tags($_POST[$key]) : $_POST[$key];
            }
        }
        return $default;
    }

    /**
     * 获取站内安全next
     * @param $next          url中要获取的地址next
     * @param $defualt       如果数据不存在，默认返回的值。默认情况下为 ''
     * @return string        next | ''
     */
    public static function getSafeNext($key = 'next', $default = '') {
        include_once dirname(__FILE__) . '/../../config/WhiteListV5.config.php';
        $next = isset($_GET[$key]) ? $_GET[$key] : $default;
        if ($next && !WhiteListConfigV5::whiteDomain($next)) {
            $next = '/';
        }
        return $next;
    }

    /**
     * 获取GET中的数据
     * @param $key                GET中的key
     * @param $default            如果数据不存在，默认返回的值。默认情况下为false
     * @param $enableHtml        返回的结果中是否允许html标签，默认为false
     * @return string
     */
    public static function getGET($key, $default = false, $enableHtml = false) {
        if (isset ($_GET[$key])) {
            return !$enableHtml ? strip_tags($_GET[$key]) : $_GET[$key];
        }
        return $default;
    }

    /// 获取REQUEST中的数据
    /// @param $key                REQUEST中的key
    /// @param $default            如果数据不存在，默认返回的值。默认情况下为false
    /// @param $enableHtml        返回的结果中是否允许html标签，默认为false
    /// @return string
    public static function getREQUEST($key, $default = false, $enableHtml = false) {
        if (isset ($_REQUEST[$key])) {
            return !$enableHtml ? strip_tags($_REQUEST[$key]) : $_REQUEST[$key];
        }
        return $default;
    }


    /// 获取COOKIE中的数据
    /// @param $key             COOKIE中的key
    /// @param $default         如果数据不存在，默认返回的值。默认情况下为false
    /// @param $enableHtml      返回的结果中是否允许html标签，默认为false
    /// @return string
    public static function getCOOKIE($key, $default = false, $enableHtml = false) {
        if (isset ($_COOKIE[$key])) {
            return !$enableHtml ? strip_tags($_COOKIE[$key]) : $_COOKIE[$key];
        }
        return $default;
    }
    /// 获得当前页面的前一个页面
    /// @param $default    如果没有前一个页面，返回的默认值
    /// @return string
    public static function getReferer($default = false) {
        if (isset ($_SERVER['HTTP_REFERER']))
            return $_SERVER['HTTP_REFERER'];
        else
            return $default;
    }

    /// 获取当前的域名
    public static function getHost() {
        return $_SERVER['HTTP_HOST'];
    }

    /// @param $url string 页面url
    /// @return string 二级域名
    public static function getCityDomain() {
        $domain = '';
        $buf = explode ('.', $_SERVER['HTTP_HOST']);
        if (count ($buf) > 3) {
            $domain = $buf[1];
        } else {
            $domain =  $buf[0];
        }
        if ($domain == 'www') {
            if (!empty($_GET['domain'])) {
                $domain = $_GET['domain'];
            }
            elseif (!empty($_COOKIE['citydomain'])) {
                $domain = $_COOKIE['citydomain'];
            }
        }
        return $domain;
    }

    /**
     * @brief 获取用户ip
     * @param boolean $useInt 是否将ip转为int型，默认为true
     * @param boolean $returnAll 如果有多个ip时，是否会部返回。默认情况下为false
     * @return string|array|false
     */
    public static function getIp($useInt = true, $returnAll=false) {
//        $ip = getenv('HTTP_CLIENT_IP');
//        if($ip && strcasecmp($ip, "unknown") && !preg_match("/192\.168\.\d+\.\d+/", $ip)) {
//            return self::_returnIp($ip, $useInt, $returnAll);
//        }
        // 获取remote_addr_ip
        $remoteAddrIp = false;
        $ip = getenv('REMOTE_ADDR');
        if($ip && strcasecmp($ip, "unknown")) {
            $remoteAddrIp = $ip;
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if($ip && strcasecmp($ip, "unknown")) {
                $remoteAddrIp = $ip;
            }
        }

        // 不是内网私有
        if ($remoteAddrIp && !self::_isPrivateIp($remoteAddrIp)) {
            return self::_returnIp($remoteAddrIp, $useInt, $returnAll);
        }

        $ip = $_SERVER['HTTP_GJ_CLIENT_IP'];
        if($ip && strcasecmp($ip, "unknown")) {
            return self::_returnIp($ip, $useInt, $returnAll);
        }

        $ip = getenv('HTTP_X_FORWARDED_FOR');
        if($ip && strcasecmp($ip, "unknown")) {
            return self::_returnIp($ip, $useInt, $returnAll);
        }

        //存在 remote_addr_ip
        if ($remoteAddrIp) {
            return self::_returnIp($remoteAddrIp, $useInt, $returnAll);
        }
        return false;
    }

    /**
     *
     * @brief 获取客户端port，只有https才能得到真实的REMOTE_PORT，http得到为假port
     * @return  REMOTE_PORT int|''
     */
    public static function getIpPort() {
        return $_SERVER['REMOTE_PORT'] ? $_SERVER['REMOTE_PORT'] : '';
    }

    /**
     * @brief 是否私有ip
     * @param string $ip
     * @return boolean true|是, false|不是
     */
    private static function _isPrivateIp($ip) {
//      私有地址排除应该是如下的地址段：
//A类 10.0.0.0--10.255.255.255
//B类 172.16.0.0--172.31.255.255
//C类 192.168.0.0--192.168.255.255
        $privateIps = array(
            '127.',
            '10.',
            '192.168.',
            // B类
            '172.16.',
            '172.17.',
            '172.18.',
            '172.19.',
            '172.20.',
            '172.21.',
            '172.22.',
            '172.23.',
            '172.24.',
            '172.25.',
            '172.26.',
            '172.27.',
            '172.28.',
            '172.29.',
            '172.30.',
            '172.31.',
        );
        foreach ($privateIps as $rangeIp) {
            $len = strlen($rangeIp);
            if (substr($ip, 0, $len) == $rangeIp) {
                return true;
            }
        }
        return false;
    }

    private static function _returnIp($ip, $useInt, $returnAll) {
        if (!$ip) return false;

        $ips = preg_split("/[，, _]+/", $ip);
        if (!$returnAll) {
            $ip = $ips[count($ips)-1];
            return $useInt ? self::ip2long($ip) : $ip;
        }

        $ret = array();
        foreach ($ips as $ip) {
            $ret[] = $useInt ? self::ip2long($ip) : $ip;
        }
        return $ret;
    }

    /// 对php原ip2long的封装，原函数在win系统下会出现负数
    /// @author zhoufan <zhoufan@staff.ganji.com>
    /// @param string $ip
    /// @return int
    public static function ip2long($ip) {
        return sprintf ('%u', ip2long ($ip));
    }

    /// 对php原long2ip的封装
    /// @author zhoufan <zhoufan@staff.ganji.com>
    /// @param int $long
    /// @return string
    public static function long2ip($long) {
        return long2ip ($long);
    }

    /**
     * ip地址转城市信息
     * @param string $ip
     * @return array
     *  - ip
     *  - ip_begin
     *  - ip_end
     *  - location
     *  - province_id
     *  - province_name
     *  - city_id
     *  - city_name
     *  - city_pinyin
     *  - district_id
     *  - district_name
     */
    public static function ip2City_old($ip) {
        if (!$ip)   return false;
        require_once GANJI_CONF . '/ServiceConfig.class.php';
        require_once dirname(__FILE__)  . '/HttpRequest.class.php';
        
        $service_url    = ServiceConfig::$SERVICE_HOST . '/fcgi/ip2city/query?act=ip2city&ip=' . $ip;
        $ret = HttpRequest::get($service_url, 1);
        if (strtolower(substr($ret , 0 , 5)) == 'error') {
            return false;
        }
        $info   = explode("\t" , $ret);
        return array(
            'ip'            => $ip,
            'ip_begin'      => $info[0],
            'ip_end'        => $info[1],
            'location'      => $info[2],
            'province_id'   => $info[3],
            'province_name' => $info[4],
            'city_id'       => $info[5],
            'city_name'     => $info[6],
            'city_pinyin'   => $info[7],
            'district_id'   => $info[8],
            'district_name' => $info[9],
        );
    }

    /**
     * finagle version of ip2city
     * @param $ip
     * @return array|bool
     */
    public static function ip2city($ip) {
        require_once CODE_BASE2  . '/interface/ip2city/IpToCityInterface.class.php';
        if( !$ip ) return false;
        $service = new IpToCityInterface();
        return $service->ip2city( $ip );
    }

    /**
     * @brief 生成 uuid v4
     * @return string
     */
    public static function uniqidV4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand(0, 0xffff), mt_rand(0, 0xffff),
          mt_rand(0, 0xffff),
          mt_rand(0, 0x0fff) | 0x4000,
          mt_rand(0, 0x3fff) | 0x8000,
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @breif 生成赶集reqid
     * @return string
     */
    public static function getReqId() {
        require_once(dirname(__FILE__).'/../datetime/DateTimeNamespace.class.php');
        $mtime = DateTimeNamespace::getMicrosecond();
        $srt = self::uniqidV4();
        $time = intval($mtime / 1000);
        return sprintf("%s%s", $time, $srt);
    }

}
