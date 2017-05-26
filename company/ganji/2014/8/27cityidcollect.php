<?php

header('Content-type: text/html;charset=utf-8');
include_once dirname(__FILE__) . '/../lib/Curl.class.php';

/**
 * 中国气象城市数据采集
 * 
 */
class WeatherCityIdsCollecter {

    private static $provinceTxt = 'province_html.txt';

    public function run() {
//        $provinceIds = $this->getProvinceIds();
//        foreach ($provinceIds as $provinceId) {
//            $this->collectCitys($provinceId);
//        }
        #查询城市
//        $sql = 'select * from city';
//        $handle = self::getDBHandle();
//        $weatherCitys = file_get_contents('citys.txt');
//        $weatherCitys = explode("\n", $weatherCitys);
//        foreach ($weatherCitys as $weatherCity) {
//            $weatherCityArr = explode('  ', $weatherCity);
//            $sql = "select * from city where short_name like'%" . $weatherCityArr[0] . "%'";
//            $row = self::getRow($handle, $sql);
//            if ($row) {
//                //匹配到的城市
//                file_put_contents('weather_city.txt', $row['city_id'] . '=>' . $weatherCityArr[1] . "\n", FILE_APPEND);
//            } else {
//                //没有匹配到天气的城市
//                file_put_contents('no_weather_city.txt', $weatherCityArr[0] . "\n", FILE_APPEND);
//            }
//            echo iconv('utf- 8', 'gb2312', $weatherCityArr[0]) . "\n";
//        }
        #生成城市配置
        $citysConfig = file_get_contents('weather_city.txt');
        $citysConfig = explode("\n", $citysConfig);
        $cityWeatherIds = array();
        foreach ($citysConfig as $cityConfig) {
            $cityConfigArr = explode('=>', $cityConfig);
            $cityId = $cityConfigArr[0];
            $cityWeatherId = $cityConfigArr[1];
            $cityWeatherIds[$cityId] = $cityWeatherId;
        }
        ksort($cityWeatherIds);
        foreach ($cityWeatherIds as $cityId => $cityWeatherId) {
            file_put_contents('weather_city_config.txt', $cityId . ' => ' . "'" . $cityWeatherId . "'," . "\n", FILE_APPEND);
        }
    }

    public static function getDBHandle() {

        $handle = @mysqli_connect(self::$DB_CONFIG['host'], self::$DB_CONFIG['username'], self:: $DB_CONFIG['password'], self:: $DB_CONFIG['dbname'], self:: $DB_CONFIG['port']);
        mysqli_set_charset($handle, "utf8");
        return $handle;
    }

    public static function getRow($handle, $sql) {
        $result = mysqli_query($handle, $sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        return $row;
    }

    private function collectCitys($provinceId) {
        //采集城市ids

        $url = 'http://hf.weathercn.com/dis.do?pid=' . $provinceId;
        $curl = new Curl();
        $content = $curl->get($url);
        $pattern = '/\<a\s+href\=\"cout\.do\?did=(\d+)\&pid=(\d+)\"\s+[^>]+\>(.*?)\<\/a\>/is';
        preg_match_all($pattern, $content, $matches);
        $cityIds = $matches[1];
        $cityNames = $matches[3];
        foreach ($cityIds as $i => $cityId) {
            echo iconv('utf- 8', 'gb2312', $cityNames[$i]) . "\n";
            file_put_contents('citys.txt', $cityNames[$i] . '  ' . $cityId . "\n", FILE_APPEND);
        }
    }

    private function getProvinceIds() {

        $content = file_get_contents(self::$provinceTxt);
        //采集省份ids
        $pattern = '/<a\s+href\=\"dis\.do\?pid=(\d+)"\s+class\=\"c\"\>(.+?)\<\/a\>/is';
        preg_match_all($pattern, $content, $matches);
        return $matches[1];
    }

    private static $DB_CONFIG = array(
        //测试环境
        'host' => '10.3.255.21',
        'username' => 'off_mymsc',
        'password' => 'hpctiEepEIty',
        'port' => 3311,
        'dbname' => 'management',
    );

}

$o = new WeatherCityIdsCollecter();
$o->run();

