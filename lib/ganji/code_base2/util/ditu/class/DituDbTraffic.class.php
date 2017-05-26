<?php
    /**
     * 地图详情页公交查询数据库类
     * 
     * @author    yixiao <yixiao@staff.ganji.com>
     * 
     * @category  Ganji_V3
     * @package   Ganji_V3_Apps_Ditu
     * @version   1.0.0.0
     * @copyright Copyright (c) 2005-2009 GanJi Inc. (http://www.ganji.com)
     */
    
    //Include Class Files
    require_once(APP_PATH . '/ditu/class/DituDb.class.php');
    
    class DituDbTraffic extends DituDb
    {
        public function getData($key, $post_id, $type)
        {
            $res = $this->getDataFromDb(
                DituDbConfig::DEFAULT_TABLE_NAME,
                DituDbConfig::DEFAULT_COLUMNS,
                array(
                    array('id',      SqlBuilder::FILTER_EQUAL, $key),
                    array('post_id', SqlBuilder::FILTER_EQUAL, $post_id),
                    array('type',    SqlBuilder::FILTER_EQUAL, $type),
                )
            );
            if ($res !== false && count($res) == 1)
            {
                return unserialize($res[0]['data']);
            }
            return false;
        }
        public function setData($key, $post_id, $type, $content)
        {
            //让地图直接使用memcached，不使用db了。
            return false;
            $post_id    = (int) $post_id;
            $res = $this->getDataFromDb(
                DituDbConfig::DEFAULT_TABLE_NAME,
                DituDbConfig::DEFAULT_COLUMNS,
                array(
                    array('post_id', SqlBuilder::FILTER_EQUAL, $post_id),
                    array('type',    SqlBuilder::FILTER_EQUAL, $type),
                )
            );
            if ($res !== false && count($res) == 1)
            {
                $this->sql = SqlBuilder::buildUpdateSql(
                    DituDbConfig::DEFAULT_TABLE_NAME,
                    array(
                        'id'         => $key,
                        'lastmodify' => time(),
                        'data'       => $content,
                    ),
                    array(
                        array('post_id', SqlBuilder::FILTER_EQUAL, $post_id),
                        array('type',    SqlBuilder::FILTER_EQUAL, $type),
                    )
                );
                $this->execute();
            }
            else if ($res !== false && count($res) == 0)
            {
                $NOW       = time();
                $this->sql = SqlBuilder::buildInsertSql(
                    DituDbConfig::DEFAULT_TABLE_NAME,
                    array(
                        'id'         => $key,
                        'post_at'    => $NOW,
                        'lastmodify' => $NOW,
                        'data'       => $content,
                        'post_id'    => $post_id,
                        'type'       => $type,
                    )
                );
                $this->execute();
            }
        }
    }
