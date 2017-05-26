<?php

//define('URL','http://mobtestweb6.ganji.com/datashare/control/common/CommonGetUserPostList');//web6
//define('URL','http://mobds.ganji.cn/datashare/');//线上
//define('URL','http://mobds.ganjistatic3.com/datashare/');//test1
//define('URL','http://mobds.ganji.com:1804/datashare/');//开发
define('URL', 'http://mobds.ganji.com:1806/datashare/control/common/CommonGetUserPostList'); //开发

$header = array(
    'customerId:705',
    'clientAgent:sdk#320*480',
    "GjData-Version:1.0",
    'versionId:5.2.0',
    'model:Generic/AnyPhone',
    'agency:agencydefaultid',
    'contentformat:json2',
    'userId:1615BBAAF41ABDC59CFA7EBE8C643919',
    'token:712b587555786c63524b316b61433646712b484d726f516d',
    'mac:787987779',
    'interface:CommonGetUserPostList', //验
);
$post_fields = array(
    //'type'=>1,
    //'loginId'=>50277998,
    //'postType'=>0,
    //'pageIndex'=>1,
    //'pageSize'=>10,
    //'cityId' => 46,

    'type' => 1, //工作台类型  0：表示其他 1:表示招聘帮帮，2:二手辆帮帮，3:服务店铺帮帮
    'loginId' => 50000098,
    'postType' => 0, //帖子列表类型：0：表示全部 1：帮帮推广中，2：置顶中，3：未推广 4：审核中 5：删除
    'pageIndex' => 0,
    'pageSize' => 100,
    'cityId' => 12,
);

$ch = curl_init();
$url = URL;
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_VERBOSE, 0);
$result = curl_exec($ch);
curl_close($ch);
echo'<pre>';
$resultPosts = json_decode($result, true);
if (!$resultPosts) {
    print_r($result);
} else {
    print_r($resultPosts);
}

echo'</pre>';
