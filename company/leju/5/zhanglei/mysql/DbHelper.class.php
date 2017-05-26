<?php
/**
 * @description mysql class
 * @author zhanglei
 */
class DbHelper{
    
    private static $class = null;
    private $link;
    private $db;
    
    private function __construct($conf){
        if(!$conf || !is_array($conf) || !$conf['host'] || !$conf['user'] || !$conf['pass'] || !$conf['db']){
            die('配置错误');
        }
        $this->db = $conf['db'];
        $this->link = mysql_connect($conf['host'], $conf['user'], $conf['pass']) or die('连接数据库错误');
        mysql_select_db($this->db, $this->link);
        $character = isset($conf['character']) ? $conf['character'] : 'utf8';
        if(!mysql_query("set names $character", $this->link)) die('character is error');
    }
    
    public static function getInstance($conf){
        if(self::$class === null){
            self::$class = new DbHelper($conf);
        }
        return self::$class;
    }
    
    public function getLink(){
        return isset($this->link) ? $this->link : false;
    }
    
    public function getDb(){
        return isset($this->db) ? $this->db : false;
    }
    
    private function getSprintfStr($value){
        if(!$value) return false;
        if(is_int($value))
            return '%d';
        elseif(is_string($value))
            return "'%s'";
        else
            return false;
    }
    
    private function getFields($fields){
        if(is_string($fields)){
            return $fields;
        }elseif(is_array($fields)){
            return implode(', ', $fields);
        }else{
            throw new Exception("the fields type is not correct");
        }
    }
    
    private function getMixSqlStr($params, $delimiter = 'and'){
        if(is_string($params) || $params == ''){
            return $params;
        }elseif(is_array($params)){
            $tmp = array();
            foreach($params as $key => $param){
                $sprintf_str = $this->getSprintfStr($param);
                $tmp[] = sprintf("%s = $sprintf_str", $key, mysql_real_escape_string($param));
            }
            return !empty($tmp) ? implode(" $delimiter ", $tmp) : '';
        }else{
            throw new Exception("the params type is not correct");
        }
    }
    
    /**
     * @param type $sql
     * @return type
     *     if insert opertion is insert return mysql_insert_id
     *     return else return mysql_affected_rows;
     */
    public function excute($sql){
        $result = mysql_query($sql, $this->link);
        if(!$result){
			$this->error = array('errno' => mysql_errno(), 'error' => mysql_error());
            return false;
        }else{
            if(strtolower(substr($sql, 0, 6)) == 'insert'){
                return mysql_insert_id();
            }else{
                return mysql_affected_rows();
            }
        }
    }
    
    /**
     * @param type $table
     * @param type $where
     *     array or string
     *     if array array(key => value)
     * @param type $fields
     *     same to param $where
     * @param type $tail
     *     like order by or group by or limit 0, 10 and so on
     * @return see $this->readBySql
     */
    public function read($table, $where, $fields, $tail, $returnsql = 0){
        if(!$fields) return false;
        $sql = '';
        $sql .= "select ";
        $sql .= $this->getFields($fields);
        $sql .= sprintf(" from %s.%s", $this->db, $table);
        $sql .= $where ? ' where ' : '';
        $sql .= $this->getMixSqlStr($where);
        $sql .= $tail;
        switch($returnsql){
            case 0:
                return $this->readBySql($sql);
            break;
            case 1:
                return $sql;
            break;
            default:
                return false;
            break;
        }
    }
    
    /**
     * @see $this->read();
     */
    public function readOne($table, $where, $field, $tail = '', $returnsql = false){
        $return = $this->read($table, $where, $field, $tail, $returnsql);
        if($returnsql){
            return $return;
        }else{
            if(count($return) > 0 && !isset($return['error']) && !isset($return['errno'])) return $return[0];
            return ($return['error'] && $return['errno']) ? $return : array();
        }
    }
    
    /**
     * return two-dimensional array
     */
    public function readBySql($sql){
        $list = array();
        $result = mysql_query($sql, $this->link);
        if(!$result) return array('errno' => mysql_errno(), 'error' => mysql_error());
        while($row = mysql_fetch_assoc($result)){
            $list[] = $row;
        }
        return count($list) > 0 ? $list : array();
    }
    
    /**
     * @param type $table
     * @param type $data
     * @param type $update
     *     false or empty , insert
     *     array , update and array is the where condition
     *     how to user this func
     *         use write('users', array('username' => 'zhanglei'), false);
     *         use write('users', array('username' => 'zhanglei'), array('id' => '1'))
     */
    public function write($table, $data, $where = false, $returnsql = 0){
        if(!$data || !is_array($data)) return false;
        $sql = "";
        $operation = empty($where) ? 'insert into' : 'update';
        $sql .= sprintf("%s %s.%s set ", $operation, $this->db, $table);
        $sql .= $this->getMixSqlStr($data, ',');
        $sql .= $where ? ' where ' : '';
        $sql .= $this->getMixSqlStr($where);
        return $returnsql == 0 ? $this->excute($sql) : $sql;
    }
    
    /**
     * @param $table
     * @param $where
     *     array('id' => '5')
     *     id = 5
     * @param returnsql
     */
    public function delete($table, $where, $returnsql = 0){
        $sql = "";
        $sql .= sprintf("delete from %s.%s", $this->db, $table);
        $sql .= $where ? ' where ' : '';
        $sql .= $this->getMixSqlStr($where);
        return $returnsql ? $sql : $this->excute($sql);
    }

	public function getError(){
		return $this->error;
	}
    
}
?>