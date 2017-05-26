<?php
    /**
     * 公交搜素建议数据访问处理类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Bus_BusSearch
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */

    //Include Common Files
    require_once(SITE_PATH . '/libs/hanzitopinyin/ChineseStringToPinYin.class.php');
    //Include Class Files
    require_once(APP_PATH . '/bus/class/BusDb.class.php');
    
    class BusSuggestionDb extends BusDb
    {
        /**
         * Get the Line Name Suggest
         *
         * @param  string $input
         * @return mixed
         */
        public function getLineSuggestion($input, $count)
        {
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_LINE_SUFFIX,
                BusSearchConfig::SUGGEST_LINE_COLUMNS,
                array(array('line_name', SqlBuilder::FILTER_LIKE, array($input, SqlBuilder::WILDCARD_RIGHT))),
                array(0, $count),
                array('line_name' => SqlBuilder::SORT_ASC)
            );
            if ($res !== false)
            {
                $lineNames = array();
                foreach ($res as $lineName)
                {
                    $lineNames[] = $lineName['line_name'];
                }
                $res = json_encode($lineNames);
            }
            return $res;
        }
        /**
         * Get the Station Name Suggest
         *
         * @param  string $input
         * @return mixed
         */
        public function getStationSuggestion($input, $count)
        {
            $pinyin  = new ChineseStringToPinYin($input, '');
            $inputpy = $pinyin->getPinyinString();
            if (strcmp($input, $inputpy) == 0)
            {
                $where = array(array('station_spell', SqlBuilder::FILTER_LIKE, array(strtolower($pinyin->getPinyinString()), SqlBuilder::WILDCARD_RIGHT)));
            }
            else
            {
                $where = array(array('station_name', SqlBuilder::FILTER_LIKE, array($input, SqlBuilder::WILDCARD_RIGHT)));
            }
            $res = $this->getDataFromDb(
                $this->city . BusDbConfig::DEFAULT_TABLE_STATION_SUFFIX,
                BusSearchConfig::SUGGEST_STATION_COLUMNS,
                $where,
                array(0, $count),
                array('station_name' => SqlBuilder::SORT_ASC)
            );
            if ($res !== false)
            {
                $stationNames = array();
                foreach ($res as $stationName)
                {
                    $stationNames[] = $stationName['station_name'];
                }
                $res = json_encode($stationNames);
            }
            return $res;
        }
    }