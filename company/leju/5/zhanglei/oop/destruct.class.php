<?php
class Destruct{

	private $name;

	public function __construct($name){
		$this->name = $name;
	}

	public function say(){
		echo $this->name . "\r\n";
	}

	public function __destruct(){
		echo "unset " . $this->name . "\r\n";
	}

}

$d1 = new Destruct('zhanglei');
$d2 = new Destruct("adele");
echo $d1->say();
echo $d2->say();
?>
