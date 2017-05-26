<?php
    /**
     * 公交站点坐标解密脚本
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
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
    $cities = array(
    	'cq', 'cd', 'cs', 'cc', 'dl', 'dg', 'fz', 'gz',
    	'gl', 'gy', 'hz', 'hrb', 'hf', 'hn', 'nmg', 'jn',
    	'km', 'lz', 'xz', 'nj', 'nc', 'nn', 'nb', 'qd',
    	'sz', 'su', 'sy', 'sjz', 'tj', 'ty', 'wh', 'xj',
    	'wx', 'wei', 'xm', 'xa', 'xn', 'yc', 'zz',
    );
    foreach (BusConfig::$BUS_CITYS_MAP as $city => $item)
    {
        if (array_search($city, $cities) === false)
        {
            continue;
        }
        echo "CITY=$city\r\n";
        $stations = $city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX;
        $count    = 0;
        try
        {
            $res = $dbObj->getAll(SqlBuilder::buildSelectSql($stations, BusDbConfig::DEFAULT_COLUMNS));
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "\r\n";
            continue;
        }
        echo "TOTAL=" . count($res) . "\r\n";
        foreach ($res as $station)
        {
            $data   = BusUtils::decodeMapABCCoordinate(array($station['latY'], $station['lngX']));
            $latlng = implode(',', $data);
            echo "STATION_ID=" . $station['line_id'] . '_' . $station['station_num'] .  " STATION_NAME=" . $station['station_name'] . " latY=" . $station['latY'] . " lngX=" . $station['lngX'] . " latlng=" . $latlng;
            try
            {
                $dbObj->execute(
                    SqlBuilder::buildUpdateSql(
                        $stations,
                        array(
                            'latlng' => $latlng,
                            'lat'    => $data[0],
                            'lng'    => $data[1],
                        ),
                        array(
                            array('line_id',     SqlBuilder::FILTER_EQUAL, $station['line_id']),
                            array('station_num', SqlBuilder::FILTER_EQUAL, $station['station_num']),
                        )
                    )
                );
                $count++;
                echo " UPDATE SUCCESS\r\n";
            }
            catch (Exception $e)
            {
                echo "UPDATE FAILED " . $e->getMessage() . "\r\n";
            }
        }
        echo "CITY=$city COUNT=$count\r\n";
    }