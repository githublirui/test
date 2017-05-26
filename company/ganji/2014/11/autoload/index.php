<?php

include __DIR__ . '/ClassLoader.php';

$loader = new ClassLoader();
$loader->register();

//SPL autoload有类则优先使用类，没有再则通过spl_autoload_register 加载
class A {

    public function ClassName() {
        return __CLASS__ . 'bootstacache';
    }

}

$a = new A();
echo $a->ClassName();
?>
