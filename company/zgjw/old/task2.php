<?php
/**
 * @description:  
 * @author: li
 */
include("./My.class.php");
$link = DbManager::dbConnect();

$url = "http://www.58.com/hezu/changecity";
$content = @file_get_contents($url);
//'<a onclick="co('as')" href="http://as.58.com/hezu/">鞍山</a>';
//preg_match_all('/\<div\s+class\=[\"\']index\_bo[\'"]\>\n*\s*<dl\s+id=["\']clist[\'"]>.*<dd><a\s+[^>]+>(.*)<\/a>/is', $content, $match);
preg_match_all('/<div\s+class=\"index\_bo\">[\w\W]+<dl<\/div\>?/i', $content, $match);
var_dump($match);
die;
?>
s