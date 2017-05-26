<?php

/**
 * 
 * 赶集接口调用配置文件
 */
require_once 'Timer.php';
require_once 'ganji_conf.php';

// define('URL', 'http://devmobds.ganjistatic3.com/datashare/');
define('URL', 'http://ganji.local/ganji_online/mobile_client/index.php');
//define('URL', 'http://mobds.ganjistatic3.com/datashare/');
//define('URL', 'http://test.local/up.php');
// define('URL', 'http://mobtestweb6.ganji.com/datashare/');
// define('URL', 'http://mobds.ganji.cn/datashare/');
// define('URL', 'http://mobds.ganjistatic3.com/users/');
// define('URL', 'http://mobtestweb6.ganji.com/users');
// define('URL', 'http://mobds.ganji.cn/users/');
// define('URL', 'http://mobsa.ganjiStatic3.com/Users.svc/50024678/posts/0/11?post_session_id=FCFB9E2A-BA9B-45EE-B95A-950DE8BFE259&major_category_script_index=2&minor_category_si=0&flag=1');
// define('URL', 'http://mobds.ganjistatic3.com/users/50009853/posts/0/11/2277329/update?post_session_id=55505a54-4fc3-4268-a943-0be5e031ee43&major_category_script_index=2&tag=2609');
// define('URL', 'http://mobds.ganji.cn/users/140298739/posts/0/6/NewPost?post_session_id=5fbe5ca6-6acb-49ea-a57d-c9652d13c2b7&major_category_script_index=2&tag=2609');
// define('URL', 'http://mobds.ganjistatic3.com/push/');
// define('URL', 'http://mobtestweb6.ganji.com/push/');
// define('URL', 'http://mobds.ganji.cn/push/');
// define('URL', 'http://mbbackend.ganjistatic3.com/');
// define('URL', 'http://mbbackend.ganji.com/');
// define('URL', 'http://datashare.ganjiStatic3.com/datasharing.aspx');
// define('URL', 'http://localhost:12520/datasharing.aspx');
// define('URL', 'http://mobds.ganji.com/datasharing.aspx');
// define('URL', 'http://mobds.ganjiStatic3.com/posts');
// define('URL', 'http://mobds.ganji.cn/posts');
// define('URL', 'http://mobsa.ganji.com/Users.svc/140298739/posts/0/11?post_session_id=FCFB9E2A-BA9B-45EE-B95A-950DE8BFE259&major_category_script_index=2&minor_category_si=0&flag=1');
// define('URL', 'http://mobsa.ganjiStatic3.com/Users.svc/?login_name=jingling&password=123456');
// define('URL', 'http://mobsa.ganji.com/users/?login_name=jinglingyueyue&password=9110281128');
// define('URL', 'http://mobds.ganjiStatic3.com/users/50024678/posts/0/6/NewPost?post_session_id=F006EA32-7728-41B3-BDF6-0D672BFF9666&major_category_script_index=1');
// define('URL', 'http://mobtestweb6.ganji.com/users/64106921/posts/0/14/19017381?post_session_id=F006EA32-7728-41B3-BDF6-0D672BFF9666&major_category_script_index=12');

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
    // 'interface:userLogin',
    // 'interface:autoLogin',
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
    // 'interface:GetPost',
    // 'interface: GetPostByPuid',
    // 'interface: CommonCategoryInfo',
    // 'interface:CommonSearchHotWord',
    // 'interface:GetBackPassword',
    // 'interface: SearchPostsByJson',
//    'interface: SearchPostsByJson2',
    'interface:UploadImages',
    // 'interface:SearchLifePostList',
    // 'interface: AndroidInterFace',
    // 'interface: ajaxSuggestion',
    // 'interface: GetMajorCategoryFilters',
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
    // 'interface: CommonToolbox',
    // 'interface: PostPvStat',
    // 'interface: register',
    // 'interface: ManageUserFavorite',
    // 'interface: GetOperationNotice',
    // 'interface: GetActivityInfo',
    // 'interface: CreatePostNew',
    // 'interface: CollectPushRegId',
    // 'interface: SaveComment',
    'contentformat:json2',
    'agency:kuaibo0',
    'customerId:801',
    'clientAgent:EndeavorU#720*1280',
    'versionId:4.7.0',
    'GjData-Version:1.0',
    'model:Generic/iPhone',
    // 'model:Symbian',
    'userId:F9F205681C4BD9B32C1F55CEBCF87F37', //77008678
    // 'userId:40135731',
    'deviceId:fb3c183067356c7a404f72881cf6bd0eb566b24c1ad418e0e67a9bce1ef0c796',
    // 'token:74484154544e6d63536c55495a5675667448437043783835',
    'token:303679436e6d49557272657a394b33613036714d4b775133',
    'imei:98FE94C29882',
);
$jsonArgs = array(
    'cityScriptIndex' => 0,
    'categoryId' => 14,
    // 'url' => 'qitachuang',
    // 'keyword' => 'a',
    // 'majorCategoryScriptIndex' => -1000,
    // 'pageIndex' => 0,
    'pageSize' => 50,
    // 'queryFilters' => array(
    // array('name' => 'degree', 'operator' => '=', 'value' => 2),
    // array('name' => 'price', 'operator' => '=', 'value' => array('up' => '10000', 'low' => '5000')),
    // array('name' => 'price', 'up_range' => '30000', 'low_range' => '5000'),
    // array('name' => 'price', 'operator' => '=', 'value' => 3),
    // array('name' => 'huxing_shi', 'operator' => '=', 'value' => 1),
    // array('name' => 'base_tag', 'operator' => '=', 'value' => 'iphone-iphone-4s'),
    // array('name' => 'minor_category', 'operator' => '=', 'value' => '1559'),
    // array("name" => "minor_category", "operator" => "=", "value" => "1123"),
    // ),
    // 'price' => array(
    // 	'2000', '10000',
    // ),
    'andKeywords' => array(
        0 => array(
            'value' => '13260054011',
            'operator' => '=',
            'name' => '',
        ),
    ),
    'sortKeywords' => array(
        '0' => array(
            'field' => 'refresh_at',
            'sort' => 'desc',
        ),
    ),
);
$jsonArags = array(
    'city_id' => 12,
    'categoryId' => 2,
    'module' => 'pinche',
    'cityScriptIndex' => 0,
    'keyword' => 'm',
    'latlng' => "40.04475,116.333123",
    'act' => 1,
    'loginId' => '3758688449',
    'state' => 0,
    'starttime' => 0,
);
$post_fields = array(
    // "jsonArgs" => json_encode($jsonArags),
    // 'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"2","pageIndex":"3","pageSize":"20","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"refresh_at","sort":"desc"}]}',
    // "jsonArgs" => '{"searchType":0,"cityScriptIndex":0,"latlng":"40.034113,116.311913","searchConditionIndex":null,"pageIndex":0,"pageSize":10}',
    // 'jsonArgs' => '{"ownerId":30041,"puid":91915208,"ownerType":1,"commentType":1,"commentGrade":1,"content":"很不好的服务态度,很不周到", "loginId":"50005439"}',
    // 'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","latlng":"40.040386,116.293067","categoryId":"7","majorCategoryScriptIndex":"3", "pageSize":"20", "queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"post_at","sort":"desc"}]}',
    // 'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"6","majorCategoryScriptIndex":"2","pageIndex":"0","pageSize":"50","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"refresh_at","sort":"desc"}]}',
//    'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"7","majorCategoryScriptIndex":"1"                   ,"pageSize":"100","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"post_at","sort":"desc"}],"pageSize":"100"}',
    // 'jsonArgs' => '{"city_id":"12"}',
//    'jsonArgs' => '{"imageCount":"1"}',
    // "jsonArgs" => '{"ownerId":1240080,"puid":816020667,"ownerType":2,"commentType":1,"commentGrade":3,"content":"我们认为您对所购买的商品有更深入的了解", "phone":"15210813542", "authCode":"1111", "loginId":"220782322"} ',
    // 'schema' => 1,
//    'subscribeTime' => 0,
    // 'subscribeID' => '394',
    // 'state' => 0,
    // 'regId' => 'otwytNUp+CtDgHLxN2Qnuh7bEqW2z6M5bsVx5S9pHVNcPGkJBTLmSm9FxWgDu8lQ1pZWH1i/jkoT1tFeQGFPzd01Bzr5hIqk0Dfocr83/Ug=',
//    'cityScriptIndex' => 0,
    // 'keywords' => '维修',
//    'categoryId' => 6,
    // 'allPuid' => 1,
//    'id' => 715,
//    'cityId' => 12,
    // 'ucUserId' => '49445579',
    // 'loginName' => 'jinglingyueyue',
    // 'password' => '9110281128',
//     'captcha' => 'mmmm',
//    'ucenterUserID' => 73793251,
    // 'ucenterUserID' => 140298739,
    // 'loginId' => 14701105039,
    // 'captcha' => '2345',
    // 'loginId' => '140298739',
    // 'phone' => 15810508809,
//    'pageIndex' => 1,
//    'pageSize' => 100,
//    'isDeleted' => 1,
    // 'page' => 1,
    // 'toolType' => 0,
    // 'act' => 5,
    // 'loginId' => 50024678,
    // 'favoriteIds' => 50045128,
    // 'registerType' => 1,
//    'loginType' => 1,
//    'phone' => '13282987627',
    // 'password' => '123456c',
//    'code' => '4958',
//    'version' => '3.9',
//    'puid' => 808894990,
    // 'cityScriptIndex' => 0,
    // 'pageSite' => 1,
    // 'keywords' => '发廊',
    // 'searchAll' => 1,
    // 'categoryId' => 5,
    // 'type' => 0,
    // 'email' => 'chenyihong@ganji.com',
    // 'contents' => '测试数据，请忽略',
    // 'loginId' => 140298739,
    // 'categoryId' => 1,
    // 'virtualId' => 1,
    // 'majorCategoryScriptIndex' => 1,
    // 'minorCategoryId' => 1207,
    // 'postID' => '838485',
    // 'phone' => '',
    'image_count' => 1,
    'image[0]' => '@C:\Users\lirui1\Pictures\test\1.jpg',
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
);

function curlPost($header, $post_fields) {
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
    print_r($resArr);
    echo $result;
    echo '<pre>';
}

curlPost($header, $post_fields);
