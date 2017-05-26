<?php
/*
* mysql数据库 DB类
* @package  db
* @author   yytcpt(无影)
* @version  2008-03-27
* @copyrigth  http://www.d5s.cn/
*/

/*
使用方法：
$db_config['hostname'] = "host";
$db_config['username'] = "0000";
$db_config['password'] = "1234";
$db_config['database'] = "dbdb";
$db_config['charset']  = "utf8";

$db = new mysqldb($db_config);
//插入数据
$insert_arr = array('user_name'=>'infozero','user_sex'=>'男','user_pwd'=>"mypass");
$db->row_insert("admin_user",$insert_arr);

//插入多行数据
$insert_sql = "insert into admin_user(user_name,user_sex,user_pwd) values('test2','女','123'),('test3','男','456')";
$db->query($insert_sql,'mysql_query');

//获取多条数据
$sql = "select * from admin_user ";
print_r($db->row_query($sql));

//获取一条数据
$sql = "select * from admin_user ";
print_r($db->row_query_one($sql));

//更新数据
$update_arr = array('user_name'=>'updtest','user_sex'=>'女');
$where = 'user_name="test"';
$db->row_update('admin_user',$update_arr,$where);

//删除数据
$where = 'user_name = "test0" ';
$db->row_delete("admin_user",$where);

$db->close_db();
unset($db);
*/

//header("Content-type:text/html; charset=utf-8");
class mysqldb {
	var $connection_id = "";                 //mysql连接标识符
	var $pconnect = 0;                     //是否无间断连接
	var $shutdown_queries = array();          //
	var $queries = array();                 //数据库操作sql语句数组
	var $query_result = "";                 //mysql_query资源标识符
	var $query_count = 0;                 //查询次数
	var $record_row = array();                //返回记录
	var $failed = 0;                        //可能是查询是否成功的意思
	var $halt = "";                       //错误消息
	var $query_log = array();               //操作日志

	function __construct($db_config){
		$this->connect($db_config);
	}

	function connect($db_config){
		if ($this->pconnect){
			$this->connection_id = mysql_pconnect($db_config["hostname"], $db_config["username"], $db_config["password"]);
		}else{
			$this->connection_id = mysql_connect($db_config["hostname"], $db_config["username"], $db_config["password"]);
		}
		if ( ! $this->connection_id ){
			$this->halt("Can not connect MySQL Server");
		}
		if ( ! @mysql_select_db($db_config["database"], $this->connection_id) ){
			$this->halt("Can not connect MySQL Database");
		}
		if ($db_config["charset"]) {
			$this->db_charset = $db_config["charset"];
			@mysql_unbuffered_query("SET NAMES '".$db_config["charset"]."'");
		}
		return true;
	}
	//发送SQL 查询，并返回结果集
	function query($query_result, $query_type='mysql_query'){
		$this->query_result = $query_type($query_result, $this->connection_id);
		$this->queries[] = $query_result;
		if (! $this->query_result ) {
			$this->halt("查询失败:\n$query_result");
		}
		$this->query_count++;
		//$this->query_log[] = $str;
		return $this->query_result;
	}
	//发送SQL 查询，并不获取和缓存结果的行
	function query_unbuffered($sql=""){
		return $this->query($sql, 'mysql_unbuffered_query');
	}
	//从结果集中取得一行作为关联数组
	function fetch_array($sql = ""){
		if ($sql == "") $sql = $this->query_result;
		$this->record_row = @mysql_fetch_array($sql, MYSQL_ASSOC);
		return $this->record_row;
	}
	function shutdown_query($query_result = ""){
		$this->shutdown_queries[] = $query_result;
	}
	//取得结果集中行的数目，仅对 INSERT，UPDATE 或者 DELETE
	function affected_rows() {
		return @mysql_affected_rows($this->connection_id);
	}
	//取得结果集中行的数目，仅对 SELECT 语句有效
	function num_rows($query_result="") {
		if ($query_result == "") $query_result = $this->query_result;
		return @mysql_num_rows($query_result);
	}
	//返回上一个 MySQL 操作中的错误信息的数字编码和错误内容
	function get_error(){
		$this->errno = @mysql_errno($this->connection_id);
		$this->error = @mysql_error($this->connection_id);
		return $this->errno.':'.$this->error;
	}
	//取得上一步 INSERT 操作产生的 ID
	function insert_id(){
		return @mysql_insert_id($this->connection_id);
	}
	//得到查询次数
	function query_count() {
		return $this->query_count;
	}
	//释放结果内存
	function free_result($query_result=""){
		if ($query_result == "") $query_result = $this->query_result;
		@mysql_free_result($query_result);
	}
	//关闭 MySQL 连接
	function close_db(){
		if ( $this->connection_id ) return @mysql_close( $this->connection_id );
	}
	//列出 MySQL 数据库中的表
	function get_table_names(){
		global $db_config;
		$result = mysql_list_tables($db_config["database"]);
		$num_tables = @mysql_numrows($result);
		for ($i = 0; $i < $num_tables; $i++) {
			$tables[] = mysql_tablename($result, $i);
		}
		mysql_free_result($result);
		return $tables;
	}
	//从结果集中取得列信息并作为对象返回，取得所有字段
	function get_result_fields($query_result=""){
		if ($query_result == "") $query_result = $this->query_result;
		while ($field = mysql_fetch_field($query_result)) {
			$fields[] = $field;
		}
		return $fields;
	}
	//错误提示
	function halt($the_error=""){
		$message = $the_error."\r\n";
		$message.= $this->get_error() . "\r\n";
		/*$sql = "INSERT INTO `db_error`(pagename, errstr, timer) VALUES('".$_SERVER["PHP_SELF"]."', '".addslashes($message)."', ".time().")";
		@mysql_unbuffered_query($sql);*/
		if ($this->errno){
			echo "<html><head><title>MySQL 数据库错误</title>";
			echo "<style type=\"text/css\"><!--.error { font: 11px tahoma, verdana, arial, sans-serif, simsun; }--></style></head>\r\n";
			echo "<body>\r\n";
			echo "<blockquote>\r\n";
			echo "<textarea class=\"error\" rows=\"15\" cols=\"100\" wrap=\"on\" >" . htmlspecialchars($message) . "</textarea>\r\n";
			echo "</blockquote>\r\n</body></html>";
			exit;
		}
	}
	function __destruct(){
		$this->shutdown_queries = array();
		$this->close_db();
	}
	function sql_select($tbname, $where="", $limit=0, $fields="*", $orderby="id", $sort="DESC"){
		$sql = "SELECT ".$fields." FROM `".$tbname."` ".($where?" WHERE ".$where:"")." ORDER BY ".$orderby." ".$sort.($limit ? " limit ".$limit:"");
		return $sql;
	}
	function sql_insert($tbname, $row){
		$sqlfield = $sqlvalue = "";
		foreach ($row as $key=>$value) {
			$sqlfield .= $key.",";
			$sqlvalue .= "'" . mysql_escape_string($value) ."',";
		}
		return "INSERT INTO `".$tbname."`(".substr($sqlfield, 0, -1).") VALUES (".substr($sqlvalue, 0, -1).")";
	}
	function sql_update($tbname, $row, $where){
		$sqlud="";
		foreach ($row as $key=>$value) {
			$sqlud .= $key."= '". mysql_real_escape_string($value) ."',";
		}
		return "UPDATE `".$tbname."` SET ".substr($sqlud, 0, -1)." WHERE ".$where;
	}
	function sql_delete($tbname, $where){
		return "DELETE FROM `".$tbname."` WHERE ".$where;
	}
	//新增加一条记录
	function row_insert($tbname, $row){
		$sql = $this->sql_insert($tbname, $row);
		return $this->query_unbuffered($sql);
	}
	//更新指定记录
	function row_update($tbname, $row, $where){
		$sql = $this->sql_update($tbname, $row, $where);
		return $this->query_unbuffered($sql);
	}
	//删除满足条件的记录
	function row_delete($tbname, $where){
		$sql = $this->sql_delete($tbname, $where);
		return $this->query_unbuffered($sql);
	}
	/*  根据条件查询，返回所有记录
	* $tbname 表名, $where 查询条件, $limit 返回记录, $fields 返回字段
	*/
	function row_select($tbname, $where="", $limit=0, $fields="*", $orderby="id", $sort="DESC"){
		$sql = $this->sql_select($tbname, $where, $limit, $fields, $orderby, $sort);
		return $this->row_query($sql);
	}
	//详细显示一条记录
	function row_select_one($tbname, $where, $fields="*", $orderby="id"){
		$sql = $this->sql_select($tbname, $where, 1, $fields, $orderby);
		return $this->row_query_one($sql);
	}
	//返回所有数据(多维数组)
	function row_query($sql){
		$rs  = $this->query($sql);
		$rs_num = $this->num_rows($rs);
		$rows = array();
		for($i=0; $i<$rs_num; $i++){
			$rows[] = $this->fetch_array($rs);
		}
		$this->free_result($rs);
		return $rows;
	}
	//返回一行数据
	function row_query_one($sql){
		$rs  = $this->query($sql);
		$row = $this->fetch_array($rs);
		$this->free_result($rs);
		return $row;
	}
	//计数统计
	function row_count($tbname, $where=""){
		$sql = "SELECT count(*) as row_sum FROM `".$tbname."` ".($where?" WHERE ".$where:"");
		$row = $this->row_query_one($sql);
		return $row["row_sum"];
	}
}
?>