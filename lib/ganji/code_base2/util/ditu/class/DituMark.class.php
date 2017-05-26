<?php
    /**
     * 地图标注类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Common Files
    require_once(SITE_PATH . '/framework/ui/SmartyEngine.class.php');
    //Include Class Files
    require_once(APP_PATH . '/weather/class/WeatherConfig.class.php');
    require_once(APP_PATH . '/ditu/class/DituConfig.class.php');
    require_once(APP_PATH . '/ditu/class/DituUtils.class.php');    
    
    class DituMark
    {
        /**
         * the Construct of the DituMark Class
         *
         * @param  void
         * @return void
         */
        public function __construct()
        {
            $this->process();
        }
        /**
         * Process the WorkFlow of the Show DituMark Page
         *
         * @param  void
         * @return void
         */
        private function process()
        {
            //Step 1: Parse the lngX & latY From Request
            list($lngX, $latY) = DituUtils::getCoordinates();
            //Step 2: Get PostId or PostName
            list($post, $type) = DituUtils::getWriteBackParams();
            //Step 3: Get Pop Window Params
            $pop  = DituUtils::getPopWindowParam();
            //Step 4: Get Zoom Param
            $zoom = DituUtils::getZoomParam();
            //Step 5: Display the DituMark Page
            $templateVar = array(
                'tpl'     => DituConfig::DEFAULT_MARK_TEMPLATE,
                'ispop'   => ($pop === false) ? false : true,
                'width'   => ($pop === false) ? '' : $pop['width'],
                'height'  => ($pop === false) ? '' : $pop['height'],
                'level'   => ($pop === false) ? '' : $pop['zoom'],
                'grand'   => (isset($_GET['grand']) || isset($_GET['show'])) ? true : false,
                'show'    => isset($_GET['show'])   ? true : false,
                'nomark'  => isset($_GET['nomark']) ? true : false,
                'xiaoqu'  => isset($_GET['xiaoqu']) ? true : false,
                'zoom'    => $zoom,
                'post'    => $post,
                'type'    => $type,
                'lngX'    => $lngX,
                'latY'    => $latY,
                'city'    => isset($_GET['city'])  ? $_GET['city']  : false,
                'address' => isset($_GET['address']) ? $_GET['address'] : DituConfig::DEFAULT_ADDRESS,
                'title'   => isset($_GET['title']) ? true : false,
                'fang135' => isset($_GET['fang135']) ? true : false,
            	'valid'   => isset($_GET['valid']) ? true : false,
            	'small'   => isset($_GET['small']) ? true : false,
            );
            $this->displayPage($templateVar);
        }
        /**
         * Show the DituMark Page
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
         * the Construct of the DituMark Class
         *
         * @param  void
         * @return void
         */
        public function __destruct()
        {
            //nothing to do
        }
    }
