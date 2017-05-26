<?php
class Set{

	private $name;

	public function __construct(){
	
	}

	public function __set($property, $value){
		$this->$property = "had been set a value for the property, and the value is " . $value;
	}

	public function __get($property){
		echo $this->$property;
	}
	
}

$set = new Set();
$set->name = "张磊";
echo $set->name;
?>
