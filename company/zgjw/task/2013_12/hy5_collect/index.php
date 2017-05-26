<?php

include '../../conn.php';
include '../../func.php';
$pdo_instance = MyPDO::getInstance();


/**
 * 
 * 采集设计师的程序
 * @date 2013-12-3
 * @author Li Rui <649037629@qq.com>
  CREATE TABLE `collect_designer` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `sex` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL COMMENT '0: ??, 1: ??',
  `design_type` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `special` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `address` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `description` TEXT COLLATE utf8_general_ci,
  `email` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `qq` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `company` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `tel` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `sj` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `url` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  `pic` VARCHAR(255) COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
  )ENGINE=InnoDB
  AUTO_INCREMENT=1 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
 */
$start_page = 1;
$end_page = 7200;

/**
 * 通过url采集设计师的相关信息
 * @param type $url
 * @return string
 */
for ($i = 1; $i <= $end_page; $i++) {
    $url = 'http://www2.' . $i . '.zxdyw.com';
    getCoutentFromUrl($url);
}

function getCoutentFromUrl($url) {
    global $pdo_instance;
    $result = array();
    $url_parse = parse_url($url);
    $avatar_path = realpath('./avatar');
    $works_path = realpath('./works');
    $url = 'http://' . $url_parse['host'] . '/lx.aspx';
    $url_works = 'http://' . $url_parse['host'] . '/zp.aspx';

    $content = curl_file_get_contents($url);
    $pattern_name = '/<span\s+id=\"SjshLeft_myName\">(.*?)<\/span>/is';
    $pattern_mySex = '/<span\s+id=\"SjshLeft_mySex">(.*?)<\/span>/is';
    $pattern_myYear = '/<span\s+id=\"SjshLeft_myYear">(.*?)<\/span>/is';
    $pattern_myFengGe = '/<span\s+id=\"SjshLeft_myFengGe">(.*?)<\/span>/is';
    $pattern_myTeChang = '/<span\s+id=\"SjshLeft_myTeChang">(.*?)<\/span>/is';
    $pattern_myCity = '/<span\s+id=\"SjshLeft_myCity">(.*?)<\/span>/is';
    $pattern_myJianJie = '/<span\s+id=\"SjshLeft_myJianJie">(.*?)<\/span>/is';
    $pattern_myCompany = '/<span\s+id=\"SjshLeft_myCompany">(.*?)<\/span>/is';
    $pattern_myTel = '/<span\s+id=\"SjshLeft_myTel">(.*?)<\/span>/is';
    $pattern_myMobil = '/<span\s+id=\"SjshLeft_myMobil">(\d+)<\/span>/is';
    $pattern_myMail = '/<span\s+id=\"SjshLeft_myMail">(.*?)<\/span>/is';
    $pattern_myQQ = '/<span\s+id=\"SjshLeft_myQQ">(.*?)<\/span>/is';
    $pattern_myQQ = '/<span\s+id=\"SjshLeft_myQQ">(.*?)<\/span>/is';
    preg_match_all($pattern_name, $content, $march_names);
    preg_match_all($pattern_mySex, $content, $march_sexs);
    preg_match_all($pattern_myYear, $content, $march_myYears);
    preg_match_all($pattern_myFengGe, $content, $march_myFengGe);
    preg_match_all($pattern_myTeChang, $content, $march_myTeChang);
    preg_match_all($pattern_myCity, $content, $march_myCity);
    preg_match_all($pattern_myJianJie, $content, $march_myJianJie);
    preg_match_all($pattern_myCompany, $content, $march_myCompany);
    preg_match_all($pattern_myTel, $content, $march_myTel);
    preg_match_all($pattern_myMobil, $content, $march_myMobil);
    preg_match_all($pattern_myMail, $content, $march_myMail);
    preg_match_all($pattern_myQQ, $content, $march_myQQ);
    $result['url'] = $url;
    $result['name'] = $march_names[1][0]; #姓名
    $result['sex'] = $march_sexs[1][0]; #性别
    $result['my_year'] = $march_myYears[1][0]; #从业年限
    $result['myFengGe'] = $march_myFengGe[1][0]; #设计风格
    $result['myTeChang'] = $march_myTeChang[1][0]; #设计特长
    $result['myCity'] = $march_myCity[1][0]; #所在城市
    $result['myJianJie'] = $march_myJianJie[1][0]; #个人简介
    $result['myCompany'] = $march_myCompany[1][0]; #所在公司
    $result['myTel'] = $march_myTel[1][0]; #固定电话
    $result['myMobil'] = $march_myMobil[1][0]; #手机
    $result['myMail'] = $march_myMail[1][0]; #E-mail
    $result['myQQ'] = $march_myQQ[1][0]; #QQ
    #保存头像
    $pattern_avatar = '/<img\s+id=\"SjshLeft_myPhoto\"\s+src="(.*?)"[^>]+\/?>/is';
    preg_match_all($pattern_avatar, $content, $march_avatars);
    $avatar_src = $march_avatars[1][0];

    if ($avatar_src != '/sjsh/img/zp.jpg') {#默认头像不采
        $pic = time() . substr(md5(uniqid(rand())), 0, 5);
        $pic = time() . $pic . '.jpg';
        file_put_contents($avatar_path . '/' . $pic, curl_file_get_contents('http://' . $url_parse['host'] . $avatar_src));
    }
    $result['pic'] = $pic; #PIC
    #插入数据
    $id = insertData($result);
    if (!$id) {
        return;
    }
    #插入作品
    $content = curl_file_get_contents($url_works);
    $pattern_list = "/<div\s+class=\"main\">\s*<ul>(.*?)<\/ul>/is";
    preg_match_all($pattern_list, $content, $lists);
    $lists = $lists[1][0];
    $pattern_name = "/<span>\s*(.*?)\s*<\/span>/is";
    $pattern_src = "/<img[^>*]src=\"(.*?)\"\s*alt=\"(.*?)\"/is";
    preg_match_all($pattern_src, $lists, $match_lists);
    foreach ($match_lists[1] as $k => $src) {
        $big_src = str_replace('S_', '', $src);
        $img_name = strrchr($src, '/');
        preg_match('/\/s\_(\d+\.\w+)/is', $img_name, $img_name);
        $img_name = $img_name[1];
        $name = $match_lists[2][$k];
        $code = substr(md5(uniqid(rand(), true)), 0, 6);
        $file_name = time() . $code . strrchr($img_name, '.');
        var_dump('http://' . $url_parse['host'] . $big_src);
        file_put_contents($works_path . '/' . $file_name, curl_file_get_contents('http://' . $url_parse['host'] . $big_src));
        $works_data = array(
            'collect_designer_id' => $id,
            'name' => $name,
            'pic' => $file_name,
        );
        $pdo_instance->insert('collect_designer_works', $works_data);
    }
}

/**
 * 插入设计师相关信息
 * @global type $pdo_instance
 * @param type $data
 */
function insertData($data) {
    global $pdo_instance;
    $insert_arr = array(
        'username' => $data['name'],
        'sex' => $data['sex'],
        'design_type' => $data['myFengGe'],
        'special' => $data['myTeChang'],
        'address' => $data['myCity'],
        'description' => $data['myJianJie'],
        'email' => $data['myMail'],
        'qq' => $data['myQQ'],
        'company' => $data['myCompany'],
        'company' => $data['myCompany'],
        'tel' => $data['myTel'],
        'sj' => $data['myMobil'],
        'url' => $data['url'],
        'pic' => $data['pic'],
    );
    #判断是否存在
    $exists_sql = "select count(*) as num from collect_designer where username='" . $data['name'] . "'";
    $exist = $pdo_instance->fetchOne($exists_sql);
    if ($exist['num'] > 0) {
        return;
    }
    $pdo_instance->insert('collect_designer', $insert_arr);
    echo '.';
    flush();
    return $pdo_instance->lastinsertid();
}

#采集作品
//getCoutentFromUrl($url);

