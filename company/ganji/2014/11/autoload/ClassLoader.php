<?php

class ClassLoader {

    private $classMap = array();

    public function register() {
        spl_autoload_register(array($this, 'loadClass'), true);
    }

    public function loadClass($class) {
        include __DIR__ . '/' . $class . '.php';
    }

}

?>
