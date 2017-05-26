<?php
/**
 * @author zhanglei<zhanglei19881228@sina.com>
 * @todo mysqli类
 * @date 201-07-15 14:20:00
 */
class Db{
    
    private static $_class = null;
    private $dbconf = array();
    private $debug = false;
    private $mysqli;
    
    private function __construct($dbconf){
        $this->setConf($dbconf);
        $this->chkConf();
        $this->connect();
    }
    
    // 设置数据库参数
    private function setConf($dbconf){
        $this->dbconf = $dbconf;
    }
    
    // 检查连接数据库的参数
    private function chkConf(){
        if(!isset($this->dbconf['host']) || empty($this->dbconf['host'])){
            throw new Exception("请传入数据库主机地址");
        }
        if(!isset($this->dbconf['user']) || empty($this->dbconf['user'])){
            throw new Exception('请传入数据库用户名');
        }
        if(!isset($this->dbconf['pass']) || empty($this->dbconf['pass'])){
           throw new Exception('请传入数据库密码'); 
        }
        if(!isset($this->dbconf['name']) || empty($this->dbconf['name'])){
            throw new Exception('请传入数据库名称');
        }
        if(!isset($this->dbconf['port']) || empty($this->dbconf['port'])){
            $this->dbconf['port'] = 3306;
        }
        if(!isset($this->dbconf['char']) || empty($this->dbconf['char'])){
            $this->dbconf['char'] = 'utf8';
        }
    }
    
    // 连接数据库
    private function connect(){
        $this->mysqli = new mysqli($this->dbconf['host'], $this->dbconf['user'], $this->dbconf['pass'], $this->dbconf['name']);
        if(mysqli_connect_errno()){
            $this->setDebug();
            $this->halt(mysqli_connect_error());
        }
        $this->setCharset();
    }
    
    // 设置字符集
    private function setCharset(){
        $this->excute("set names " . $this->dbconf['char']);
    }
    
    // 设置debug
    public function setDebug($debug = true){
        $this->debug = $debug;
    }
    
    private function getSprintfStr($value){
        if(is_string($value)){
            return "'%s'";
        }elseif(is_int($value)){
            return "%u";
        }elseif(is_float($value)){
            return "%f";
        }else{
            return false;
        }
    }
    
    // 将数组转换成sql语句
    private function getMixSqlStr($params, $delimiter = 'and'){
        if(is_string($params) || empty($params)){
            return $params;
        }elseif(is_array($params)){
            $tmp = array();
            foreach($params as $column => $value){
                $sprintf_str = $this->getSprintfStr($value);
                $tmp[] = sprintf("%s = $sprintf_str", $column, $this->mysqli->real_escape_string($value));
            }
            return !empty($tmp) ? implode($delimiter, $tmp) : '';
        }else{
            throw new Exception("传入参数的格式错误");
        }
    }
    
    // 得到select fields
    private function getFields($fields){
        if(is_string($fields)){
            return $fields;
        }elseif(is_array($fields)){
            return implode(',', $fields);
        }else{
            throw new Exception('查询的字段错误');
        }
    }
    
    // 得到limit后缀
    private function getLimitPage($page = '', $limit = ''){
        if(empty($page) || empty($limit)){
            return '';
        }
        $start = ($page - 1) * $limit;
        return sprintf(" limit %u, %u", $start, $limit);
    }
    
    // 插入数据
    public function insert($table, $data){
        $set_data = $this->getMixSqlStr($data, ',');
        $sql = sprintf("insert into %s set %s", $table, $set_data);
        return $this->excute($sql);
    }
    
    // 更新操作
    public function update($table, $data, $where){
        $set_data = $this->getMixSqlStr($data, ',');
        $where_string = $this->getMixSqlStr($where);
        $sql = sprintf("update %s set %s where %s", $table, $set_data, $where_string);
        return $this->excute($sql);
    }
    
    // 删除操作
    public function delete($table, $where){
        if(empty($where)){
            throw new Exception("删除操作必须带条件");
        }
        $where_string = $this->getMixSqlStr($where);
        $sql = sprintf("delete from %s where %s", $table, $where_string);
        return $this->excute($sql);
    }
    
    // 执行sql语句, 返回结果集
    public function query($sql){
        return $this->mysqli->query($sql);
    }
    
    public function fetchBySql($sql){
        $query = $this->query($sql);
        $returns = array();
        while($row = $query->fetch_assoc()){
            $returns[] = $row;
        }
        return count($returns) ? $returns : array();
    }
    
    // 取得一条记录
    public function fetch($table, $field, $where = '', $orderby = '', $page = '', $limit = ''){
        $field = $this->getFields($field);
        $where = $this->getMixSqlStr($where);
        $limitpage = $this->getLimitPage($page, $limit);
        $sql = sprintf("select %s from %s %s %s %s", $field, $table, (!empty($where) ? 'where ' : '') . $where, $orderby, $limitpage);
        $query = $this->query($sql);
        return $query->fetch_assoc();
    }
    
    // 取得多条记录
    public function fetchAll($table, $field, $where = '', $orderby = '', $page = '', $limit = ''){
        $field = $this->getFields($field);
        $where = $this->getMixSqlStr($where);
        $limitpage = $this->getLimitPage($page, $limit);
        $sql = sprintf("select %s from %s %s %s %s", $field, $table, (!empty($where) ? 'where ' : '') . $where, $orderby, $limitpage);
        return $this->fetchBySql($sql);
    }
    
    // 执行insert update delete操作
    public function excute($sql){
        $this->mysqli->query($sql);
        if(strtolower(substr($sql, 0, 6)) == 'insert'){
            return $this->mysqli->insert_id;
        }else{
            return $this->mysqli->affected_rows;
        }
    }
    
    // 输出错误信息
    public function halt($message = ''){
        $errno = $error = null;
        if($this->debug){
            $errno = $this->mysqli->errno;
            $error = $this->mysqli->error;
        }
        echo $errno . "<br />" . $error . "<br />" . $message;die;
    }
    
    // 单例
    public static function getInstance($dbconf){
        if(self::$_class === null){
            self::$_class = new Db($dbconf);
        }
        return self::$_class;
    }
    
}