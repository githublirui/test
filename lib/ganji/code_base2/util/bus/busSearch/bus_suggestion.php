<?php
    /**
     * 公交数据查询搜索建议
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');    
    require_once(APP_PATH . '/bus/class/BusSuggestionDb.class.php');
    
    $res = array();
    //Step 1: Check the Param
    if (!isset($_GET['type']) || !isset($_GET['input']) || empty($_GET['input']) || ($type = array_search($_GET['type'], BusSearchConfig::$ALL_SEARCH_TYPES)) === false)
    {
        echo json_encode($res);
        exit();
    }
    $count = intval($_GET['count']);
    if ($count <= 0 || $count > 10)
    {
        $count = 10;
    }
    //Step 2: Get the City Domain
    $city = BusUtils::getCityByDomain(); 
    //Step 3: Try to Get the Suggestion Data From Cache
    $cacheKey = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_SUGGEST_INFIX,
            strtoupper($_GET['type']),
            strtoupper($city),
            $_GET['input'],
        )
    );
    $cache = new CacheMemcache;
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $res = false;
    }
    else
    {
        $res = $cache->read($cacheKey);
    }
    //Step 4: Get the Suggestion Data From DataBase
    if ($res === false)
    {
        $dbObj = new BusSuggestionDb($city);
        switch ($type)
        {
            case BusSearchConfig::SEARCH_TYPE_LINE:
                $res = $dbObj->getLineSuggestion($_GET['input'], $count);
                break;
            case BusSearchConfig::SEARCH_TYPE_STATION:
                $res = $dbObj->getStationSuggestion($_GET['input'], $count);
                break;
            default:
                break;
        }
        if ($res === false)
        {
            $res = json_encode(array());
        }
        else
        {
            $cache->write($cacheKey, $res, BusSearchConfig::CACHE_SUGGEST_EXPIRE);
        }
    }
    echo $res;
    exit();