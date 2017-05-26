<?php
    /**
     * 公交数据查询线路结果页
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusSearchDb.class.php');
    
    $tplVars = array();
    //Step 1: Get the City Infomation
    $city      = BusUtils::getCityByDomain();
    $hotLineDb = new BusSearchDb($city, DBConfig::MASTER);
    $hotLineDb->setHotLine($_GET['line']);
    unset($hotLineDb);
    //Step 2: Check the Service
    if (BusConfig::$BUS_CITYS_MAP[$city]['onService'] === false)
    {
        $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_UPDATE, false);
        $tpl->display($tplVars);
        exit();
    }
    //Step 3: Check the Param
    if (!isset($_GET['line']) || empty($_GET['line']))
    {
        HttpHandler::displayPageNotFound();
    }
    //Step 4: Get the City Infomation
    $tplVars['city']     = $city;
    $tplVars['cityName'] = array_shift(BusUtils::getCityRelate($city, false));
    //Step 5: Try to Get the Assort Key of Line or Station From Cache
    $cache             = new CacheMemcache;    
    $cacheKeyAttribute = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_LINE]),
            BusSearchConfig::CACHE_ATTRIBUTE_INFIX,
            $_GET['line'],
        )
    );
    $cacheKeyStations  = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_LINE]),
            BusSearchConfig::CACHE_STATIONS_INFIX,
            $_GET['line'],
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
    //Step 6: Get the Data From DataBase
    $dbObj = new BusSearchDb($city);
    if ($lineAttribute === false)
    {
        $lineAttribute = $dbObj->getLineAttributeByLineId($_GET['line']);
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
        $lineStations = $dbObj->getLineStationsByLineId($_GET['line']);
        if ($lineStations !== false)
        {
            $cache->write($cacheKeyStations, $lineStations, BusSearchConfig::CACHE_STATIONS_EXPIRE);
        }
        else
        {
            HttpHandler::displayPageNotFound();
        }
    }
    $tplVars['switch']        = true;
    $tplVars['input']         = '';
	$tplVars['tabItem']       = BusSearchConfig::SEARCH_TYPE_LINE + 1;
    $tplVars['lineAttribute'] = $lineAttribute;
    $tplVars['lineStations']  = $lineStations;
    $tplVars['rightLinks']    = BusSearchConfig::$RIGHT_LINKS;
    $tplVars['map']           = true;
	$tplVars['webtrendsSourceName'] = 'ganji';
    $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_LINE, false);
    $tpl->display($tplVars);