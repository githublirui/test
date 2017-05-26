<?php
    /**
     * 公交数据查询首页
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
    require_once(APP_PATH  . '/common/LinkUtil.class.php');
    require_once(APP_PATH  . '/help/includes/HelpHelper.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusAssortKeyDb.class.php');

    if (!isset(BusConfig::$BUS_CITYS_MAP[HttpHandler::getDomain()]))
    {
        HttpHandler::redirectPermently('http://' . HttpHandler::getDomain() . ".ganji.com/");
    }
    $tplVars = array();
    //Step 1: Get the City Infomation
    $city = BusUtils::getCityByDomain();
    //Step 2: Check the Service
    if (BusConfig::$BUS_CITYS_MAP[$city]['onService'] === false)
    {
        $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_UPDATE, false);
        $tpl->display($tplVars);
        exit();
    }
    if (file_exists(APP_PATH . "/bus/data/{$city}/bus_hot_stations.php"))
    {
    	require_once(APP_PATH . "/bus/data/{$city}/bus_hot_stations.php");
    	$tplVars['busHotStations'] = $busHotStations;
    	unset($busHotStations);
    }
    else
    {
    	$tplVars['busHotStations'] = array();
    }
    if (file_exists(APP_PATH . "/bus/data/{$city}/bus_hot_lines.php"))
    {
    	require_once(APP_PATH . "/bus/data/{$city}/bus_hot_lines.php");
    	$tplVars['busHotLines'] = $busHotLines;
    	unset($busHotLines);
    }
    else
    {
    	$tplVars['busHotLines'] = array();
    }
    $tplVars['city']       = $city;
    $tplVars['majorCity']  = BusConfig::$MAJOR_CITY;
    $tplVars['friendLink'] = LinkUtil::getLinkList(sprintf(BusConfig::DEFAULT_REQUEST_URL, $city));
    list($tplVars['cityName'], $tplVars['roundCity']) = BusUtils::getCityRelate($city);
    //Step 3: Try to Get the Assort Key of Line and Station From Cache
    $cache           = new CacheMemcache;
    $cacheKeyLine    = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_ASSORT_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_LINE]),
            strtoupper($city),
        )
    );
    $cacehKeyStation = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_ASSORT_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_STATION]),
            strtoupper($city),
        )
    );
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $lineAssortKeys    = false;
        $stationAssortKeys = false;
    }
    else
    {
        $lineAssortKeys    = $cache->read($cacheKeyLine);
        $stationAssortKeys = $cache->read($cacehKeyStation);
    }
    //Step 4: Get the Assort Key of Line and Station From DataBase
    $dbObj = new BusAssortKeyDb($city);
    if ($lineAssortKeys === false)
    {
        $lineAssortKeys = $dbObj->getAssortKeys(BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX);
        if ($lineAssortKeys !== false)
        {
            $cache->write($cacheKeyLine, $lineAssortKeys, BusSearchConfig::CACHE_ASSORT_EXPIRE);
        }
        else
        {
            $lineAssortKeys = array();
        }
    }
    if ($stationAssortKeys === false)
    {
        $stationAssortKeys = $dbObj->getAssortKeys(BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX);
        if ($stationAssortKeys !== false)
        {
            $cache->write($cacehKeyStation, $stationAssortKeys, BusSearchConfig::CACHE_ASSORT_EXPIRE);
        }
        else
        {
            $stationAssortKeys = array();
        }
    }
    $tplVars['lineAssortKeys']    = $lineAssortKeys;
    $tplVars['stationAssortKeys'] = $stationAssortKeys;
    $tplVars['json']              = BusConfig::DEFAULT_TEMPLATE_JSON;
    $tplVars['help']              = new HelpHelper(@GeographyManager::getCityPropertyByDomain($city), 0, 'bus');
	$tplVars['webtrendsSourceName'] = 'ganji';
    $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_INDEX, false);
    $tpl->display($tplVars);