<?PHP
/**
 * @brief 构造cache对象
 * @package              ganji.cache
 * @author               yangyu
 * @file                 $RCSfile: Curl.class.php,v $
 * @version              $Revision: 50890 $
 * @modifiedby           $Author: yangyu $
 * @lastmodified         $Date: 2011-03-11 17:29:21 +0800 (五, 2011-03-11) $
 * @copyright            Copyright (c) 2011, ganji.com
 */

class CacheNamespace
{
    /** APC */
    const MODE_APC = 1;

    /** memcache */
    const MODE_MEMCACHE = 2;

    /** DIST_MEMCACHE */
    const MODE_DIST_MEMCACHE = 3;
    
    private static $_HANDLE_ARRAY   = array();    
    
    private static function _getHandleKey($params) {
        ksort($params);
        return md5(implode('_' , $params));
    }
    
    /**
     * @brief 创建一个cache 对象
     *
     * @param $mode int cache的类型
     * @param $servers array
     * @param array $params  memcached的 一些扩展选项, 默认为空, 
     *     如果使用一致性hash, $params['distribution_consistent'] = true
     *     设置发送和接收超时 默认1秒
     *     $params['OPT_SEND_TIMEOUT'] = 1000;// ms
     *     $params['OPT_RECV_TIMEOUT'] = 1000;// ms
     * @see CacheBackingStore::MEMCACHE         memcache
     * @see CacheBackingStore::LOCAL_FILE       local_file
     * @see CacheMemcache
     * @see CacheFile
     *
     * @return  MemCacheAdapter|ApcCacheAdapter|DistMemCacheAdapter
     */
    public static function createCache($mode, $servers=array(), $params = array()) {
        
        $handle_key = self::_getHandleKey(array(
            'mode'      => $mode,
            'servers'   => serialize($servers),
            'params'    => serialize($params),
        ));

        if (isset(self::$_HANDLE_ARRAY[$handle_key])) {
            return self::$_HANDLE_ARRAY[$handle_key];
        }
        
        switch($mode) {
            case self::MODE_MEMCACHE:
                require_once dirname(__FILE__) . '/adapter/MemCacheAdapter.class.php';
                $objCache = new MemCacheAdapter($servers, $params);
                break;
            case self::MODE_APC:
                require_once dirname(__FILE__) . '/adapter/ApcCacheAdapter.class.php';
                $objCache = new ApcCacheAdapter();
                break;
            case self::MODE_DIST_MEMCACHE:
                require_once dirname(__FILE__) . "/adapter/DistMemCacheAdapter.class.php";
                $objCache = new DistMemCacheAdapter($servers, $params);
                break;
            default:
                require_once dirname(__FILE__) . '/adapter/MemCacheAdapter.class.php';
                $objCache = new MemCacheAdapter($servers, $params);
                break;
        }
        
        self::$_HANDLE_ARRAY[$handle_key]    = $objCache;
        
        return $objCache;
    }

    public static function encodeKey($key) {
        return urlencode($key);
    }
}

