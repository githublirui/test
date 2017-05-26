<?php
    /**
     * 公交数据查询简称结果页
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
    require_once(APP_PATH . '/bus/class/BusAssortKeyDb.class.php');
    
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
    //Step 3: Check the Param
    if (!isset($_GET['type']) || !isset($_GET['key']) || empty($_GET['key']) || ($type = array_search($_GET['type'], BusSearchConfig::$ALL_SEARCH_TYPES)) === false)
    {
        HttpHandler::displayPageNotFound();
    }
    //Step 4: Get the City Infomation
    $tplVars['city']     = $city;
    $tplVars['cityName'] = array_shift(BusUtils::getCityRelate($city, false));
    $tplVars['keyword']  = $_GET['key'];
    //Step 5: Try to Get the Assort Key of Line or Station From Cache
    $cache          = new CacheMemcache;    
    $cacheKeyAssort = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_ASSORT_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[$type]),
            strtoupper($city),
        )
    );
    $cacheKeyList   = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_LIST_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[$type]),
            strtoupper($city),
            strtoupper($_GET['key']),
        )
    );
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $assortKeys = false;
        $keyLists  = false;
    }
    else
    {
        $assortKeys = $cache->read($cacheKeyAssort);
        $keyLists   = $cache->read($cacheKeyList);
    }
    //Step 6: Get the Data From DataBase
    $dbObj = new BusAssortKeyDb($city);
    if ($assortKeys === false)
    {
        switch ($type)
        {
            case BusSearchConfig::SEARCH_TYPE_LINE:
                $assortKeys = $dbObj->getAssortKeys(BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX);
                break;
            case BusSearchConfig::SEARCH_TYPE_STATION:
                $assortKeys = $dbObj->getAssortKeys(BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX);
                break;
            default:
                break;
        }
        if ($assortKeys !== false)
        {
            $cache->write($cacheKeyAssort, $assortKeys, BusSearchConfig::CACHE_ASSORT_EXPIRE);
        }
        else
        {
            $assortKeys = array();
        }
    }
    if ($keyLists === false)
    {
        switch ($type)
        {
            case BusSearchConfig::SEARCH_TYPE_LINE:
                $keyLists = $dbObj->getLineListsByAssortKey($_GET['key']);
                break;
            case BusSearchConfig::SEARCH_TYPE_STATION:
                $keyLists = $dbObj->getStationListsByAssortKey($_GET['key']);
                break;
            default:
                break;
        }
        if ($keyLists !== false)
        {
            $cache->write($cacheKeyList, $keyLists, BusSearchConfig::CACHE_LIST_EXPIRE);
        }
        else
        {
            $keyLists = array();
        }
    }
    $tplVars['assortKeys'] = $assortKeys;
    $tplVars['keyLists']   = $keyLists;
	$tplVars['webtrendsSourceName'] = 'ganji';
    $tpl = new SmartyEngine(SITE_PATH . sprintf(BusSearchConfig::DEFAULT_TEMPLATE_LIST, BusSearchConfig::$ALL_SEARCH_TYPES[$type]), false);
    $tpl->display($tplVars);