<?PHP
/**
 * memcache操作对象, 此class是对Memcached对象的包装
 * @package              ganji\base\cache\adapter
 * @author               yangyu
 * @file                 $RCSfile: MemCache.class.php,v $
 * @version              $Revision: 50884 $
 * @modifiedby           $Author: yangyu $
 * @lastmodified         $Date: 2011-03-11 15:53:56 +0800 (五, 2011-03-11) $
 * @copyright            Copyright (c) 2011, ganji.com
 */

/**
 * memcache操作对象, 此class是对Memcached对象的包装
 * @package              ganji\base\cache\adapter
 */
class MemCacheAdapter {
    /**
     * @brief 一致性hash
     * @var boolean, false|不使用， true|使用
     */
    private $_distribution_consistent = false;

    /**
     * change from private to protected
     * @var Memcached|null
     */
    protected $connection = null;

    /**
     * @var array 缓存已经创建的实例
     */
    private static $instances = array();

    /**
     * @brief socket time out(ms)
     * @var integer
     */
    private static $_OPT_SEND_TIMEOUT = 1000;

    /**
     * @brief reading time out(ms)
     * @var integer
     */
    private static $_OPT_RECV_TIMEOUT = 1000;

    /**
     * 构造函数
     * @param array
     * @param array
     */
    public function __construct($servers, $params = array()) {       
        if (!is_array($servers) || count($servers) == 0 || empty($servers[0]['host']) || empty($servers[0]['port'])) {
            die('MemCache::未指定$servers');
        }
        $keys = array();
        $servers_to_add = array();
        foreach ($servers as $server) {
            $keys[] = $server['host'] . '_' . $server['port'];
            $servers_to_add[] = array($server['host'], $server['port'], $server['weight']);
        }
        $key = implode('|', $keys);
        // 一致性hash
        $this->_distribution_consistent = (array_key_exists('distribution_consistent', $params) && $params['distribution_consistent'] === true) 
                                        ? true 
                                        : false;
        if($this->_distribution_consistent) {
            $key .= '|consistent';
        }

        // 获取部分memcache 选项
        $optOptions = self::_formatOptions($params);

        if (isset(self::$instances[$key]) && self::$instances[$key]) {
            $this->connection = self::$instances[$key];
        } else {
            try {
                $this->connection = new Memcached();
                $this->connection->addServers($servers_to_add);
                $this->connection->setOption(Memcached::OPT_COMPRESSION, 0);
                $this->connection->setOption(Memcached::OPT_CONNECT_TIMEOUT, 50);
                $this->connection->setOption(Memcached::OPT_SEND_TIMEOUT, $optOptions['OPT_SEND_TIMEOUT']);
                $this->connection->setOption(Memcached::OPT_RECV_TIMEOUT, $optOptions['OPT_RECV_TIMEOUT']);
                // 使用一致性hash分配
                if($this->_distribution_consistent) {
                    $this->connection->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
                    $this->connection->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true); 
                } else {
                    $this->connection->setOption(Memcached::OPT_HASH, Memcached::HASH_CRC);
                }

                self::$instances[$key] = $this->connection;
            } catch (Exception $e) {
                if (rand(0, 10) == 1) {
                    self::_log(sprintf('errmsg=%s connect memcache fail, service=%s, params=%s', 
                            $e->getMessage(), var_export($servers, true), var_export($params, true)));
                }
            }
        }
    }

    /**
     * @brief 格式化memcached的一些选项, 当前允许自定义 发送和接收超时
     * @param array $params 设置的options可以为空数组
     * @return array
     *  -OPT_SEND_TIMEOUT 
     *  -OPT_RECV_TIMEOUT
     */
    private function _formatOptions($params = array()) {
        // memcache options init
        $options = array(
            'OPT_SEND_TIMEOUT' => self::$_OPT_SEND_TIMEOUT,
            'OPT_RECV_TIMEOUT' => self::$_OPT_RECV_TIMEOUT,
        );
        foreach ($options as $key => $value) {
            if (isset($params[$key])) {
                $options[$key] = $params[$key];
            }
        }
        return $options;
    }

    /**
     * 写缓存
     *
     * @param $key string 缓存的key
     * @param $value mixed 缓存的值
     * @param $duration int 缓存的时间，以秒为单位
     *
     * @returns  bool  如果成功缓存则返回true，否则返回false. 
     */
    public function write($key, $value, $duration = 3600)
    {
        if (!$this->connection) return false;
        $key  = $this->encodeKey($key);
        return $this->connection->set($key, $value, time() + $duration);
    }

    /**
     * 一次设置多个items
     *
     * @param array key=>value 形式存储到cache中
     * @param int 过期时间秒
     *
     * @see Memcached::setMulti PHP手册
     * @returns mixed  
     */
    public function writeMulti(array $items, $duration = 3600)
    {
        if (!$this->connection) return false;
        $data = array();
        foreach ($items as $key => $item) {
           $key = $this->encodeKey($key);
           $data[$key] = $item;
        }   
        return $this->connection->setMulti($data, time() + $duration);
    }
    
    /**
     * 读缓存
     *
     * @param string 缓存的key
     *
     * @returns  mixed 返回key对应的value，如果结果不存在、过期或者在获取的过程中发生错误，将会返回false. 
     */
    public function read($key)
    {
        if (!$this->connection) return NULL;
        $key    = $this->encodeKey($key);
        $ret     = $this->connection->get($key);

        $this->stat($key, $ret !== false);
        return $ret;
    }
    
    /**
     * 壳函数，用于缓存命中率统计
     *
     * @abstract
     * @ignore
     */
    protected function stat($key, $hit)
    {
        //xhprof通过统计miss函数的调用次数，可以了解缓存的命中信息 
    }
    
    /**
     * 一次读取多个items
     *
     * @param array
     * @code
     * 例子:
     *    $items = array(
     *      'key1' => 'value1',
     *      'key2' => 'value2',
     *      'key3' => 'value3'
     *    );
     *    $result = $m->readMulti(array('key1', 'key3', 'badkey'));
     *    var_dump($result, $cas);
     * 结果:
     *    array(2) {
     *          ["key1"]=>
     *              string(6) "value1"
     *          ["key3"]=>
     *              string(6) "value3"
     *        }
     * @endcode
     *
     * @returns  mixed 返回一个发现key=>value的数组，失败返回false
     *
     * @see Memcached::getMulti
     */
    public function readMulti(array $keys)
    {
        if (!$this->connection) return NULL;
        $cas = 0;
        foreach ($keys as $key => $item) {
            $keys[$key] = $this->encodeKey($keys[$key]);
        }
        return $this->connection->getMulti($keys, $cas, Memcached::GET_PRESERVE_ORDER);
    }
    
    /**
     * 删除缓存
     *
     * @param $key string 要删除的key
     *
     * @returns 如果成功删除,返回true;如果key对应的value不存在或者不能删除，则返回false.
     */
    public function delete($key)
    {
        if (!$this->connection) return false;
        $key    = $this->encodeKey($key);
        return $this->connection->delete($key);
    }
    
    /**
     * 清除所有KEY.
     * 将现有的items的时间，设置为过期,从而使items失效
     * @codeCoverageIgnore
     * @returns boolean   
     */
    public function clear()
    {
        if (!$this->connection) return false;
        return $this->connection->flush();
    }
        
    /**
     * 析构.
     * 清除连接对象
     *
     */
    public function __destruct()
    {
        if($this->connection)
            unset($this->connection);
    }
    
    /**
     * 对值进行加法操作
     *
     * @param $key string 要进行加法操作的key
     * @param $value int 要加的值 如果不传这个参说默认+1
     *
     * @returns 返回key的value key不存在返回false
     * @see Memcached::increment
     */
    public function increment($key, $value = null)
    {
        if (!$this->connection) return false;
        $key    = $this->encodeKey($key);
        if ( $value == null )
        {
            return $this->connection->increment($key);
        }
        return $this->connection->increment($key, $value);
    }
    
    /**
     * 对值进行减法操作
     *
     * @param $key string 要进行减法操作的key
     * @param $value int 要加的值 如果不传这个参说默认-1
     *
     * @returns  返回key的value key不存在返回false 
     */
    public function decrement($key, $value = null)
    {
        if (!$this->connection) return false;
        $key    = $this->encodeKey($key);
        if ( $value == null )
        {
            return $this->connection->decrement($key);
        }
        return $this->connection->decrement($key, $value);
    }

    /**
     * 对key进行编码，避免特殊字符和不合法的字符
     * @param $key
     * @return string
     */
    protected function encodeKey( $key ) {
        return CacheNamespace::encodeKey($key);
    }

    /**
     * 日志记录
     * @param string $msg 错误信息
     */
    public static function _log($msg) {
        if(class_exists('Logger') && method_exists('Logger', 'logWarn')){
            Logger::logWarn($msg, 'cache.memcache');
        }
    }
    /**
     * 写缓存
     *
     * @param $key string 缓存的key
     * @param $value mixed 缓存的值
     * @param $duration int 缓存的时间，以秒为单位
     *
     * @returns  bool  如果成功缓存则返回true，否则返回false. 
     */
    public function add($key, $value, $duration = 3600)
    {
        if (!$this->connection) return false;
        $key  = $this->encodeKey($key);
        return $this->connection->add($key, $value, time() + $duration);
    }
}
