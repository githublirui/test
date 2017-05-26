<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        require_once 'Timer.php';

//        define('URL', 'http://dev.mobds.ganjistatic3.com/datashare/');
        define('URL', 'http://mobds.ganjistatic3.com/datashare/');
// define('URL', 'http://mobtestweb6.ganji.com/datashare/');
// define('URL', 'http://mobds.ganji.cn/datashare/');
// define('URL', 'http://dev.mobds.ganjiStatic3.com/users/');
// define('URL', 'http://mobds.ganjistatic3.com/users/');
// define('URL', 'http://mobtestweb6.ganji.com/users');
// define('URL', 'http://mobds.ganji.cn/users/');
// define('URL', 'http://mobsa.ganjiStatic3.com/Users.svc/50024678/posts/0/11?post_session_id=FCFB9E2A-BA9B-45EE-B95A-950DE8BFE259&major_category_script_index=2&minor_category_si=0&flag=1');
// define('URL', 'http://mobds.ganjistatic3.com/users/50024678/posts/0/11/2277329/update?post_session_id=55505a54-4fc3-4268-a943-0be5e031ee43&major_category_script_index=2&tag=2609');
// define('URL', 'http://mobds.ganji.cn/users/140298739/posts/0/6/NewPost?post_session_id=5fbe5ca6-6acb-49ea-a57d-c9652d13c2b7&major_category_script_index=2&tag=2609');
// define('URL', 'http://dev.mobds.ganjiStatic3.com/push/');
// define('URL', 'http://mobds.ganjistatic3.com/push/');
// define('URL', 'http://mobtestweb6.ganji.com/push/');
// define('URL', 'http://mobds.ganji.cn/push/');
// define('URL', 'http://mbbackend.ganjistatic3.com/');
// define('URL', 'http://mbbackend.ganji.com/');
// define('URL', 'http://datashare.ganjiStatic3.com/datasharing.aspx');
// define('URL', 'http://localhost:12520/datasharing.aspx');
// define('URL', 'http://mobds.ganji.com/datasharing.aspx');
// define('URL', 'http://mobds.ganjiStatic3.com/openapi/?interface=GetSpreadInstallData');
// define('URL', 'http://mobtestweb6.ganji.com/openapi/?interface=GetSpreadInstallData');
//define('URL', 'http://mobds.ganji.cn/openapi/?interface=GetSpreadInstallData');
// define('URL', 'http://mobsa.ganji.com/Users.svc/140298739/posts/0/11?post_session_id=FCFB9E2A-BA9B-45EE-B95A-950DE8BFE259&major_category_script_index=2&minor_category_si=0&flag=1');
// define('URL', 'http://mobsa.ganjiStatic3.com/Users.svc/?login_name=jingling&password=123456');
// define('URL', 'http://mobsa.ganji.com/users/?login_name=jinglingyueyue&password=9110281128');
// define('URL', 'http://dev.mobds.ganjiStatic3.com/users/50024678/posts/0/7/NewPost?post_session_id=F006EA32-7728-41B3-BDF6-0D672BFF9666&major_category_script_index=1');
// define('URL', 'http://dev.mobds.ganjiStatic3.com/users/50259796/posts/0/11/2279901/update?post_session_id=F2C11D54414F14F689444BBD9FD2BABE&major_category_script_index=304&tag=4663');

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
            // 'interface:userLogin',
            // 'interface:autoLogin',
            // 'interface:PushSettingSync',
            // 'interface:UserSubscribe',
            // 'interface:VotePost',
            // 'interface:RefreshPost',
            // 'interface:UserFavorites',
            // 'interface:GetUserJobList',
            // 'interface: GetUserFindJobList',
            // 'interface:GetPostsByAppId',
            // 'interface:GetPostsByPhone',
            // 'interface:GetPostsByUserId',
            // 'interface:GetUserInfo',
            // 'interface:GetWebSearchCount',
            // 'interface:GetPost',
            // 'interface: GetPostByPuid',
            'interface: CommonCategoryInfo',
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
            'contentformat:json2',
            'agency:oppostore02',
            'customerId:801',
            'clientAgent:EndeavorU#720*1280',
            // 'Content-Type:application/x-www-form-urlencoded;',
            'versionId:5.0.0',
            'GjData-Version:1.0',
            'model:Generic/iPhone',
            // 'model:Symbian',
            'userId:07CAC2A7E71839A0250856FE7F2EA19B', //77008678
            // 'userId:BDA29C535FC0DD5B3C264C6811220422',
            'deviceId:ea1f1a85ecbb0c6a9d4cb44b2258477ba77f159bd378a5ab7cb83009046705db',
            'token:38466a2b3575345173304e4a6c73375538464c5043633457',
            'imei:98FE94C29882',
                // 'idfa:463344767163627330',
        );
        $jsonArgs = array(
            'cityScriptIndex' => 0,
            'categoryId' => 0,
            // 'url' => 'qitachuang',
            // 'keyword' => 'a',
            'majorCategoryScriptIndex' => -1000,
            // 'pageIndex' => 0,
            'pageSize' => 50,
            'queryFilters' => array(
                // array('name' => 'degree', 'operator' => '=', 'value' => 2),
                // array('name' => 'price', 'operator' => '=', 'value' => array('up' => '10000', 'low' => '5000')),
                array('name' => 'price', 'up_range' => '200', 'low_range' => '500'),
                // array('name' => 'price', 'operator' => '=', 'value' => 3),
                // array('name' => 'huxing_shi', 'operator' => '=', 'value' => 1),
                array('name' => 'base_tag', 'operator' => '=', 'value' => 'shouji'),
            ),
            // 'price' => array(
            // 	'2000', '10000',
            // ),
            'andKeywords' => array(
            // 0 => array(
            // 	'value' => 'a',
            // 	'operator' => '=',
            // 	'name' => '',
            // ),
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
            'cityScriptIndex' => 0,
            'keyword' => '司',
            'latlng' => "40.04475,116.333123",
            'act' => 1,
            'loginId' => '3758688449',
            'state' => 0,
            'starttime' => 0,
            'userid' => 40000066,
            'token' => '303679436e6d49557272657a394b33613036714d4b775133',
            'searchType'
        );
        $post_fields = array(
            'categoryId' => 0,
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
//        var_dump($info);
        $timer->stop();
        echo $timer->spent('ms') . ' ms<br />';

        $resArr = json_decode($result, true);
        // $des = $resArr['posts'][0]['description'];
        // $des = str_replace(array("\r\n", "\r"), "AA", $des);
        // var_dump(json_encode($des), $des);exit;
        echo '<pre>';
        var_dump($resArr);
        echo '<br />' . $result;
        echo '<pre>';

//search_debug=2225342
        ?>
    </body>
</html>
