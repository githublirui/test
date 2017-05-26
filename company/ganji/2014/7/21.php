<?php

$xmlContent = file_get_contents('getdayfortune.php');
include 'XML2Array.class.php';
$xmlArray = XML2Array::createArray($xmlContent);
var_dump($xmlArray);
die;
header("Content-type: text/html; charset=utf-8");
$str = '<div class="im-message-content"><p></p><p><img src="http://img3.hefei.cc/portal/forum_cms/2014/07/21/5b87e2bd7271036f5029b7da8cee59eb.jpg" _src="http://img3.hefei.cc/portal/forum_cms/2014/07/21/5b87e2bd7271036f5029b7da8cee59eb.jpg" style=""></p>
<p><img src="http://img3.hefei.cc/portal/forum_cms/2014/07/21/172e2729fcbedcf58addec4e186aeada.jpg" _src="http://img3.hefei.cc/portal/forum_cms/2014/07/21/5b87e2bd7271036f5029b7da8cee59eb.jpg" style=""></p>
<p><img src="http://img3.hefei.cc/portal/forum_cms/2014/07/21/172e2729fcbedcf58addec4e186aeada.jpg" onclick="thispic(this)"></p><p></p>
</div>';
// $reg = '/<img\s+src="([^"]+)"\s_src="([^"]+)"[^>]+>/';
$reg = '/<img src="(.*)" _src="(.*)".*\/>/';
$replace = '<img src="$1" _src="$1" onclick="showbig(this)" 33/></a>';
$echo = preg_replace($reg, $replace, $str);

//print_r($echo);

$json = '{"CityName":"郑州市","UpDateTime":"2013-11-14 15:00:00","H24_Avg_PM2_5":"91","H24_Avg_AQI":118,"Real_Avg_AQI":118,"MonPointList":[{"MonPointName":"岗李水库","Real_CO":"2.139","Real_NO2":"47","Real_O3":"93","Real_PM10":"166","Real_SO2":"39","Real_PM2_5":"49","Real_AQI":"108"},{"MonPointName":"河医大","Real_CO":"2.082","Real_NO2":"36","Real_O3":"69","Real_PM10":"234","Real_SO2":"77","Real_PM2_5":"83","Real_AQI":"142"},{"MonPointName":"市监测站","Real_CO":"1.712","Real_NO2":"47","Real_O3":"44","Real_PM10":"—","Real_SO2":"63","Real_PM2_5":"83","Real_AQI":"110"},{"MonPointName":"四十七中","Real_CO":"2.039","Real_NO2":"32","Real_O3":"72","Real_PM10":"149","Real_SO2":"48","Real_PM2_5":"45","Real_AQI":"100"},{"MonPointName":"经开区管委","Real_CO":"1.701","Real_NO2":"36","Real_O3":"42","Real_PM10":"152","Real_SO2":"55","Real_PM2_5":"122","Real_AQI":"160"},{"MonPointName":"供水公司","Real_CO":"1.169","Real_NO2":"31","Real_O3":"72","Real_PM10":"173","Real_SO2":"57","Real_PM2_5":"49","Real_AQI":"112"},{"MonPointName":"银行学校","Real_CO":"1.569","Real_NO2":"20","Real_O3":"48","Real_PM10":"282","Real_SO2":"60","Real_PM2_5":"60","Real_AQI":"166"},{"MonPointName":"郑纺机","Real_CO":"0.917","Real_NO2":"28","Real_O3":"42","Real_PM10":"—","Real_SO2":"46","Real_PM2_5":"69","Real_AQI":"92"},{"MonPointName":"烟厂","Real_CO":"2.337","Real_NO2":"39","Real_O3":"51","Real_PM10":"107","Real_SO2":"52","Real_PM2_5":"44","Real_AQI":"78"}]}';
$key = base64_encode(hash_hmac('sha1', $replace, $reg));
$key = urlencode($key);
$json = '{"a":"\U5220\U9664\U5e16\U5b50\U5931\U8d25"}';
var_dump(json_decode($json, true));
