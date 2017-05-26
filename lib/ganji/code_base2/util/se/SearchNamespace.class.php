<?php
/**
 * @see SearchQueryBuilder
 */
require_once(dirname(__FILE__) . '/SearchQueryBuilder.class.php');

class SearchConstNamespace {

    const TYPE_SPHINX   = 1;
    /**
     * 搜索引擎类型 Xapian
     * @var int
     */
    const TYPE_XAPIAN   = 3;

    /**
     *
     * Enter description here ...
     * @var unknown_type
     */
    const TYPE_MYSQL_WHERE  = 4;

    /**
     * 按相关度降序
     * @var int
     */
    const ORDER_BY_RELEVANCE    = 0;

    /**
     * 按属性降序排列
     * @var int
     */
    const ORDER_BY_DESC = 1;

    /**
     * 按属性升序排列
     * @var int
     */
    const ORDER_BY_ASC  = 2;

    /**
     * 先按时间段降序,再按相关度降序
     * @var int
     */
    const ORDER_BY_TIME_SEGMENTS = 3;
}

class SearchNamespace {

    /**
     * 是否是第一页
     *
     * @var bool
     */
    protected static $IS_FIRST_PAGE = false;

    /**
     * createSearchEngine 为静态方法，用于创建搜索引擎对象
     * @param int $type 索引类型
     * @param array $config 索引配置
     * @return Xapian 返回搜索引擎对象
     */
    static function createSearchHandle($type , $config) {
        if(!$type || !$config)
            return false;

        switch ($type) {
            case SearchConstNamespace::TYPE_XAPIAN:
                require_once(dirname(__FILE__) . '/xapian/Xapian.class.php');
                return new Xapian($config);
            default:
                require_once(dirname(__FILE__) . '/xapian/Xapian.class.php');
                return new Xapian($config);
        }
    }

    /**
     * 查询数据，返回一个查询id
     * @param Xapian $handle
     * @param SearchQueryBuilder
     * @return int 查询id
     */
    static function query($handle , $queryString) {
        if (!self::_checkHandle($handle)) return 0;

        self::$IS_FIRST_PAGE = strpos($queryString, '[L:0:');
        return $handle->query($queryString);
    }

    /**
     * 通过查询id，获得数据
     * @param Xapian $handle
     * @param int $query_id
     * @return array
     */
    static function getResultByQueryId($handle, $query_id) {
        if (!self::_checkHandle($handle)) {
            return array(null, 0);
        }

        if (empty($query_id)) {
            return array(null, 0);
        }
        $ret    = $handle->getResultByQueryId($query_id);
        if (!$ret) {
            return array(null, 0);
        }
        $data = $ret['data'];
        $count = $ret['count'];
        // 如果是第一页并且有高级置顶帖，需要做轮播处理
        /*
        if (self::$IS_FIRST_PAGE) {
            do {
                $post = isset($data[0]) ? $data[0] : null;
                if (!$post) {
                    break;
                }
                if (!isset($post['post_type']) || !isset($post['listing_status']) || !isset($post['top_info'])) {
                    break;
                }
                require_once CODE_BASE2 . '/app/sticky/StickyNamespace.class.php';
                $gao_ji_zhi_ding_post = array();
                $gao_ji_start = $gao_ji_end = -1;
                foreach ($data as $key => $post) {
                    if (isset($post['post_type']) && isset($post['listing_status']) && isset($post['top_info']) &&
                    $post['post_type'] == 10 && StickyNamespace::getStickyTypeByPostInfo($post['top_info'], $post['listing_status'], $post['post_type'])) {
                        if ($gao_ji_start == -1) {
                            $gao_ji_start = $gao_ji_end = $key;
                        } else {
                            $gao_ji_end++;
                        }
                        $gao_ji_zhi_ding_post[] = $post;
                    }
                }
                $gao_ji_zhi_ding_count = count($gao_ji_zhi_ding_post);
                if ($gao_ji_zhi_ding_count > 1 && $gao_ji_start != -1) {
                    shuffle($gao_ji_zhi_ding_post);
                    if ($gao_ji_start == 0) {
                        $data = array_merge($gao_ji_zhi_ding_post, array_slice($data, $gao_ji_zhi_ding_count));
                    } else {
                        $begin_post_list = array_slice($data, 0, $gao_ji_start);
                        $end_post_list = array_slice($data, $gao_ji_end+1);
                        $data = array_merge($begin_post_list, $gao_ji_zhi_ding_post, $end_post_list);
                    }
                }
            } while(false);
        }
        */
        return array($data, $count);
    }

    /**
     * 查询类别下的小类以及条数
     * @param object $handle
     * @param string $queryString
     * @return array
     */
    static function getSearchCount($handle, $queryString)
    {
        if (!self::_checkHandle($handle)) return false;
        return $handle->searchCount($queryString);
    }

    /**
     * 自定义sleep时间间隔
     * @param Xapian $handle
     * @param array $sleep_time
     * @return boolean
     */
    static function setSleepTime($handle, $sleep_time) {
        if (!self::_checkHandle($handle)) return false;
        return $handle->setSleepTime($sleep_time);
    }

    /**
     * @brief 检查handle
     * @param[in] handle $handle, xapian对象
     * @return boolean true|成功, false|失败
     */
    private static function _checkHandle($handle) {
        if (!is_object($handle)) {
            if( class_exists('Logger') ) {
                Logger::logWarn( sprintf("handle Error",var_export($handle, true)), 'SearchNamespace');
            }
            return false;
        }
        return true;
    }

}