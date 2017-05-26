<?php

include '../lib/UtilsImage.class.php';

/**
 * 遍历文件夹下所有
 * 
 */
$base_path = './upload';
resizeDir($base_path);

function resizeDir($base_path) {
    $base_path = realpath($base_path);
    #遍历文件夹找class
    $list = scandir($base_path); // 得到该文件下的所有文件和文件夹
    foreach ($list as $file) { //遍历
        $file_c_location = $base_path . DIRECTORY_SEPARATOR . $file;
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (is_dir($file_c_location)) {
            resizeDir($file_c_location);
        } else if (is_file($file_c_location)) {
            $image_info = getimagesize($file_c_location);

            if ($image_info[0] <= 600) {
                continue;
            }
            resizeImage($file_c_location);
            echo '.';
            flush();
        }
    }
}

function resizeImage($path) {
    UtilsImage::resize($path, 600, $path);
}
