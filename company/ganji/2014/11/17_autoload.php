<?php

//namespace Composer\Autoload;
echo substr($class, 1);
class ClassLoader {

    public static $classMap = array();

    public function loadClass($class) {
        var_dump($class);
    }

}

call_user_func(array('ClassLoader', 'loadClass'), 1, 2, 3, 4);
die;

//namespace Composer\Autoloadb;
//class ClassLoader {
//
//    public static $classMap = array();
//
//    public function loadClass($class) {
//        var_dump(__CLASS__ . 'b');
//    }
//
//}
//$ob = new \Composer\Autoload\ClassLoader();
//$ob->loadClass($ob);
class MyClassLoader {

    public static function loader($class) {
//    include $class;
    }

}

class MyClassLoader1 {

    public static function loader($class) {
//    include $class;
    }

}

class MyClassLoader2 {

    public static function loader($class) {
//    include $class;
    }

}

/**
 * 自动加载函数
 * @param type $class
 */
function loadClassFunc($class) {
    
}

spl_autoload_register('loadClassFunc');

$functions = spl_autoload_functions();
var_dump($functions);
die;

//new self();和 new static();的区别
class A {

    public static function get_self() {
        return new self();
    }

    public static function get_static() {
        return new static();
    }

}

class B extends A {
    
}

//echo get_class(B::get_self()); // A
//echo get_class(B::get_static()); // B
//echo get_class(A::get_static()); // A

spl_autoload_register(array('Composer\Autoloadb\MyClassLoader', 'loader'));
spl_autoload_register(array('Composer\Autoloadb\MyClassLoader1', 'loader'));
spl_autoload_register(array('Composer\Autoloadb\MyClassLoader2', 'loader'));
$functions = spl_autoload_functions();

function enable() {
    // Ensures we don't hit https://bugs.php.net/42098
    class_exists(__NAMESPACE__ . '\ErrorHandler', true);

    if (!is_array($functions = spl_autoload_functions())) {
        return;
    }

    foreach ($functions as $function) {
        spl_autoload_unregister($function);
    }

    foreach ($functions as $function) {
        if (!is_array($function) || !$function[0] instanceof self) {
            $function = array(new static($function), 'loadClass');
        }
        spl_autoload_register($function);
    }
}

//enable();
//$functions = spl_autoload_functions();
?>
