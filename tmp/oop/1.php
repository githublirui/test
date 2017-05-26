<?php

class Test1 {

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return 'name1';
    }

}

class Test2 extends Test1 {

    public function getName() {
        return $this->name;
    }

}

$test = new Test2('abc');

var_dump($test);