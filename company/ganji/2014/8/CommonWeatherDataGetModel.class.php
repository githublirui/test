<?php

/**
 * @file CommonWeatherDataGetModel.class.php
 * @brief 获取首页天气等相关数据model
 * @author lirui(lirui1@ganji.com)
 * @version 1.0
 * @date 2014-08-07
 */
require_once CLIENT_APP_DATASHARE . '/config/common/CommonWeatherConfig.php';
require_once CLIENT_APP_DATASHARE . '/config/common/CommonWeatherUpdateConfig.php';

class CommonWeatherDataGetModel extends MobileClientBaseModel {

    private static $_MODEL = 'mobile';
    private static $_DB = 'widget';
    private static $_PM25_TABLE_NAME = 'weather_pm25'; //pm25表名
    private static $_WEATHER_FORECAST_TABLE_NAME = 'weather_forecast'; //天气预报表名
    private static $_WEATHER_WARNING_TABLE_NAME = 'weather_warning'; //天气预警表名
    private static $_HOROSCOPE_TABLE_NAME = 'horoscope'; //星座数据表
    private static $weatherDetailUrl = array(
        0 => 'http://hf.weathercn.com/', //今天天气详情页url
        1 => 'http://hf.weathercn.com/7d.do', //明天详情页url
    );

    public function getTopData($param) {
        $result = array();
        $cityId = $param['cityId'];
        $result['traffic'] = $this->getTraffic($cityId); //限行尾号列表
        //天气相关
        $weather = $this->getWeatherForecast($cityId); //天气预报数据
        $result['weather']['realTimeTemp'] = isset($weather['observe']['temp']) ? $weather['observe']['temp'] : ''; //实时温度
        //今天天气预报格式化
        for ($i = 0; $i <= 1; $i++) {
            $result['weather']['forecast'][] = $this->getFormatForecast($weather, $i); //今天,明天天气
        }
        //天气预警
        $weatherWarnings = reset($this->getWeatherWarning($cityId));
        $weatherWarning = is_array($weatherWarnings['warning']) && !empty($weatherWarnings['warning']) ? reset($weatherWarnings['warning']) : ''; //取最新预警
        if (empty($weatherWarning)) {
            $result['weather']['warning'] = (object) array();
        } else {
            $result['weather']['warning']['desc'] = $weatherWarning['signal']['desc'];
            $result['weather']['warning']['colorIndex'] = (string) $weatherWarning['level']['code'];
            $result['weather']['warning']['detailUrl'] = 'http://sta.ganji.com/ng/app/client/common/index.html#app/client/app/misc/weather_warning/weather/view/index.js';
        }
        $result['weather']['serverTime'] = time();
        //pm
        $pm = $this->getWeatherPm25($cityId);
        if (empty($pm) || !is_array($pm)) {
            $result['pm'] = (object) array();
        } else {
            $result['pm']['num'] = (string) $pm['aqi'];
            $result['pm']['desc'] = (string) $pm['desc']['level'];
            $result['pm']['colorIndex'] = (string) $pm['index'];
            $result['pm']['detailUrl'] = 'http://sta.ganji.com/ng/app/client/common/index.html#app/client/app/misc/air_quality/air/view/index.js';
        }
        //星座constellation
        $constellId = $param['constellId'] - 1;
        $horoscope = $this->getHoroscope($constellId);
        $result['constellation']['index'] = (string) $param['constellId'];
        $result['constellation']['name'] = (string) CommonWeatherConfig::$horoscope[$constellId]['name'] . '运势';
        $result['constellation']['score'] = isset($horoscope['moodfortune']) ? (string) $horoscope['moodfortune']['@attributes']['score'] : '';
        $result['constellation']['moodfortune'] = isset($horoscope['moodfortune']) ? $horoscope['moodfortune']['@value'] : '';
        $result['constellation']['detailUrl'] = 'http://sta.ganji.com/ng/app/client/common/index.html#app/client/app/misc/fortune_change/fortune/view/index.js';
        return $result;
    }

    /**
     * 格式化天气数据
     * @param type $index  天气index,今天0,明天1
     */
    public function getFormatForecast($weather, $index = 0) {
        $result = array();
        $weatherForecast = $weather['forecast']['weather'][$index];
        $weatherObserve = $weather['observe'];
        $result['week'] = $this->getWeek($index);
        if ($index == 0) {
            //今天使用实时天气
            $result['iconIndex'] = isset($weatherObserve['weather']['desc']) ? $weatherObserve['weather']['desc'] : ''; //实况天气
            $result['desc'] = isset($weatherObserve['weather']['desc']) ? $weatherObserve['weather']['desc'] : '';
            //实时风向风力判断
            if ($weatherObserve['windDirection'] == '无持续风向') {
                $result['wind'] = '无持续风向';
            } else {
                $result['wind'] = isset($weatherObserve['windDirection']) ? $weatherObserve['windDirection'] . $weatherObserve['windForce'] . '级' : ''; //东北风2级
            }
        } else {
            $result['iconIndex'] = isset($weatherForecast['dayWeather']['desc']) ? $weatherForecast['dayWeather']['desc'] : '';
            $result['desc'] = isset($weatherForecast['dayWeather']['desc']) ? $weatherForecast['dayWeather']['desc'] : '';
            //实时风向风力判断
            if ($weatherForecast['dayDirection']['desc'] == '无持续风向') {
                $result['wind'] = '无持续风向';
            } else {
                $result['wind'] = isset($weatherForecast['dayDirection']['desc']) && isset($weatherObserve['dayForce']['desc']) ? //
                        $weatherObserve['dayDirection']['desc'] . $weatherObserve['dayForce']['desc'] . '级' : ''; //东北风2级
            }
        }
        $result['lowTemp'] = isset($weatherForecast['dayTemp']) && isset($weatherForecast['nightTemp']) ? //
                ($weatherForecast['dayTemp'] > $weatherForecast['nightTemp'] ? //
                        $weatherForecast['nightTemp'] : $weatherForecast['dayTemp']) : '';
        $result['highTemp'] = isset($weatherForecast['dayTemp']) && isset($weatherForecast['nightTemp']) ? //
                ($weatherForecast['dayTemp'] > $weatherForecast['nightTemp'] ? //
                        $weatherForecast['dayTemp'] : $weatherForecast['nightTemp']) : '';

        $result['detailUrl'] = self::$weatherDetailUrl[$index];
        return $result;
    }

    /**
     * 获取星期中文 周一
     * @param type $next 后几天，例：明天$next=1,等于0表示今天
     * @return string
     */
    public function getWeek($next = 0) {
        $week = date("w");
        $week = $week + $next;
        switch ($week) {
            case 1:
                return "周一";
                break;
            case 2:
                return "周二";
                break;
            case 3:
                return "周三";
                break;
            case 4:
                return "周四";
                break;
            case 5:
                return "周五";
                break;
            case 6:
                return "周六";
                break;
            case 0:
                return "周日";
                break;
        }
    }

    /**
     * 获取限行,目前支持四个城市，北京：12，天津：14，长春：45，成都84
     * @param type $cityId
     */
    public function getTraffic($cityId) {
        $result = array();
        $date = date('Ymd');
        $week = date('w');
        $config = CommonWeatherConfig::$traffic;
        if (array_key_exists($cityId, $config)) {
            $trafficConfig = $config[$cityId];
        }
        if ($cityId == 12 || $cityId == 14) {
            //北京，天津
            foreach ($trafficConfig as $tDate => $Tconfig) {
                $arrDate = explode('=>', $tDate);
                if ($date >= $arrDate[0] && $date <= $arrDate[1]) {
                    $result = $Tconfig[$week];
                    break;
                }
            }
        } else if ($cityId == 45) {
            //成都
            $result = $trafficConfig[$week];
        } else if ($cityId == 84) {
            //长春
            $result = array((int) substr($date, -1, 1));
        }
        return $result;
    }

    /**
     *  获取城市预警列表信息
     * @param type $cityId
     */
    public function getWeatherWarning($cityId) {
        $result = array();
        $filter = array(array('city_id', '=', $cityId),
            array('post_time', '>=',
                strtotime(date('Y-m-d 00:00:00'))),
            array('post_time', '<=', strtotime(date('Y-m-d 23:59:59'))),
        );
        $dbhandle = $this->getDbHandler(self::$_MODEL, self::$_DB, false);
        $select = SqlBuilderNamespace::buildSelectSql(self::$_WEATHER_WARNING_TABLE_NAME, array('data'), $filter, array(), array('id' => 'desc'));
        $warnings = DBMysqlNamespace::getAll($dbhandle, $select);
        foreach ($warnings as $warning) {
            $warningArr = $warning['data'];
            $warningArr = json_decode($warningArr, true); //data参数有多条预警
            $tmp['post_time'] = $warningArr['postTime'];
            $oneWarnings = $warningArr['warning']; //data参数有多条预警
            foreach ($oneWarnings as $oneWarning) {
                $tmp['warning'][] = $oneWarning;
            }
            $result[] = $tmp;
            $tmp = array();
        }
        return $result;
    }

    /**
     * 获取城市天气预报信息
     * @param type $cityId
     */
    public function getWeatherForecast($cityId) {
        $result = array();
        $filter = array(array('city_id', '=', $cityId),);
        $dbhandle = $this->getDbHandler(self::$_MODEL, self::$_DB, false);
        $select = SqlBuilderNamespace::buildSelectSql(self::$_WEATHER_FORECAST_TABLE_NAME, array('data,observe'), $filter);
        $queryRet = DBMysqlNamespace::getRow($dbhandle, $select);
        $forecast = $queryRet['data'];
        $observe = $queryRet['observe'];
        $forecast = json_decode($forecast, true);
        $observe = json_decode($observe, true);
        if (is_array($forecast) && !empty($forecast)) {
            $result['forecast'] = $forecast;
        }
        if (is_array($observe) && !empty($observe)) {
            $result['observe'] = $observe;
        }
        return $result;
    }

    /**
     * 获取pm25
     * @param type $cityId
     * @return array
     */
    public function getWeatherPm25($cityId) {
        $result = array();
        $filter = array(
            array('city_id', '=', $cityId),
        );
        $dbhandle = $this->getDbHandler(self::$_MODEL, self::$_DB, false);
        $select = SqlBuilderNamespace::buildSelectSql(self::$_PM25_TABLE_NAME, array('data'), $filter, array(1), array('post_time' => 'desc'));
        $pm25 = DBMysqlNamespace::getRow($dbhandle, $select);
        $pm25 = $pm25['data'];
        $result = json_decode($pm25, true);
        $pm25 = $result['aqi'];
        //PM5说明文字判断
        if (is_array($result) && !empty($result)) {
            if ($pm25 >= 0 && $pm25 <= 50) {
                $result['index'] = 0; //pm index
            } elseif ($pm25 >= 51 && $pm25 <= 100) {
                $result['index'] = 1;
            } elseif ($pm25 >= 101 && $pm25 <= 150) {
                $result['index'] = 2;
            } elseif ($pm25 >= 151 && $pm25 <= 200) {
                $result['index'] = 3;
            } elseif ($pm25 >= 201 && $pm25 < 300) {
                $result['index'] = 4;
            } elseif ($pm25 >= 300) {
                $result['index'] = 5;
            }
            $result['desc'] = CommonWeatherConfig::$pm25Desc[$result['index']];
        }
        return (array) $result;
    }

    /**
     * 获取星座信息
     * @param type $index
     * @return array
     */
    public function getHoroscope($index) {
        $result = array();
        $day = (int) date('d');
        $dbhandle = $this->getDbHandler(self::$_MODEL, self::$_DB, false);
        $select = SqlBuilderNamespace::buildSelectSql(self::$_HOROSCOPE_TABLE_NAME, array('data'), array(), array(1), array('created' => 'desc'));
        $horoscope = DBMysqlNamespace::getRow($dbhandle, $select);
        $horoscope = $horoscope['data'];
        $horoscope = json_decode($horoscope, true);
        if (isset($horoscope[$day]['astro'][$index])) {
            $result = $horoscope[$day]['astro'][$index];
        }
        return $result;
    }

    /**
     * 
     * 测试
     */
    public function insertTest() {
        //天气测试数据
        $weathers = CommonWeatherUpdateConfig::$weatherCode;
        $winds = CommonWeatherUpdateConfig::$windCode;
        $warningSignalCode = CommonWeatherUpdateConfig::$warningSignalCode;
        $warningSignalLevel = CommonWeatherUpdateConfig::$warningSignalLevel;
        $pms = array('00' => 30, '01' => 70, '02' => 120, '03' => 180, '04' => 260, '05' => 320);
        $i = 0;
        foreach ($weathers as $code => $weather) {
            $i++;
            $id = $code + 1;
            $wind = isset($winds[(int) $code]) ? $winds[(int) $code] : array('windDirection' => '西北风', 'windForce' => '9-10 级');
            $dbhandle = $this->getDbHandler(self::$_MODEL, self::$_DB, false);
            $filters = array(array('city_id', '=', $id));
            //更新天气
            $dataWeather = '{"temp":"30","humidity":"67","windForce":"2","windDirection":"\u897f\u5357\u98ce","weather":{"code":"01","desc":"\u591a\u4e91"},"postTime":"11:45"}';
            $dataWeather = json_decode($dataWeather, true);

            $dataWeather['windForce'] = rand(1, 7);
            $dataWeather['windDirection'] = $wind['windDirection'];
            $dataWeather['weather']['code'] = $code;
            $dataWeather['weather']['desc'] = $weather;

            $upWeatherArr = array(
                'observe' => json_encode($dataWeather),
            );
            $upWeathersql = SqlBuilderNamespace::buildUpdateSql(self::$_WEATHER_FORECAST_TABLE_NAME, $upWeatherArr, $filters);
            DBMysqlNamespace::execute($dbhandle, $upWeathersql);
            //更新明天天气
            if (isset($winds[$code])) {
                $lwindCode = $code;
            } else {
                $lwindCode = '5';
            }
            $tWeather = '{"updateTime":1408590000,"weather":[{"dayWeather":{"code":"00","desc":"\u6674"},"nightWeather":{"code":"01","desc":"\u591a\u4e91"},"dayTemp":"32","nightTemp":"21","dayDirection":{"code":"4","desc":"\u5357\u98ce"},"nightDirection":{"code":"4","desc":"\u5357\u98ce"},"dayForce":{"code":"0","desc":"\u5fae\u98ce"},"nightForce":{"code":"0","desc":"\u5fae\u98ce"}},{"dayWeather":{"code":"01","desc":"\u591a\u4e91"},"nightWeather":{"code":"04","desc":"\u96f7\u9635\u96e8"},"dayTemp":"31","nightTemp":"21","dayDirection":{"code":"4","desc":"\u5357\u98ce"},"nightDirection":{"code":"8","desc":"\u5317\u98ce"},"dayForce":{"code":"0","desc":"\u5fae\u98ce"},"nightForce":{"code":"0","desc":"\u5fae\u98ce"}},{"dayWeather":{"code":"04","desc":"\u96f7\u9635\u96e8"},"nightWeather":{"code":"04","desc":"\u96f7\u9635\u96e8"},"dayTemp":"30","nightTemp":"19","dayDirection":{"code":"4","desc":"\u5357\u98ce"},"nightDirection":{"code":"8","desc":"\u5317\u98ce"},"dayForce":{"code":"0","desc":"\u5fae\u98ce"},"nightForce":{"code":"0","desc":"\u5fae\u98ce"}}]}';
            $tWeather = json_decode($tWeather, true);
            $tWeather['weather'][1]['dayWeather']['code'] = $code;
            $tWeather['weather'][1]['dayWeather']['desc'] = $weather;
            $tWeather['weather'][1]['dayDirection']['code'] = $lwindCode;
            $tWeather['weather'][1]['dayDirection']['desc'] = $winds[$lwindCode]['windDirection'];
            $tWeather['weather'][1]['dayForce']['code'] = $lwindCode;
            $tWeather['weather'][1]['dayForce']['desc'] = $winds[$lwindCode]['windForce'];
            $upTWeatherArr = array(
                'data' => json_encode($tWeather),
            );
            $upTWeathersql = SqlBuilderNamespace::buildUpdateSql(self::$_WEATHER_FORECAST_TABLE_NAME, $upTWeatherArr, $filters);
            DBMysqlNamespace::execute($dbhandle, $upTWeathersql);
            //更新pm
            $pm = '{"updateTime":1408590000,"pm25":8,"aqi":32,"co":0.515,"no2":1,"o3":52,"pm10":32,"so2":2}';
            $pm = json_decode($pm, true);
            $rpm = isset($pms[$code]) ? $pms[$code] : 30;
            $pm['aqi'] = $rpm;
            $pm['pm25'] = $rpm;
            $upPmArr = array(
                'data' => json_encode($pm),
            );
            $uppsql = SqlBuilderNamespace::buildUpdateSql(self::$_PM25_TABLE_NAME, $upPmArr, $filters);
            DBMysqlNamespace::execute($dbhandle, $uppsql);
            //更新天气预警
            $warning = '{"postTime":2,"warning":[{"province":"","signal":{"code":"05","desc":"\u5927\u98ce"},"level":{"code":"01","desc":"\u84dd\u8272"},"postTime":1407912420,"content":"","warning":"","detailUrl":""}]}';
            $warning = json_decode($warning, true);

            if (isset($warningSignalCode[$code])) {
                $warning['warning'][0]['signal']['code'] = $code;
                $warning['warning'][0]['signal']['desc'] = $warningSignalCode[$code];
            } else {
                $warning['warning'][0]['signal']['code'] = '02';
                $warning['warning'][0]['signal']['desc'] = '暴雨';
            }
            if (isset($warningSignalLevel[$code])) {
                $warning['warning'][0]['level']['code'] = $code;
                $warning['warning'][0]['level']['desc'] = $warningSignalLevel[$code];
            } else {
                $warning['warning'][0]['level']['code'] = '01';
                $warning['warning'][0]['level']['desc'] = '蓝色';
            }

            $upWArr = array(
                'data' => json_encode($warning),
            );
            $upWsql = SqlBuilderNamespace::buildUpdateSql(self::$_WEATHER_WARNING_TABLE_NAME, $upWArr, $filters);
            DBMysqlNamespace::execute($dbhandle, $upWsql);
        }
    }

}

?>
