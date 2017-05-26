<?php
/**
 * @description memcache class
 * @author zhanglei
 */
class Memcache_Queue{
    
    private static $class = null;
    private $memcache;
    private $write_prefix = 'W';
    private $read_prefix = 'R';
    
    private function __construct(){
        $this->memcache = new Memcache;
        $this->memcache->connect('127.0.0.1', 11211) or die('can not connect memcache by 11211 port');
    }
    
    public static function getInstance(){
        if(self::$class === null){
            self::$class = new Memcache_Queue();
        }
        return self::$class;
    }
    
    public function getMemcache(){
        return $this->memcache;
    }
    
    private function getWritePrefix(){
        return isset($this->write_prefix) && !empty($this->write_prefix) ? $this->write_prefix : '';
    }
    
    private function getReadPrefix(){
        return isset($this->read_prefix) && !empty($this->read_prefix) ? $this->read_prefix : '';
    }
    
    /*
     * @param type $key
     * @param type $offset
           对应key的value, 做相应的增减, 如果value不是int或者小于0, 则设置key对应的value = 0
     * @param type $time
     * @return boolean|int
           返回key对应的value 增减 offset 返回的值
     */
    private function counter($key, $offset, $time = 0){
        $value = $this->memcache->get($key);
        if(!$value || $value < 0 || !is_numeric($value)){
            $result = $this->memcache->set($key, 0, $time);
            if(!$result) return false;
            $value = 0;
        }
        $offset = intval($offset);
        if($offset > 0){
            return $this->memcache->increment($key, $offset);
        }elseif($offset < 0){
            return $this->memcache->decrement($key, -$offset);
        }
        return $value;
    }
    
    /**
     * @param type $key memcache共享多个项目, 每个项目设定特定的key,例如聊天室key = chart
           存储的key等于 前缀 + key + $this->counter
     * @param type $value 存储所对应的值
     * @param type $time 存储的有效时间, time = 0, 永久
     * @return 成功 true 失败 false
     */
    public function input($key, $value, $time = 0){
        $w_key = $this->getWritePrefix() . $key;
        $w_count = $this->counter($w_key, 1);
        $v_key = $w_key . $w_count;
        return $this->memcache->set($v_key, $value, $time);
    }
    
    /**
     * @param $key see input
     * @param $page 从memcache中key_$page开始读数据
     * @param $limitpage 读limitpage条数据
     */
    public function output($key, $page, $limitpage){
        $output = array();
        $w_key = $this->getWritePrefix() . $key;
        $start = ($page - 1) * $limitpage + 1;
        for($i=$start; $i<=($start + $limitpage); $i++){
            $output[] = $this->memcache->get($w_key . $i);
        }
        return !empty($output) ? $output : array();
    }
    
    /**
     * @param $key see input
     * @return memcache中key的总数
     */
    public function getCount($key){
        $return = $this->memcache->get($this->getWritePrefix() . $key);
        return $return ? $return : 0;
    }
    
    /**
     * @param $key see input
     * @param $startpage 取得memcache中的起始条
     * @param $endpage 取得memcache中的结束条
     */
    public function getDataByPage($key, $startpage, $endpage){
        $output = array();
        if($startpage > $endpage) return $output;
        $w_key = $this->getWritePrefix() . $key;
        for($i=$startpage; $i<=$endpage; $i++){
            $tmp = $this->memcache->get($w_key . $i);
            if(!empty($tmp)) $output[] = unserialize($tmp);
        }
        return !empty($output) ? $output : array();
    }
    
    /**
     * @param $key see input
     * @return array 返回所有memcache中key = $key的所有键值对
     */
    public function getkeyValue($_key){
        $prefix = $this->getWritePrefix();
        $return = array();
        $data = $this->memcache->getextendedstats('slabs');
        $items = $data['127.0.0.1:11211'];
        if(!is_array($items)) return false;
        $keys = array_keys($items);
        foreach($keys as $key){
            $key = intval($key);
            $tmp = $this->memcache->getextendedstats('cachedump', $key, 0);
            $line = $tmp['127.0.0.1:11211'];
            if(!empty($line)){
                $tmp_keys = array_keys($line);
                foreach($tmp_keys as $_k){
                    if(false !== strpos($_k, $_key)){
                        $_result = $this->memcache->get($_k);
                        if(!empty($_result) && $_k != $prefix . $_key) 
                            $return[$_k] = unserialize($_result);
                        elseif($_k == $prefix . $_key)
                            $return[$_k] = $_result;
                    }
                }
            }
        }
        return $return;
    }
    
    public function set($key, $value, $time = 0){
        return $this->memcache->set($key, $value, $time);
    }
    
    public function get($key){
        return $this->memcache->get($key);
    }
    
    public function delete($key){
        return $this->memcache->delete($key);
    }
    
    /**
     * @return 清楚memcache内容, 让内存重写
     */
    public function flush(){
        return $this->memcache->flush();
    }
      
}
?>