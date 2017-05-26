<?php

$a = null;
var_dump(empty($a));

die;
list($usec, $sec) = explode(" ", microtime());

var_dump($usec);
var_dump((int) ($usec * 1000000));
var_dump($sec);
$s = $sec * 1000000 + (int) ($usec * 1000000);
?>
