<?php
    /**
     * 公交简称数据访问处理类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusDb.class.php');
    require_once(APP_PATH . '/bus/class/BusUtils.class.php');

    class BusAssortKeyDb extends BusDb
    {
        /**
         * Get the Assort Keys
         *
         * @param  string $tableSuffix
         * @return mixed
         */
        public function getAssortKeys($tableSuffix)
        {
            $res = $this->getDataFromDb(
                $this->city . $tableSuffix,
                BusSearchConfig::ASSORT_COLUMNS,
                array(),
                array(),
                array('assort_key' => SqlBuilder::SORT_ASC)
            );
            if ($res !== false)
            {
                $assortKeys = array();
                foreach ($res as $assortKey)
                {
                    $assortKeys[] = $assortKey['assort_key'];
                }
                $res = $assortKeys;
            }
            return $res;
        }
        /**
         * Get Line Lists by Assort Key
         *
         * @param  string $assortKey
         * @return mixed
         */
        public function getLineListsByAssortKey($assortKey)
        {
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX,
                BusSearchConfig::LINE_LIST_COLUMNS,
                array(array('assort_key', SqlBuilder::FILTER_EQUAL, $assortKey)),
                array(),
                array('line_name' => SqlBuilder::SORT_ASC)
            );
            if ($res === false)
            {
                return false;
            }
            $duplicate = array();
            $distinct  = array();
            foreach ($res as $key => $line)
            {
                $res[$key]['line_name'] = BusUtils::filterLineName($line['line_name']);
                if (array_search($res[$key]['line_name'], $duplicate) === false)
                {
                    $distinct[]  = $res[$key];
                    $duplicate[] = $res[$key]['line_name'];
                }
            }
            return $distinct;
        }
        /**
         * Get Station Lists by Assort Key
         *
         * @param  string $assortKey
         * @return mixed
         */        
        public function getStationListsByAssortKey($assortKey)
        {
            $res =  $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                BusSearchConfig::STATION_LIST_COLUMNS,
                array(array('assort_key', SqlBuilder::FILTER_EQUAL, $assortKey)),
                array(),
                array('station_name' => SqlBuilder::SORT_ASC)
            );
            if ($res !== false)
            {
                $nameLists = array();
                $stations  = array();
                foreach ($res as $element)
                {
                    if (array_search($element['station_name'], $nameLists) === false)
                    {
                        $stations[]  = $element;
                        $nameLists[] = $element['station_name'];
                    }
                }
                $res = $stations;
            }
            return $res;
        }
    }