<?php

/**
 * 
 * 采集教材数据
 */
function insert_materials($level) {
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
    $url = "http://www.17zuoye.com/student/learning/bookschip.api?level={$level}&subjectType=ENGLISH&_=1464315669766";
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
    $pattern = "/<strong\s+class\=\"wb-title\"\>(.*?)\<\/strong\>/is";
    preg_match_all($pattern, $ret, $matches);
    $copyrights = $matches[1];
    $pattern = "/<dl.*?data\-book\_id\=\s*\"(\d+)\"/is";
    preg_match_all($pattern, $ret, $matches);
    $course_ids = $matches[1];
    $pattern = "/\<p\>(.*?)\<\/p\>/is";
    preg_match_all($pattern, $ret, $matches);
    $course_names = $matches[1];
    foreach ($course_ids as $_i => $course_id) {
        $insert_data = array(
            'grade' => $level,
            'name' => $course_names[$_i],
            'copyright' => $copyrights[$_i],
            'type' => 1,
            'yiqi_id' => $course_id,
            'created' => time(),
        );
        $id = DBMysqlNamespace::insert($GLOBALS['db_handle'], 'b2s_teach_materials', $insert_data);
        echo ".";
    }
}

for ($i = 1; $i <= 6; $i++) {
    insert_materials($i);
}