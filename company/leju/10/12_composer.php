<?php

$param = array(
    'host' => 'localhost',
);
$dr = new \Doctrine\DBAL\Driver();

$conn = new Doctrine\DBAL\Connection($param, $dr);
var_dump($conn);
die;
?>
