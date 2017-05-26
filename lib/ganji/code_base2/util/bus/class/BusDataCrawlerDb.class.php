<?php
    /**
     * 公交数据抓取访问处理类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusDb.class.php');
    
    class BusDataCrawlerDb extends BusDb
    {
        /**
         * Whether the Line Id is in the DB or not
         *
         * @param  string $lineId
         * @return boolean
         */
        public function isExistLineId($lineId)
        {
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX,
                BusDbConfig::DEFAULT_COLUMNS,
                array(array('line_id', SqlBuilder::FILTER_EQUAL, $lineId))
            );
            if ($res !== false && count($res) > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        /**
         * Set the Attribute Info of the Line
         *
         * @param  array $attribute
         * @return mixed
         */
        public function setAttributeOfLine($attribute)
        {
            $this->sql = SqlBuilder::buildInsertSql($this->city . BusDbConfig::DEFAULT_TABLE_ATTRIBUTE_SUFFIX, $attribute);
            return $this->execute();
        }
        /**
         * Set the Basic Info of the Line
         * 
         * @param  array $basic
         * @return boolean
         */
        public function setBasicOfLine($basic)
        {
            $this->sql = SqlBuilder::buildInsertSql($this->city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX, $basic);
            return $this->execute();
        }
        /**
         * Set the Stations of the Line
         *
         * @param  array $stations
         * @return boolean
         */
        public function setStationOfLine($stations)
        {
            foreach($stations as $station)
            {
                $this->sql = SqlBuilder::buildInsertSql($this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX, $station);
                if ($this->execute() === false)
                {
                    return false;
                }
            }
            return true;
        }
    }