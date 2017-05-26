<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        require_once 'Timer.php';
        error_reporting(E_ERROR);
//        define('URL', 'http://mobds.ganjistatic3.com/users/');
//        define('URL', 'http://mobds.ganjistatic3.com/datashare/');
//        define('URL', 'http://mobds.ganjistatic3.com/push/');
        define('URL', 'http://mobtestweb6.ganji.com/datashare/');
//        define('URL', 'http://mobds.ganji.cn/datashare/');

        $header = array(
//            'interface: GetPost',
//            'interface: getClientCommercial',
//            'interface: GetActivityInfo',
//            'interface: SearchPostsByJson2',
//            'interface: UserFavorites',
//            'interface: LoginOut',
//            'interface:GetLastCategory',#获取大类下小类信息，如房产大类下包括出租房、出售房、合租房
            // 'interface:TestInterface',
            // 'interface: GetMessagePhone',
            // 'interface: PushTest',
//            'interface: userRegister', #第一次安装，注册设备信息，返回注册后的userid
//            'interface: UserPhoneAuth',
//            'interface: register',
//            'interface: userLogin',
//            'interface: autoLogin',
//            'interface:CommonCategoryInfo',
            //'interface:GetLastGeography', #城市区域的下属地标信息，如海淀 =>清河,小营,西二旗,上地
//            'interface:GetMajorCategoryFilter', #传递小类，获取小类的条件列表如宠物=> 区域,品种，价格
//            'interface:GetLastCategories',
            // 'interface:GetMajorCategoryFilter',
            // 'interface:userLogin',
//            'interface:CommonToolbox',
            // 'interface:PushSettingSync',
            // 'interface:UserSubscribe',
            // 'interface:VotePost',
            // 'interface:RefreshPost',
            // 'interface:UserFavorites',
            // 'interface:GetUserJobList',
            // 'interface:GetPostsByAppId',
            // 'interface:GetPostsByPhone',
            // 'interface:GetPostsByUserId',
            // 'interface:GetUserInfo',
            // 'interface:GetWebSearchCount',
            // 'interface:GetPost',#获取帖子详情
            // 'interface: GetPostByPuid',
            'interface: CommonLotteryInfo',
//             'interface:CommonSearchHotWord',
            // 'interface:GetBackPassword',
//            'interface: SearchPostsByJson', #客户端帖子搜索接口，通过参数jsonArgs
//            'interface:userLoginOut',
//            'interface:CommonToolbox',
            // 'interface:SearchLifePostList',
            // 'interface: AndroidInterFace',
            // 'interface: ajaxSuggestion',
            // 'interface: GetMajorCategoryFilters',
//            'interface: GetNewCategories', #求职简历，新类目结构下载,如技工=>电工,木工,焊工水工
            // 'interface: ImSubscribe',
//            'interface: AddImMessageToPushQ',
            // 'interface: getClientCommercial',
            // 'interface: GetNotRecvMsgs',
            // 'interface: GetErshoucheTagOrCar',
            // 'interface: PostSubscribe',
            // 'interface: CommonToolbox',
//            'interface: GetLastPostTemplates', #发帖模板选项配置，如：标题，招聘人数，工作年限
            // 'interface: SubscribePosts',
            // 'interface: CommonToolbox',
            // 'interface: PostPvStat',
            // 'interface: ManageUserFavorite',
//            'interface: GetOperationNotice',
            // 'interface: GetActivityInfo',
            // 'interface: CreatePostNew',
            // 'interface: CollectPushRegId',
            // 'interface: SaveComment',
            'ClientTimeStamp' => date('y-m-d h:i:s'),
            'agency:fff',
            'contentformat:json2',
            'customerId:801',
            'clientAgent:EndeavorU#720*1280',
            'versionId:4.0.0',
            'GjData-Version:1.0',
            'model:Generic/iPhone',
            // 'model:Symbian',
            'userId:F52D0D0615961C2722FB7DC430DBB9ED', //50185718
            // 'userId:40135731',
//            'deviceId:fb3c183067356c7a404f72881cf6bd0eb566b24c1ad418e0e67a9bce1ef0c796',
            'token:6337645947694577627a464e4446475a63374d3447476153',
//            'imei:98FE94C29882',
        );
        $post_fields = array(
            'customerId' => 801,
            'categoryId' => 0,
            'minorCategoryId' => 1196,
            'cityScriptIndex' => 0,
            'majorCategoryScriptIndex' => 1,
            'cityId' => 12,
            'version' => '4.0.0',
//            'jsonArgs' => json_encode(array(
            'loginId' => 291142678,
////                'customerId' => 710,
////                'livesCategoryId' => 12,
//            )),
//            'image[0]' => '@E:\kuaipan\document\company\ganji\2014-3\image\zhuangxiu.png',
//            "phone" => '13083056971',
//            'jsonArgs' => '{"loginId":"50024678","sortKeywords":[{,"sort":"desc","field":"refresh_at"}],"categoryId":2,"andKeywords":[],"majorCategoryScriptIndex":22,"customerId":"705","cityScriptIndex":0,"pageIndex":0,"pageSize":20,"queryFilters":[]}',
            // "jsonArgs" => '{"searchType":0,"cityScriptIndex":0,"latlng":"40.034113,116.311913","searchConditionIndex":null,"pageIndex":0,"pageSize":10}',
//             'jsonArgs' => '{"ownerId":30041,"puid":91915208,"ownerType":1,"commentType":1,"commentGrade":1,"content":"很不好的服务态度,很不周到", "loginId":"50005439"}',
            // 'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","latlng":"40.040386,116.293067","categoryId":"7","majorCategoryScriptIndex":"3", "pageSize":"20", "queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"post_at","sort":"desc"}]}',
            'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"6","majorCategoryScriptIndex":"2","pageIndex":"0","pageSize":"50","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"refresh_at","sort":"desc"}]}',
//            'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"7","majorCategoryScriptIndex":"1"                   ,"pageSize":"100","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"post_at","sort":"desc"}],"pageSize":"100"}',
            // 'jsonArgs' => '{"city_id":"12"}',
//            'jsonArgs' => '{"companyid":"500"}',
//            "jsonArgs" => '{"ownerId":1240080,"puid":816020667,"ownerType":2,"commentType":1,"commentGrade":3,"content":"我们认为您对所购买的商品有更深入的了解", "phone":"15210813542", "authCode":"1111", "loginId":"220782322"} ',
            // 'schema' => 1,
//            'subscribeTime' => 0,
            // 'subscribeID' => '394',
            // 'state' => 0,
            // 'regId' => 'otwytNUp+CtDgHLxN2Qnuh7bEqW2z6M5bsVx5S9pHVNcPGkJBTLmSm9FxWgDu8lQ1pZWH1i/jkoT1tFeQGFPzd01Bzr5hIqk0Dfocr83/Ug=',
//            'cityScriptIndex' => 0,
            // 'keywords' => '维修',
//            'categoryId' => 6,
            // 'allPuid' => 1,
//            'imageCount' => 715,
            // 'ucUserId' => '49445579',
            'loginName' => 'hellojiajianming', //web6 id 291142678
            'password' => 'lirui123', //web6
//            'captcha' => 'SEWH',
//            'ucenterUserID' => 73793251,
            // 'ucenterUserID' => 140298739,
//            'captcha' => '2345',
             'phone' => 13562138611,
//            'pageIndex' => 1,
//            'pageSize' => 100,
//            'isDeleted' => 1,
            // 'page' => 1,
            // 'toolType' => 0,
            // 'act' => 5,
            // 'favoriteIds' => 50045128,
//            'registerType' => 1,
//            'phoneRegister' => 1,
//            'loginType' => 1,
//            'phone' => '13083056971',
//            'authCode' => '444383',
//            'password' => '123123',
//            'code' => '444383',
//            'puid' => 808894990,
            // 'cityScriptIndex' => 0,
            'pageSite' => 1,
            // 'keywords' => '发廊',
            // 'searchAll' => 1,
//            'categoryId' => 2,
            // 'type' => 0,
//            'email' => 'lirui1@ganji.com',
            // 'contents' => '测试数据，请忽略',
//            'loginId' => 50185718,
//            'loginId' => 50024678, #绑定过手机的userid
//            'ucenterUserID' => 50185718, #绑定过手机的userid
//            'virtualId' => 1,
//            'majorCategoryScriptIndex' => 2,
//            'minorCategoryId' => 100,
            // 'postID' => '838485',
            // 'phone' => '',
            // 'image_count' => 1,
            // 'title' => '二手轿车出售',
            // 'major_category_script_index' => 4,
            // 'findjob_fulltime_url' => '11:qzshichangyingxiao',
            // 'district_id'=>'12',
            //        'street_id'=>'-1',
            //        'findjob_age'=>'25',
            //        'findjob_sex'=>'1',
            //        'findjob_salary' => '3',
            //        'findjob_degree'=> 5,
            //        'findjob_period' => '2',
            //        'findjob_open_status' => '1',
            //        'price' => '1000',
            //        'person' => '谢先生',
            //        'phone' => '13282987627',
            //        'description' => '用了不久的家庭车，想换新车， 诚心出让',
//            'getCodeType' => '1',
//            'method' => '1',
//            'step' => '2',
//            'method'=>'AuthCode',
            'id' => '1',
            'puid' => '91915208',
        );
        $timer = new Timer();
        $timer->start();

        set_time_limit(500);
        $ch = curl_init();
        $url = URL;
        echo $url . '<br />';

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
// curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $timer->stop();
        echo $timer->spent('ms') . ' ms<br />';

        $resArr = json_decode($result, true);
        echo '<pre>';
        var_dump($resArr);
//        //print_r($resArr['data']['version']);
        //die;
//        die;
        echo '</pre>';
        var_dump($result);

//search_debug=2225342
        ?>
    </body>
</html>
