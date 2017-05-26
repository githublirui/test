<?php

echo urldecode('%7B%22majorCategoryScriptIndex%22%3A%221%22%2C%22customerId%22%3A%22870%22%2C%22categoryId%22%3A%227%22%2C%22pageSize%22%3A%2210%22%2C%22cityScriptIndex%22%3A%220%22%2C%22sortKeywords%22%3A%5B%7B%22field%22%3A%22post_at%22%2C%22sort%22%3A%22desc%22%7D%5D%2C%22queryFilters%22%3A%5B%5D%2C%22pageIndex%22%3A%220%22%7D&showType=0');die;
$str = 'gjfstmp2/M00/00/02/wKgCzFQhISiIcGJtAAFGUhd01KQAAAAyALmnkIAAUZq882_90-75c_6-0.jpg';

$pattern = '/.+\_(\d+)\-(\d+)(c)?\_(\d*)\-(\d*)[.](\w+)/';
preg_match_all($pattern, $str, $matches);
var_dump($matches);
?>
