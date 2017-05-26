<?php
    /**
     * 公交数据查询换乘结果页
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

    //Step 1: Check the Param
    $tplVars = array();
    $city    = BusUtils::getCityByDomain();
    if (BusConfig::$BUS_CITYS_MAP[$city]['onService'] === false)
    {
        $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_UPDATE, false);
        $tpl->display($tplVars);
        exit();
    }    
    if (!isset($_GET['start']) || !isset($_GET['end']) || empty($_GET['start']) || empty($_GET['end']))
    {
        HttpHandler::displayPageNotFound();
    }
    $_GET['start']      = iconv('gbk', 'utf-8', $_GET['start']);
    $_GET['end']        = iconv('gbk', 'utf-8', $_GET['end']);
    $tplVars['start']   = $_GET['start'];
    $tplVars['end']     = $_GET['end'];
    $tplVars['tabItem'] = BusSearchConfig::SEARCH_TYPE_TRANSFER + 1;
    $tplVars['switch']  = true;
    //Step 2: Get the City Infomation
    $tplVars['city']     = $city;
    $tplVars['cityName'] = array_shift(BusUtils::getCityRelate($city, false));
    //Step 3: Try to Get the Assort Key of Line or Station From Cache
    $cache         = new CacheMemcache;
    $cacheKeyStart = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_TRANSFER_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_STATION]),
            strtoupper($city),
            $_GET['start'],
        )
    );
    $cacheKeyEnd   = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_TRANSFER_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[BusSearchConfig::SEARCH_TYPE_STATION]),
            strtoupper($city),
            $_GET['end'],
        )
    );
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $start = false;
        $end   = false;
    }
    else
    {
        $start = $cache->read($cacheKeyStart);
        $end   = $cache->read($cacheKeyEnd);
    }
    //Step 4: Get the Data From DataBase
    $dbObj = new BusSearchDb($city);
    if ($start === false)
    {
        $start = $dbObj->getSearch(BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX, $_GET['start'], true);
        if ($start !== false && count($start) !== 0)
        {
            $start = BusUtils::relateSort($start, $_GET['start']);
            $cache->write($cacheKeyStart, $start, BusSearchConfig::CACHE_SEARCH_EXPIRE);
        }
        else
        {
            $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_NORESULT, false);
            $tpl->display($tplVars);
            exit();
        }
    }
    if ($end === false)
    {
        $end = $dbObj->getSearch(BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX, $_GET['end'], true);
        if ($end !== false && count($end) !== 0)
        {
            $end = BusUtils::relateSort($end, $_GET['end']);
            $cache->write($cacheKeyEnd, $end, BusSearchConfig::CACHE_SEARCH_EXPIRE);
        }
        else
        {
            $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_NORESULT, false);
            $tpl->display($tplVars);
            exit();
        }
    }
    if (count($start))
    {
        $start[0]['gbk'] = urlencode(iconv('utf-8', 'gbk', $start[0]['station_name']));
    }
    if (count($end))
    {
        $end[0]['gbk'] = urlencode(iconv('utf-8', 'gbk', $end[0]['station_name']));
    }
    $tplVars['startStations'] = $start;
    $tplVars['endStations']   = $end;
	$tplVars['webtrendsSourceName'] = 'ganji';
    $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_TRANSFER, false);
    $tpl->display($tplVars);