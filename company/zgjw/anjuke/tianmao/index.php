<?php

$base = new BaseModel();
$handle = $base->getDbHandler('lrtest');

set_time_limit(0);
header("Content-type: text/html; charset=gbk");
$base_url = "https://jia.tmall.com/?spm=3.7396704.20000034.3.FeGGul&abbucket=&acm=tt-1143139-39143.1003.8.209658&aldid=209658&abtest=&scm=1003.8.tt-1143139-39143.OTHER_1438590153981_209658&pos=1";
//$base_url = dirname(__FILE__) . "/list.html";
$content = file_get_contents("compress.zlib://" . $base_url);
//$content = file_get_contents($base_url);
//列表页数据
$pattern1 = "/<li\s+class\=\"main3_body_lis\s*main3_body_lis\d+\"[^>]*>.*?<a\s*href=\"(.*?)\"[^>]*>.*?<\/li>/is";
$pattern2 = "/<li\s+class\=\"main5_lis\"[^>]*>.*?<a\s*href=\"(.*?)\"[^>]*>.*?<\/li>/is";
preg_match_all($pattern1, $content, $matches1);
preg_match_all($pattern2, $content, $matches2);
$pro_links = array_merge($matches1[1], $matches2[1]);
foreach ($pro_links as $pro_link) {
    sleep(1);
    $pro_link = "https:" . $pro_link;
    //详情页数据
//    $base_url = dirname(__FILE__) . "/detail.html";
//    $content = file_get_contents($base_url);
    $content = file_get_contents("compress.zlib://" . $pro_link);

    //商品名称
    $pattern_proname = "/<h1\s*data-spm=\"\d+\">\s*(.*)\s*<\/h1>/is";
    preg_match_all($pattern_proname, $content, $matches_proname);
    $pro_name = $matches_proname[1][0];
    $pattern1 = "/<a\s+class=\"J_EbrandLogo\"[^>]*>(.*?)<\/a>/is";
    preg_match_all($pattern1, $content, $matches1);
//$pattern2 = "/<ul\s+id=\"J_AttrUL\"[^>]*>\s*<li\s*[^>]*>.*?<\/li>\s*<\/ul>/is";
//$pattern2 = "/<ul\s+id=\"J_AttrUL\"[^>]*>(\s*<li[^>]*>(.*?)<\/li>\s*)*/is";
//$pattern2 = "/<ul\s+id=\"J_AttrUL\"[^>]*>(\s*<li[^>]*>(.*?)<\/li>\s*)+?<\/ul>/is";
    $pattern2 = "/<ul\s+id=\"J_AttrUL\"[^>]*>(\s*<li[^>]*>(.*?)<\/li>\s*)*/is";
    preg_match_all($pattern2, $content, $matches2);
    $params = $matches2[0][0];
    $pattern3 = "/<li[^>]*>(.*?)<\/li>/is";
    preg_match_all($pattern3, $params, $matches3);
    $param_arr = array_map(function($v) {
        return str_replace("&nbsp;", "", $v);
    }, $matches3[1]);
    $param_str = implode("=>", $param_arr);
    $data = array(
        'url' => $pro_link,
        'name' => iconv("GBK", "UTF-8//IGNORE", $pro_name),
        'pro_param' => iconv("GBK", "UTF-8//IGNORE", $param_str),
        'create_at' => time(),
    );
    DBMysqlNamespace::insert($handle, 'zgjw_tianmao_pro', $data);
}

//
//
//测试
//$a = "<ul>
//        <li>你好</li>
//        <li>我是</li>
//        <li>李睿</li>
//        </ul>";
////$pattern2 = "/<ul>(\s*<li>.*<\/li>\s*)*/is";
//$pattern2 = "/<ul>\s+(<li(?=.*)>(?=.*)<\/li>)\s+<\/ul>/is";
//$pattern2 = "/<ul>\s+(<li(?=.*)>(?=.*)<\/li>)\s+<\/ul>/is";
//preg_match_all($pattern2, $a, $matches2);
//var_dump($matches2);
//die;
?>
