<?php

require_once 'Stack.php';

class StackTest extends PHPUnit2_Framework_TestCase {

    private $o = null;
    private $_testable = null;

    public function setUp() {
        $this->o = new Stack();
    }

    public function tearDown() {
        $this->o = null;
    }

    public function testTruePropertyIsTrue() {
        $this->assertEquals($this->o->truePropertyIsTrue(), 1211);
    }

    public function testTruePropertyIsFalse() {
        $this->assertEquals("trueProperty isn't fals1e", "trueProperty isn't false");
    }

    /** test methods will go here */
}

?> 