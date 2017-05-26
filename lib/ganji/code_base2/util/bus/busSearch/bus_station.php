<?php
    /**
     * 公交数据查询站点结果页
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */

    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/housing/premier/apps/bootstrap.php');
    require_once(APP_PATH . '/housing/api/GeoHelper.class.php');
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    require_once(SITE_PATH . '/framework/caching/CacheFile.class.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusSearchDb.class.php');
    
    //Step 1: Check the Param
    $tplVars = array();
    $city    = BusUtils::getCityByDomain();
    if (BusConfig::$BUS_CITYS_MAP[$city]['onService'] === false)
    {
        $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_UPDATE, false);
        $tpl->display($tplVars);
        exit();
    }
    if (!isset($_GET['line']) || empty($_GET['line']) || !isset($_GET['station']) || empty($_GET['station']))
    {
        HttpHandler::displayPageNotFound();
    }
    //Step 2: Get the City Infomation
    $tplVars['city']     = $city;
    $tplVars['cityName'] = array_shift(BusUtils::getCityRelate($city, false));
    //Step 3: Try to Get the Assort Key of Line or Station From Cache
    $cacheFile = new CacheFile;
    $cacheFile->setDirectory(BusSearchConfig::CACHE_STATIONS_PATH);
    $cacheKeyStation = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_STATION]),
            $_GET['line'],
            $_GET['station'],
        )
    );
    if (($html = @$cacheFile->read($cacheKeyStation)) !== false)
    {
        echo $html;
        exit;
    }
    $cache = new CacheMemcache;
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $station = false;
    }
    else
    {
        $station = $cache->read($cacheKeyStation);
    }
    //Step 4: Get the Data From DataBase
    $dbObj = new BusSearchDb($city);
    if ($station === false)
    {
        $station = $dbObj->getStation($_GET['line'], $_GET['station']);
        if ($station !== false)
        {
            $cache->write($cacheKeyStation, $station, BusSearchConfig::CACHE_STATIONS_EXPIRE);
        }
        else
        {
            HttpHandler::displayPageNotFound();
        }
    }
    $tplVars['station'] = $station[0];
    $tplVars['houses']  = $station[2];
    //Step 5: Get the Line Data
    $attributes = array();
    $stations   = array();
    $duplicate  = array();
    foreach ($station[1] as $lineId)
    {
        $cacheKeyAttribute = implode(
            BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
            array(
                BusConfig::DEFAULT_CACHE_PREFIX,
                strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_LINE]),
                BusSearchConfig::CACHE_ATTRIBUTE_INFIX,
                $lineId,
            )
        );
        $cacheKeyStations  = implode(
            BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
            array(
                BusConfig::DEFAULT_CACHE_PREFIX,
                strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_LINE]),
                BusSearchConfig::CACHE_STATIONS_INFIX,
                $lineId,
            )
        );
        if (BusSearchConfig::SEARCH_DEBUG)
        {
            $lineAttribute = false;
            $lineStations  = false;
        }
        else
        {
            $lineAttribute = $cache->read($cacheKeyAttribute);
            $lineStations  = $cache->read($cacheKeyStations);
        }
        if ($lineAttribute === false)
        {
            $lineAttribute = $dbObj->getLineAttributeByLineId($lineId);
            if ($lineAttribute !== false)
            {
                $cache->write($cacheKeyAttribute, $lineAttribute, BusSearchConfig::CACHE_ATTRIBUTE_EXPIRE);
            }
            else
            {
                HttpHandler::displayPageNotFound();
            }
        }
        if ($lineStations === false)
        {
            $lineStations = $dbObj->getLineStationsByLineId($lineId);
            if ($lineStations !== false)
            {
                $cache->write($cacheKeyStations, $lineStations, BusSearchConfig::CACHE_STATIONS_EXPIRE);
            }
            else
            {
                HttpHandler::displayPageNotFound();
            }
        }
        if (array_search($lineAttribute['line_name'], $duplicate) === false)
        {
            $attributes[] = $lineAttribute;
            $stations[]   = $lineStations;
            $duplicate[]  = $lineAttribute['line_name'];
        }
    }
    $tplVars['switch']     = true;
    $tplVars['input']      = '';
	$tplVars['tabItem']    = BusSearchConfig::SEARCH_TYPE_STATION + 1;
    $tplVars['attributes'] = $attributes;
    $tplVars['stations']   = $stations;
    $tplVars['rightLinks'] = BusSearchConfig::$RIGHT_LINKS;
	$tplVars['webtrendsSourceName'] = 'ganji';
    $tpl  = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_STATION, false);
    $html = $tpl->fetch($tplVars);
    @$cacheFile->write($cacheKeyStation, $html, BusSearchConfig::CACHE_STATIONS_EXPIRE);
    echo $html;
