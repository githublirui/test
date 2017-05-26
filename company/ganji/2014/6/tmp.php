<?php

include 'Curl.class.php';

set_time_limit(0);
$url = "http://webim.ganji.com/index.php?op=getVisitorCount&dest=client&category=2&city=12&token=STr8n9AgtE0cocb1ST5Wy4Il&mobileType=705";
$cUrl = new Curl();
$result = $cUrl->get($url);
$result = json_decode($result, true);
$result = (int) $result['data']['count'];
var_dump($result);
die;
$content = file_get_contents('dm.txt');
$contentarr = explode("\n", $content);
$urlTpl = 'http://e.domob.cn/track/ow/api?appId=%s&udid=%s&ifa=%s&returnFormat=1';
$i = 0;
foreach ($contentarr as $third_party_key) {
    if ($third_party_key) {
        list($appId, $uniqueId) = explode('|', $third_party_key);
        $mac = $idfa = '';
        $uniqueId = trim($uniqueId);
        strpos($uniqueId, ':') !== false || strlen($uniqueId) < 15 ? $mac = $uniqueId : $idfa = $uniqueId;
        if (!empty($appId)) {
            trim($urlTpl);
//            $callBackUrl = sprintf($urlTpl, $appId, $mac, $idfa);
//            $cUrl = new Curl();
//            $result = $cUrl->get($callBackUrl);
//            echo '.';
//            flush();
//            file_put_contents('a.txt', $result . '||' . $callBackUrl . "\n", FILE_APPEND);
//            $i++;
        }
    }
}
echo $i;
