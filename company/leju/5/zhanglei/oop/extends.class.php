<?php
class _parent{

	public function __construct(){
		echo "aaa";
	}

	public function name(){
		echo "_parent function name";
	}

}

class _children extends _parent{

	public function __construct(){
		parent::__construct();
	}

	public function _name(){
		parent::name();
	}

}

$children = new _children();
echo "\r\n";
$children->_name();
echo "\r\n";
?>
