<?php

//数据库配置
$server = 'localhost';
$username = 'root';
$password = '';
$db = 'cn_collect';

//连接数据库
$link = mysql_connect($server, $username, $password) or die('connect error');
mysql_select_db($db, $link) or die('select db error');
mysql_query("SET NAMES UTF8");

//所需要的分类
$allow_product_categorys = array(
    '太阳能集热器',
    '塑料门窗',
    '防水涂料',
    '家用制冷器具',
    '建筑砌块',
    '家用微波炉',
    '人造板及其制品',
    '室内装饰装修用溶剂型木器涂料',
    '厨柜',
    '木质门和钢质门',
    '建筑装饰装修工程',
    '建筑用塑料管材',
    '房间空气调节器',
);
//插入数据库
$i = 1;
do {
    //匹配内容
    $url = "http://datacenter.sepacec.com/entSearch.do?method=view&id=" . $i;
    $content = @file_get_contents($url);
    preg_match_all('/<td\s*[^>]+bgcolor=\"\#FFFFFF\"[^>]+>\s*[\&nbsp\;]*(.*?)\s*<\/td>/is', $content, $match);
    $company_name = processStrForMysql(@$match[1][0]);
    $province = processStrForMysql(@$match[1][1]);
    $product_category = processStrForMysql(@$match[1][2]);
    $approve_time = processStrForMysql(@$match[1][3]);
    $expiry_date = processStrForMysql(@$match[1][4]);
    $certificate_number = processStrForMysql(@$match[1][5]);
    $tested_products = processStrForMysql(@$match[1][6]);

    //执行sql
    if (count($match[1]) > 0) {
        //判断日期是否正确并且是否在设置的分类内
        //if (strlen($approve_time) == 10 && strlen($expiry_date) == 10 && in_array($product_category, $allow_product_categorys)) {
        if (strlen($approve_time) == 10 && strlen($expiry_date) == 10) {
            $sql = 'replace into ent(company_name,province,product_category,approve_time,expiry_date,certificate_number,tested_products) 
            values("' . $company_name . '","' . $province . '","' . $product_category . '","' . $approve_time . '","' . $expiry_date . '","' . $certificate_number . '","' . $tested_products . '")';
            mysql_query($sql);

            //debug
            //file_put_contents("c:/test.txt", '"' . $company_name . '", "' . $province . '", "' . $product_category . '", "' . $approve_time . '", "' . $expiry_date . '", "' . $certificate_number . '", "' . $tested_products . '"' . "\n", FILE_APPEND);
            echo '.';
            flush();
        }
    }

    $i++;
} while ($i < 8500); //id 到8500结束，中间可能有的id删除，所以全部循环

mysql_close($link);

function processStrForMysql($str) {
    return mysql_real_escape_string(@iconv('gb2312', 'utf-8', trim($str)));
}

?>
