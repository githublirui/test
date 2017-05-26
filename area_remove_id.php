<?php
set_time_limit(0);
$content = file_get_contents("6_e.sql");
$content = preg_replace('/\(\d+,/i','(NULL,',$content);
file_put_contents("6_e.sql",$content);