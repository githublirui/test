<?php

require_once 'Sample.class.php';

class SampleTest extends PHPUnit2_Framework_TestCase {

    protected $_o = null;

    public function setUp() {
        $this->_o = new Sample();
    }

    public function tearDown() {
        unset($this->_o);
    }

    public function testGetName() {
        $this->assertEquals($this->_o->getName(), 'lirui1');
    }

    public function testGetAge() {
        $this->assertEquals($this->_o->getAge(), '25');
    }

}
