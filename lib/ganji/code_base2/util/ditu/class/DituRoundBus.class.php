<?php
    /**
     * 周边公交线路类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once(dirname(dirname(dirname(__FILE__))) . '/bootstrap.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    //Include Class Files
    require_once(APP_PATH . '/weather/class/WeatherConfig.class.php');
    require_once(APP_PATH . '/ditu/class/DituConfig.class.php');
    require_once(APP_PATH . '/ditu/class/DituDbTraffic.class.php');
    require_once(APP_PATH . '/ditu/class/DituUtils.class.php');
    
    class DituRoundBus
    {
        public static function getRoundBus($type, $id, $lngX, $latY, $query, $city = DituConfig::DEFAULT_CITY_NAME, $range = DituConfig::DEFAULT_RANGE)
        {
            if (!strlen($lngX) || !strlen($latY))
            {
                return array();
            }
            $cacheKey = DituConfig::TRAFFIC_PREFIX . md5(HttpHandler::getDomain() . $query);
            $stop     = false;
            $line     = array();
            if (($data = self::getDataFromCache($type, $id, $lngX, $latY, $range, $cacheKey)) !== false && isset($data['stop'][0]['ids']))
            {
                $stop = $data['stop'];
            }
            else
            {
                $stop = self::getDataFromMapABC($lngX, $latY, $city, $range);
                $data = array(
                    'coord' => array($lngX, $latY),
                    'stop'  => $stop,
                    'range' => $range,
                );
                self::setDataToCache($type, $id, $cacheKey, $data);
            }
            if ($stop !== false)
            {
                foreach ($stop as $item)
                {
                    foreach ($item['ids'] as $key => $element)
                    {
                        if (!isset($line[$key]))
                        {
                            $line[$key] = $element;
                        }
                    }
                }
            }
            return $line;
        }
        private static function getDataFromCache($type, $id, $lngX, $latY, $range, $cacheKey)
        {
            $data     = false;
            $cacheMem = new CacheMemcache();
            if (($data = unserialize($cacheMem->read($cacheKey))) !== false && $data['range'] == $range)
            {
                return $data;
            }
            $cacheDb = new DituDbTraffic();
            if (($data = $cacheDb->getData(md5($cacheKey), $id, $type)) !== false && $data['range'] == $range)
            {
                $cacheMem->write($cacheKey, serialize($data), DituConfig::TRAFFIC_EXPIRE);
                return $data;
            }
            return $data;
        }
        private static function setDataToCache($type, $id, $cacheKey, $data)
        {
            $cacheMem = new CacheMemcache();
            $cacheDb  = new DituDbTraffic();
            $content  = serialize($data);
            $cacheMem->write($cacheKey, $content, DituConfig::TRAFFIC_EXPIRE);
            $cacheDb->setData(md5($cacheKey), $id, $type, $content);
            unset($cacheMem);
            unset($cacheDb);
        }
        private static function getDataFromMapABC($lngX, $latY, $city, $range)
        {
            $cityCode = isset(WeatherConfig::$CITYS_WEATHER_CODE[$city])
                      ? WeatherConfig::$CITYS_WEATHER_CODE[$city][3]
                      : WeatherConfig::$CITYS_WEATHER_CODE[DituConfig::DEFAULT_CITY_NAME][3];
            $url      = sprintf(DituConfig::DEFAULT_SEARCH_BUS_URL, $cityCode, $lngX, $latY, $range);
            return DituUtils::getRoundStopFromMapABC($url);
        }
    }
