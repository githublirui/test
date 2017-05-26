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
    $dbObj = DBFactory::createDb(BusDbConfig::DEFAULT_DATABASE_NAME, DBConfig::MASTER, DBConfig::ENCODING_UTF8);
    $exObj = new PHPExcel();
    $exObj->getProperties()
          ->setCreator("易潇")
          ->setLastModifiedBy("易潇")
          ->setTitle("公交站点数据收集")
          ->setSubject("公交站点数据收集")
          ->setDescription("公交站点数据收集")
          ->setKeywords("公交站点数据收集")
          ->setCategory("公交站点数据收集");
    try
    {
        $dbObj->connect();
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
        exit();
    }
    $sheet = 0;
    foreach (BusConfig::$BUS_CITYS_MAP as $city => $item)
    {
        $cityname = WeatherUtils::getCityChineseName($city);
        $linetb   = $city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX;
        $stations = $city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX;
        if ($sheet)
        {
            $exObj->createSheet($sheet);
        }
        $exObj->setActiveSheetIndex($sheet);
        $exObj->getActiveSheet()->setTitle($cityname);
        $exObj->getActiveSheet()
              ->setCellValue('A1', '公交站名')
              ->setCellValue('B1', '途径公交线路数量')
              ->setCellValue('C1', '途径公交线路名称');
        $count = 2;
        try
        {
            $res = $dbObj->getAll("SELECT station_name , COUNT(*) AS `count` FROM {$stations} GROUP BY station_name ORDER BY `count` DESC");
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "\r\n";
            continue;
        }
        foreach ($res as $value)
        {
            $exObj->getActiveSheet()
                  ->setCellValue("A{$count}", $value['station_name'])
                  ->setCellValue("B{$count}", $value['count']);
            try
            {
                $req = $dbObj->getAll("SELECT line_id FROM {$stations} WHERE station_name = '" . $value['station_name'] . "'");
            }
            catch (Exception $e)
            {
                echo $e->getMessage() . "\r\n";
                continue;
            }
            $lines = '';
            foreach ($req as $line)
            {
                try
                {
                    $rep = $dbObj->getAll("SELECT line_name FROM {$linetb} WHERE line_id = '" . $line['line_id'] . "'");
                    if (strlen($lines))
                    {
                        $lines .= ', ' . $rep[0]['line_name'];
                    }
                    else
                    {
                        $lines .= $rep[0]['line_name'];
                    }
                }
                catch (Exception $e)
                {
                    echo $e->getMessage() . "\r\n";
                    continue;
                }
            }
            $exObj->getActiveSheet()
                  ->setCellValue("C{$count}", $lines);
            $count++;
        }
        $sheet++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($exObj, 'Excel2007');
    $objWriter->save('bus_stations.xlsx');