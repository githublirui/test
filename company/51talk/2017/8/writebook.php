<?php

/**
 * 书名整理
 * @author lirui
 * $n = new Writebook();
 * $n->setDir('G:/projects/lirui_dev/51talk/App/B2s/View/Agent/daily/meeting.html'); //文件外层目录
 * $n->start();
 */
class Writebook {

    private $dir = '';
    private $files = [];
    private $exts = ['pdf', 'txt', 'mobi'];

    public function setDir($dir) {
        $this->dir = $dir;
        if (file_exists($this->dir . DIRECTORY_SEPARATOR . 'books.txt')) {
            unlink($this->dir . '/books.txt');
        }
    }

    public function start() {
        if (is_dir($this->dir)) {
            $this->_setFiles();
        } else {
            $this->files[] = $this->dir;
        }
        $this->_startNest();
    }

    private function _startNest() {
        foreach ($this->files as $file) {
            $pathInfo = pathinfo($file);
            if (in_array($pathInfo['extension'], $this->exts)) {
                file_put_contents($this->dir . '/books.txt', $pathInfo['basename'] . "\n", FILE_APPEND);
            }
        }
        echo "写入成功 共" . count($this->files) . "本书\n";
    }

    private function _setFiles($filePath = '') {
        if (!$filePath) {
            $filePath = $this->dir;
        }
        if ($dh = opendir($filePath)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                $subFilePath = $filePath . '/' . $file;
                if (is_dir($subFilePath)) {
                    $this->_setFiles($subFilePath);
                } else {
                    $this->files[] = $subFilePath;
                }
            }
            closedir($dh);
        }
    }

}

//test
$n = new Writebook();
$n->setDir('G:\GoogleDrive\tmp'); //设置模版路径或者文件路径
$n->start();
