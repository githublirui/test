<?php

define('WATERMARK_OVERLAY_IMAGE', 'logo2.png'); // add your watermark image path here
define('WATERMARK_OVERLAY_OPACITY', 50);
define('WATERMARK_OUTPUT_QUALITY', 100);
ini_set('display_errors', 1);
error_reporting(E_ALL);

function create_watermark($source_file_path, $output_file_path) {
    list($source_width, $source_height, $source_type) = getimagesize($source_file_path);
    if ($source_type === NULL) {
        return false;
    }
    switch ($source_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_file_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_file_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_file_path);
            break;
        default:
            return false;
    }
    $overlay_gd_image = imagecreatefrompng(WATERMARK_OVERLAY_IMAGE);
    $overlay_width = imagesx($overlay_gd_image);
    $overlay_height = imagesy($overlay_gd_image);
    $s_w = 29;
    $s_h = 14;
    imagecopymerge(
            $source_gd_image, $overlay_gd_image, $source_width - $overlay_width - $s_w, $source_height - $overlay_height - $s_h, 0, 0, $overlay_width, $overlay_height, 100
    );
    imagejpeg($source_gd_image, $output_file_path, 60);
    imagedestroy($source_gd_image);
    imagedestroy($overlay_gd_image);
}

/*
 * Uploaded file processing function
 */

define('UPLOADED_IMAGE_DESTINATION', './');
define('PROCESSED_IMAGE_DESTINATION', './');

function process_image_upload($Field) {
    $temp_file_path = $_FILES[$Field]['tmp_name'];
    $temp_file_name = $_FILES[$Field]['name'];
    list(,, $temp_type) = getimagesize($temp_file_path);
    if ($temp_type === NULL) {
        return false;
    }
    switch ($temp_type) {
        case IMAGETYPE_GIF:
            break;
        case IMAGETYPE_JPEG:
            break;
        case IMAGETYPE_PNG:
            break;
        default:
            return false;
    }
    $uploaded_file_path = UPLOADED_IMAGE_DESTINATION . $temp_file_name;
    $processed_file_path = PROCESSED_IMAGE_DESTINATION . preg_replace('///.[^//.]+$/', '.jpg', $temp_file_name);
    move_uploaded_file($temp_file_path, $uploaded_file_path);
    $result = create_watermark($uploaded_file_path, $processed_file_path);
    if ($result === false) {
        return false;
    } else {
        return array($uploaded_file_path, $processed_file_path);
    }
}

#遍历
$base_path = './images/anjuke';
$base_path = realpath($base_path);

$list = scandir($base_path); // 得到该文件下的所有文件和文件夹
foreach ($list as $file) { //遍历
    if ($file != "." && $file != "..") { //判断是不是文件夹
        $file_c_location = $base_path . DIRECTORY_SEPARATOR . $file; //生成路径
        create_watermark($file_c_location, $file_c_location);
        echo '.';
        flush();
    }
}
?>

