<?php
    //Include Common Files
    require_once(dirname(dirname(__FILE__)) . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusDbConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    
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
        echo "CITY=$city\r\n";
        $attrs    = $city . BusDbConfig::DEFAULT_TABLE_ATTRIBUTE_SUFFIX;
        $lines    = $city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX;
        $stations = $city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX;
        $unique   = array();
        $count    = 0;
        try
        {
            $res = $dbObj->getAll(SqlBuilder::buildSelectSql($lines, BusDbConfig::DEFAULT_COLUMNS));
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "\r\n";
            continue;
        }
        echo "TOTAL=" . count($res) . "\r\n";
        foreach ($res as $line)
        {
            $short = BusUtils::filterLineName($line['line_name']);
            echo "LINE_ID=" . $line['line_id'] . " LINE_NAME=" . $line['line_name'] . " SHORT_NAME=" . $short . " ";
            if (array_search($short, $unique) !== false)
            {
                echo "DUPLICATE ";
                try
                {
                    $dbObj->execute(SqlBuilder::buildDeleteSql($lines,    array(array('line_id', SqlBuilder::FILTER_EQUAL, $line['line_id']))));
                    $dbObj->execute(SqlBuilder::buildDeleteSql($attrs,    array(array('line_id', SqlBuilder::FILTER_EQUAL, $line['line_id']))));
                    $dbObj->execute(SqlBuilder::buildDeleteSql($stations, array(array('line_id', SqlBuilder::FILTER_EQUAL, $line['line_id']))));
                    echo "DELETE SUCCESS\r\n";
                    $count++;
                }
                catch (Exception $e)
                {
                    echo "DELETE FAILED " . $e->getMessage() . "\r\n";
                }
            }
            else
            {
                $unique[] = $short;
                echo "NEW\r\n";
            }
        }
        echo "CITY=$city COUNT=$count\r\n";
    }
    