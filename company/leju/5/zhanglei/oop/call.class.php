<?php
class Call{

	private static $name = "adele";
	private $address = "london";

	public function __construct(){
		// do nothing
	}

	public function __call($name, $arguments){
		print_r($arguments);
		throw new Exception('function ' . $name . ' is not exists');
		echo "\r\n";
	}

	private function test(){
		echo 'aa';
	}
	
	public static function staticfunc(){
		echo self::$name;
		echo "\r\n";
		// echo $this->test();
		// echo $this->address;
		// 静态方法中只能只用静态变量, 不能使用this这个特殊对象
	}

}

$call = new Call();
$call->staticfunc();
$call->name('a', 'b');
?>
