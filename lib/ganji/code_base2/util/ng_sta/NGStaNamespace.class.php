<?php
/**
 * 分页相关方法
 * @copyright (c) 2011 Ganji Inc.
 * @file      NGStaNamespace.class.php
 * @author    chenjianbin <chenjianbin@ganji.com>
 * @date      2014-2-25
 */

class NGStaNamespace {
    private static $expire = 604800; // 1 week
    private static $version = array();
    private static $server = 'http://sta.ganjistatic1.com/ng/';
    private static $servers = array(
        'http://sta.ganjistatic1.com/ng/',
        'http://sta2.ganjistatic2.com/ng/'
    );

    public static function init () {
        $path = CODE_BASE2 . '/../ng_sta/build/g-version.json';

        if (file_exists($path)) {
            try {
                self::$version = json_decode(file_get_contents($path), true);
            } catch (Exception $ex) {
                self::$version = array();
            }
        }
    }

    public static function getVersionByDirs($dirs) {
        $files = array_keys(self::$version);
        $version = array();
        foreach ($files as $file) {
            foreach ($dirs as $dir) {
                if (strpos($file, $dir) === 0) {
                    $version[$file] = self::$version[$file];
                    break;
                }
            }
        }

        return $version;
    }

    public static function getVersionByFile($file) {
        $version = self::$version[$file];
        if (empty($version)) {
            $now = time();
            $version = $now - ($now % self::$expire);
        }

        return $version;
    }

    public static function getAbsoluteUrl($file) {
        if(preg_match("/(.*)\.(js|css|tpl)$/i", $file, $matches)){
            return self::$server . $matches[1] . '.__' . self::getVersionByFile($file) . '__.' . $matches[2];
        } else {
            return self::$server . $file . '?v=' . self::getVersionByFile($file);
        }

    }
}

NGStaNamespace::init();
