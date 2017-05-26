<?php

//$main_url = 'http://bihaifangzhou.soufun.com/';
//$main_content = file_get_contents("compress.zlib://" . $main_url);
//$huxing_link_preg1 = '/<a\s+id="xqw_B01_20"\s*href="([.\w\/\/:]+)"[^>]*>.*?<\/a>/is';
//
//preg_match_all($huxing_link_preg1, $main_content, $huxing_link);
//var_dump($huxing_link);die;
/**
 * 从安居客采集户型图
 */
function collectHuxingImageFromAnjuke($row) {
    $main_url = $row['urltext'];
    #获取户型图的url
    #target = http://tianjin.anjuke.com/community/photos/model/197473
    $march_link_preg = '/http\:\/\/(\w+)\.anjuke\.com\/community\/view\/(\d+)/is';
    preg_match_all($march_link_preg, $main_url, $march_link);
    $huxing_url = 'http://' . $march_link[1][0] . '.anjuke.com/community/photos/model/' . $march_link[2][0];

    #匹配获取居室的url
    $huxing_content = file_get_contents($huxing_url);

    $march_huxing_link_wraper_preg = '/<div\s+class=\"list-tab\">(.*)<\/div>/isU';
    preg_match_all($march_huxing_link_wraper_preg, $huxing_content, $march_huxing_link_wraper);

    $march_huxing_link_wraper = $march_huxing_link_wraper[1][0];
    $march_huxing_link_preg = '/<a\s+href="(.*?)">(.*?)\(\d*\)<\/a>/is';
    preg_match_all($march_huxing_link_preg, $march_huxing_link_wraper, $march_huxing_links);

    $march_huxing_link_names = $march_huxing_links;
    $march_huxing_links = $march_huxing_links[1];
    foreach ($march_huxing_links as $i => $march_huxing_link) {
        collectOneHuxingImageFromAnjuke(array('name' => $march_huxing_link_names[2][$i], 'link' => $march_huxing_link, 'tbl_content_row' => $row));
    }
}

/**
 * 匹配一个户型
 * @param type $link_row
 */
function collectOneHuxingImageFromAnjuke($link_row) {
    $huxing_type = getHuxingTypeFromTxt($link_row['name']);
    $huxing_content = file_get_contents($link_row['link']);

    $march_huxing_preg = '/<dl\s+class=\"photo-list\">(.*)<\/dl>/isU';
    preg_match_all($march_huxing_preg, $huxing_content, $march_huxings);
    $march_huxings = $march_huxings[1][0];

    $march_huxing_image_link_preg = '/<a\s+class=\"title\"\s*href=\"(.*?)\">(.*?)<\/a>/is';
    preg_match_all($march_huxing_image_link_preg, $march_huxings, $march_huxing_image_links);
    $march_huxing_image_link_names = $march_huxing_image_links;
    $march_huxing_image_links = $march_huxing_image_links[1];
    foreach ($march_huxing_image_links as $i => $march_huxing_image_link) {
        insertOneHuxingImageFromAnjuke(array('name' => $march_huxing_image_link_names[2][$i], 'link' => $march_huxing_image_link, 'huxing_type' => $huxing_type, 'tbl_content_row' => $link_row['tbl_content_row']));
    }
}

/**
 * 插入一个户型图
 * @param type $link_name
 */
function insertOneHuxingImageFromAnjuke($link_name) {
    $image_content = file_get_contents($link_name['link']);
    //<img alt="金茂现场房型图" title="" src="236/600x600.jpg">
    $march_huxing_image_preg = '/<img[^>]+src="(.*\/600x600\.\w+)"[^>]+>/is';
    preg_match_all($march_huxing_image_preg, $image_content, $march_huxing_image);
    $url = $march_huxing_image[1][0];
    $file_name = substr(md5(uniqid(rand(), true)), 0, 10) . time();
    $ext = strrchr(strtolower($url), ".");
    if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png") {
        return;
    }
    file_put_contents(dirname(__FILE__) . '\images/anjuke/' . $file_name . $ext, file_get_contents($url));
    #插入数据库
    $row['title'] = $link_name['name'];
    $row['type'] = $link_name['huxing_type'];
    $row['image'] = $file_name . $ext;
    $row['tbl_content_id'] = $link_name['tbl_content_row']['id'];
    $row['tbl_content_name'] = $link_name['tbl_content_row']['name'];
    $row['tbl_content_province'] = $link_name['tbl_content_row']['province'];
    $row['tbl_content_city'] = $link_name['tbl_content_row']['city'];
    $row['tbl_content_area'] = $link_name['tbl_content_row']['area'];
    insertHuxingDb($row);
}

function insertHuxingDb($row) {
    $sql = "insert into `tbl_house_type_manage` (`heme_title`,`heme_type`,`heme_town_id`,`heme_town_name`,`heme_province`,`heme_city`,`heme_area`,`heme_general_img`,`heme_img_url`,`heme_create_date`,`heme_status`) values 
('{$row['title']}','{$row['type']}','{$row['tbl_content_id']}','{$row['tbl_content_name']}','{$row['tbl_content_province']}','{$row['tbl_content_city']}','{$row['tbl_content_area']}','{$row['image']}','/uploads/area/huxing','" . date('Y-m-d H:i:s') . "',1)        
";
    mysql_query($sql);
    echo '.';
    flush();
}

function insertOneHuxingImageFromSouFun($link_name) {
    $image_content = file_get_contents($link_name['link']);
    $file_name = substr(md5(uniqid(rand(), true)), 0, 10) . time();
    $ext = strrchr(strtolower($link_name['link']), ".");
    if ($ext != ".gif" && $ext != ".jpg" && $ext != ".png") {
        return;
    }
    file_put_contents(dirname(__FILE__) . '\images/soufun/' . $file_name . $ext, $image_content);
    sleep(7);
}

/**
 * 通过文字获取户型的类型
 * @param type $txt
 */
function getHuxingTypeFromTxt($txt) {
    $result = '';
    switch ($txt) {
        case '一室';
            $result = '1';
            break;
        case '二室';
            $result = '2';
            break;
        case '三室';
            $result = '3';
            break;
        case '四室';
            $result = '4';
            break;
        case '五室';
            $result = '5';
            break;
        case '六室';
            $result = '6';
            break;
        case '七室';
            $result = '7';
            break;
    }
    return $result;
}

/* * ************************* */
/*       搜房网采集          */
/* * ************************* */

function collectHuxingImageFromSouFun($row) {
    $main_url = ($row['urltext']);

    #两种url小区，分类处理
    $ur_preg = '/http\:\/\/\d+\.soufun\.com/is';
    if (preg_match($ur_preg, $main_url)) {
        #第一种
        #1. 获取户型图的url
        $url_content = file_get_contents("compress.zlib://" . $main_url);
        $march_huxing_url_preg = '/<a\s*href="([.\w\/\/:]+)"\s+id="xfxq_B01_28"[^>]*?>/is';
        preg_match_all($march_huxing_url_preg, $url_content, $march_huxing_url);
        $march_huxing_url = $march_huxing_url[1][0];
        #2. 采集图片分两种情况
        $huxing_content = file_get_contents("compress.zlib://" . $march_huxing_url);
        if (!preg_match('/hxsearch_hx/is', $huxing_content)) {
            #第一种分页的形式
            #获取户型图幻灯片的链接
            $popup_preg = '/<span\s+class=\"container\">.*<a[^>]*href="(.*)"[^>]*>.*<\/a>.*<\/span>/isU';
            preg_match_all($popup_preg, $huxing_content, $march_huxing_popup_link);
            $popup_link = $march_huxing_popup_link[1][0];
            #采集图片插入数据库
            #匹配列表链接
            collectPopupImageFromSouFun($popup_link);
        } else {
            #第二种居室的形式
            #获取单个
            $img_preg = '/href="(http\:\/\/\d+\.soufun\.com\/photo\/d_house_\d+\.htm)"/is';
            preg_match_all($img_preg, $huxing_content, $img);
            $img_link = $img[1][0];
            collectOneHuxingImageFromSouFun($img_link);
        }
    } else {
        #第二种
        $main_url = 'http://bihaifangzhou.soufun.com/';
        $main_content = file_get_contents("compress.zlib://" . $main_url);
        $huxing_link_preg = '/<a\s*href="([.\w\/\/:]+)"[^>]*id="[\w\:\[\]]*xq_B01_28"[^>]*[^>]*>.*<\/a>/is';
        $huxing_link_preg1 = '/<a\s+id="xqw_B01_20"\s*href="([.\w\/\/:]+)"[^>]*>.*?<\/a>/is';

        preg_match_all($huxing_link_preg, $main_content, $huxing_link);
        if (!$huxing_link) {
            preg_match_all($huxing_link_preg1, $main_content, $huxing_link);
        }
        $huxing_link = $huxing_link[1][0];
        if ($huxing_link) {
            $huxing_content = file_get_contents("compress.zlib://" . $huxing_link);
            #匹配一个
            $img_preg = '/href="(http\:\/\/\w+\.soufun\.com\/photo\/d_house_\d+\.htm)"/is';
            preg_match_all($img_preg, $huxing_content, $img);
            $img_link = $img[1][0];
            collectPopupImageFromSouFun($img_link, false);
        }
    }
}

function collectOneHuxingImageFromSouFun($url, $huxing_type = '') {
    $image_content = file_get_contents("compress.zlib://" . $url);
    //获取title
    $title_preg = '/<h1\s+id="photo_title">(.*?)<\/h1>/is';
    preg_match_all($title_preg, $image_content, $title);
    $title = $title[1][0];

    #获取图片
    $img_preg = '/<img\s*id="Display"[^>]*src="(.+?)"[^>]*>/is';
    preg_match_all($img_preg, $image_content, $img);
    insertOneHuxingImageFromSouFun(array('name' => $title, 'link' => $img[1][0], 'huxing_type' => $huxing_type));
}

function collectPopupImageFromSouFun($popup_link, $from_num = true) {
    $popup_image_content = file_get_contents("compress.zlib://" . $popup_link);
    #匹配列表链接
    $popup_image_preg = '/<ul.*?id="smallPhoto"[^>]*>(.*?)<\/ul>/is';
    preg_match_all($popup_image_preg, $popup_image_content, $march_huxing_popup_image);
    $popup_image_a_preg = '/<a\s*href="(.*?)"[^>]*>.*?<\/a>/is';
    preg_match_all($popup_image_a_preg, $march_huxing_popup_image[1][0], $big_image_link);
    $big_image_link = $big_image_link[1][0];
    #获取photo的json,获取图片的id
    $json_preg = '/var\s+photoJson\s*=\s*\[(.*?)\]/is';
    $json_pic_preg = '/"picid"\:"(\d*?)"/is';
    preg_match_all($json_preg, $popup_image_content, $json);
    preg_match_all($json_pic_preg, $json[1][0], $json_img);
    $json_imgs = $json_img[1];
    foreach ($json_imgs as $json_img) {
        $url = substr($big_image_link, 0, strrpos($big_image_link, '.'));
        if ($from_num) {
            $big_image_url = $url . $json_img . '.htm';
        } else {
            $big_image_url = $url . '.htm';
        }
        collectOneHuxingImageFromSouFun($big_image_url);
    }
}