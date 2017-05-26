<?php
    /**
     * 公交查询配置类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusConfig.class.php');    
    
    class BusSearchConfig extends BusConfig
    {
        /**
         * Debug On/Off
         * 
         * @staticvar boolean
         */
        const SEARCH_DEBUG              = false;
        /**
         * the Id of the Line Type in $ALL_SEARCH_TYPES
         *
         * @staticvar integer
         */
        const SEARCH_TYPE_LINE          = 0;
        /**
         * the Id of the Station Type in $ALL_SEARCH_TYPES
         *
         * @staticvar integer
         */
        const SEARCH_TYPE_STATION       = 1;
        /**
         * the Id of the Transfer Type in $ALL_SEARCH_TYPES
         *
         * @staticvar integer
         */
        const SEARCH_TYPE_TRANSFER      = 2;
        /**
         * Default Value of All the Search Types
         *
         * @staticvar array
         */
        public static $ALL_SEARCH_TYPES = array(
            0 => 'line',
            1 => 'station',
            2 => 'transfer',
        );
        /**
         * the Values of the Links on the Right
         *
         * @staticvar array
         */
        public static $RIGHT_LINKS      = array(
            array('name' => '租房',   'link' => 'fang1'),
            array('name' => '二手房',  'link' => 'fang5'),
            array('name' => '交友',   'link' => 'jiaoyou'),
            array('name' => '二手车',  'link' => 'ershouche'),
            array('name' => '招聘',   'link' => 'zhaopin'),
            array('name' => '兼职',   'link' => 'jianzhi'),
            array('name' => '家教',   'link' => 'jiajiao'),
            array('name' => '火车票',  'link' => 'piao'),
            array('name' => '婚纱摄影', 'link' => 'hunshasheying'),
            array('name' => '装修',   'link' => 'zhuangxiu'),
            array('name' => '地图',   'link' => 'ditu'),
            array('name' => '天气',   'link' => 'weather'),
        );
        /**
         * Limit of the Suggest
         *
         * @staticvar array
         */
        public static $SUGGEST_LIMIT    = array(
            0, 10
        );
        /**
         * Default Value of the Columns of the Line Assort
         *
         * @staticvar string
         */
        const ASSORT_COLUMNS            = 'distinct(assort_key) as assort_key';
        /**
         * Default Value of the Assort Infix
         *
         * @staticvar string
         */
        const CACHE_ASSORT_INFIX        = 'ASSORT';
        /**
         * Default Value of the Assort Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_ASSORT_EXPIRE       = 86400;
        /**
         * Default Value of the Line Attribute Cache Infix
         *
         * @staticvar string
         */
        const CACHE_ATTRIBUTE_INFIX     = 'ATTRIBUTE';
        /**
         * Default Value of the Line Attribute Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_ATTRIBUTE_EXPIRE    = 86400;
        /**
         * Default Value of the Line Stations Cache Infix
         *
         * @staticvar string
         */
        const CACHE_STATIONS_INFIX      = 'STATIONS';
        /**
         * Default Value of the Line Stations Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_STATIONS_EXPIRE     = 86400;
        /**
         * Default Value of the Bus Station Cache Path
         * 
         * @staticvar string
         */
        const CACHE_STATIONS_PATH       = 'busStation';
        /**
         * Default Value of the List Infix
         *
         * @staticvar string
         */        
        const CACHE_LIST_INFIX          = 'LIST';
        /**
         * Default Value of the List Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_LIST_EXPIRE         = 86400;
        /**
         * Default Value of the Search Cache Infix
         *
         * @staticvar string
         */
        const CACHE_SEARCH_INFIX        = 'SEARCH';
        /**
         * Default Value of the Search Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_SEARCH_EXPIRE       = 86400;
        /**
         * Default Value of the Suggest Cache Infix
         *
         * @staticvar string
         */
        const CACHE_SUGGEST_INFIX       = 'SUGGEST';
        /**
         * Default Value of the Suggest Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_SUGGEST_EXPIRE      = 86400;
        /**
         * Default Value of the Transfer Cache Infix
         *
         * @staticvar string
         */
        const CACHE_TRANSFER_INFIX      = 'TRANSFER';
        /**
         * Default Value of the Transfer Cache Expire
         *
         * @staticvar integer
         */
        const CACHE_TRANSFER_EXPIRE     = 86400;
        /**
         * Default Value of the Cookie Name
         *
         * @staticvar string
         */
        const DEFAULT_COOKIE_DOMAIN     = '.ganji.com';
        /**
         * Default Value of the Cookie Expire
         *
         * @staticvar integer
         */
        const DEFAULT_COOKIE_EXPIRE     = 0;
        /**
         * Default Value of the Cookie Name
         *
         * @staticvar string
         */
        const DEFAULT_COOKIE_NAME       = 'bus_search_keyword';
        /**
         * Default Value of the Cookie Name
         *
         * @staticvar string
         */
        const DEFAULT_COOKIE_VAILDNAME  = 'bus_search_keyword_valid';
        /**
         * Default Value of the Cookie Path
         *
         * @staticvar string
         */
        const DEFAULT_COOKIE_PATH       = '/bus/';
        /**
         * Default Value of the Search Keyword
         *
         * @staticvar boolean
         */
        const DEFAULT_SEARCH_KEYWORD    = false;
        /**
         * Default Value of the Iframe Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_IFRAME   = '/templates/bus/bus_iframe.htm';
        /**
         * Default Value of the Index Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_INDEX    = '/templates/bus/bus_city.htm';
        /**
         * Default Value of the Line Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_LINE     = '/templates/bus/bus_line.htm';
        /**
         * Defaulte Value of the List Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_LIST     = '/templates/bus/bus_list_%s.htm';
        /**
         * Default Value of the No Result Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_NORESULT = '/templates/bus/bus_noresult.htm';
        /**
         * Default Value of the Bus Show Transfer Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_SHOW     = '/templates/bus/bus_show_transfer.htm';
        /**
         * Default Value of the Station Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_STATION  = '/templates/bus/bus_station.htm';        
        /**
         * Default Value of the Bus Transfer Template
         *
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_TRANSFER = '/templates/bus/bus_transfer.htm';
        /**
         * Default Value of the Update Template
         * 
         * @staticvar string
         */
        const DEFAULT_TEMPLATE_UPDATE   = '/apps/update.htm';
        /**
         * Default Value of the Url of the Line
         *
         * @staticvar string
         */
        const DEFAULT_URL_LINE          = '/bus/line/%s.html';
        /**
         * Default Value of the Url of the Station
         *
         * @staticvar string
         */        
        const DEFAULT_URL_STATION       = '/bus/station/%s_%s.html';
        /**
         * Defaulte Value of the Columns of the Line
         *
         * @staticvar string
         */
        const LINE_ATTRIBUTE_COLUMNS    = '*';
        /**
         * Default Value of the Columns of the Line List
         *
         * @staticvar string
         */        
        const LINE_LIST_COLUMNS         = 'line_name, line_id';
        /**
         * Default Value of the Columns of the Line Stations
         *
         * @staticvar string
         */
        const LINE_STATIONS_COLUMNS     = 'line_id, station_name, station_num, lngX, latY';
        /**
         * Default Value of the Columns of the Search
         *
         * @staticvar string
         */
        const SEARCH_COLUMNS            = '*';
        /**
         * Default Value of the Columns of the Station List
         *
         * @staticvar string
         */
        const STATION_LIST_COLUMNS      = 'station_name, line_id, station_num';
        /**
         * Default Value of the Columns of the Station
         *
         * @staticvar string
         */
        const STATION_COLUMNS           = 'station_name, lngX, latY';
        /**
         * Default Value of the Columns of the Station Line
         *
         * @staticvar string
         */
        const STATION_LINE_COLUMNS      = 'distinct(line_id) as line_id';
        /**
         * Default Value of the Columns of the Line Suggest
         *
         * @staticvar string
         */
        const SUGGEST_LINE_COLUMNS      = 'line_name';
        /**
         * Default Value of the Columns of the Station Suggest
         *
         * @staticvar string
         */
        const SUGGEST_STATION_COLUMNS   = 'distinct(station_name) as station_name';
        /**
         * Default Value of the Transfer Station Columns
         *
         * @staticvar string
         */
        const TRANSFER_STATION_COLUMNS  = '.*, line_name';
        /**
         * Default Value of the Transfer Fastest Type
         *
         * @staticvar integer
         */   
        const TRANSFER_TYPE_FASTEST     = 0;
        /**
         * Default Value of the Transfer Save Money Type
         *
         * @staticvar integer
         */           
        const TRANSFER_TYPE_SAVEMONEY   = 1;
        /**
         * Default Value of the Transfer Switch Less Type
         *
         * @staticvar integer
         */           
        const TRANSFER_TYPE_SWITCHLESS  = 2;
        /**
         * Default Value of the Range Round the Station
         *
         * @staticvar integer
         */
        const STATION_HOUSE_RANGE       = 3000;
        /**
         * Default Value of the House Count Round the Station
         *
         * @staticvar integer
         */
        const STATION_HOUSE_LIMIT       = 2;
    }