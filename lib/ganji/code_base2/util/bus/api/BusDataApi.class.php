<?php
    /**
     * 公交数据API接口
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusDbConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');

    function get_bus_detail($city, $bus_id)
    {
        $data = array(
            'link'     => '',
            'stations' => array(),
        );
        if (array_key_exists($city, BusConfig::$BUS_CITYS_MAP) === false)
        {
            return $data;
        }
        $prefix = BusConfig::$BUS_CITYS_MAP[$city]['prefix'];
        $lineId = $prefix . BusUtils::stringToLineId($bus_id);
        $dbObj  = DBFactory::createDb(BusDbConfig::DEFAULT_DATABASE_NAME, DBConfig::SLAVE, DBConfig::ENCODING_UTF8);
        try
        {
            $dbObj->connect();
        }
        catch (Exception $e)
        {
            return $data;
        }
        try
        {
            $res = $dbObj->getAll(
                SqlBuilder::buildSelectSql(
                    $city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                    BusDbConfig::DEFAULT_COLUMNS,
                    array(array('line_id', SqlBuilder::FILTER_EQUAL, $lineId)),
                    array(),
                    array('station_num' => SqlBuilder::SORT_ASC)
                )
            );
        }
        catch (Exception $e)
        {
            return $data;
        }
        $data['link'] = "http://$city.ganji.com/bus/line/$lineId.html";
        foreach ($res as $station)
        {
            $data['stations'][$bus_id . 'z' . BusUtils::numToString($station['station_num'])] = $station['station_name'];
        }
        return $data;
    }