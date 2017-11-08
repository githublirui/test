<?php

/**
 * B2S嵌套模版 替换css image js script
 * @author lirui
 * $n = new NestTemplate();
 * $n->setDir('G:/projects/lirui_dev/51talk/App/B2s/View/Agent/daily/meeting.html'); //设置模版路径或者文件路径
 * $n->start();
 */
class NestTemplate {

    private $dir = '';
    private $files = [];

    public function setDir($dir) {
        $this->dir = $dir;
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
            $fileSource = fopen($file, "r");
            $fileContent = '';
            while (!feof($fileSource)) {
                $line = fgets($fileSource);
                if (!trim($line)) {
                    continue;
                }
                $fileContent .= $this->_replace($line);
            }
            fclose($fileSource);
            file_put_contents($file, $fileContent);
            echo "{$file} 嵌套成功\n";
        }
    }

    private function _replace($content) {
        $pattern = "/\<title\>(.*?)\<\/title\>/is";
        
        if (preg_match($pattern, $content, $match)) {
            return str_replace($match[1], '互联网+外教进校工程', $content);
        }
        
        if (strpos($content, 'stylesheet') !== false) {
            $pattern = "/href\s*=[\'\"](.*?)[\'\"]/is";
            preg_match($pattern, $content, $match);
            $tmp = str_replace('../../../', '', $match[1]);
            $tmp = str_replace('.css', '', $tmp);
            return "{linkb2s type='new_css' name='{$tmp}'}\n";
        }
        
        if (strpos($content, '<img') !== false && strpos($content, 'link') === false) {
            $pattern = "/src\s*=[\'\"](.*?)[\'\"]/is";
            preg_match($pattern, $content, $match);
            $tmp = str_replace('../../../', '', $match[1]);
            return str_replace($match[1], "{linkb2s type='new_images' name='{$tmp}'}", $content);
        }
        
        if (strpos($content, 'src') !== false && //
                strpos($content, '<script') !== false && //
                strpos($content, 'link') === false) {
            $pattern = "/src\s*=[\'\"](.*?)[\'\"]/is";
            preg_match($pattern, $content, $match);
            $tmp = str_replace('../../../', '', $match[1]);
            return "{linkb2s type='new_js' name='{$tmp}'}\n";
        }
        
        if (strpos($content, '<script') !== false) {
            return "{literal}\n" . $content;
        }
        
        if (strpos($content, '</script>') !== false) {
            return $content . "{/literal}\n";
        }
        return $content;
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
$n = new NestTemplate();
$n->setDir('G:/projects/lirui_dev/51talk/App/B2s/View/Agent/daily/test.html'); //设置模版路径或者文件路径
$n->start();
