<?php
    //Include Common Files
    require_once(dirname(dirname(__FILE__)) . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    require_once(SITE_PATH . '/libs/phpExcel/PHPExcel.php');
    require_once(SITE_PATH . '/libs/phpExcel/PHPExcel/IOFactory.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusDbConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/weather/class/WeatherConfig.class.php');
    require_once(APP_PATH . '/weather/class/WeatherUtils.class.php');
    
    set_time_limit(0);
    ini_set('memory_limit', -1);
    if ($argc == 2)
    {
        $TYPE = trim($argv[1]);
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
    $data = array();
    foreach (BusConfig::$BUS_CITYS_MAP as $city => $item)
    {
    	if (!empty($TYPE) && $city != $TYPE)
    	{
    		continue;
    	}
        $cityname = WeatherUtils::getCityChineseName($city);
        $stations = $city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX;
        try
        {
            $res = $dbObj->getAll("SELECT line_id, station_num, station_name , COUNT(*) AS `count` FROM {$stations} GROUP BY station_name ORDER BY `count` DESC LIMIT 30");
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            exit();
        }
        $data[$city] = $res;
    }
    foreach ($data as $key => $val)
    {
    	$array = "";
    	$count = count($val);
    	for ($i = 15;$i < $count;$i++)
    	{
    		$array .= "\t\tarray('line_id'=>'{$val[$i]['line_id']}','station_num'=>'{$val[$i]['station_num']}','station_name'=>'{$val[$i]['station_name']}','count'=>{$val[$i]['count']}),\n";
    	}
    	if ($count < 30)
    	{
    		$count = ($count > 15) ? 30 - $count : $count;
    		for ($i = 0;$i < $count;$i++)
    		{
    			$array .= "\t\tarray('line_id'=>'{$val[$i]['line_id']}','station_num'=>'{$val[$i]['station_num']}','station_name'=>'{$val[$i]['station_name']}','count'=>{$val[$i]['count']}),\n";
    		}
    	}
    	$content = "<?PHP\n"
		         . "\t//TimeStamp:" . date('Y-m-d H:i:s', time()) . "\n"
	             . "\t\$busHotStations=array(\n{$array}"
	             . "\t);";
    	if (file_exists("./data/{$key}/") === false)
        {
            mkdir("./data/{$key}/", 0777, true);
        }
    	if (file_exists("./data/{$key}/bus_hot_stations.php"))
        {
        	system("mv -f ./data/{$key}/bus_hot_stations.php ./data/{$key}/bus_hot_stations.php.bak");
        }
		file_put_contents("./data/{$key}/bus_hot_stations.php", $content);
    }