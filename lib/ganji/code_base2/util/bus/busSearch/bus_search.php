<?php
    /**
     * 公交数据查询结果页
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
    if (!isset($_GET['type']) || !isset($_GET['key']) || empty($_GET['key']) || ($type = array_search($_GET['type'], BusSearchConfig::$ALL_SEARCH_TYPES)) === false)
    {
        HttpHandler::displayPageNotFound();
    }
    $_GET['key'] = iconv('gbk', 'utf-8', $_GET['key']);
    //Step 2: Get the City Infomation
    $tplVars['switch']   = true;
    $tplVars['input']    = $_GET['key'];
    $tplVars['tabItem']  = $type + 1;
    $tplVars['city']     = $city;
    $tplVars['cityName'] = array_shift(BusUtils::getCityRelate($city, false));
    //Step 3: Try to Get the Assort Key of Line or Station From Cache
    $cache          = new CacheMemcache;    
    $cacheKeySearch = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_SEARCH_INFIX,
            strtoupper(BusSearchConfig::$ALL_SEARCH_TYPES[$type]),
            strtoupper($city),
            $_GET['key'],
        )
    );
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $search = false;
    }
    else
    {
        $search = $cache->read($cacheKeySearch);
    }
    //Step 4: Get the Data From DataBase
    $dbObj = new BusSearchDb($city);
    if ($search === false)
    {
        switch ($type)
        {
            case BusSearchConfig::SEARCH_TYPE_LINE:
                $search = $dbObj->getSearch(BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX, $_GET['key']);
                break;
            case BusSearchConfig::SEARCH_TYPE_STATION:
                $search = $dbObj->getSearch(BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX, $_GET['key']);
                break;
            default:
                break;
        }
        if ($search !== false && count($search) !== 0)
        {
            $cache->write($cacheKeySearch, $search, BusSearchConfig::CACHE_SEARCH_EXPIRE);
        }
        else
        {
            $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_NORESULT, false);
            $tplVars['keyword'] = $_GET['key'];
            $tpl->display($tplVars);
            exit();
        }
    }
    if (count($search) == 1)
    {
        switch ($type)
        {
            case BusSearchConfig::SEARCH_TYPE_LINE:
                $url = sprintf(BusSearchConfig::DEFAULT_URL_LINE, $search[0]['line_id']);
                break;
            case BusSearchConfig::SEARCH_TYPE_STATION:
                $url = sprintf(BusSearchConfig::DEFAULT_URL_STATION, $search[0]['line_id'], $search[0]['station_num']);
                break;
            default:
                $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_NORESULT, false);
                $tplVars['keyword'] = $_GET['key'];
                $tpl->display($tplVars);
                exit();
                break;
        }
        BusUtils::rewriteUrl($url);
    }
    $tplVars['keyLists'] = $search;
    $tplVars['search']   = true;
    $tplVars['keyword']  = false;
	$tplVars['webtrendsSourceName'] = 'ganji';
    $tpl = new SmartyEngine(SITE_PATH . sprintf(BusSearchConfig::DEFAULT_TEMPLATE_LIST, BusSearchConfig::$ALL_SEARCH_TYPES[$type]), false);
    $tpl->display($tplVars);