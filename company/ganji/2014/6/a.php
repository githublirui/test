<?php

include 'Curl.class.php';

$url = 'http://e.domob.cn/track/ow/api?appId=388932995&udid=&ifa=EAB1E705-E963-4999-B00C-BED9BC87A5B0&returnFormat=1';
$cUrl = new Curl();
$cUrl->setTimeout(2);
$result = $cUrl->get($url);
var_dump($result);
die;
file_put_contents('6_9.txt', sprintf('call third party back result. anency=%s,url=%s res=%s', 'duomeng', $url, var_export($result)), "\n", FILE_APPEND);
die;
$content = file_get_contents('dm.txt');
$contentarr = explode("\n", $content);
$urlTpl = 'http://e.domob.cn/track/ow/api?appId=%s&udid=%s&ifa=%s&returnFormat=1';
$i = 0;
foreach ($contentarr as $third_party_key) {
    if ($third_party_key) {
        list($appId, $uniqueId) = explode('|', $third_party_key);
        $mac = $idfa = '';
        strpos($uniqueId, ':') !== false || strlen($uniqueId) < 15 ? $mac = $uniqueId : $idfa = $uniqueId;
        if (!empty($appId)) {
            $callBackUrl = sprintf($urlTpl, $appId, $mac, $idfa);
//            $cUrl = new Curl();
//            $result = $cUrl->get($callBackUrl);
//            file_put_contents('a.txt', $result . '||' . $callBackUrl . "\n", FILE_APPEND);
            $i++;
        }
    }
}
echo $i;
