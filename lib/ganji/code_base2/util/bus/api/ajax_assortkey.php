<?php
    /**
     * 公交数据AJAX接口，提供用首字母（或数字）检索公交线路
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
    $cacheKey = 'BUS_AJAX_' . strtoupper($city) . '_' . urlencode($key) . '_ASSORTKEY';
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
                BusSearchConfig::LINE_LIST_COLUMNS,
                array(array('assort_key', SqlBuilder::FILTER_EQUAL, $key)),
                array(),
                array('line_name' => SqlBuilder::SORT_ASC)
            )
        );
    }
    catch (Exception $e)
    {
        echo json_encode($data);
        exit();
    }
    $duplicate = array();
    foreach ($res as $line)
    {
        $name = BusUtils::filterLineName($line['line_name']);
        if (array_search($name, $duplicate) === false)
        {
            $duplicate[] = $name;
            $data[]      = array(
                'ID'   => BusUtils::lineIdToString(strval($line['line_id'])),
                'name' => $name,
            );
        }
    }
    $cache->write($cacheKey, $data, BusSearchConfig::CACHE_ASSORT_EXPIRE);
    echo json_encode($data);
    exit();