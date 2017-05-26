<?php

class UtilsFile{
    
    public static function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!self::deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!self::deleteDirectory($dir . "/" . $item)) return false;
            };
        }
        return rmdir($dir);
    }     

    public static function deleteDirectoryFiles($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!self::deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!self::deleteDirectory($dir . "/" . $item)) return false;
            };
        }
        return true;
    } 
        
    public static function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst, 0777, true);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    
    
    /**
     * Get the directory size
     * @param directory $directory
     * @return integer
     */
    public static function dirSize($directory) {
        $size = 0;
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
            $size+=$file->getSize();
        }
        return $size;
    }     
    
    public static function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }
    

    public static function format_kbytes($size) {
        $units = array(' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }    

    public static function dircopy($src_dir, $dst_dir, $UploadDate = false, $verbose = false, $use_cached_dir_trees = false) {
        static $cached_src_dir;
        static $src_tree;
        static $dst_tree;
        $num = 0;

        if (($slash = substr($src_dir, - 1)) == "\\" || $slash == "/")
            $src_dir = substr($src_dir, 0, strlen($src_dir) - 1);
        if (($slash = substr($dst_dir, - 1)) == "\\" || $slash == "/")
            $dst_dir = substr($dst_dir, 0, strlen($dst_dir) - 1);
        if (!$use_cached_dir_trees || !isset($src_tree) || $cached_src_dir != $src_dir) {
            $src_tree = self::get_dir_tree($src_dir, true, $UploadDate);
            $cached_src_dir = $src_dir;
            $src_changed = true;
        }
        if (!$use_cached_dir_trees || !isset($dst_tree) || $src_changed)
            $dst_tree = self::get_dir_tree($dst_dir, true, $UploadDate);
        if (!is_dir($dst_dir)) {
            @mkdir($dst_dir, 0777, true);
        }

        foreach ($src_tree as $file => $src_mtime) {
            if (!isset($dst_tree[$file]) && $src_mtime === false) {
                @ mkdir("$dst_dir/$file");
            } elseif (!isset($dst_tree[$file]) && $src_mtime || isset($dst_tree[$file]) && $src_mtime > $dst_tree[$file]) {
                if (copy("$src_dir/$file", "$dst_dir/$file")) {
                    if ($verbose)
                        echo "Copied '$src_dir/$file' to '$dst_dir/$file'<br>\r\n";
                    touch("$dst_dir/$file",$src_mtime);
                    $num++;
                } else
                    echo "<font color='red'>File '$src_dir/$file' could not be copied!</font><br>\r\n";
            }
        }
        return $num;
    }
    
    public static function get_dir_tree($dir, $root = true, $UploadDate) {
        static $tree;
        static $base_dir_length;

        if ($root) {
            $tree = array();
            $base_dir_length = strlen($dir) + 1;
        }

        if (is_file($dir)) {
            if ($UploadDate != false) {
                if (filemtime($dir) > strtotime($UploadDate))
                    $tree[substr($dir, $base_dir_length)] = date('Y-m-d H:i:s', filemtime($dir));
            }else
                $tree[substr($dir, $base_dir_length)] = date('Y-m-d H:i:s', filemtime($dir));
        }elseif ((is_dir($dir) && substr($dir, - 4) != ".svn") && $di = dir($dir)) {
            if (!$root)
                $tree[substr($dir, $base_dir_length)] = false;
            while (($file = $di->read()) !== false)
                if ($file != "." && $file != "..")
                    self::get_dir_tree("$dir/$file", false, $UploadDate);
            $di->close();
        }
        if ($root)
            return $tree;
    }
}
