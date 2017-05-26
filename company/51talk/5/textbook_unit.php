<?php

/**
 * 
 * 采集教材单元数据
 */
function insert_materials_unit($bookId,  $mid) {
//username and password of account
    $username = '18911228229';
    $password = '123456';

//set the directory for the cookie using defined document root var
    $dir = $GLOBALS['now_path'] . "/ctemp";
//build a unique path with every request to store 
//the info per user with custom func. 
//$path = build_unique_path($dir);
    $path = $dir;

//login form action url
    $url = "http://www.17zuoye.com/student/book/units.api?bookId={$bookId}&subjectType=ENGLISH&_=1464317706172";
//$postinfo = "_a_loginForm=立即登录&j_username=" . $username . "&j_password=" . $password;

    $cookie_file_path = $path . "/cookie.txt";


    $header[] = "Content-Type:application/x-www-form-urlencoded";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);

//set the cookie the site has for certain features, this is optional
    curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_POST, 0);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
    $ret = curl_exec($ch);
    $header = curl_getinfo($ch);
    $ret = json_decode($ret, true);
    $units = $ret['units'];
    foreach ($units as $_i => $unit) {
        $insert_data = array(
            'm_id' => $mid,
            'cname' => $unit['cname'],
            'ename' => $unit['ename'],
            'group_cname' => $unit['groupCname'],
            'group_ename' => $unit['groupEname'],
            'created' => time(),
        );
        $id = DBMysqlNamespace::insert($GLOBALS['db_handle'], 'b2s_teach_materials_unit', $insert_data);
        echo ".";
    }
}

$courses = DBMysqlNamespace::fetch_all($GLOBALS['db_handle'], 'b2s_teach_materials');

foreach ($courses as $course) {
    insert_materials_unit($course['yiqi_id'],$course['id']);
}