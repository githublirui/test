<?php
    /**
     * 公交查询数据访问处理类
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
    
    class BusSearchDb extends BusDb
    {
        /**
         * Get the Line Attribute by Line Id
         *
         * @param  string $lineId
         * @return mixed
         */
        public function getLineAttributeByLineId($lineId)
        {
            $res  = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_ATTRIBUTE_SUFFIX,
                BusSearchConfig::LINE_ATTRIBUTE_COLUMNS,
                array(array('line_id', SqlBuilder::FILTER_EQUAL, $lineId))
            );
            $line = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX,
                BusSearchConfig::LINE_ATTRIBUTE_COLUMNS,
                array(array('line_id', SqlBuilder::FILTER_EQUAL, $lineId))
            );
            if ($res !== false && $line !== false && count($line == 1) && count($res) == 1)
            {
                $res[0]['start_time'] = substr($res[0]['start_time'], 0, 2) . ':' . substr($res[0]['start_time'], 2, 2);
                $res[0]['end_time']   = substr($res[0]['end_time'], 0, 2)   . ':' . substr($res[0]['end_time'], 2, 2);
                $res[0]['line_name']  = BusUtils::filterLineName($line[0]['line_name']);
                return $res[0];
            }
            else
            {
                return false;
            }
        }
        /**
         * Get the Line Id By Line Name
         *
         * @param  string $lineName
         * @return mixed
         */
        public function getLineIdByName($lineName)
        {
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX,
                BusDbConfig::DEFAULT_COLUMNS,
                array(array('line_name', SqlBuilder::FILTER_LIKE, array($lineName, SqlBuilder::WILDCARD_BOTH))),
                array(),
                array(),
                array(),
                BusDbConfig::$ALL_DATASET[BusDbConfig::DATASET_GETALL]
            );
            if ($res === false || empty($res))
            {
                return false;
            }
            if (count($res) == 1)
            {
                return $res[0]['line_id'];
            }
            else
            {
                foreach ($res as $key => $value)
                {
                    if (BusUtils::filterLineName($value['line_name']) == $lineName)
                    {
                        return $value['line_id'];
                    }
                }
            }
            return false;
        }
        /**
         * Get the Line Stations by Line Id
         *
         * @param  string $lineId
         * @return mixed
         */
        public function getLineStationsByLineId($lineId)
        {
            return $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                BusSearchConfig::LINE_STATIONS_COLUMNS,
                array(array('line_id', SqlBuilder::FILTER_EQUAL, $lineId)),
                array(),
                array('station_num' => SqlBuilder::SORT_ASC)
            );
        }
        /**
         * Get the Search
         *
         * @param  string $tableSuffix
         * @param  string $keyword
         * @return mixed
         */
        public function getSearch($tableSuffix, $keyword, $transfer = false)
        {
            switch ($tableSuffix)
            {
                case BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX:
                    return $this->getDataFromDb(
                        $this->city . $tableSuffix,
                        BusSearchConfig::SEARCH_COLUMNS,
                        array(array('line_name', SqlBuilder::FILTER_LIKE, array($keyword, SqlBuilder::WILDCARD_RIGHT))),
                        array(),
                        array('line_name' => SqlBuilder::SORT_ASC)
                    );
                    break;
                case BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX:
                    if (!$transfer)
                    {
                        $res = $this->getDataFromDb(
                            $this->city . $tableSuffix,
                            BusSearchConfig::STATION_LIST_COLUMNS,
                            array(array('station_name', SqlBuilder::FILTER_LIKE, array($keyword, SqlBuilder::WILDCARD_BOTH))),
                            array(),
                            array('station_name' => SqlBuilder::SORT_ASC)
                        );
                    }
                    else
                    {
                        $this->error = '';
                        $this->sql   = sprintf(BusDbConfig::SQL_TRANSFER, $this->city, $this->city, $this->city, '%' . $keyword . '%');
                        $res         = $this->getAll();
                    }
                    if ($res !== false)
                    {
                        $nameLists = array();
                        $stations  = array();
                        $fullmatch = array();
                        foreach ($res as $element)
                        {
                            if (array_search($element['station_name'], $nameLists) === false)
                            {
                                if ($element['station_name'] == $keyword)
                                {
                                    $fullmatch[] = $element;
                                }
                                else
                                {
                                    $stations[]  = $element;
                                }
                                $nameLists[] = $element['station_name'];
                            }
                        }
                        return array_merge($fullmatch, $stations);
                    }
                    break;
                default:
                    return false;
                    break;
            }
        }
        /**
         * Get the Station by Line Id and Station Num
         *
         * @param  string  $lineId
         * @param  integer $stationNum
         * @return mixed
         */
        public function getStation($lineId, $stationNum)
        {
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                BusSearchConfig::STATION_COLUMNS,
                array(array('line_id', SqlBuilder::FILTER_EQUAL, $lineId), array('station_num', SqlBuilder::FILTER_EQUAL, $stationNum))
            );
            if ($res !== false && count($res) == 1)
            {
                $stationName = $res[0]['station_name'];
                $lineRes = $this->getDataFromDb(
                    $this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                    BusSearchConfig::STATION_LINE_COLUMNS,
                    array(array('station_name', SqlBuilder::FILTER_EQUAL, $stationName)),
                    array(),
                    array('line_id' => SqlBuilder::SORT_ASC)
                );
                if ($lineRes !== false && count($lineRes) > 0)
                {
                    $lineIds = array();
                    foreach ($lineRes as $item)
                    {
                        $lineIds[] = $item['line_id'];
                    }
                    list($lngX, $latY) = BusUtils::decodeMapABCCoordinate(array($res[0]['lngX'], $res[0]['latY']));
                    $houses            = GeoHelper::getNearbyRentHouse(HttpHandler::getDomain(), $latY, $lngX, BusSearchConfig::STATION_HOUSE_RANGE, null, BusSearchConfig::STATION_HOUSE_LIMIT);
                    return array($stationName, $lineIds, $houses);
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        /**
         * Get the Station Id By Station Name
         *
         * @param  string $stationName
         * @return mixed
         */
        public function getStationIdByName($stationName)
        {
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                BusDbConfig::DEFAULT_COLUMNS,
                array(array('station_name', SqlBuilder::FILTER_EQUAL, $stationName)),
                array(),
                array(),
                array(),
                BusDbConfig::$ALL_DATASET[BusDbConfig::DATASET_GETROW]
            );
            if ($res === false || empty($res))
            {
                return false;
            }
            else
            {
                return $res['line_id'] . BusConfig::DEFAULT_CACHE_SPLIT_CHAR . $res['station_num'];
            }
        }
    	/**
    	 * Set Hot Line Count
    	 * 
    	 * @param  string $lineId
    	 * @return boolean
    	 */
        public function setHotLine($lineId)
        {
        	if (empty($lineId))
        	{
        		return false;
        	}
        	$this->sql = "UPDATE "
        	           . $this->city . BusDbConfig::DEFAULT_TABLE_HOT_LINE_SUFFIX . " "
        	           . "SET count = count + 1 "
        	           . "WHERE line_id = '{$lineId}'";
        	return $this->execute();
        }
    }