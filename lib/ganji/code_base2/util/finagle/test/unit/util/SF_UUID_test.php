<?php
require_once dirname(__FILE__) . '/../SF_UUID.php';
class SF_UUID_test extends  PHPUnit_Framework_TestCase { 
	public function testGen() {
		$uuid = SF_UUID::guid();
		var_dump($uuid);
		$this->assertFalse(empty($uuid));
	}
}