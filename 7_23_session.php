<?php

#session 保存时间测试

session_start();
$session_id = session_id();

setcookie(session_name(), $session_id, time() + 3600);
//$_SESSION['fff'] = '1231';
var_dump($_SESSION);
die;