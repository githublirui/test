<?php

$con = '{"pageSize":"100","queryFilters":[{"operator":"=","name":"district_id","value":"10"},
{"operator":"=","name":"strval2","value":"10"},{"operator":"=","name":"agent","value":"0"},
{"operator":"=","name":"deal_type","value":"0"},{"value":"diandongche","name":"base_tag","operator":"="}],
"customerId":"705","categoryId":"14","cityScriptIndex":"901","sortKeywords":{"sort":"desc","field":"post_at"}}';
$s = json_decode($con, true);

$s = array_search('base_tag', array('base_tag'));
var_dump($s);
?>
