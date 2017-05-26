<?php

$url = parse_url($_SERVER['REQUEST_URI']);
$queryString = $url['query'];
parse_str($queryString, $params);
var_dump($queryString);
//echo http_build_query($params);
?>


