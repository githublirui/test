<?php

/**
 * composer 自动加载类
 */
class ComposerAutoloader {

    public static function getInitializer($loader) {
        return \Closure::bind(function () use ($loader) {
                    var_dump( $loader->classMap);
                    die;
                    $loader->classMap = 111;
                }, null);
    }

}

?>
