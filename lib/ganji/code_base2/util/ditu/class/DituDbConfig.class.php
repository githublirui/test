<?php
    /**
     * 地图数据库配置类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * 
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    class DituDbConfig
    {
        /**
         * the Id of the GetAll in Data Set
         *
         * @staticvar integer
         */
        const DATASET_GETALL                 = 0;
        /**
         * the Id of the GetRow in Data Set
         *
         * @staticvar integer
         */        
        const DATASET_GETROW                 = 1;
        /**
         * the Id of the GetOne in Data Set
         *
         * @staticvar integer
         */        
        const DATASET_GETONE                 = 2;        
        /**
         * All the Values of the Data Set
         *
         * @staticvar array
         */
        public static $ALL_DATASET           = array(
            self::DATASET_GETALL => 'getAll',
            self::DATASET_GETROW => 'getRow',
            self::DATASET_GETONE => 'getOne',
        );
        /**
         * Default Value of the Columns
         *
         * @staticvar string
         */
        const DEFAULT_COLUMNS                = '*';
        /**
         * Default Value of the Database Name
         *
         * @staticvar string
         */
        const DEFAULT_DATABASE_NAME          = 'beijing';
        /**
         * Default Value of the Data Set
         *
         * @staticvar string
         */
        const DEFAULT_DATASET                = 'getAll';
        /**
         * Default Value of the Filters
         *
         * @staticvar array
         */
        const DEFAULT_FILTERS                = null;
        /**
         * Default Value of the Group By
         *
         * @staticvar array
         */
        const DEFAULT_GROUPBY                = null;
        /**
         * Default Value of the Limit
         *
         * @staticvar array
         */
        const DEFAULT_LIMIT                  = null;
        /**
         * Default Value of the Orders
         * 
         * @staticvar array
         */
        const DEFAULT_ORDERS                 = null;
        /**
         * Default Value of the Name of the Table
         *
         * @staticvar string
         */
        const DEFAULT_TABLE_NAME             = 'ditu_traffic';
    }