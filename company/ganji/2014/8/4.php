<?php

$r = '{"customerId":"705" , ,  ,  , "cityScriptIndex":"407","categoryId":"3","majorCategoryScriptIndex":"-2"                   ,"pageSize":"100","queryFilters":[,{"name":"district_id","value":"0","operator":"="},{"name":"tag_info","value":"6","operator":"="},],"andKeywords":[,{"name":"title","value":""}],"sortKeywords":["{"field":"post_at","sort":"desc"}"]}';
//$a = '{{"customerId":"705","cityScriptIndex":"601","categoryId":"2","majorCategoryScriptIndex":"-2"                   ,"pageSize":"100","queryFilters":[{"name":"district_id","value":"4","operator":"="},,{"name":"tag_info","value":"4","operator":"="},],"andKeywords":[{"name":"title","value":""}],"sortKeywords":[{"field":"post_at","sort":"desc"}]}';
//$r = '{"customerId":"705","cityScriptIndex":"0","categoryId":"7","majorCategoryScriptIndex":"1"                   ,"pageSize":"100","queryFilters":[{"name":"street_id","operator":"=","value":"9"},{"name":"district_id","operator":"=","value":"1"},{"name":"huxing_shi","operator":"=","value":"1"},{"name":"agent","operator":"=","value":"0"}],"andKeywords":["{"name":"title","value":"望京花园"}"],"sortKeywords":[{"field":"post_at","sort":"desc"}]}';
$r = '{"customerId":"705","cityScriptIndex":"905","categoryId":"7","majorCategoryScriptIndex":"1" ,"pageSize":"100","queryFilters":[{"name":"district_id","value":"0","operator":"="},{"name":"street_id","value":"3","operator":"="},{"name":"huxing_shi","value":"2","operator":"="},{"name":"agent","value":"0","operator":"="},,],"andKeywords":[{"name":"title","value":""}],"sortKeywords":[{"field":"post_at","sort":"desc"}]}';

$patterns = array('/(\s*,\s*){2,10}/', '/\}\s*,\s*\]/', '/\[\s*,\s*\{/is', '/\[\s*\"\s*\{/is', '/\}\s*\"\s*\]/is');
$replace = array(',', '}]', '[{', '[{', '}]');
$r = preg_replace($patterns, $replace, $r);
var_dump(json_decode($r, true));
die;
echo $r;
die;
$a = 0;
var_dump(json_encode((object) array()));
die;
var_dump(is_numeric($a));

echo time();
echo '<br/>';
echo strtotime('2014-08-19 20:10');
die;
//不足补0
$number = 3;
$txt = sprintf("%02d", $number);
var_dump($txt);
die;
$postDate = explode(" ", '2013-11-14 15:00:00');
$job = $argv;
var_dump($postDate);
die;
$json = array('mood' => array('score' => 9, 'desc' => '说明'));
var_dump(json_encode($json));
?>