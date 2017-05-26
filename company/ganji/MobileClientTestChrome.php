<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        require_once 'Timer.php';
        error_reporting(E_ERROR);
//        define('URL', 'http://mobds.ganjistatic3.com/datashare/');
//        define('URL', 'http://mobds.ganjistatic3.com/users/');
//        define('URL', 'http://mobds.ganjistatic3.com/push');
//        define('URL', 'http://dev.mobds.ganjistatic3.com/datashare/');
//        define('URL', 'http://dev.mobds.ganjistatic3.com/push/');
//        define('URL', 'http://dev.mobds.ganjistatic3.com/users/');
        define('URL', 'http://mobtestweb6.ganji.com/datashare/');
//        define('URL', 'http://mobds.ganji.cn/users/');

        $header = array(
            // 'interface:TestInterface',
            // 'interface: GetMessagePhone',
            // 'interface: PushTest',
            // 'interface: userRegister',
            // 'interface:UserPhoneAuth',
            // 'interface:CheckVersion',
            // 'interface:GetLastGeography',
            // 'interface:GetLastCategories',
            // 'interface:GetLastCategory',
            // 'interface:GetMajorCategoryFilter',
            // 'interface: GetMajorCategoryFilters',
            'interface:userLogin',
            // 'interface:autoLogin',
            // 'interface:PushSettingSync',
            // 'interface:UserSubscribe',
            // 'interface:VotePost',
            // 'interface:RefreshPost',
//            'interface:UserFavorites',
            // 'interface:GetUserJobList',
            // 'interface: GetUserFindJobList',
            // 'interface:GetPostsByAppId',
            // 'interface:GetPostsByPhone',
            // 'interface:GetPostsByUserId',
            // 'interface:GetUserInfo',
            // 'interface:GetWebSearchCount',
            // 'interface:GetPost',
            // 'interface: GetPostByPuid',
            // 'interface: CommonCategoryInfo',
            // 'interface:CommonSearchHotWord',
            // 'interface:GetBackPassword',
            // 'interface: SearchPostsByJson',
            // 'interface: SearchPostsByJson2',
            // 'interface:SearchLifePostList',
            // 'interface: AndroidInterFace',
            // 'interface: ajaxSuggestion',
            // 'interface: GetLastPostTemplates',
            // 'interface: GetNewCategories',
            // 'interface: ImSubscribe',
            // 'interface: AddImMessageToPushQ',
            // 'interface: getClientCommercial',
            // 'interface: GetNotRecvMsgs',
            // 'interface: GetErshoucheTagOrCar',
            // 'interface: PostSubscribe',
            // 'interface: CommonToolbox',
            // 'interface: GetLastPostTemplates',
            // 'interface: SubscribePosts',
            // 'interface: PostPvStat',
            // 'interface: register',
            // 'interface: ManageUserFavorite',
            // 'interface: GetOperationNotice',
            // 'interface: GetActivityInfo',
            // 'interface: CreatePostNew',
            // 'interface: CollectPushRegId',
            // 'interface: SaveComment',
            // 'interface: SimpleLivesCreateNeedsPost',
            // 'interface: SimpleLivesGetMyNeedsPostList',
            // 'interface: CommonOperateList',
            // 'interface: LoginOut',
            // 'interface: CommonConsultList',
            // 'interface: RechargeCallBack',
            // 'interface: UploadImages',
            // 'interface: CommonSearchIR',
            // 'interface: GetSpreadInstallData',
            // 'interface: SecondmarketFilter',
            // 'interface: SearchCityByLocation',
            // 'interface: VehicleSelectedPost',
            // 'interface: CommonSubscribe',
            // //
            // //
            // 
            //'interface: Test', #测试
//            'interface:CommonToolbox', #客户端工具箱相关接口
//            'interface: CommonOperateList', #获取运营banner列表接口
            'interface:CommonCategoryInfo', #客户端获取大类首页配置的接口
//            'interface: CommonLotteryInfo', #房产抽奖接口
//            'interface:CommonConsultList', //资讯列表
//            'interface:CommonSubscribe', //订阅相关
//            'interface:CommonBenchInfo', //赶集叮咚工作台
            'interface:CommonGetUserPostList', //赶集叮咚帖子列表接口
//            'interface:CommonPostOperate', //赶集叮咚帖子处理接口
            'contentformat:json2',
            'agency:appstore',
            'customerId:705',
            'clientAgent:lenvon1024',
            // 'Content-Type:application/x-www-form-urlencoded;',
            'versionId:1.7.0',
            'GjData-Version:1.0',
            'model:Generic/iPhone',
            // 'model:Symbian',
            'userId:63384339918B55C356598199C6F45ECC', //90667226, loginID:50284248
            'deviceId:323539557a675a416b4d6d2f50794d34323558535a366651',
            'token:323539557a675a416b4d6d2f50794d34323558535a366651',
            // 'imei:',
            'idfa:685B35813CsfA8',
        );
        $jsonArgs = array(
            'cityScriptIndex' => 0,
            'categoryId' => 14,
            // 'url' => 'qitachuang',
            // 'keyword' => 'a',
            'majorCategoryScriptIndex' => '-1000',
            'pageIndex' => 0,
            'pageSize' => 50,
            'queryFilters' => array(
            // array('name' => 'degree', 'operator' => '=', 'value' => 2),
            // array('name' => 'price', 'operator' => '=', 'value' => array('up' => '10000', 'low' => '5000')),
            // array('name' => 'price', 'up_range' => '200', 'low_range' => '500'),
            // array('name' => 'price', 'operator' => '=', 'value' => 3),
            // array('name' => 'huxing_shi', 'operator' => '=', 'value' => 1),
            // array('name' => 'base_tag', 'operator' => '=', 'value' => 'shouji'),
            // array("name"=>"latlng","operator"=>"=","value"=>"30.43967,113.28139,3000"),
            ),
            // 'price' => array(
            // 	'2000', '10000',
            // ),
            'andKeywords' => array(
                0 => array(
                    'value' => '乐扣水杯�?��',
                    'operator' => '=',
                    'name' => 'title',
                ),
            ),
            'sortKeywords' => array(
                '0' => array(
                    'field' => 'refresh_at',
                    'sort' => 'desc',
                ),
            ),
        );
        $jsonArgs = array(
            'puid' => '93589219',
            'city' => 12,
            'categoryId' => 7,
            'majorCategoryScriptIndex' => 1,
        );
        $post_fields = array(
            // "jsonArgs" => json_encode($jsonArgs),
            // 'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"7","majorCategoryScriptIndex": 1, "pageIndex":"3","pageSize":"20","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"refresh_at","sort":"desc"}]}',
            // "jsonArgs" => '{"searchType":0,"cityScriptIndex":0,"latlng":"40.034113,118.311913","searchConditionIndex":null,"pageIndex":0,"pageSize":10}',
            // 'jsonArgs' => '{"ownerId":30041,"puid":91915208,"ownerType":1,"commentType":1,"commentGrade":1,"content":"很不好的服务态度,很不周到", "loginId":"50005439"}',
            'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"2","majorCategoryScriptIndex":"2" ,"pageIndex":0, "pageSize":"100","queryFilters":[],"andKeywords":[{"name":"title","value":""}],"sortKeywords":[{"field":"post_at","sort":"desc"}]}',
            // 'jsonArgs' => '{"majorCategoryScriptIndex":"1","customerId":"801","categoryId":"7","pageSize":"10","cityScriptIndex":"0","sortKeywords":[{"field":"post_at","sort":"desc"}],"queryFilters":[],"pageIndex":"0"}',
            // 'jsonArgs' => '{"action":"2","loginId":"50009853","state":"0","receive_post_state":"1","receive_msg_state":"0","receive_im_state":"0","info":{"master":"1"},"resume":"1","frequency":"1440","remind":"1_0_0_1","starttime":"0","endtime":"24"}',
            // "jsonArgs" => '{"imageCount":"1"}',
            'subscribeIds' => '{"123456"}',
            // 'schema' => 1,
            // 'subscribeTime' => 0,
            // 'showType' => 0,
            // 'subscribeID' => '394',
            // 'image[0]' => '@' . realpath('./logo.png'),
//            'image' => '@' . realpath('./logo_2.png'),
            'regId' => '2rLO7IuSv4q7HwgEaPpEiw5Y0PuOXoOlTenHKp1vW5y1Kdo6lh0Qjg5s5eaSbLbcDFhUaXmQvjL8lOERH+DwUgYJagzuJ/4saFj1tO7eByXdQbO3AtSlSWVGy9idTBLV',
            // 'newMul' => '1',
            'pageSite' => 1,
            'pageType' => 1,
            // 'subscribe_push' => 1,
            'method' => 1,
            'step' => 3,
            'methodKey' => '13301395641',
            'password' => 'hhh111',
//            'loginType' => 1,
            'cityScriptIndex' => 0,
            'loginId' => 342860671,
            'majorCategoryScriptIndex' => 10,
            'categoryId' => 7,
            // 'allPuid' => 1,
            'postId' => '1207531',
            'cityId' => 12,
            'act' => '5',
            'type' => 1,
            'phone' => '13282987627',
            'code' => '352934',
            // 'user_id' => '50078656',
            'loginName' => 'zzwtest20',
            'password' => 'q123456',
            // 'captcha' => 'mmmm',
            // 'ucUserId' => '28260405',
            // 'ucenterUserID' => 28260405,
            'ucenterUserID' => 140298739,
            'subscribeID' => 26098,
            'frequency' => 1440,
            // 'phone' => 15810508809,
            'pageIndex' => 0,
            'pageSize' => 10,
            // 'isDeleted' => 1,
            // 'virtualId' => 1,
            // 'keyword' => '菱帅',
            // 'categoryId' => 6,
            // 'searchAll' => 1,
            // 'image_count' => 1,
// 	// 'images' => '["gjfs07/M07/38/CF/wKhzVlN0lO,o6zx-AACLwgd4W3A243_770-470_7-5.jpg"]',
// 	'title' => '闲置的戴�?3二手显示器出�?3282987627',
// 	// 'major_category_script_index' => 4,
// 	// 'findjob_fulltime_url' => '11:qzshichangyingxiao',
// 'deal_type' => 1,
// 'agent' => 1,
// 	'district_id'=>'12',
//         'street_id'=>'14',
//         'findjob_age'=>'25',
//         'findjob_sex'=>'1',
//         'findjob_salary' => '3',
//         'findjob_degree'=> 5,
//         'findjob_period' => '2',
//         'findjob_open_status' => '1',
//         'price' => '980',
//         'person' => '谢先�?,
//         'phone' => '13282987627',
//         'description' => '用了1年，搬家更换，S2340L�?成新 13282987627',
            'pageIndex' => 0,
            'pageSize' => 50,
//////          'majorCategoryScriptIndex ' => 1,
//            'pageType' => 1,
            'cityId' => 12,
            'loginId' => '338305613',
            'type' => 2,
            'postType' => 0,
        );
        $timer = new Timer();
        $timer->start();

        set_time_limit(500);
        $ch = curl_init();
        $url = URL;
        echo $url;

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
// curl_setopt($ch, CURLOPT_NOBODY, true);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        echo '<br />';
        $headerArr = explode("\n", $result);
        foreach ($headerArr as $key => $item) {
            if (stripos($item, 'ChromeLogger-Data') > 0) {
                header($item);
            }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $timer->stop();
        echo $timer->spent('ms') . ' ms<br />';
        $resArr = json_decode($result, true);
        echo '<pre>';
        var_dump($resArr);
        echo '</pre>';
        echo '<br />';
        var_dump($result);
//search_debug=2225342
        ?>
    </body>
</html>
