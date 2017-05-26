<?php
$job = $GLOBALS['argv'][1];
file_put_contents('cron.txt',$job.'  '.date('Y-m-d H:i:s')." \n",FILE_APPEND);