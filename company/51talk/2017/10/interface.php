<?php 
require 'conf.php';
require 'tis.php';

$api = new TisApi($accessId,$accessKey);
$method = $_REQUEST['method'];

$rst = $api->$method($_REQUEST,$tisId);
echo json_encode($rst);
?>