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
//        define('URL', 'http://dev.mobds.ganjistatic3.com/posts/');
//        define('URL', 'http://mobtestweb6.ganji.com/datashare/');
//        define('URL', 'http://mobtestweb6.ganji.com/push/');
//        define('URL', 'http://mobds.ganji.cn/datashare/');
        define('URL', 'http://m125624.mustfollow.axfree.mvote.net/op.php');
//        define('URL', 'http://mobds.ganji.cn/users/178591446/posts/0/7/NewPost?post_session_id=AFCB32CB-6E21-4409-B345-E2944BF11DDF&major_category_script_index=1');
//        define('URL', 'http://dev.mobds.ganjistatic3.com/users/1000140086/posts/0/2/NewPost?post_session_id=4ea310ed-37cc-41e3-83ea-26f8b7924726&major_category_script_index=2&tag=541');
        $header = array(
            'Expect:',
            'Content-Type' => 'application/x-www-form-urlencoded',
        );
  
        $post_fields = array(
            'action' => 'dovote',
            'guid' => '0f298e8e-c88f-2239-bf48-83cc4f6b2476',
            'ops' => '2348837',
            'wxparam' => 'oTbZJt8ztIa6XiwhDnspVIvTpJgw|0f298e8e-c88f-2239-bf48-83cc4f6b2476|8e34968f0c3fadca52ff16ceb61551a8|08a33266b025100702265a310038d3c3|vote|1482123670',
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
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
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
        //nginx只支持一个进程，
//        if (strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false) {
        $timer->stop();
        echo $timer->spent('ms') . ' ms<br />';
        $resArr = json_decode($result, true);
        echo '<pre>';
        var_dump($resArr);
//        //print_r($resArr['data']['version']);
        //die;
        echo '</pre>';
        var_dump($result);
        exit;
//        }
        $headerArr = explode("\n", $result);
        foreach ($headerArr as $key => $item) {
            if (stripos($item, 'ChromeLogger-Data') > 0) {
                header($item);
            }
        }
        $result = explode("\n\r\n", $result);
        $result = $result[2];
        $result = array('data' => $result);
        //回调返回url
        $requestUris = explode('/', $_SERVER['REQUEST_URI']);
        unset($requestUris[count($requestUris) - 1]);
        $requestUri = implode('/', $requestUris);
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $requestUri . '/a.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
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
        echo '</pre>';
        var_dump($result);
        exit;
//search_debug=2225342
        ?>
    </body>
</html>
