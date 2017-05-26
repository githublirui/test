<?php

/**
 * @file ClientAppCateConfigModel.class.php
 * @brief 
 * @author Robber
 * @version 1.0
 * @date 2012-03-31
 */
require_once CLIENT_APP_DATASHARE . "/model/ClientAppConfigModel.class.php";

class ClientAppCateConfigModel extends ClientAppConfigModel {

    protected $key_pre = 'mobile_config_cate_';
    private $customerid_table = 'mobile_config_client_major';
    private $major_table = 'mobile_config_majors';

    public function is_modify($ClientVer, $ver) {
        $arr = explode('_', $ClientVer);
        //数据版本与客户端版本都相等才下发
        if (count($arr == 2) && $arr['0'] == $ver && $arr['1'] == HeaderCheck::$param['versionId']) {
            return false;
        } else {
            return true;
        }
    }

    public function getCustomerCatesByCustomerId($customerid) {
        if (empty($customerid))
            return false;

        $ret = $this->getCache($customerid);
        if (!empty($ret)) {
            return $ret;
        }

        $sql = 'select `major_idx`,`desc`,`cityScriptIndex`,`categoryid`,`listDataSchema`,`display_order` from ' . $this->customerid_table . ' where customer_id = ' . $customerid . ' order by display_order desc';

        $arrRes = DBMysqlNamespace::query($this->getDbHandler($this->model, $this->DB, true), $sql);

        if (false === $arrRes) {
            $arrRes = array();
        }

        $ret = array();

        foreach ($arrRes as $res) {
            $v = substr($res['major_idx'], 0, 2);
            if ($v == 'V3') {
                $ret[300 + $res['categoryid']] = $res;
            } elseif ($v == 'V4') {
                $ret[400 + $res['categoryid']] = $res;
            } else {
                $ret[$res['categoryid']] = $res;
            }
        }

        $this->setCache($customerid, $ret);

        return $ret;
    }

    public function getMajorConfByMajorIdx($major_idx) {
        if (empty($major_idx))
            return false;

        $ret = $this->getCache($major_idx);
        if (!empty($ret)) {
            return $ret;
        }
        // $sql = "select categoryid,scriptindex,cityScriptIndex,cityName,version,name,enablepost,enablelist,enablenearbysearch,enableXiaoQu,listDataSchema,majorCategorys from {$this->major_table} where major_idx='{$major_idx}'";
        $sql = SqlBuilderNamespace::buildSelectSql($this->major_table, array('categoryid', 'scriptindex', 'cityScriptIndex', 'cityName', 'version', 'name', 'enablepost', 'enablelist', 'enablenearbysearch', 'enableXiaoQu', 'listDataSchema', 'majorCategorys'), array(array('major_idx', '=', $major_idx)));
        $arrRes = DBMysqlNamespace::query($this->getDbHandler($this->model, $this->DB, true), $sql);

        if (false === $arrRes) {
            $arrRes = array();
        }
        foreach ($arrRes[0] as $k => $v) {
            if ($k === "majorCategorys") {
                $ret[$k] = json_decode($v, true);
            } else {
                $ret[$k] = $v;
            }
        }

        $this->setCache($major_idx, $ret);

        return $ret;
    }

    public function genResult($major, $minors) {
        $maj = array(
            'cid' => intval($major['categoryid']),
            'cidx' => intval($major['scriptindex']),
            'v' => $major['version'] . '_' . HeaderCheck::$param['versionId'], //下发数据版本与当前客户端版本
            'nb' => $this->_getBool($major['enablenearbysearch']),
            //'p'     => (bool)$major['enablepost'],
            'p' => $this->_checkCateEnablePub($major),
            'l' => $this->_getBool($major['enablelist']),
            'xq' => $this->_getBool($major['enableXiaoQu']),
            'n' => $major['name'],
            's' => intval($major['listDataSchema']),
            'e' => $GLOBALS['cate_explain'][$major['categoryid']],
            'ms' => array(),
        );
        foreach ($minors as $minor) {
            $min = array(
                'msi' => intval($minor['majorCategoryScriptIndex']),
                'n' => $minor['majorCategoryName'],
                'nb' => $this->_getBool($minor['enableNearbySearch']),
                // 'p'     => (bool)$minor['enablePost'],
                'p' => $this->_checkMajorEnablePub($minor),
                'l' => $this->_getBool($minor['enableList']),
                'xq' => $this->_getBool($minor['enableXiaoQu']),
            );
            //由于发布模板在客户端生成，新增某些小类发帖需根据版本控制兼容 ,enablePostVersion这配置字段只有在新增发帖支持才有2012-12-20
            //if(!empty($minor['enablePostVersion'])) $min['p'] = $this->_checkMajorPub($minor['enablePostVersion']);

            if (isset($minor['minorCategoryId'])) {
                $min['mii'] = intval($minor['minorCategoryId']);
            }

            $maj['ms'][] = $min;
        }
        return $maj;
    }

    public function genResultNew($major, $minors) {
        $maj = array(
            'cid' => intval($major['categoryid']),
            'cidx' => intval($major['scriptindex']),
            'v' => $major['version'],
            'nb' => $this->_getBool($major['enablenearbysearch']),
            'p' => $this->_getBool($major['enablepost']),
            'l' => $this->_getBool($major['enablelist']),
            'xq' => $this->_getBool($major['enableXiaoQu']),
            'n' => $major['name'],
            's' => intval($major['listDataSchema']),
            'class' => array(),
        );

        foreach ($minors as $minor) {
            $min = array(
                'classid' => intval($minor['classId']),
                'n' => $minor['name'],
                'nb' => $this->_getBool($minor['enableNearbySearch']),
                'p' => $this->_getBool($minor['enablePost']),
                'l' => $this->_getBool($minor['enableList']),
                'xq' => $this->_getBool($minor['enableXiaoQu']),
            );
            if (isset($minor['subClass'])) {
                $min['subClass'] = array();
                foreach ($minor['subClass'] as $sub) {
                    $chld = array(
                        'msi' => intval($sub['majorCategoryScriptIndex']),
                        'n' => $sub['name'],
                        'tagid' => $sub['tagId'],
                    );
                    $min['subClass'][] = $chld;
                }
                $min['msi'] = -1;
            } else {
                $min['msi'] = intval($minor['majorCategoryScriptIndex']);
            }

            $maj['class'][] = $min;
        }
        return $maj;
    }

    /**
     * 获取一个值的bool 值，主要把字符串false 识别为bool false
     * @param $str string | bool
     * @return boolean
     */
    private function _getBool($str) {
        if (empty($str)) {
            return false;
        }
        if (is_bool($str)) {
            return $str;
        }
        if (is_string($str) && strcmp(trim($str), 'false') == 0) {
            return false;
        }
        //其他情况强制转换类型返回
        return (bool) $str;
    }

    /**
     * 获得小类是否可以发帖
     * (特殊处理)不同版本，检验当前版本，检验发帖大类里，小类目是否支持，
     * @param <array>$majorInfo, 小类配置信息
     *       enablePost
     *    ,其中enablePostVersion字段格式：配置格式 "customerId#versionId,customerId#versionId" eg:801#2.9.3,705#2.9.4
     * @return <bool> $result
     */
    private function _checkMajorEnablePub($majorInfo) {
        $result = $this->_getBool($majorInfo['enablePost']);
        //与版本有关的处理
        if (!empty($majorInfo['enablePostVersion'])) {
            $result = false;
            $tmp = explode(',', $majorInfo['enablePostVersion']);
            foreach ($tmp as $v) {
                list($customerId, $versionId) = explode('#', $v);
                if (HeaderCheck::$param['customerId'] == $customerId && HeaderCheck::$param['versionId'] >= $versionId) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * 获得大类是否可以发帖
     * （特殊处理）不同版本、不同平台，大类是否支持发帖
     *
     * @param array $cateInfo 大类配置信息
     * @return <bool> $result
     */
    private function _checkCateEnablePub($cateInfo) {
        $result = $this->_getBool($cateInfo['enablepost']);
        //与版本有个关的特殊处理:
        //招聘android3.5.0开始支持发帖
        if ($cateInfo['categoryid'] == 2 && HeaderCheck::$param['customerId'] == '801') {
            $result = true;
        }
        //生活、商务服务iphone4.0.0,android4.0.0开始支持发帖
        if (in_array($cateInfo['categoryid'], array(4, 5)) && in_array(HeaderCheck::$param['customerId'], array(801, 705))) {
            $result = true;
        }
        return $result;
    }

    /**
     * 获得新版大类首页配置（4.1.0版本开始）
     * @param <array> $params
     *    @li customerId <int>  平台id
     *    @li categoryId <int>  大类id
     *    @li virtualId  <int>  虚拟大类id
     *    @li cityId <int>      城市id
     *    @li version  <string>  数据版本号
     * @return <bool/array>
     */
    public function getCategoryInfo($params) {
        $categoryInfo = array();
        $fileName = self::getDataPath($params['customerId'], $params['categoryId'], $params['virtualId'], $params['versionId']);
        $file = basename($fileName);
        $cacheKey = $file . '_category_info';
        $data = $this->getCache($cacheKey);
        if (empty($data)) {
            if (!file_exists($fileName))
                return false;
            $data = @file_get_contents($fileName);
            if (strlen($data) == 0)
                return false;
            $data = @unserialize($data);
            $this->setCache($cacheKey, $data);
        }
        $data['version'] = $params['versionId'] . $data['version'] . $params['cityId'];
        //各个频道可能需要根据参数特殊处理下配置
        $data = self::categorySpecificDeal($data, $params);
        if ($data['version'] != $params['version']) {
            $categoryInfo = $data;
        }
        return $categoryInfo;
    }

    private function getDataPath($customerId, $categoryId, $virtualId = 0, $versionId = 0) {
        $fileName = CLIENT_DATA . '/category/' . $customerId . '_' . $categoryId;
        if (in_array($categoryId, array(6, 7, 14)) && clientPara::versionCompare($versionId, '5.10.0') >= 0) {
            //二手，车辆，房产，5.10.0改版，新文件读取数据
            $fileName = CLIENT_DATA . '/category/' . $customerId . '_' . $categoryId . '_5.10.0';
        }
        if ($virtualId > 0) {
            $fileName .= '_' . $virtualId;
        }
        return $fileName;
    }

    /**
     * 各个频道对首页配置的特殊处理
     * @param <array> $data  平台通用配置数据
     * @param <array> $params
     *    @li customerId <int>  平台id
     *    @li categoryId <int>  大类id
     *    @li cityId <int>      城市id
     *    @li version  <string>  数据版本号
     * @return <array> 
     */
    private function categorySpecificDeal($data, $params) {
        $className = '';
        switch ($params['categoryId']) {
            case '5':
                $className = 'serviceliving';
                break;
            case '6':
                $className = 'vehicle';
                break;
            case '14':
                $className = 'secondmarket';
                break;
        }
        $className = ucfirst($className) . 'SpecailFormat';
        $filename = CLIENT_APP_DATASHARE . '/model/category_config/' . $className . '.class.php';
        if (file_exists($filename)) {
            require_once $filename;
            $formatObj = new $className();
            $data = $formatObj->specailFormat($data, $params);
        }
        return $data;
    }

    /**
     * 将客户端版本倒序排序
     * @param type $version
     */
    public static function versionrRSort($versions) {
        $len = count($versions);
        if ($len <= 1) {
            return $versions;
        }
        $key = $versions[0];
        $leftArr = array();
        $rightArr = array();
        for ($i = 1; $i < $len; $i++) {
            if (clientPara::versionCompare($versions[$i], $key) >= 0) {
                $leftArr[] = $versions[$i];
            } else {
                $rightArr[] = $versions[$i];
            }
        }
        $leftArr = self::versionrRSort($leftArr);
        $rightArr = self::versionrRSort($rightArr);
        return array_merge($leftArr, array($key), $rightArr);
    }

    /**
     * 获取新配置
     * @param type $params
     * @return boolean
     */
    public function getNewCategoryInfo($params) {
        $categoryInfo = array();
        $fileName = self::getDataPath($params['customerId'], $params['categoryId'], $params['virtualId'], $params['versionId']);
        $file = basename($fileName);
        $cacheKey = $file . '_new_category_info';
        $rData = $this->getCache($cacheKey);
        if (empty($rData)) {
            $fileName = self::getDataPath($params['customerId'], $params['categoryId'], $params['virtualId']);
            if (!file_exists($fileName))
                return false;
            $rData = @file_get_contents($fileName);
            if (strlen($rData) == 0)
                return false;
            $rData = @unserialize($rData);
            $this->setCache($cacheKey, $rData);
        }
        $dataModules = $rData['modules'];
        $dataItems = $rData['items'];
        $categoryId = 0;
        $modules = array();
        //按版本查找列表
        foreach ($dataModules as $moudleId => $dataModule) {
            $ver = '';
            foreach ($dataModule as $startVersion => $listModule) {
                //不限最后版本，或者当前版本大于最后版本
                if ($listModule['end_version'] == '' || clientPara::versionCompare($listModule['end_version'], $params['versionId']) < 0) {
                    $startVersions[] = $startVersion;
                }
            }
            //筛选版本
            $startVersions = self::versionrRSort($startVersions);
            foreach ($startVersions as $startV) {
                //选择模块最接近版本
                if (clientPara::versionCompare($params['versionId'], $startV) >= 0) {
                    $ver = $startV;
                    break;
                }
            }
            if ($ver) {
                $modules[] = $dataModules[$moudleId][$ver];
            }
        }
        #item 组合
        $data = array();
        $indexData = $modules[0];
        $indexData['itemList'] = array();
        foreach ($modules as $k => $module) {
            $itemIds = explode(",", $module['itemList']);
            $modules[$k]['itemList'] = array();
            foreach ($itemIds as $itemId) {
                $modules[$k]['itemList'][$dataItems[$itemId]['id']] = $dataItems[$itemId];
                $indexData['itemList'][$dataItems[$itemId]['id']] = $dataItems[$itemId];
            }
        }
        if ($categoryId == 0) {
            $data['info'] = array($indexData);
        } else {
            $data['info'] = $modules;
        }
        $data['version'] = $rData['version'];
        $data['allItems'] = $dataItems;
        $data['version'] = $params['versionId'] . $data['version'] . $params['cityId'];
        //各个频道可能需要根据参数特殊处理下配置
        $data = self::categorySpecificDeal($data, $params);
        if ($data['version'] != $params['version']) {
            $categoryInfo = $data;
        }
        return $categoryInfo;
    }

}
