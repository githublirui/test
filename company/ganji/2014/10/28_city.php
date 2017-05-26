<?php

include_once dirname(__FILE__) . '/../../lib/common.php';
//易淘食
$citys = '北京市,深圳市, 成都市,榆林市,天津市,襄阳市,长春市,吉林市,唐山市,石家庄市,南京市,洛阳市,南宁市,广州市,上海市,福州市,常州市,长沙市,重庆市,贵阳市,大连市,柳州市,杭州市,通化市';
$citys = explode(',', $citys);
$citys = array_map('tomysqlstr', $citys);

//echo implode(',', $citys);

function tomysqlstr($v) {
    return "'" . trim($v) . "'";
}

$cityIds = '13       12       65       142      85       2        56       14       69       16       45       26       133      183      104      17       1        75       196      36       88       15       84       204';
$cityIds = preg_split("/\s+/is", $cityIds);
echo 'array(' . implode(',', $cityIds) . ')';
//贷款
die;
$fhandle = fopen('cate.txt', 'r');
$baseModel = new BaseModel();
$handle = $baseModel->getDbHandler('lrtest');
while (!feof($fhandle)) {
    $line = fgets($fhandle);
    $arr = explode('	', $line);
    $cityName = trim($arr[0]);
    $cityDomain = trim($arr[1]);
    $filters = array(
        array('domain', '=', $cityDomain),
    );
    $sql = SqlBuilderNamespace::buildSelectSql('city', array('city_id'), $filters);
    $cityId = DBMysqlNamespace::getOne($handle, $sql);
    if ($cityId) {
        file_put_contents('citys.txt', $cityId . ',', FILE_APPEND);
    } else {
        file_put_contents('citys1.txt', $cityId . "=>'" . $cityDomain . "'\n", FILE_APPEND);
    }
}
?>
