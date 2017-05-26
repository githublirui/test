<?php

include dirname(__FILE__) . '/../lib/Timer.class.php';

class CurlSingle {

    public function __construct() {
        #4197.7779865265 ms
    }

    public static function curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        $html = curl_exec($ch);
        $errno = curl_errno($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $html;
    }

}

class CurlMulti {

    public static function curlGet($urls) {

        $ch = array();
        foreach ($urls as $i => $url) {
            $ch[$i] = curl_init();
            curl_setopt($ch[$i], CURLOPT_HEADER, 0);
            curl_setopt($ch[$i], CURLOPT_URL, $url);
            curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch[$i], CURLOPT_POST, 0);
            curl_setopt($ch[$i], CURLOPT_VERBOSE, 0);
            curl_setopt($ch[$i], CURLOPT_NOBODY, FALSE);
        }
        $mh = curl_multi_init(); //只能在curl_init之前 初始化
        foreach ($ch as $c) {
            curl_multi_add_handle($mh, $c);
        }
        $flag = false;
        do {
            $ret = curl_multi_exec($mh, $flag);
        } while ($flag > 0);
//        foreach ($urls as $i => $url) {
//            $status = curl_getinfo($ch[$i], CURLINFO_HTTP_CODE);
//            $res[$i] = (($status == 200 || $status == 302 || $status == 301) ? curl_multi_getcontent($ch[$i]) : null);
//            curl_close($ch[$i]);
//            curl_multi_remove_handle($mh, $ch[$i]);   //用完马上释放资源  
//        }
//        var_dump($res);
//        die;
//        $conn = array();
    }

}

Timer::start();
$urls = array(
    'http://shanghai.anjuke.com/',
    'http://shanghai.anjuke.com/',
    'http://shanghai.anjuke.com/',
    'http://shanghai.anjuke.com/',
    'http://shanghai.anjuke.com/',
    'http://shanghai.anjuke.com/',
);
//foreach ($urls as $url) {
//    CurlSingle::curlGet($url);
//}
CurlMulti::curlGet($urls);
echo 'single curl spend' . Timer::spend();
?>
