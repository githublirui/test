<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>客户端接口追踪 - GanJi</title>
        <style>
            body{margin:0;padding:12px;font-family:"微软雅黑";font-size:12px;color:#333;}
            div,ul,li,pre,p,span,strong{margin:0;padding:0;}
            pre{font-family:"微软雅黑";font-size:12px;}
            .result{width:600px;margin-bottom:8px;padding:0;}
            .result li{width:49.9%;float:left;margin-bottom:5px;list-style:none;word-break:keep-all;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
            .result li span{display:block;font-weight:bold;font-size:14px;}
            .toolBar,.toolBarBg{height:100%;position:fixed;top:0;right:0;}
            .toolBar{width:435px;padding:15px 12px;z-index:9;}
            .toolBar .search{margin-bottom:30px;}
            .toolBar .search strong{width:70px;display:inline-block;font-weight:normal;}
            .toolBar .search span{width:80px;display:inline-block;}
            .toolBar .search a{color:blue;}
            .toolBar .search .subArea{padding-top:5px;}
            .toolBar .search .subArea a{margin-left:5px;}
            .toolBar .headersInfo pre{word-wrap:break-word;word-break:break-all;}
            .toolBarBg{width:459px;background:#fff;opacity:0.6;}
            .pre{margin-bottom:10px;padding:15px;background:#f5f5f5;color:rgb(204, 0, 0);}
            .res{margin-bottom:20px;word-wrap:break-word;word-break:normal;}
            .selectList ul{margin-bottom:12px;}
            .selectList ul li{list-style-type:disc;list-style-position:inside;}
            .selectList ul li a.on{background:#ffff00;}
            .selectList ul li a.autotest{margin-right:3px;text-decoration:none;color:#333;}
            .selectList ul li a.autotest:hover{color:red;}
            .cl{clear:both;}
        </style>
    </head>
    <body>
        <?php
        //开始运行时间
        $startTime = microtime(true);


        //开启错误提示
        error_reporting(E_ERROR);


        //定义超时时间
        set_time_limit(500);


        //初始化参数
        $tracerUrl = isset($_GET['tracerUrl']) ? $_GET['tracerUrl'] : '165';          //追踪环境
        $dirType = isset($_GET['dirType']) ? $_GET['dirType'] : 'datashare';    //追踪目录
        $urlParam = isset($_GET['urlParam']) ? $_GET['urlParam'] : '';             //自定义参数预留
        $interface = isset($_GET['interface']) ? $_GET['interface'] : '';             //接口名称
        $testUrl = isset($_GET['testUrl']) ? $_GET['testUrl'] : '';             //WebApp测试URL
        //测试环境列表
        $tracerUrlConf = array(
            '165' => 'http://dev.mobds.ganjistatic3.com/',
            'Test1' => 'http://mobds.ganjistatic3.com/',
            'Web6' => 'http://mobtestweb6.ganji.com/',
            'Web' => 'http://mobds.ganji.cn/'
        );


        //WebApp测试URL
        $tracerTestUrl = array(
            $tracerUrlConf[$tracerUrl] . 'webapp/common/?controller=GetAirQuality&cityId=12',
            $tracerUrlConf[$tracerUrl] . 'webapp/common/?controller=GetConstellation&constellationId=6',
            $tracerUrlConf[$tracerUrl] . 'webapp/common/?controller=GetWeatherWarning&cityId=12'
        );


        //autotest env参数
        $autotestEnvConf = array(
            '165' => 'dev',
            'Test1' => 'dev',
            'Web6' => 'sim',
            'Web' => 'online'
        );


        //AutoTest测试URL
        $autotestUrl = 'http://autotest.corp.ganji.com/mobile_client.php?env=' . $autotestEnvConf[$tracerUrl] . '&category=common&interface=%s&debug=1';


        //interface列表
        $interfaceConf = array(
            'getClientCommercial' => '',
            'SearchPostsByJson2' => '数据列表查询',
            'SubscribePosts' => '订阅数据列表查询',
            'UserFavorites' => '获取用户收藏接口',
            'LoginOut' => '用户注销',
            'CheckVersion' => '检查客户端软件版本更新',
            'GetLastCategory' => '获取大类下小类信息，如房产大类下包括出租房、出售房、合租房',
            'GetMessagePhone' => '获取上传短信手机号码',
            'PushTest' => '',
            'userRegister' => '第一次安装，注册设备信息，返回注册后的userid',
            'UserPhoneAuth' => '获取手机验证码校验和用户手机号码绑定',
            'register' => '用户注册接口',
            'autoLogin' => '用户自动登录接口',
            'GetLastGeography' => '城、市区域的下属地标信息，如海淀 =>清河,小营,西二旗,上地',
            'GetMajorCategoryFilter' => '传递小类，获取小类的条件列表如宠物=> 区域,品种，价格',
            'GetMajorCategoryFilters' => '根据传递的小类获取到当前小类的最新的过滤条件列表，并显示在客户端的列表页上以进行相应过滤',
            'GetLastCategories' => '类别配置下载',
            'userLogin' => '用户登录接口',
            'PushSettingSync' => '用户推送设置同步接口',
            'UserSubscribe' => '',
            'VotePost' => '举报帖子接口',
            'RefreshPost' => '刷新帖子接口',
            'GetUserJobList' => '',
            'GetPostsByAppId' => '用户中心通过安装标识获得历史帖子',
            'GetPostsByPhone' => '获取某一电话号码下的帖子列表',
            'GetPostsByUserId' => '通过用户id 获取帖子信息',
            'GetPostByPuid' => '获取剩余字段',
            'GetUserInfo' => '获取用户信息接口',
            'getPubPostLimit' => '获取用户发帖限制数',
            'GetWebSearchCount' => '全局搜索获取类别下帖子数',
            'GetPost' => '获取帖子详情',
            'CommonSearchHotWord' => '搜索热词接口',
            'GetBackPassword' => '找回密码相关接口',
            'ModifyPost' => '',
            'SearchPostsByJson' => '客户端帖子搜索接口，通过参数jsonArgs',
            'userLoginOut' => '',
            'SearchLifePostList' => '',
            'AndroidInterFace' => '',
            'ajaxSuggestion' => '关键字联想搜索',
            'GetNewCategories' => '求职简历，新类目结构下载,如技工=>电工,木工,焊工水工',
            'ImSubscribe' => 'IM消息推送用户设置接口',
            'AddImMessageToPushQ' => '',
            'GetNotRecvMsgs' => '',
            'GetErshoucheTagOrCar' => '获得二手车某品牌 下的车系或者某车系下车型选项配置',
            'PostSubscribe' => '帖子订阅相关接口',
            'GetLastPostTemplates' => '发帖模板选项配置，如：标题，招聘人数，工作年限',
            'PostPvStat' => '',
            'SearchCityByLocation' => '通过位置获取城市信息',
            'ManageUserFavorite' => '添加\取消收藏接口',
            'GetOperationNotice' => '获取运营通知接口',
            'GetActivityInfo' => '获得运营活动的信息',
            'CreatePostNew' => '',
            'CollectPushRegId' => '',
            'SaveComment' => '',
            'GetBiotope' => '',
            'UploadImages' => '图片上传',
            'GetSpreadInstallData' => '',
            'getFastCommonFilter' => '获得大类下快速筛选项配置',
            'YJJY' => '用户意见反馈和联系客服接口',
            'CommonToolbox' => '客户端工具箱相关接口',
            'CommonOperateList' => '获取运营banner列表接口',
            'CommonCategoryInfo' => '客户端获取大类首页配置的接口',
            'CommonLotteryInfo' => '房产抽奖接口',
            'CommonConsultList' => '资讯列表',
            'CommonSubscribe' => '订阅相关',
            'CommonBenchInfo' => '赶集叮咚工作台',
            'CommonGetUserPostList' => '赶集叮咚帖子列表接口',
            'CommonPostOperate' => '赶集叮咚帖子处理接口',
            'delImageByCode' => '根据code删除图片',
            'GetWebSearchQueryIR' => '意图搜索接口',
            'GetThridpartPayUrl' => '获得第三方（支付宝）支付url、获得银联订单号',
            'CommonAlarmMessage' => '客户端异常报警短信提示接口',
            'uploadImageByCode' => '根据验证码（pc上的）手机上传图片',
            'checkPcCode' => '检验手机传图验证码，确认上传完毕',
            'CommonSearchIR' => '意图搜索接口'
        );
        ///增加interface分别在 interfaceConf 增加接口名、中文解释和 $interfacePara 增加参数


        ksort($interfaceConf);


        //interface参数列表
        $interfacePara = array(
            'SearchCityByLocation' => array(
                'state' => 0,
                'act' => 5,
                'coordinate' => '40.047738,116.305422'
            ),
            'GetWebSearchCount' => array(
                'cityScriptIndex' => 0,
                'keywords' => '手机'
            ),
            'getFastCommonFilter' => array(
                'jsonArgs' => '{"cityScriptIndex":"0","categoryId":"2","version":""}'
            ),
            'delImageByCode' => array(
                'jsonArgs' => '{"code":4435834,"imageUrl":"gjfstmp1\/M00\/88\/D9\/CgP,ylCFD7,gxZwjAACznFog,PI768_770-470_7-5.jpg"}'
            ),
            'VotePost' => array(
                'categoryId' => '12',
                'majorCategoryScriptIndex' => '1',
                'postId' => '1098640586',
                'agent' => '',
                'cityScriptIndex' => '0',
                'reasonId' => '1',
                'content' => '测试内容，请忽略',
                'userName' => '',
                'jubaoUserName' => '',
                'phone' => '',
                'ip' => ''
            ),
            'YJJY' => array(
                'act' => '2',
                'email' => 'gaozhifeng@ganji.com',
                'contents' => '测试数据，请忽略',
                'type' => '0',
                'loginId' => '50193806',
                'Ip' => '',
                'source' => 'duomeng',
                'puid' => '91915208',
                'isSupport' => '1'
            ),
            'GetWebSearchQueryIR' => array(
                'cityScritpIndex' => '0',
                'keywords' => '店铺管理',
            ),
            'GetThridpartPayUrl' => array(
                'loginId' => '50024678',
                'amount' => '0.01',
                'type' => '1',
                'consumeType' => '0',
                'orderId' => ''
            ),
            'CommonAlarmMessage' => array(
                'interfaceName' => 'CheckVersion',
                'message' => '测试数据，请忽略'
            ),
            'CommonSearchHotWord' => array(
            ),
            'uploadImageByCode' => array(
                'jsonArgs' => '{"code":"4435834"}',
                'image[0]' => '@D:\icon_v.png'
            ),
            'GetLastCategories' => array(
                'categoryId' => '2',
                'versions' => '2.0.11.20130723180000_5.6.0',
                'cityScriptIndex' => '12'
            ),
            'CheckVersion' => array(
                'UpgradeMode' => '2'
            ),
            'CommonPostOperate' => array(
                'type' => '0',
                'act' => '1',
                'loginId' => '50277526',
                'puid' => '8511771',
                'postId' => '54424511',
                'categoryId' => '2',
                'majorCategoryId' => '20',
                'cityId' => '12',
                'isBang' => '1',
            ),
            'checkPcCode' => array(
                'jsonArgs' => '{"code":4435834}'
            ),
            'GetPostsByAppId' => array(
                //'ucenterUserID' => 50277526,
                'ucenterUserID' => 298955115,
                'isDeleted' => 0,
                'pageIndex' => 0,
                'pageSize' => 50,
            ),
            'GetPost' => array(
                'puid' => '1141400150',
                'cityScriptIndex' => '0'
            ),
            'CommonSearchIR' => array(
                'cityId' => '12',
                'keyword' => '水杯',
                'categoryId' => '-1000',
            //'search_debug' =>'2225342'
            ),
            'userLogin' => array(
            ),
            'SubscribePosts' => array(
                'jsonArgs' => '{"categoryId":"1","cityScriptIndex":"0","majorCategoryScriptIndex":"1","category_script_index":"4","majorCategoryId":"","category_script_index":"1"}',
                'schema' => '1',
                'subscribeTime' => '60',
                'subscribeID' => ''
            ),
            'CommonSubscribe' => array(
                'act' => '1',
                'loginId' => '50277526',
                'type' => '0',
                'conditions' => '{"postId":"106736_1_1009_0"}',
                'frequency' => '24'
            ),
            'ImSubscribe' => array(
                'jsonArgs' => '{"act":"1","state":"0","loginId":"50277526","remind":"1_1_1","starttime":"9","endtime":"21"}'
            ),
            'GetOperationNotice' => array(
                'cityId' => '12',
                'subscribe_push' => '2'
            ),
            'GetActivityInfo' => array(
                'id' => '1'
            ),
            'PostSubscribe' => array(
                'act' => '1',
                'state' => '0',
                'frequency' => '10',
                'remind' => '1_1_0',
                'starttime' => '9',
                'endtime' => '21',
                'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"6","majorCategoryScriptIndex":"10","queryFilters":[],"andKeywords":[],"sortKeywords":[{"field":"post_at","sort":"desc"}]}'
            ),
            'PushSettingSync' => array(
                'jsonArgs' => '{"action":"1", "loginId":"50277526"}'
            ),
            'LoginOut' => array(
                'loginId' => '50277526',
                'deviceId' => '85ee8513e060e53cee9751754eefb728d58dc066ddc347cd69072b546922f36b'
            ),
            'GetBackPassword' => array(
                'method' => '3',
                'step' => '3'
            ),
            'CommonGetUserPostList' => array(
                'loginId' => '50277526',
                'type' => '0',
                'postType' => '0',
                'cityId' => '12',
                'pageIndex' => '0',
                'pageSize' => '0',
            ),
            'CommonBenchInfo' => array(
                'loginId' => '50277526',
                'type' => '1',
                'cityId' => '12'
            ),
            'CommonOperateList' => array(
                'cityId' => '12',
                'pageType' => '2',
                'categoryId' => '13',
                'majorCategoryId' => '20'
            ),
            'CommonToolbox' => array(
                'toolType' => '0',
                'version' => '5.4.1'
            ),
            'GetErshoucheTagOrCar' => array(
                'minorcategoryId' => '1206'
            ),
            'UploadImages' => array(
                'jsonArgs' => '{"imageCount":"1", "width":"80", "height":"60", "categoryId":"12", "nowatermark":"1", "newMulti":"1"}',
                'image' => '@D:\icon_v.png'
            ),
            'UserFavorites' => array(
                'ucenterUserID' => '50277526',
                'page' => '1',
                'pageSize' => '1',
                'pageIndex' => '1'
            ),
            'ManageUserFavorite' => array(
                'act' => '1',
                'puid' => '91890889',
                'loginId' => '50277526',
            ),
            'userRegister' => array(
                'customerId' => '705',
                'versionId' => '5.6.0',
                'clientAgent' => 'iPhone5#640*1136',
                'agency' => '',
                'imei' => '341000314718'
            ),
            'SearchPostsByJson2' => array(
                'jsonArgs' => '{"customerId":"705","cityScriptIndex":"0","categoryId":"14","majorCategoryScriptIndex":"-1000","pageSize":"20","pageIndex":"0","andKeywords":[{"value":"水杯","name":"title"}],"sortKeywords":[{"field":"post_at","sort":"desc"}],"queryFilters":[]}'
            ),
            'register' => array(
                'loginName' => 'xixihaha123',
                'password' => 'xixihaha9986',
                'captcha' => '39ds',
                'email' => 'xixi@qq.com', //选填
            )
        );



        //替换当前URL中的interface
        $queryArr = explode('&', $_SERVER['QUERY_STRING']);
        $queryStr = '';

        if ($queryArr[0] != '') {

            foreach ($queryArr as $k) {

                $queryTemp = explode('=', $k);

                if (strstr($queryTemp[0], 'interface') == '') {

                    $queryTemp[0] = isset($queryTemp[0]) ? $queryTemp[0] : '';
                    $queryTemp[1] = isset($queryTemp[1]) ? $queryTemp[1] : '';

                    $queryStr .= $queryTemp[0] . '=' . $queryTemp[1] . '&';
                }
            }

            $nowUrl = '?' . $queryStr;
        } else {
            $nowUrl = '?';
        }


        //如果testUrl不等于空，则认为是进行WebApp测试
        if ($testUrl != '') {
            $url = $tracerTestUrl[$testUrl];
        } else {
            if ($tracerUrl != '') {
                $url = $tracerUrlConf[$tracerUrl];
            }
            if ($dirType != '') {
                $url .= $dirType . '/';
            }
        }


        //定义传送字段
        if ($interface != '') {
            $post_fields = $interfacePara[$interface];
        } else {
            $post_fields = array();
        }




        //定义header
        $header = array(
            'interface:' . $interface,
            'customerId:705', //数据用户标识 705 801（IOS/安卓）
            'versionId:5.6.0', //客户端软件版本
            'model:Generic/iphone', //平台/机型系列
            'clientAgent:iphone#640*1136', //机型屏幕
            'clientTest:true', //测试标记
            'ClientTimeStamp:' . date('Y-m-d h:i:s'), //客户端时间
            'contentformat:json2', //协议标识
            'GjData-Version:1.0', //数据版本
            'Connection:close', //完成后即断开
            'userId:B18D42D75118D207AC02D79948E560BD', //50277526
            'agency:test', //合作伙伴标识
            //'token:674750574136306a48414b6958646c62674776535377704f',
            'token:4135513033612f4d784d69315533626c4135734731343232',
            'imei:013AED56-2576-40A1-9319-9E61F11111',
            'deviceId:85ee8513e060e53cee9751754eefb728d58dc066ddc347cd69072b546922f36b',
        );

        define('URL', $url);

        $resArr = '';
        $result = '';
        $info = '';

        if (($interface != '' && URL != '') or $testUrl != '') {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, URL);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);
            $result = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            $resArr = json_decode($result, true);
        }

        $endTime = microtime(true);
        $diffTime = (float) $endTime - $startTime;
        ?>
        <ul class="result">
            <li title="<?php echo URL; ?>"><span>TracerURL：</span><?php echo URL; ?></li>
            <li><span>PostTime：</span><?php echo time(); ?>s</li>
            <li><span>Consume：</span><?php echo $diffTime; ?>s</li>
            <li><span>Interface：</span><?php if ($interface != '') echo $interface . ' <a href="#' . $interface . '" title="Detail Info">&gt;&gt;</a>'; ?></li>
            <div class="cl"></div>
        </ul>
        <pre class="pre"><?php
            if (is_array($resArr)) {
                print_r($resArr);
            } else {
                echo 'empty';
            }
            ?></pre>
        <div class="res"><?php var_dump($result); ?></div>
        <div class="selectList">
            <ul>
                <strong>WebApp：</strong>
                <?php
                $testUrlStr = '';
                foreach ($tracerTestUrl as $key => $value) {
                    $testUrlStr .= '<li><a href="?tracerUrl=' . $tracerUrl . '&testUrl=' . $key . '">' . $value . '</a></li>';
                }
                echo $testUrlStr;
                ?>
            </ul>
            <ul>
                <strong>Interface：</strong>
                <?php
                $interfaceStr = '';
                foreach ($interfaceConf as $key => $value) {
                    $interfaceStr .= '<li id="' . $key . '"><a href="' . sprintf($autotestUrl, $key) . '" target="_blank" class="autotest">[autotest]</a> <a href="' . $nowUrl . 'interface=' . $key . '"';
                    if (array_key_exists($key, $interfacePara)) {
                        $interfaceStr .= ' title="已有参数"';
                    } else {
                        $interfaceStr .= ' title="暂无参数"';
                    }

                    if ($interface == $key) {
                        $interfaceStr .= ' class="on"';
                    }
                    $interfaceStr .= '>' . $key . ' ' . $value . '</a></li>';
                }
                echo $interfaceStr;
                ?>
            </ul>
        </div>
        <div class="toolBar">
            <div class="search">
                <form name="form" action="" method="get">
                    <strong>SelectUrl：</strong>
                    <span><input type="radio" name="tracerUrl" value="Web"   <?php if ($tracerUrl == 'Web') echo 'checked="1"'; ?> />线上</span>
                    <span><input type="radio" name="tracerUrl" value="Web6"  <?php if ($tracerUrl == 'Web6') echo 'checked="1"'; ?> />Web6</span>
                    <span><input type="radio" name="tracerUrl" value="Test1" <?php if ($tracerUrl == 'Test1') echo 'checked="1"'; ?> />Test1</span>
                    <span><input type="radio" name="tracerUrl" value="165"   <?php if ($tracerUrl == '165') echo 'checked="1"'; ?> />165</span><br />

                    <strong>SelectDir：</strong>
                    <span><input type="radio" name="dirType" value="datashare" <?php if ($dirType == 'datashare') echo 'checked="1"'; ?> />datashare</span>
                    <span><input type="radio" name="dirType" value="push"      <?php if ($dirType == 'push') echo 'checked="1"'; ?> />push</span>
                    <span><input type="radio" name="dirType" value="users"     <?php if ($dirType == 'users') echo 'checked="1"'; ?> />users</span><br />

                    <!--<strong>UrlParam：</strong><-->
                    <!--<input type="text" name="urlParam" value="<?php echo $urlparam; ?>" />-->
                    <div class="subArea">
                        <input type="submit" value="提交" /> <a href="?">清空查询</a> <a href="#">回顶部</a>
                        <input type="hidden" name="interface" value="<?php echo $interface; ?>" />
                    </div>
                </form>
            </div>
            <div class="headersInfo">
                <strong>Header</strong>
                <pre><?php print_r($header); ?></pre>
                <br />
                <strong>Params</strong>
                <pre><?php print_r($post_fields); ?></pre>
            </div>
        </div>
        <div class="toolBarBg"></div>
        <div>©2014 Ganji By MSC.Feng - Version1.0</div>
    </body>
</html>