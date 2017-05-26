<?php

$dir = 'E:\projects\test\shanghai';

$list = scandir($dir); // 得到该文件下的所有文件和文件夹
foreach ($list as $file) {//遍历
    $file_location = $dir . "/" . $file; //生成路径
    if (!is_dir($file_location) && $file != "." && $file != "..") { //判断是不是文件夹
        $url = 'shanghai/' . $file;
        run($url);
    }
}

function run($url) {
//数据库配置
    $db_server = 'localhost';
    $db_user = 'root';
    $db_psw = 'JDLUREN124711';
    $db_name = 'chhome';

//$url = 'http://ditu.o.cn/poi/shanghai-xiaoqu/more';
//$url = './task4_html.php';
    $content = file_get_contents($url);

//$pattern = "/<dl\s+class=\"dl-hor\s+bc-href\">\n*\s*<dt>\w.+[\s\n]*<dd>[\s\n]*<ul[^>]*>([\n\s]*<li>\s*<a[^>]*>(.+)<\/a><\/li>[\n\s]*)+[\n\s]*<\/ul>/i";
    $pattern = "/<dl\s+class=\"dl-hor\s+bc-href\">\n*\s*<dt>\w.+[\s\n]*<dd>[\s\n]*<ul[^>]*>([\n\s]*<li>\s*<a[^>]*>(.+)<\/a><\/li>[\n\s]*)+[\n\s]*<\/ul>/i";
//$pattern = "/[\n\s]*<li>\s*<a[^>]*>(.+)<\/a><\/li>[\n\s]*/i";
    preg_match_all($pattern, $content, $matches);

    $link = mysql_connect($db_server, $db_user, $db_psw) or die('connect error');
    mysql_select_db($db_name, $link) or die('select db error');
    mysql_query("SET NAMES UTF8");
    set_time_limit(0);

    $areas = $matches[0];
    foreach ($areas as $area) {
        $area_pattern = "/<dt>(\w+)<i><\/i><\/dt>[\w\W]*<a[^>]*>(.*)<\/a>/is";
        preg_match_all($area_pattern, $area, $area_matches);
        $area_str = $area_matches[0][0];
        $area_group = $area_matches[1][0];

        $area_name_pattern = "/<a[^>]*>(.*)<\/a>/i";
        preg_match_all($area_name_pattern, $area_str, $area_name_matches);
        $area_names = $area_name_matches[1];

        foreach ($area_names as $area_name) {
            $sql = 'replace into area(name,e_group) 
            values("' . $area_name . '","' . $area_group . '");';
            mysql_query($sql);
            echo '.';
            flush();
        }
    }
}

?>
