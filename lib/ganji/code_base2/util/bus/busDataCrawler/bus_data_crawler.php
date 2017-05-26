<?php
    /**
     * 公交数据抓取
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusDataCrawler
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once('../../bootstrap.php');
    require_once(SITE_PATH . '/framework/logging/Log.class.php');
    require_once(SITE_PATH . '/framework/data/DBFactory.class.php');
    require_once(SITE_PATH . '/framework/data/SqlBuilder.class.php');
    require_once(SITE_PATH . '/libs/hanzitopinyin/ChineseStringToPinYin.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusDataCrawlerConfig.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');
    require_once(APP_PATH . '/bus/class/BusDataCrawlerDb.class.php');
    
    //Step 1: Init the Log
    ganji_log_init(
        BusDataCrawlerConfig::DEFAULT_LOG_PATH,
        BusDataCrawlerConfig::DEFAULT_LOG_NAME,
        BusDataCrawlerConfig::DEFAULT_LOG_LEVEL,
        BusDataCrawlerConfig::$DEFAULT_LOG_BASIC,
        BusDataCrawlerConfig::DEFAULT_LOG_FLUSH,
        BusDataCrawlerConfig::$DEFAULT_BASIC_FIELD
    );
    $allCityCount = array(
        'total'     => 0,
        'success'   => 0,
        'fail'      => 0,
        'duplicate' => 0,
    );
    //Step 2: Crawl the Data
    foreach (BusDataCrawlerConfig::$SPECIAL_KEYWORDS as $city => $specialKeywords)
    {
        $cityCount = array(
            'total'     => 0,
            'success'   => 0,
            'fail'      => 0,
            'duplicate' => 0,
        );
        GANJI_LOG_NOTICE('Begin to Crawl City : %s %d', $city, __LINE__);
        //Step 2.1: Get the City Code
        if (isset(BusConfig::$BUS_CITYS_MAP[$city]))
        {
            $cityCode = BusConfig::$BUS_CITYS_MAP[$city]['cityCode'];
        }
        else
        {
            GANJI_LOG_WARNING('Undefined City : %s %d', $city, __LINE__);
            continue;
        }
        ganji_log_addbasic(array('city' => $city, 'cityCode' => $cityCode));
        $dbObj = new BusDataCrawlerDb($city, DBConfig::MASTER);
        if (strlen($dbObj->getSqlError()) != 0)
        {
            GANJI_LOG_FATAL('%s %d', $dbObj->getSqlError(), __LINE__);
        }
        //Step 2.2: Merge the Keywords
        $keywords = array();
        $keywords = array_merge(BusDataCrawlerConfig::$DEFAULT_KEYWORDS, $specialKeywords);
        //Step 2.3: Crawl the Data of the Keywords
        foreach ($keywords as $keyword)
        {
            //Step 2.3.1: Build the Url
            $url = sprintf(BusConfig::DEFAULT_SEARCH_BUSNAME_URL, $cityCode, iconv('UTF-8', 'GB2312', $keyword));
            GANJI_LOG_NOTICE('keyword=%s url=%s %d', $keyword, $url, __LINE__);
            //Step 2.3.2: Get the Data
            for ($i = 0;$i < BusDataCrawlerConfig::DEFAULT_RETRY_NUM;$i++)
            {
                $xml = BusUtils::getDataByCurl($url);
                if ($xml !== false)
                {
                    break;
                }
            }
            if ($xml === false)
            {
                GANJI_LOG_WARNING('Gan\'t Get the Data of the Url(%s) %d', $url, __LINE__);
                continue;
            }
            //Step 2.3.3: Build the SimpleXml Object
            $result = @simplexml_load_string($xml);
            if ($result === false)
            {
                GANJI_LOG_WARNING('Error Data From the Url(%s) %d', $url, __LINE__);
                continue;
            }
            //Step 2.3.4: Analyse the Data
            if (empty($result->list))
            {
                GANJI_LOG_WARNING('Empty Data From the Url(%s) %d', $url, __LINE__);
                continue;
            }
            $cityCount['total'] += intval($result->count);
            foreach ($result->list->bus as $bus)
            {
                if ($dbObj->isExistLineId(strval($bus->line_id)) === true)
                {
                    GANJI_LOG_WARNING('DUPLICATE line_id=%s %d', strval($bus->line_id), __LINE__);
                    $cityCount['duplicate']++;
                    continue;
                }
                if (strlen($dbObj->getSqlError()) != 0)
                {
                    GANJI_LOG_FATAL('FAIL sql=%s error=%s %d', $dbObj->getSql(), $dbObj->getSqlError(), __LINE__);
                    $cityCount['fail']++;
                    continue;
                }
                $data = BusUtils::getDataFromXml($bus);
                if ($data === false)
                {
                    GANJI_LOG_FATAL('FAIL line_id=%s %d', strval($bus->line_id), __LINE__);
                    $cityCount['fail']++;
                    continue;
                }
                if ($dbObj->setBasicOfLine($data['basic']) === false)
                {
                    GANJI_LOG_FATAL('FAIL sql=%s error=%s %d', $dbObj->getSql(), $dbObj->getSqlError(), __LINE__);
                    $cityCount['fail']++;
                    continue;
                }
                if ($dbObj->setAttributeOfLine($data['attribute']) === false)
                {
                    GANJI_LOG_FATAL('FAIL sql=%s error=%s %d', $dbObj->getSql(), $dbObj->getSqlError(), __LINE__);
                    $cityCount['fail']++;
                    continue;
                }
                if ($dbObj->setStationOfLine($data['station']) === false)
                {
                    GANJI_LOG_FATAL('FAIL sql=%s error=%s %d', $dbObj->getSql(), $dbObj->getSqlError(), __LINE__);
                    $cityCount['fail']++;
                    continue;
                }
                $cityCount['success']++;
            }
            usleep(BusDataCrawlerConfig::DEFAULT_USLEEP_NUM);
        }
        GANJI_LOG_NOTICE('total=%d success=%d fail=%d duplicate=%d', $cityCount['total'], $cityCount['success'], $cityCount['fail'], $cityCount['duplicate']);
        foreach ($allCityCount as $key => $value)
        {
            $allCityCount[$key] += $cityCount[$key];
        }
        unset($dbObj);
        usleep(BusDataCrawlerConfig::DEFAULT_USLEEP_NUM);
    }
    ganji_log_addbasic(array('city' => '', 'cityCode' => ''));
    GANJI_LOG_NOTICE('total=%d success=%d fail=%d duplicate=%d', $allCityCount['total'], $allCityCount['success'], $allCityCount['fail'], $allCityCount['duplicate']);