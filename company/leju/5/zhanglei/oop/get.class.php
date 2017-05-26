<?php
class Get{

	private $private;
	public $public;

	public function __construct($private, $public){
		$this->private = $private;
		$this->public  = $public;
	}

	public function __get($property){
		if(isset($this->$property)) return 'call this __get function and the property is ' . $this->$property;
	}

}

$get = new Get('private', 'public');
echo $get->private;
?>
