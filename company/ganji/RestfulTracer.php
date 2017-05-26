<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>RESTful 接口追踪 - GanJi</title>
        <style>
            body{margin:0;padding:12px;font-family:"微软雅黑";font-size:12px;color:#333;}
            div,ul,li,pre,p,span,strong{margin:0;padding:0;}
            pre{font-family:"微软雅黑";font-size:12px;}
            .toolBar a{color:#ccc;}
            .resultArea{padding-left:395px;}
            .resultArea .result{margin-bottom:8px;padding:0;}
            .resultArea .result li{width:25%;height:40px;float:left;margin-bottom:5px;list-style:none;word-break:keep-all;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
            .resultArea .result li span{display:block;font-weight:bold;font-size:14px;}
            .resultArea .result li.url{width:100%;}
            .toolBar,.toolBarBg{height:100%;position:fixed;top:0;left:0;}
            .toolBar{width:360px;padding:15px 10px;z-index:9;color:#fff;}
            .toolBar .search{margin-bottom:30px;}
            .toolBar .search span{width:60px;display:inline-block;}
            .toolBar .headersInfo pre{font-family:"Courier New";word-wrap:break-word;word-break:break-all;color:#999;}
            .toolBar .headersInfo p{padding-top:3px;}
            .toolBar .headersInfo p span{text-decoration:underline;}
            .toolBarBg{width:380px;background:#333;}
            .pre{margin-bottom:10px;padding:15px;background:#f5f5f5;color:rgb(204, 0, 0);}
            .res{margin-bottom:20px;word-wrap:break-word;word-break:normal;}
            .selectList ul{margin-bottom:12px;}
            .selectList ul strong{line-height:30px;}
            .selectList ul span{display:inline-block;width:96px;}
            .selectList ul li{list-style-type:disc;list-style-position:inside;list-style-type:none}
            .selectList ul li a{text-decoration: none;}
            .selectList ul li a:hover, .selectList ul li a.on{background: rgb(43, 153,255)}
            .tools a{margin-right:5px;}
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
        $runtime = isset($_GET['runtime']) ? $_GET['runtime'] : '165';    //追踪环境
        $url = isset($_GET['url']) ? $_GET['url'] : '';       //接口名称
        $method = isset($_GET['method']) ? $_GET['method'] : '';       //请求方法
        $isTest = isset($_GET['isTest']) ? $_GET['isTest'] : 0;        //测试标识
        //测试环境列表
        $runtimeUrl = array(
            '165' => 'http://dev.mobds.ganjistatic3.com/',
            'Test1' => 'http://mobds.ganjistatic3.com/',
            'Web6' => 'http://mobtestweb6.ganji.com/',
            'Web' => 'http://mobds.ganji.cn/',
        );

        $restfulUrl = array(
            'api/common/user/userinfo/' => array(
                'POST' => array(
                    'name' => '用户注册',
                    'param' => array(
                        'test' => array(
                            'action_type' => 2,
                            /* 'phone' => '13936166081',
                              'code' => '1234', */
                            'login_name' => 'fengtest50',
                            'password' => 'fengtest',
                            'captcha' => 'abcd',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/user/userinfo/500010380/' => array(
                'GET' => array(
                    'name' => '获取用户',
                    'param' => array(),
                ),
            ),
            'api/common/user/userinfo/500010380/phone/' => array(
                'PUT' => array(
                    'name' => '手机绑定',
                    'param' => array(
                        'test' => array(
                            'phone' => '13936166081',
                            'code' => '1234',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/user/userinfo/500010380/avatar/' => array(
                'PUT' => array(
                    'name' => '修改头像',
                    'param' => array(
                        'test' => array(
                            'new_avatar' => 'gjfstmp2/M00/00/02/wKgCzFSZRNOIC3vIAAD,CmK7Z,QAAAA4gNlK0kAAP8i625_580-560_7-5.png',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/user/userinfo/500010380/nickname/' => array(
                'PUT' => array(
                    'name' => '修改昵称',
                    'param' => array(
                        'test' => array(
                            'new_nickname' => '麦田守望者',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/user/userinfo/500010380/password/' => array(
                'PUT' => array(
                    'name' => '找回密码',
                    'param' => array(
                        'test' => array(
                            'method_key' => '13936166081',
                            'new_password' => 'feng123',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/default/verification/captcha/' => array(
                'POST' => array(
                    'name' => '验证图片验证码',
                    'param' => array(
                        'test' => array(
                            'captcha' => 'abcd',
                            'type' => '1',
                            'type_key' => '',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/default/verification/code/' => array(
                'GET' => array(
                    'name' => '获取手机验证码',
                    'param' => array(
                        'test' => array(
                            'phone' => 18500172225,
                            'type' => 1,
                            'login_id' => 0,
                        ),
                        'online' => array(),
                    ),
                ),
                'POST' => array(
                    'name' => '验证手机验证码',
                    'param' => array(
                        'test' => array(
                            'phone' => 13936166081,
                            'type' => 1,
                            'code' => 400347,
                            'login_id' => 0,
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/user/usertoken/' => array(
                'POST' => array(
                    'name' => '用户登陆',
                    'param' => array(
                        'test' => array(
                            'action_type' => 1,
                            'login_name' => 'fengtest',
                            'password' => 'fengtest',
                        ),
                        'online' => array(),
                    ),
                ),
            ),
            'api/common/default/geo/province/?d_version=2015020112121' => array(
                'GET' => array(
                    'name' => '获取省的城市',
                    'param' => array(),
                ),
            ),
            'api/common/default/geo/city/12/district/?d_version=1.0.1384225778' => array(
                'GET' => array(
                    'name' => '获取城市街道',
                    'param' => array(),
                ),
            ),
            'api/common/default/files/' => array(
                'POST' => array(
                    'name' => '文件上传',
                    'param' => array(
                        'test' => array(
                            'img_width' => '500',
                            'img_height' => '500',
                            'category_id' => '2',
                            'nowatermark' => '2',
                            'file[0]' => '@C:\Users\lirui1\Pictures\test\1.jpg',
//                            'file[1]' => '@C:\Users\lirui1\Pictures\test\1.jpg',
                        ),
                    ),
                ),
            ),
            'api/common/operation/banners/' => array(
                'POST' => array(
                    'name' => 'banner',
                    'param' => array(
                        'test' => array(
                            'city_id' => '12',
                            'page_type' => '4',
                        ),
                    ),
                ),
            ),
            'api/common/operation/notices/' => array(
                'GET' => array(
                    'name' => 'id获取运营通知',
                    'param' => array(
                        'test' => array(
//                            'city_id' => '12',
//                            'page_type' => '1',
                        ),
                    ),
                ),
            ),
        );

        if ($runtime) {
            $tarcerUrl = $runtimeUrl[$runtime] . $url;
        }

        //定义传送字段
        if (!empty($url) && !empty($method)) {
            $postFields = $restfulUrl[$url][$method]['param'];
            if (isset($postFields['test']) or isset($postFields['online'])) {
                if ($runtime == '165' or $runtime == 'Test1') {
                    $postFields = $postFields['test'];
                    $testParam = '165/test1';
                } else {
                    $postFields = $postFields['online'];
                    $testParam = 'web6/online';
                }
            } else {
                $testParam = '通用';
            }
        } else {
            $postFields = array();
        }

        //定义header
        $header = array(
//            'Content-Type:application/json',
            'X-Ganji-CustomerId:705',
            'X-Ganji-VersionId:5.9.0',
            'X-Ganji-InstallId:C6FE4A2140D0632A4C41B8B8BA138582',
            'X-Ganji-ClientAgent:iphone#640*1136',
            'X-Ganji-Agency:appstore',
            'X-Ganji-Token:534352745756576b7473754a31777344534333304270676a',
                //'X-Ganji-Agent:H5',
        );

        $result = '';
        $resultJson = '';
        $reponse = '';
        if (!empty($url)) {
            $ch = curl_init();
            if ($method == 'GET' && !empty($postFields)) {
                $tarcerUrl .= '?';
                $tarcerUrl .= http_build_query($postFields);
            } else {
                curl_setopt($ch, CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $tarcerUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);
            $resultJson = curl_exec($ch);
            $reponse = curl_getinfo($ch);
            curl_close($ch);
            $result = json_decode($resultJson, true);
        }
        $endTime = microtime(true);
        $diffTime = (float) $endTime - $startTime;
        ?>
        <div class="resultArea">
            <ul class="result">
                <li title="<?php echo $url; ?>" class="url"><span>TracerUri：</span><?php echo $tarcerUrl; ?></li>
                <li><span>PostTime：</span><?php echo time(); ?>s</li>
                <li><span>Consume：</span><?php echo $diffTime; ?>s</li>
                <li><span>Method：</span><?php echo $method; ?></li>
                <li><span>Response.HttpCode：</span><?php echo $reponse['http_code']; ?></li>
                <div class="cl"></div>
            </ul>
            <pre class="pre"><?php
                if (is_array($result)) {
                    print_r($result);
                } else {
                    echo 'empty';
                }
                ?></pre>
            <div class="res"><?php
                if ($isTest == 0) {
                    var_dump($resultJson);
                } else {
                    echo '<pre>';
                    print_r($resultJson);
                    echo '</pre>';
                }
                ?></div>
        </div>
        <div class="toolBar">
            <div class="search">
                <form name="form" action="" method="get">
                    <span><input type="radio" name="runtime" value="Web"    <?php if ($runtime == 'Web') echo 'checked="1"'; ?>  />线上</span>
                    <span><input type="radio" name="runtime" value="Web6"  <?php if ($runtime == 'Web6') echo 'checked="1"'; ?> />Web6</span>
                    <span><input type="radio" name="runtime" value="Test1" <?php if ($runtime == 'Test1') echo 'checked="1"'; ?> />Test1</span>
                    <span><input type="radio" name="runtime" value="165"   <?php if ($runtime == '165') echo 'checked="1"'; ?> />165</span> <input type="submit" value="切换" /> <br />
                </form>
            </div>
            <div class="headersInfo">
                <strong>Header</strong>
                <pre><?php print_r($header); ?></pre>
                <br />
                <strong>Params</strong>
                <pre><?php print_r($postFields); ?></pre>
                <p>
                    <?php
                    if ($interface != '') {
                        echo
                        '当前接口参数配置为 <span>' . $testParam . '</span> 环境数据';
                    }
                    ?>
                </p>
                <div class="selectList">
                    <ul>
                        <strong>RESTful</strong>
                        <?php
                        $restfulStr = '';
                        foreach ($restfulUrl as $key => $item) {
                            foreach ($item as $methodKey => $subItem) {
                                $restfulStr .= '<li><a href="?runtime=' . $runtime . '&method=' . $methodKey . '&url=' . $key . '"';
                                if ($url == $key && $methodKey == $method) {
                                    $restfulStr .= ' class="on"';
                                }
                                $restfulStr .= '>' . $key . ' ' . $subItem['name'] . '</a></li>';
                            }
                        }
                        echo $restfulStr;
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="toolBarBg"></div>
    </body>
</html>
