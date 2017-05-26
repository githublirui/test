<?php

/**
 * 
 * 
 * 统计脚本
 */
class FinderLog {

    private static $log = 'log.txt';

    public function run($path) {
        $fileSource = opendir($path);
        while (($filename = readdir($fileSource)) !== false) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }
            $filePath = realpath($path . '/' . $filename);
            if (is_file($filePath)) {
                $this->findFileMatchcontent($filePath);
//                $this->findFileByName($filePath);
            } else {
//                $this->findFolderByName($filePath);
                $this->run($filePath);
            }
        }
        closedir($fileSource);
    }

    /**
     * 通过文件内容匹配
     * @param type $file
     */
    public function findFileMatchcontent($file) {
        $pattern = '/stripslashes\s*\(/is';
        $content = file_get_contents($file);
        $fileInfo = pathinfo($file);
        if (!isset($fileInfo['extension']) || $fileInfo['extension'] != 'php') {
            return;
        }
        if (preg_match($pattern, $content) > 0) {
            $this->writeLog($file);
            echo '.';
        }
    }

    public function findFolderByName($folderName) {
//        $file = basename($folderName);
//        echo $file . "<br/>";
//        if (strpos($file, "o") !== false) {
//            $this->writeLog($file);
//        }
    }

    public function findFileByName($folderName) {
        $file = basename($folderName);
    }

    public function writeLog($content) {
        file_put_contents(self::$log, $content . "\n", FILE_APPEND);
    }

}

$path = 'E:\165\www\ganji\ganji_online\mobile_client';
$o = new FinderLog();
$o->run($path);
?>
