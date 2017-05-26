<?php

class test2 extends PHPUnit2_Framework_TestCase {

    public function testPushAndPop() {
        $stack = array();
        $this->assertEquals(3, count($stack));

        array_push($stack, 'f1oo');
        $this->assertEquals('1foo', $stack[count($stack) - 1]);
        $this->assertEquals(12, count($stack));

        $this->assertEquals('12foo', array_pop($stack));
        $this->assertEquals(1, count($stack));
    }

}

?>