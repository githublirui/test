<?php

namespace base;

class A {

    public function run() {
        $this->handleRequest();
    }

    public function handleRequest() {
        echo '1';
    }

}

namespace web;

class A extends \base\A {

    public function handleRequest() {
        echo '2';
    }

}

$webA = new \web\A();
$webA->run();
?>
