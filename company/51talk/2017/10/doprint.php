<?php

function sendPost($url, array $post = array(), array $options = array()) {
    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_POSTFIELDS => http_build_query($post, '', '&'),
//        CURLOPT_REFERER => __WWW__,
    );
    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
    $result = curl_exec($ch);
    //调试信息
    if (defined('DEBUG') && true === DEBUG) {
        $info = curl_getinfo($ch);
        $arr = array();
        $arr[] = array('opt', 'info');
        $arr[] = array('query', $post ? var_export($post, true) : '');
        $arr[] = array('response', $result);
        foreach ($info as $k => $v) {
            $arr[] = array($k, $v);
        }
//            fb($arr, 'POST请求：' . $url, FirePHP::TABLE);
    }
    if (curl_error($ch)) {
        $result = false;
    }
    curl_close($ch);
    return $result;
}

$url = "http://test.local/index.php?company/51talk/2017/10/print.php";
$ret = sendPost($url);
var_dump($ret);
die;
?>
