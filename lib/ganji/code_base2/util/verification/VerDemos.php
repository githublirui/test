<?php
include_once 'VerificationNamespace.class.php';

$array = array('air_displacement'=>'111');

VerificationNamespace::_init(6,14);

$res = VerificationNamespace::Check($array);

print_r($res);

?>
