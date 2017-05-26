<?php
/**
 * 跨机房一致的Memcache实现，支持异地一致性清除.
 * @package              ganji\base\cache\adapter
 * @author               tailor
 * @file                 $RCSfile: DistMemCacheAdapter.class.php,v $
 * @version              $Revision: $
 * @modifiedby           $Author: $
 * @lastmodified         $Date: $
 * @copyright            Copyright (c) 2012, ganji.com
 *
 */

require_once dirname(__FILE__) . '/MemCacheAdapter.class.php';
require_once GANJI_CONF . '/MemcacheConfig.class.php';
require_once CODE_BASE2 . '/util/queue/MemcacheQueueConnection.class.php';
require_once CODE_BASE2 . '/util/profile/statsd.php';

/**
 * 跨机房一致的Memcache实现，支持异地一致性清除.
 * 本模块主要解决下面3个问题：
 *
 * 1. 不同应用的KEY冲突.
 *
 * 2. 分布式系统中，实现cache的一致性.
 *
 * 3. 对不同应用的cache使用效果进行统计.
 *
 * 实现：
 *
 * a) 首先，我们需要对memcache的配置进行统一管理，确保同一个应用的参数（服务器，其它选项）必须保持完全一致。
 * 因此我们给每一个应用分配一个唯一的名称，该名称对应了全局配置文件中的一组配置。在代码中，只要提供应用的名称，
 * 即可创建出唯一的实例。
 *
 * 比如：CacheNamespace::createCache(CacheNamespace::MODE_DIST_MEMCACHE, array('name'=>'post_detail'));
 *
 * b) 对不同应用，模块会自动在所有key之前增加prefix（包括multi操作）。所有的prefix对于使用者来说是完全透明的。
 * 这个prefix能够确保不同的应用的item不会产生冲突。
 *
 * c) 对于需要实现分布式一致的应用，执行删除操作时，会自动将key值写入分布式队列，实现远程异地的cache清除。
 *
 * d) 对各个业务的get命中率进行实时统计。
 *
 * To change this template use File | Settings | File Templates.
 * DistMemcacheConfig {
 *  static public $APP_CONFIG = array(
 *  'app1' => array( 'servers' => Memcached服务器配置,
 *                   'params'  => Memcached选项,
 *                   'is_dist' => 是否需要分布式删除,
 *                   'prefix'  => 自动添加的KEY前缀 )
 *   );
 * }
 * @package              ganji\base\cache\adapter
 */
class DistMemCacheAdapter extends MemCacheAdapter {
    /**
     * @var MemcacheQueueConnection|null 传输KEY值的队列，在分布式模式下有效
     * @access private
     */
    protected $distQueue    = null;
    /**
     * @var bool|false 实例是否工作在分布式模式
     * @access private
     */
    protected $is_dist   = false;
    /**
     * @var string|mc.hit 统计图表的前缀
     * @access private
     */
    private static $STATSD_KEY_PREFIX = "mc.hit";

    /**
     * 构造函数.
     * @param array 目前支持一个字段，name:String name of the instance
     * @param array $params Memcached实例的配置参数，参见MemcacheAdapter.class.php
     */
    public function __construct($servers, $params = array() ) {
        $this->name = $servers['name'];

        $real_config = $this->getConfig($this->name);
        if( empty($real_config))
            die("DistMemCacheAdapter::找不到{$this->name}对应的memcache配置项");

        if(     isset($real_config['servers'])
            &&  isset($real_config['params'])
            && isset($real_config['prefix']))
        {
            parent::__construct( $real_config['servers'], $real_config['params']);
            $this->prefix = $real_config['prefix'];
            $this->is_dist = $real_config['is_dist'];
            if( isset( $real_config['stat_ratio']))
                $this->stat_ratio = intval( $real_config['stat_ratio'] );
            else
                $this->stat_ratio = 0;
        }
        else
            die("DistMemCacheAdapter::必须设置servers, params和prefix");

    }

    /**
     * Override MemCacheAdapter的缺省encodeKey，提供带有prefix的Key
     * @param $key
     * @return string
     */
    protected function encodeKey( $key ) {
        $real_key = $this->prefix . ":" . CacheNamespace::encodeKey($key);
        return $real_key;
    }

    /**
     * 清除所有cache。(不可用)
     */
    public function clear() {
        die("clear is not supported and you must redesign your implementation!");
        return;
    }
    /**
     * 删除缓存
     *
     * @param $key string 要删除的key
     *
     * @returns bool 如果成功删除,返回true;如果key对应的value不存在或者不能删除，则返回false.
     */
    public function delete($key)
    {
        if ($this->is_dist) return $this->distDelete($key);

        return parent::delete($key);
    }

    /**
     * 删除分布式Key.
     * 首先删除本地的key，然后将key的信息写入同步队列
     * @param $key 待删除的key
     * @return bool 如果成功删除,返回true;如果key对应的value不存在或者不能删除，则返回false.
     */
    public function distDelete($key)
    {
        if (!$this->connection) return false;

        // write the key to DIST-QUEUE
        if( $this->is_dist ) {
            if( !$this->distQueue ) $this->initDistQueue();
            $dist_info = array( 'k' => $key,
                                't' => time(),
                                'n' => $this->name
                            );
            $this->distQueue->set( json_encode($dist_info) );
        }

        $real_key    = $this->encodeKey($key);
        return $this->connection->delete($real_key);
    }

    /**
     * 一次读取多个items.
     * {@inheritdoc}
     * @param array $keys
     * @return array|mixed|null
     */
    public function readMulti(array $keys)
    {
        if (!$this->connection) return NULL;
        $cas = 0;
        foreach ($keys as $key => $item) {
            $keys[$key] = $this->encodeKey($keys[$key]);
        }
        $data = $this->connection->getMulti($keys, $cas, Memcached::GET_PRESERVE_ORDER);
        if( !empty($data) && !empty($this->prefix) )
            return self::removePrefixForMulti( $data );
        return $data;
    }

    /**
     * 去掉自动添加的KEY前缀，保持接口的一致性
     *
     * @param array $data
     * @return array $out
     * @access private
     */
    private function removePrefixForMulti(array $data ) {
        $prefix_len = strlen($this->prefix)+1;
        $out = array();
        foreach( $data as $key => $item) {
            $newkey = substr($key,$prefix_len);
            $out[$newkey] = $item;
        }
        return $out;
    }


    /**
     * 统计各个业务memcache的命中率
     *
     * @param string memcache 请求的key
     * @param bool 命中true，没有命中false
     */
    protected function stat($key, $hit)
    {
        if( $this->stat_ratio && rand(0, $this->stat_ratio-1) == 0 ) {
            $parts = explode( ':', $key,3 );
            $cat = self::$STATSD_KEY_PREFIX;
            if( $hit ) $v = 1;
            else $v = 0;
            for($i = 0;$i<count($parts)-1;$i++) {
                $cat .= "." . $parts[$i];
                $this->timing($cat, $v, $this->stat_ratio);
            }
        }
    }


    /* the following function can be overrided for test for other purpose */


    /**
     * 实现基于statsd的统计操作.
     * @param string 类别描述，格式为a.b.c.d
     * @param int 该timing操作的数值
     * @param $ratio 采样率
     * @access private
     */
    protected function timing($cat, $v, $ratio) {
        StatsD::get()->timing($cat, $v, $this->stat_ratio);
    }


    /**
     * 根据名字获取Memcache实例的配置
     * @param $name
     * @access private
     */
    protected function getConfig($name) {

        if( $name == null ) {
            return DistMemcacheConfig::$QUEUE_CONFIG;
        }

        if( isset(DistMemcacheConfig::$APP_CONFIG[$name]))
            return DistMemcacheConfig::$APP_CONFIG[$name];

        die("DistMemCacheAdapter::$name 未配置");
    }

    /**
     * initialize the dist queue info
     */
    protected function initDistQueue() {
        $config = $this->getConfig(null);
        if( empty($config) || !isset($config['servers']) || !isset($config['queue']))
            die("DistMemCacheAdapter::未设置分布式缓存同步队列.");
        $this->distQueue = new MemcacheQueueConnection($config['servers'], $config['queue']);
    }

}
