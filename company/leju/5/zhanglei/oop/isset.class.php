<?php
class _Isset{

	private $name;

	public function __construct($name){
		$this->name = $name;
	}

	public function __isset($property){
		echo "the function __isset has been called";
		return isset($this->$property);
	}

	public function __get($property){
		if(isset($this->$property)) return $this->$property;
	}

}

$isset = new _Isset('adele');
echo isset($isset->sex) ? " yes" : " no";
echo "\r\n";
echo isset($isset->name) ? " yes, and value is " . $isset->name : " no";
?>
