<?php

$arr1 = array(1, 2, 3);
$arr2 = array(1, 2, 3, 4);

$ret = 1;

//$data['card_id'] = 1;
//$data['card_code'] = 'LJ35123423';
//$data['card_openid'] = 'fidneudnfbgzzff';
//$data['card_source'] = 'weixin';
//$data['timestamp'] = time();
//$data['card_sign'] = '41aaff34fzz';
//$base_url = '?site=activity&ctl=procard&act=award&card_id=%card_id&card_code=%card_code&card_openid=%card_openid&card_source=%card_source&timestamp=%timestamp&card_sign=%card_sign';
//$ret = sprintf($base_url, $data);
//var_dump($ret);
//die;

$url = "http://new.intra.leju.com/index.php?m=addressBook&c=index&a=get_user_detail_info&flag=0&username=4&callback=jQuery182004678588290698826_1450922898545";

$ret = file_get_contents($url);
var_dump($ret);
die;
/**
 * 百度地图api
 */
class BaiduMapApi {

    private $ak = '345cade0d861b77d1441f57faa65b873';
    private $api_url = 'http://api.map.baidu.com/geoconv/v1/?';

    /**
     * 转换经纬度为百度坐标
     * @param type $coords 经纬度 114.21892734521,29.575429778924
     */
    public function change_to_baidu_coordinate($coords) {
        $param = array(
            'coords' => $coords,
            'from' => 1,
            'to' => 5,
            'ak' => $this->ak,
        );
        $request_url = $this->api_url . http_build_query($param);
        $responce = file_get_contents($request_url);
        $responce = json_decode($responce, true);
        if ($responce && $responce['status'] == 0) {
            return $responce['result'][0];
        } else {
            return false;
        }
    }

    public function geo_location($coords) {
        $request_url = "http://api.map.baidu.com/geocoder/v2/?ak=" . $this->ak . "&coordtype=wgs84ll&location={$coords}&output=json";
        $responce = file_get_contents($request_url);
        $responce = json_decode($responce, true);
        if ($responce && $responce['status'] == 0) {
            return $responce['result'];
        } else {
            return false;
        }
    }

}

$api = new BaiduMapApi();
$ret = $api->geo_location('31.8059,117.2114');
var_dump($ret);
die;
?>
