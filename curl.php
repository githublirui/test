<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>模拟提交</title>
    </head>
    <body>
        <?php
        require_once 'Timer.php';
        error_reporting(E_ERROR);
        define('URL', 'http://open.bch.leju.com/api/member/getlist.html?reg_time=1428032307,1428032777&page=5&count=3&&timestamp=1431315422sign=xxx');
        $header = array(
            'idfa:FD359279-2A6A-4F55-B3CA-91027B321A0D',
        );
        $post_fields = array(
            'menu' => '{"button":[{"name":"\u627e\u7ecf\u7eaa\u4eba","sub_button":[{"type":"view","name":"\u91d1\u724c\u91d1\u7ecf\u7eaa\u4eba","url":"http:\/\/m.bch.leju.com\/weixin\/fnj\/gold_agent_list.html?city_id=bj&unit_id=139654"},{"type":"view","name":"\u5c0f\u7ecf\u7eaa\u4eba","url":"http:\/\/m.bch.leju.com\/weixin\/esf\/bj\/agent\/?unitid=139654"},{"type":"scancode_waitmsg","name":"\u626b\u7801\u5e26\u63d0\u793a","key":"rselfmenu_0_0","sub_button":[]},{"type":"scancode_push","name":"\u626b\u7801\u63a8\u4e8b\u4ef6","key":"rselfmenu_0_1","sub_button":[]},{"type":"pic_sysphoto","name":"\u7cfb\u7edf\u62cd\u7167\u53d1\u56fe","key":"\u7cfb\u7edf\u62cd\u7167\u53d1\u56fe","sub_button":[]}]},{"name":"\u627e\u623f\u6e90","sub_button":[{"type":"view","name":"\u8ba4\u8bc1\u771f\u623f\u6e90","url":"http:\/\/m.bch.leju.com\/?site=weixin&ctl=esf&act=search&topic_status=2&city=bj&unitid=139654"},{"type":"view","name":"\u51fa\u552e\u4e8c\u624b\u623f","url":"http:\/\/m.bch.leju.com\/weixin\/esf\/unit_house.html?city=bj&unitid=139654&type=3"},{"type":"view","name":"\u79df\u8d41\u623f\u6e90","url":"http:\/\/m.bch.leju.com\/?site=weixin&ctl=esf&act=search_z&city=bj&unitid=139654"}]},{"name":"\u793e\u533a\u751f\u6d3b","sub_button":[{"type":"view","name":"\u6211\u7684\u6d88\u606f","url":"http:\/\/m.bch.leju.com\/touch\/im\/message.html?touch_type=wx"},{"type":"view","name":"\u5c0f\u533a\u4ecb\u7ecd","url":"http:\/\/m.bch.leju.com\/weixin\/esf\/bj\/info\/139654.html"},{"type":"view","name":"\u623f\u4ef7\u70b9\u8bc4","url":"http:\/\/app.fangjiadp.com\/?s=yd_wx"},{"type":"view","name":"\u88c5\u4fee\u653b\u7565","url":"http:\/\/zx.jiaju.sina.com.cn\/?utm_source=weixiaoqu&app=Shouji&mod=Gonglue"},{"type":"click","name":"\u5ba2\u670d\u7535\u8bdd","key":"\u7535\u8bdd"}]}]}',
        );
        $timer = new Timer();
        $timer->start();
        set_time_limit(500);
        $url = URL;
        echo $url . '<br />';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
// curl_setopt($ch, CURLOPT_NOBODY, true);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        echo '<br />';
        $timer->stop();
        echo $timer->spent('ms') . ' ms<br />';
        echo '<pre>';
        $resArr = json_decode($result, true);
        echo '<pre>';
        var_dump($resArr);
//        //print_r($resArr['data']['version']);
        //die;
        echo '</pre>';
        var_dump($result);
        exit;
        ?>
    </body>
</html>
