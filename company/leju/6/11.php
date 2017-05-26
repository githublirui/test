<?php

/**
 * 获取iP地址函数
 * @return type
 */
function fetch_alt_ip() {
    $alt_ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $alt_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        // try to avoid using an internal IP address, its probably a proxy
        $ranges = array(
            '10.0.0.0/8' => array(ip2long('10.0.0.0'), ip2long('10.255.255.255')),
            '127.0.0.0/8' => array(ip2long('127.0.0.0'), ip2long('127.255.255.255')),
            '169.254.0.0/16' => array(ip2long('169.254.0.0'), ip2long('169.254.255.255')),
            '172.16.0.0/12' => array(ip2long('172.16.0.0'), ip2long('172.31.255.255')),
            '192.168.0.0/16' => array(ip2long('192.168.0.0'), ip2long('192.168.255.255')),
        );
        foreach ($matches[0] AS $ip) {
            $ip_long = ip2long($ip);
            if ($ip_long === false) {
                continue;
            }

            $private_ip = false;
            foreach ($ranges AS $range) {
                if ($ip_long >= $range[0] AND $ip_long <= $range[1]) {
                    $private_ip = true;
                    break;
                }
            }

            if (!$private_ip) {
                $alt_ip = $ip;
                break;
            }
        }
    } else if (isset($_SERVER['HTTP_FROM'])) {
        $alt_ip = $_SERVER['HTTP_FROM'];
    }

    return $alt_ip;
}

$ip = fetch_alt_ip(); //获取ip地址
$city_redirect = 'http://www.baidu.com'; //跳转到另外一个网站
$url = "http://ip.taobao.com/service/getIpInfo.php?ip=123.124.163.250";
$data = json_decode(file_get_contents($url), true); //调用淘宝接口获取信息 
if (is_array($data) && $data['data']['area_id'] == '100000') {//如果不是北京跳转到另外一个页面
    header("Location: " . $city_redirect); //跳转
    exit;
}
?>