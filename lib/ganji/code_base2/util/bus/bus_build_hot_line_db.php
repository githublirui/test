<?php
    //Include Common Files
    require_once(dirname(dirname(__FILE__)) . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusDbConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/weather/class/WeatherConfig.class.php');
    require_once(APP_PATH . '/weather/class/WeatherUtils.class.php');
    
    set_time_limit(0);
    ini_set('memory_limit', -1);
    if (isset($_GET['type']))
    {
        $TYPE = trim($_GET['type']);
    }
    else
    {
    	$TYPE = null;
    }
    $dbObj = DBFactory::createDb(BusDbConfig::DEFAULT_DATABASE_NAME, DBConfig::MASTER, DBConfig::ENCODING_UTF8);
    try
    {
        $dbObj->connect();
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        exit();
    }
    foreach (BusConfig::$BUS_CITYS_MAP as $city => $item)
    {
    	if (!empty($TYPE) && $city != $TYPE)
    	{
    		continue;
    	}
    	try
    	{
    		$sql = "DROP TABLE IF EXISTS {$city}_hot_lines ";
    		$dbObj->execute($sql);
    		$sql = "CREATE TABLE `{$city}_hot_lines`"
    		     . "("
    		     . "`line_id` char(12) NOT NULL,"
    		     . "`count` int unsigned DEFAULT 0,"
			     . "PRIMARY KEY (`line_id`)"
			     . ") ENGINE=MyISAM DEFAULT CHARSET=utf8";
    		$dbObj->execute($sql);
    		$sql = "SELECT * FROM {$city}_lines";
    		$res = $dbObj->getAll($sql);
    		foreach ($res as $item)
    		{
    			$sql = "INSERT INTO {$city}_hot_lines (line_id) VALUES ({$item['line_id']})";
    			$dbObj->execute($sql);
    		}
    	}
    	catch (Exception $e)
    	{
    		echo $e->getMessage();
    		exit();
    	}
    }