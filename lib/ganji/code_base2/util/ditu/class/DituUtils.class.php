<?php
    /**
     * 地图工具函数类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     *            zhangjian <zhangjian@staff.ganji.com>
     * @info
     *            add by zhangjian
     *            add getDistanceByLatlng method
     *            add getDistance         method
     * 
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */

    //Include Class Files
    require_once("DituConfig.class.php");
    require_once("DituDbConfig.class.php");
    
    final class DituUtils
    {
        /**
         * Get the Coordinate of the Address
         * 
         * @param  string $address
         * @return array|boolean If It Can Get the Coordinate of the Address, Return Array, Else Return False
         */
        public static function getAddressCoord($address)
        {
            /*
        	require_once(SITE_PATH . '/framework/logging/Log.class.php');
            ganji_log_init(
                '/tmp/',
                DituConfig::LOG_NAME . '.' . date('YmdH', time()),
                DituConfig::LOG_LEVEL,
                array(),
                DituConfig::LOG_FLUSH,
                array()
            );
            */
            set_time_limit(0);
            if (($curl = @curl_init()) === false)
            {
                return false;
            }
            $curlOptions              = DituConfig::$CURL_OPTIONS;
            $curlOptions[CURLOPT_URL] = sprintf(DituConfig::DEFAULT_SEARCH_URL, iconv('utf-8', 'gbk', $address));
            if (isset($_GET['city']) && isset(WeatherConfig::$CITYS_WEATHER_CODE[$_GET['city']]))
            {
                $curlOptions[CURLOPT_URL] .= '&cityCode=' . WeatherConfig::$CITYS_WEATHER_CODE[$_GET['city']][3];
            }
            else
            {
                $curlOptions[CURLOPT_URL] .= '&cityCode=' . WeatherConfig::$CITYS_WEATHER_CODE[DituConfig::DEFAULT_CITY_NAME][3];
            }
            if (@curl_setopt_array($curl, $curlOptions) === false)
            {
                return false;
            }
            if (($xml = @curl_exec($curl)) === false)
            {
            	//GANJI_LOG_FATAL('CURL_EXEC URL=%s ERROR=%s LINE=%d', $curlOptions[CURLOPT_URL], curl_error($curl), __LINE__);
                return false;
            }
            @curl_close($curl);
            $xml = str_replace("GBK", "UTF-8", strval($xml));
            $xml = mb_convert_encoding($xml, 'UTF-8', 'GBK');
            if (($result = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)) === false)
            {
                return false;
            }
            if (empty($result->count) === true)
            {
                return false;
            }
            return array($result->list->poi[0]->x, $result->list->poi[0]->y);
        }
        /**
         * Get the Coordinate of the City By the CityName
         * 
         * @param  string $cityName
         * @return array|boolean If It Can Get the Coordinate of the City, Return Array, Else Return False
         */
        public static function getCityCoord($cityName = DituConfig::DEFAULT_CITY_NAME)
        {
            if (isset(DituConfig::$CITYS_DITU_CODE[$cityName]))
            {
                list(,,,$coord,) = split('\|', DituConfig::$CITYS_DITU_CODE[$cityName]);
                $coord           = split(';', $coord);
                return array($coord[0], $coord[1]);
            }
            else
            {
                return false;
            }
        }
        /**
         * Get the lngX & latY From the $_GET
         *
         * @param  array $_GET
         * @return array (lngX, latY)
         */
        public static function getCoordinates()
        {
            if (isset($_GET['lnglat']) && ($coord = DituUtils::splitCoord($_GET['lnglat'])) !== false)
            {
                return $coord;
            }
            if (isset($_GET['latlng']) && ($coord = DituUtils::splitCoord($_GET['latlng'])) !== false)
            {
                return array($coord[1], $coord[0]);
            }
            if (isset($_GET['lng']) && isset($_GET['lat']) && 
                empty($_GET['lng']) === false && empty($_GET['lat']) === false)
            {
                return array($_GET['lng'], $_GET['lat']);
            }
            if (isset($_GET['city']) && !isset($_GET['address']) && empty($_GET['city']) === false && 
                ($coord = DituUtils::getCityCoord($_GET['city'])) !== false)
            {
                return $coord;
            }
            if (isset($_GET['address']) && empty($_GET['address']) === false && 
                ($coord = DituUtils::getAddressCoord($_GET['address'])) !== false)
            {
                return $coord;
            }
            return DituConfig::$DEFAULT_COORDINATE;
        }
        /**
         * Get the Database Name of the City
         *
         * @return string
         */
        public static function getDatabaseName()
        {
            $cityDomain = HttpHandler::getDomain();
            try
            {
                $city = @GeographyManager::getCityPropertyByDomain($cityDomain);
                if (!is_null($city))
                {
                    return $city->databaseName;
                }
                else
                {
                    return DituDbConfig::DEFAULT_DATABASE_NAME;
                }
            }
            catch (Exception $e)
            {
                return DituDbConfig::DEFAULT_DATABASE_NAME;
            }
        }
        /**
         * Get the Data From Cache or Database
         *
         * @param  string $cacheKey
         * @return array|boolean
         */
        public static function getDataFromCache($cacheKey)
        {
            $data = false;
            if (isset($_GET['type']) && strlen(($_GET['type'])) && isset($_GET['id']) && strlen($_GET['id']))
            {
                $range    = (isset($_GET['range']) && !empty($_GET['range'])) ? intval($_GET['range']) : DituConfig::DEFAULT_RANGE;
                $cacheMem = new CacheMemcache();
                if (($data = unserialize($cacheMem->read($cacheKey))) !== false && $data['range'] == $range)
                {
                    return $data;
                }
                $cacheDb = new DituDbTraffic();
                if (($data = $cacheDb->getData(md5($cacheKey), $_GET['id'], $_GET['type'])) !== false && $data['range'] == $range)
                {
                    $cacheMem->write($cacheKey, serialize($data), DituConfig::TRAFFIC_EXPIRE);
                    return $data;
                }
            }
            return $data;
        }
        /**
         * Set the Data To Cache And Database
         *
         * @param  string $cacheKey
         * @param  array  $data
         * @return void
         */
        public static function setDataToCache($cacheKey, $data)
        {
            if (isset($_GET['type']) && strlen(($_GET['type'])) && isset($_GET['id']) && strlen($_GET['id']))
            {
                $cacheMem = new CacheMemcache();
                //$cacheDb  = new DituDbTraffic();
                $content  = serialize($data);
                $cacheMem->write($cacheKey, $content, DituConfig::TRAFFIC_EXPIRE);
                //$cacheDb->setData(md5($cacheKey), $_GET['id'], $_GET['type'], $content);
                unset($cacheMem);
                //unset($cacheDb);
            }
        }
        /**
         * Get the HTML From File Cache
         *
         * @param  string $cacheKey
         * @return string|boolean
         */
        public static function getFileFromCache($cacheKey)
        {
            $cacheFile = new CacheFile();
            $cacheFile->setDirectory('dituTraffic');
            return $cacheFile->read($cacheKey);
        }
        /**
         * Get Pop Window Params
         *
         * @param  array $_GET
         * @return array|boolean If Isset $_GET['pop'], Return Array, Else Return False
         */
        public static function getPopWindowParam()
        {
            if (isset($_GET['pop']))
            {
                if (empty($_GET['pop']) === true)
                {
                    return array(
                        'width'  => DituConfig::DEFAULT_POP_WIDTH,
                        'height' => DituConfig::DEFAULT_POP_HEIGHT,
                        'zoom'   => DituConfig::DEFAULT_ZOOM_LEVEL,
                    );
                }
                list($width, $height, $zoom) = split(',', $_GET['pop']);
                if (empty($width))
                {
                   $width = DituConfig::DEFAULT_POP_WIDTH;
                }
                if (empty($height))
                {
                   $height = DituConfig::DEFAULT_POP_HEIGHT;
                }
                if (empty($zoom) || intval($zoom) < 3 || intval($zoom > 17))
                {
                   $zoom = DituConfig::DEFAULT_ZOOM_LEVEL;
                }
                return array(
                    'width'  => $width,
                    'height' => $height,
                    'zoom'   => $zoom,
                );
            }
            else
            {
                return false;
            }
        }
        /**
         * Get the Round Bus Stop and Subway Stop
         *
         * @param  array $_GET
         * @param  string $lngX
         * @param  string $latY
         * @return array
         */
        public static function getRoundStop($lngX, $latY)
        {
            if (!strlen($lngX) || !strlen($latY))
            {
                return false;
            }
            $range    = (isset($_GET['range']) && !empty($_GET['range'])) ? intval($_GET['range']) : DituConfig::DEFAULT_RANGE;
            $cityCode = (isset($_GET['city']) && isset(WeatherConfig::$CITYS_WEATHER_CODE[$_GET['city']]))
                      ? WeatherConfig::$CITYS_WEATHER_CODE[$_GET['city']][3]
                      : WeatherConfig::$CITYS_WEATHER_CODE[DituConfig::DEFAULT_CITY_NAME][3];
            $url      = sprintf(DituConfig::DEFAULT_SEARCH_BUS_URL, $cityCode, $lngX, $latY, $range);
            return DituUtils::getRoundStopFromMapABC($url);
        }
        /**
         * Get Round Stop From MapABC Url
         *
         * @param  string $url
         * @return array
         */
        public static function getRoundStopFromMapABC($url)
        {
            //require_once(SITE_PATH . '/framework/logging/Log.class.php');
            require_once(CODE_BASE2  . '/util/bus/class/BusConfig.class.php');
            require_once(CODE_BASE2  . '/util/bus/class/BusSearchConfig.class.php');
            require_once(CODE_BASE2  . '/util/bus/class/BusSearchDb.class.php');
            /*
            ganji_log_init(
                '/tmp/',
                DituConfig::LOG_NAME . '.' . date('YmdH', time()),
                DituConfig::LOG_LEVEL,
                array(),
                DituConfig::LOG_FLUSH,
                array()
            );
            */
            set_time_limit(0);
            if (($curl = @curl_init()) === false)
            {
                //GANJI_LOG_FATAL('CURL_INIT URL=%s LINE=%d', $url, __LINE__);
                return false;
            }
            $curlOptions              = DituConfig::$CURL_OPTIONS;
            $curlOptions[CURLOPT_URL] = $url;
            if (@curl_setopt_array($curl, $curlOptions) === false)
            {
                //GANJI_LOG_FATAL('CURL_SETOPT_ARRAY URL=%s LINE=%d', $url, __LINE__);
                return false;
            }
            if (($xml = @curl_exec($curl)) === false)
            {
                //GANJI_LOG_FATAL('CURL_EXEC URL=%s ERROR=%s LINE=%d', $url, curl_error($curl), __LINE__);
                return false;
            }
            @curl_close($curl);
            $xml = str_replace("GBK", "UTF-8", strval($xml));
            $xml = mb_convert_encoding($xml, 'UTF-8', 'GBK');
            if (($result = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)) === false)
            {
                //GANJI_LOG_FATAL('SIMPLEXML_LOAD_STRING ENCODING=%s XML=%d URL=%s LINE=%d', mb_detect_encoding($xml, array('utf-8', 'gbk')), strlen($xml), $url, __LINE__);
                return false;
            }
            //GANJI_LOG_NOTICE('ENCODING=%s RESULT_COUNT=%d XML=%d URL=%s LINE=%d', mb_detect_encoding($xml, array('utf-8', 'gbk')), $result->count, strlen($xml), $url, __LINE__);
            if ($result->count == 0)
            {
                return false;
            }
            $bus    = array();
            $subway = array();
            $unique = array();
            $dbObj  = new BusSearchDb();
            foreach ($result->list->poi as $poi)
            {
                if (($line = @simplexml_load_string(strval($poi->xml), 'SimpleXMLElement')) === false)
                {
                    //GANJI_LOG_FATAL('SIMPLEXML_LOAD_STRING URL=%s LINE=%d', $url, __LINE__);
                    return false;
                }
                $item         = array();
                $rail         = array();
                $item['line'] = array();
                $item['ids']  = array();
                $rail['line'] = array();
                $rail['ids']  = array();
                $stopData     = '';
                $idsData      = '';
                foreach ($line->DATA as $data)
                {
                    foreach ($data->attributes() as $key => $value)
                    {
                        if ($value == 'BUSINFO_LINE_KEYS')
                        {
                            $stopData = $data;
                        }
                        if ($value == 'BUSINFO_LINEIDS')
                        {
                            $idsData = $data;
                        }
                    }
                }
                $stops = split(
                    DituConfig::DEFAULT_LINE_SPLIT,
                    str_replace(
                        DituConfig::$DEFAULT_REPLACE,
                        DituConfig::DEFAULT_LINE_SPLIT,
                        $stopData
                    )
                );
                $ids   = split(
                    DituConfig::DEFAULT_LINE_SPLIT,
                    str_replace(
                        DituConfig::$DEFAULT_REPLACE,
                        DituConfig::DEFAULT_LINE_SPLIT,
                        $idsData
                    )
                );
                
                foreach ($ids as $key => $id)
                {
                    if (($attr = $dbObj->getLineAttributeByLineId($id)) !== false)
                    {
                        $name = $attr['line_name'];
                        if (strstr($name, '地铁') === false)
                        {
                            if (!isset($item['ids'][$name]) && !isset($unique[$name]))
                            {
                                $item['ids'][$name] = sprintf(DituConfig::TRAFFIC_BUS_URL, $id);
                                $unique[$name]      = true;
                            }
                        }
                        else
                        {
                            if (!isset($rail['ids'][$name]) && !isset($unique[$name]))
                            {
                                $rail['ids'][$name] = sprintf(DituConfig::TRAFFIC_BUS_URL, $id);
                                $unique[$name]      = true;
                            }
                        }
                    }
                }
                foreach ($stops as $key => $stop)
                {
                    if (strstr($stop, '地铁') === false)
                    {
                        $item['line'][] = $stop;
                    }
                    else
                    {
                        $rail['line'][] = $stop;
                    }
                }
                if (count($item['line']))
                {
                    $item['line'] = implode(DituConfig::DEFAULT_LINE_SPLIT, array_unique($item['line']));
                    $item['name'] = strval($poi->name);
                    $item['lngX'] = strval($poi->x);
                    $item['latY'] = strval($poi->y);
                    $item['len']  = intval($poi->distance);
                    $item['bus']  = true;
                    $item['time'] = intval(round($item['len'] / DituConfig::DEFAULT_STEP_SPEED));
                }
                if (count($rail['line']))
                {
                    $rail['line'] = implode(DituConfig::DEFAULT_LINE_SPLIT, array_unique($rail['line']));
                    $rail['name'] = strval($poi->name);
                    $rail['lngX'] = strval($poi->x);
                    $rail['latY'] = strval($poi->y);
                    $rail['len']  = intval($poi->distance);
                    $rail['bus']  = false;
                    $rail['time'] = intval(round($rail['len'] / DituConfig::DEFAULT_STEP_SPEED));
                }
                if (is_string($item['line']))
                {
                    $temp = array();
                    $push = false;
                    foreach ($bus as $element)
                    {
                        if ($element['len'] > $item['len'] && $push === false)
                        {
                            $temp[] = $item;
                            $push   = true;
                        }
                        $temp[] = $element;
                    }
                    if ($push === false)
                    {
                        $temp[] = $item;
                    }
                    $bus = $temp;
                }
                if (is_string($rail['line']))
                {
                    $temp = array();
                    $push = false;
                    foreach ($subway as $element)
                    {
                        if ($element['len'] > $rail['len'] && $push === false)
                        {
                            $temp[] = $rail;
                            $push   = true;
                        }
                        $temp[] = $element;
                    }
                    if ($push === false)
                    {
                        $temp[] = $rail;
                    }
                    $subway = $temp;
                }
            }
            return array_merge($subway, $bus);
        }
        /**
         * Get the Size of the Div and the Map in the Traffic Page
         *
         * @param  array $_GET
         * @return array
         */
        public static function getTrafficDivSize()
        {
            $size = array(
                'leftW'  => DituConfig::DEFAULT_LEFT_WIDTH,
                'leftH'  => DituConfig::DEFAULT_LEFT_HEIGHT,
                'rightW' => DituConfig::DEFAULT_RIGHT_WIDTH,
                'rightH' => DituConfig::DEFAULT_RIGHT_HEIGHT,
            );
            if (isset($_GET['left']) && strlen($_GET['left']))
            {
                list($leftW, $leftH) = split(',', $_GET['left']);
                if (strlen($leftW))
                {
                    $size['leftW'] = $leftW;
                }
                if (strlen($leftH))
                {
                    $size['leftH'] = $leftH;
                }
            }
            if (isset($_GET['right']) && strlen($_GET['right']))
            {
                list($rightW, $rightH) = split(',', $_GET['right']);
                if (strlen($rightW))
                {
                    $size['rightW'] = $rightW;
                }
                if (strlen($rightH))
                {
                    $size['rightH'] = $rightH;
                }
            }
            return $size;
        }
        /**
         * Get PostId or PostName
         * 
         * @param  array $_GET
         * @return array ($post, $type)
         */
        public static function getWriteBackParams()
        {
            if (isset($_GET['postid']))
            {
                return array($_GET['postid'], 'Id');
            }
            else if (isset($_GET['postname']))
            {
                return array($_GET['postname'], 'Name');
            }
            else
            {
                return array(DituConfig::DEFAULT_PARENT_INPUT, 'Both');
            }
        }
        /**
         * Get PostId or PostName
         * 
         * @param  array $_GET
         * @return integer
         */
        public static function getZoomParam()
        {
            if (isset($_GET['zoom']) && intval($_GET['zoom']) >=0 && intval($_GET['zoom']) <= 14)
            {
                return $_GET['zoom'];
            }
            else
            {
                return DituConfig::DEFAULT_ZOOM_LEVEL;
            }
        }
        /**
         * Split & Judge the Array
         *
         * @param  array $coord
         * @return array|boolean If Array is Legal, Return Array, Else Return False 
         */
        public static function splitCoord($coord)
        {
            $xy = split(',', $coord);
            if (count($xy) != 2 || strlen($xy[0]) == 0 || strlen($xy[1]) == 0)
            {
                return false;
            }
            else
            {
                return $xy;
            }
        }
        /** {{{ getDistanceByLatlng 
         * measure distance between the two latlng
         * 
         * @param  string $from latlng
         * @param  string $to   latlng
         * @return float
         */
        public static function getDistanceByLatlng($from, $to){
            list($from_lat, $from_lng) = explode(',', $from);
            list($to_lat,   $to_lng)   = explode(',', $to);
            $x_len = 2 * (DituConfig::EARTH_RADII * sin(abs($from_lat - $to_lat) / 2));
            $y_len = 2 * (DituConfig::EARTH_RADII * sin(abs($from_lng - $to_lng) / 2));
            return ceil(self::getDistance($x_len, $y_len));
        }//}}}
        /** {{{ getDistance 
         * measure distance between two real lat length and lng length
         *
         * @param  string $from real of the lat length
         * @param  string $to   real of the lng length
         * @return float
         */
        public static function getDistance($from, $to){
            $angle = 2 * asin(sqrt(pow($from, 2) + pow($to, 2)) / 2 / DituConfig::EARTH_RADII);
            return DituConfig::EARTH_RADII * ($angle * M_PI / 180.0);
        }//}}}
        /**
         * Get Other Info
         *
         * @param  void
         * @return array()
         */
        public static function getOtherInfo()
        {
            require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
            require_once(APP_PATH  . '/housing/api/HouseHelper.class.php');
            require_once(APP_PATH  . '/wanted/helper/WantedHelper.class.php'); 
            $cache    = new CacheMemcache();
            $city     = HttpHandler::getDomain();
            $cacheKey = DituConfig::OTHER_INFO_PREFIX . strtoupper($city);
            if (($data = $cache->read($cacheKey)) === false || empty($data[0]) || empty($data[1]) || empty($data[2]))
            {
                $data[] = HouseHelper::getLatestRentHouse($city, DituConfig::OTHER_INFO_COUNT);
                $data[] = HouseHelper::getLatestSellHouse($city, DituConfig::OTHER_INFO_COUNT);
                $data[] = WantedHelper::getListForMap($city, DituConfig::OTHER_INFO_COUNT);
                $cache->write($cacheKey, $data, DituConfig::OTHER_INFO_EXPIRE);
            }
            return $data;
        }
    }
