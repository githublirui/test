<?php

class UtilsImage {

    public static function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {

        // Get dimensions of existing image
        $image = getimagesize($src_image);

        // Check for valid dimensions
        if ($image[0] <= 0 || $image[1] <= 0)
            return false;

        // Determine format from MIME-Type
        $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

        // Import image
        switch ($image['format']) {
            case 'jpg':
            case 'jpeg':
                $image_data = imagecreatefromjpeg($src_image);
                break;
            case 'png':
                $image_data = imagecreatefrompng($src_image);
                break;
            case 'gif':
                $image_data = imagecreatefromgif($src_image);
                break;
            default:
                // Unsupported format
                return false;
                break;
        }

        // Verify import
        if ($image_data == false)
            return false;

        // Calculate measurements
        if ($image[0] & $image[1]) {
            // For landscape images
            $x_offset = ($image[0] - $image[1]) / 2;
            $y_offset = 0;
            $square_size = $image[0] - ($x_offset * 2);
        } else {
            // For portrait and square images
            $x_offset = 0;
            $y_offset = ($image[1] - $image[0]) / 2;
            $square_size = $image[1] - ($y_offset * 2);
        }

        // Resize and crop
        $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
        if (imagecopyresampled(
                        $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
                )) {

            // Create thumbnail
            switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg($canvas, $dest_image, $jpg_quality);
                    break;
                case 'png':
                    return imagepng($canvas, $dest_image);
                    break;
                case 'gif':
                    return imagegif($canvas, $dest_image);
                    break;
                default:
                    // Unsupported format
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }

    public static function resize($img, $thumb_width, $newfilename = null) {
        $max_width = $thumb_width;

        //Check if GD extension is loaded
        if (!extension_loaded('gd') && !extension_loaded('gd2')) {
            trigger_error("GD is not loaded", E_USER_WARNING);
            return false;
        }

        //Get Image size info
        list($width_orig, $height_orig, $image_type) = getimagesize($img);

        switch ($image_type) {
            case 1: $im = imagecreatefromgif($img);
                break;
            case 2: $im = imagecreatefromjpeg($img);
                break;
            case 3: $im = imagecreatefrompng($img);
                break;
            default: trigger_error('Unsupported filetype!', E_USER_WARNING);
                break;
        }

        /*         * * calculate the aspect ratio ** */
        $aspect_ratio = (float) $height_orig / $width_orig;

        /*         * * calulate the thumbnail width based on the height ** */
        $thumb_height = round($thumb_width * $aspect_ratio);


        while ($thumb_height > $max_width) {
            $thumb_width-=1;
            $thumb_height = round($thumb_width * $aspect_ratio);
        }

        $newImg = imagecreatetruecolor($thumb_width, $thumb_height);

        /* Check if this image is PNG or GIF, then set if Transparent */
        if (($image_type == 1) OR ($image_type == 3)) {
            imagealphablending($newImg, false);
            imagesavealpha($newImg, false);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $thumb_width, $thumb_height, $transparent);
        }
        imagecopyresampled($newImg, $im, 0, 0, 0, 0, $thumb_width, $thumb_height, $width_orig, $height_orig);

        //Generate the file, and rename it to $newfilename
        switch ($image_type) {
            case 1: imagegif($newImg, $newfilename);
                break;
            case 2: imagejpeg($newImg, $newfilename);
                break;
            case 3: imagepng($newImg, $newfilename);
                break;
            default: trigger_error('Failed resize image!', E_USER_WARNING);
                break;
        }

        return $newfilename;
    }

    public static function deleteDirectory($dir) {
        if (!file_exists($dir))
            return true;
        if (!is_dir($dir) || is_link($dir))
            return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..')
                continue;
            if (!UtilsImage::deleteDirectory($dir . "/" . $item)) {
                chmod($dir . "/" . $item, 0777);
                if (!UtilsImage::deleteDirectory($dir . "/" . $item))
                    return false;
            };
        }
        return rmdir($dir);
    }

    public static function smartImageSizeHtml($image_path, $max_size) {
        list($new_width, $new_height) = self::smartImageSize($image_path, $max_size);
        return 'width="' . $new_width . '" height="' . $new_height . '"';
    }

    public static function smartImageSize($image_path, $max_size) {
        if (!file_exists($image_path)) {
            $new_height = $max_size;
            $new_width = $max_size;
        } else {
            $infos = getimagesize($image_path);
            $width = $infos[0];
            $height = $infos[1];
            if ($width > $height) {
                $new_height = $max_size * ($height / $width);
                $new_width = $max_size;
            } else {
                $new_width = $max_size * ($width / $height);
                $new_height = $max_size;
            }
        }
        return array($new_width, $new_height);
    }

    public static function smartImageSizeHtmlNoEnlargePdf($image_path, $max_size) {
        list($new_width, $new_height) = self::smartImageSizeNoEnlarge($image_path, $max_size);
        return 'width="' . $new_width * 0.87 . '" height="' . $new_height * 0.87 . '"';
    }

    public static function smartImageSizeHtmlNoEnlarge($image_path, $max_size) {
        list($new_width, $new_height) = self::smartImageSizeNoEnlarge($image_path, $max_size);
        return 'width="' . $new_width . '" height="' . $new_height . '"';
    }

    public static function smartImageSizeNoEnlarge($image_path, $max_size) {
        if (!file_exists($image_path)) {
            $new_height = $max_size;
            $new_width = $max_size;
        } else {
            $infos = getimagesize($image_path);
            $width = $infos[0];
            $height = $infos[1];
            if ($width < $max_size && $height < $max_size) {
                $new_height = $height;
                $new_width = $width;
            } else {
                if ($width > $height) {
                    $new_height = $max_size * ($height / $width);
                    $new_width = $max_size;
                } else {
                    $new_width = $max_size * ($width / $height);
                    $new_height = $max_size;
                }
            }
        }
        return array($new_width, $new_height);
    }

    public static function resizeImageForStyleAndOrderMainImage($filename, $max_width, $max_height = '', $newfilename = "", $withSampling = true) {
        if ($newfilename == "")
            $newfilename = $filename;
        // Get new sizes
        list($width, $height) = getimagesize($filename);
        // -- dont resize if the width of the image is smaller or equal than the new size.
        if ($width <= $max_width)
            $max_width = $width;

        $percent = $max_width / $width;

        $newwidth = $width * $percent;
        if ($max_height == '') {
            $newheight = $height * $percent;
        }
        else
            $newheight = $max_height;
        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $ext = strtolower(self::getFileExtension($filename));

        if ($ext == 'jpg' || $ext == 'jpeg')
            $source = imagecreatefromjpeg($filename);
        if ($ext == 'gif')
            $source = imagecreatefromgif($filename);
        if ($ext == 'png')
            $source = imagecreatefrompng($filename);
        // Resize
        if ($withSampling)
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        else
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // Output
        if ($ext == 'jpg' || $ext == 'jpeg')
            return imagejpeg($thumb, $newfilename);
        if ($ext == 'gif')
            return imagegif($thumb, $newfilename);
        if ($ext == 'png')
            return imagepng($thumb, $newfilename);
    }

    public static function getFileExtension($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    /**
      //裁切图片函数
     * $srcFile - 图形文件
     * $pixel - 尺寸大小，如：400*300
     * $_quality - 图片质量，默认100
     * $cut - 是否裁剪，默认1，当$cut=0的时候，将不进行裁剪
     * $cache - 如果有缓存，是否直接用缓存，默认true
     * 示例："< img src=\"".MiniImg('images/image.jpg','300*180',100,1)."\">"
     * MiniImg("1.jpg","100*200");
     */
    public static function MiniImg($srcFile, $pixel, $target_file, $_quality = 100, $cut = 1, $cache = false) {
        if ($srcFile == ".")
            return;

        $pixelInfo = explode('*', $pixel);
        $pathInfo = pathinfo($srcFile);
        $_cut = intval($cut);
        $searchFileName = preg_replace("/\.([A-Za-z0-9]+)$/isU", "_" . $pixelInfo[0] . "x" . $pixelInfo[1] . "_" . $cut . ".\\1", $pathInfo['basename']);
        //$miniFile = $pathInfo['dirname'].'/'.$searchFileName;
        $ShowTime4 = date("Y-m-d H:i:s");
        $Pic_FileName = strtotime($ShowTime4) . rand(1, 10);

        $miniFile = $target_file;  //改成替换原图

        if ($cache and file_exists($miniFile))
            return $miniFile;

        $data = GetImageSize($srcFile);
        $FuncExists = 1;
        switch ($data[2]) {
            case 1:   //gif
                if (function_exists('ImageCreateFromGIF'))
                    $_im = ImageCreateFromGIF($srcFile);
                break;
            case 2:   //jpg
                if (function_exists('imagecreatefromjpeg'))
                    $_im = imagecreatefromjpeg($srcFile);
                break;
            case 3:   //png
                if (function_exists('ImageCreateFromPNG'))
                    $_im = ImageCreateFromPNG($srcFile);
                break;
//            case 6:   //bmp，这里需要用到ImageCreateFromBMP
//                $_im = ImageCreateFromBMP($srcFile);
//                break;
        }
        if (!$_im)
            return $srcFile;
        $sizeInfo['width'] = @imagesx($_im);
        $sizeInfo['height'] = @imagesy($_im);
        if (!$sizeInfo['width'] or !$sizeInfo['height'])
            return $srcFile;
        if ($sizeInfo['width'] == $pixelInfo[0] && $sizeInfo['height'] == $pixelInfo[1]) {
            return $srcFile;
        } elseif ($sizeInfo['width'] < $pixelInfo[0] && $sizeInfo['height'] < $pixelInfo[1] && $miniMode == '2') {
            return $srcFile;
        } else {
            $resize_ratio = ($pixelInfo[0]) / ($pixelInfo[1]);
            $ratio = ($sizeInfo['width']) / ($sizeInfo['height']);
            if ($cut == 1) {
                $newimg = imagecreatetruecolor($pixelInfo[0], $pixelInfo[1]);
                if ($ratio >= $resize_ratio) {          //高度优先
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], $pixelInfo[1], (($sizeInfo['height']) * $resize_ratio), $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                } else {               //宽度优先
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], $pixelInfo[1], $sizeInfo['width'], (($sizeInfo['width']) / $resize_ratio));
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                }
            } else {                //不裁图
                if ($ratio >= $resize_ratio) {
                    $newimg = imagecreatetruecolor($pixelInfo[0], ($pixelInfo[0]) / $ratio);
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, $pixelInfo[0], ($pixelInfo[0]) / $ratio, $sizeInfo['width'], $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                } else {
                    $newimg = imagecreatetruecolor(($pixelInfo[1]) * $ratio, $pixelInfo[1]);
                    imagecopyresampled($newimg, $_im, 0, 0, 0, 0, ($pixelInfo[1]) * $ratio, $pixelInfo[1], $sizeInfo['width'], $sizeInfo['height']);
                    $_result = ImageJpeg($newimg, $miniFile, $_quality);
                }
            }

            ImageDestroy($_im);
            ImageDestroy($newimg);
            if ($_result)
                return $miniFile;
            return $srcFile;
        }
    }

}