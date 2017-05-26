<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        require_once 'Timer.php';

// define('URL', 'http://devmobds.ganjistatic3.com/datashare/');
//        define('URL', 'http://mobds.ganjistatic3.com/datashare/');
// define('URL', 'http://mobtestweb6.ganji.com/datashare/');
//        define('URL', 'http://mobds.ganji.cn/datashare/');
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
//        define('URL', 'http://sso.corp.ganji.com/Account/LogOn?returnUrl=ajax');
        define('URL', 'http://mobds.ganjistatic3.com/datashare/');
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
            'interface: CommonCategoryInfo',
            // 'interface:CommonSearchHotWord',
            // 'interface:GetBackPassword',
            // 'interface: SearchPostsByJson',
//            'interface:SearchPostsByJson2',
//            'interface:UploadImages',
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
            'versionId:5.8.0',
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
        $post_fields = array(
            'categoryId' => '0',
            "cityId" => 12,
//            "Domain" => '@ganji.com',
//            "Password" => '649037629@qq.com',
//            "UserName" => 'lirui1111',
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
        $result = json_decode(curl_exec($ch), true);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $timer->stop();
        echo $timer->spent('ms') . ' ms<br />';

        echo '<pre>';
        print_r($result);
        echo '<pre>';

//search_debug=2225342
        ?>
    </body>
</html>
