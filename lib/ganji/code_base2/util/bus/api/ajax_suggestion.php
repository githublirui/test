<?php
    /**
     * 公交数据AJAX接口，提供关键字检索公交线路
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusDbConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusSearchConfig.class.php');
    
    $city = HttpHandler::getGET('city');
    $key  = HttpHandler::getGET('key');
    $data = array();
    if ($city === false || $key === false || array_key_exists($city, BusConfig::$BUS_CITYS_MAP) === false)
    {
        echo json_encode($data);
        exit();
    }
    $cacheKey = 'BUS_AJAX_' . strtoupper($city) . '_' . urlencode($key) . '_SUGGESTION';
    $cache    = new CacheMemcache;
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $data = false;
    }
    else
    {
        $data = $cache->read($cacheKey);
    }
    if ($data != false)
    {
        echo json_encode($data);
        exit();
    }
    else
    {
        $data = array();
    }
    $dbObj = DBFactory::createDb(BusDbConfig::DEFAULT_DATABASE_NAME, DBConfig::SLAVE, DBConfig::ENCODING_UTF8);
    try
    {
        $dbObj->connect();
    }
    catch (Exception $e)
    {
        echo json_encode($data);
        exit();
    }
    try
    {
        $res = $dbObj->getAll(
            SqlBuilder::buildSelectSql(
                $city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX,
                BusDbConfig::DEFAULT_COLUMNS,
                array(array('line_name', SqlBuilder::FILTER_LIKE, array($key, SqlBuilder::WILDCARD_BOTH))),
                BusSearchConfig::$SUGGEST_LIMIT
            )
        );
    }
    catch (Exception $e)
    {
        echo json_encode($data);
        exit();
    }
    foreach ($res as $line)
    {
        $data[] = array(
            'ID'   => BusUtils::lineIdToString(strval($line['line_id'])),
            'name' => $line['line_name'],
        );
    }
    $cache->write($cacheKey, $data, BusSearchConfig::CACHE_STATIONS_EXPIRE);
    echo json_encode($data);
    exit();