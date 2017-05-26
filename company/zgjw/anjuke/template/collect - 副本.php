<?php

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

$content = file_get_contents($base_url);
//单个总条数
$pattern_page = "/<span\s+class=\"red\">(\d+)<\/span>/is";
//搜房网，匹配全部
$pattern = "/<div\s+class=\"searchListNoraml\">[\s\S]*?<div\s+class=\"hotlinebox01\">/is";
//搜房网，单个匹配名字，小区详情地址
$pattern_a = "/<div\s+class=\"name\">[^>]+<a[^>]+href=\"(.+?)\">(.+?)<\/a>/is";
//单个匹小区开发商
$pattern_dev = "/<li\s+class=\"s2\">[^>]*<a[^>]+>(.+)<\/a>[^>]*<\/li>/is";
//单个匹配小区地址
$pattern_address = "/<li\s+class=\"s2\"\s+id=\"sjina_B02_\d+\"[^>]*>[^<]*<font[^>]*>(.+)<\/font>/is";
//单个匹配小区售价
$pattern_price = "/<span\s+class=\"price_type\">(.+)<\/span>/is";
//匹配url
$pattern_url = '/(.+)\d+_\.htm/is';

preg_match($pattern_url, $base_url, $url_marth); //url前缀
preg_match_all($pattern_page, $content, $page_arr); //总页数
$page_num = ceil($page_arr[1][0] / 10); //总页数
#2.插入商圈，判断商圈是否存在
$sql_district_exist = "select count(*) as num from tbl_districts where district='" . $district . "' and areaid=" . $area['areaid'];
$r_district_exist = mysql_query($sql_district_exist);
$arr_district_exist = mysql_fetch_assoc($r_district_exist);
if ($arr_district_exist['num'] <= 0) { //商圈不存在,则插入
    $insert_district_sql = "insert into tbl_districts (`districtid`,`district`,`areaid`,`username`)
                VALUES ('0','" . $district . "','" . $area['areaid'] . "','" . $collecter . "');
    ";
    mysql_query($insert_district_sql);
}


for ($i = 1; $i <= $page_num; $i++) {
    $url = $url_marth[1] . $i . '_.htm';
    $content = file_get_contents($url);
    preg_match_all($pattern, $content, $lis_arr); //列表
    $lists = $lis_arr[0];
    foreach ($lists as $list) {
        preg_match_all($pattern_a, $list, $name_link_arr);
        preg_match_all($pattern_dev, $list, $dev_arr);
        preg_match_all($pattern_address, $list, $address_arr);
        preg_match_all($pattern_price, $list, $price_arr);

        $content_name = $name_link_arr[1][0]; //小区名称
        $target_url = $name_link_arr[2][0]; //小区链接地址
        $address = $address_arr[1][0]; //小区地址
        $finish_data = ''; //竣工日期
        $price = $price_arr[1][0]; //售价
        //插入数据库
    }
}





preg_match_all($pattern_price, $content, $page_arr); //总页数
$page_num = ceil($page_arr[1][0] / 10); //总页数

for ($i = 1; $i <= $page_num; $i++) {
    $pattern_url = '/(.+)\d+_\.htm/is';

    preg_match($pattern_url, $base_url, $url_marth);
    $url = $url_marth[1] . $i . '_.htm';
    $content = file_get_contents($url);
    preg_match_all($pattern, $content, $lis_arr); //列表
    $lists = $lis_arr[0];
    foreach ($lists as $list) {
        preg_match_all($pattern_a, $list, $name_link_arr);
        preg_match_all($pattern_dev, $list, $dev_arr);
        preg_match_all($pattern_address, $list, $address_arr);
        preg_match_all($pattern_price, $list, $price_arr);
        //插入数据库
        if (mysql_query($insert_sql)) {
            //插入session,刷新输出
            $_SESSION['add_area'][] = $content_name;
        }
    }
}