<?php

require_once dirname(__FILE__) . '/config.inc.php';
require_once dirname(__FILE__) . '/lib/MyPDO.class.php';
@session_start();
$link = mysql_connect($dbhost, $dbuser, $dbpw) or die('connect error');
mysql_select_db($dbname, $link) or die('select db error');
mysql_query("SET NAMES " . $dbcharset);
set_time_limit(0);