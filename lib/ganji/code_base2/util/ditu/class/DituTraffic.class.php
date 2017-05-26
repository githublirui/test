<?php
    /**
     * 交通地图类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    require_once(SITE_PATH . '/framework/caching/CacheFile.class.php');
    require_once(SITE_PATH . '/framework/caching/CacheMem.class.php');
    require_once(SITE_PATH . '/framework/logging/Log.class.php');
    //Include Class Files
    require_once(APP_PATH . '/weather/class/WeatherConfig.class.php');
    require_once(APP_PATH . '/ditu/class/DituConfig.class.php');
    require_once(APP_PATH . '/ditu/class/DituDbTraffic.class.php');
    require_once(APP_PATH . '/ditu/class/DituUtils.class.php');
    
    class DituTraffic
    {
        /**
         * the Construct of the DituTraffic Class
         *
         * @param  void
         * @return void
         */
        public function __construct()
        {
            $this->process();
        }
        /**
         * Process the WorkFlow of the Show DituTraffic Page
         *
         * @param  void
         * @return void
         */
        private function process()
        {
        	$NOW = microtime(true);
		    $log = ganji_log_init(
		        '/tmp/ditu_traffic_page/',
		        'ditu_traffic_page.' . date('YmdH', $NOW),
		        8,
		        array(),
		        true,
		        array()
		    );
		    $infos    = array(
		    	'file'  => 0,
		    	'cache' => 0,
		    	'curl'  => 0,
		    	'rest'  => 0,
		    );
            $cacheKey = DituConfig::TRAFFIC_PREFIX . md5(HttpHandler::getDomain() . $_SERVER['QUERY_STRING']);
            //Step 0: Read the HTML From File Cache
            if (($html = @DituUtils::getFileFromCache($cacheKey)) !== false)
            {
                echo $html;
                $infos['file'] = microtime(true) - $NOW;
                GANJI_LOG_TRACE(
                	'file=%f cache=%f curl=%f rest=%f query=%s referer=%s',
                	$infos['file'], $infos['cache'], $infos['curl'], $infos['rest'], $_SERVER['QUERY_STRING'], $_SERVER['HTTP_REFERER'] 
                );
                exit;
            }
            $infos['file'] = microtime(true) - $NOW;
            if (($data = @DituUtils::getDataFromCache($cacheKey)) !== false)
            {
                list($lngX, $latY) = $data['coord'];
                $stop              = $data['stop'];
                $infos['cache'] = microtime(true) - $NOW;
            }
            else
            {
                //Step 1: Parse the lngX & latY From Request
                list($lngX, $latY) = DituUtils::getCoordinates();
                //Step 2: Get the Round Bus Stop and Subway Stop
                if (!isset($_GET['show']))
                {
                    $stop = DituUtils::getRoundStop($lngX, $latY);
                }
                else
                {
                    $stop = false;
                }
                $data          = array();
                $data['coord'] = array($lngX, $latY);
                $data['stop']  = $stop;
                $data['range'] = (isset($_GET['range']) && !empty($_GET['range'])) ? intval($_GET['range']) : DituConfig::DEFAULT_RANGE;
                @DituUtils::setDataToCache($cacheKey, $data);
                $infos['curl'] = microtime(true) - $NOW;
            }
            //Step 3: Get the Size of the Div and the Map in the Traffic Page
            foreach (DituUtils::getTrafficDivSize() as $key => $value)
            {
                $$key = $value;
            }
            //Step 4: Get Pop Window Params
            $pop  = DituUtils::getPopWindowParam();
            //Step 5: Get Zoom Param
            $zoom = DituUtils::getZoomParam();
            //Step 6: Display the DituMark Page
            $templateVar = array(
                'crossdomain' => isset($_GET['crossdomain']) ? true : false,
                'tpl'     => DituConfig::DEFAULT_TRAFFIC_TEMPLATE,
            	'logType' => $_GET['type'],
            	'logId'   => $_GET['id'],
                'ispop'   => ($pop === false) ? false : true,
                'popW'    => ($pop === false) ? '' : $pop['width'],
                'popH'    => ($pop === false) ? '' : $pop['height'],
                'level'   => ($pop === false) ? '' : $pop['zoom'],
                'leftW'   => $leftW,
                'leftH'   => $leftH,
                'rightW'  => $rightW,
                'rightH'  => $rightH,
                'zoom'    => $zoom,
                'lngX'    => $lngX,
                'latY'    => $latY,
                'range'   => (isset($_GET['range']) && !empty($_GET['range'])) ? intval($_GET['range']) : DituConfig::DEFAULT_RANGE,
                'show'    => isset($_GET['show']) ? true : false,
                'suggest' => (isset($_GET['suggest']) && !empty($_GET['suggest'])) ? $_GET['suggest'] : DituConfig::DEFAULT_SUGGEST_NUM,
                'iframe'  => (isset($_GET['iframe']) && !empty($_GET['iframe'])) ? $_GET['iframe'] : DituConfig::DEFAULT_TRAFFIC_IFRAME,
                'city'    => (isset($_GET['city']) && !empty($_GET['city'])) ? $_GET['city'] : DituConfig::DEFAULT_CITY_NAME,
                'address' => (isset($_GET['address']) && !empty($_GET['address'])) ? $_GET['address'] : DituConfig::DEFAULT_ADDRESS,
                'stop'    => $stop,
                'code'    => (isset($_GET['city']) && isset(WeatherConfig::$CITYS_WEATHER_CODE[$_GET['city']]))
                           ? WeatherConfig::$CITYS_WEATHER_CODE[$_GET['city']][3]
                           : WeatherConfig::$CITYS_WEATHER_CODE[DituConfig::DEFAULT_CITY_NAME][3],
            );
			$templateVar['enaddr'] = urlencode($templateVar['address']);
			$templateVar['encity'] = urlencode($templateVar['city']);
            $this->displayPage($templateVar, $cacheKey);
            $infos['rest'] = microtime(true) - $NOW;
            GANJI_LOG_TRACE(
                'file=%f cache=%f curl=%f rest=%f query=%s referer=%s',
                $infos['file'], $infos['cache'], $infos['curl'], $infos['rest'], $_SERVER['QUERY_STRING'], $_SERVER['HTTP_REFERER'] 
			);
        }
        /**
         * Show the DituTraffic Page
         *
         * @param  array  $templateVar the Array of the template Vars
         * @param  string $cacheKey    the Key of the File Cache
         * @return void
         */
        private function displayPage($templateVar, $cacheKey)
        {
            $tpl = new SmartyEngine(SITE_PATH . $templateVar['tpl'], false);
            unset($templateVar['tpl']);
            $html  = $tpl->fetch($templateVar);
            $cache = new CacheFile();
            $cache->setDirectory('dituTraffic');
            $cache->write($cacheKey, $html, DituConfig::TRAFFIC_EXPIRE);
            echo $html;
        }
        /**
         * the Construct of the DituTraffic Class
         *
         * @param  void
         * @return void
         */
        public function __destruct()
        {
            //nothing to do
        }
    }
