<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
//defined('IN_IA') or exit('Access Denied');

function getip() {
    static $ip = '';
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
        $ip = $_SERVER['HTTP_CDN_SRC_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

//define('HONGBAO_UNIACID', 99962);
define('HONGBAO_MCHID', '1271329001');
define('HONGBAO_PASSWORD', 'z0f1rrn5usjadtwfbthrxl2ozpl4bbfu');
define('HONGBAO_APPID', 'wxc4503dec164e0e32');

define('HONGBAO_PROVIDER', '宁波万科江湾府');
define('HONGBAO_WISH', '宁波万科江湾府');
define('HONGBAO_IP', '121.40.83.102');
define('HONGBAO_TITLE', '宁波万科江湾府');
define('HONGBAO_REMARK', '宁波万科江湾府');

define('HONGBAO_LOGO_IMGURL', '');
define('HONGBAO_SHARE_CONTENT', '');
define('HONGBAO_SHARE_IMGURL', '');

function array2xml($arr, $level = 1) {
    $s = $level == 1 ? "<xml>" : '';
    foreach ($arr as $tagname => $value) {
        if (is_numeric($tagname)) {
            $tagname = $value['TagName'];
            unset($value['TagName']);
        }
        if (!is_array($value)) {
            $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
        } else {
            $s .= "<{$tagname}>" . array2xml($value, $level + 1) . "</{$tagname}>";
        }
    }
    $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
    return $level == 1 ? $s . "</xml>" : $s;
}

function random($length, $numeric = FALSE) {
    $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
    if ($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

function strexists($string, $find) {
    return !(strpos($string, $find) === FALSE);
}

function is_error($data) {
    if (empty($data) || !is_array($data) || !array_key_exists('errno', $data) || (array_key_exists('errno', $data) && $data['errno'] == 0)) {
        return false;
    } else {
        return true;
    }
}

function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
    $urlset = parse_url($url);
    if (empty($urlset['path'])) {
        $urlset['path'] = '/';
    }
    if (!empty($urlset['query'])) {
        $urlset['query'] = "?{$urlset['query']}";
    }
    if (empty($urlset['port'])) {
        $urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
    }
    if (strexists($url, 'https://') && !extension_loaded('openssl')) {
        if (!extension_loaded("openssl")) {
            message('请开启您PHP环境的openssl');
        }
    }
    if (function_exists('curl_init') && function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlset['scheme'] . '://' . $urlset['host'] . ($urlset['port'] == '80' ? '' : ':' . $urlset['port']) . $urlset['path'] . $urlset['query']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if ($post) {
            if (is_array($post)) {
                $filepost = false;
                foreach ($post as $name => $value) {
                    if (substr($value, 0, 1) == '@') {
                        $filepost = true;
                        break;
                    }
                }
                if (!$filepost) {
                    $post = http_build_query($post);
                }
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        if (defined('CURL_SSLVERSION_TLSv1')) {
            curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
        if (!empty($extra) && is_array($extra)) {
            $headers = array();
            foreach ($extra as $opt => $value) {
                if (strexists($opt, 'CURLOPT_')) {
                    curl_setopt($ch, constant($opt), $value);
                } elseif (is_numeric($opt)) {
                    curl_setopt($ch, $opt, $value);
                } else {
                    $headers[] = "{$opt}: {$value}";
                }
            }
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $data = curl_exec($ch);
        $status = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if ($errno || empty($data)) {
            return error($errno, $error);
        } else {
            return ihttp_response_parse($data);
        }
    }
    $method = empty($post) ? 'GET' : 'POST';
    $fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
    $fdata .= "Host: {$urlset['host']}\r\n";
    if (function_exists('gzdecode')) {
        $fdata .= "Accept-Encoding: gzip, deflate\r\n";
    }
    $fdata .= "Connection: close\r\n";
    if (!empty($extra) && is_array($extra)) {
        foreach ($extra as $opt => $value) {
            if (!strexists($opt, 'CURLOPT_')) {
                $fdata .= "{$opt}: {$value}\r\n";
            }
        }
    }
    $body = '';
    if ($post) {
        if (is_array($post)) {
            $body = http_build_query($post);
        } else {
            $body = urlencode($post);
        }
        $fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
    } else {
        $fdata .= "\r\n";
    }
    if ($urlset['scheme'] == 'https') {
        $fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
    } else {
        $fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
    }
    stream_set_blocking($fp, true);
    stream_set_timeout($fp, $timeout);
    if (!$fp) {
        return error(1, $error);
    } else {
        fwrite($fp, $fdata);
        $content = '';
        while (!feof($fp))
            $content .= fgets($fp, 512);
        fclose($fp);
        return ihttp_response_parse($content, true);
    }
}

function error($code, $msg) {
    echo 'error code: ' . $code;
    echo "<br/>";
    echo 'error msg: ' . $msg;
    die;
}

function ihttp_response_parse($data, $chunked = false) {
    $rlt = array();
    $pos = strpos($data, "\r\n\r\n");
    $split1[0] = substr($data, 0, $pos);
    $split1[1] = substr($data, $pos + 4, strlen($data));

    $split2 = explode("\r\n", $split1[0], 2);
    preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
    $rlt['code'] = $matches[2];
    $rlt['status'] = $matches[3];
    $rlt['responseline'] = $split2[0];
    $header = explode("\r\n", $split2[1]);
    $isgzip = false;
    $ischunk = false;
    foreach ($header as $v) {
        $row = explode(':', $v);
        $key = trim($row[0]);
        $value = trim($row[1]);
        if (@is_array($rlt['headers'][$key])) {
            $rlt['headers'][$key][] = $value;
        } elseif (!empty($rlt['headers'][$key])) {
            $temp = $rlt['headers'][$key];
            unset($rlt['headers'][$key]);
            $rlt['headers'][$key][] = $temp;
            $rlt['headers'][$key][] = $value;
        } else {
            $rlt['headers'][$key] = $value;
        }
        if (!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
            $isgzip = true;
        }
        if (!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
            $ischunk = true;
        }
    }
    if ($chunked && $ischunk) {
        $rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
    } else {
        $rlt['content'] = $split1[1];
    }
    if ($isgzip && function_exists('gzdecode')) {
        $rlt['content'] = gzdecode($rlt['content']);
    }

    $rlt['meta'] = $data;
    if ($rlt['code'] == '100') {
        return ihttp_response_parse($rlt['content']);
    }
    return $rlt;
}

function ihttp_response_parse_unchunk($str = null) {
    if (!is_string($str) or strlen($str) < 1) {
        return false;
    }
    $eol = "\r\n";
    $add = strlen($eol);
    $tmp = $str;
    $str = '';
    do {
        $tmp = ltrim($tmp);
        $pos = strpos($tmp, $eol);
        if ($pos === false) {
            return false;
        }
        $len = hexdec(substr($tmp, 0, $pos));
        if (!is_numeric($len) or $len < 0) {
            return false;
        }
        $str .= substr($tmp, ($pos + $add), $len);
        $tmp = substr($tmp, ($len + $pos + $add));
        $check = trim($tmp);
    } while (!empty($check));
    unset($tmp);
    return $str;
}

function ihttp_get($url) {
    return ihttp_request($url);
}

function ihttp_post($url, $data) {
    $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
    return ihttp_request($url, $data, $headers);
}

function ihttp_email($to, $subject, $body, $global = false) {
    static $mailer;
    set_time_limit(0);

    if (empty($mailer)) {
        if (!class_exists('PHPMailer')) {
            require IA_ROOT . '/framework/library/phpmailer/PHPMailerAutoload.php';
        }
        $mailer = new PHPMailer();
        global $_W;
        $config = $GLOBALS['_W']['setting']['mail'];
        if (!$global) {
            $row = pdo_fetch("SELECT `notify` FROM " . tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
            $row['notify'] = @iunserializer($row['notify']);
            if (!empty($row['notify']) && !empty($row['notify']['mail'])) {
                $config = $row['notify']['mail'];
            }
        }

        $config['charset'] = 'utf-8';
        if ($config['smtp']['type'] == '163') {
            $config['smtp']['server'] = 'smtp.163.com';
            $config['smtp']['port'] = 25;
        } else {
            if (!empty($config['smtp']['authmode'])) {
                $config['smtp']['server'] = 'ssl://' . $config['smtp']['server'];
            }
        }

        if (!empty($config['smtp']['authmode'])) {
            if (!extension_loaded('openssl')) {
                return error(1, '请开启 php_openssl 扩展！');
            }
        }
        $mailer->signature = $config['signature'];
        $mailer->isSMTP();
        $mailer->CharSet = $config['charset'];
        $mailer->Host = $config['smtp']['server'];
        $mailer->Port = $config['smtp']['port'];
        $mailer->SMTPAuth = true;
        $mailer->Username = $config['username'];
        $mailer->Password = $config['password'];
        !empty($config['smtp']['authmode']) && $mailer->SMTPSecure = 'ssl';

        $mailer->From = $config['username'];
        $mailer->FromName = $config['sender'];
        $mailer->isHTML(true);
    }
    if (!empty($mailer->signature)) {
        $body .= htmlspecialchars_decode($mailer->signature);
    }
    $mailer->Subject = $subject;
    $mailer->Body = $body;
    $mailer->addAddress($to);
    if ($mailer->send()) {
        return true;
    } else {
        return error(1, $mailer->ErrorInfo);
    }
}

/**
 *  打款
 * $open_id 微信open_id
 * $fee     打款金额
 * $record_id   打款记录表主键id
 */
function send_fee($open_id, $fee, $record_id) {

    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
//    load()->func('communication');
    $pars = array();
    $pars['nonce_str'] = random(32);
    $pars['mch_billno'] = HONGBAO_MCHID . date('Ymd') . sprintf('%010d', $record_id);
    $pars['mch_id'] = HONGBAO_MCHID;
    $pars['wxappid'] = HONGBAO_APPID;
    $pars['nick_name'] = HONGBAO_PROVIDER;
    $pars['send_name'] = HONGBAO_PROVIDER;
    $pars['re_openid'] = $open_id;
    $pars['total_amount'] = $fee;
    $pars['min_value'] = $fee;
    $pars['max_value'] = $fee;
    $pars['total_num'] = 1;
    $pars['wishing'] = HONGBAO_WISH;
    $pars['client_ip'] = HONGBAO_IP;
    $pars['act_name'] = HONGBAO_TITLE;
    $pars['remark'] = HONGBAO_REMARK;
    $pars['logo_imgurl'] = 'tt';
    $pars['share_content'] = 'tt';
    $pars['share_imgurl'] = 'tt';
    $pars['share_url'] = 'tt';

//    $pars['logo_imgurl'] = tomedia($activity['image']);
//    $pars['share_content'] = $activity['content'];
//    $pars['share_imgurl'] = tomedia($activity['image']);
//    $pars['share_url'] = $_W['siteroot'].'app/' . substr(substr($this->createMobileUrl('entry', array('owner'=>$user['uid'])), 2), 0, -39);

    ksort($pars, SORT_STRING);
    $string1 = '';
    foreach ($pars as $k => $v) {
        $string1 .= "{$k}={$v}&";
    }
    $string1 .= "key=" . HONGBAO_PASSWORD;
    $pars['sign'] = strtoupper(md5($string1));
    $xml = array2xml($pars);
    $extras = array();
    $extras['CURLOPT_CAINFO'] = NOW_PATH . '/cert/rootca.pem';
    $extras['CURLOPT_SSLCERT'] = NOW_PATH . '/cert/apiclient_cert.pem';
    $extras['CURLOPT_SSLKEY'] = NOW_PATH . '/cert/apiclient_key.pem';

    $procResult = null;
    $resp = ihttp_request($url, $xml, $extras);
    if (is_error($resp)) {
//        $setting = $this->module['config'];
//        $setting['api']['error'] = $resp['message'];
//        $this->saveSettings($setting);
//        $procResult = $resp;
        return false;
    } else {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
        $dom = new DOMDocument();
        if ($dom->loadXML($xml)) {
            $xpath = new DOMXPath($dom);
            $code = $xpath->evaluate('string(//xml/return_code)');
            $ret = $xpath->evaluate('string(//xml/result_code)');
            if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
                return true;
            } else {
                $error = $xpath->evaluate('string(//xml/err_code_des)');
                return $error;
//                $setting = $this->module['config'];
//                $setting['api']['error'] = $error;
//                $this->saveSettings($setting);
//                $procResult = error(-2, $error);
            }
        } else {
            return 'error response';
//            $procResult = error(-1, 'error response');
        }
    }
}

function rand_phone() {
    $phoneArr = array();     //保存手机号数组
    $num = 1;                 //生成手机号的个数
    $twoArr = array(3, 5, 8);  //手机号的第二位
    $tArr = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);    //手机号第二位为3时，手机号的第3位数据集，以及手机号的第4位到第11位
    $ntArr = array(0, 1, 2, 3, 5, 6, 7, 8, 9);      //手机号第二位不为3时，手机号的第3位数据集
    for ($i = 0; $i < $num; $i++) {
        $phone[0] = 1;                      //手机号第一位必须为1
        for ($j = 1; $j < 11; $j++) {
            if ($j == 1) {
                $t = rand(0, 2);          //随机生成手机号的第二位数字
                $phone[$j] = $twoArr[$t];
            } elseif ($j == 2 && $phone[1] != 3) {     //当第二位不为3时，随机生成其余手机号
                $th = rand(0, 8);
                $phone[$j] = $ntArr[$th];
            } else {                                         //当第二位为3时，随机生成其余手机号
                $th = rand(0, 9);
                $phone[$j] = $tArr[$th];
            }
        }
        $phoneArr[] = implode("", $phone);          //将手机号数组合并成字符串
    }
    return $phoneArr[0];
}

/**
 * 计算概率
 * @param type $proArr
 * @return type
 */
function get_rand($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset($proArr);
    return $result;
}
