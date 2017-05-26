<?php
class _Unset{

	private $name;

	public function __construct($name){
		$this->name = $name;
	}

	public function __isset($property){
		return isset($this->$property);
	}

	public function __get($property){
		if(isset($this->$property)) return $this->$property;
	}

	public function __unset($property){
		unset($this->$property);
	}

}

$unset = new _Unset('adele');

echo isset($unset->name) ? "property is " . $unset->name : 'property is null';

unset($unset->name);
echo "\r\n";

echo isset($unset->name) ? 'property is ' . $unset->name : 'property is null';
echo "\r\n";
?>
