<?php

$a = array('xf' => '新房', 'esf' => '二手房', 'bdfc' => '本地房产', 'cssq' => '成熟社区', 'jiaju' => '家居', 'jth' => '集团号', 'other' => '其他');
$s = array('xf', 'esf');
foreach ($s as $v) {
    $arr[$v] = $a[$v];
}
var_dump($arr);
die;
$string = '@string1@1231@aaa';
$token = strtok($string, "@");
var_dump($token);
die;
$arr = explode("@", $string);
$toks = array(':', ' ');
$users = array();

foreach ($arr as $k => $v) {
    if (empty($v))
        continue;

    if (strpos($v, ":") === false) {
        $users[] = strtok($v, ' ');
    } else {
        $users[] = strtok($v, ':');
    }
}

print_r($users);
die;
var_dump($_SERVER['SCRIPT_FILENAME']);
die;
echo memory_get_usage();
$s = range(1, 50000);
echo '<br/>';
echo memory_get_usage(); //123224 查看内存消耗
die;
echo microtime(TRUE); //微妙值1432193311.6222
echo '<br/>';
echo microtime(); //0.62221200 1432193311 数组
die;

$s = 'a:23:{s:2:"id";s:2:"98";s:15:"weixin_house_id";s:3:"343";s:5:"title";s:20:"嫁接游戏专用10";s:7:"keyword";s:8:"嫁接10";s:8:"password";s:4:"1234";s:6:"wx_pic";s:90:"http://src.house.sina.com.cn/imp/imp/deal/28/3c/f/3a71ef8ae80bd81259ef333e74f_p10_mk10.jpg";s:13:"platform_type";s:0:"";s:8:"i_fields";s:17:"telphone,truename";s:6:"msg_id";s:4:"3098";s:22:"person_max_award_count";s:1:"5";s:19:"day_max_award_count";s:1:"5";s:22:"mobile_max_award_count";s:1:"5";s:10:"count_type";s:1:"1";s:9:"game_addr";s:75:"http://mobile.house.sina.com.cn/game/201505/test/game.php?wx_id=343&igid=98";s:10:"start_time";s:10:"1431567240";s:8:"end_time";s:3:"111";s:11:"create_time";s:10:"1431567359";s:9:"edit_time";s:10:"1431567424";s:11:"create_user";s:7:"caopeng";s:9:"edit_user";s:7:"caopeng";s:17:"end_awarding_time";s:10:"1431740100";s:9:"city_code";s:2:"bj";s:9:"is_refund";s:1:"0";}';
var_dump(unserialize($s));
die;


$str = 'lirui5@leju.com';
//echo strstr($str,'@');#@leju.com
//echo strstr($str, '@', true); //lirui5
$a = 1;
$b = 2;
if ($a = 3 || $b == 4) {
//    echo $a . '----' . $b;//1,2
}
//var_dump(md5('240610708') == md5('QNKCDZO')); //true
//echo '<script>console.log(1231)</script>';

$menu = array(
    'button' =>
    array(
        0 =>
        array(
            'name' => '找经纪人',
            'sub_button' =>
            array(
                0 =>
                array(
                    'type' => 'view',
                    'name' => '金牌金经纪人',
                    'url' => 'http://m.bch.leju.com/weixin/fnj/gold_agent_list.html?city_id=bj&unit_id=139654',
                ),
                1 =>
                array(
                    'type' => 'view',
                    'name' => '小经纪人',
                    'url' => 'http://m.bch.leju.com/weixin/esf/bj/agent/?unitid=139654',
                ),
                2 =>
                array(
                    'type' => 'scancode_waitmsg',
                    'name' => '扫码带提示',
                    'key' => 'rselfmenu_0_0',
                    'sub_button' => array(),
                ),
                3 =>
                array(
                    'type' => 'scancode_push',
                    'name' => '扫码推事件',
                    'key' => 'rselfmenu_0_1',
                    'sub_button' => array(),
                ),
                4 =>
                array(
                    'type' => 'pic_sysphoto',
                    'name' => '系统拍照发图',
                    'key' => '系统拍照发图',
                    'sub_button' => array(),
                ),
            ),
        ),
        1 =>
        array(
            'name' => '找房源',
            'sub_button' =>
            array(
                0 =>
                array(
                    'type' => 'view',
                    'name' => '认证真房源',
                    'url' => 'http://m.bch.leju.com/?site=weixin&ctl=esf&act=search&topic_status=2&city=bj&unitid=139654',
                ),
                1 =>
                array(
                    'type' => 'view',
                    'name' => '出售二手房',
                    'url' => 'http://m.bch.leju.com/weixin/esf/unit_house.html?city=bj&unitid=139654&type=3',
                ),
                2 =>
                array(
                    'type' => 'view',
                    'name' => '租赁房源',
                    'url' => 'http://m.bch.leju.com/?site=weixin&ctl=esf&act=search_z&city=bj&unitid=139654',
                ),
            ),
        ),
        2 =>
        array(
            'name' => '社区生活',
            'sub_button' =>
            array(
                0 =>
                array(
                    'type' => 'view',
                    'name' => '我的消息',
                    'url' => 'http://m.bch.leju.com/touch/im/message.html?touch_type=wx',
                ),
                1 =>
                array(
                    'type' => 'view',
                    'name' => '小区介绍',
                    'url' => 'http://m.bch.leju.com/weixin/esf/bj/info/139654.html',
                ),
                2 =>
                array(
                    'type' => 'view',
                    'name' => '房价点评',
                    'url' => 'http://app.fangjiadp.com/?s=yd_wx',
                ),
                3 =>
                array(
                    'type' => 'view',
                    'name' => '装修攻略',
                    'url' => 'http://zx.jiaju.sina.com.cn/?utm_source=weixiaoqu&app=Shouji&mod=Gonglue',
                ),
                4 =>
                array(
                    'type' => 'click',
                    'name' => '客服电话',
                    'key' => '电话',
                ),
            ),
        ),
    ),
);
echo json_encode($menu);
exit;

$s = array('key' => 'keyword');
//var_dump(key($s));
//die;
?>
