<?php
/**
 * @author zhanglei <zhanglei19881228@sina.com>
 * @todo mongodb class
 */
class MongoHelper{

	private static $class = null;
	
	private $conf = array(
		'host' => 'localhost',
		'user' => 'mongodb',
		'pass' => 'mongodb',
		'name' => 'test',
		'port' => 27017
	);

	private $connection;

	private function __construct(){
		$params = sprintf("mongodb://%s:%s@%s:%u", $this->conf['user'], $this->conf['pass'], $this->conf['host'], $this->conf['port']);
		$connection = new MongoClient($params);
		$this->connection = $connection->selectDB($this->conf['name']);
	}

	public function getCollection(){
		return $this->connection;
	}

	public static function getInstance(){
		if(self::$class === null){
			self::$class = new MongoHelper();
		}
		return self::$class;
	}

}
?>
