<?php

$a = array('d');
array_walk($a,function($value,$key) use (&$a) {unset($a[$key]);$a[$key+10] = $value;});
var_dump($a);