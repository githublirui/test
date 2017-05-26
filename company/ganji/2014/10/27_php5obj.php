<?php

//非静态方法，静态调用，内部可以，外部不可以
class A {

    public function test1() {
        echo 'test1';
        self::test2();
    }

    public function test2() {
        echo 'test2';
    }

}

$a = new A();
$a->test1();
?>
