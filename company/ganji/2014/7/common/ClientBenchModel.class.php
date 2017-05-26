<?php

/**
 * @file ClientBenchModel.class.php
 * @brief 获取赶集叮咚工作台配置处理类
 * @author jiajianming(jiajianming@ganji.com)
 * @version 1.0
 * @date 2014-06-18
 */
require_once CLIENT_APP_DATASHARE . '/config/common/CommonDingDongConfig.php';
include_once CODE_BASE2 . '/interface/uc/UserInterface.class.php';
include_once CLIENT_APP_DATASHARE . '/model/ClientTrading.class.php';
include_once CODE_BASE2 . '/interface/uc/UserPostInterface.class.php';
require_once CODE_BASE2 . '/app/sticky/StickyRefreshNamespace.class.php';
include_once CODE_BASE2 . '/app/self_sticky/order/api/SelfStickyGoodsApi.class.php';
require_once CODE_BASE2 . '/util/http/Curl.class.php';
require_once CODE_BASE2 . '/app/geo/GeoNamespace.class.php';
include_once CODE_BASE2 . '/app/wanted_findjob/DownloadResumeNamespace.class.php';
include_once CODE_BASE2 . '/app/wanted_findjob/TuiguangNamespace.class.php';
include_once CODE_BASE2 . '/app/wanted_findjob/JobCrmNamespace.class.php';
include_once CODE_BASE2 . '/interface/jobcenter/FindjobReceiveInterface.class.php';
include_once CODE_BASE2 . '/app/bang/BangNamespace.class.php';
require_once CODE_BASE2 . '/app/wanted_findjob/WantedReserveRefreshNamespace.class.php';
require_once CLIENT_APP_USERS . '/model/ClientGetUserExtInfo.class.php';

class ClientBenchModel {

    private static $allCategory = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14); //所有类目

    /**
     * 获取赶集叮咚工作台用户信息
     * @param <type> $userId  登录用户的ucId
     * @param <type> $type  工作台类型
     */

    public static function getUserBenchInfo($param) {
        $bencheInfo = array();
        $userInfo = self::getBaseUserInfo($param);
        $cityId = $param['cityId']; //工作台需要分城市显示，需要传入城市信息
        $type = $param['type'];
        switch ($type) {
            case CommonDingDongConfig::WANTED:
                $bencheInfo = self::_wantedBenchInfo($userInfo);
                break;
            case CommonDingDongConfig::ERSHOUCHE:
                $bencheInfo = self::_ershoucheBenchInfo($userInfo);
                break;
            case CommonDingDongConfig::SERVICESTORE:
                $bencheInfo = self::_servicestoreBenchInfo($userInfo, $cityId);
                break;
            default:
                $bencheInfo = self::_nomalBenchInfo($userInfo);
                break;
        }
        $bencheInfo = self::format($bencheInfo);
        return $bencheInfo;
    }

    /**
     * 获取基础用户数据
     * @param type $userId
     * @param type $type
     * @return int
     */
    private static function getBaseUserInfo($param) {
        $userId = $param['loginId'];
        $type = $param['type'];
        $userInfo = UserInterface::getUser($userId);
        $userInfo['param'] = $param;
        //type对应的category
        $category = isset(CommonDingDongConfig::$typeCategory[$type]) ? CommonDingDongConfig::$typeCategory[$type] : NULL;
        //刷新点数
        $refreshPoints = StickyRefreshNamespace::getRefreshCountByUserId($userId);
        //计算刷新点数
        if ($type == 0) {
            //其他身份的为全部分类点数和
            $refreshPoint = array_sum($refreshPoints);
        } else {
            if ($category == 5) {
                //本地生活服务和本地商务服务
                $refreshPoint = $refreshPoints[4] + $refreshPoints[5];
            } else {
                $refreshPoint = $refreshPoints[$category];
            }
        }

        //用户账户余额
        $mTrading = new ClientTrading();
        $account = $mTrading->getUserAccount($userId);
        //从uc获取用户所有帖子数
        $allNumArr = UserPostInterface::getPostList($userId, 0, 0, 100, 3);
        $userInfo['userName'] = $userInfo['user_name']; //用户名称
        $bang = BangNamespace::getUserCooperationYear($userId, $category);
        $userInfo['bangAge'] = $bang[$userId]['years']; //帮帮年限
        $userInfo['account'] = ($account->balance / 100); //账号余额
        $userInfo['refreshPoints'] = (int) $refreshPoint; //自助刷新点数，自助提供
        $userInfo['allNum'] = (int) $allNumArr['count']; //所有帖子数
        $userInfo['stickNum'] = (int) SelfStickyGoodsApi::getUserStickyCount($userId, $category); //置顶帖子数
        $userInfo['visitNum'] = (int) self::getVisitNum($param); //今日访问数
        //判断是否是这个频道的帮帮用户
        $bangList = ClientGetBangInfo::getUserBangList($userId);
        if ($bangList[CommonDingDongConfig::$bangStr[$type]] == 1) {
            $userInfo['isBang'] = 1;
        }
        //删除不必要字段
        unset($userInfo['user_name']);
        unset($userInfo['username']);
        unset($userInfo['password']);
        return $userInfo;
    }

    /**
     * 获取普通（其他类型）工作台用户信息
     * @param <type> $userInfo
     * @return <array>  $res
     */
    private static function _nomalBenchInfo($userInfo) {
        return $userInfo;
    }

    /**
     * 获取招聘工作台用户信息
     * @param <array> $userInfo
     * @return <array>  $res
     */
    private static function _wantedBenchInfo($userInfo) {
        $userId = $userInfo['user_id'];
        //招聘币剩余
        $recruitCoin = DownloadResumeNamespace::getRecruitCoin($userId);
        //短信包剩余
        $smsPackage = TuiguangNamespace::getSmsNumOfInviteByUserId($userId);
        //帮帮套餐详情
        $payStatus = JobCrmNamespace::getUserPayStatus($userId);
        //收到简历数剩
        $num = FindjobReceiveInterface::getReceiveCountByUserID($userId, true);
        //剩余刷新数
        $totalRefreshCount = JobCrmNamespace::getRefreshNumByUserId($userId);
        $todayRefreshCount = WantedReserveRefreshNamespace::getAlreadyRefreshCountByUserId($userId);
        $refreshNum = intval($totalRefreshCount - $todayRefreshCount['ret']);
        //帮帮推广帖子数
        $bangbangInfo = UserPostInterface::getStickyPostList($userId, 0, 0, 409, 3);
        $banNum = $bangbangInfo['count'];

        $res = array(
            'smsPackage' => $smsPackage,
            'wantedCurrency' => $recruitCoin,
            'bangPackage' => array(
                'type' => $payStatus['is_pay_user'],
                'expire' => $payStatus['expired_at'],
            ),
            'newReceiveResumeNum' => $num,
            'receiveResumeUrl' => 'http://sta.ganji.com/ng/app/client/app/zp/index.html#app/client/app/zp/resume/view/icenter_page.js',
            'downResumeUrl' => 'http://sta.ganji.com/ng/app/client/app/zp/index.html#app/client/app/zp/resume/view/icenter_page.js',
            'resumeUrl' => 'http://sta.ganji.com/ng/app/client/app/zp/index.html#app/client/app/zp/resume/view/index_page.js',
            'refreshNum' => $refreshNum,
            'bangNum' => $banNum,
        );
        $res = array_merge($userInfo, $res);
        return $res;
    }

    /**
     * 获取二手车工作台用户信息
     * @param <array> $userInfo
     * @return <array>  $res
     */
    private static function _ershoucheBenchInfo($userInfo) {
        require_once CODE_BASE2 . '/app/bang/model/BangCompanyModel.class.php';
        require_once CODE_BASE2 . '/app/bang/BangVehicleNamespace.class.php';
        require_once CODE_BASE2 . '/app/vehicle_bang/vars/VehicleBangVars.class.php';
        require_once CODE_BASE2 . '/app/vehicle_bang/util/VehicleBangTcUtil.class.php';
        require_once CODE_BASE2 . '/app/vehicle_bang/util/VehicleBangPostNamespace.class.php';

        $res = array();
        $cityId = intval(clientPara::getArg('cityId'));
        $bangInfo = BangCompanyModel::getRow('*', "user_id={$userInfo['user_id']} and category_id=6");

        if (empty($bangInfo)) {
            return $userInfo;
        }

        //获取城市库存信息
        $bangBalanceList = BangVehicleNamespace::getBangBalanceList($userInfo['user_id'], true);
        $cityBalanceInfo = isset($bangBalanceList[$cityId]) ? $bangBalanceList[$cityId] : array();

        //判定免费店铺还是付费的
        $res['bangPackage']['type'] = empty($bangBalanceList) ? VehicleBangVars::STORE_TYPE_FREE : VehicleBangVars::STORE_TYPE_PAID;
        if (!empty($cityBalanceInfo)) {
            $res['bangPackage']['expire'] = $cityBalanceInfo['MaxEndTime'];
        }

        //获取刷新次数
        if ($res['bangPackage']['type'] == VehicleBangVars::STORE_TYPE_PAID) {
            list($res['refreshNum']) = VehicleBangTcUtil::getRefreshPoint($userInfo['user_id'], $cityId);
        } else {
            $res['refreshNum'] = VehicleBangVars::FREE_BANG_PROMOTION_LIMIT;
        }

        //获取可推广帖子条数
        $promotionLimit = VehicleBangTcUtil::getPromoteLimit($userInfo['user_id'], $cityId);
        $res['bangTotalNum'] = $promotionLimit;

        //获取推广中的帖子条数
        $promotionCount = VehicleBangPostNamespace::getPromotePostCount($userInfo['user_id'], $cityId);
        $res['bangNum'] = $promotionCount;

        $res = array_merge($userInfo, $res);
        return $res;
    }

    /**
     * 获取服务店铺工作台用户信息
     * @param <array> $userInfo
     * @param <int> $cityId
     * @return <array> $res
     */
    private static function _servicestoreBenchInfo($userInfo, $cityId) {
        include_once CODE_BASE2 . '/app/bang/BangNamespace.class.php';
        require_once CODE_BASE2 . '/app/post/PostNamespace.class.php';
        require_once CODE_BASE2 . '/app/service_store/ServiceStorePostClientNamespace.class.php';
        require_once CODE_BASE2 . '/app/service_store/ServiceStoreRefreshNamespace.class.php';

        $userId = $userInfo['user_id'];
        $refreshNamespace = new ServiceStoreRefreshNamespace();
        //剩余增值刷新 
        $addedRefresh = 0;
        //剩余打卡
        $cardRemain = $refreshNamespace->getUserCityCardRemain($userId, $cityId);
        //剩余刷新点数
        $refreshPoint = $refreshNamespace->getUserRefreshPoint($userId);

        $categoryId = 5;
        //返回该城市的店铺总数量
        $postApi = PostNamespace::getPostApiByCategory($categoryId);
        $storePostList = ServiceStorePostClientNamespace::getAllStorePost($userId, $postApi);
        $allNum = 0;
        if (!empty($storePostList)) {
            foreach ($storePostList as $key => $post) {
                if ($post['cityobj']['id'] == $cityId) {
                    $allNum ++;
                }
            }
        }
        $userInfo['allNum'] = $allNum;
        //帮帮相关
        $bangObj = BangNamespace::getBangInfoByUserIdFromCrm($userId, $categoryId, $cityId);
        $bangIsValid = $bangObj->is_valid;    //帮帮是否有效
        $bangPosts = $bangObj->stickyList;    //已经帮帮置顶的店铺
        $bangYears = $bangObj->year_fmt;      //帮帮年限
        $bangExpire = $bangObj->end_time;     //帮帮失效时间
        $bangStickyNum = $bangObj->sticky_num; //帮帮可置顶个数 > 0 付费帮帮  =0 免费帮帮
        $bangNum = $bangIsValid ? count($bangPosts) : 0;

        if ($bangIsValid) {
            $bangPackage = array(
                'type' => $bangStickyNum > 0 ? '付费' : '免费',
                'expire' => date('Y-m-d H:i:s', $bangExpire),
            );
            //剩余增值刷新 
            $addedRefresh = $refreshNamespace->getUserCityAddedRefreshPoint($userId, $cityId);
        } else { //帮帮失效
            $bangPackage = array();
        }
        $res = array(
            "bangNum" => $bangNum,
            "bangPackage" => $bangPackage,
            "bangTotalNum" => $bangStickyNum, //帮帮的可推广数
            "punchCardNum" => $cardRemain, // (int) 打卡剩余数量
            "refreshNum" => $addedRefresh, //(int)增值剩余次数
            "refreshPoints" => $refreshPoint, //(int)刷新剩余点数
        );
        $res = array_merge($userInfo, $res);
        return $res;
    }

    /**
     * 获取浏览量
     * @param type $param
     * @return type
     */
    public static function getVisitNum($param) {
        $allCategoryStr = implode(",", self::$allCategory);
        $cityId = $param['cityId'];
        $cityInfo = GeoNamespace::getCityById($cityId);
        $cityCode = $cityInfo['city_code'];
        $type = $param['type'];
        //如果是其他身份则获取所有类别
        if (!isset(CommonDingDongConfig::$typeCategory[$type])) {
            $categoryStr = $allCategoryStr;
        } else if ($type == CommonDingDongConfig::SERVICESTORE) {
            //如果是服务则为本地服务和商务服务一起
            $categoryStr = implode(",", array(4, 5));
        } else {
            $categoryStr = CommonDingDongConfig::$typeCategory[$type];
        }
        $token = clientPara::hex2bin(HeaderCheck::$param['token']);
        $url = "http://webim.ganji.com/index.php?op=getVisitorCount&dest=client&category=$categoryStr&city=$cityCode&token={$token}&mobileType=" . $param['customerId'];
        $cUrl = new Curl();
        $result = $cUrl->get($url);
        $result = json_decode($result, true);
        $result = (int) $result['data']['count'];
        return $result;
    }

    /**
     * 格式化字段
     * @param type $userInfo
     */
    public static function format($userInfo) {
        $result = array();
        $result['userName'] = (string) $userInfo['userName'];
        $result['bangAge'] = (int) $userInfo['bangAge'];
        $result['account'] = (float) $userInfo['account'];
        $result['smsPackage'] = (int) $userInfo['smsPackage'];
        $result['wantedCurrency'] = (int) $userInfo['wantedCurrency'];
        $result['refreshNum'] = (int) $userInfo['refreshNum'];
        $result['punchCardNum'] = (int) $userInfo['punchCardNum'];
        $result['refreshPoints'] = (int) $userInfo['refreshPoints'];
        $result['bangPackage']['type'] = (string) $userInfo['bangPackage']['type'];
        $result['bangPackage']['expire'] = (string) $userInfo['bangPackage']['expire'];
        $result['newReceiveResumeNum'] = (int) $userInfo['newReceiveResumeNum'];
        $result['receiveResumeUrl'] = (string) $userInfo['receiveResumeUrl'];
        $result['downResumeUrl'] = (string) $userInfo['downResumeUrl'];
        $result['resumeUrl'] = (string) $userInfo['resumeUrl'];
        $result['allNum'] = (int) $userInfo['allNum'];
        $result['bangNum'] = (int) $userInfo['bangNum']; //帮帮推广中的帖子数（已经用过的帮帮数）
        $result['bangTotalNum'] = (int) $userInfo['bangTotalNum']; //帮帮可推广数
        $result['isBang'] = (int) $userInfo['isBang']; //是否是帮帮用户
        $result['isBrandBang'] = (int) $userInfo['isBrandBang']; //是否是品牌帮帮用户
        $result['stickNum'] = (int) $userInfo['stickNum'];
        $result['visitNum'] = (int) $userInfo['visitNum'];
        return $result;
    }

}
