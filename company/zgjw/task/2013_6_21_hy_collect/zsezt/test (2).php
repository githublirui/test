<?php
#装修E站通
$content = file_get_contents('tmp.html');

$pattern_al ='/<h5>.*<a[^>]*href="(.*)"[^>]*>.*<\/a>.*<\/h5>/is';
$pattern_al ='/<h5>\s*<a[^>]*href="(.+?)"[^>]*>/is';
preg_match_all($pattern_al, $content, $match);

var_dump($match);
die;
?>