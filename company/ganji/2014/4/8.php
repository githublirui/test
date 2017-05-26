<?php

$description = 'asdfsdfsdfsdfopnsognsonfn\n\n\n\n\n\n\n\n1231
\n
sdf
sdfsdfasdf
\n\n\n
sfsadfasdf
ads
fasdfasdfasdfsa<div><img src="http://image.ganjistatic1.com/gjfstmp2/M00/00/00/wKgCzFKz6UGIXYHsAAELr1Bl0a8AAAASAAW8JoAAQvH121_0-0_9-0.jpg" /></div>
sfsdf
sadfas
';
$description = formatDescription($description);
$description = preg_replace("/\n{2,}/i", "\n", $description);
var_dump($description);
die;

function formatDescription($description, $length = 32767) {
    //过滤html标签
    $description = strip_tags($description, '<img>');
    $description = str_replace(array('&nbsp;', '&gt;', '&lt;'), array('', '>', '<'), $description);

    //客户端显示分段
    $crlf = array("\\r\\n", "\r\n", "\\r", "\r", "\\n");    //需要替换的特殊字符
    $description = str_replace($crlf, "\n", $description);
    //新的搜索列表
//    $description = sysSubStr($description, $length, true); //String::substr_utf8($description, 50);
    return $description;
}

function sysSubStr($String, $Length, $Append = false) {
    $StringLast = array();
    if (strlen($String) <= $Length) {
        return $String;
    } else {
        $I = 0;
        while ($I < $Length) {
            $StringTMP = substr($String, $I, 1);
            if (ord($StringTMP) >= 224) {
                $StringTMP = substr($String, $I, 3);
                $I = $I + 3;
            } elseif (ord($StringTMP) >= 192) {
                $StringTMP = substr($String, $I, 2);
                $I = $I + 2;
            } else {
                $I = $I + 1;
            }
            $StringLast[] = $StringTMP;
        }
        $StringLast = implode("", $StringLast);
        if ($Append) {
            $StringLast .= "...";
        }
        return $StringLast;
    }
}

$a = '';
$s = json_decode($a, true);
var_dump($s);
die;
$s = '{"search_condition":{"categoryId":"6","majorCategoryScriptIndex": "1","queryFilters": [{"value": "1","operator": "=","name": "price"}]}}';
$s = json_decode($s, true);

$e = array_key_exists('search_condition', $s);

//var_dump($s);
//phpinfo();

$tasklist = "C:/Windows/System32/tasklist.exe";
//@exec($tasklist, $plist);
//foreach ($plist as $p) {
//    print_r($plist);
//}
//for ($i = 0; $i <= 20000000; $i++) {
//    echo ".";
//    flush();
//    sleep(1);
//}
echo $GLOBALS ['argv'] [1];
?>
