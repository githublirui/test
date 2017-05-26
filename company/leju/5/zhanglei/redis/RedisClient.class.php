<?php
/**
 * @brief redis client class
 * @author zhanglei <zhanglei19881228@sina.com>
 */

class RedisClient{
    
    private static $class = null;       // 实例化对象
    private $connection = null;         // redis连接对象
    private $keyprefix = 'redis_';      // 存储key前缀
    
    // 构造函数
    private function __construct($conf){
        if($this->checkConf($conf)){
            throw new Exception('redis conf is error');
        }
		if($this->connection === null){
			$this->connection = new redis();
			$this->connection->connect($conf['host'], $conf['port']);
            
            if(isset($conf['password']) && !empty($conf['password'])){
                $this->connection->auth($conf['password']);
            }
            if(isset($conf['dbname']) && is_int($conf['dbname'])){
                $this->selectDB($conf['dbname']);
            }
		}
    }
    
    // 检查传入的conf是否正确
    private function checkConf($conf){
        if(!$conf || !is_array($conf) || !isset($conf['host']) || !isset($conf['port'])){
            return false;
        }
    }
    
    // 选择数据库
    public function selectDB($dbname){
        if(!is_int($dbname)){
            throw new Exception('redis db name is empty');
        }
        return $this->connection->select($dbname);
    }
    
    // 查看key是否存在 存在 true 不存在 false
    public function exists($key){
        return $this->connection->exists($key);
    }
    
    // 设置key value值, 0 无过期
    public function set($key, $value, $expire = 0){
        if(!empty($expire) && $expire > 0 && is_int($expire)){
            return $this->connection->setex($this->keyprefix . $key, $expire, $value);
        }
        return $this->connection->set($this->keyprefix . $key, $value);
    }
    
    // 修改key值
    public function update($key, $value, $expire = 0){
        if($this->connection->exists($this->keyprefix . $key)){
            return $this->set($key, $value, $expire);
        }
        return false;
    }
    
    // 删除key
    public function del($key){
        if($this->connection->exists($this->keyprefix . $key)){
            return $this->connection->delete($this->keyprefix . $key);
        }
        return false;
    }
    
    // 得到key的生存时间
	public function ttl($key){
		if($this->connection->exists($this->keyprefix . $key)){
            return $this->connection->ttl($this->keyprefix . $key);
        }
	}
    
    // 给多个key赋值
	public function mset($data){
		return $this->connection->mset($data);
	}
    
    // 得到key
    public function get($key){
        return $this->connection->get($this->keyprefix . $key);
    }
    
    // 通过多个key(数组)得到value
    public function getMultiple($keys = array()){
        return $this->connection->getMultiple($keys);
    }
    
    // 增加key的值
    public function incr($key, $incrBy = 1){
        return $this->connection->incr($key, $incrBy);
    }
    
    // 减少key的值
    public function decr($key, $decrBy = 1){
        return $this->connection->decr($key, $decrBy);
    }

    // 队列, 向首部增加数据
    public function lpush($key, $value){
        return $this->connection->lpush($this->keyprefix . $key, $value);
    }

    // 队列, 向尾部增加数据
    public function rpush($key, $value){
        return $this->connection->rpush($this->keyprefix . $key, $value);
    }

    // 队列, 从首部弹出数据，并且删除此数据
    public function lpop($key){
        return $this->connection->lpop($this->keyprefix . $key);
    }

    // 队列, 从尾部弹出数据, 并且删除数据
    public function rpop($key){
        return $this->connection->rpop($this->keyprefix . $key);
    }

    // 查看队列中的总数
    public function llen($key){
        return $this->connection->llen($this->keyprefix . $key);
    }

    // 查看队列中的数据, 有偏移量
    public function lrange($key, $start, $offset){
        return $this->connection->lrange($this->keyprefix . $key, $start, $offset);
    }

    // 查看队列中某一个值
    public function lindex($key, $index){
        return $this->connection->lindex($this->keyprefix . $key, $index);
    }

	// 清空所有过期的key
	public function persist(){
		return $this->connection->persist();
	}
    
    // 得到符合正则表达式的所有key
    public function keys($regexp){
        return $this->connection->keys($regexp);
    }
    
    // 将key重命名
    public function rename($key, $new_key){
        return $this->connection->rename($key, $new_key);
    }
    
    // 返回一个随机的key
    public function getRandomKey(){
        return $this->connection->randomKey();
    }
    
    // 得到redis完整信息
    public function getInfomation(){
        return $this->connection->info();
    }
    
    // 得到当前数据库总共有多少key
    public function dbSize(){
        return $this->connection->dbSize();
    }
    
    // 清空当前数据库
    public function flushDB(){
        return $this->connection->flushDB();
    }
    
    // 清空所有数据库
    public function flushAll(){
        return $this->connection->flushAll();
    }
    
    // 将所有数据持久化磁盘 type = 1 同步 type = 2 异步
    public function save($type = 2){
        if($type == 1){
            $this->connection->save();
        }else{
            $this->connection->bgsave();
        }
    }
    
    // 得到redis类
    public function getRedisClient(){
        return $this->connection;
    }

    // 实例化类
    public static function getInstance($conf){
        if(self::$class === null){
            self::$class = new RedisClient($conf);
        }
        return self::$class;
    }
    
}

?>
