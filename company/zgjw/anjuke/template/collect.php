<?php

set_time_limit(0);
unset($_SESSION['add_area']);
/**
 * 小区采集
 */
$base_url = trim($_POST['url']);
$province = getProvinceByid(trim($_POST['province']));
$district = trim($_POST['district']);
$collecter = trim($_POST['collecter']);
//$regular_url = trim($_POST['regular_url']);
$city = getCityByid(trim($_POST['city']));
$area = getAreaByid(trim($_POST['area']));
//$page = isset($_POST['pager']) && (int) trim($_POST['pager']) > 0 ? (int) trim($_POST['pager']) : 1;
//sleep(5);
$content = file_get_contents("compress.zlib://" . $base_url);

//匹配url
$pattern_url = '/(.+)\d+_*?\.htm/is';

//单个总条数
$pattern_page = "/<span\s+class=\"snorange\">(\d+)<\/span>/is";

#匹配小区列表
$parttern_list = '/<div\s+class="sslalone"\s+id="loupan_\d+">.*?<\/ul>/is';
preg_match_all($parttern_list, $content, $preg_list_arr);

#1. 匹配小区名称和链接
$parttern_name_link = '/<a\s+class=\"snblue\"\s+target=\"_blank\"\s+href="(.*?)"[^>]*>(.*?)<\/a>/is';
#2. 匹配地址
$parttern_address = '/<font\s+title="\[.*?\](.*?)">/is';
$parttern_address1 = '/>\]\s*<\/span>(.*?)<span\s+class=\"ckdt\">/is';
#3. 匹配房价
$parttern_price = '/<li\s+class=\"junjia\"\s*>/is';

preg_match($pattern_url, $base_url, $url_marth); //url前缀
preg_match_all($pattern_page, $content, $page_arr); //总页数
$page_num = ceil($page_arr[1][0] / 10); //总页数
#2.插入商圈，判断商圈是否存在
$sql_district_exist = "select count(*) as num from tbl_districts where district='" . $district . "' and areaid=" . $area['code'];
$r_district_exist = mysql_query($sql_district_exist);
$arr_district_exist = mysql_fetch_assoc($r_district_exist);
if ($arr_district_exist['num'] <= 0) { //商圈不存在,则插入
    $insert_district_sql = "insert into tbl_districts (`districtid`,`district`,`areaid`,`username`)
                VALUES ('0','" . $district . "','" . $area['code'] . "','" . $collecter . "');
    ";
    mysql_query($insert_district_sql);
}

for ($i = 1; $i <= $page_num; $i++) {
    $url = $url_marth[1] . $i . '_.htm';
    $content = file_get_contents("compress.zlib://" . $url);
    preg_match_all($parttern_list, $content, $preg_list_arr);
    $lists = $preg_list_arr[0];
    foreach ($lists as $list) {
        if (!$list) {
            continue;
        }
        //正规
        preg_match_all($parttern_name_link, $list, $name_link_arr);
        preg_match_all($parttern_address, $list, $address_arr);
        if (!$address_arr[1][0]) {
            preg_match_all($parttern_address1, $list, $address_arr);
        }

        $content_name = iconv('GBK', 'UTF-8', $name_link_arr[2][0]); //小区名称
        $target_url = iconv('GBK', 'UTF-8', $name_link_arr[1][0]); //小区链接地址
        $address = iconv('GBK', 'UTF-8', $address_arr[1][0]); //小区地址
//插入数据库
        #1. 判断小区是否存在，根据小区区域和小区名来判定
        $sql_area_exist = "select count(*) as num from tbl_content where name='" . $content_name . "' and area=" . $area['code'];
        $r_area_exist = mysql_query($sql_area_exist);
        $arr_area_exist = mysql_fetch_assoc($r_area_exist);
        if ($arr_area_exist['num'] > 0) { //小区已经存在
            continue;
        }
        //插入小区
        $insert_sql = "insert into tbl_content
 (`name`, `address`, `urltext`, `date`, `province`, `city`, `area`,
 `district`,`price_flat`, `username`,`inputtime`,`updatatime`) VALUES
 ('" . $content_name . "','" . $address . "','" . $target_url . "','" . $finish_data . "',
     '" . $province['code'] . "','" . $city['code'] . "','" . $area['code'] . "','" . $district . "',
         '" . $price . "','" . $collecter . "','" . time() . "','" . time() . "');";
        if ($content_name) {
            if (mysql_query($insert_sql)) {
                //插入session,刷新输出
                $_SESSION['add_area'][] = $content_name;
            }
        }
        sleep(5);
    }
}