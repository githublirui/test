<?php

/**
 * 获得新的大类类目配置接口（客户端4.1.0版本开始支持）
 * @brief CommonCategoryInfo
 * @author  jiajianming
 * @version 1.0
 * @date  2013-08-19
 */
class CommonCategoryInfo extends control {

    public function checkPara() {
        $this->para['categoryId'] = (int) clientPara::getArg('categoryId'); //大类id
        $this->para['virtualId'] = (int) clientPara::getArg('virtualId'); //虚拟大类id（"生活家政"id取1、"本地服务"id取2）
        $this->para['cityId'] = clientPara::getArg('cityId'); //暂时保留
        $this->para['constellId'] = (int) clientPara::getArg('constellId'); //星座index
        $this->para['version'] = clientPara::getArg('version'); //数据版本号
        $this->para['customerId'] = clientPara::getArg('customerId'); //平台id
        $this->para['versionId'] = clientPara::$param['header']['versionId']; //客户端版本
        if ($this->para['categoryId'] < 0) {
            $res = format::codeFormat(bodyErrDef::ERROR_PARAM, 'categoryId参数错误');
            $this->display($res);
            exit;
        }
        return true;
    }

    public function includeFiles() {
        include_once CLIENT_APP_DATASHARE . '/config/CategoryInfoConfig.class.php';
        include_once CLIENT_APP_DATASHARE . '/model/ClientAppCateConfigModel.class.php';
        include_once MSAPI . '/core/app/weather/MSWeatherApp.class.php';
        include_once(CODE_BASE2 . '/app/geo/GeoNamespace.class.php');
    }

    public function action() {
        $cateModel = new ClientAppCateConfigModel();
        $data = $cateModel->getCategoryInfo($this->para);
        if ($this->para['categoryId'] == 0) {
            $fileData = $cateModel->getNewCategoryInfo($this->para);
            $data['info'][0]['itemList'] = $this->checkItemListByClientVersion($fileData);
            $data = $this->_processDisplay($data);
        }
        if ($this->para['categoryId'] == 7 && !empty($data)) {
            $versionBoundary = false;
            if (clientPara::versionCompare($this->para['versionId'], '5.4.0') >= 0) {
                $versionBoundary = true;
            }
            $xiaoquEntrance = true;
            if ((clientPara::versionCompare($this->para['versionId'], '5.4.0') >= 0) && !$this->checkXiaoquEntrance()) {
                $this->para['versionId'] = '5.3.0';
                $xiaoquEntrance = false;
            }
            if (clientPara::versionCompare($this->para['versionId'], '5.4.0') < 0 && clientPara::versionCompare($this->para['versionId'], '5.3.0') > 0) {
                $this->para['versionId'] = '5.3.0';
            }
            //产品需求:5.3.0房产首页非北京,不显示100%入口
            if ((clientPara::versionCompare($this->para['versionId'], '5.8.0') <= 0) && $this->para['cityId'] != 12) {
                $this->para['versionId'] = '5.1.0';
            }

            $data = $this->genenatecategoryVersionItemList($data, $xiaoquEntrance, $versionBoundary);
        }
        //版本控制 ： 小于 5.5 不显示预约
        if ($this->para['categoryId'] == 5 && !empty($data)) {
            if (clientPara::versionCompare($this->para['versionId'], '5.5.0') < 0) {
                foreach ($data['info'][0]['itemList'] as $itkey => $items) {
                    if (in_array($items['title'], array('预约', '装修效果图'))) {
                        $data['info'][0]['itemList'][$itkey] = $data['info'][0]['itemList'][$itkey + 1];
                        unset($data['info'][0]['itemList'][$itkey + 1]);
                        if (in_array($data['info'][0]['itemList'][$itkey]['title'], array('婚庆典礼',))) {
                            $data['info'][0]['itemList'][$itkey]['id'] = str_replace('5', '4', $data['info'][0]['itemList'][$itkey]['id']);
                        }
                    }
                }
            } else {
                foreach ($data['info'][0]['itemList'] as $itkey => $items) {
                    if (in_array($items['title'], array('婚庆典礼', '婚庆'))) {
                        unset($data['info'][0]['itemList'][$itkey]);
                    }
                }
            }
        }

        if ($data === false) {
            $res = format::codeFormat(bodyErrDef::ERROR_SYSTEM);
        } else {
            $res = format::codeFormat(bodyErrDef::ERROR_SUCCESS, '', array('data' => $data));
        }
        $this->display($res);
    }

    /**
     * 处理大类（categoryId=0）的返回，
     * @param type $data
     * @return type
     */
    private function _processDisplay($data) {
        if ($data === false) {
            return false;
        }
        $dataWeather = $this->_getWeatherData();
        //首页搜索框关键词 大家都在搜索
        $nCategoryArr['searchKeyword'] = '电动车';

        //组合返回数组
        if (is_array($dataWeather) && !empty($dataWeather) && clientPara::versionCompare($this->para['versionId'], '5.7.0') < 0) {
            $nCategoryArr['weather'] = $dataWeather;
        }

        //5.7.0 新版首页
        if (clientPara::versionCompare($this->para['versionId'], '5.7.0') >= 0) {
            include_once CLIENT_APP_DATASHARE . '/model/common/CommonWeatherDataGetModel.class.php';
            $weatherModel = new CommonWeatherDataGetModel();
            $topData = $weatherModel->getTopData($this->para); //获取新版首页头部数据
            $nCategoryArr['top'] = $topData;
        }
        //$nCategoryArr['category'] = array();
        if (count($data['info']) > 0) {
            $nCategoryArr['category']['info'] = $data['info'];
            $nCategoryArr['category']['version'] = $data['version'];
        }
        return $nCategoryArr;
    }

    /**
     * 
     * 获取格式化后的天气数组
     */
    private function _getWeatherData() {
        #天气数组处理
        $result = array();
        $cityWeather = MSWeatherApp::getWeatherByCityID($this->para['cityId']);
        //如果天气不存在不下发
        if (!is_array($cityWeather) || empty($cityWeather['weather'])) {
            return $result;
        }
        #1. 最近三天的天气
        $late3Weathers = array_slice($cityWeather['weather'], 0, 3);
        $formatWeather = array();
        foreach ($late3Weathers as $late3Weather) {
            //如果有一个天气异常则不下发天气数据
            if (empty($late3Weather['weather']) || empty($late3Weather['temp_detail'][0]) || empty($late3Weather['temp_detail'][0]) || !is_numeric($late3Weather['temp_detail'][1]) || !is_numeric($late3Weather['temp_detail'][0])
            ) {
                return $result;
            }
            //天气
            $formatWeather[] = array(
                'iconIndex' => $this->_getIconIndexByDesc($late3Weather['weather']),
                'desc' => $late3Weather['weather'],
                'lowTemp' => $late3Weather['temp_detail'][1],
                'highTemp' => $late3Weather['temp_detail'][0],
                'date' => $late3Weather['date'],
            );
        }
        //组合weather 数组
        $result = array(
            'info' => array(
                'weather' => $formatWeather,
                'lastUpTime' => $cityWeather['update_time'],
                'serverTime' => time(),
            )
        );
        //pm
        if (isset($cityWeather['pm'])) {
            //pm值版本控制5.0.0 PM，5.1.0之后取AQI值
            $pm25 = $cityWeather['pm']['pm2_5'];
            if (clientPara::versionCompare($this->para['versionId'], '5.1.0') >= 0) {
                $pm25 = $cityWeather['pm']['aqi'];
            }
            $formatWeatherPm = array(
                'num' => floatval($pm25),
                'aqi' => floatval($cityWeather['pm']['aqi']),
                'desc' => (string) $this->_getDescByAqi($cityWeather['pm']['aqi']),
                'bgNormalColor' => $this->_getColorByAqi($cityWeather['pm']['aqi']),
            );
            $result['info']['pm'] = $formatWeatherPm;
        }
        return $result;
    }

    /**
     * 通过天气说明获取天气图标名
     * @param type $desc
     */
    private function _getIconIndexByDesc($desc) {
        $result = '';
        //如果不包含两种天气则直接返回，按照转来区分
        $distinguish = '转';
        if (strpos($desc, $distinguish) === false) {
            $result = $desc;
        } else {
            $two_desc = explode($distinguish, $desc);
            #用第一个早上(8:00 - 20:00)，晚上（其他时间）用第二个
            $time = (int) date('H');
            if ($time >= 8 && $time <= 20) {
                $result = $two_desc[0];
            } else {
                $result = $two_desc[1];
            }
        }
        return $result;
    }

    /**
     * 通过pm aqi的值，获取颜色
     * @param type $aqi
     */
    private function _getColorByAqi($aqi) {
        $result = '';
        $time = (int) date('H');
        $isDaytime = $time >= 8 && $time <= 20; //是否是白天
        if ($aqi >= 0 && $aqi <= 100) {
            if ($isDaytime) {
                $result = '49c0e3';
            } else {
                $result = '717c92';
            }
        } else if ($aqi >= 101 && $aqi <= 200) {
            if ($isDaytime) {
                $result = 'dbb239';
            } else {
                $result = '996231';
            }
        } else if ($aqi >= 201) {
            if ($isDaytime) {
                $result = 'dd4248';
            } else {
                $result = '992231';
            }
        }
        //iphone 不加ff
        if ($this->para['customerId'] != 705) {
            $result = 'ff' . $result;
        }
        return $result;
    }

    /**
     * 通过pm aqi的值，获取天气说明
     * @param type $aqi
     */
    private function _getDescByAqi($aqi) {
        $result = '';
        if ($aqi >= 1 && $aqi <= 50) {
            $result = '空气优';
        } else if ($aqi >= 51 && $aqi <= 100) {
            $result = '空气良';
        } else if ($aqi >= 101 && $aqi <= 150) {
            $result = '轻度污染';
        } else if ($aqi >= 151 && $aqi <= 200) {
            $result = '中度污染';
        } else if ($aqi >= 201 && $aqi <= 300) {
            $result = '重度污染';
        } else if ($aqi >= 301) {
            $result = '严重污染';
        }
        return $result;
    }

    /**
     * 获取当前版本的itemlist
     * @param type $itemList
     * @return type array
     */
    private function checkItemListByClientVersion($data) {
        $result = array();
        $version = $this->para['versionId'];
        $itemList = $data['info'][0]['itemList'];
        //2. 针对指定渠道替换应用
        if (isset(CategoryInfoConfig::$AGENCY_REPLACE_ITEMS[clientPara::getArg('agency')])) {
            $relaceIds = CategoryInfoConfig::$AGENCY_REPLACE_ITEMS[clientPara::getArg('agency')];
            foreach ($relaceIds as $version => $_data) {
                foreach ($_data as $old => $new) {
                    if (is_numeric($version) || clientPara::versionCompare($this->para['versionId'], $version) >= 0) {
                        $itemList[$old] = $data['allItems'][$new];
                    }
                }
            }
        }
        //3. 针对指定城市，版本的替换
        $cityReplaceItems = CategoryInfoConfig::$CITY_REPLACE_ITEMS;
        foreach ($cityReplaceItems as $cityReplaceItem) {
            $replaceItemData = $cityReplaceItem['replaceTtems'];
            $replaceItemData = $replaceItemData[$this->para['customerId']];
            foreach ($replaceItemData as $cityRversion => $replaceVersionItems) {
                //多版本
                if (is_numeric($cityRversion) || clientPara::versionCompare($this->para['versionId'], $cityRversion) >= 0) {
                    //多个item 替换
                    foreach ($replaceVersionItems as $old => $new) {
                        if (!is_numeric($old)) {
                            //指定城市替换
                            if (in_array($this->para['cityId'], $cityReplaceItem['cityIds'])) {
                                $itemList[$old] = $data['allItems'][$new];
                            }
                        } else {
                            //指定城市显示
                            if (!in_array($this->para['cityId'], $cityReplaceItem['cityIds'])) {
                                unset($itemList[$new]);
                            }
                        }
                    }
                }
            }
        }
        $itemList = array_values($itemList);
        $result = $this->formatItemList($itemList);
        return $result;
    }

    /**
     * 格式化item list
     * @param type $itemList
     * @return type
     */
    private function formatItemList($itemList) {
        foreach ($itemList as &$item) {
            //图片版本控制
            if (isset($item['imgInfo'])) {
                foreach ($item['imgInfo'] as $imgInfo) {
                    if (clientPara::versionCompare($this->para['versionId'], $imgInfo['startVersion']) >= 0 //
                            && (empty($imgInfo['endVersion']) || //
                            clientPara::versionCompare($imgInfo['endVersion'], $this->para['versionId']) > 0) //
                    ) {
                        $item['imgUrl'] = $imgInfo['imgUrl'];
                    }
                    unset($item['imgInfo']);
                }
            }
            //金融链接特殊处理
            if ($item['id'] == '0.1.45') {
                $cityInfo = GeoNamespace::getCityById($this->para['cityId']);
                $item['dataParams']['wapUrl'] = sprintf($item['dataParams']['wapUrl'], $cityInfo['domain']);
            }
        }
        return $itemList;
    }

    /**
     * 赶集生活 频道 首页 版本控制 
     * @param type $itemList
     * @return type array
     * @author Li Danfeng
     */
    private function genenatecategoryVersionItemList($itemList, $flag = false, $versionBoundary = false) {
        $version = $this->para['versionId'];
        $versionItemList = CategoryInfoConfig::$categoryVersionItemList[$this->para['categoryId']][$this->para['customerId']];
        if (empty($versionItemList)) {
            return $itemList;
        }
        foreach ($versionItemList as $value) {
            if (array_key_exists($this->para['versionId'], $value)) {
                $exactLists[] = $value[$this->para['versionId']];
            } else {
                if (clientPara::versionCompare($this->para['versionId'], '5.1.0') <= 0) {
                    $exactLists[] = $value[0];
                } else if (clientPara::versionCompare($this->para['versionId'], '5.9.0') <= 0 && clientPara::versionCompare($this->para['versionId'], '5.1.0') > 0) {
                    $exactLists[] = $value['5.4.0'];
                }
            }
        }
        //5.9.0 以前$exactLists数组中只有两个元素
        if (empty($exactLists[2])) {
            unset($exactLists[2]);
            unset($exactLists[3]);
        }
        if (($flag == true && 0 == clientPara::versionCompare($version, '5.1.0') ) && $versionBoundary) {
            $exactLists[0][3] = '7.1.6';
            $exactLists[1][] = '7.2.12';
        }
        // 北京显示的是 100% 个人房源
        if (($flag == true && clientPara::versionCompare($version, '5.10.0') >= 0 ) && $versionBoundary && $this->para['cityId'] == 12) {
            $exactLists[2][2] = '7.3.5';
            $exactLists[2][3] = '7.3.4';
        }
        // 没有小区的城市 显示 （个人房源，懒人找房）
        if (($flag == true && clientPara::versionCompare($version, '5.10.0') >= 0 ) && $versionBoundary && !$this->checkXiaoquEntrance()) {
            unset($exactLists[2][1]);
            unset($exactLists[2][2]);
        }
        foreach ($itemList['info'] as $key => &$section) {
            foreach ($section['itemList'] as $wholeListKey => $wholeList) {
                if (!in_array($wholeList['id'], $exactLists[$key])) {
                    unset($section['itemList'][$wholeListKey]);
                }
            }
            $section['itemList'] = array_values($section['itemList']);
        }
        return $itemList;
    }

    /**
     * 赶集生活 频道 首页 关于小区入口的判断 
     *
     * @brief 5.4才有这个逻辑判断
     * @param mixed
     * @return boolean  
     * @author Li Danfeng
     */
    private function checkXiaoquEntrance() {
        include_once CODE_BASE2 . '/app/post/adapter/housing/include/HousingVars.class.php';
        $city = GeoNamespace::getCityById($this->para['cityId']);
        if (in_array($city['domain'], HousingVars::$DOMAIN_IN_FIRST_TIER_CITY) ||
                in_array($city['domain'], HousingVars::$DOMAIN_IN_SECOND_TIER_CITY) ||
                in_array($city['domain'], CategoryInfoConfig::$xiaoquThirdCityDomain)
        ) {
            return true;
        }
        return false;
    }

}
