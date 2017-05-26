<?php
    /**
     * 地图类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once(SITE_PATH . '/common/geography/GeographyManager.class.php');
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    require_once(APP_PATH  . '/common/IpLocation.php');
    //Include Class Files
    require_once(APP_PATH . '/weather/class/WeatherConfig.class.php');
    require_once(APP_PATH . '/weather/class/WeatherUtils.class.php');
    require_once(APP_PATH . '/ditu/class/DituUtils.class.php');
    require_once(APP_PATH . '/ditu/class/DituConfig.class.php');
    
    final class ditu
    {
        /**
         * the Construct of the Ditu Class
         *
         * @param  void
         * @return void
         */
        public function __construct()
        {
            //nothing to do
        }
        /**
         * Process the WorkFlow of the Show Ditu Page
         *
         * @param  boolean $isIndex Show Index Page or Detail Page, True Is Index, False Is Detail
         * @return void
         */
        public function process($isIndex = false)
        {
            //Step 1: Parser the Pinyin of the City From $_SERVER['HTTP_HOST']
            $cityPinyin  = WeatherUtils::getCityPinyin($isIndex);
            //Step 2: Get the Chinese Name of the City
            $cityName    = WeatherUtils::getCityChineseName($cityPinyin);
            //Step 3: Get the Citys Around
            $roundCity   = WeatherUtils::getRoundCity($cityPinyin);
            //Step 4: Get Other Info
            list($fang1, $fang5, $zhaopin) = DituUtils::getOtherInfo();
            //Step 5: Display the Weather Page
            $templateVar = array(
                'dituweather' => true,
                'tpl'         => DituConfig::DEFAULT_DETAIL_TEMPLATE,
                'roundCity'   => $roundCity,
                'city'        => $cityName,
                'json'        => WeatherConfig::$TEMPLATE_JSON,
                'cityPinyin'  => $cityPinyin,
                'hasBus'      => WeatherConfig::$CITYS_WEATHER_CODE[$cityName][2],
                'mapParam'    => isset(DituConfig::$CITYS_DITU_CODE[$cityName]) ? DituConfig::$CITYS_DITU_CODE[$cityName] : DituConfig::$CITYS_DITU_CODE[WeatherConfig::DEFAULT_CITY_NAME],
                'fang1'       => count($fang1)   == 0 ? false : $fang1,
                'fang5'       => count($fang5)   == 0 ? false : $fang5,
                'zhaopin'     => count($zhaopin) == 0 ? false : $zhaopin,
				'webtrendsSourceName' => 'ganji',
            );
            $this->displayPage($templateVar);
        }
        /**
         * Show the Weather Page
         *
         * @param  array $templateVar the Array of the template Vars
         * @return void
         */
        private function displayPage($templateVar)
        {
            $tpl = new SmartyEngine(SITE_PATH . $templateVar['tpl'], false);
            unset($templateVar['tpl']);
            $tpl->display($templateVar);
        }
        /**
         * the Destruct of the Ditu Class
         *
         * @param  void
         * @return void
         */
        public function __destruct()
        {
            //nothing to do
        }
    }