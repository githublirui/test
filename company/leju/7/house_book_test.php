<?php

/**
  本地测试 绑定host 10.207.26.254 yangyi.data.house.sina.com.cn
  线上site地址	http://data.house.sina.com.cn
  查询审核状 get_house_book_status 	1:未审核|2:通过审核|3:驳回
 */
function send_house_book() {
    $type = isset($_GET['type']) ? trim($_GET['type']) : 'book';
    $site = 'http://yangyi.data.house.sina.com.cn';
    switch ($type) {
        case 'book': //推送文本
            $url = $site . "/api/house.php?action=put_house_book_text";
            $data = array(
                "bid" => "12345", //批次id
                "hid" => '107667',
                "city_en" => 'bn',
                "summary_pic" => "概览篇头图",
                "property_price" => "20000",
                "open_time" => "1438746267",
                "build_type" => "建筑类型",
                "pro_info" => "项目介绍",
                "around_info" => "周边配套",
                "around_traffic" => "周边交通",
                    );
            break;
        case 'media': //推送多媒体
            $url = $site . "/api/house.php?action=put_house_book_media";
            $data = array(
                "bid" => "123456", //批次ID
                "hid" => '107667',
                "city_en" => 'bn',
                'info' => array(
                    array(
                        "type" => "1", //媒体类型, 1.户型 2.相册 3.360度看房 4.视频看房
                        "title" => "标题1", //标题
                        "cat_type" => '一居室', //分类类型(户型: 一居室,二居室..),(相册: 效果图，样板间..)
                        "pic" => "pic/pp", //图片，封面
                        "url" => "url", //外链地址
                        "video_id" => "12233", //视频id
                    ),
                    array(
                        "type" => "2", //媒体类型, 1.户型 2.相册 3.360度看房 4.视频看房
                        "title" => "标题1", //标题
                        "cat_type" => '二居室', //分类类型(户型: 一居室,二居室..),(相册: 效果图，样板间..)
                        "pic" => "pic/pp", //图片，封面
                        "url" => "url", //外链地址
                        "video_id" => "12233", //视频id
                    ),
                ),
                    );
            break;
        case 'status': //查询审核状态
            $url = $site . "/api/house.php?action=get_house_book_status";
            $data = array(
                "bid" => "12345", //批次id
                "type" => "1",
                    );
            break;
        default:
            exit('Missing parameters');
            break;
    }
    $data = gbk2utf8($data);
    $poststatus = curl_post($url, array('data' => json_encode($data)));
    $poststatus = json_decode($poststatus, true);
    var_dump($poststatus);
    exit;
}

send_house_book();

/**
 * 编码转换,gbk -> utf8
 */
function gbk2utf8($s) {
    return iconv_array("gbk", "utf-8", $s);
}

/**
 * 编码转换,utf8 -> gbk
 */
function utf82gbk($s) {
    return iconv_array("utf-8", "gbk", $s);
}

/**
 * 对数组进行编码转换
 *
 * @param strint $in_charset  输入编码
 * @param string $out_charset 输出编码
 * @param array $arr          输入数组
 * @return array              返回数组
 */
function iconv_array($in_charset, $out_charset, $arr) {
    if (strtolower($in_charset) == "utf8") {
        $in_charset = "UTF-8";
    }
    if (strtolower($out_charset) == "utf-8" || strtolower($out_charset) == 'utf8') {
        $out_charset = "UTF-8";
    }
    if (is_array($arr)) {
        foreach ($arr as $key => $value) {
            $arr[iconv($in_charset, $out_charset, $key)] = $value;
            unset($arr[$key]);
            $arr[iconv($in_charset, $out_charset, $key)] = iconv_array($in_charset, $out_charset, $value);
        }
    } else {
        if (!is_numeric($arr)) {
            //$arr = iconv($in_charset, $out_charset, $arr);
            #针对UTF8编码中含有特殊的字符做下替换，转换成正常的
            //$arr = str_replace(array("\xC2\xA0",chr(226)), '&nbsp;', $arr);
            $arr = mb_convert_encoding($arr, $out_charset, $in_charset);
        }
    }
    return $arr;
}

/**
 * curl POST
 * @param string $url       请求地址
 * @param array  $data 		post参数
 * @param array  $header    超时时间
 * $param int	 $timeout	curl执行超时时间
 * @Param int	 $port		端口
 * @return mixed
 */
function curl_post($url, $data, $timeout = 3) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $ret = curl_exec($ch);
    if ($ret === false) {
        write_log($url . '--Curl error(refer::' . $_SERVER["HTTP_REFERER"] . '): SERVER_ADDR(' . $_SERVER['SERVER_ADDR'] . ');REMOTE_ADDR(' . $_SERVER['SERVER_ADDR'] . ');;' . curl_error($ch) . '<br>', 'curl_err.txt');
    }
    curl_close($ch);
    return $ret;
}
