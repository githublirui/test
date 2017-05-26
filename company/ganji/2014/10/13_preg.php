<?php

$str = file_get_contents('html.txt');
$pattern = '/(\<\s*meta.*?content\=[\'\"]\s*text\/html.*?charset=)(.*?)(\s*[\'\"][^>]*\>)/is';
$matches = preg_replace($pattern, "$1big5$3", $str);
//preg_match_all($pattern, $str, $matches);
var_dump($matches);
die;
?>
