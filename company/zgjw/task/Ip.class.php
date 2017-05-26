<?php

include 'UtilsIpCheck.class.php';

/**
 * 关于Ip的一些函数
 * @autor lirui
 */
class Ip {

    /**
     * Fetches an alternate IP address of the current visitor, attempting to detect proxies etc.
     *
     * @return	string
     */
    public static function fetch_alt_ip() {
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

    /**
     * 判断当前iP是否在范围内
     * @param @array  eg: array('192.168.2.1','192.168.2.1 - 192.168.2.20'); 
     */
    public static function myIpIsInTheRange($ip_range) {
        $user_ip = self::fetch_alt_ip();
        $result = false;
        foreach ($ip_range as $ip_allow) {
            $ip_allow = explode("-", $ip_allow);
            if (count($ip_allow) == 2) {
                $ip_allow = trim($ip_allow[0]) . '-' . trim($ip_allow[1]);
            } else {
                $ip_allow = trim($ip_allow[0]);
            }
            $ipCheck = new UtilsIpCheck($ip_allow);
            if ($ipCheck->check($user_ip) || $user_ip == $ip_allow) {
                $result = true;
                break;
            }
        }
        return $result;
    }

}
