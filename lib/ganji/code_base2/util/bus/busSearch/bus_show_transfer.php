<?php
    /**
     * 公交数据查询换乘结果展示页
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
    if (!isset($_GET['start']) || empty($_GET['start']) || !isset($_GET['end']) || empty($_GET['end']) || 
        !isset($_GET['x1']) || empty($_GET['x1']) || !isset($_GET['y1']) || empty($_GET['y1']) || !isset($_GET['z1']) || empty($_GET['z1']) || 
        !isset($_GET['x2']) || empty($_GET['x2']) || !isset($_GET['y2']) || empty($_GET['y2']) || !isset($_GET['z2']) || empty($_GET['z2']))
    {
        $tplVars['noresult'] = true;
        $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_SHOW, false);
        $tpl->display($tplVars);
        exit();
    }
    //Step 2: Get the City Infomation
    $tplVars['start_gbk'] = urlencode($_GET['start']);
    $tplVars['end_gbk']   = urlencode($_GET['end']);
    $_GET['start']        = iconv('gbk', 'utf-8', $_GET['start']);
    $_GET['end']          = iconv('gbk', 'utf-8', $_GET['end']);
    $tplVars['noresult']  = false;
    $tplVars['city']      = $city;
    $tplVars['cityName']  = array_shift(BusUtils::getCityRelate($city, false));
    $tplVars['start']     = $_GET['start'];
    $tplVars['end']       = $_GET['end'];
    $tplVars['x1']        = $_GET['x1'];
    $tplVars['x2']        = $_GET['x2'];
    $tplVars['y1']        = $_GET['y1'];
    $tplVars['y2']        = $_GET['y2'];
    $tplVars['z1']        = $_GET['z1'];
    $tplVars['z2']        = $_GET['z2'];
    //Step 3: Try to Get the Assort Key of Line or Station From Cache
    $cache            = new CacheMemcache;
    $cacheKeyTransfer = implode(
        BusConfig::DEFAULT_CACHE_SPLIT_CHAR,
        array(
            BusConfig::DEFAULT_CACHE_PREFIX,
            BusSearchConfig::CACHE_TRANSFER_INFIX,
            strtoupper($city),
            $_GET['start'],
            $_GET['end'],
        )
    );
    if (BusSearchConfig::SEARCH_DEBUG)
    {
        $tplVars['data'] = false;
    }
    else
    {
        $tplVars['data'] = $cache->read($cacheKeyTransfer);
    }
    //Step 4: Get the Transfer Data
    if ($tplVars['data'] === false)
    {
        $url = sprintf(
            BusConfig::DEFAULT_SWITCH_BUSLINE_URL,
            BusConfig::$BUS_CITYS_MAP[$city]['cityCode'],
            $_GET['x1'], $_GET['y1'], $_GET['x2'], $_GET['y2'],
            BusSearchConfig::TRANSFER_TYPE_SWITCHLESS
        );
        $tplVars['data'] = BusUtils::getTransferFromXml(BusUtils::getDataByCurl($url));
        if ($tplVars['data'] === false || $tplVars['data'] === 0)
        {
            $tplVars['noresult'] = true;
            $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_SHOW, false);
            $tpl->display($tplVars);
            exit();
        }
        else
        {
            $cache->write($cacheKeyTransfer, $tplVars['data'], BusSearchConfig::CACHE_TRANSFER_EXPIRE);
        }
    }
    $tpl = new SmartyEngine(SITE_PATH . BusSearchConfig::DEFAULT_TEMPLATE_SHOW, false);
    $tpl->display($tplVars);
    exit();