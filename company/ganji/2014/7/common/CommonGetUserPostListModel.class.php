<?php

/**
 * @file CommonGetUserPostListModel.class.php
 * @brief 获取赶集叮咚帖子列表
 * @author lirui(lirui1@ganji.com)
 * @version 1.0
 * @date 2014-06-19
 */
require_once CLIENT_APP_DATASHARE . '/config/common/CommonDingDongConfig.php';
require_once CODE_BASE2 . '/app/post/PostPvNamespace.class.php';
require_once CODE_BASE2 . '/app/category/CategoryNamespace.class.php';
require_once CODE_BASE2 . '/app/adtype/AdTypeNamespace.class.php';

class CommonGetUserPostListModel extends MobileClientBaseModel {

    /**
     * 获取用户帖子列表
     * @param type $param
     * @return array('total'=>1,'posts'=>array()...) 
     */
    public static function getUserPostList($param) {
        $result = array();
        $type = $param['type'];
        switch ($type) {
            case CommonDingDongConfig::WANTED:
                $result = self::_wantedUserPostList($param);
                break;
            case CommonDingDongConfig::ERSHOUCHE:
                $result = self::_ershoucheUserPostList($param);
                break;
            case CommonDingDongConfig::SERVICESTORE:
                $result = self::_servicestoreUserPostList($param);
                break;
            default:
                $result = self::_normalUserPostList($param);
                break;
        }
        return $result;
    }

    /**
     * 获取招聘帖子列表
     * @param type     $param
     */
    private static function _wantedUserPostList($param) {
        $res = array('count'=>0, 'data'=>array());
        $withCount = 2;
        switch ($param['postType']) {
            case CommonDingDongConfig::$ALL:
                $filter_type = 404;
                break;
            case CommonDingDongConfig::$BANGBANG_POSTING:
                $filter_type = 425;
                break;
            case CommonDingDongConfig::$TOP_POSTING:
                $filter_type = 426;
                break;
            case CommonDingDongConfig::$NO_POSTING:
                $filter_type = 427;
                break;
            case CommonDingDongConfig::$VERIFYING:
                $filter_type = 428;
                break;
            case CommonDingDongConfig::$DELETED:
                $filter_type = 408;
                break;
        }
        $list = UserPostInterface::getStickyPostList($param['loginId'], $param['pageIndex'], $param['pageSize'], $filter_type, $withCount);
        $posts = array();
        foreach ($list['item_list'] as $item) {
            $majorInfo = CategoryNamespace::getMajorCategoryById($item['major_category_id']);

            //帖子状态
            $postInfo = UserPostInterface::getPost($item['user_id'], $item['puid']);
            $postStatus = self::getPostStatus($postInfo);

            //浏览次数           
            $ret = PostPvNamespace::getPvByPuids(array($item['puid']));
            $viewTime = !empty($ret[$item['puid']]) ? $ret[$item['puid']] : 0;

            //判断帖子类型
            $a = AdTypeNamespace::exists(AdTypeVars::TYPE_WANTED_TUIGUANG, $item['ad_types']);
            $b = AdTypeNamespace::exists(AdTypeVars::TYPE_WANTED_MINGQI_TAG, $item['ad_types']);
            $c = AdTypeNamespace::exists(AdTypeVars::TYPE_WANTED_MINGQI_ZHIWEI, $item['ad_types']);

            $tmp = array();
            $tmp['id'] = $item['post_id'];
            $tmp['puid'] = $item['puid'];
            $tmp['cityId'] = $item['city_id'];
            $tmp['categoryId'] = $item['category_id'];
            $tmp['majorCategoryId'] = $item['major_category_id'];
            $tmp['majorCategoryName'] = $majorInfo['name'];
            $tmp['title'] = $item['post_title'];
            $tmp['postState'] = $postStatus;
            $tmp['delReason'] = $postInfo['deleted_reason'];
            $tmp['postTime'] = date('Y-m-d H:i:s', $item['post_time']);
            $tmp['viewTimes'] = $viewTime;
            $tmp['isBang'] = 0;
            if ($a || $b || $c) {
                $tmp['isBang'] = 1;
            } 
            $tmp['isStick'] = $item['ad_types']>0?1:0;
            $tmp['isBrand'] = 0;
            if ($b || $c) {
                $tmp['isBrand'] = 1;
            }
            if ($b || $c) {
                $tmp['extendType'] = 2;
            } elseif ($a) {
                $tmp['extendType'] = 1;
            } else {
                $tmp['extendType'] = 0;
            }
            $posts[] = $tmp;
        }
        $res['count'] = $list['count'];
        $res['data'] = $posts;
        return $res;
    }

    /**
     * 获取二手车帖子列表
     * @param type     $param
     * @return array('total'=>1,'posts'=>array()...) 
     */
    private static function _ershoucheUserPostList($param) {
        require_once CODE_BASE2 . '/app/vehicle_bang/VehicleBangPostNamespace.class.php';

        $offset = $param['pageSize'] * $param['pageIndex'];
        switch ($param['postType']) {
            case CommonDingDongConfig::$ALL:
                $result = VehicleBangPostNamespace::getCommonPostList($param['loginId'], $param['cityId'], 0, 0, '', $param['pageSize'], $offset, true);
                break;
            case CommonDingDongConfig::$BANGBANG_POSTING:
                $result = VehicleBangPostNamespace::getCommonPostList($param['loginId'], $param['cityId'], 0, 1, '', $param['pageSize'], $offset, true);
                break;
            case CommonDingDongConfig::$TOP_POSTING:
                $result = VehicleBangPostNamespace::getStickyPostList($param['loginId'], $param['cityId'], $param['pageSize'], $offset, true);
                break;
            case CommonDingDongConfig::$NO_POSTING:
                $result = VehicleBangPostNamespace::getCommonPostList($param['loginId'], $param['cityId'], 0, 2, '', $param['pageSize'], $offset, true);
                break;
            case CommonDingDongConfig::$VERIFYING:
                $result = VehicleBangPostNamespace::getCommonPostList($param['loginId'], $param['cityId'], 1, 0, '', $param['pageSize'], $offset, true);
                break;
            case CommonDingDongConfig::$DELETED:
                $result = VehicleBangPostNamespace::getCommonPostList($param['loginId'], $param['cityId'], 2, 0, '', $param['pageSize'], $offset, true);
                break;
        }
        
        if (empty($result)) {
            return array();
        }

        $postList = array();
        foreach ($result[0] as $item) {
            $majorInfo = CategoryNamespace::getMajorCategoryByScriptIndex(11, $item['major_category']);
            
            //浏览次数
            $ret = PostPvNamespace::getPvByPuids(array($item['puid']));
            $viewTime = !empty($ret[$item['puid']]) ? $ret[$item['puid']] : 0;

            //是否是置顶帖
            $isSticky = in_array($item['post_type'], array(1, 10, 15)) ? 1 : 0;

            $postInfo = UserPostInterface::getPost($item['user_id'], $item['puid']);
            
            //帖子状态
            $postStauts = self::getPostStatus($postInfo);
            
            $post = array(
                'id' => (int)$item['id'],
                'puid' => (int)$item['puid'],
                'cityId' => $param['cityId'],
                'categoryId' => 6,
                'majorCategoryId' => (int)$item['major_category'],
                'majorCategoryName' => $majorInfo['name'],
                'title' => $item['title'],
                'postState' => $postStauts,
                'delReason' => $postInfo['deleted_reason'],
                'postTime' => date('Y-m-d H:i:s', $item['post_at']),
                'viewTimes' => $viewTime,
                'isBang' => 1,
                'isStick' => $isSticky,
                'isBrand' => 0,
                'punchCard' => 0,
                'extendType' => 1,
            );
            $postList[] = $post;
        }
        
        return array(
            'count' => $result[1],
            'data' => $postList
        );
    }

    /**
     * 获取服务帖子列表
     * @param type     $param
     * @return array('total'=>1,'posts'=>array()...) 
     */
    private static function _servicestoreUserPostList($param) {
    	require_once CLIENT_APP_DATASHARE . '/model/service_store/ServiceDingDongBenchModel.class.php';
    	$userId = $param['loginId'];
    	$cityId = $param['cityId'] ?$param['cityId'] : 12;
    	//postType帖子列表类型：0：表示全部 1：帮帮推广中，2：置顶中，3：未推广 4：审核中 5：删除
    	$postType = in_array($param['postType'],array('0','1','2')) ?$param['postType'] : '0';
    	$offset = $param['pageIndex']*$param['pageSize'];
    	$serviceDingDongModel = new ServiceDingDongBenchModel();
    	switch ($postType) {
            case CommonDingDongConfig::$BANGBANG_POSTING://该城市下帮帮店铺
            	$posts = $serviceDingDongModel->getBangPosts($userId, $cityId);
                break;
            case CommonDingDongConfig::$TOP_POSTING: //该城市下置顶店铺
            	$posts = $serviceDingDongModel->getDingPosts($userId, $cityId);
                break;
            default: //该城市下所有的店铺
            	$posts = $serviceDingDongModel->getAllPosts($userId, $cityId);
                break;
        }
        $data = array('count'=>0,'data'=>array());
        if(!empty($posts)){
        	$data['count'] = count($posts);
        	$rdata = array_slice($posts,$offset,$param['pageSize']);
        	if(!empty($rdata)){
        		foreach($rdata as $post){
        			$temp = array();
        			$temp['id'] = (int) $post['id']; //帖子id
        			$temp['puid'] = (int) $post['puid']; //帖子puid
        			$temp['cityId'] = (int) $post['cityId']; //城市id
        			$temp['categoryId'] = (int) $post['categoryId']; //大类id
        			$temp['majorCategoryId'] = (int) $post['majorCategoryId']; //小类id
        			$temp['majorCategoryName'] = (string) $post['majorCategoryName']; //小类名称
        			$temp['title'] = (string) $post['title']; //标题
        			//获取帖子状态
//         			$postState = self::getPostStatus($post);
//         			$temp['postState']['v'] = (int) $postState['v'];    //帖子状态值
//         			$temp['postState']['n'] = (string) $postState['n']; //展示名称
        			$temp['postState'] = $post['postState'];
        			if($temp['isStick']){ //置顶
        				$temp['postState']['n'] = '置顶中';
        			}
        			$temp['delReason'] = '';   //删除原因
        			$temp['postTime'] = (string) date('Y-m-d H:i:s', $post['post_at']); //发布时间
        			//浏览量
        			$viewNums = PostPvNamespace::getPvByPuids(array($store['puid']));
        			$temp['viewTimes'] = $viewNums[$store['puid']] > 0 ? (int)$viewNums[$store['puid']] : 0;
        			
        			$temp['isBang'] = (int) $post['isBang'];          //是否是帮帮帖,0否，1是
        			$temp['isStick'] = (int) $post['isStick'];        //是否是置顶帖,0否，1是
        			$temp['isBrand'] = (int) 0;                       //是否是品牌帖,0否，1是
        			$temp['punchCard'] = (int) $post['punchCard'];    //是否能打卡 0表示不能，1表示能
        			$temp['CanEdit'] = (int) $post['CanEdit'];        //是否能编辑
        			$temp['CanDelete'] = (int) $post['CanDelete'];    //是否能删除
        			$temp['CanRefresh'] = (int) $post['CanRefresh'];  //是否能刷新
        			$temp['RefreshUrl'] = $post['RefreshUrl'];
        			$temp['extendType'] = (int) 0;                    //1表示帮帮推广 2表示品牌推广
        			$data['data'][] = $temp;
        		}
        	}
        	
        }
    	return $data;
    }

    /**
     * 获取其他身份帖子列表
     * @param type     $param
     * @return array('total'=>1,'posts'=>array()...) 
     */
    private static function _normalUserPostList($param) {
        $result = array();
        $offset = $param['pageIndex'] * $param['pageSize'];
        $postDatas = self::getPostsByUcId($param['loginId'], $offset, $param['pageSize'], $param['postType']);
        $result['count'] = $postDatas['count']; //帖子总数
        $posts = $postDatas['item_list']; //格式化字段
        foreach ($posts as $post) {
            $majorCategory = CategoryNamespace::getMajorCategoryById($post['major_category_id']);
            $data['id'] = (int) $post['post_id']; //帖子id
            $data['puid'] = (int) $post['puid']; //帖子puid
            $data['cityId'] = (int) $post['city_id']; //城市id
            $data['categoryId'] = (int) $post['category_id']; //大类id
            $data['majorCategoryId'] = (int) $post['major_category_id']; //小类id
            $data['majorCategoryName'] = (string) $majorCategory['name']; //小类名称
            $data['title'] = (string) $post['post_title']; //标题
            //获取帖子状态
            $postState = self::getPostStatus($post);
            $data['postState']['v'] = (int) $postState['v']; ////帖子状态值
            $data['postState']['n'] = (string) $postState['n']; //展示名称
            $data['delReason'] = (string) $post['deleted_reason']; //删除原因
            $data['postTime'] = (string) date('Y-m-d H:i:s', $post['post_time']); //发布时间
            $data['viewTimes'] = (int) PostPvNamespace::getPvByPuids($post['puid']); //获取浏览量; //浏览次数
            $data['isBang'] = (int) 0; //是否是帮帮帖,0否，1是
            $data['isStick'] = (int) $post['sticky_status'] == 6 ? 1 : 0; //是否是置顶帖,0否，1是 
            $data['isBrand'] = (int) 0; //是否是品牌帖,0否，1是 
            $data['punchCard'] = (int) 0; //是否能打卡 0表示不能，1表示能
            $data['extendType'] = (int) 0; //1表示帮帮推广 2表示品牌推广
            $result['data'][] = $data;
        }
        return $result;
    }

    public static function getPostsByUcId($userId, $offset, $pageSize, $postType = 0) {
        switch ($postType) {
            case CommonDingDongConfig::$ALL:
                $filter_type = 100; //所有在线帖子
                break;
            case CommonDingDongConfig::$TOP_POSTING:
                $filter_type = 901; //置顶中的帖子
                break;
            case CommonDingDongConfig::$VERIFYING:
                $filter_type = 103; //审核中
                break;
            case CommonDingDongConfig::$DELETED:
                $filter_type = 104; //已删除
                break;
        }
        $posts = UserPostInterface::getPostList($userId, $offset, $pageSize, $filter_type, 2);
        return $posts;
    }

    /**
     * 获取帖子状态
     * @param type $post
     */
    public static function getPostStatus($post) {
        $result = array();
        //已过期
        if ($post['is_archived'] == 1) {
            $result['v'] = 6;
        }
        $stickyStatus = $post['sticky_status'];
        //已删除(个人删除)
        if ($post['display_status'] <= 0 && $post['display_status'] > -2) {
            $result['v'] = 2;
        } elseif ($post['display_status'] == -2) {
            $result['v'] = 2;
        } elseif ($post['display_status'] == -3) {
            $result['v'] = 2;
        } elseif (in_array($post['display_status'], array(1, -4)) && ($post['ad_types'] < 1 || in_array($post['ad_types'], array(0x1000000, 0x2000000))) && $stickyStatus != 4) {
            $result['v'] = 2;
            //等待付款
        } elseif ($stickyStatus == 1) {
            //正常显示
            if ($post['display_status'] == 3) {
                $result['v'] = 1;
                //待审核
            } elseif ($post['display_status'] == 2) {
                $result['v'] = 3;
            }
            //推广审核中
        } elseif ($stickyStatus == 2) {
            $result['v'] = 7;
            //未通过推广审核
        } elseif ($stickyStatus == 4) {
            $result['v'] = 8;
            //推广修改审核中
        } elseif ($stickyStatus == 3) {
            $result['v'] = 9;
            //未通过推广修改审核
        } elseif ($stickyStatus == 5) {
            $result['v'] = 10;
            //置顶推广中
        } elseif ($stickyStatus == 6) {
            $result['v'] = 5;
            //待审核状态
        } elseif ($post['display_status'] == 2) {
            $result['v'] = 3;
            //正常显示
        } elseif ($post['display_status'] == 3) {
            $result['v'] = 1;
        }
        //如果是已删除帖没有相关操作
        if ($result['v'] == 2) {
            //一天之内的显示 待审核
            if ($post['display_status'] == -2 && (time() - $post['post_time']) < 86400) {
                $result['v'] = 3;
            }
        }
        $result['n'] = CommonDingDongConfig::$DISPLAY_POST_STATUS[$result['v']];
        return $result;
    }

}

?>
